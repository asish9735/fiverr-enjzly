<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$featured_fee=get_option_value('featured_fee');
$processing_fee=get_option_value('processing_fee');
$featured_duration=get_option_value('featured_duration');
$current_balance=$member_details['member']->balance;
$enable_paypal = get_option_value('enable_paypal');
$enable_stripe =get_option_value('enable_stripe');
$enable_payza =get_option_value('enable_payza');
$enable_coinpayments =get_option_value('enable_coinpayments');
$enable_dusupay =get_option_value('enable_dusupay');
$enable_telr =get_option_value('enable_telr');
$enable_ngenius =get_option_value('enable_ngenius');
$enable_bank =$member_details['member']->bank_transfer_allowed;
$total = $processing_fee+$featured_fee;
$p=0;
?>
<div id="featured-listing-modal" class="modal fade">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content mycustom-modal">
			<div class="modal-header">
				<h4 class="modal-title"> <?php D(__('pay_featured_page_heading','Make Your Proposal/Service Featured'))?></h4>
			</div>
		<div class="modal-body">
			<div class="order-details">
				<div class="request-div">
					<h4 class="mb-3">
						<b><?php D(__('pay_featured_page_fee_info','FEATURE LISTING FEE & INFO:'))?></b> <span class="price pull-right d-none d-sm-block mb-3 font-weight-bold"><?php D(CURRENCY); ?><?php D($featured_fee); ?></span>
					</h4>

					<p>
					<?php D(__('pay_featured_page_fee_info_text_part_1',"You are about to pay a feature listing fee for your proposal/service. This will make this proposal/service feature on our \"Featured proposal/service\" spots. The fee is >"))?>	
						<?php D(CURRENCY); ?><?php D($featured_fee); ?> <?php D(__('pay_featured_page_fee_info_text_part_2',"and the duration is"))?> <?php D($featured_duration); ?> <?php D(__('pay_featured_page_fee_info_text_part_3',"Days. Please use any of the following payment methods below to complete payment."))?>

					</p>

					<h4><b><?php D(__('pay_featured_page_SUMMARY','SUMMARY:'))?></b></h4>

					<p><b><?php D(__('pay_featured_page_Proposal_Title','Proposal Title:'))?></b> <?php echo $proposal_details->proposal_title; ?></p>
					<p class="processing-fee"><b><?php D(__('pay_featured_page_Feature_Listing_Fee','Feature Listing Fee:'))?></b> <?php D(CURRENCY); ?><ec><?php D($processing_fee); ?></ec></p>
					<p><b><?php D(__('pay_featured_page_Feature_Duration','Listing Duration:'))?></b> <?php D($featured_duration); ?> <?php D(__('pay_featured_page_Days','Days.'))?></p>

				</div>

			</div>

			<div class="payment-options-list">
			
			
			
			
                
                <?php if($current_balance >= $featured_fee){
                	$p++;
                	 ?>
				<div class="payment-options mb-2">
					<input type="radio" name="method" id="shopping-balance" class="radio-custom" checked data-processing-fee-text="0" data-processing-fee="0" data-total="<?php D($featured_fee);?>">
					<label for="shopping-balance" class="radio-custom-label" >
					<?php D(__('pay_featured_page_Shopping_Balance','Shopping Balance'))?> </label>
					<p class="lead ml-5">
					<?php D(__('pay_featured_page_Personal_Balance','Personal Balance -'))?> <?php D($member_details['member']->member_name); ?> <span class="text-success font-weight-bold"> <?php D(CURRENCY); ?><?php echo $current_balance; ?> </span>
					</p>
				</div>
                <?php } ?>
                 
                 <?php if($enable_ngenius == 1){ 
                if($p>0){D('<hr>');}
				$p++;
				$feeCalculation=generateProcessingFee('ngenius',$featured_fee);
                ?>
				<div class="payment-option">
					<input type="radio" name="method" id="ngenius" class="radio-custom" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
					<label for="ngenius" class="radio-custom-label"><?php D(__('paymentmethod_page_Pay_By_Ngenius',"Pay With Ngenius"));?> </label>
					<img src="<?php D(theme_url().IMAGE)?>ngenius.png">
				</div>
                <?php } ?>
                <?php if($enable_paypal == 1){ 
                if($p>0){D('<hr>');}
				$p++;
				$feeCalculation=generateProcessingFee('paypal',$featured_fee);
                ?>
				<div class="payment-option">
					<input type="radio" name="method" id="paypal" class="radio-custom" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
					<label for="paypal" class="radio-custom-label"><?php D(__('paymentmethod_page_Pay_By_Paypal',"Pay With Paypal"));?> </label>
					<img src="<?php D(theme_url().IMAGE)?>paypal.png">
				</div>
                <?php } ?>
                <?php if($enable_bank == 1){ 
                if($p>0){D('<hr>');}
				$p++;
				$feeCalculation=generateProcessingFee('bank',$featured_fee);
                ?>
				<div class="payment-option">
					<input type="radio" name="method" id="bank" class="radio-custom" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
					<label for="bank" class="radio-custom-label"><?php D(__('paymentmethod_page_Pay_By_Bank',"Pay With Bank Transfer"));?> </label>
					<img src="<?php D(theme_url().IMAGE)?>bank-transfer.png">
				</div>
                <?php } ?>
                <?php if($enable_telr == 1){ 
                if($p>0){D('<hr>');}
				$p++;
				$feeCalculation=generateProcessingFee('telr',$featured_fee);
                ?>
				<div class="payment-option">
					<input type="radio" name="method" id="telr" class="radio-custom" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
					<label for="telr" class="radio-custom-label"><?php D(__('paymentmethod_page_Pay_By_Telr',"Pay With Telr"));?> </label>
					<img src="<?php D(theme_url().IMAGE)?>telr.png">
				</div>
                <?php } ?>
               
                <?php if($enable_stripe == 1){ 
                if($p>0){D('<hr>');}
				$p++;
				?>
				<div class="payment-option">
					<input type="radio" name="method" id="credit-card" class="radio-custom">
					<label for="credit-card" class="radio-custom-label"></label>
					<img src="<?php D(theme_url().IMAGE)?>credit_cards.jpg">
				</div>
            <?php } ?>
            <?php if($enable_payza == 1){ 
             if($p>0){D('<hr>');}
				$p++;
			?>
				<div class="payment-option">
					<input type="radio" name="method" id="payza" class="radio-custom">
					<label for="payza" class="radio-custom-label"></label>
					<img src="<?php D(theme_url().IMAGE)?>payza.jpg">
				</div>
            <?php } ?>   
            <?php if($enable_coinpayments == 1){
            	 if($p>0){D('<hr>');}
			$p++; ?>
				<div class="payment-option">
					<input type="radio" name="method" id="coinpayments" class="radio-custom">
					<label for="coinpayments" class="radio-custom-label"></label>
					<img src="<?php D(theme_url().IMAGE)?>coinpayments.png">
				</div>
            <?php } ?>   
            <?php if($enable_dusupay == 1){ 
             if($p>0){D('<hr>');}
			$p++;?>
				<div class="payment-option">
					<input type="radio" name="method" id="mobile-money" class="radio-custom">
					<label for="mobile-money" class="radio-custom-label"></label>
					<img src="<?php D(theme_url().IMAGE)?>mobile-money.png">
				</div>
            <?php } ?>     

            
            </div>

		</div>

		<div class="modal-footer ">

            <button class="btn btn-secondary" data-dismiss="modal"> Close </button>
		<?php 
        $paymentMethod=array(
        'current_balance'=>$current_balance,
        'sub_total'=>$featured_fee,
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
        'payfor'=>'featured',
        'ids'=>$proposal_details->proposal_id,
        );
 		$templateLayout=array('view'=>'payment/paymentMethod','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$paymentMethod);
          
         // include("checkoutPayMethods.php"); ?> 


        </div>

	</div>

</div>


</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#featured-listing-modal").modal("show");

});

</script>