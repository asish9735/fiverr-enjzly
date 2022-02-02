<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*echo '<div style="display:none">';

dd($orderDetails,TRUE);

echo '</div>';*/

$orderStatus=array(

'1'=>__('global_Order_Status_Pending','Pending'),

'2'=>__('global_Order_Status_Progress','Progress'),

'3'=>__('global_Order_Status_Revision','Revision requested'),

'4'=>__('global_Order_Status_Cancellation','Cancellation requested'),

'5'=>__('global_Order_Status_Cancelled','Cancelled'),

'6'=>__('global_Order_Status_Delivered','Delivered'),

'7'=>__('global_Order_Status_Completed','Completed'),

);

//$comission_percentage=getComissionPercentage($orderDetails->seller_id);

//$comission_percentage=get_option_value('comission_percentage');

//$commission=($comission_percentage / 100 ) * $orderDetails->order_price;

?>
<?php //require_once("orderIncludes/orderDetails.php"); ?>

<div class="container-fluid order-page mt-2">
<div class="row">
      <div class="col-xl-3 col-lg-4 col-12">
        <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
      </div>
      <div class="col-xl-9 col-lg-8 col-12">
      <?php if($orderDetails->order_status == ORDER_PENDING or $orderDetails->order_status == ORDER_PROCESSING or $orderDetails->order_status == ORDER_DELIVERED or $orderDetails->order_status == ORDER_REVISION or $orderDetails->order_status == ORDER_CANCELLATION){ ?>


  <div class="alert alert-primary d-flex mt-3">
    <span>
      <i class="icon-feather-check text-primary iphone5-d-none"></i> <?php D(__('order_details_page_orderID_number',"Order: #"));?>
      <?php D($orderDetails->order_number); ?>
      </span>
    <span class="ml-auto">
      <?php // D(__('order_details_page_Status',"Status:"));?>
      <span class="badge badge-warning">
      <?php if($orderDetails->order_status ==ORDER_PROCESSING){ D(__('order_details_page_In',"In")); } ?>
      <?php 

          D($orderStatus[$orderDetails->order_status]);

          ?>
      </span> </span>
  </div>

<?php }elseif($orderDetails->order_status == ORDER_CANCELLED){ ?>
  <div class="alert alert-danger d-flex mt-3">
    <p class="mb-0">
      <i class="icon-feather-slash text-danger iphone5-d-none"></i> <?php D(__('order_details_page_Order_Cancelled',"Order Cancelled, Payment Has Been Refunded To Buyer."));?>
    </p>
    <p class="mb-0 ml-auto">
      <?php // D(__('order_details_page_Status',"Status:"));?>
      <span class="badge badge-danger">
      <?php  D($orderStatus[$orderDetails->order_status]); ?>
      </span> </p>
  </div>
<?php }elseif($orderDetails->order_status == ORDER_COMPLETED){

	 ?>
  <div class="alert alert-success d-flex mt-3">
    <?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>
    <p class="mb-0"><i class="icon-feather-check text-success"></i>
      <?php D(__('order_details_page_Order_Delivered',"Order Delivered. You Earned"));?>
      <?php D(CURRENCY) ; ?>
      <?php D($orderDetails->revenues->amount); //D($orderDetails->order_price-$commission); ?>
    </p>
    <p class="mb-0 ml-auto">
      <?php // D(__('order_details_page_Status',"Status:"));?>
      <span class="badge badge-success">
      <?php D($orderStatus[$orderDetails->order_status]);?>
      </span></p>
    <?php if($orderDetails->revenues && $orderDetails->revenues->status!=1){?>
    <p>
      <?php D(__('order_details_page_Payment_expected_Release_Date',"Payment expected Release Date :"));?>
      <?php D(dateFormat($orderDetails->revenues->end_date,'F d, Y'));?>
    </p>
    <?php }?>
    <?php }elseif($orderDetails->buyer_id == $loggedUser['MID']){ ?>
    <p class="mb-0"><i class="icon-feather-check text-success"></i>
      <?php D(__('order_details_page_Delivery_Submitted',"Delivery Submitted"));?>
    </p>
    <p class="mb-0 ml-auto">
      <?php // D(__('order_details_page_Status',"Status:"));?>
      <span class="badge badge-success">
      <?php  D($orderStatus[$orderDetails->order_status]); ?>
      </span></p>
    <?php } ?>
  </div>
