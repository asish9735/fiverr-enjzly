<ul class="dashboard-box-list">
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$seller_user_name=getUserName($orderDetails->seller_id);
$buyer_user_name=getUserName($orderDetails->buyer_id);
if($orderconversations){
	foreach($orderconversations as $k=>$conversation){
		if($conversation->sender_id==$orderDetails->seller_id){
			$sender_user_name=$seller_user_name;
		}elseif($conversation->sender_id==$orderDetails->buyer_id){
			$sender_user_name=$buyer_user_name;
		}else{
			$sender_user_name='';
		}
		if($conversation->status == "message"){ 
?>
        <li>
            <!-- Job Listing -->
            <div class="job-listing">
                <!-- Job Listing Details -->
                <div class="job-listing-details">
                	<!-- Logo -->
						<div class="job-listing-user-logo">
							<img src="<?php D(getMemberLogo($conversation->sender_id)); ?>" alt="" height="48" width="48" class="rounded-circle">
						</div>

                    <!-- Details -->
                    <div class="job-listing-description">
                        <h4 class="job-listing-title mb-0"><a href="#"><?php /*D($conversation->member_name);*/ D($sender_user_name);?></a></h4>
                        <p><?php D($conversation->message); ?></p>
        				<p class="mt-0">							
                            <?php if(!empty($conversation->file)){ ?>
                                <a target="_blank" href="<?php D(URL_USERUPLOAD.'conversation-files/'.$conversation->file)?>" class="text-dark" download>
                                    <i class="icon-feather-download"></i> <?php echo $conversation->file; ?>
                                </a>
                                
                            <?php }?>
                        </p>
                        <!-- Job Listing Footer -->
                        <div class="job-listing-footer">
                            <ul>
                                <li><i class="icon-feather-calendar"></i>
                                <?php D(dateFormat($conversation->date,'M d, Y').' '.date('H:i', strtotime($conversation->date))); ?> 
		<?php if($loggedUser['MID']!= $conversation->sender_id){ ?></li>
		 <li><a href="#" data-toggle="modal" data-target="#report-modal" class="text-muted"><i class="fa fa-flag"></i> <?php D(__('order_conversation_page_Report',"Report"));?></a> 
		<?php } ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>                    
        </li>
        	
	
<?php	
		}
		elseif($conversation->status == "delivered"){	
		$order_complete_time = new DateTime($orderDetails->complete_time);
		$remain = $order_complete_time->diff(new DateTime());
		if($remain->d < 1){ $remain->d = 1; }
?>
	<div class="card mt-4">
		<div class="card-body">
		<h5 class="text-center"><i class="fa fa-archive"></i> <?php D(__('order_conversation_page_Order_Delivered',"Order Delivered"));?></h5>
		  <?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>
		  <p class="text-center font-weight-bold pb-0"><?php D(__('order_conversation_page_The_buyer_has',"The buyer has"));?> <?php echo $remain->d; ?> <?php D(__('order_conversation_page_days_to_complete',"day(s) to complete/respond to this order, otherwise it will be automatically marked as completed."));?></p>
		  <?php } else { ?>
		   <p class="text-center font-weight-bold pb-0"><?php D(__('order_conversation_page_You_have',"You have"));?> <?php echo $remain->d; ?> <?php D(__('order_conversation_page_days_to_complete',"day(s) to complete/respond to this order, otherwise it will be automatically marked as completed."));?></p>
		  <?php } ?>
		</div>
	</div>
    
    <li class="<?php D(($conversation->sender_id==$loggedUser['MID']? 'message-div-hover':' message-div'))?>">
    <div class="job-listing">
    <div class="job-listing-details">
    	<div class="job-listing-company-logo">
            <img src="<?php D(getMemberLogo($conversation->sender_id)); ?>" class="img-fluid" alt="" height="64" width="64">
        </div>		
        <div class="job-listing-description">
            <h3 class="job-listing-title mb-0">
				<a href="#" class="seller-buyer-name"> <?php /*D($conversation->member_name);*/ D($sender_user_name);?> </a>
			</h3>
		<p class="mt-0">
			<?php D($conversation->message); ?>
			<?php if(!empty($conversation->file)){ ?>
				<a target="_blank" href="<?php D(URL_USERUPLOAD.'conversation-files/'.$conversation->file)?>" class="d-block mt-2 ml-1" download>
					<i class="fa fa-download"></i> <?php echo $conversation->file; ?>
				</a>
				
			<?php }?>
		</p>
        <div class="job-listing-footer">
            <ul>
                <li><small><i class="icon-feather-calendar"></i> <?php D(date('H:i',strtotime($conversation->date)).dateFormat($conversation->date,'F d,Y')); ?></small></li>
            </ul>
        </div>
        </div>
		
	</div>
    </div>
    </li>
    <!--- message-div Ends --->
	<?php
			if($orderDetails->order_status ==ORDER_DELIVERED){
				if($orderDetails->buyer_id==$loggedUser['MID']){
	?>
	<li>
		<form method="post" id="reportrequestForm" onsubmit="return performAction(this);return false;">
		    <input type="hidden" name="action" value="complete"/>
			<button name="complete" type="submit" class="btn btn-success saveBTN">
				<?php D(__('order_conversation_page_Accept_Review_Order',"Accept & Review Order"));?>
			</button>
			&nbsp;&nbsp;&nbsp;
			<button type="button" data-toggle="modal" data-target="#revision-request-modal" class="btn btn-success">
				<?php D(__('order_conversation_page_Request_A_Revison',"Request A Revison"));?>
			</button>
		</form>
		<?php 
		if(isset($_POST['complete'])){
		  //require_once("orderIncludes/orderComplete.php");
		}
		?>
	</li>
	<?php		
				}
			}
		
		}
		elseif($conversation->status == "revision"){
	?>
    <div class="headline">
		<h4><i class="fa fa-pencil-square-o"></i> <?php D(__('order_conversation_page_Revison_Requested_By',"Revison Requested By"));?> <?php /*D($conversation->member_name);*/ D($sender_user_name); ?> </h4>	   
	</div>
    <li class="<?php D(($conversation->sender_id==$loggedUser['MID']? 'message-div-hover':' message-div'))?>">
    <div class="job-listing">
    	<div class="job-listing-details">
    	<div class="job-listing-company-logo">
			<img src="<?php D(getMemberLogo($conversation->sender_id)); ?>" alt="" class="fluid-img" height="64" width="64">
        </div>
        <div class="job-listing-description">
	    <h3 class="job-listing-title mb-0"><a href="#"> <?php /*D($conversation->member_name);*/ D($sender_user_name);?> </a></h3>
		<p class="mt-0">
			<?php D($conversation->message); ?>
			<?php if(!empty($conversation->file)){ ?>
				<a target="_blank" href="<?php D(URL_USERUPLOAD.'conversation-files/'.$conversation->file)?>" class="d-block mt-2 ml-1" download>
					<i class="fa fa-download"></i> <?php echo $conversation->file; ?>
				</a>
				
			<?php }?>
		</p>
        <div class="job-listing-footer">
            <ul>
                <li><small><i class="icon-feather-calendar"></i> <?php D(date('H:i',strtotime($conversation->date)).dateFormat($conversation->date,'F d,Y')); ?></small></li>
            </ul>
        </div>
		
	</div>
    </div>
    </div>
	<?php			
		}
		elseif($conversation->status == "cancellation_request"){
	?>
    <div class="headline">
        <h4><i class="icon-feather-x text-danger"></i> <?php D(__('order_conversation_page_Cancellation_Requested_By',"Cancellation Requested By"));?> <?php /*D($conversation->member_name);*/ D($sender_user_name); ?></h4>
    </div>
	
	<li class="<?php D(($conversation->sender_id==$loggedUser['MID']? 'message-div-hover_':' message-div_'))?>"><!--- message-div Starts --->
    	<div class="job-listing">
    	<div class="job-listing-details">
    	<div class="job-listing-company-logo">
			<img src="<?php D(getMemberLogo($conversation->sender_id)); ?>" alt="" class="fluid-img" height="64" width="64">
        </div>
        <div class="job-listing-description">
	    <h3 class="job-listing-title mb-0"><a href="#"><?php /*D($conversation->member_name);*/ D($sender_user_name); ?></a></h3>
		<p class="mt-0">
        <?php D($conversation->message); ?>
			<?php if(!empty($conversation->file)){ ?>
				<a target="_blank" href="<?php D(URL_USERUPLOAD.'conversation-files/'.$conversation->file)?>" class="d-block mt-2 ml-1" download>
					<i class="fa fa-download"></i> <?php echo $conversation->file; ?>
				</a>
				
			<?php }?>
		</p>
        <div class="job-listing-footer">
            <ul>
                <li><small><i class="icon-feather-calendar"></i> <?php D(date('H:i',strtotime($conversation->date)).dateFormat($conversation->date,'F d,Y')); ?></small></li>
            </ul>
        </div>
		
	</div>
    </div>
    	<?php if($conversation->sender_id != $loggedUser['MID']){?>
		<form class="mb-2"  method="post" id="reportrequestForm" >
			<center>
				<button name="accept_request" id="accept_request" type="button" class="btn btn-success btn-sm" onclick="return performAction(this);return false;"><?php D(__('order_conversation_page_Accept_Request',"Accept Request"));?></button>
				<button name="decline_request" id="decline_request" type="button" class="btn btn-danger btn-sm" onclick="return performAction(this);return false;"><?php D(__('order_conversation_page_Decline_Request',"Decline Request"));?></button>
		   </center>
		</form>
		<?php }?>
    </div>
		
	</li>
	<?php		
		}
		elseif($conversation->status == "decline_cancellation_request"){
	?>
	<div class="card mt-4">
	   <div class="card-body">
	   	<h5 class="text-center">
	   		<i class="fa fa-trash-o"></i> <?php D(__('order_conversation_page_Cancellation_Request_Declined_By',"Cancellation Request Declined By"));?> <?php /*D($conversation->member_name);*/ D($sender_user_name); ?>
	   	</h5>
	   </div>
	</div>
	<div class="<?php D(($conversation->sender_id==$loggedUser['MID']? 'message-div-hover':' message-div'))?>"><!--- message-div Starts --->
		<img src="<?php D(getMemberLogo($conversation->sender_id)); ?>" width="50" height="50" class="message-image">
	    <h5>
			<a href="#" class="seller-buyer-name"> <?php /*D($conversation->member_name);*/ D($sender_user_name); ?> </a>
		</h5>
		<p class="message-desc">
			<?php D($conversation->message); ?>
			<?php if(!empty($conversation->file)){ ?>
				<a target="_blank" href="<?php D(URL_USERUPLOAD.'conversation-files/'.$conversation->file)?>" class="d-block mt-2 ml-1" download>
					<i class="fa fa-download"></i> <?php echo $conversation->file; ?>
				</a>
				
			<?php }?>
		</p>

		<p class="text-right text-muted mb-0" style="font-size: 14px;"> 
		<?php D(date('H:i',strtotime($conversation->date)).dateFormat($conversation->date,'F d,Y')); ?>
		</p>
	</div>
	<div class="order-status-message"><!--- order-status-message Starts --->
		<i class="fa fa-times fa-3x text-danger"></i>
		<h5 class="text-danger">
		<?php D(__('order_conversation_page_Cancellation_Request_Declined_By',"Cancellation Request Declined By"));?> <?php D(($conversation->sender_id==$orderDetails->seller_id ? __('order_conversation_page_Buyer','Buyer'):__('order_conversation_page_Freelancer','Freelancer'))); ?>
		</h5>
	</div>
	
	<?php		
		}
		elseif($conversation->status == "accept_cancellation_request"){
	?>
	<div class="card mt-4">
	   <div class="card-body">
	   	<h5 class="text-center">
	   	<i class="fa fa-trash-o"></i> <?php D(__('order_conversation_page_Cancellation_Request_By',"Cancellation Request By"));?> <?php /*D($conversation->member_name);*/ D($sender_user_name); ?>
	   	</h5>
	   </div>
	</div>
	<div class="<?php D(($conversation->sender_id==$loggedUser['MID']? 'message-div-hover':' message-div'))?>"><!--- message-div Starts --->
		<img src="<?php D(getMemberLogo($conversation->sender_id)); ?>" width="50" height="50" class="message-image">
	    <h5>
			<a href="#" class="seller-buyer-name"> <?php /*D($conversation->member_name);*/ D($sender_user_name);  ?> </a>
		</h5>
		<p class="message-desc">
			<?php D($conversation->message); ?>
			<?php if(!empty($conversation->file)){ ?>
				<a target="_blank" href="<?php D(URL_USERUPLOAD.'conversation-files/'.$conversation->file)?>" class="d-block mt-2 ml-1" download>
					<i class="fa fa-download"></i> <?php echo $conversation->file; ?>
				</a>
				
			<?php }?>
		</p>
		<p class="text-right text-muted mb-0" style="font-size: 14px;"> 
		<?php D(date('H:i',strtotime($conversation->date)).dateFormat($conversation->date,'F d,Y')); ?>
		</p>
	</div>
		<?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>
	<div class="order-status-message"><!-- order-status-message Starts --->
		<i class="fa fa-times fa-3x text-danger"></i>
		<h5 class="text-danger"> <?php D(__('order_conversation_page_Order_Cancelled_By_Mutual_Agreement',"Order Cancelled By Mutual Agreement."));?> </h5>
		<p>
			<?php D(__('order_conversation_page_Order_Cancelled_By_Mutual_Agreement_info_seller',"Order Was Cancelled By A Mutual Agreement Between You and Your Buyer. <br>Funds have been refunded to buyer's account."));?>
		</p>
	</div><!-- order-status-message Ends --->
		<?php }else{ ?>
	<div class="order-status-message"><!-- order-status-message Starts --->
		<i class="fa fa-times fa-3x text-danger"></i>
		<h5 class="text-danger">  <?php D(__('order_conversation_page_Order_Cancelled_By_Mutual_Agreement',"Order Cancelled By Mutual Agreement."));?> </h5>
		<p>
			<?php D(__('order_conversation_page_Order_Cancelled_By_Mutual_Agreement_info_buyer',"Order was cancelled by a mutual agreement between you and your freelancer.<br>The order funds have been refunded to your Shopping Balance."));?>
		</p>
	</div><!-- order-status-message Ends --->
		<?php 
			}
		}
		elseif($status == "cancelled_by_customer_support"){
			if($seller_id == $login_seller_id){ ?>
	<div class="order-status-message"><!-- order-status-message Starts --->
		<i class="fa fa-times fa-3x text-danger"></i>
		<h5 class="text-danger"> <?php D(__('order_conversation_page_Order_Cancelled_By_Admin',"Order Cancelled By Admin."));?> </h5>
		<p>
			<?php D(__('order_conversation_page_Order_payment_refunded',"Payment For This Order Was Refunded To Buyer's Shopping Balance."));?> <br>
			<?php D(__('order_conversation_page_Order_payment_refunded_Further_Assistance',"For Any Further Assistance, Please Contact Our"));?> <a target="_blank" href="<?php D(get_link('CustomerSupportURL'))?>" class="link"> <?php D(__('order_conversation_page_Customer_Support',"Customer Support."));?></a>
		</p>
	</div><!-- order-status-message Ends --->
<?php }else{ ?>
	<div class="order-status-message"><!-- order-status-message Starts --->
		<i class="fa fa-times fa-3x text-danger"></i>
		<h5 class="text-danger"> <?php D(__('order_conversation_page_Order_Cancelled_By_Customer_Support',"Order Cancelled By Customer Support."));?> </h5>
		<p>
			<?php D(__('order_conversation_page_Payment_refunded_to_your',"Payment For This Order Has Been Refunded To Your"));?>
			<a href="<?php D(get_link('revenueURL'))?>" class="link"> <?php D(__('order_conversation_page_Shopping_balance',"Shopping balance."));?> </a>
		</p>
	</div><!-- order-status-message Ends --->
		<?php 
			} 
		}
	}
}
?>
</ul>