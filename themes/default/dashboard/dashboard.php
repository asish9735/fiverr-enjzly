<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//dd($member_details,true);
$orderStatus=array(
  '1'=>__('global_Order_Status_Pending','Pending'),
  '2'=>__('global_Order_Status_Progress','Progress'),
  '3'=>__('global_Order_Status_Revision','Revision requested'),
  '4'=>__('global_Order_Status_Cancellation','Cancellation requested'),
  '5'=>__('global_Order_Status_Cancelled','Cancelled'),
  '6'=>__('global_Order_Status_Delivered','Delivered'),
  '7'=>__('global_Order_Status_Completed','Completed'),
);
$s_currency=CURRENCY;

?>
<div class="breadcrumbs">
  <div class="container-fluid">
  <div class="row">  	
	<div class="col-sm-6">
  	<h1><?php D(__('','Dashboard'));?></h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php D(VPATH);?>">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    </div>
    <div class="col-sm-6 text-sm-right">                  
      <a href="<?php D(get_link('postrequestURL')); ?>" class="btn btn-outline-dark mr-3"><i class="icon-line-awesome-hand-stop-o"></i> <?php D(__('Post_Requests','Post Request'))?></a>
      <a href="<?php D(get_link('postproposalURL')); ?>" class="btn btn-dark"><i class="icon-line-awesome-mouse-pointer"></i> Post Gigs</a>
    </div>
  </div>
  </div>
