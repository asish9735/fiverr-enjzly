<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$enable_paypal=get_option_value('enable_paypal');

$enable_stripe=get_option_value('enable_stripe');

$enable_coinpayments=get_option_value('enable_coinpayments');

$enable_payza=get_option_value('enable_payza');

$enable_dusupay=get_option_value('enable_dusupay');

$enable_telr=get_option_value('enable_telr');

$enable_ngenius =get_option_value('enable_ngenius');

$enable_bank =$member_details['member']->bank_transfer_allowed;

$current_balance=$member_details['member']->balance;

$sub_total=$CheckOutData['sub_total'];

$s_currency=CURRENCY;

$processing_fee=get_option_value('processing_fee');

$total = $processing_fee+$sub_total;

//dd($proposal_details,TRUE);

//dd($CheckOutData,TRUE);

$p=0;

?>

<div class="container mt-5 mb-5">
  <div class="row">
    <div class="col-md-7">
        <?php /*if($current_balance >=$sub_total){ ?>
          <div class="card payment-options mb-4">
            <div class="card-header">
              <h5>
                <i class="icon-feather-dollar-sign"></i> <?php D(__('cart_checkout_page_Available_Balance',"Available Shopping Balance"));?>
              </h5>
            </div>
            <div class="card-body">
              <div class="btn-group btn-group-toggle pricing-group" data-toggle="buttons">
                
              </div>
            </div>
          </div>
        <?php } */?>
        
          <div class="card payment-options">
            <div class="card-header">
              <h5><i class="icon-feather-credit-card"></i>
                <?php D(__('cart_checkout_page_Payment_Options',"Payment Options"));?>
              </h5>
            </div>
            <div class="card-body">
              <div class="btn-group btn-group-toggle pricing-group" data-toggle="buttons">
              <?php if($current_balance >=$sub_total){ ?>
              <label for="shopping-balance" class="btn btn-outline-site">
                <input type="radio" name="method" id="shopping-balance"  checked data-processing-fee-text="0" data-processing-fee="0" data-total="<?php D($sub_total);?>">
                <?php D(__('pay_featured_page_Shopping_Balance','Shopping Balance'))?><br>
                <?php D(__('cart_checkout_page_Personal_Balance',"Personal Balance"));?> -<br>
                <?php D($member_details['member']->member_name); ?>
                  <b class="text-site"><?php D($s_currency); ?><?php D($current_balance); ?></b>
                </label>
              <?php } ?>
              <?php if($enable_ngenius == 1){

                	$p++;

                	$feeCalculation=generateProcessingFee('ngenius',$sub_total);

                	 ?>
              
              <div class="form-check">
                  <input id="ngenius" type="radio" name="method" class="form-check-input" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">                
                  <label for="ngenius"><?php D(__('paymentmethod_page_Pay_By_Ngenius',"Pay With Ngenius"));?>
                  <img src="<?php D(theme_url().IMAGE)?>ngenius.png" height="32" alt="" /> </label>
              </div>
              
              <?php } ?>
              <?php if($enable_paypal == 1){

                	$p++;

                	$feeCalculation=generateProcessingFee('paypal',$sub_total);

                	 ?>
              <label for="paypal" class="btn btn-outline-site">
                <input type="radio" name="method" id="paypal" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
                <?php D(__('paymentmethod_page_Pay_By_Paypal',"Pay With Paypal"));?><br>
                <img src="<?php D(theme_url().IMAGE)?>paypal.png" height="48" alt="" />
              </label>
              <?php } ?>
              <?php if($enable_bank == 1){
                	$p++;
                	$feeCalculation=generateProcessingFee('bank',$sub_total);
                	 ?>
              <label for="bank" class="btn btn-outline-site">
                <input type="radio" name="method" id="bank" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
                <?php D(__('paymentmethod_page_Pay_By_Bank',"Pay With Bank Transfer"));?><br>
                <img src="<?php D(theme_url().IMAGE)?>bank-transfer.png" height="48" alt="" />
              </label>
              
              <?php } ?>
              <?php if($enable_telr == 1){
                	$p++;
                	$feeCalculation=generateProcessingFee('telr',$sub_total);
                	 ?>
              <div class="form-check">
                  <input id="telr" type="radio" name="method" class="form-check-input" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
                 <label for="telr">
                  <?php D(__('paymentmethod_page_Pay_By_Telr',"Pay With Telr"));?>
                  <img src="<?php D(theme_url().IMAGE)?>telr.png" height="32" alt="" /> 
                  </label>
              </div>
              
              <?php } ?>
              <?php if($enable_stripe == 1){

                if($p>0){D('<hr>');}

                $p++;

                ?>
              <div class="form-check">                
                <input id="credit-card" type="radio" name="method" class="form-check-input">
                <label for="credit-card"><img src="<?php D(theme_url().IMAGE)?>credit_cards.jpg" height="32" alt="" /></label>
              </div>
              <?php } ?>
              <?php if($enable_payza == 1){

                if($p>0){D('<hr>');}

                $p++;

                ?>
              <div class="form-check">
                  <input id="payza" type="radio" name="method" class="form-check-input" >
               	  <label for="payza"> <img src="<?php D(theme_url().IMAGE)?>payza.jpg" height="32" alt="" /> </label>
              </div>
              <?php } ?>
              <?php if($enable_coinpayments == 1){ 

              if($p>0){D('<hr>');}

              $p++;

              ?>
              <div class="form-check">
               
                  <input id="coinpayments" type="radio" name="method" class="form-check-input" >
                <label for="coinpayments"> <img src="<?php D(theme_url().IMAGE)?>coinpayments.png" height="32" alt="" /></label>
              </div>
              <?php } ?>
              <?php if($enable_dusupay == 1){

              if($p>0){D('<hr>');}

              $p++;

              ?>
              <div class="form-check">
                
                  <input id="mobile-money" type="radio" name="method" class="form-check-input" >
               
                <label for="mobile-money"><img src="<?php D(theme_url().IMAGE)?>mobile-money.png" height="32" alt="" /> </label>
              </div>
              <?php } ?>
            </div>
            </div>
          </div>
        
    </div>
    <div class="col-md-5">
      <div class="card checkout-details">
        <div class="card-header">
          <h5><i class="icon-feather-file"></i>
            <?php D(__('cart_checkout_page_Order_Summary',"Order Summary"));?>
          </h5>
        </div>
        <div class="card-body">
         <div class="media mb-3">            
            <img src="<?php D(URL_USERUPLOAD)?>proposal-files/<?php D($proposal_details['proposal']->proposal_image); ?>" alt="" class="mr-3" height="64" /> 
            <div class="media-body align-self-center">
              <h5>
                <?php D($proposal_details['proposal']->proposal_title); ?>
              </h5>
            </div>
          </div>
          
          <p class="d-flex justify-content-between">
            <?php D(__('cart_checkout_page_Proposal_Price',"Proposal\'s Price:"));?>
            <span>
            <?php D($s_currency); ?><?php D($CheckOutData['proposal_price']); ?>
            </span></p>
          <?php if($CheckOutData['extra']){ ?>
          
          <p class="d-flex justify-content-between">
            <?php D(__('cart_checkout_page_Proposal_Extras',"Proposal\'s Extras:"));?>
            <span>
            <?php D($s_currency); ?><?php D($CheckOutData['extra_price']); ?>
            </span> </p>
          <?php } ?>
          
          <div class="d-flex justify-content-between mb-2">
            <?php D(__('cart_checkout_page_Proposal_Quantity',"Proposal\'s Quantity:"));?>
            <span class="buy-qty">
            	<span class="qtext">Qty: </span>
                <select class="form-control form-control-sm">                    
                    <option><?php D($CheckOutData['qty']); ?></option>
                    <option>2</option>
                    <option>3</option>
                </select>
            </span>
          </div>
          <p class="d-flex justify-content-between">
            <?php D(__('cart_checkout_page_Processing_Fee',"Processing Fee:"));?>
            <span><?php D($s_currency); ?><ec><?php D($processing_fee); ?></ec>
            </span></p>
          
          <!--<hr class="processing-fee">

					<h6>Appy Coupon Code:</h6>

					<form class="input-group" method="post">

						

						<input type="text" name="code" value="<?php if($CheckOutData['coupon']){D($CheckOutData['coupon']);}?>" class="form-control apply-disabled" placeholder="Enter Coupon Code">

						<button type="submit" name="coupon_submit" class="input-group-addon btn btn-success">Apply</button>

					</form>

					<?php if($CheckOutData['coupon']){?>

					<p class="coupon-response mt-2 p-2 bg-success text-white">Your coupon code has been applied successfully.</p>

					<?php }?>-->
          
          
          <h5 class="d-flex justify-content-between mb-4">
            <b><?php D(__('cart_checkout_page_Proposal_Total',"Proposal\'s Total:"));?></b>
            <span class="total-price">
            <?php D($s_currency); ?>
            <?php D($total); ?>
            </span> </h5>
          
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

        'enable_bank'=>$enable_bank,

        's_currency'=>$s_currency,

        'payfor'=>'checkout',

        'ids'=>'0',

        );

 		$templateLayout=array('view'=>'payment/paymentMethod','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

		load_template($templateLayout,$paymentMethod);

          

         // include("checkoutPayMethods.php"); ?>
        </div>
        <?php if($referred){ ?>
        <div class="card-footer">
          <?php D(__('cart_checkout_page_Referred_By',"Referred By :"));?>
          <b>
          <?php D($referred['username']); ?>
          </b></div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
