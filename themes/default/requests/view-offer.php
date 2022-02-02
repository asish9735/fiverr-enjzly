<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="breadcrumbs">
  <div class="container">
    <h1>
      <?php if($is_login){?>
      <?php D(__('view_offer_page_heading',"View Offers"));?>
      (
      <?php D(count($request_offer)); ?>
      )
      <?php }else{?>
      <?php D(ucfirst($request_details['request']->request_title)); ?>
      <?php }?>
    </h1>
  </div>
</div>
<section class="section">
  <div class="container">
    <div class="view-offers">
      <div class="listings-container mb-4">
        <div class="headline">
          <h4>
            <?php D(__('view_offer_page_Request_Description',"Request Description:"));?>
          </h4>
        </div>
          
            <!-- Job Listing -->
            <div class="job-listing"> 
              <!-- Job Listing Details -->
              <div class="job-listing-details"> 
                <!-- Logo --> 
                <!--<a href="#" class="job-listing-company-logo">
                                <img src="images/company-logo-05.png" alt="">
                            </a> --> 
                <!-- Details -->
                <div class="job-listing-description">
                  <p class="offer-p">
                    <?php D($request_details['request']->request_description); ?>
                  </p>
                  <!-- Job Listing Footer -->
                  <div class="job-listing-footer">
                    <ul>
                      <li><i class="icon-material-outline-money"></i> <b>
                        <?php D(__('view_offer_page_Request_Budget',"Request Budget:"));?>
                        </b> <span class="text-muted">
                        <?php D(CURRENCY); ?>
                        <?php D($request_details['request']->request_budget); ?>
                        </span></li>
                      <li><i class="icon-feather-calendar"></i> <b>
                        <?php D(__('view_offer_page_Request_Date',"Request Date:"));?>
                        </b> <span class="text-muted">
                        <?php D(dateFormat($request_details['request']->request_date,'F d, Y')); ?>
                        </span></li>
                      <li><i class="icon-feather-clock"></i> <b>
                        <?php D(__('view_offer_page_Request_Duration',"Request Duration:"));?>
                        </b> <span class="text-muted">
                        <?php D($request_details['request']->delivery_time); ?>
                        <?php D(__('view_offer_page_days',"days"));?>
                        </span></li>
                      <li><i class="icon-feather-archive"></i> <b>
                        <?php D(__('view_offer_page_Request_Category',"Request Category:"));?>
                        </b> <span class="text-muted">
                        <?php D($request_details['request_category']->category_name); ?>
                        /
                        <?php D($request_details['request_category']->category_subchild_name); ?>
                        </span> </li>
                    </ul>
                  </div>
                  <?php 
					if($request_details['request_files']){
						foreach($request_details['request_files'] as $f=>$file){
							$cntmyCarousel++;
							?>
            <a download="" class="btn btn-outline-site mt-2" target="_blank" href="<?php D(URL_USERUPLOAD)?>request-files/<?php D($file->server_name); ?>"> <i class="icon-feather-download"></i>
              <?php D($file->original_name); ?>
              </a> 
            <?php }
                        }
              ?>
                </div>
              </div>
            </div>
           
          
      
      <?php 
            if($is_login){
            if($request_offer){
            	foreach($request_offer as $r=>$offer){
            		$seller_user_name=getUserName($offer->proposal_seller_id);
            		$seller_dtl=getMemberDetails($offer->proposal_seller_id,array('main'=>1));
					$url=get_link('ProposalDetailsURL').'/'.$seller_user_name.'/'.$offer->proposal_url;
				?>
               
                    
				<!-- Job Listing -->
                <div class="job-listing">

                    <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <a href="<?php D($url);?>" class="job-listing-company-logo">
                            <img src="<?php D(URL_USERUPLOAD.'proposal-files') ?>/<?php D($offer->proposal_image); ?>" class="img-fluid" alt="" />
                        </a>

                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title"><a href="<?php D($url);?>"><?php D($offer->proposal_title); ?></a></h3>
                            <p><?php D(dateFormat($offer->reg_date,'F d, Y').' '.date('H:i',strtotime($offer->reg_date)))?></p>
                            <p class="text-secondary mb-1"><?php D($offer->description_offer); ?></p>
                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="icon-material-outline-money"></i>
                                    <?php D(__('view_offer_page_Offer_Budget',"Offer Budget:"));?>
                                    <span class="text-muted"><?php D(CURRENCY); ?> <?php D($offer->amount_offer); ?></span>
                                    </li>
                                    <li><i class="icon-feather-calendar"></i>
									<?php D(__('view_offer_page_Offer_Duration',"Offer Duration:"));?>
                                    <span class="text-muted">
                                    <?php D($offer->delivery_time_offer); ?>
                                    <?php D(__('view_offer_page_days',"days"));?>
                                    </span></li>
                                    <li></li>
                                    <li></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-12">
              <div class="offer-seller-picture"> <img src="<?php D(getMemberLogo($offer->proposal_seller_id))?>" class="rounded-circle" >
                <?php if($offer->seller_level == 2){ ?>
                <img src="<?php D(theme_url().IMAGE)?>level_badge_1.png" class="level-badge" >
                <?php }elseif($offer->seller_level == 3){ ?>
                <img src="<?php D(theme_url().IMAGE)?>level_badge_2.png" class="level-badge" >
                <?php }elseif($offer->seller_level == 4){ ?>
                <img src="<?php D(theme_url().IMAGE)?>level_badge_3.png" class="level-badge" >
                <?php } ?>
              </div>
              <div class="offer-seller mb-1">
                <p class="font-weight-bold mb-1">
                  <?php /*D($offer->member_name);*/ D($seller_user_name); ?>
                  <?php if(is_online($offer->proposal_seller_id)){?>
                  <small class="text-success">
                  <?php D(__('view_offer_page_Online',"Online"));?>
                  </small>
                  <?php }else{?>
                  <small class="text-secondary">
                  <?php D(__('view_offer_page_Offline',"Offline"));?>
                  </small>
                  <?php }?>
                </p>
                <a href="<?php D(get_link('viewprofileURL'))?><?php D($seller_user_name); ?>" class="btn btn-sm btn-outline-site" target="blank">
                <?php D(__('view_offer_page_User_Profile',"User Profile"));?>
                </a> </div>
              <?php if($seller_dtl['member']->is_vacation){?>
              <small style="line-height: 25px;">
              <?php D(__('view_offer_page_Freelancer_vacation',"Freelancer vacation mode has been switched to"));?>
              <span class="badge badge-success">
              <?php D(__('view_offer_page_Freelancer_vacation_ON',"ON"));?>
              </span>
              <?php D(__('view_offer_page_Freelancer_vacation_ON_text',"At this momment, you are unable to purchase this proposal/service until the freelancer swiches vacation mode back to"));?>
              <span class="badge badge-success">
              <?php D(__('view_offer_page_Freelancer_vacation_OFF',"OFF"));?>
              </span>. </small>
              <?php }else{?>
              <a href="<?php D(get_link('messageLink'))?>/<?php D($offer->proposal_seller_id); ?>" class="btn btn-sm btn-outline-site">
              <?php D(__('view_offer_page_Contact_Now',"Contact Now"));?>
              </a> 
              <!--<button id="conatct-button-<?php D($offer->proposal_seller_id); ?>" data-seller-id="<?php D($offer->proposal_seller_id); ?>" class="btn btn-sm btn-success rounded-0 ContactNow">
								Contact Now
							</button>-->
              <button id="order-button-<?php D($offer->offer_id); ?>" data-offer="<?php D($offer->offer_id); ?>" class="btn btn-sm btn-outline-secondary acceptOfferBTN">
              <?php D(__('view_offer_page_Order_Now',"Order Now"));?>
              </button>
              <?php }?>
            </div>
                    </div>
                    
                </div>
                        
				
	
      
      <?php	
			}
            }else{?>
      <div class="card rounded-0 mb-3">
        <div class="card-body">
          <h5 class="text-muted text-center">
            <?php D(__('view_offer_page_no_offer_yet',"Unfortunately, no offers yet. Please wait a little longer."));?>
          </h5>
        </div>
      </div>
      <?php }
            }
		?>
      </div>  
    </div>
  </div>
