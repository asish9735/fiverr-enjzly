<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="send-offer-modal" class="modal fade">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <div class="modal-content mycustom-modal">
			<div class="modal-header">
				<button type="button" class="btn btn-dark pull-left" data-dismiss="modal"><?php D(__('global_Close',"Close"));?></button>
		        <h4 class="modal-title"><?php D(__('modal_send_offer_heading',"Select A Proposal/Service To Offer"));?></h4>
				<button class="btn btn-site pull-right" id="submit-proposal" data-toggle="modal" data-dismiss="modal" data-target="#submit-proposal-details" title="Choose an offer before clicking continue"><?php D(__('modal_send_offer_Continue',"Continue"));?></button>
			</div>
			<div class="modal-body p-0">
				<div class="request-summary">
                     <img src="<?php D(getMemberLogo($request_details->seller_id)); ?>" width="50" height="50" class="rounded-circle">
				<div id="request-description">
					<h6 class="mb-1"> <?php D($request_details->request_title); ?> </h6>
					<p><?php D($request_details->request_description); ?></p>
				</div>
				</div>
				<ul class="request-proposals-list">
                    <?php
                    if($my_proposal){
						foreach($my_proposal as $k=>$proposal){
							?>
                            <li>
                            <div class="custom-control custom-radio">
                              <input type="radio" name="proposal_id" id="radio-<?php D($proposal->proposal_id); ?>" class="custom-control-input" value="<?php D($proposal->proposal_id); ?>" required>
                              <label class="custom-control-label" for="radio-<?php D($proposal->proposal_id); ?>"><img src="<?php D(URL_USERUPLOAD.'proposal-files/'); ?><?php D($proposal->proposal_image); ?>" alt="" class="mr-2" width="48" height="32"> <?php D($proposal->proposal_title); ?></label>
                            </div>	
                            </li>					 						
							
							<?php
						}
					}else{
						?>
                        <li>
                        <?php
						D(__('modal_send_offer_no_match',"You have no proposal in this category"));
						?>
                        </li>
                        <?php
					}
                    ?>                  
				</ul>
			</div>
		</div>
	</div>
</div>
<script>

$(document).ready(function(){
	$("#send-offer-modal").modal("show");
	$("#submit-proposal").attr("disabled", "disabled");
	$("input[type=radio][name='proposal_id']").change(function(){
		$("#submit-proposal").removeAttr("disabled");
	});
   $("#submit-proposal").click(function(){ 
	   proposal_id = document.querySelector('input[name="proposal_id"]:checked').value;	   
	   request_id = "<?php D($request_details->request_id); ?>";
	   $.ajax({
		method: "POST",   
		url: "<?php D(get_link('sendOfferRequestDerails'))?>",
		data: { proposal_id: proposal_id, request_id: request_id }
	   })
	   .done(function(data){
		   $("#submit-proposal-details .modal-dialog").html(data);
	   });
   });
});

</script>