<div class="breadcrumbs">
  <div class="container-fluid">
    <h1>
      <?php D(__('buyer_request_page_heading','Recent Buyer Requests'))?>
    </h1>
    <h5 class="headline-text-right"> <i class="fa fa-list-alt"></i>
      <?php D(10-$login_seller_offers); ?>
      <?php D(__('buyer_request_page_Offers_Left_Today','Offers Left Today'))?>
    </h5>
  </div>
</div>
<section class="section gray">
  <div class="container-fluid">
  <div class="row">
  <div class="col-xl-3 col-lg-auto col-12">
    <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
  </div>
  <div class="col-xl-9 col-lg col-12">
    <div class="row buyer-requests mb-3">
      <div class="col-lg-4 col-md-6">
        <form action="" method="get" id="name_search">
          <div class="input-group">
            <input type="text" id="search-input" name="title"  placeholder="<?php D(__('buyer_request_page_search_Buyer_Requests_input','Search Buyer Requests'))?>" class="form-control" value="<?php if($this->input->get('title')){D($this->input->get('title'));}?>" >
            <span class="input-group-append">
            <button class="btn btn-site" type="submit"> <i class="fa fa-search"></i> </button>
            </span> </div>
        </form>
      </div>
    </div>
    <div class="mt-4">
      <ul class="nav nav-pills mt-3">
        <!-- nav nav-tabs Starts -->
        <li class="nav-item"> <a href="#active-requests" data-toggle="tab" class="nav-link active make-black">
          <?php D(__('buyer_request_page_Active','Active'))?>
          <span class="badge badge-site ml-1">
          <?php D(count($buyer_request)); ?>
          </span> </a> </li>
        <li class="nav-item"> <a href="#sent-offers" data-toggle="tab" class="nav-link make-black">
          <?php D(__('buyer_request_page_Offers_Sent','Offers Sent'))?>
          <span class="badge badge-site ml-1">
          <?php D(count($offer_sent)); ?>
          </span> </a> </li>
      </ul>
      <div class="tab-content">
        <div id="active-requests" class="tab-pane fade show active">
          <div class="custom-headline">
            <div class="row align-items-center">
              <div class="col-lg-9">
                <h4 class="mb-0">
                  <?php D(__('buyer_request_page_Buyer_Requests','Buyer Requests'))?>
                </h4>
              </div>
              <div class="col-lg-3">
                <form action="" method="get" id="category_search">
                  <select id="sub-category" name="scat" class="form-control sort-by">
                    <option value="">
                    <?php D(__('buyer_request_page_All_Subcategories','All Subcategories'))?>
                    </option>
                    <?php

                if($seller_category){

                    foreach($seller_category as $category){

                    ?>
                    <option value="<?php D($category->category_subchild_id);?>" <?php if($this->input->get('scat') && $this->input->get('scat')==$category->category_subchild_id){D('selected');}?>>
                    <?php D($category->name);?>
                    </option>
                    <?php	

                    }

                }

                ?>
                  </select>
                </form>
              </div>
            </div>
          </div>
          <div class="listings-container compact-list-layout" id="load-data">
            <?php /*?><thead>



        <tr>



        <th><?php D(__('buyer_request_page_Request','Request'));?></th>



        <th><?php D(__('buyer_request_page_Offers','Offers'));?></th>



        <th><?php D(__('buyer_request_page_Date','Date'));?></th>



        <th><?php D(__('buyer_request_page_Duration','Duration'));?></th>



        <th><?php D(__('buyer_request_page_Budget','Budget'));?></th>



        </tr>



        </thead><?php */?>
            <?php

