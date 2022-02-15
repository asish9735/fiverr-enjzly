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
    <h1>
      <?php D(__('buyer_order_page_heading',"Manage Proposals/Services Ordered"));?>
    </h1>
  </div>
</div>
<section class="section">
  <div class="container-fluid">
  <div class="row">
      <div class="col-xl-3 col-lg-auto col-12">
        <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
      </div>
      <div class="col-xl-9 col-lg col-12">
        <ul class="nav nav-pills">
          <li class="nav-item"> <a href="#active" data-toggle="tab" class="nav-link active">
            <?php D(__('buyer_order_page_tab_ACTIVE',"Active"));?>
            <span class="badge badge-site ml-1">
            <?php D(count($active_orders)); ?>
            </span> </a> </li>
          <li class="nav-item"> <a href="#delivered" data-toggle="tab" class="nav-link">
            <?php D(__('buyer_order_page_tab_DELIVERED',"Delivered"));?>
            <span class="badge badge-site ml-1">
            <?php D(count($delivered_orders)); ?>
            </span> </a> </li>
          <li class="nav-item"> <a href="#completed" data-toggle="tab" class="nav-link">
            <?php D(__('buyer_order_page_tab_COMPLETED',"COMPLETED"));?>
            <span class="badge badge-success ml-1">
            <?php D(count($complete_orders)); ?>
            </span> </a> </li>
          <li class="nav-item"> <a href="#cancelled" data-toggle="tab" class="nav-link">
            <?php D(__('buyer_order_page_tab_CANCELLED',"CANCELLED"));?>
            <span class="badge badge-danger ml-1">
            <?php D(count($cancelled_orders)); ?>
            </span> </a> </li>
          <li class="nav-item"> <a href="#all" data-toggle="tab" class="nav-link">
            <?php D(__('buyer_order_page_tab_ALL',"ALL"));?>
            <span class="badge badge-site ml-1">
            <?php D(count($all_orders)); ?>
            </span> </a> </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="active">
            <div class="dashboard-box mt-0">
              <ul class="dashboard-box-list">
                <?php if($active_orders){
                    ?>
                  <?php /*?><thead>
                              <tr> 
                                  <th><?php D(__('buyer_order_page_ORDER_SUMMARY',"ORDER SUMMARY"));?></th>
                                  <th><?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?></th>
                                  <th><?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?></th>
                                  <th><?php D(__('buyer_order_page_TOTAL',"TOTAL"));?></th>
                                  <th><?php D(__('buyer_order_page_STATUS',"STATUS"));?></th>
                              </tr>
                          </thead><?php */?>
                  <?php
                      foreach($active_orders as $order){
                          
                  ?>
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
                  <li>
                    <div class="job-listing">
                      <div class="job-listing-details">
                        <div class="job-listing-company-logo"> <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="fluid-img"></a> </div>
                        <div class="job-listing-description">                        
                          <h4 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>">
                            <?php D($order->proposal_title); ?>
                            </a> </h4>
                          <div class="job-listing-footer">
                            <ul>
                              <li><i class="icon-feather-calendar"></i> <b>
                                <?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?>
                                :</b>
                                <?php D(dateFormat($order->order_date,'F d, Y')); ?>
                              </li>
                              <li><i class="icon-feather-calendar"></i> <b>
                                <?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?>
                                :</b>
                                <?php D(dateFormat($order->order_time,'F d, Y')); ?>
                              </li>
                              <li>
                                <span class="dashboard-status-button <?php echo $class;?>">
									<?php D(ucwords($orderStatus[$order->order_status])); ?>                        
                                </span>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="buttons-to-right single-right-button">
                    	<h3 class="price"><span><?php D($s_currency); ?></span><?php D($order->order_price); ?></h3>
                    </div>
                  </li>
                  <?php		
                      }
                      ?>
                  <?php
                  }else{?>
                <li>
                  <div class="text-center w-100">
                    <h2 class="icon-line-awesome-info-circle text-danger"></h2>
                    <h5><?php D(__('buyer_order_page_no_record_active',"No active purchases at the momment."));?></h5>
                  </div>
                </li>
                <?php }?>
              </ul>
          </div>
      </div>
      <div class="tab-pane" id="delivered">
        <div class="dashboard-box mt-0">
          <ul class="dashboard-box-list">
            <?php if($delivered_orders){
                ?>
            <?php
                foreach($delivered_orders as $order){
                    
            ?>
            <li>
              <div class="job-listing">
                <div class="job-listing-details">
                  <div class="job-listing-company-logo"> <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="fluid-img"></a> </div>
                  <div class="job-listing-description">
                    <h4 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>">
                      <?php D($order->proposal_title); ?>
                      </a> </h4>
                    <div class="job-listing-footer">
                      <ul>
                        <li><i class="icon-feather-calendar"></i> <b>
                          <?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?>
                          :</b>
                          <?php D(dateFormat($order->order_date,'F d, Y')); ?>
                        </li>
                        <li><i class="icon-feather-calendar"></i> <b>
                          <?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?>
                          :</b>
                          <?php D(dateFormat($order->order_time,'F d, Y')); ?>
                        </li>
                        <li><span class="dashboard-status-button red"><?php D(ucwords($orderStatus[$order->order_status])); ?></span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="buttons-to-right single-right-button">              
			  	<h3 class="price"><span><?php D($s_currency); ?></span><?php D($order->order_price); ?></h3>              
              </div>
            </li>
            <?php		
                }
                ?>
            <?php
            }else{?>
            <li>
              <div class="text-center w-100">
                <h2 class="icon-line-awesome-info-circle text-danger"></h2>
                <h5><?php D(__('buyer_order_page_no_record_delivered',"No proposals/services have been recently delivered yet."));?></h5>
              </div>
            </li>
            <?php }?>
          </ul>
        </div>
      </div>
      <div class="tab-pane" id="completed">
        <div class="dashboard-box mt-0">
          <ul class="dashboard-box-list">
            <?php if($complete_orders){
                ?>
            <?php
                foreach($complete_orders as $order){
                    
            ?>
            <li>
              <div class="job-listing">
                <div class="job-listing-details">
                  <div class="job-listing-company-logo"> <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="fluid-img"></a> </div>
                  <div class="job-listing-description">
                    <h4 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>">
                      <?php D($order->proposal_title); ?>
                      </a> </h4>
                    <div class="job-listing-footer">
                      <ul>
                        <li><i class="icon-feather-calendar"></i> <b>
                          <?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?>
                          :</b>
                          <?php D(dateFormat($order->order_date,'F d, Y')); ?>
                        </li>
                        <li><i class="icon-feather-calendar"></i> <b>
                          <?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?>
                          :</b>
                          <?php D(dateFormat($order->order_time,'F d, Y')); ?>
                        </li>
                        <li><span class="dashboard-status-button green">
							<?php D(ucwords($orderStatus[$order->order_status])); ?>
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="buttons-to-right single-right-button">
              	<h3 class="price"><span><?php D($s_currency); ?></span><?php D($order->order_price); ?></h3>
              </div>
            </li>
            <?php		
                }
                ?>
            <?php
            }else{?>
            <li>
              <div class="text-center w-100">
                <h2 class="icon-line-awesome-info-circle text-danger"></h2>
                <h5><?php D(__('buyer_order_page_no_record_completed',"No proposals/services purchased have been completed yet."));?></h5>
              </div>
            </li>
            <?php }?>
          </ul>
        </div>
      </div>
      <div class="tab-pane" id="cancelled">
        <div class="dashboard-box mt-0">
          <ul class="dashboard-box-list">
            <?php if($cancelled_orders){
                ?>
            <?php
                foreach($cancelled_orders as $order){
                    
            ?>
            <li>
              <div class="job-listing">
                <div class="job-listing-details">
                  <div class="job-listing-company-logo"> <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="fluid-img"></a> </div>
                  <div class="job-listing-description">
                    <h4 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>">
                      <?php D($order->proposal_title); ?>
                      </a></h4>
                    <div class="job-listing-footer">
                      <ul>
                        <li><i class="icon-feather-calendar"></i> <b>
                          <?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?>
                          :</b>
                          <?php D(dateFormat($order->order_date,'F d, Y')); ?>
                        </li>
                        <li><i class="icon-feather-calendar"></i> <b>
                          <?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?>
                          :</b>
                          <?php D(dateFormat($order->order_time,'F d, Y')); ?>
                        </li>
                        <li><span class="dashboard-status-button red">
			 	 <?php D(ucwords($orderStatus[$order->order_status])); ?>
              </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="buttons-to-right single-right-button">
              	<h3 class="price"><span><?php D($s_currency); ?></span><?php D($order->order_price); ?></h3>
              </div>
            </li>
            <?php		
                }
                ?>
            <?php
            }else{?>
            <li>
              <div class="text-center w-100">
                <h2 class="icon-line-awesome-info-circle text-danger"></h2>
                <h5><?php D(__('buyer_order_page_no_record_cancelled',"No proposals/services have been cancelled."));?></h5>
              </div>
            </li>
            <?php }?>
          </ul>
        </div>
      </div>
      <div class="tab-pane" id="all">
        <div class="dashboard-box mt-0">
          <ul class="dashboard-box-list">
            <?php if($all_orders){
                ?>
            <?php
                foreach($all_orders as $order){
                    
            ?>
            <li>
              <div class="job-listing">
                <div class="job-listing-details">
                  <div class="job-listing-company-logo"> <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="fluid-img"></a> </div>
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
                    <h4 class="job-listing-title"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>">
                      <?php D($order->proposal_title); ?>
                      </a> </h4>
                    <div class="job-listing-footer">
                      <ul>
                        <li><i class="icon-feather-calendar"></i> <b>
                          <?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?>
                          :</b>
                          <?php D(dateFormat($order->order_date,'F d, Y')); ?>
                        </li>
                        <li><i class="icon-feather-calendar"></i> <b>
                          <?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?>
                          :</b>
                          <?php D(dateFormat($order->order_time,'F d, Y')); ?>
                        </li>
                        <li><i class="icon-feather-tag"></i><b>
                          <?php D(__('buyer_order_page_TOTAL',"TOTAL"));?>
                          :</b>
                          <?php D($s_currency); ?>
                          <?php D($order->order_price); ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="buttons-to-right single-right-button">
              <span class="dashboard-status-button <?php echo $class;?>">
			  	<?php D(ucwords($orderStatus[$order->order_status])); ?>
              </span>
              </div>
            </li>
            <?php		
                }
                ?>
            <?php
            }else{?>
            <li>
              <div class="text-center w-100">
                <h2 class="icon-line-awesome-info-circle text-danger"></h2>
                <h5><?php D(__('buyer_order_page_no_record_all',"No proposals/services purchases at the momment."));?></h5>
              </div>
            </li>
            <?php }?>
          </ul>
        </div>
      </div>
    </div>
      </div>
  </div>
  </div>
</section>
