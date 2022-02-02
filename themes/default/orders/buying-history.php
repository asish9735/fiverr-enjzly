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
$seller_user_name=getUserName($member_details['member']->member_id);
?>
<div class="breadcrumbs">
	<div class="container-fluid">
		<h1><?php D(__('buying_history_page_Sales_To',"Sales To"));?> <a href="<?php D(get_link('viewprofileURL').$seller_user_name); ?>"><?php /*D(ucfirst($member_details['member']->member_name));*/ D($seller_user_name);?></a> </h1>
		<p><?php D(count($all_orders)); ?> <?php D(__('buying_history_page_Results_Found',"Results Found"));?></p>
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
				<div class="table-responsive">
					<?php if($all_orders){
						?>
						<table class="table table-bordered table-middle bg-white">
							<thead>
								<tr> 
									<th><?php D(__('buying_history_page_ORDER_SUMMARY',"ORDER SUMMARY"));?></th>
									<th><?php D(__('buying_history_page_ORDER_DATE',"ORDER DATE"));?></th>
									<th><?php D(__('buying_history_page_DUE_ON',"DUE ON"));?></th>
									<th><?php D(__('buying_history_page_TOTAL',"TOTAL"));?></th>
									<th><?php D(__('buying_history_page_STATUS',"STATUS"));?></th>
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
										<button class="btn btn-sm btn-outline-site"><?php D(ucwords($orderStatus[$order->order_status])); ?></button>
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
						<h3 class='pb-4 pt-4'><i class='fa fa-meh-o'></i> <?php D(__('buying_history_page_no_sold',"No gigs sold at the momment."));?></h3>
					</center>
					<?php }?>
				
				</div>
			</div>
			</div>
		</div>
	</div>
</section>