<?php } ?>
  <ul class="nav nav-tabs mb-3 mt-3">
    <li class="nav-item"> <a href="#order-activity" data-toggle="tab" class="nav-link active">
      <?php D(__('order_details_page_Order_Activity',"Order Activity"));?>
      </a> </li>
    <?php if($orderDetails->order_status == ORDER_PENDING or $orderDetails->order_status == ORDER_PROCESSING or $orderDetails->order_status == ORDER_DELIVERED or $orderDetails->order_status == ORDER_REVISION){ ?>
    <li class="nav-item"> <a href="#resolution-center" data-toggle="tab" class="nav-link">
      <?php D(__('order_details_page_Resolution_Center',"Resolution Center"));?>
      </a> </li>
    <?php } ?>
  </ul>
  <div class="tab-content mt-2 mb-4">
    <div id="order-activity" class="tab-pane fade show active">
      <div class="listings-container">
        <div class="job-listing"> 
          
          <!-- Job Listing Details -->
          
          <div class="job-listing-details"> 
            
            <!-- Logo -->
            
            <div class="job-listing-company-logo"> <img src="<?php D(URL_USERUPLOAD) ?>proposal-files/<?php D($orderDetails->proposal_image); ?>" class="img-fluid" alt=""> </div>
            <?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>
            
            <!-- Details -->
            
            <div class="job-listing-description">
              <h3 class="job-listing-title">
                <?php D(__('order_details_page_orderID_number',"Order #"));?>
                <?php D($orderDetails->order_number); ?>
              </h3>
              <h3><?php D(CURRENCY) ; ?><?php D($orderDetails->order_price); ?></h3>                            
              <a href="<?php D(get_link('ProposalDetailsURL'))?>/<?php D($orderDetails->seller_user_name); ?>/<?php D($orderDetails->proposal_url); ?>" target="_blank" class="btn btn-sm btn-site mb-2">
              <?php D(__('order_details_page_View_Proposal',"View Proposal/Service"));?>
              </a>
              
              <div class="job-listing-footer">
                <ul>
                  <li><i class="icon-feather-user"></i> <b>Buyer:</b> <a href="<?php D(get_link('viewprofileURL')); ?><?php D($orderDetails->buyer_user_name); ?>" target="_blank" class="seller-buyer-name mr-1 text-success">
                    <?php /*ucfirst(D($orderDetails->buyer['member']->member_name));*/ D($orderDetails->buyer_user_name); ?>
                    </a> </li>
                  <li><i class="icon-feather-check-circle"></i> <b>
                    <?php D(__('order_details_page_Status',"Status:"));?>
                    </b>
                    <?php D($orderStatus[$orderDetails->order_status]);?>
                  </li>
                  <li><i class="icon-feather-calendar"></i> <!--<b><?php // D(__('order_details_page_Date',"Date:"));?></b>--> <?php D(dateFormat($orderDetails->order_date,'M d, Y')); ?>
                  </li>
                </ul>
              </div>              
            </div>
            
            <?php }elseif($orderDetails->buyer_id == $loggedUser['MID']){ ?>
            
            <!-- Details -->
            
            <div class="job-listing-description">              
              <div class="d-md-flex justify-content-between">
              	<h3 class="job-listing-title"><?php  D($orderDetails->proposal_title); ?></h3>              
              	<h3><?php D(CURRENCY) ; ?><?php D($orderDetails->order_price); ?></h3>
              </div>
              <div class="job-listing-footer">
                <ul>
                  <li><i class="icon-feather-user"></i> <b>
                    <?php D(__('order_details_page_Freelancer',"Freelancer:"));?>
                    </b> <a href="<?php D(get_link('viewprofileURL')); ?><?php D($orderDetails->seller_user_name); ?>" target="_blank" class="seller-buyer-name mr-1 text-success">
                    <?php /* ucfirst(D($orderDetails->seller['member']->member_name));*/ D($orderDetails->seller_user_name);?>
                    </a></li>
                  <li><i class="icon-feather-check-circle"></i> <b>
                    <?php D(__('order_details_page_Order',"Order:"));?>
                    </b> #
                    <?php D($orderDetails->order_number); ?>
                  </li>
                  <li><i class="icon-feather-calendar"></i> <!--<b><?php // D(__('order_details_page_Date',"Date:"));?></b>--> <?php D(dateFormat($orderDetails->order_date,'F d,Y')); ?>
                  </li>
                </ul>
              </div>
            </div>
            
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="dashboard-box mb-4">
        <ul class="dashboard-box-list">
          <?php /*?><thead>

            <tr>

              <th><?php D(__('order_details_page_Item',"Item"));?></th>

              <th><?php D(__('order_details_page_Quantity',"Quantity"));?></th>

              <th><?php D(__('order_details_page_Duration',"Duration"));?></th>

              <th><?php D(__('order_details_page_Amount',"Amount"));?></th>

            </tr>

          </thead><?php */?>
          <li>
            <div class="job-listing"> 
              
              <!-- Job Listing Details -->
              
              <div class="job-listing-details">
                <div class="job-listing-description">
                  <h3 class="job-listing-title">
                    <?php D($orderDetails->proposal_title); ?>
                  </h3>
                  <div class="job-listing-footer">
                    <?php 

              if($orderDetails->extra){

              ?>
                    <ul class="ml-5" style="list-style-type: circle;">
                      <?php

              foreach($orderDetails->extra as $extra){

              ?>
                      <li class="font-weight-normal text-muted">
                        <?php D($extra->name); ?>
                        (+<span class="price">
                        <?php D(CURRENCY.$extra->price); ?>
                        </span>) </li>
                      <?php } ?>
                    </ul>
                    <?php } ?>
                    <ul>
                      <li><i class="icon-feather-calendar"></i> <b> Date:</b> <span>
                        <?php D($orderDetails->order_duration); ?>
                        <?php D(__('order_details_page_days',"days"));?>
                        </span></li>
                      <li><i class="icon-feather-eye"></i> <b> For:</b> <span>
                        <?php D($orderDetails->order_qty); ?>
                        </span></li>
                      <li><i class="icon-feather-tag"></i> <b> Amount:</b> <span>
                        <?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>
                        <?php D(CURRENCY); ?>
                        <?php D($orderDetails->order_price); ?>
                        <?php }elseif($orderDetails->buyer_id == $loggedUser['MID']){ ?>
                        <?php D(CURRENCY); ?>
                        <?php D($orderDetails->order_price); ?>
                        <?php } ?>
                        </span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <?php /*?><?php if($orderDetails->buyer_id == $loggedUser['MID']){  ?>

	           <?php if(!empty($orderDetails->order_fee)){ ?>

	        <tr>

	              <td><?php D(__('order_details_page_Processing_Fee',"Processing Fee"));?></td>

	              <td></td>

	              <td></td>

	              <td><?php D(CURRENCY); ?><?php D($orderDetails->order_fee) ?></td>

	        </tr>

	            <?php } ?>

            <?php } ?>

            <tr>

              <td colspan="4">

              <span class="float-right mr-4">

                <strong><?php D(__('order_details_page_Total',"Total :"));?> </strong>

                <?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>

                    <?php D(CURRENCY); ?><?php D($orderDetails->order_price); ?>

                 <?php }elseif($orderDetails->buyer_id == $loggedUser['MID']){ ?> 

                    <?php D(CURRENCY); ?><?php D($orderDetails->order_price+$orderDetails->order_fee); ?>

                 <?php } ?>

              </span>

            </tr><?php */?>
        </ul>
        <?php if(!empty($orderDetails->order_description)){ ?>
        <table class="table">
          <thead>
            <tr>
              <th><?php D(__('order_details_page_Description',"Description"));?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td width="600"><?php D($orderDetails->order_description); ?></td>
            </tr>
          </tbody>
        </table>
        <?php } ?>
      </div>

      <?php if($orderDetails->order_status ==ORDER_PROCESSING or $orderDetails->order_status == ORDER_REVISION){ ?>
        <div class="card mb-4">
      <?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>
      <div class="card-header">
        <h4 class="text-center" id="countdown-heading">
        <?php D(__('order_details_page_order_need_to_be_deliverd_before',"This Order Needs To Be Delivered Before This Day/Time:"));?>
        </h4>
      </div>
      <?php }elseif($orderDetails->buyer_id == $loggedUser['MID']){ ?>
        
          <div class="card-header">
            <h4 class="text-center" id="courntdown-heading">
              <?php D(__('order_details_page_your_order_need_to_be_deliverd_before',"Your Order Should Be Ready On or Before This Day/Time:"));?>
            </h4>
          </div>
        <?php } ?>
        <div class="alert alert-danger text-center courntdown-late-order" style="display:none">
        <p class="mb-0"><?php D(__('order_details_page_your_order_is_late',"Your order is late"));?></p>
        </div>
        <div class="card-body" id="countdown-timer" style="display:none">
            <div class="d-flex justify-content-center">
              <div class="countdown-box">                 
                <h2 class="countdown-number" id="days"></h2>
                <p class="countdown-title"><?php D(__('order_details_page_counter_days',"Day(s)"));?></p>
              </div>
              <div class="countdown-box"> 
                <h2 class="countdown-number" id="hours"></h2>
                <p class="countdown-title"><?php D(__('order_details_page_counter_Hours',"Hours"));?></p>
              </div>
              <div class="countdown-box"> 
                <h2 class="countdown-number" id="minutes"></h2>
                <p class="countdown-title"><?php D(__('order_details_page_counter_Minutes',"Minutes"));?></p>
              </div>
              <div class="countdown-box"> 
                <h2 class="countdown-number" id="seconds"></h2>
                <p class="countdown-title"><?php D(__('order_details_page_counter_Seconds',"Seconds"));?></p>
              </div>
          </div>

        </div>
      <?php } ?>
      </div>

      <?php if($orderDetails->buyer_id == $loggedUser['MID']){ ?>
      <?php if(!empty($orderDetails->buyer_instruction)){ ?>
      <div class="card mb-4">
        <div class="card-header">
          <h4>
            <?php D(__('order_details_page_Getting_Started',"Getting Started"));?>
          </h4>
        </div>
        <div class="card-body">
          <h6> <b>
            <?php /*ucfirst(D($orderDetails->seller['member']->member_name));*/ D($orderDetails->seller_user_name);?>
            </b>
            <?php D(__('order_details_page_requires_information',"requires the following information in order to get started:"));?>
          </h6>
          <p>
            <?php D(nl2br($orderDetails->buyer_instruction)); ?>
          </p>
        </div>
      </div>
      
      <?php } ?>
      <?php } ?>
      <div id="order-conversations" class="dashboard-box mb-4">
        <?php

    $data=array();

    $data['orderDetails']=$orderDetails;

    $data['loggedUser']=$loggedUser;

    $templateLayout=array('view'=>'order-conversations','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

	load_template($templateLayout,$data);

     ?>
      </div>
      <?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>
      <?php if($orderDetails->order_status == ORDER_PROCESSING or $orderDetails->order_status == ORDER_REVISION){ ?>
      <center>
        <button class="btn btn-site mb-3" data-toggle="modal" data-target="#deliver-order-modal"> <i class="fa fa-upload"></i>
        <?php D(__('order_details_page_Deliver_Order',"Deliver Order"));?>
        </button>
      </center>
      <?php } ?>
      <?php if($orderDetails->order_status == ORDER_DELIVERED){ ?>
      <center>
        <button class="btn btn-site mt-4 mb-2" data-toggle="modal" data-target="#deliver-order-modal"> <i class="fa fa-upload"></i>
        <?php D(__('order_details_page_Deliver_Order_Again',"Deliver Order Again"));?>
        </button>
      </center>
      <?php } ?>
      <?php } ?>
      <div class="proposal_reviews mb-0">
        <?php if($orderDetails->order_status == ORDER_COMPLETED){ 

        if($orderDetails->seller_review || $orderDetails->buyer_review){

          ?>
        <div class="card rounded-0 mt-3">
          <div class="card-header bg-fivGrey">
            <h5 class="text-center mt-2"><i class="fa fa-star"></i>
              <?php D(__('order_details_page_Order_Review',"Order Review"));?>
            </h5>
          </div>
          <div class="card-body">
            <div class="proposal-reviews">
              <ul class="reviews-list">
                <?php if($orderDetails->buyer_review){ ?>
                <li class="star-rating-row"> <span class="user-picture"> <img src="<?php D(getMemberLogo($orderDetails->buyer_id)) ?>" width="60" height="60"> </span>
                  <h4> <a href="#" class="mr-1">
                    <?php /*D($orderDetails->buyer['member']->member_name);*/ D($orderDetails->buyer_user_name); ?>
                    </a>
                    <?php

		                for($buyer_i=0; $buyer_i<$orderDetails->buyer_review->buyer_rating; $buyer_i++){

		               	 echo "<img src='".theme_url().IMAGE."user_rate_full.png'>";

		                }

		                for($buyer_i=$orderDetails->buyer_review->buyer_rating; $buyer_i<5; $buyer_i++){

		                	echo "<img src='".theme_url().IMAGE."user_rate_blank.png'>";

		                }

		      ?>
                  </h4>
                  <div class="msg-body">
                    <?php D($orderDetails->buyer_review->buyer_review); ?>
                  </div>
                  <span class="rating-date">
                  <?php D(dateFormat($orderDetails->buyer_review->review_date,'F d,Y')); ?>
                  </span> </li>
                <hr class="mb-4">
                <?php }

            if($orderDetails->seller_review){

            ?>
                <li class="rating-seller">
                  <h4> <span class="mr-1">
                    <?php D(__('order_details_page_Freelancers_Feedback',"Freelancer\'s Feedback"));?>
                    </span>
                    <?php 

                        for($seller_i=0; $seller_i<$orderDetails->seller_review->seller_rating; $seller_i++){

                        	echo "<img src='".theme_url().IMAGE."user_rate_full.png'>";

                        }

                        for($seller_i=$orderDetails->seller_review->seller_rating; $seller_i<5; $seller_i++){

                        	echo "<img src='".theme_url().IMAGE."user_rate_blank.png'>";

                        }

              ?>
                  </h4>
                  <span class="user-picture"> <img src="<?php D(getMemberLogo($orderDetails->seller_id)) ?>" width="40" height="40"> </span>
                  <div class="msg-body">
                    <?php D($orderDetails->seller_review->seller_review); ?>
                  </div>
                  <span class="rating-date">
                  <?php D(dateFormat($orderDetails->seller_review->review_date,'F d,Y')); ?>
                  </span> </li>
                <?php

            }

            ?>
                <hr>
              </ul>
            </div>
          </div>
        </div>
        <?php

	}

 ?>
        <?php if($orderDetails->seller_id == $loggedUser['MID']){

	?>
        <div class="card order-review-box">
          <div class="card-header">
            <h4>
              <?php if($orderDetails->seller_review){D(__('order_details_page_Edit_Review_To_Your_Buyer',"Edit Review To Your Buyer"));}else{D(__('order_details_page_Submit_Review_For_Your_Buyer',"Please Submit a Review For Your Buyer"));}?>
            </h4>
          </div>
          <div class="card-body">
            <form method="post" id="ratingForm" onsubmit="return performAction(this);return false;">
              <input type="hidden" name="action" value="review_submit"/>
              <div class="form-group">
                <label class="form-label">
                  <?php D(__('order_details_page_Review_Rating',"Review Rating"));?>
                </label>
                <div class="star-rating" data-rating="3.5"></div>
                <?php /*?><select name="rating" class="rating-select">

                    <option value="1" <?php if($orderDetails->seller_review && $orderDetails->seller_review->seller_rating==1){D('selected');}?>>1</option>

                    <option value="2" <?php if($orderDetails->seller_review && $orderDetails->seller_review->seller_rating==2){D('selected');}?>>2</option>

                    <option value="3" <?php if($orderDetails->seller_review && $orderDetails->seller_review->seller_rating==3){D('selected');}?>>3</option>

                    <option value="4" <?php if($orderDetails->seller_review && $orderDetails->seller_review->seller_rating==4){D('selected');}?>>4</option>

                    <option value="5" <?php if($orderDetails->seller_review && $orderDetails->seller_review->seller_rating==5){D('selected');}?>>5</option>

                  </select><?php */?>
              </div>
              <textarea name="review" class="form-control mb-3" rows="5" placeholder="<?php D(__('order_details_page_Review_Rating_input',"What was your Experience?"));?>"><?php if($orderDetails->seller_review){D($orderDetails->seller_review->seller_review);}?>
    </textarea>
              <button type="submit" name="seller_review_submit" class="btn btn-site">
              <?php if($orderDetails->seller_review){D(__('order_details_page_Update_Review',"Update Review"));}else{D(__('order_details_page_Submit_Review',"Submit Review"));}?>
              </button>
            </form>
          </div>
        </div>
        <?php }elseif($orderDetails->buyer_id == $loggedUser['MID']){

	?>
        <div class="card order-review-box mt-4 mb-3">
          <div class="card-header">
            <h3>
              <?php if($orderDetails->seller_review){D(__('order_details_page_Edit_Review_To_Your_Freelancer',"Edit Review To Your Freelancer"));}else{D(__('order_details_page_Submit_For_Your_Freelancer',"Please Submit a Review For Your Freelancer"));}?>
            </h3>
          </div>
          <div class="card-body">
          <form method="post"  align="center" id="ratingForm" onsubmit="return performAction(this);return false;">
            <input type="hidden" name="action" value="review_submit"/>
            <div class="form-group">
              <label>
                <?php D(__('order_details_page_Review_Rating',"Review Rating"));?>
              </label>
              <select name="rating" class="rating-select">
                <option value="1" <?php if($orderDetails->buyer_review && $orderDetails->buyer_review->buyer_rating==1){D('selected');}?>>1</option>
                <option value="2" <?php if($orderDetails->buyer_review && $orderDetails->buyer_review->buyer_rating==2){D('selected');}?>>2</option>
                <option value="3" <?php if($orderDetails->buyer_review && $orderDetails->buyer_review->buyer_rating==3){D('selected');}?>>3</option>
                <option value="4" <?php if($orderDetails->buyer_review && $orderDetails->buyer_review->buyer_rating==4){D('selected');}?>>4</option>
                <option value="5" <?php if($orderDetails->buyer_review && $orderDetails->buyer_review->buyer_rating==5){D('selected');}?>>5</option>
              </select>
            </div>
            <textarea name="review" class="form-control mb-3" rows="5" placeholder="<?php D(__('order_details_page_Review_Rating_input',"What was your Experience?"));?>"><?php if($orderDetails->buyer_review){D($orderDetails->buyer_review->buyer_review);}?></textarea>
            <button type="submit" name="buyer_review_submit" class="btn btn-site">
            <?php if($orderDetails->buyer_review){D(__('order_details_page_Update_Review',"Update Review"));}else{D(__('order_details_page_Submit_Review',"Submit Review"));}?>
            </button>
          </form>
          </div>
        </div>
        <?php 

          }

      } 

      ?>
      </div>
      <?php if($orderDetails->order_status == ORDER_PENDING or $orderDetails->order_status == ORDER_PROCESSING or $orderDetails->order_status == ORDER_DELIVERED or $orderDetails->order_status == ORDER_REVISION){ ?>
      <div class="insert-message-box">
        <?php if($orderDetails->buyer_id == $loggedUser['MID'] AND $orderDetails->order_status == ORDER_PENDING ){ ?>
        <div class="float-md-left"> <span class="font-weight-bold text-danger">
          <?php D(__('order_details_page_RESPOND_TO_SELLER',"Respond so that seller can start your order."));?>
          </span> </div>
        <?php } ?>
        <div class="float-md-right">
          <?php

        if($orderDetails->seller_id == $loggedUser['MID']){

          $is_online=is_online($orderDetails->buyer_id);

          }elseif($orderDetails->buyer_id == $loggedUser['MID']){

          $is_online=is_online($orderDetails->seller_id);

        }

      ?>
          <p class="text-muted mt-1">
            <?php if($orderDetails->seller_id == $loggedUser['MID']){ 

                    		/*D(ucfirst($orderDetails->buyer['member']->member_name));*/ D($orderDetails->buyer_user_name);

                    	?>
            <span <?php if($is_online){ ?>class="text-success font-weight-bold"<?php }else{ ?>style="color:#868e96; font-weight:bold;"<?php } ?>> is
            <?php D(($is_online==1 ? 'online':'offline')); ?>
            </span> |
            <?php D(__('order_details_page_Local_Time',"Local Time"));?>
            <?php }elseif($orderDetails->buyer_id == $loggedUser['MID']){ 

                		/*D(ucfirst($orderDetails->seller['member']->member_name));*/ D($orderDetails->seller_user_name);

                	 ?>
            <span <?php if($is_online){ ?>class="text-success font-weight-bold"<?php }else{ ?>style="color:#868e96; font-weight:bold;"<?php } ?>> is
            <?php D(($is_online==1 ? __('order_details_page_online','online'):__('order_details_page_offline','offline'))); ?>
            </span> |
            <?php D(__('order_details_page_Local_Time',"Local Time"));?>
            <?php } ?>
            <i class="icon-feather-clock"></i>
            <?php 

					D(date("h:i A"));

				?>
          </p>
        </div>
        <form id="insert-message-form" class="clearfix">
          <textarea name="messagebox" id="messagebox" rows="5" placeholder="<?php D(__('order_details_page_Type_message_input',"Type Your Message Here..."));?>" class="form-control mb-3"></textarea>
          <div class="d-flex align-items-center justify-content-between">
            <label class="mb-0 d-none d-sm-block"><?php D(__('order_details_page_Attach_File',"Attach File (optional)"));?></label>  
            <div class="d-flex">                 
                <div class="uploadButton mb-0">
                  <input type="file" id="file" class="uploadButton-input">
                  <label class="uploadButton-button ripple-effect" for="file"><i class="icon-feather-paperclip"></i><?php // D(__('global_Choose_File',"Choose File"));?></label>
                </div>
                <button type="submit" class="btn btn-site saveBTN"><?php D(__('order_details_page_Send',"Send"));?></button> 
            </div>
          </div>
          <div class="upload_file_div"></div>
        </form>
      </div>
      <div id="message_data_div"></div>
      <?php } ?>
    </div>
    <div id="resolution-center" class="tab-pane fade">
      <div class="card">
        <div class="card-body">
              <h3>
                    <?php D(__('order_details_page_Order_Cancellation_Request',"Order Cancellation Request"));?>
                  </h3>
              <form method="post" id="resolutionForm" onsubmit="return performAction(this);return false;">
                <input type="hidden" name="action" value="submit_cancellation_request"/>
                <div class="form-group">
                  <label class="form-label">Description</label>
                  <textarea name="cancellation_message" id="cancellation_message" placeholder="<?php D(__('order_details_page_Order_Cancellation_Request_input',"Please be as detailed as possible..."));?>" rows="5" class="form-control" ></textarea>
                </div>
                <div class="form-group">
                  <label class="form-label">
                    <?php D(__('order_details_page_Cancellation_Request_Reason',"Cancellation Request Reason"));?>
                  </label>
                  <select name="cancellation_reason" class="form-control" id="cancellation_reason">
                    <option class="hidden">
                    <?php D(__('order_details_page_Select_Cancellation_Reason',"Select Cancellation Reason"));?>
                    </option>
                    <?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>
                    <option>
                    <?php D(__('order_details_page_Cancellation_Reason_option_seller_1',"Buyer is not responding."));?>
                    </option>
                    <option>
                    <?php D(__('order_details_page_Cancellation_Reason_option_seller_2',"Buyer is extremely rude."));?>
                    </option>
                    <option>
                    <?php D(__('order_details_page_Cancellation_Reason_option_seller_3',"Buyer requested that I cancel this order."));?>
                    </option>
                    <option>
                    <?php D(__('order_details_page_Cancellation_Reason_option_seller_4',"Buyer expects more than what this gig can offer."));?>
                    </option>
                    <?php }elseif($orderDetails->buyer_id == $loggedUser['MID']){ ?>
                    <option>
                    <?php D(__('order_details_page_Cancellation_Reason_option_buyer_1',"Freelancer is not responding."));?>
                    </option>
                    <option>
                    <?php D(__('order_details_page_Cancellation_Reason_option_buyer_2',"Freelancer is extremely rude."));?>
                    </option>
                    <option>
                    <?php D(__('order_details_page_Cancellation_Reason_option_buyer_3',"Order does meet requirements."));?>
                    </option>
                    <option>
                    <?php D(__('order_details_page_Cancellation_Reason_option_buyer_4',"Freelancer asked me to cancel."));?>
                    </option>
                    <option>
                    <?php D(__('order_details_page_Cancellation_Reason_option_buyer_5',"Freelancer cannot do required task."));?>
                    </option>
                    <?php }  ?>
                  </select>
                </div>
                <button type="submit" name="submit_cancellation_request" class="btn btn-site saveBTN">
                <?php D(__('order_details_page_Submit',"Submit"));?>
                </button>
              </form>
            
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<!---modal-->

