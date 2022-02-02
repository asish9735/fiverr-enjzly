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
<div class="container-fluid mt-5">
	<div class="row">
		<div class="col-md-12">
			<h1><?php D(__('buyer_order_page_heading',"Manage Proposals/Services Ordered"));?></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 mt-5 mb-3">
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a href="#active" data-toggle="tab" class="nav-link active make-black">
						<?php D(__('buyer_order_page_tab_ACTIVE',"ACTIVE"));?> <span class="badge badge-success"> <?php D(count($active_orders)); ?></span>
					</a>
				</li>
				<li class="nav-item">
					<a href="#delivered" data-toggle="tab" class="nav-link make-black">
						<?php D(__('buyer_order_page_tab_DELIVERED',"DELIVERED"));?> <span class="badge badge-success"><?php D(count($delivered_orders)); ?> </span>
					</a>
				</li>
				<li class="nav-item">
					<a href="#completed" data-toggle="tab" class="nav-link make-black">
						<?php D(__('buyer_order_page_tab_COMPLETED',"COMPLETED"));?> <span class="badge badge-success"><?php D(count($complete_orders)); ?></span>
						


					</a>
									

				</li>

				<li class="nav-item">

					<a href="#cancelled" data-toggle="tab" class="nav-link make-black">

						<?php D(__('buyer_order_page_tab_CANCELLED',"CANCELLED"));?> <span class="badge badge-success"><?php D(count($cancelled_orders)); ?> </span>
						


					</a>
									

				</li>

				<li class="nav-item">

					<a href="#all" data-toggle="tab" class="nav-link make-black">

						<?php D(__('buyer_order_page_tab_ALL',"ALL"));?> <span class="badge badge-success"><?php D(count($all_orders)); ?></span>
						


					</a>
									

				</li>

				
			</ul>

			<div class="tab-content">

				<div class="tab-pane fade show active" id="active">
					<div class="dashboard-box mt-0">
					<?php if($active_orders){
						?>
						<ul class="dashboard-box-list">
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
								<li>
                                <div class="job-listing">
                                    <div class="job-listing-details">
                                    <div class="job-listing-company-logo">
                                        <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>" alt="" class="fluid-img"></a>
                                    </div>
                                    <div class="job-listing-description">
                                    <h3 class="job-listing-title mb-0"><a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>"><?php D($order->proposal_title); ?></a>
                                    <span class="dashboard-status-button red"><?php D(ucwords($orderStatus[$order->order_status])); ?></span></h3>
                                    <div class="job-listing-footer">
                                        <ul>
                                            <li><i class="icon-feather-calendar"></i> <b><?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?>:</b> <?php D(dateFormat($order->order_date,'F d, Y')); ?></li>
                                            <li><i class="icon-feather-calendar"></i> <b><?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?>:</b> <?php D(dateFormat($order->order_time,'F d, Y')); ?></li>
                                            <li><i class="icon-feather-tag"></i><b><?php D(__('buyer_order_page_TOTAL',"TOTAL"));?>:</b> <?php D($s_currency); ?><?php D($order->order_price); ?></li>
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
					<div class="alert alert-danger mb-0">
						 <?php D(__('buyer_order_page_no_record_active',"No active purchases at the momment."));?>
					</div>
					<?php }?>
					
					</div>
				</div>



				<div class="tab-pane" id="delivered">

					<div class="dashboard-box mt-0">
					<?php if($delivered_orders){
						?>
						<table class="table table-bordered table-middle">
							<thead>
								<tr> 
									<th><?php D(__('buyer_order_page_ORDER_SUMMARY',"ORDER SUMMARY"));?></th>
									<th><?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?></th>
									<th><?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?></th>
									<th><?php D(__('buyer_order_page_TOTAL',"TOTAL"));?></th>
									<th><?php D(__('buyer_order_page_STATUS',"STATUS"));?></th>
								</tr>
							</thead>
							<tbody>
						<?php
						foreach($delivered_orders as $order){
							
					?>
								<tr>
									<td>
									<a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="make-black">
										<img class="order-proposal-image" src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>">
										<p class="order-proposal-title"><?php D($order->proposal_title); ?></p>
									</a>
									</td>

									<td><?php D(dateFormat($order->order_date,'F d, Y')); ?></td>
									<td><?php D(dateFormat($order->order_time,'F d, Y')); ?> </td>
									<td><?php D($s_currency); ?><?php D($order->order_price); ?></td>
									<td>
										<button class="btn btn-success"><?php D(ucwords($orderStatus[$order->order_status])); ?></button>
									</td>
								</tr>
					<?php		
						}
						?>
							</tbody>
						</table>
					<?php
					}else{?>
                    <div class="alert alert-danger mb-0">
						 <?php D(__('buyer_order_page_no_record_delivered',"No proposals/services have been recently delivered yet."));?>
					</div>
					
					<?php }?>
					
					</div>

				</div>


				<div class="tab-pane" id="completed">

					<div class="table-responsive box-table mt-3">
					<?php if($complete_orders){
						?>
						<table class="table table-bordered table-middle">
							<thead>
								<tr> 
									<th><?php D(__('buyer_order_page_ORDER_SUMMARY',"ORDER SUMMARY"));?></th>
									<th><?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?></th>
									<th><?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?></th>
									<th><?php D(__('buyer_order_page_TOTAL',"TOTAL"));?></th>
									<th><?php D(__('buyer_order_page_STATUS',"STATUS"));?></th>
								</tr>
							</thead>
							<tbody>
						<?php
						foreach($complete_orders as $order){
							
					?>
								<tr>
									<td>
									<a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="make-black">
										<img class="order-proposal-image" src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>">
										<p class="order-proposal-title"><?php D($order->proposal_title); ?></p>
									</a>
									</td>

									<td><?php D(dateFormat($order->order_date,'F d, Y')); ?></td>
									<td><?php D(dateFormat($order->order_time,'F d, Y')); ?> </td>
									<td><?php D($s_currency); ?><?php D($order->order_price); ?></td>
									<td>
										<button class="btn btn-success"><?php D(ucwords($orderStatus[$order->order_status])); ?></button>
									</td>
								</tr>
					<?php		
						}
						?>
							</tbody>
						</table>
					<?php
					}else{?>
					<center>
						<h3 class='pb-4 pt-4'><i class='fa fa-meh-o'></i> <?php D(__('buyer_order_page_no_record_completed',"No proposals/services purchased have been completed yet."));?></h3>
					</center>
					<?php }?>
					
					</div>

				</div>

				<div class="tab-pane" id="cancelled">

					<div class="table-responsive box-table mt-3">
					<?php if($cancelled_orders){
						?>
						<table class="table table-bordered table-middle">
							<thead>
								<tr> 
									<th><?php D(__('buyer_order_page_ORDER_SUMMARY',"ORDER SUMMARY"));?></th>
									<th><?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?></th>
									<th><?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?></th>
									<th><?php D(__('buyer_order_page_TOTAL',"TOTAL"));?></th>
									<th><?php D(__('buyer_order_page_STATUS',"STATUS"));?></th>
								</tr>
							</thead>
							<tbody>
						<?php
						foreach($cancelled_orders as $order){
							
					?>
								<tr>
									<td>
									<a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="make-black">
										<img class="order-proposal-image" src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>">
										<p class="order-proposal-title"><?php D($order->proposal_title); ?></p>
									</a>
									</td>

									<td><?php D(dateFormat($order->order_date,'F d, Y')); ?></td>
									<td><?php D(dateFormat($order->order_time,'F d, Y')); ?> </td>
									<td><?php D($s_currency); ?><?php D($order->order_price); ?></td>
									<td>
										<button class="btn btn-success"><?php D(ucwords($orderStatus[$order->order_status])); ?></button>
									</td>
								</tr>
					<?php		
						}
						?>
							</tbody>
						</table>
					<?php
					}else{?>
					<center>
						<h3 class='pb-4 pt-4'><i class='fa fa-meh-o'></i> <?php D(__('buyer_order_page_no_record_cancelled',"No proposals/services have been cancelled."));?></h3>
					</center>
					<?php }?>
					
					</div>

				</div>

				<div class="tab-pane" id="all">

					<div class="table-responsive box-table mt-3">
					<?php if($all_orders){
						?>
						<table class="table table-bordered table-middle">
							<thead>
								<tr> 
									<th><?php D(__('buyer_order_page_ORDER_SUMMARY',"ORDER SUMMARY"));?></th>
									<th><?php D(__('buyer_order_page_ORDER_DATE',"ORDER DATE"));?></th>
									<th><?php D(__('buyer_order_page_DUE_ON',"DUE ON"));?></th>
									<th><?php D(__('buyer_order_page_TOTAL',"TOTAL"));?></th>
									<th><?php D(__('buyer_order_page_STATUS',"STATUS"));?></th>
								</tr>
							</thead>
							<tbody>
						<?php
						foreach($all_orders as $order){
							
					?>
								<tr>
									<td>
									<a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="make-black">
										<img class="order-proposal-image" src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($order->proposal_image); ?>">
										<p class="order-proposal-title"><?php D($order->proposal_title); ?></p>
									</a>
									</td>

									<td><?php D(dateFormat($order->order_date,'F d, Y')); ?></td>
									<td><?php D(dateFormat($order->order_time,'F d, Y')); ?> </td>
									<td><?php D($s_currency); ?><?php D($order->order_price); ?></td>
									<td>
										<button class="btn btn-success"><?php D(ucwords($orderStatus[$order->order_status])); ?></button>
									</td>
								</tr>
					<?php		
						}
						?>
							</tbody>
						</table>
					<?php
					}else{?>
					<center>
						<h3 class='pb-4 pt-4'><i class='fa fa-meh-o'></i> <?php D(__('buyer_order_page_no_record_all',"No proposals/services purchases at the momment."));?></h3>
					</center>
					<?php }?>
					
					</div>

				</div>


			</div>



		</div>

	</div>

</div>