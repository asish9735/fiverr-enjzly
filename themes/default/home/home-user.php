<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?>
<?php /*?>

<div id="demo3" class="carousel slide home-banner" data-ride="carousel">

<div class="overlay"></div>

    <ul class="carousel-indicators">

    <?php

    if($slider){

        foreach($slider as $k=>$sliderimage){

            ?>

     <li data-target="#demo3" data-slide-to="<?php echo $k; ?>" class="<?php if($k==0){D('active');}?>"></li>		

            <?php

        }

    }

    ?>

    </ul>

    <div class="carousel-inner">

    <?php

    if($slider){

        foreach($slider as $k=>$sliderimage){

            ?>

        <div class="carousel-item <?php if($k==0){D('active');}?>">

            <img src="<?php D(URL_USERUPLOAD.'slider-user/')?><?php D($sliderimage->slide_image);?>" alt="<?php D($sliderimage->name);?>" class="img-fluid">

            <div class="carousel-caption d-lg-block d-md-block d-none">

                <div class="row">

                <div class="offset-lg-2 col-lg-8 offset-md-1 col-md-10 col-12">

                <h1>Find The Perfect Freelance Services For Your Business<?php D($sliderimage->name);?></h1>

                <p><?php D($sliderimage->description);?></p>

                </div>

                </div>

            </div>

      </div>

    <?php

        }

    }

    ?>

    </div>

</div>

<?php */?>