<?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>

<div id="deliver-order-modal" class="modal fade"><!--- deliver-order-modal Starts --->

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title"> <?php D(__('modal_deliver_order_heading',"Deliver Your Order Now"));?> </h5>

				<button class="close" data-dismiss="modal"> <span>&times;</span> </button>

			</div>

			<div class="modal-body">

				<form method="post" id="deliverorderForm" onsubmit="return performAction(this);return false;">

					<input type="hidden" name="action" value="submit_delivered"/>

					<div class="form-group">

						<label class="font-weight-bold" > <?php D(__('modal_deliver_order_Message',"Message"));?> </label>

						<textarea rows="4" name="delivered_message" id="delivered_message" placeholder="<?php D(__('modal_deliver_order_Message_input',"Type Your Message Here..."));?>" class="form-control mb-2"></textarea>

					</div>

					<div class="form-group clearfix">

						<div class="choosefile" style="max-width:125px;">

							<input type="file" name="delivered_file" id="delivered_file" class="form-control">

							<span class="btn btn-outline-site"><?php D(__('global_Choose_File',"Choose File"));?></span>

						</div>

								

						<small class="text-info"><i class="fa fa-info-circle"></i> <?php D(__('modal_deliver_order_attachment_note',"NB: Maximum size 25MB"));?></small>

						<button type="submit" name="submit_delivered" class="btn btn-site float-right saveBTN" style="margin-top: -35px"><?php D(__('modal_deliver_Deliver_Order',"Deliver Order"));?></button>

						<div  style="clear: both"></div>

            			<div class="upload_file_div"></div>

					</div>

				</form>

			</div>

		</div>

	</div>