if($buyer_request){

	foreach($buyer_request as $k=>$request){

		$seller_user_name=getUserName($request->seller_id);

		$requestdtl=getRequestDetails($request->request_id,array('request_category','request_files'));

		$count_send_offers=$this->db->where('request_id',$request->request_id)->from('send_offers')->count_all_results();

		?>
            
            <!-- Job Listing -->
            
            <div class="job-listing pr-3" id="request_tr_<?php D($request->request_id); ?>"> 
              
              <!-- Job Listing Details -->
              
              <div class="job-listing-details"> 
                
                <!-- Logo -->
                
                <div class="job-listing-user-logo"> <img src="<?php D(getMemberLogo($request->seller_id)); ?>" alt="" class="rounded-circle"> </div>
                
                <!-- Details -->
                
                <div class="job-listing-description"> 
                  
                  <!--<h4 class="job-listing-company">Hexagon <span class="verified-badge" data-tippy-placement="top" data-tippy="" data-original-title="Verified Employer"></span></h4>-->
                  
                  <h3 class="job-listing-title">
                    <?php D($seller_user_name); ?>
                  </h3>
                  <p class="job-listing-text">
                    <?php D($request->request_description); ?>
                  </p>
                  <!-- Job Listing Footer -->              
                  <div class="job-listing-footer mb-2">
                    <ul>
                      <li><i class="icon-material-outline-location-on"></i>
                        <?php D($request->request_title); ?>
                      </li>
                      <li><i class="icon-material-outline-business-center"></i>
                        <?php if($requestdtl && $requestdtl['request_files']){ ?>
                        <a href="<?php D(URL_USERUPLOAD.'request-files')?>/<?php D($requestdtl['request_files'][0]->server_name); ?>" download> <i class="fa fa-arrow-circle-down"> </i>
                        <?php D($requestdtl['request_files'][0]->server_name); ?>
                        </a>
                        <?php } ?>
                      </li>
                      <li><i class="icon-material-outline-account-balance-wallet"></i>
                        <?php D($requestdtl['request_category']->category_name); ?>
                      </li>
                      <li>
                        <i class="icon-material-outline-local-offer"></i> <?php D($count_send_offers); ?>
                      </li>
                      <li><i class="icon-feather-calendar"></i>
                        <?php D(dateFormat($request->request_date,'F d, Y')); ?>
                      </li>
                      <li>
                        <i class="icon-material-outline-brush"></i> <?php D($requestdtl['request_category']->category_subchild_name); ?>
                      </li>
                      <li><i class="icon-material-outline-access-time"></i>
                        <?php D($request->delivery_time); ?>
                        <?php D(__('buyer_request_page_days','days'));?>
                      </li>
                      <li><a href="#" class="remove-link text-danger" onclick="$('#request_tr_<?php D($request->request_id); ?>').fadeOut().remove()">
                        <?php D(__('buyer_request_page_Remove_Request','Remove Request'));?>
                        </a></li>
                      <li>
                        <?php if($request->request_budget && $request->request_budget>0){ ?>
                        <?php D(CURRENCY); ?>
                        <?php D($request->request_budget); ?>
                        <?php }else{ ?>
                        -----
                        <?php } ?>
                      </li>                      
                    </ul>
                  </div>
                  
                        <?php if($login_seller_offers >= 10){ ?>
                        <button class="btn btn-site btn-sm" data-id="<?php D($request->request_id); ?>" data-toggle="modal" data-target="#quota-finish">
                        <?php D(__('buyer_request_page_Send_An_Offer','Send An Offer'));?>
                        </button>
                        <?php }else{ ?>
                        <button class="btn btn-site btn-sm send_button_offer" data-id="<?php D($request->request_id); ?>">
                        <?php D(__('buyer_request_page_Send_Offer','Send Offer'));?>
                        </button>
                        <?php } ?>
                      
                </div>
              </div>
              
              
            </div>
            
            <?php

			}
		
		}else{
		
			?>
            <div class='job-listing p-3'>
              <div class="text-center">
              	<h2 class="icon-line-awesome-info-circle text-danger"></h2>
                <h5><?php D(__('buyer_request_page_no_match_request','No requests that matches any of your proposals/services yet!'));?></h5>
              </div>
            </div>
            <?php

}

?>
          </div>
        </div>
        <div id="sent-offers" class="tab-pane fade">
          <div class="custom-headline">
            <h4 class="mb-0">
              <?php D(__('buyer_request_page_OFFERS_SUBMITTED','OFFERS SUBMITTED'));?>
            </h4>
          </div>
          <div class="listings-container compact-list-layout">
            <?php /*?><thead>

<tr>

<th><?php D(__('buyer_request_page_Request','Request'));?></th>

<th><?php D(__('buyer_request_page_Duration','Duration'));?></th>

<th><?php D(__('buyer_request_page_Price','Price'));?></th>

<th><?php D(__('buyer_request_page_Your_Pitch','Your Pitch'));?></th>

</tr>

</thead><?php */?>
            <?php

