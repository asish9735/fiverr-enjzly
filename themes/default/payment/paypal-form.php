<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!--<script>
swal({
  type: 'pending',
  text: '<?php D(__('popup_paypal_processing_payment',"Processing Payment"));?>',
  timer: 3000,
  onOpen: function(){
	swal.showLoading();
	$('#pay').click();
  }
  }).then(function(){	      		      	
})
</script>-->
<section class="pgLoad" style="background-color: #fff;">
  <form action="<?php D($formdata['url']);?>" method="post" style="display: none">
    <input name="amount" type="hidden" value="<?php D($formdata['amount_converted']);?>">
    <input name="currency_code" type="hidden" value="<?php D($formdata['currency_code']);?>">
    <input name="shipping" type="hidden" value="0.00">
    <input name="tax" type="hidden" value="0.00">
    <input name="return" type="hidden" value="<?php D($formdata['return_url']);?>">
    <input name="cancel_return" type="hidden" value="<?php D(get_link('homeURL'))?>">
    <input name="notify_url" type="hidden" value="<?php D($formdata['notify_url']);?>">
    <input name="cmd" type="hidden" value="_xclick">
    <input name="business" type="hidden" value="<?php D(get_option_value('paypal_email'))?>">
    <input name="item_name" type="hidden" value="Purchase from - <?php D(get_option_value('website_name'))?>">
    <input name="no_note" type="hidden" value="1">
    <input type="hidden" name="no_shipping" value="1">
    <input name="lc" type="hidden" value="EN">
    <input name="bn" type="hidden" value="PP-BuyNowBF">
    <input name="custom" type="hidden" value="<?php D($formdata['custom']);?>">
    <input type="submit" name="pay" value="Submit" id="pay">
  </form>
</section>

<script>
$(document).ready(function() {
	$('body').addClass('loading');
	setTimeout(function(){
	//$('body').removeClass('loading').addClass('loaded');	
	$('#pay').click();},3000)	
});
</script>

<style>
.loading {
	background: #fff url('<?php D(theme_url().IMAGE)?>/loader.gif') no-repeat center center;
	background-attachment:fixed;
}
.pgLoad {
    opacity: 0;
}
.loaded .pgLoad {
	opacity: 1;
	-webkit-transition: opacity 5s ease-out;
	-moz-transition: opacity 5s ease-out;
	transition: opacity 5s ease-out;
}
@media (min-width: 768px) {
	.pgLoad{
		min-height: 450px
	}
}
@media (max-width: 767px) {
	.pgLoad{
		max-height: 250px
	}
}

</style>