</div> 

<?php }elseif($orderDetails->buyer_id == $loggedUser['MID']){ ?>

<div id="revision-request-modal" class="modal fade">

    <div class="modal-dialog">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title"> <?php D(__('modal_revision_request_heading',"Submit Your Revision Request Here"));?> </h5>

          <button class="close" data-dismiss="modal"> <span>&times;</span> </button>

        </div>

        <div class="modal-body">

          <form method="post" id="revisionrequestForm" onsubmit="return performAction(this);return false;">

			<input type="hidden" name="action" value="submit_revison"/>

            <div class="form-group">

              <label class="font-weight-bold" > <?php D(__('modal_revision_request_Message',"Request Message"));?> </label>

              <textarea name="revison_message" id="revison_message" placeholder="<?php D(__('modal_revision_request_Message_input',"Type Your Message Here..."));?>" class="form-control mb-2"></textarea>

            </div>

            <div class="form-group clearfix">

            <div class="choosefile" style="max-width:125px;">

            <input type="file" name="revison_file" id="revison_file" class="form-control">

            <span class="btn btn-site"><?php D(__('global_Choose_File',"Choose File"));?></span>

			</div>

            <small class="text-info"><i class="fa fa-info-circle"></i> <?php D(__('modal_revision_request_attachment_note',"NB: Maximum size 25MB"));?></small>  

              <button type="submit" name="submit_revison" class="btn btn-site float-right saveBTN" style="margin-top: -35px"><?php D(__('modal_revision_request_Submit_Request',"Submit Request"));?></button>

              <div  style="clear: both"></div>

            <div class="upload_file_div"></div>

            </div>

          </form>

        </div>

      </div>

    </div>

</div>

<?php } ?>

    <div id="report-modal" class="modal fade"><!-- report-modal modal fade Starts -->

        <div class="modal-dialog"><!-- modal-dialog Starts -->

            <div class="modal-content"><!-- modal-content Starts -->

            	<div class="modal-header p-2 pl-3 pr-3"><!-- modal-header Starts -->

            		<?php D(__('modal_report_heading',"Report This Message"));?>

		            <button class="close" data-dismiss="modal">

		            	<span> &times; </span>

		            </button>

            	</div><!-- modal-header Ends -->

	            <div class="modal-body"><!-- modal-body p-0 Starts -->

	            	<h6><?php D(__('modal_report_text',"Let us know why you would like to report this user?."));?></h6>

		           	 <form method="post" id="reportrequestForm" onsubmit="return performAction(this);return false;">

		           	 <input type="hidden" name="action" value="submit_report"/>

			            <div class="form-group mt-3"><!--- form-group Starts --->

			            <select class="form-control float-right" name="reason" id="reason">

				            <option value=""><?php D(__('modal_report_Select_Reason',"Select Reason"));?></option>

				            <?php if($orderDetails->buyer_id == $loggedUser['MID']){ ?>

				            <option><?php D(__('modal_report_Reason_buyer_option_1',"The Seller tried to abuse the rating system."));?></option>

				            <option><?php D(__('modal_report_Reason_buyer_option_2',"The Seller was using inappropriate language."));?></option>

				            <option><?php D(__('modal_report_Reason_buyer_option_3',"The Seller delivered something that infringes copyrights"));?></option>

				            <option><?php D(__('modal_report_Reason_buyer_option_4',"The Seller delivered something partial or insufficient"));?></option>

				            <?php }else{ ?>

				            <option><?php D(__('modal_report_Reason_seller_option_1',"The Buyer tried to abuse the rating system."));?></option>

				            <option><?php D(__('modal_report_Reason_seller_option_2',"The Buyer was using inappropriate language."));?></option>

				            <?php } ?>

				        </select>

			            </div><!--- form-group Ends --->

		            	<br>

		            	<br>

			            <div class="form-group mt-1 mb-3"><!--- form-group Starts --->

			           		<label> <?php D(__('modal_report_Additional_Information',"Additional Information"));?> </label>

			            	<textarea name="additional_information" id="additional_information" rows="3" class="form-control" ></textarea>

			            </div><!--- form-group Ends --->

			            <button type="submit" name="submit_report" class="float-right btn btn-sm btn-site saveBTN">

			            	<?php D(__('modal_report_Submit_Report',"Submit Report"));?>

			            </button>

		            </form>

		           

	            </div><!-- modal-body p-0 Ends -->	

        	</div><!-- modal-content Ends -->

        </div><!-- modal-dialog Ends -->

    </div><!-- report-modal modal fade Ends -->