if($offer_sent){

	foreach($offer_sent as $k=>$request){

		$requestdtl=getRequestDetails($request->request_id,array('request_category','request_files'));

		$seller_user_name=getUserName($request->seller_id);

		//dd($requestdtl);

		?>
            <div class="job-listing pr-3"> 
              
              <!-- Job Listing Details -->
              
              <div class="job-listing-details"> 
                
                <!-- Logo -->
                
                <div class="job-listing-user-logo"> <img src="<?php D( getMemberLogo($request->request_id))?>" alt="" class="rounded-circle"> </div>
                
                <!-- Details -->
                
                <div class="job-listing-description">
                  <h3 class="job-listing-title">
                    <?php D($seller_user_name); ?>
                  </h3>
                  <p class="job-listing-text">
                    <?php D($request->request_description); ?>
                  </p>
                  <!-- Job Listing Footer -->              
              <div class="job-listing-footer">
                <ul>
                  <li><i class="icon-material-outline-location-on"></i>
                    <?php D($request->request_title); ?>
                  </li>
                  
                    <?php if($requestdtl && $requestdtl['request_files']){ ?>
                    <li><a href="<?php D(URL_USERUPLOAD.'request-files') ?>/<?php D($requestdtl['request_files'][0]->server_name); ?>" class="text-dark" download> <i class="icon-feather-download"></i>
                    <?php D($requestdtl['request_files'][0]->server_name); ?>
                    </a></li>
                    <?php } ?>
                  
                  <li><i class="icon-material-outline-account-balance-wallet"></i>
                    <?php D($requestdtl['request_category']->category_name); ?>
                  </li>
                  <li><i class="icon-material-outline-brush"></i>
                    <?php D($requestdtl['request_category']->category_subchild_name); ?>
                  </li>
                  <li><i class="icon-material-outline-access-time"></i>
                    <?php D($request->delivery_time_offer); ?>
                    <?php D(__('buyer_request_page_days','days'));?>
                  </li>
                  <li><i class="icon-feather-tag"></i>
                    <?php D(CURRENCY); ?>
                    <?php D($request->amount_offer); ?>
                  </li>
                  <li>
                    <i class="icon-material-outline-local-offer"></i> <?php D($request->proposal_title); ?>
                  </li>
                  <li>
                    <i class="icon-material-outline-local-offer"></i> <?php D($request->description_offer); ?>
                  </li>
                </ul>
              </div>
                </div>
              </div>
              
              
            </div>
            <?php

	}

}else{

	?>
            <div class='job-listing p-3'>
              <div class="text-center">
              	<h2 class="icon-line-awesome-info-circle text-danger"></h2>
                <h5><?php D(__('buyer_request_page_no_offer_sent',"You\'ve sent no offers yet!"));?></h5>
              </div>
            </div>
            <?php

}

?>
          </div>
        </div>
      </div>
    </div>
    <div class="append-modal"></div>
    <div id="quota-finish" class="modal fade">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content mycustom-modal">
          <div class="modal-header">
            <button type="button" class="btn btn-dark pull-left" data-dismiss="modal">
            <?php D(__('global_Close',"Close"));?>
            </button>
            <h4 class="modal-title"><i class="fa fa-frown-o fa-move-up"></i>
              <?php D(__('modal_quota_finish_heading',"Request Quota Reached"));?>
            </h4>
            <button type="button" class="btn btn-site pull-right" data-dismiss="modal">
            <?php D(__('global_ok',"OK"));?>
            </button>
          </div>
          <div class="modal-body">
            <center>
              <h5>
                <?php D(__('modal_quota_finish_text',"You can only send a max of 10 offers per day. Today you've maxed out. Try again tomorrow."));?>
              </h5>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div id="submit-proposal-details" class="modal fade"> <!-- Continue's Code -->
  
  <div class="modal-dialog"> </div>
</div>
<script>



$(document).ready(function(){

$('#sub-category').change(function(){

$(this).closest('form').submit();

});



});





var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

	$(".send_button_offer").click(function(){

         request_id = $(this).data('id');

          $.ajax({

           	method: "POST",

            url: "<?php D(get_link('sendOfferRequest'))?>",

            data: {request_id: request_id }

        })

    	.done(function(data){

            $(".append-modal").html(data);

        });

    });

function saveOffer(ev){

	var formID="proposal-details-form";

	var buttonsection=$('#'+formID).find('.saveBTN');

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

		event.preventDefault();	

		$.ajax({

			method: "POST",

			dataType: 'json',

			url: "<?php D(get_link('saveOfferRequest'))?>",

			data: $('#'+formID).serialize(),

			success: function(msg) {

				buttonsection.html(buttonval).removeAttr('disabled');

				clearErrors();

				if (msg['status'] == 'OK') {

					 swal({

	                  type: 'success',

	                  text: "<?php D(__('popup_saveoffer_success_message',"Your offer has been submitted successfully."));?>",

	                  timer: 2000,

	                  onOpen: function(){

	                    swal.showLoading()

	                  }

	                  }).then(function(){

	                  	window.location.href=msg['redirect'];

	                })	

				} else if (msg['status'] == 'FAIL') {

					registerFormPostResponse(formID,msg['errors']);

				}

			}

		})

	//});

return false;

}

</script>