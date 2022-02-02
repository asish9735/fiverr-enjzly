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
//dd($all_orders,true);
?>
<div class="breadcrumbs">
  <div class="container-fluid">
  	<h1><?php D(__('seller_order_page_heading',"Manage Proposal/Service Orders"));?></h1>
  </div>
</div>
<section class="section">
  <div class="container-fluid">
    <?php /*?><h1 class="mb-4">
      <?php D(__('seller_order_page_heading',"Manage Proposal/Service Orders"));?>
    </h1><?php */?>
    <div class="row">
      <div class="col-xl-3 col-lg-4 col-12">
        <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
      </div>
      <div class="col-xl-9 col-lg-8 col-12">
    <ul class="nav nav-tabs">
      <li class="nav-item"> <a href="#active" data-toggle="tab" class="nav-link  <?php if($tab==''  || $tab=='active'){ D("active");}?>">
        <?php D(__('seller_order_page_tab_ACTIVE',"ACTIVE"));?>
        <span class="badge badge-site ml-1">
        <?php D(count($active_orders)); ?>
        </span> </a> </li>
      <li class="nav-item"> <a href="#delivered" data-toggle="tab" class="nav-link <?php if($tab=='delivered'){ D("active");}?>">
        <?php D(__('seller_order_page_tab_DELIVERED',"DELIVERED"));?>
        <span class="badge badge-site ml-1">
        <?php D(count($delivered_orders)); ?>
        </span> </a> </li>
      <li class="nav-item"> <a href="#completed" data-toggle="tab" class="nav-link <?php if($tab=='completed'){ D("active");}?>">
        <?php D(__('seller_order_page_tab_COMPLETED',"COMPLETED"));?>
        <span class="badge badge-success ml-1">
        <?php D(count($complete_orders)); ?>
        </span> </a> </li>
      <li class="nav-item"> <a href="#cancelled" data-toggle="tab" class="nav-link <?php if($tab=='cancelled'){ D("active");}?>">
        <?php D(__('seller_order_page_tab_CANCELLED',"CANCELLED"));?>
        <span class="badge badge-danger ml-1">
        <?php D(count($cancelled_orders)); ?>
        </span> </a> </li>
      <li class="nav-item"> <a href="#all" data-toggle="tab" class="nav-link">
        <?php D(__('seller_order_page_tab_ALL',"ALL"));?>
        <span class="badge badge-site ml-1">
        <?php D(count($all_orders)); ?>
        </span> </a> </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade  <?php if($tab==''  || $tab=='active'){ D("show active");}?>" id="active">
      <div class="dashboard-box mt-0">

            <!-- Headline
            <div class="headline">
                <h4></h4>
            </div> -->

            <div class="content_">
            <?php if($active_orders){
                    ?>
                <ul class="dashboard-box-list">
                <?php
                        foreach($active_orders as $order){
                            
                    ?>
                    <li>
                        <!-- Job Listing -->
                        <div class="job-listing">

                            <!-- Job Listing Details -->
                            <div class="job-listing-details">

                                <!-- Logo -->
                                <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="job-listing-company-logo">
                                    <img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="img-fluid">
                                </a>
                                

                                <!-- Details -->
                                <div class="job-listing-description">
                                    <h3 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><?php D($order->proposal_title); ?></a> <span class="dashboard-status-button red"><?php D(ucwords($orderStatus[$order->order_status])); ?></span></h3>

                                    <!-- Job Listing Footer -->
                                    <div class="job-listing-footer">
                                        <ul>                                            
                                            <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_ORDER_DATE',"ORDER DATE"));?>:</b> <span><?php D(dateFormat($order->order_date,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_DUE_ON',"DUE ON"));?>:</b> <span><?php D(dateFormat($order->order_time,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-tag"></i> <b><?php D(__('seller_order_page_TOTAL',"TOTAL"));?>:</b> <?php D($s_currency); ?> <span><?php D($order->order_price); ?></span></li>
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <?php // D(__('seller_order_page_ORDER_SUMMARY',"ORDER SUMMARY"));?>
						<?php // D(__('seller_order_page_STATUS',"STATUS"));?>
                        <!--<div class="buttons-to-right always-visible">
                            <a href="dashboard-manage-candidates.html" class="button ripple-effect"><i class="icon-material-outline-supervisor-account"></i> Manage Candidates <span class="button-info">0</span></a>
                            <a href="#" class="button gray ripple-effect ico" title="Edit" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                            <a href="#" class="button gray ripple-effect ico" title="Remove" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a>
                        </div>-->
                    </li>
                <?php		
                }
                ?>
                </ul>
                <?php
                }else{?>          
          <div class='p-4'>
            <div class="alert alert-danger mb-0">
              <?php D(__('seller_order_page_no_record_active',"No active orders at the momment."));?>
            </div>
          </div>
          <?php }?>
            </div>
		</div>
        
      </div>
      <div class="tab-pane <?php if($tab=='delivered'){ D("show active");}?>" id="delivered">
        <div class="dashboard-box mt-0">            
          <?php if($delivered_orders){
                    ?>
            <ul class="dashboard-box-list">
              <?php /*?><tr>
                <th><?php D(__('seller_order_page_ORDER_SUMMARY',"ORDER SUMMARY"));?></th>
                <th><?php D(__('seller_order_page_ORDER_DATE',"ORDER DATE"));?></th>
                <th><?php D(__('seller_order_page_DUE_ON',"DUE ON"));?></th>
                <th><?php D(__('seller_order_page_TOTAL',"TOTAL"));?></th>
                <th><?php D(__('seller_order_page_STATUS',"STATUS"));?></th>
              </tr><?php */?>
            
              <?php
                    foreach($delivered_orders as $order){
                        
                ?>
              <li>
              <!-- Job Listing -->
                <div class="job-listing">
                <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="job-listing-company-logo">
                            <img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="img-fluid">
                        </a>
                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><?php D($order->proposal_title); ?></a> <span class="dashboard-status-button green"><?php D(ucwords($orderStatus[$order->order_status])); ?></span></h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>                                            
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_ORDER_DATE',"ORDER DATE"));?>:</b> <span><?php D(dateFormat($order->order_date,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_DUE_ON',"DUE ON"));?>:</b> <span><?php D(dateFormat($order->order_time,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-tag"></i> <b><?php D(__('seller_order_page_TOTAL',"TOTAL"));?>:</b> <?php D($s_currency); ?> <span><?php D($order->order_price); ?></span></li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
              </li>
              <?php		
                    }
                    ?>
            </ul>
          <?php
                }else{?>
          <div class='p-4'>
            <div class="alert alert-danger mb-0">
              <?php D(__('seller_order_page_no_record_delivered',"No recent deliveries yet."));?>
            </div>
          </div>
          <?php }?>
        </div>
      </div>
      <div class="tab-pane <?php if($tab=='completed'){ D("show active");}?>" id="completed">
        <div class="dashboard-box mt-0">
          <?php if($complete_orders){
                 ?>
             <ul class="dashboard-box-list">
            <?php /*?><thead>
              <tr>
                <th><?php D(__('seller_order_page_ORDER_SUMMARY',"ORDER SUMMARY"));?></th>
                <th><?php D(__('seller_order_page_ORDER_DATE',"ORDER DATE"));?></th>
                <th><?php D(__('seller_order_page_DUE_ON',"DUE ON"));?></th>
                <th><?php D(__('seller_order_page_TOTAL',"TOTAL"));?></th>
                <th><?php D(__('seller_order_page_STATUS',"STATUS"));?></th>
              </tr>
            </thead><?php */?>

              <?php
                    foreach($complete_orders as $order){
                        
                ?>
                
              <li>
                <!-- Job Listing -->
                <div class="job-listing">
                <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="job-listing-company-logo">
                            <img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="img-fluid">
                        </a>
                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><?php D($order->proposal_title); ?></a> <span class="dashboard-status-button green"><?php D(ucwords($orderStatus[$order->order_status])); ?></span></h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>                                            
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_ORDER_DATE',"ORDER DATE"));?>:</b> <span><?php D(dateFormat($order->order_date,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_DUE_ON',"DUE ON"));?>:</b> <span><?php D(dateFormat($order->order_time,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-tag"></i> <b><?php D(__('seller_order_page_TOTAL',"TOTAL"));?>:</b> <?php D($s_currency); ?> <span><?php D($order->order_price); ?></span></li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
              </li>
              <?php		
                    }
                    ?>
            
          </ul>
          <?php
                }else{?>
          <div class='p-4'>
            <div class="alert alert-danger mb-0">
              <?php D(__('seller_order_page_no_record_completed',"No proposals/services sold have been completed yet."));?>
            </div>
          </div>
            
          <?php }?>
        </div>
      </div>
      <div class="tab-pane <?php if($tab=='cancelled'){ D("show active");}?>" id="cancelled">
        <div class="dashboard-box mt-0">
          <?php if($cancelled_orders){
                    ?>
				<ul class="dashboard-box-list">
              <?php
                    foreach($cancelled_orders as $order){
                        
                ?>
              <li>
              	<!-- Job Listing -->
                <div class="job-listing">
                <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="job-listing-company-logo">
                            <img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="img-fluid">
                        </a>
                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><?php D($order->proposal_title); ?></a> <span class="dashboard-status-button red"><?php D(ucwords($orderStatus[$order->order_status])); ?></span></h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>                                            
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_ORDER_DATE',"ORDER DATE"));?>:</b> <span><?php D(dateFormat($order->order_date,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_DUE_ON',"DUE ON"));?>:</b> <span><?php D(dateFormat($order->order_time,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-tag"></i> <b><?php D(__('seller_order_page_TOTAL',"TOTAL"));?>:</b> <?php D($s_currency); ?> <span><?php D($order->order_price); ?></span></li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
              </li>
              <?php		
                    }
                    ?>
            </ul>
   
          <?php
                }else{?>
            <div class='p-4'>
            <div class="alert alert-danger mb-0">
              <?php D(__('seller_order_page_no_record_cancelled',"No proposals/services have been cancelled."));?>
            </div>
          </div>
          
          <?php }?>
        </div>
      </div>
      <div class="tab-pane" id="all">
        <div class="dashboard-box mt-0">
          <?php if($all_orders){
                    ?>
            <ul class="dashboard-box-list">
              <?php
                    foreach($all_orders as $order){
                        
                ?>
              <li>
                <!-- Job Listing -->
                <div class="job-listing">
                <!-- Job Listing Details -->
                    <div class="job-listing-details">
                        <!-- Logo -->
                        <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="job-listing-company-logo">
                            <img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="img-fluid">
                        </a>
                        <!-- Details -->
                        <div class="job-listing-description">
						<?php $class='';
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
                            <h3 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><?php D($order->proposal_title); ?></a>                            
                            <span class="dashboard-status-button <?php echo $class;?>"><?php D(ucwords($orderStatus[$order->order_status])); ?></span>
							
                            </h3>
                            
                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>                                            
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_ORDER_DATE',"ORDER DATE"));?>:</b> <span><?php D(dateFormat($order->order_date,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-calendar"></i> <b><?php D(__('seller_order_page_DUE_ON',"DUE ON"));?>:</b> <span><?php D(dateFormat($order->order_time,'F d, Y')); ?></span></li>
                                    <li><i class="icon-feather-tag"></i> <b><?php D(__('seller_order_page_TOTAL',"TOTAL"));?>:</b> <?php D($s_currency); ?> <span><?php D($order->order_price); ?></span></li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
              </li>
              <?php		
                    }
                    ?>
            </ul>
          <?php
                }else{?>
          <div class='p-4'>
            <div class="alert alert-danger mb-0">
              <?php D(__('seller_order_page_no_record_all',"No proposals/services sold at the momment."));?>
            </div>
          </div>
          
          <?php }?>
        </div>
      </div>
    </div>
      </div>
    </div>
  </div>
</section>