</div>
<section class="section">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-3 col-lg-auto col-12">
        <?php /*?>
		    <div class="card contacts-sidebar_" style="border-bottom:0">
          <div class="card-header bg-white">
            <h4 class="mb-0"><i class="icon-feather-smartphone text-site"></i>
              <?php D(__('dashboard_page_My_Contacts','My Contacts'));?>
            </h4>
          </div>
          <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs">
              <li class="nav-item"> <a href="#my_buyers" data-toggle="tab" class="nav-link make-black active ">
                <?php D(__('dashboard_page_My_Buyers','My Buyers'));?>
                </a> </li>
              <li class="nav-item"> <a href="#my_sellers" data-toggle="tab" class="nav-link make-black">
                <?php D(__('dashboard_page_My_Freelancers','My Freelancers'));?>
                </a> </li>
            </ul>
          </div>
        </div>
        
        <div class="tab-content  mb-3">
          <div id="my_buyers" class="tab-pane fade show active">
            <ul class="list-group">
              <li class="list-group-item" style="border-top: 0">
                <h5 class="mb-0">
                  <?php D(__('dashboard_page_Buyer_Names','Buyer Names'));?>
                </h5>
              </li>
              <?php if($all_buyer){
				foreach($all_buyer as $buyer){
				$username=getUserName($buyer->member_id);
			  ?>
              <li class="list-group-item"> <img src="<?php D(getMemberLogo($buyer->member_id))?>" class="rounded-circle float-left" width="48" height="48">
                <div class="contact-title">
                  <h5 class="text-dark">
                    <?php D($username);?>
                    <!--<?php D($buyer->member_name);?>-->
                  </h5>
                  <a href="<?php D(get_link('viewprofileURL').$username) ; ?>" target="_blank" class="btn btn-sm btn-outline-site mb-2">
                  <?php D(__('dashboard_page_User_Profile','User Profile'));?>
                  </a> &nbsp; <a href="<?php D(get_link('messageLink').'/'.$buyer->member_id) ; ?>" target="_blank" class="btn btn-sm btn-outline-web mb-2">
                  <?php D(__('dashboard_page_Chat_History','Chat History'));?>
                  </a> </div>
              </li>
              <?php }
				}
				?>
            </ul>
          </div>
          <div id="my_sellers" class="tab-pane fade">
            <ul class="list-group">
              <li class="list-group-item" style="border-top: 0">
                <h5 class="mb-0">
                  <?php D(__('dashboard_page_Freelancer_Names','Freelancer Names'));?>
                </h5>
                <?php
					if($all_seller){

					foreach($all_seller as $seller){

						$username=getUserName($seller->member_id);

				?>
              <li class="list-group-item"> <img src="<?php D(getMemberLogo($seller->member_id))?>" class="rounded-circle float-left" width="48" height="48">
                <div class="contact-title">
                  <h5 class="text-dark">
                    <?php D($username);?>
                    <!--<?php D($seller->member_name);?>-->
                  </h5>
                  <a href="<?php D(get_link('viewprofileURL').$username) ; ?>" target="_blank" class="btn btn-sm btn-outline-site">
                  <?php D(__('dashboard_page_User_Profile','User Profile'));?>
                  </a> &nbsp; <a href="<?php D(get_link('messageLink').'/'.$seller->member_id) ; ?>" target="_blank" class="btn btn-sm btn-outline-web">
                  <?php D(__('dashboard_page_Chat_History','Chat History'));?>
                  </a> </div>
              </li>
              <?php

					}

				}

				?>
            </ul>
          </div>
        </div>
		
        <div class="card rounded-0 sticky-start mb-3 card_user ">
          <div class="card-body"> <img src="<?php D(theme_url().IMAGE)?>freelancer.png" class="img-fluid center-block" alt="none">
            <h4>
              <?php D(__('dashboard_page_Start_Freelancing','Start Freelancing'));?>
            </h4>
            <p>
              <?php D(__('dashboard_page_Start_Freelancing_text','Sell your services to millions of people all over the world.'));?>
            </p>
            <button onclick="location.href='<?php D(get_link('startsellingURL'))?>'" class="btn btn-site">
            <?php D(__('dashboard_page_GET_STARTED','Get Started'));?>
            </button>
          </div>
        </div>
        <?php */?>
        
        <?php 
            if($loggedUser && $this->router->fetch_class()!='home' && $this->router->fetch_class()!='category'){
            $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
            load_template($templateLayout,$data);
            }
        ?>
            
      </div>
      <div class="col-xl-9 col-lg col-12" style="overflow-x: hidden;">
      	<!-- Fun Facts Container -->
        <div class="fun-facts-container">
          <a href="<?php D(get_link('manageproposalURL'))?>" class="fun-fact text-white" data-fun-fact-color="#111">
                <div class="fun-fact-text">
                    <span>Active Gigs</span>
                    <h2><?php D($active_gigs); ?></h2>
                </div>
                <div class="fun-fact-icon"><i class="icon-material-outline-business-center"></i></div>
          </a>
           <!--  <a href="<?php D(get_link('revenueURL'))?>" class="fun-fact" data-fun-fact-color="#f4a716">
                <div class="fun-fact-text">
                    <h2><?php D($s_currency); ?><?php D(displayamount($member_details->month_earnings)); ?></h2>
                    <span><?php D(__('dashboard_page_Earnings_Month','Earnings (Month)'));?></span>
                </div>
                <div class="fun-fact-icon"><i class="icon-feather-dollar-sign"></i></div>
            </a> -->
            <a href="<?php D(get_link('managerequestURL'))?>" class="fun-fact text-dark" data-fun-fact-color="#fdd007">
                <div class="fun-fact-text">                    
                    
                    <span>Active Request</span>
                    <h2><?php D($active_request); ?></h2>
                </div>
                <div class="fun-fact-icon"><i class="icon-feather-eye"></i></div>
            </a>
           <!--  <div class="fun-fact" data-fun-fact-color="#d11458">
                <div class="fun-fact-text">                    
                    <h2>25</h2>
                    <span>Expired Jobs</span>
                </div>
                <div class="fun-fact-icon"><i class="icon-feather-trash"></i></div>
            </div> -->
            <a href="<?php D(get_link('sellingOrderURL').'?tab=completed')?>" class="fun-fact text-white" data-fun-fact-color="#111">
                <div class="fun-fact-text">                    
                    
                    <span><?php D(__('dashboard_page_Orders_Completed','Orders Completed'));?></span>
                    <h2><?php D($order_complete); ?></h2>
                </div>
                <div class="fun-fact-icon"><i class="icon-feather-check-circle"></i></div>
            </a>
                       
            <a href="<?php D(get_link('revenueURL'))?>" class="fun-fact text-dark" data-fun-fact-color="#fdd007">
                <div class="fun-fact-text">                    
                    <span><?php D(__('dashboard_page_Balance','Balance'));?></span>
                    
                    <h2><?php D($s_currency); ?><?php D(displayamount($member_details->balance)); ?></h2>
                    
                </div>
                <div class="fun-fact-icon"><i class="icon-feather-dollar-sign"></i></div>
            </a>
            
            <a href="<?php D(get_link('sellingOrderURL').'?tab=delivered')?>" class="fun-fact text-dark" data-fun-fact-color="#fdd007">
                <div class="fun-fact-text">                    
                    
                    <span><?php D(__('dashboard_page_Delivered_Orders','Delivered Orders'));?></span>
                    <h2><?php D($order_delivered); ?></h2>
                </div>
                <div class="fun-fact-icon"><i class="icon-feather-shopping-cart"></i></div>
            </a>

            <a href="<?php D(get_link('sellingOrderURL').'?tab=cancelled')?>" class="fun-fact text-white" data-fun-fact-color="#111">
                <div class="fun-fact-text">                    
                    
                    <span><?php D(__('dashboard_page_Orders_Cancelled','Orders Cancelled'));?></span>
                    <h2><?php D($order_cancelled); ?></h2>
                </div>
                <div class="fun-fact-icon"><i class="icon-feather-x-circle"></i></div>
            </a>
    
           <a href="<?php D(get_link('sellingOrderURL').'?tab=active')?>" class="fun-fact text-dark" data-fun-fact-color="#fdd007">

              <div class="fun-fact-text">
              <span><?php D(__('dashboard_page_Sales_In_Queue','Sales In Queue'));?></span>
              <h2><?php D($order_queue); ?></h2>                
              </div>
              <div class="fun-fact-icon"><i class="icon-feather-shopping-bag"></i></div>
              </a>
              <a href="<?php D(get_link('buyingOrderURL').'?tab=purchase')?>" class="fun-fact text-white" data-fun-fact-color="#111">
                    <div class="fun-fact-text"> 
                      <span>
                      <?php D(__('dashboard_page_Open_Purchases','Open Purchases'));?>
                      </span>
                      <h2>
                        <?php D($open_purchase); ?>
                      </h2>                      
                    </div>
                    <div class="fun-fact-icon"><i class="icon-feather-shopping-cart"></i></div>
                    </a>
               <?php /*?><?php */?>
        </div>
        <!-- Fun Facts Container / End -->		
        <div class="row">
            <div class="col-xl-6 col-12">
                <!-- Dashboard Box -->
                <div class="dashboard-box mb-4">
                    <div class="headline black">
                        <h3>Order Status</h3>                        
                    </div>
                    <div class="content">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="chartpie" width="500" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Dashboard Box / End -->
            </div>
            <div class="col-xl-6 col-12">
                <!-- Dashboard Box -->
                <div class="dashboard-box mb-4">
                    <div class="headline black">
                        <h3>Order Graph</h3>                        
                    </div>
                    <div class="content">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="chart" width="500" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Dashboard Box / End -->
            </div>
        </div>
        
        <?php /*?><div class="card mb-4 rounded-0_">
          <div class="card-body">
            <div class="row">
              <div class="col-sm align-self-center">
                <div class="submit-field">
                  <h5>
                    <?php D(__('dashboard_page_Country','Country'));?>
                  </h5>
                  <p>
                    <?php D($member_details->country_name); ?>
                  </p>
                </div>
                <div class="submit-field">
                  <h5>
                    <?php D(__('dashboard_page_Positive_Ratings','Positive Ratings'));?>
                  </h5>
                  <p>
                    <?php D($member_details->seller_rating); ?>
                    %</p>
                </div>
              </div>
              <div class="col-sm align-self-center">
                <div class="submit-field">
                  <h5>
                    <?php D(__('dashboard_page_Recent_Delivery','Recent Delivery'));?>
                  </h5>
                  <p>
                    <?php if($member_details->recent_delivery_date){D(dateFormat($member_details->recent_delivery_date,'F d, Y'));}else{D(__('dashboard_page_None','None'));} ?>
                  </p>
                </div>
                <div class="submit-field">
                  <h5>
                    <?php D(__('dashboard_page_Member_Since','Member Since'));?>
                  </h5>
                  <p>
                    <?php D(dateFormat($member_details->member_register_date,'F d, Y')); ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div><?php */?>
        <ul class="nav nav-pills">
          <li class="nav-item"> <a href="#notifications" data-toggle="tab" class="nav-link active">
            <?php D(__('dashboard_page_Notifications','Notifications'));?>
            <span class="badge badge-dark ml-1">
            <?php D($notification_count); ?>
            </span> </a> </li>
          <li class="nav-item"> <a href="#inbox" data-toggle="tab" class="nav-link">
            <?php D(__('dashboard_page_Messages','Messages'));?>
            <span class="badge badge-dark ml-1">
            <?php D($message_count); ?>
            </span> </a> </li>
        </ul>        
        <div class="card mb-4">          
          <div class="card-body p-0">
            <div class="tab-content message-tab">
              <div id="notifications" class="tab-pane fade show active">
                <?php
            	if($notifications){
            	foreach($notifications as $notification){
			    ?>
                <div class="message-list <?php if($notification->is_read != 1){ D("header-message-div-read"); }else{ D("header-message-div"); } ?>"><a href="<?php D(VZ); ?>" onclick="deleteNotification('<?php D($notification->notification_id); ?>')" class="btn btn-sm btn-outline-danger"> <i class="icon-feather-trash"></i> </a> <a class="media p-3 text-dark" href="<?php D(get_link('NotificationDetailsLink'))?><?php D($notification->notification_id); ?>"> <img src="<?php D(getMemberLogo($notification->sender_id))?>" width="48" height="48" class="rounded-circle mr-3">
                  <div class="media-body">
                    <h5 class="mb-0">
                      <?php if($notification->sender_id){/*D($notification->member_name);*/ D(getUserName($notification->sender_id));}else{D(__('dashboard_page_Admin','Admin'));} ?>
                    </h5>
                    <p class="message mb-0">
                      <?php D($notification->template_content);?>
                    </p>
                    <span class="text-muted date">
                    <i class="icon-feather-clock"></i> <?php D(date('H:i',strtotime($notification->notification_date)).' '.dateFormat($notification->notification_date,'F d,Y')); ?>
                    </span> </div>
                  </a> </div>
                <?php		

                }

              ?>
                <div class="text-center pb-4"> <a href="<?php D(get_link('NotificationListURL'));?>" class="btn btn-outline-site">
                  <?php D(__('dashboard_page_view_all','View All'));?>
                  </a> </div>
                <?php	

              }else{

              ?>
                <div class="text-center p-3">
                  <h5 class='text-muted'>
                    <?php D(__('dashboard_page_no_notification','No Notifications Are Available'));?>
                  </h5>
                </div>
                <?php	

                }

                ?>
              </div>
              <div id="inbox" class="tab-pane fade">
                <?php

            if($messages){

				foreach($messages as $message){

					if($message->sender_id){

						//$member_name=$message->member_name;

						$sender_user_name=getUserName($message->sender_id);

					}else{

						//$member_name='Admin';

						$sender_user_name=__('dashboard_page_Admin','Admin');

					}

				?>
                <div class="message-list <?php if($message->is_read != 1 && $message->message_sender!=$loggedUser['MID']){D('header-message-div-read_');}else{D('header-message-div_');}?>"> <a class="media p-3 text-dark" href="<?php D(get_link('MessageBoard').'/'.$message->conversations_id); ?>"> <img src="<?php D(getMemberLogo($message->sender_id)); ?>" width="50" height="50" class="rounded-circle mr-3">
                  <div class="media-body">
                    <h5 class="">
                      <?php /*D($member_name);*/ D($sender_user_name);?>
                    </h5>
                    <p class="message text-truncate mb-0">
                      <?php 

					if($message->offer_id){

						D(__('dashboard_page_Sent_you_an_offer','Sent you an offer'));

					}else{

						D($message->message);

					}

					?>
                    </p>
                    <span class="text-muted date">
                    <i class="icon-feather-clock"></i> <?php D(date('H:i ',strtotime($message->sending_date)).dateFormat($message->sending_date,'F d, Y')); ?>
                    </span> </div>
                  </a> </div>
                <?php	

				}

				?>
                <div class="text-center pb-4"> <a href="<?php D(get_link('MessageBoard'));?>" class="btn btn-outline-site">
                  <?php D(__('dashboard_page_view_all','View All'));?>
                  </a> </div>
                <?php

                }else{

                ?>
                <div class="text-center p-3">
                  <h5 class='text-muted'>
                    <?php D(__('dashboard_page_no_message','No Messages.'));?>
                  </h5>
                </div>
                <?php	

			}

            ?>
              </div>
            </div>
          </div>
        </div>
        <ul class="nav nav-pills">
              <li class="nav-item"> <a href="#myorder" data-toggle="tab" class="nav-link active">
                <?php D(__('dashboard_page_my_order','My Orders As Seller'));?>
                </a> </li>
              <li class="nav-item"> <a href="#mytask" data-toggle="tab" class="nav-link">
                <?php D(__('dashboard_page_my_task','My Orders As Buyer'));?>
                </a> </li>
               
            </ul>
        <div class="dashboard-box margin-top-0">
          
            <div class="tab-content">

              <div id="myorder" class="tab-pane fade show active">
              <?php 
                if($orders_as_seller){
              ?>
                <ul class="dashboard-box-list">
                <?php
                  foreach($orders_as_seller as $order){      
                ?>
                    <li>
                        <!-- Job Listing -->
                        <div class="job-listing">               
                            <!-- Job Listing Details -->
                            <div class="job-listing-details">
                                <!-- Logo -->
                                <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="job-listing-company-logo">
                                    <img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="">
                                </a>
                                <?php 
                                  $class='';
                                  if($order->order_status==1){
                                    $class='yellow';
                                  }elseif($order->order_status==2){
                                    $class='blue';
                                  }elseif($order->order_status==3){
                                    $class='';
                                  }elseif($order->order_status==4){
                                    $class='';
                                  }elseif($order->order_status==5){
                                    $class='red';
                                  }elseif($order->order_status==6){
                                    $class='green';
                                  }elseif($order->order_status==7){
                                    $class='green';
                                  }
                                ?>
                                <!-- Details -->
                                <div class="job-listing-description">
                                    <h4 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><?php D($order->proposal_title); ?></a></h4>
                                    <div class="job-listing-footer">
                                    <ul>
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_ORDER_DATE',"Order Date"));?>:</b> <span><?php D(dateFormat($order->order_date,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_DUE_ON',"Due On"));?>:</b> <span><?php D(dateFormat($order->order_time,'F d, Y')); ?></span></li>
                                    <li><span class="mb-0 dashboard-status-button <?php echo $class;?>"><?php D(ucwords($orderStatus[$order->order_status])); ?></span></li>
                                    </ul>
                                    </div>                                                                        
                                </div>
                            </div>
                            <h3 class="price"><span><?php D($s_currency); ?></span><?php D($order->order_price); ?></h3>
                        </div>
                          <!-- Buttons
                          <div class="buttons-to-right single-right-button">                            
                          </div> -->
                    </li>
                <?php }?>
                </ul>
                <div class="text-center p-3">
                  <a href="<?php D(get_link('sellingOrderURL'))?>" class="btn btn-outline-site">
                    <?php D(__('dashboard_page_view_all','View All'));?>
                  </a>
                </div>
              <?php }?>
              </div>

              <div id="mytask" class="tab-pane fade">
              <?php 
                if($orders_as_buyer){
              ?>
              <ul class="dashboard-box-list">
                <?php
                  foreach($orders_as_buyer as $order){      
                ?>
                    <li>
                        <!-- Job Listing -->
                        <div class="job-listing">               
                            <!-- Job Listing Details -->
                            <div class="job-listing-details">
                                <!-- Logo -->
                                <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="job-listing-company-logo">
                                    <img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="">
                                </a>
                                <?php 
                                  $class='';
                                  if($order->order_status==1){
                                    $class='yellow';
                                  }elseif($order->order_status==2){
                                    $class='blue';
                                  }elseif($order->order_status==3){
                                    $class='';
                                  }elseif($order->order_status==4){
                                    $class='';
                                  }elseif($order->order_status==5){
                                    $class='red';
                                  }elseif($order->order_status==6){
                                    $class='green';
                                  }elseif($order->order_status==7){
                                    $class='green';
                                  }
                                ?>
                                <!-- Details -->
                                <div class="job-listing-description">
                                    <h4 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><?php D($order->proposal_title); ?></a></h4>
                                    <div class="job-listing-footer">
                                    <ul>
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_ORDER_DATE',"Order Date"));?>:</b> <span><?php D(dateFormat($order->order_date,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_DUE_ON',"Due On"));?>:</b> <span><?php D(dateFormat($order->order_time,'F d, Y')); ?></span></li>
                                    <li><span class="mb-0 dashboard-status-button <?php echo $class;?>"><?php D(ucwords($orderStatus[$order->order_status])); ?></span></li>
                                    </ul>
                                    </div>                                    
                                </div>                                
                            </div>
                            <h3 class="price"><span><?php D($s_currency); ?></span><?php D($order->order_price); ?></h3>
                        </div>
                          <!-- Buttons
                          <div class="buttons-to-right single-right-button ">                            
                          </div> -->
                    </li>
                <?php }?>
                </ul>
                <div class="text-center p-3">
                  <a href="<?php D(get_link('buyingOrderURL'));?>" class="btn btn-outline-site">
                    <?php D(__('dashboard_page_view_all','View All'));?>
                  </a>
                </div>
                <?php 
                  }
                ?>
              </div>
              
            </div>
        
        </div>
      </div>
    </div>
  </div>
