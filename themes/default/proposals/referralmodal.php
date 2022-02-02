
<div id="referral-modal" class="modal">

  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
 
    <div class="modal-content mycustom-modal">
	
<div class="modal-header">
<button type="button" class="btn btn-dark pull-left" data-dismiss="modal"><?php D(__('global_Close',"Close"));?></button>
		 <h4 class="modal-title">Referral Link</h4>
 <button type="button" class="btn btn-site pull-right " data-dismiss="modal"><?php D(__('global_ok',"OK"));?></button>
</div>

<div class="modal-body">

<h6>

If anyone buys this proposal with your unique referral link, you will get <?php D($proposal_referral_money); ?>% from every purchase.

</h6>

<input class="form-control mb-1" disabled value="<?php D($link);?>">

</div>

</div>

</div>

</div>

<script>

$(document).ready(function(){
	
	$("#referral-modal").modal("show");
	
	 $(".close").click(function(){
	
	$("#referral-modal").hide();
	
	$(".modal-backdrop").hide();

	 });

	$(".modal-backdrop").click(function(){
	
	$("#referral-modal").hide();
	
	$(".modal-backdrop").remove();

	 });


	});
	
</script>	
	