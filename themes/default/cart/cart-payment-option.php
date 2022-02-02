<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$enable_paypal=get_option_value('enable_paypal');
$enable_stripe=get_option_value('enable_stripe');
$enable_coinpayments=get_option_value('enable_coinpayments');
$enable_payza=get_option_value('enable_payza');
$enable_dusupay=get_option_value('enable_dusupay');
$enable_telr=get_option_value('enable_telr');
$enable_ngenius =get_option_value('enable_ngenius');
$current_balance=$member_details['member']->balance;
$sub_total = 0;
if($cart){
	foreach($cart as $c=>$cartdata){
		$username=getUserName($cartdata->proposal_seller_id);
		if($cartdata->package_id){
			$proposal_price=$cartdata->price;
		}else{
			$proposal_price=$cartdata->proposal_price;
		}
		if($cartdata->extra){
			foreach($cartdata->extra as $extra){
				$allextra[]=ucfirst($extra->name).' ('.CURRENCY.$extra->price.')';
				$proposal_price+=$extra->price;
			}	
		}
		$price_total = $proposal_price * $cartdata->qty;
		$sub_total += $price_total;
	}
}
$s_currency=CURRENCY;
$processing_fee=get_option_value('processing_fee');
$total = $processing_fee+$sub_total;
//dd($cart,TRUE);
$p=0;
?>
<div class="container mt-5 mb-3">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="float-left mt-2"> <?php D(__('cart_payment_option_your_cart',"Your Cart"));?> (<?php D(count($cart)); ?>) </h5>
            <h5 class="float-right">
                <a class="btn btn-success" href="<?php D(get_link('homeURL'))?>"> <?php D(__('cart_payment_option_Continue_Shopping',"Continue Shopping"));?></a>
            </h5>
        </div>
     </div>
	
	<div class="row">
		<div class="col-md-7">
			
        <?php if($current_balance >=$sub_total){ ?>
				
					<div class="card payment-options mb-4">
						<div class="card-header">
							<h5><i class="icon-feather-dollar-sign"></i> <?php D(__('cart_payment_option_Available_Balance',"Available Shopping Balance"));?></h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-1">
									<input id="shopping-balance" type="radio" name="method" class="form-control radio-input" checked data-processing-fee-text="0" data-processing-fee="0" data-total="<?php D($sub_total);?>">
								</div>
								<div class="col-11">
									<p class="lead mt-2">
									<?php D(__('cart_payment_option_Personal_Balance',"Personal Balance"));?> - <?php D($member_details['member']->member_name); ?>
									<span class="text-success font-weight-bold"><?php D($s_currency); ?><?php D($current_balance); ?></span>
									</p>
								</div>
							</div>
						</div>
					</div>
				
      <?php } ?>
				
					<div class="card payment-options">
						<div class="card-header">
							<h5><i class="fa fa-credit-card"></i> <?php D(__('cart_payment_option_Payment_Options',"Payment Options"));?></h5>
						</div>
						<div class="card-body">
				<?php if($enable_ngenius == 1){
                	$p++;
                	$feeCalculation=generateProcessingFee('ngenius',$sub_total);
                	 ?>
							<div class="row">
								<div class="col-1">
									<input id="ngenius" type="radio" name="method" class="form-control radio-input" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
								</div>
								<div class="col-11">
									<?php D(__('paymentmethod_page_Pay_By_Ngenius',"Pay With Ngenius"));?> <img src="<?php D(theme_url().IMAGE)?>ngenius.png" height="32" alt="" />
								</div>
							</div>
                <?php } ?>		
                <?php if($enable_paypal == 1){
                	$p++;
                	$feeCalculation=generateProcessingFee('paypal',$sub_total);
                	 ?>
							<div class="row">
								<div class="col-1">
									<input id="paypal" type="radio" name="method" class="form-control radio-input" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
								</div>
								<div class="col-11">
									<?php D(__('paymentmethod_page_Pay_By_Paypal',"Pay With Paypal"));?> <img src="<?php D(theme_url().IMAGE)?>paypal.png" height="32" alt="" />
								</div>
							</div>
                <?php } ?>
                <?php if($enable_telr == 1){
                	$p++;
                	$feeCalculation=generateProcessingFee('telr',$sub_total);
                	 ?>
							<div class="row">
								<div class="col-1">
									<input id="telr" type="radio" name="method" class="form-control radio-input" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
								</div>
								<div class="col-11">
									<?php D(__('paymentmethod_page_Pay_By_Telr',"Pay With Telr"));?> <img src="<?php D(theme_url().IMAGE)?>telr.png" height="32" alt="" />
								</div>
							</div>
                <?php } ?>
                
				<?php if($enable_stripe == 1){
						if($p>0){D('<hr>');}
						$p++;
						 ?>
							<div class="row">
								<div class="col-1">
									<input id="credit-card" type="radio" name="method" class="form-control radio-input">
								</div>
								<div class="col-11">
									<img src="<?php D(theme_url().IMAGE)?>credit_cards.jpg" height="32" alt="" />
								</div>
							</div>
                <?php } ?>
  		<?php if($enable_payza == 1){
  			if($p>0){D('<hr>');}
  			$p++;
  			?>
							<div class="row">
								<div class="col-1">
					        		<input id="payza" type="radio" name="method" class="form-control radio-input" >
								</div>
								<div class="col-11">
									<img src="<?php D(theme_url().IMAGE)?>payza.jpg" height="32" alt="" />
								</div>
							</div>
    <?php } ?>

    <?php if($enable_coinpayments == 1){ 
    	if($p>0){D('<hr>');}
    	$p++;
    	 ?>
							<div class="row">
								<div class="col-1">
					        		<input id="coinpayments" type="radio" name="method" class="form-control radio-input" >
								</div>
								<div class="col-11">
									<img src="<?php D(theme_url().IMAGE)?>coinpayments.png" height="32" alt="" />
								</div>
							</div>
    <?php } ?>
    <?php if($enable_dusupay == 1){
    	if($p>0){D('<hr>');}
    	$p++;
    	 ?>
							<div class="row">
								<div class="col-1">
					            	<input id="mobile-money" type="radio" name="method" class="form-control radio-input" >
								</div>
								<div class="col-11">
									<img src="<?php D(theme_url().IMAGE)?>mobile-money.png" height="32" alt="" />
								</div>
							</div>
        <?php } ?>
						</div>
					</div>
				
			
		</div>
		<div class="col-md-5">
			<div class="card">
				<div class="card-body cart-order-details">
					<p><?php D(__('cart_payment_option_Cart_Subtotal',"Cart Subtotal"));?> <span class="float-right"><?php echo $s_currency; ?><?php D($total); ?></span></p>
				
					<hr>
					<p class="processing-fee"><?php D(__('cart_payment_option_Processing_Fee',"Processing Fee"));?> <span class="float-right"><?php D($s_currency); ?><ec><?php D($processing_fee); ?></ec></span></p>
					<hr class="processing-fee">
					<p><?php D(__('cart_payment_option_Total',"Total"));?> <span class="font-weight-bold float-right total-price"><?php D(CURRENCY); ?><?php D($total);?> </span></p>
					<hr>
					 <?php 
			        $paymentMethod=array(
			        'current_balance'=>$current_balance,
			        'sub_total'=>$sub_total,
			        'processing_fee'=>$processing_fee,
			        'total'=>$total,
			        'enable_paypal'=>$enable_paypal,
			        'enable_stripe'=>$enable_stripe,
			        'enable_coinpayments'=>$enable_coinpayments,
			        'enable_payza'=>$enable_payza,
			        'enable_dusupay'=>$enable_dusupay,
			        'enable_telr'=>$enable_telr,
			        'enable_ngenius'=>$enable_ngenius,
			        's_currency'=>$s_currency,
			        'payfor'=>'cart',
			        'ids'=>'0',
			        );
			 		$templateLayout=array('view'=>'payment/paymentMethod','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
					load_template($templateLayout,$paymentMethod);
					?>
					
				</div>
			</div>
		</div>
	</div>
</div>