</section>
<div id="append-modal"></div>
<div id="contact-modal" class="modal fade"><!-- report-modal modal fade Starts -->
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <div class="modal-content mycustom-modal">
      <div class="modal-header p-2 pl-3 pr-3"><!-- modal-header Starts --> 
       <button type="button" class="btn btn-dark pull-left" data-dismiss="modal">Cancel</button>
        <h4 class="modal-title">Contact Now</h4>
        <button type="button" class="btn btn-site pull-right saveBTN" onclick="submitContact(this)">Send</button>
      </div>
      <!-- modal-header Ends -->
      
      <div class="modal-body"><!-- modal-body p-0 Starts -->
        
        <form action="" method="post" id="contactsubmitForm" name="contactsubmitForm">
          <input type="hidden" id="contact_id" name="contact_id" value="0"/>
          <div class="form-group mt-1 mb-3"><!--- form-group Starts --->
            
            <label> Message </label>
            <textarea name="message_content" id="message_content" rows="3" class="form-control" ></textarea>
          </div>
          <!--- form-group Ends --->
        </form>
      </div>
      <!-- modal-body p-0 Ends --> 
      
    </div>
    <!-- modal-content Ends --> 
    
  </div>
  <!-- modal-dialog Ends --> 
  
</div>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
	$('.acceptOfferBTN').click(function(){
		offer_id = $(this).data('offer');
		$.ajax({
		method: "POST",
		url: "<?php D(get_link('OfferAcceptRequest'));?>",
		data: {request_id: "<?php D($request_details['request']->request_id);?>", offer_id: offer_id}
		})
		.done(function(data){
			$("#append-modal").html(data);
		});
});
$('.ContactNow').click(function(){
	var seller_id = $(this).data('seller-id');
	$("#contact-modal #contact_id").val(seller_id);
	$("#contact-modal").modal('show');
		/*seller_id = $(this).data('seller-id');
		$.ajax({
		method: "POST",
		url: "<?php D(get_link('messageLinkAJAX'));?>",
		data: {seller_id: seller_id}
		})
		.done(function(data){
			$("#append-modal").html(data);
		});*/
});
function submitContact(ev){
	var formID='contactsubmitForm';
	var modal=$(ev).closest('.modal');
	var buttonsection=$(ev);
	var forminput=$('#'+formID).serialize();
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('messageLinkAJAX'))?>",
        data:forminput,
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				$(modal).modal('hide');
					window.location.href=msg['redirect'];	
			} else if (msg['status'] == 'FAIL') {
				registerFormPostResponse(formID,msg['errors']);
			}
		}
	})
	return false;
}
</script>