<script>

var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

var myVar;

function load_conversation(){

	var order_id = "<?php D($orderDetails->order_id)?>";

	$.ajax({

		method: "POST",

		url: "<?php D(get_link('loadConversationURL'))?>",

		data: {order_id: order_id},

		success: function(msg) {

			$("#order-conversations").empty();

			$("#order-conversations").append(msg);

			myVar=setTimeout(function(){load_conversation();},3000);

		}

	});

}

$(document).ready(function(){

// Sticky Code start //



/*$("#order-status-bar").sticky({

      topSpacing:111,

       zIndex:500 

});*/

var countDownDate = new Date("<?php D($orderDetails->order_time); ?>").getTime();

// Update the count down every 1 second

//var times='<?php D(time())?>'*1000;

var times=new Date("<?php D(date('M d, Y H:i:s')); ?>").getTime();

var m = setInterval(function(){

	 var now = new Date();

	   //var nowUTC = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), now.getUTCHours(), now.getUTCMinutes(), now.getUTCSeconds());

	    var nowUTC = new Date(times); 

	  //  console.log(nowUTC);

	   var distance = countDownDate - nowUTC;

	// Time calculations for days, hours, minutes and seconds

	var days = Math.floor(distance / (1000 * 60 * 60 * 24));

	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

	var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	times=times+1000;

	if($('#days').length>0){

		document.getElementById("days").innerHTML = days;

		document.getElementById("hours").innerHTML = hours;

		document.getElementById("minutes").innerHTML = minutes;

		document.getElementById("seconds").innerHTML = seconds;

	}

	

	// If the count down is over, write some text 

	if (distance < 0){

		clearInterval(m);

		<?php if($orderDetails->seller_id == $loggedUser['MID']){ ?>

		$("#countdown-heading").html("<?php D(__('order_details_page_Failed_To_Deliver_Seller',"You Failed To Deliver Your Order On Time"));?>");

		<?php }elseif ($orderDetails->buyer_id == $loggedUser['MID']) { ?>

		$("#countdown-heading").html("<?php D(__('order_details_page_Failed_To_Deliver_Buyer',"Your Freelancer Failed To Deliver Your Order On Time"));?>");

		<?php } ?>
$("#countdown-timer").hide();
$('.courntdown-late-order').show();
		/*$("#countdown-timer .countdown-number").addClass("countdown-number-late");*/

		/*document.getElementById("days").innerHTML = "<span class='red-color'>The</span>";

		document.getElementById("hours").innerHTML = "<span class='red-color'>Order</span>";

		document.getElementById("minutes").innerHTML = "<span class='red-color'>is</span>";

		document.getElementById("seconds").innerHTML = "<span class='red-color'>Late!</span>";*/

	}else{
		$("#countdown-timer").show();
	}

},1000);



$(document).on('change','#file', function(ev){

	var id='file';

	var formID='insert-message-form';

	uploadData(id,formID);

});

$(document).on('change','#delivered_file', function(ev){

	var id='delivered_file';

	var formID='deliverorderForm';

	uploadData(id,formID);

});

$(document).on('change','#revison_file', function(ev){

	var id='revison_file';

	var formID='revisionrequestForm';

	uploadData(id,formID);

});

$('#insert-message-form').submit(function(e){

	var formID="insert-message-form";

	var buttonsection=$('#'+formID).find('.saveBTN');

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	e.preventDefault();

	$.ajax({

		method: "POST",

		dataType:'json',

		url: "<?php D(get_link('sendMessageURL'))?>",

		data:$('#'+formID).serialize()+'&orderid=<?php D($orderDetails->order_id)?>',

		success: function(msg){

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				if(msg['redirect']){

					window.location.href=msg['redirect'];

				}

				$('#messagebox').val("");

				$('#file').val(""); 

				$("#"+formID+" .upload_file_div").empty();

				clearTimeout(myVar);

				load_conversation();

			}else{

				registerFormPostResponse(formID,msg['errors']);

			}

			

		}

	});

});

load_conversation();

});