</section>
<script>

	function deleteNotification(nid){

		$.ajax({

        type: "POST",

        url: "<?php D(get_link('deleteActionURLNotificationAJAX'))?>/"+nid,

        dataType: "json",

        cache: false,

		success: function(msg) {

			if (msg['status'] == 'OK') {

				var message='<?php D(__('popup_notification_delete_success',"Notification deleted successfully!"));?>';

				 swal({

                  type: 'success',

                  text: message,

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.reload();

                })	

			} else if (msg['status'] == 'FAIL') {

				 swal({

                  type: 'error',

                  text: message,

                  timer: 2000,

                  })

			}

		}

	})

	}

</script>
<?php if($this->session->flashdata('FirstLogin')){?>
<script>

$(document).ready(function(){

	bootbox.alert({

		title:'<?php D(__('popup_first_login_heading',"Congratulations!"));?>',

		message: '<p><?php D(__('popup_first_login_text',"Note: To protect your rights on 1gigs, please ensure that communication is done on the platform. <br>Requesting/sharing contact details is prohibited in the platform"));?></p>',

		buttons: {

		'ok': {

			label: 'Ok',

			className: 'btn-site pull-right'

			}

		},

		callback: function () {

			

	    }

	});

})

</script>
<?php }?>
<script>

var ctx = document.getElementById('chart').getContext('2d');

	var chart = new Chart(ctx, {
		type: 'line',

		// The data for our dataset
		data: {
			labels: ["<?php echo implode('","',$orderGraph['month']);?>"],
			// Information about the dataset
	   		datasets: [{
				label: "Sell",
				backgroundColor: 'rgba(253,208,7,0.1)',
				borderColor: '#fdd007',
				borderWidth: "3",
				data: [<?php echo implode(',',$orderGraph['seller']);?>],
				pointRadius: 5,
				pointHoverRadius:5,
				pointHitRadius: 10,
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointBorderWidth: "2",
			},
      {
				label: "Buy",
				backgroundColor: 'rgba(253,208,7,0.1)',
				borderColor: '#fdd007',
				borderWidth: "3",
				data: [<?php echo implode(',',$orderGraph['buyer']);?>],
				pointRadius: 5,
				pointHoverRadius:5,
				pointHitRadius: 10,
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointBorderWidth: "2",
			}
      ]
		},

		// Configuration options
		options: {

		    layout: {
		      padding: 10,
		  	},

			legend: { display: false },
			title:  { display: false },

			scales: {
				yAxes: [{
					scaleLabel: {
						display: false
					},
					gridLines: {
						 borderDash: [6, 10],
						 color: "#d8d8d8",
						 lineWidth: 1,
	            	},
				}],
				xAxes: [{
					scaleLabel: { display: false },  
					gridLines:  { display: false },
				}],
			},

		    tooltips: {
		      backgroundColor: '#333',
		      titleFontSize: 13,
		      titleFontColor: '#fff',
		      bodyFontColor: '#fff',
		      bodyFontSize: 13,
		      displayColors: false,
		      xPadding: 10,
		      yPadding: 10,
		      intersect: false
		    }
		},


});

var ctx_pie = document.getElementById('chartpie').getContext('2d');
var chart_pie = new Chart(ctx_pie, {
  type: 'pie',
	data: {
		datasets: [{
			data: [<?php echo $orderStatusChart['active'];?>, <?php echo $orderStatusChart['delivered'];?>, <?php echo $orderStatusChart['completed'];?>, <?php echo $orderStatusChart['cancelled'];?>],
			backgroundColor: ['#6f42c1','#ffc107','#28a745',"#dc3545"],
			label: 'Dataset 1',
			position: 'bottom',
		}],
		labels: [
			'Active','Delivered','Completed','Cancelled'
		]
	},
	legend: {
	options: {
      title: {
        //display: true,
		responsive: true,
		position: 'bottom',
        //text: 'Project Analytics in 2020'
      }
    }
	}
});



	


</script>