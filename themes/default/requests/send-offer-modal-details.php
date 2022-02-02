<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-content mycustom-modal">
<form id="proposal-details-form" name="proposal-details-form" method="post" onsubmit="return saveOffer(this);return false;">
<div class="modal-header"><!--- modal-header Starts --->
<button type="button" class="btn btn-dark pull-left" data-dismiss="modal" data-toggle="modal" data-target="#send-offer-modal">
<?php D(__('modal_submit_proposal_details_Back',"Back"));?>
</button>
<h4 class="modal-title"> <?php D(__('modal_submit_proposal_details_heading',"Specify Your Proposal Details"));?> </h4>
<button type="submit" class="btn btn-site pull-right saveBTN">

<?php D(__('modal_submit_proposal_details_Submit_Offer',"Submit Offer"));?>

</button>
</div><!--- modal-header Ends --->

<div class="modal-body p-0"><!--- modal-body p-0 Starts --->

<div class="request-summary"><!--- request-summary Starts --->

<img src="<?php D(getMemberLogo($request_details->seller_id)); ?>" width="50" height="50" class="rounded-circle">

<div id="request-description"><!--- request-description Starts --->

<h6 class="mb-1"> <?php D($request_details->request_title); ?> </h6>

<p> <?php D($request_details->request_description); ?> </p>

</div><!--- request-description Ends --->

</div><!--- request-summary Ends --->

<!--- proposal-details-form Starts --->

<div class="selected-proposal p-3"><!--- selected-proposal p-3 Starts --->

<h5> <?php D($my_proposal_details->proposal_title); ?> </h5>

<hr>

<input type="hidden" name="proposal_id" value="<?php D($my_proposal_details->proposal_id); ?>">

<input type="hidden" name="request_id" value="<?php D($request_details->request_id); ?>">

<div class="form-group"><!--- form-group Starts --->

<label class="font-weight-bold"> <?php D(__('modal_submit_proposal_details_Description',"Description :"));?>  </label>

<textarea name="description" id="description" class="form-control"></textarea>
<span id="descriptionError" class="rerror"></span>

</div><!--- form-group Ends --->

<hr>

<div class="form-group row"><!--- form-group Starts --->

<label class="font-weight-bold col-sm-4"> <?php D(__('modal_submit_proposal_details_Delivery_Time',"Delivery Time :"));?>  </label>
<div class="col-sm-8">
<select class="form-control float-right" name="delivery_time">

<?php 
if($all_delivery_time){
	foreach($all_delivery_time as $delivery_times){
		?>
		<option value="<?php D($delivery_times->delivery_id); ?>"><?php D($delivery_times->delivery_proposal_title); ?></option>
		<?php
	}
}
?>

</select>
<span id="delivery_timeError" class="rerror"></span>
</div>
</div><!--- form-group Ends --->

<hr>


<div class="form-group row"><!--- form-group Starts --->

<label class="font-weight-bold col-sm-4"> <?php D(__('modal_submit_proposal_details_Total_Offer_Amount',"Total Offer Amount :"));?>  </label>
<div class="col-sm-8">
<div class="input-group float-right">
<div class="input-group-prepend">
<span class="input-group-text"> <?php D(CURRENCY); ?> </span>
</div>
<input type="number" id="amount" name="amount" class="form-control" min="5" placeholder="<?php D(__('modal_submit_proposal_details_Minimum',"5 Minimum"));?>">
</div>
</div>

</div><!--- form-group Ends --->


</div><!--- selected-proposal p-3 Ends --->




</div><!--- modal-body p-0 Ends --->
</form><!--- proposal-details-form Ends --->
</div><!--- modal-content Ends --->

<div id="insert_offer"></div>


