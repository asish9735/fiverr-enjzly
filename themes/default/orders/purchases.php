<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$s_currency=CURRENCY;
//dd($all_orders,TRUE);
?>
<div class="breadcrumbs">
  <div class="container">
  	<h1><?php D(__('purchases_page_heading',"Purchases"));?></h1>
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
        <div class="dashboard-box mt-0">
            <ul class="dashboard-box-list">
            <?php if($all_orders){?>
            
            <?php /*?><thead>
                <tr>
                    <th><?php D(__('purchases_page_Date',"Date"));?></th>
                    <th><?php D(__('purchases_page_For',"For"));?></th>
                    <th><?php D(__('purchases_page_Amount',"Amount"));?></th>
                </tr>
            </thead><?php */?>
            
            <?php 
            foreach($all_orders as $order){
            ?>
                
                <li>
                <div class="job-listing">
                    <div class="job-listing-details">                            
                    <div class="job-listing-description">
                    
                    <h5 class="job-listing-title">
                
                    <?php if($order->name == "order_payment_wallet"){ ?>
                        <?php D(__('purchases_page_purchased_with_Shopping_Balance',"Proposal/Service purchased with Shopping Balance"));?> 
                        <?php }elseif($order->name == "order_payment_stripe"){?>
                        <?php D(__('purchases_page_purchased_with_stripe',"Deposit from credit card / stripe"));?> 
                        <?php }elseif($order->name == "order_payment_paypal"){?>
                        <?php D(__('purchases_page_purchased_with_paypal',"Payment for purchase with paypal"));?>
                        <?php }elseif($order->name == "order_payment_telr"){?>
                        <?php D(__('purchases_page_purchased_with_telr',"Payment for purchase with telr"));?>
                        <?php }elseif($order->name == "order_payment_ngenius"){?>
                        <?php D(__('purchases_page_purchased_with_ngenius',"Payment for purchase with ngenius"));?>
                        <?php }elseif($order->name == "order_payment_bank"){?>
                        <?php D(__('purchases_page_purchased_with_bank',"Payment for purchase with bank transfer"));?>
                        <?php }elseif($order->name == "order_payment_payza"){?>
                        <?php D(__('purchases_page_purchased_with_payza',"Payment for purchase with payza"));?>
                        <?php }elseif($order->name == "order_payment_coinpayments"){?>
                        <?php D(__('purchases_page_purchased_with_coinpayments',"Payment for purchase with coinpayments"));?>
                        <?php }elseif($order->name == "order_payment_mobile_money"){?>
                        <?php D(__('purchases_page_purchased_with_mobile_money',"Payment for purchase with mobile money"));?>
                        <?php }elseif($order->name == "order_payment_refund"){?>
                        <?php D(__('purchases_page_Cancelled_payment_refunded',"Cancelled order payment refunded to your shopping  balance"));?>
                        <?php }?>
                        </h5>
                        
                    <div class="job-listing-footer">
                        <ul>
                            <li><i class="icon-feather-calendar"></i> <b><?php D(__('purchases_page_Date',"Date"));?>:</b> <?php D(dateFormat($order->created_date,'F d, Y')); ?></li>
                            
                            <li><i class="icon-feather-tag"></i> <b><?php D(__('purchases_page_Amount',"Amount"));?>:</b>
                            <?php 
                        if($order->name == "order_payment_refund"){
                        ?>
                        <span class='text-success'><?php D('+ '.$s_currency.displayamount($order->Amount))?></span>
                        <?php
                            }else{
                        ?>
                        <span class='text-danger'><?php D('- '.$s_currency.displayamount(abs($order->Amount)));  ?></span>
                    <?php
                            }
                        ?>  
                            </li>
                        </ul>
                    </div>
                    
                </div>
                </div>
                </div>
                <div class="buttons-to-right single-right-button always-visible">
                    <a href="<?php D(get_link('OrderDetailsURL').$order->order_id)?>" class="btn btn-sm btn-outline-site"> <?php D(__('purchases_page_View_Order',"View Order"));?></a>
                </div>
                    
                </li>
                <?php } ?>
            
            
            <?php
            }else{
            ?>
            <li><div class="alert alert-danger mb-0 w-100"><?php D(__('purchases_page_no_purchase',"You have no purchases to display."));?></div></li>
            <?php   	
            }
            ?>
            </ul>
        </div>			
      </div>
    </div>
</div>
</section>