function uploadData(id,formID){

	var form_data = new FormData();

	form_data.append("fileinput", document.getElementById(id).files[0]);

	$.ajax({

		url:"<?php D(get_link('uploadFileRequestFormCheckAJAXURL'))?>",

		method:"POST",

		data:form_data,

		contentType:false,

		cache:false,

		dataType:'json',

		processData:false,

		beforeSend:function(){

			$("#"+formID+" .upload_file_div").html(SPINNER);

		},

	}).done(function(data){

		$('#'+id).val('');

		$("#"+formID+" .upload_file_div").empty();

           if(data.status=='OK'){

    			var name = data.upload_response.original_name;

    			$("#"+formID+" .upload_file_div").html('<input type="hidden" name="attachment" value=\''+JSON.stringify(data.upload_response)+'\'/> '+name+'<a href="<?php D(VZ);?>" class=" text-danger ripple-effect ico float-right" onclick="$(this).parent().empty()"><i class="fa fa-trash"></i></a>');

		}

	});

}



function performAction(ev){

	var formID=$(ev).attr('id');

	if(formID=='accept_request' || formID=='decline_request'){

		var forminput='action='+formID;

		var buttonsection=$('#'+formID);

	}else{

		var modal=$(ev).closest('.modal');

		var buttonsection=$('#'+formID).find('.saveBTN');

		var forminput=$('#'+formID).serialize();

	}

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('saveActionURLAJAX'))?>/<?php D($orderDetails->order_id)?>",

        data:forminput,

        dataType: "json",

        cache: false,

		success: function(msg) {

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				$(modal).modal('hide');

				var message='<?php D(__('popup_order_details_action_success',"Your request has been submitted successfully!"));?>';

				if(msg['message']){

					message=msg['message'];

				}

				 swal({

                  type: 'success',

                  text: message,

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	if(msg['redirect']){

						window.location.href=msg['redirect'];

					}

                  	

                })	

			} else if (msg['status'] == 'FAIL') {

				registerFormPostResponse(formID,msg['errors']);

			}

		}

	})

	

	return false;

}

$(document).ready(function(){

     $('.rating-select').barrating({

           theme: 'fontawesome-stars'

     });

 });

   $(document).ready(function(){

     $('.rating-select-update').barrating({

           theme: 'fontawesome-stars',

            initialRating: '<?php echo $seller_rating; ?>'

        });

    });

</script>

<?php if(($this->input->get('ref') && $this->input->get('ref')=='paymentsuccess') || ($this->input->get('ref_p') && $this->input->get('ref_p')=='paymentsuccess')){?>

<script>

	swal({

          type: 'success',

          text: '<?php D(__('popup_order_details_payment_success',"Payment Success"));?>',

          padding: 40,

    }).then(function(){

			window.location.href="<?php D(get_link('OrderDetailsURL'));?><?php D($orderDetails->order_id)?>";

    })

</script>

<?php }?>