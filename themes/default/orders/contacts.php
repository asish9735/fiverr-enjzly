<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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

//dd($all_buyer,true);

//dd($all_seller,true);

?>



<div class="breadcrumbs">

  <div class="container-fluid">

    <h1>

      <?php D(__('contacts_page_heading',"Manage Contacts"));?>

    </h1>

  </div>

</div>

<section class="section">

  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-3 col-lg-4 col-12">
        <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
      </div>
      <div class="col-xl-9 col-lg-8 col-12">
      <ul class="nav nav-tabs">

        <li class="nav-item"> <a href="#my_buyers" data-toggle="tab" class="nav-link <?php if($tab==''  || $tab=='buyer'){ D("active");}?>">

          <?php D(__('contacts_page_My_Buyers',"My Buyers"));?>

          <span class="badge badge-site ml-1">

          <?php D(count($all_buyer)); ?>

          </span> </a> </li>

        <li class="nav-item"> <a href="#my_sellers" data-toggle="tab" class="nav-link <?php if($tab=='seller'){ D("active");}?>">

          <?php D(__('contacts_page_My_Freelancer',"My Freelancer"));?>

          <span class="badge badge-site ml-1">

          <?php D(count($all_seller)); ?>

          </span> </a> </li>

      </ul>

    <div class="tab-content">

      <div id="my_buyers" class="tab-pane fade <?php if($tab==''  || $tab=='buyer'){ D("show active");}?>">

          

          <div class="dashboard-box mt-0">

          <div class="headline">

          <h4>

            <?php D(__('contacts_page_who_purchse_from_you',"Buyers who have purchased proposals/services from you."));?>

          </h4>

          </div>

    			<ul class="dashboard-box-list">            



              <?php /*?><tr>

                <th><?php D(__('contacts_page_Buyers_name',"Buyer\'s Name"));?></th>

                <th> <?php D(__('contacts_page_Completed_Orders',"Completed Orders"));?>

                </th>

                <th> <?php D(__('contacts_page_Amount_Spent',"Amount Spent"));?>

                </th>

                <th> <?php D(__('contacts_page_Last_Order_Date',"Last Order Date"));?></th>

                <th></th>

              </tr><?php */?>



              <?php

              if($all_buyer){

              foreach($all_buyer as $buyer){

                $username=getUserName($buyer->member_id);

              ?>

              <li>

                 <div class="job-listing">

                <div class="job-listing-details">

                <div class="job-listing-company-logo">

                    <a href="<?php D(get_link('viewprofileURL').$username) ; ?>" target="blank"><img src="<?php D(getMemberLogo($buyer->member_id))?>" alt="" class="fluid-img"></a>

                </div>

                <div class="job-listing-description">

                <h3 class="job-listing-title"><a href="<?php D(get_link('viewprofileURL').$username) ; ?>" target="blank"><?php /*D($buyer->member_name)*/ D($username);?></a></h3>

                

                <div class="job-listing-footer">

                    <ul>

                        <li><i class="icon-feather-calendar"></i><b><?php D(__('contacts_page_Completed_Orders',"Completed Orders"));?>:</b> <?php D($buyer->total_order)?></li>

                        <li><i class="icon-feather-calendar"></i><b><?php D(__('contacts_page_Amount_Spent',"Amount Spent"));?>:</b> <?php D($s_currency); ?> <?php D($buyer->total_amount)?></li>

                        <li><i class="icon-feather-calendar"></i><b><?php D(__('contacts_page_Last_Order_Date',"Last Order Date"));?>:</b> <?php D(dateFormat($buyer->last_order_date,'F d, Y'));?></li>

                    </ul>

                </div>

                

            </div>

            </div>

            </div>

            <div class="buttons-to-right single-right-button always-visible">

            	<a href="<?php D(get_link('viewprofileURL').$username) ; ?>" target="blank" class="btn btn-sm btn-outline-site" >

                      <?php D(__('contacts_page_User_Profile',"User Profile"));?>

                      </a> <a href="<?php D(get_link('BuyingHistoryURL').$buyer->member_id) ; ?>" class="btn btn-sm btn-outline-secondary">

                      <?php D(__('contacts_page_History',"History"));?>

                      </a> 

            	<a href="<?php D(get_link('messageLink').'/'.$buyer->member_id) ; ?>" target="blank" class="btn btn-sm btn-site"> <i class="icon-feather-message-square"></i> </a>

            </div>

              </li>

              <?php }

      } ?>



          

          <?php



			if(!$all_buyer){

			?>

          <li><div class="alert alert-danger mb-0 w-100"><?php D(__('contacts_page_no_buyer',"You currently have no buyers in your contact book"));?></div>

            

          <?php }?>

          </ul>

        </div>

      </div>

      <div id="my_sellers" class="tab-pane fade <?php if($tab=='seller'){ D("show active");}?>">

      	

        <div class="dashboard-box mt-0">

          <div class="headline">

          <h4>

            <?php D(__('contacts_page_whom_you_purchse_from',"Freelancers from whom you have purchased proposals/services."));?>

          </h4>

          </div>

    			<ul class="dashboard-box-list">

          

            

              <?php

				if($all_seller){

				foreach($all_seller as $seller){

					$username=getUserName($seller->member_id);

				?>

              

              <li>

                 <div class="job-listing">

                <div class="job-listing-details">

                <div class="job-listing-company-logo">

                    <a href="<?php D(get_link('viewprofileURL').$username) ; ?>" target="blank"><img src="<?php D(getMemberLogo($seller->member_id))?>" alt="" class="fluid-img"></a>

                </div>

                <div class="job-listing-description">

                <h3 class="job-listing-title"><a href="<?php D(get_link('viewprofileURL').$username) ; ?>" target="blank"><?php /*D($buyer->member_name)*/ D($username);?></a></h3>

                

                <div class="job-listing-footer">

                	<ul>

                        <li><i class="icon-feather-calendar"></i><b><?php D(__('contacts_page_Completed_Orders',"Completed Orders"));?>:</b> <?php D($seller->total_order)?></li>

                        <li><i class="icon-feather-calendar"></i><b><?php D(__('contacts_page_Amount_Spent',"Amount Spent"));?>:</b> <?php D($s_currency); ?> <?php D($seller->total_amount)?></li>

                        <li><i class="icon-feather-calendar"></i><b><?php D(__('contacts_page_Last_Order_Date',"Last Order Date"));?>:</b> <?php D(dateFormat($seller->last_order_date,'F d, Y'));?></li>

                    </ul>                    

                </div>

                

            </div>

            </div>

            </div>

            <div class="buttons-to-right single-right-button always-visible">

            	<a href="<?php D(get_link('viewprofileURL').$username) ; ?>" target="blank" class="btn btn-sm btn-outline-site" >

                      <?php D(__('contacts_page_User_Profile',"User Profile"));?>

                      </a> <a href="<?php D(get_link('SellingHistoryURL').$seller->member_id) ; ?>" class="btn btn-sm btn-outline-secondary">

                      <?php D(__('contacts_page_History',"History"));?>

                      </a> 

            	<a href="<?php D(get_link('messageLink').'/'.$seller->member_id) ; ?>" target="blank" class="btn btn-sm btn-site"> <i class="icon-feather-message-square"></i> </a>

            </div>

              </li>

              <?php }

} ?>

            

          

          <?php



			if(!$all_seller){

			?>

					  <li><div class="alert alert-danger mb-0 w-100"><?php D(__('contacts_page_no_freelancer',"You currently have no freelancer in your contact book"));?></div></li>

						

					  <?php

			}

			?>

</ul>

        </div>
        </div>
        </div>
      </div>

    </div>

  </div>

</section>