<div class="container-fluid mt-3"> <!-- Container starts -->
  
  <div class="row">
    <div class="col-xl-3 col-md-4 col-12">
      <div class="profile-x mb-3">
      <div class="avatar-wrapper mb-2">
      	<img src="<?php D(getMemberLogo($profile_data['member']->member_id));?>" class="rounded-circle" alt="User" height="64" width="64"></div>
        <div class="profile-x-body"> 
        	<p class="mb-1">Welcome</p>
          <h4>
            <?php D(__('user_home_page_Hi','Hi'));?>
            ,
            <?php if($profile_data && $profile_data['member']){D(ucwords(strtolower($profile_data['member']->member_name)));} ?>
          </h4>
        </div>
          <p>
            <?php D(__('user_home_page_get_offer_from_freelancer','Get offers from our freelancers for your project.'));?>
          </p>
          <button onclick="location.href='<?php D(get_link('postrequestURL'))?>'" class="btn btn-site">
          <?php D(__('user_home_page_post_a_request','Post A Request'));?>
          </button>
        
      </div>
      <div class=" rounded-0 carosel_sec">
        <h3 class="buy_head">
          <?php D(__('user_home_page_Buy_It_Again','Buy It Again'));?>
        </h3>
        <?php

				$proposaldata=array();

				if($recent_buy){

					?>
        <div id="demo" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#demo" data-slide-to="0" class="active"></li>
            <li data-target="#demo" data-slide-to="1"></li>
            <li data-target="#demo" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner " role="listbox">
            <?php foreach($recent_buy as $p=>$proposal){?>
            <div class="carousel-item <?php if($p==0){D('active');}?>">
              <?php 

						 	$proposaldata['proposal']=$proposal;

						 	$proposaldata['proposal']->hide_footer_action=1;

						 	$templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

							load_template($templateLayout,$proposaldata);

						 ?>
            </div>
            <?php }?>
          </div>
          
          <!-- Left and right controls           
          <a class="carousel-control-prev" href="#demo" data-slide="prev"> <i class="fa fa-angle-left"></i> </a> <a class="carousel-control-next" href="#demo" data-slide="next"> <i class="fa fa-angle-right"></i> </a>
          -->
        </div>
        
        <?php

				}else{

				?>
        <p class='text-muted'> <i class='fa fa-frown-o'></i>
          <?php D(__('user_home_page_Nothing_purchased','Nothing purchased yet.'));?>
        </p>
        <?php

				}

				?>
      </div>
      <div class="rounded-0 mb-3 carosel_sec mt-3">
        <h3 class="buy_head">
          <?php D(__('user_home_page_Recently_Viewed','Recently Viewed'));?>
        </h3>
        <?php

				$proposaldata=array();

				if($recent_proposals){

					?>
        <div id="demo2" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#demo2" data-slide-to="0" class="active"></li>
            <li data-target="#demo2" data-slide-to="1"></li>
            <li data-target="#demo2" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner" role="listbox">
            <?php foreach($recent_proposals as $p=>$proposal){?>
            <div class="carousel-item <?php if($p==0){D('active');}?>">
              <?php 

						 	$proposaldata['proposal']=$proposal;

						 	$proposaldata['proposal']->hide_footer_action=1;

						 	$templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

							load_template($templateLayout,$proposaldata);

						 ?>
            </div>
            <?php }?>
          </div>
          
          <!-- Left and right controls
          <a class="carousel-control-prev" href="#demo2" data-slide="prev"> <i class="fa fa-angle-left"></i> </a>
          <a class="carousel-control-next" href="#demo2" data-slide="next"> <i class="fa fa-angle-right"></i> </a>
          --> 
        </div>
        <?php

				}else{

				?>
        <p class='text-muted'> <i class='fa fa-frown-o'></i>
          <?php D(__('user_home_page_Nothing_Viewed','Nothing viewed yet.'));?>
        </p>
        <?php

				}

				?>
      </div>
      <div class="card rounded-0 sticky-start mb-3 card_user ">
        <div class="card-body"> <img src="<?php D(theme_url().IMAGE)?>start_selling.png" class="img-fluid center-block" alt="none">
          <h4>
            <?php D(__('user_home_page_Start_Freelancing','Start Freelancing'));?>
          </h4>
          <p>
            <?php D(__('user_home_page_Start_Freelancing_text','Sell your services to millions of people all over the world.'));?>
          </p>
          <button onclick="location.href='<?php D(get_link('startsellingURL'))?>'" class="btn btn-site">
          <?php D(__('user_home_page_GET STARTED','Get Started'));?>
          </button>
        </div>
      </div>
      <br>
      <?php //require_once("includes/user_home_sidebar.php"); ?>
    </div>
    <div class="col-xl-9 col-md-8 col-12">
      <div class="d-sm-flex align-items-center mb-3">
        <h3><?php D(__('user_home_page_Featured','Featured Proposals/Services'));?></h3>
        <button onclick="location.href='<?php D(get_link('FeaturedProposalsURL'))?>'" class="btn btn-outline-site ml-auto">
        <?php D(__('user_home_page_View_all','View all'));?>
        </button>
      </div>
      <div class="row">
        <?php
            	$proposaldata=array();

            	$loggedUser=$this->session->userdata('loggedUser');

            	if($featured_proposal){

            	foreach($featured_proposal as $p=>$proposal){

		    ?>
        <div class="col-lg-4 col-sm-6 col-12">
        <div id="" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner" role="listbox">
            <div class="carousel-item <?php if($p==0){D('active');}?>">
              <?php 

              $proposaldata['proposal']=$proposal;

              $templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

              load_template($templateLayout,$proposaldata);

              ?>
          </div>
          </div>
          <a class="carousel-control-prev" href="#demo2" data-slide="prev"> <i class="fa fa-angle-left"></i> </a>
          <a class="carousel-control-next" href="#demo2" data-slide="next"> <i class="fa fa-angle-right"></i> </a>
        </div>
        </div>
        <?php

					}	

				}else{

					?>
        <div class="col-12">
          <p class='text-muted text-center'><i class='fa fa-frown-o'></i>
            <?php D(__('user_home_page_no_featured','No featured proposals/services to display yet.'));?>
          </p>
        </div>
        <?php

				}

            	 ?>
      </div>
      <?php /*?>			

		 	<div class="row mb-3">

			  	<div class="col-md-12">

			  		<h2 class="float-left">Top Proposals/Services</h2>

			    	<button onclick="location.href='<?php D(get_link('TopProposalsURL'))?>'" class="float-right btn btn-site">View all</button>

			  	</div>

			</div>

			<div class="row">

            	<?php

            	$proposaldata=array();

            	$loggedUser=$this->session->userdata('loggedUser');

            	if($top_proposal){

            		foreach($top_proposal as $p=>$proposal){

				?>

				<div class="col-md-4 mb-3">

				 <?php 

				 	$proposaldata['proposal']=$proposal;

				 	$templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

					load_template($templateLayout,$proposaldata);

				 ?>

				</div>

				<?php

					}	

				}else{

					?>

				<div class="col-12"><p class='text-muted text-center'>

				<i class='fa fa-frown-o'></i> No top rated proposals/services to display yet. </p>	</div>

					<?php

				}

            	 ?>		      	

			</div>

			<?php */?>
      <br />
      <div class="d-sm-flex align-items-center mb-3">
        <h3>
          <?php D(__('user_home_page_Random','Random Proposals/Services'));?>
        </h3>
        <button onclick="location.href='<?php D(get_link('RandomProposalsURL'))?>'" class="btn btn-outline-site ml-auto">
        <?php D(__('user_home_page_View_all','View all'));?>
        </button>
      </div>
      <div class="row">
        <?php

            	$proposaldata=array();

            	$loggedUser=$this->session->userdata('loggedUser');

            	if($random_proposal){

            		foreach($random_proposal as $p=>$proposal){

				?>
        <div class="col-lg-4 col-sm-6 col-12">
          <?php 

				 	$proposaldata['proposal']=$proposal;

				 	//dd($proposaldata);

				 	$templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

					load_template($templateLayout,$proposaldata);

				 ?>
        </div>
        <?php

					}	

				}else{

					?>
        <div class="col-12">
          <p class='text-muted text-center'><i class='fa fa-frown-o'></i>
            <?php D(__('user_home_page_no_Random','No random proposals/services to display yet.'));?>
          </p>
        </div>
        <?php

				}

            	 ?>
      </div>
      <br>
      <?php if($seller_proposal){?>
      <div class="buyer-requests dashboard-box margin-top-0"> 
        
        <!-- Headline -->
        
        <div class="headline black d-flex">
          <h4>
            <?php D(__('user_home_page_Recent_requests','Recent requests'));?>
          </h4>
          <button type="button" onclick="location.href='<?php D(get_link('buyerRequests'))?>'" class="btn btn-outline-site ml-auto">
          <?php D(__('user_home_page_View_all','View all'));?>
          </button>
        </div>
        <ul class="dashboard-box-list with-button">
          <?php /*?>

					<thead>

                        <tr>

                            <th><?php D(__('user_home_page_Request_Message','Request Message'));?></th>

                            <th><?php D(__('user_home_page_Offers','Offers'));?></th>

                            <th><?php D(__('user_home_page_Duration','Duration'));?></th>

                            <th><?php D(__('user_home_page_Budget','Budget'));?></th>

                        </tr>

                    </thead><?php */?>
          <?php

                    if($recent_request){

                        foreach($recent_request as $k=>$request){

                            $seller_user_name=getUserName($request->seller_id);

                            $files=getRequestDetails($request->request_id,array('request_files'));

                            $count_send_offers=$this->db->where('request_id',$request->request_id)->from('send_offers')->count_all_results();

                            ?>
          <li id="request_tr_<?php D($request->request_id); ?>"> 
            
            <!-- Job Listing -->
            
            <div class="job-listing"> 
              
              <!-- Job Listing Details -->
              
              <div class="job-listing-details"> 
                
                <!-- Logo -->
                
                <div class="job-listing-company-logo"> <img src="<?php D(getMemberLogo($request->seller_id)); ?>" alt=""> </div>
                
                <!-- Details -->
                
                <div class="job-listing-description">
                  <h3 class="job-listing-title"><?php echo ucfirst($seller_user_name); ?></h3>
                  <p>
                    <?php D($request->request_title); ?>
                  </p>
                  <p>
                    <?php D($request->request_description); ?>
                  </p>
                  <?php if($files && $files['request_files']){ ?>
                  <a href="<?php D(URL_USERUPLOAD.'request-files')?>/<?php D($files['request_files'][0]->server_name); ?>" download> <i class="fa fa-arrow-circle-down"> </i>
                  <?php D($files['request_files'][0]->server_name); ?>
                  </a>
                  <?php } ?>
                  
                  <!-- Job Listing Footer -->
                  
                  <div class="job-listing-footer">
                    <ul>
                      <li><i class="icon-material-outline-money"></i> <b>
                        <?php D(__('user_home_page_Offers','Offers'));?>
                        :</b> <?php echo $count_send_offers; ?></li>
                      <li><i class="icon-feather-clock"></i> <b>
                        <?php D(__('user_home_page_Duration','Duration'));?>
                        :</b>
                        <?php D($request->delivery_time); ?>
                        <?php D(__('user_home_page_days','days'));?>
                      </li>
                      <li><i class="icon-material-outline-money"></i> <b>
                        <?php D(__('user_home_page_Budget','Budget'));?>
                        :</b>
                        <?php if($request->request_budget && $request->request_budget>0){ ?>
                        <?php D(CURRENCY); ?>
                        <?php D($request->request_budget); ?>
                        <?php }else{ ?>
                        -----
                        <?php } ?>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Buttons -->
            
            <div class="buttons-to-right">
              <?php if($login_seller_offers >= 10){ ?>
              <button class="btn btn-site" data-id="<?php D($request->request_id); ?>" data-toggle="modal" data-target="#quota-finish">
              <?php D(__('user_home_page_Send_An_Offer','Send An Offer'));?>
              </button>
              <?php }else{ ?>
              <button class="btn btn-site send_button_offer" data-id="<?php D($request->request_id); ?>">
              <?php D(__('user_home_page_Send_Offer','Send Offer'));?>
              </button>
              <?php } ?>
            </div>
          </li>
          <?php

                        }

                        

                    }else{

                        ?>
          <li style="padding:15px 20px;">
            <div class="text-center" style="flex:1;">
              <h2 class="icon-line-awesome-info-circle text-danger"></h2>
              <h5><?php D(__('user_home_page_no_request','No requests that matches any of your proposals/services yet!'));?></h5>
            </div>
          </li>
          <?php

                    }

                    ?>
        </ul>
      </div>
      <?php }?>
    </div>
  </div>
</div>
<!-- Container ends --> 


<div class="append-modal"></div>
<div id="quota-finish" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title h5"><i class="fa fa-frown-o fa-move-up"></i>
          <?php D(__('modal_home_page_quote_heading',"Request Quota Reached"));?>
        </h5>
        <button class="close" data-dismiss="modal"> &times; </button>
      </div>
      <div class="modal-body">
        <center>
          <h5>
            <?php D(__('modal_home_page_quote_description',"You can only send a max of 10 offers per day. Today you've maxed out. Try again tomorrow. "));?>
          </h5>
        </center>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">
        <?php D(__('popup_global_Close',"Close"));?>
        </button>
      </div>
    </div>
  </div>
</div>
<div id="submit-proposal-details" class="modal fade"> <!-- Continue's Code -->
  
  <div class="modal-dialog"> </div>
</div>
<!-- Continue end --> 

<script type="text/javascript">

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

	                  text: "<?php D(__('popup_home_page_send_offer_success_message',"Your offer has been submitted successfully."));?>",

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