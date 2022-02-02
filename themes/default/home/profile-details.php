<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//dd($member_details,TRUE);

$seller_cover_image=theme_url().IMAGE.'default/user-background.jpg';

if($member_details['member_logo'] && $member_details['member_logo']->banner && file_exists(ABS_USERUPLOAD_PATH.'member_banner/'.$member_details['member_logo']->banner)){

	$seller_cover_image=URL_USERUPLOAD.'member_banner/'.$member_details['member_logo']->banner;	

}

$seller_image=theme_url().IMAGE.'default/empty-image.png';

if($member_details['member_logo'] && $member_details['member_logo']->logo && file_exists(ABS_USERUPLOAD_PATH.'member_logo/'.$member_details['member_logo']->logo)){

	$seller_image=URL_USERUPLOAD.'member_logo/'.$member_details['member_logo']->logo;

}

?>

<div class="single-page-header freelancer-header" data-background-image="<?php D($seller_cover_image); ?>">
  <div class="container">
    <div class="single-page-header-inner">
      <div class="left-side">
        <div class="header-image freelancer-avatar"> <img src="<?php D($seller_image); ?>" alt="">
          <?php if($member_details['member']->seller_level == 2){ ?>
          <img src="<?php D(theme_url().IMAGE)?>level_badge_1.png" class="level_badge">
          <?php } ?>
          <?php if($member_details['member']->seller_level == 3){ ?>
          <img src="<?php D(theme_url().IMAGE)?>level_badge_2.png" class="level_badge">
          <?php } ?>
          <?php if($member_details['member']->seller_level == 4){ ?>
          <img src="<?php D(theme_url().IMAGE)?>level_badge_3.png" class="level_badge">
          <?php } ?>
        </div>
        <div class="header-details">
          <h3>
            <?php // D(__('profile_page_Hi',"Hi, I\'m"));?>
            <?php /*D(ucfirst($member_details['member']->member_name));*/ D($username);?>
            <?php if($is_editable){ ?>
            <a href="<?php D(get_link('settingsURL'))?>?tab=profile"><i class="icon-feather-edit"></i>
            <?php // D(__('profile_page_Edit',"Edit"));?>
            &nbsp;</a>
            <?php } ?>
            <span>
            <?php D(ucfirst($member_details['member_basic']->member_heading)); ?>
            </span> </h3>
          <?php if($proposal){

if($is_login){

    if(!$is_editable){ 

?>
          <a class="btn btn-site mt-3" href="<?php D(get_link('messageLink')); ?>/<?php D($member_details['member']->member_id);?>">
          <?php D(__('profile_page_Contact',"Contact"));?>
          <small>(
          <?php D($username); ?>
          )</small> </a>
          <?php } 

}else{ ?>
          <a class="btn btn-site mt-3" href="<?php D(get_link('loginURL')); ?>">
          <?php D(__('profile_page_Contact',"Contact"));?>
          <small>(
          <?php D($username); ?>
          )</small> </a>
          <?php }

} ?>
          <ul>
            <li>
              <div class="star-rating" data-rating="<?php printf("%.1f",$member_details['member']->avg_rating);?>" data-showcount="true" data-digit="(<?php D($member_details['member']->total_review); ?>)"> <span class="star-count"></span></div>
            </li>
            <li><img class="flag" src="<?php D(theme_url().IMAGE);?>flags/<?php D($member_details['member_address']->country_flag); ?>.svg" alt=""> <i class="icon-feather-map-pin"></i>
              <?php D($member_details['member_address']->member_country_name); ?>
            </li>
            
            <!--<li><div class="verified-badge-with-title">Verified</div></li>-->
            
            <li>
              <?php if(is_online($member_details['member']->member_id) == 1){ ?>
              <span class="user-is-online"> <span class="text-success h6"><i class="fa fa-circle"></i></span> <span>
              <?php D(__('profile_page_online',"Online"));?>
              </span> </span>
              <?php } ?>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<section class="section">
  <div class="container-fluid"> <!-- Container starts -->
    <div class="row">
      <div class="col-xl-3 col-lg-4 col-12">
        <div class="card mb-4">
          <div class="card-body">
            <ul>
              <li class="mb-2"> <i class="icon-feather-user"></i> <strong>
                <?php D(__('profile_page_Member_Since',"Member Since:"));?>
                </strong>
                <?php D(dateFormat($member_details['member']->member_register_date,'M d, Y')); ?>
              </li>
              <?php if($member_details['member']->recent_delivery_date){ ?>
              <li> <i class="icon-feather-shopping-cart"></i> <strong>
                <?php D(__('profile_page_Recent_Delivery',"Recent Delivery:"));?>
                </strong>
                <?php D(dateFormat($member_details['member']->recent_delivery_date,'M d, Y')); ?>
              </li>
              <?php } ?>
              <?php if($member_details['member']->seller_level >0){ ?>
              <li> <i class="fa fa-bars"></i> <strong>
                <?php D(__('profile_page_Freelancer_Level',"Freelancer Level:"));?>
                </strong>
                <?php D(getLevelName($member_details['member']->seller_level)); ?>
              </li>
              <?php } ?>
            </ul>
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-header d-flex">
            <h4>
              <?php D(__('profile_page_Languages',"Languages"));?>
            </h4>
            <?php if($is_editable){ ?>
            <button data-toggle="collapse" data-target="#language" class="btn btn-site btn-circle ml-auto" title="<?php D(__('profile_page_Add_New',"Add New"));?>"> <i class="icon-feather-plus"></i> </button>
            <?php } ?>
          </div>
          <div class="card-body">
            <div id="language" class="collapse form-style mb-2">
              <form action="" method="post" accept-charset="utf-8" id="languageform"  role="form" name="languageform" onsubmit="saveAccount(this);return false;">
                <input type="hidden" name="section" value="language"/>
                <div class="form-group"><!-- form-group Starts -->
                  
                  <select class="form-control" name="language_id" id="language_id">
                    <option class="hidden" value="">
                    <?php D(__('profile_page_Select_Language',"Select Language"));?>
                    </option>
                    <?php 

                                if($all_languages){

                                    foreach($all_languages as $language){	

                                ?>
                    <option value="<?php D($language->language_id); ?>">
                    <?php D($language->language_title); ?>
                    </option>
                    <?php }

                                } ?>
                  </select>
                </div>
                <!-- form-group Ends -->
                
                <div class="form-group"><!-- form-group Starts -->
                  
                  <select class="form-control" name="language_level" id="language_level">
                    <option class="hidden" value="">
                    <?php D(__('profile_page_Select_Level',"Select Level"));?>
                    </option>
                    <?php

                                if($languages_level){

                                    foreach($languages_level as $k=>$level){

                                        ?>
                    <option value="<?php D($k); ?>">
                    <?php D($level['name']); ?>
                    </option>
                    <?php

                                    }

                                }

                                ?>
                  </select>
                </div>
                <!-- form-group Ends -->
                
                <div class="form-group">
                  <button type="button" data-toggle="collapse" data-target="#language" class="btn btn-secondary">
                  <?php D(__('profile_page_Cancel',"Cancel"));?>
                  </button>
                  <button type="submit" name="insert_language" class="btn btn-site saveBTN"> <i class="icon-feather-plus"></i>
                  <?php D(__('profile_page_Add',"Add"));?>
                  </button>
                </div>
              </form>
              <!-- form Ends --> 
              
            </div>
            <ul class="card-list">
              <!-- list-unstyled mt-3 Starts -->
              
              <?php

                if($member_details['member_languages']){

                    foreach($member_details['member_languages'] as $language){

                        ?>
              <li><!--- card-li mb-1 Starts -->
                
                <?php D($language->language_title); ?>
                - <span class="text-muted">
                <?php D(getLanguageLevelName($language->language_level)); ?>
                </span>
                <?php if($is_editable){ ?>
                <a href="<?php D(VZ);?>" onclick="DeleteLang('<?php D($language->language_id);?>')" class="btn btn-outline-danger btn-circle ml-auto"> <i class="icon-feather-trash"></i> </a>
                <?php } ?>
              </li>
              <!--- card-li mb-1 Ends -->
              
              <?php

            }

        }

        ?>
            </ul>
            <!-- list-unstyled mt-3 Ends --> 
            
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-header d-flex">
            <h4 class="">
              <?php D(__('profile_page_Skills',"Skills"));?>
            </h4>
            <?php if($is_editable){ ?>
            <button data-toggle="collapse" data-target="#add_skill" class="btn btn-site btn-circle ml-auto" title="<?php D(__('profile_page_Add_New',"Add New"));?>"> <i class="icon-feather-plus"></i> </button>
            <?php } ?>
          </div>
          <div class="card-body">
            <div id="add_skill" class="collapse form-style mb-2">
              <form action="" method="post" accept-charset="utf-8" id="skillform"  role="form" name="skillform" onsubmit="saveAccount(this);return false;">
                <input type="hidden" name="section" value="skills"/>
                <div class="form-group"><!-- form-group Starts -->
                  
                  <select class="form-control" name="skill_id" id="skill_id">
                    <option class="hidden" value="">
                    <?php D(__('profile_page_Select_Skill',"Select Skill"));?>
                    </option>
                    <?php

                                if($all_skills){

                                    foreach($all_skills as $k=>$skill){

                                        ?>
                    <option value="<?php D($skill->skill_id); ?>">
                    <?php D($skill->skill_title); ?>
                    </option>
                    <?php

                                    }

                                }

                                ?>
                  </select>
                </div>
                <!-- form-group Ends -->
                
                <div class="form-group"><!-- form-group Starts -->
                  
                  <select class="form-control" name="skill_level" id="skill_level">
                    <option class="hidden" value="">
                    <?php D(__('profile_page_Select_Level',"Select Level"));?>
                    </option>
                    <?php

                            if($skills_level){

                                foreach($skills_level as $k=>$level){

                                    ?>
                    <option value="<?php D($k); ?>">
                    <?php D($level['name']); ?>
                    </option>
                    <?php

                                }

                            }

                            ?>
                  </select>
                </div>
                <!-- form-group Ends -->
                
                <div class="form-group">
                  <button type="button" data-toggle="collapse" data-target="#add_skill" class="btn btn-secondary">
                  <?php D(__('profile_page_Cancel',"Cancel"));?>
                  </button>
                  <button type="submit" name="insert_skill" class="btn btn-site saveBTN"> <i class="icon-feather-plus"></i>
                  <?php D(__('profile_page_Add',"Add"));?>
                  </button>
                </div>
              </form>
              <!-- form Ends --> 
              
            </div>
            <ul class="card-list">
              <!-- list-unstyled mt-3 Starts -->
              
              <?php

                if($member_details['member_skills']){

                    foreach($member_details['member_skills'] as $skill){

                        ?>
              <li><!--- card-li mb-1 Starts -->
                
                <?php D($skill->skill_title); ?>
                - <span class="text-muted">
                <?php D(getSkillsLevelName($skill->skill_level)); ?>
                </span>
                <?php if($is_editable){ ?>
                <a href="<?php D(VZ);?>" onclick="DeleteSkill('<?php D($skill->skill_id);?>')" class="btn btn-outline-danger btn-circle ml-auto"><i class="icon-feather-trash"></i> </a>
                <?php } ?>
              </li>
              <!--- card-li mb-1 Ends -->
              
              <?php }

                    }	

                     ?>
            </ul>
            <!-- list-unstyled mt-3 Ends --> 
            
          </div>
          <!-- card-body Ends --> 
          
        </div>
      </div>
      <div class="col-xl-9 col-lg-8 col-12">
        <div class="card user-sidebar mb-4"><!--- card user-sidebar rounded-0 Starts -->
          
          <div class="card-header">
            <h4>
              <?php D(__('profile_page_Description',"Description"));?>
            </h4>
          </div>
          <div class="card-body">
            <p>
              <?php D($member_details['member_basic']->member_overview); ?>
            </p>
          </div>
        </div>
        <div class="mb-4">
            <h4 class="mb-4">
              <?php /*D(ucfirst($member_details['member']->member_name));*/ D($username); ?>
              <?php D(__('profile_page_proposal_service',"\'s Proposals/Services"));?>
            </h4>

            <div class="row">
              <?php

                if($proposals){

                	$proposaldata=array();

                	foreach($proposals as $k=>$proposal){

					?>
              <div class="col-md-4 col-12">
                <?php 

					 	$proposaldata['proposal']=$proposal;

					 	if($is_editable){

							$proposaldata['proposal']->hide_footer_action=1;

						}

					 	$templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

						load_template($templateLayout,$proposaldata);

					 ?>
              </div>
              <?php

					}

				}else{	

                ?>
              <div class="col-md-12">
                <?php if($is_editable) { ?>
                <h5 class="text-center text-muted">
                  <?php D(__('profile_page_Hey',"Hey"));?>
                  <?php /*D(ucfirst($member_details['member']->member_name));*/ D($username);?>
                  <?php D(__('profile_page_no_proposal',"! you have no proposals/services displayed here at the moment. Click "));?>
                  <a href="<?php D(get_link('startsellingURL'))?>" class="text-success">
                  <?php D(__('profile_page_no_proposal_click_here',"here"));?>
                  </a>
                  <?php D(__('profile_page_no_proposal_last_part',"to create a proposal/service."));?>
                </h5>
                <?php }else{ ?>
                <h5 class="text-center text-muted">
                  <?php /*D(ucfirst($member_details['member']->member_name));*/ D($username);?>
                  <?php D(__('profile_page_no_proposal_public',"does not have any proposals/services to display at the moment."));?>
                </h5>
                <?php } ?>
              </div>
              <?php	

                }

                ?>
              <?php if($is_editable AND count($proposals) > 0) { ?>
              <a href="<?php D(get_link('postproposalURL'));?>" class="col-lg-4 col-md-6 col-sm-6 mb-3">
              <div class="proposal-card-base mp-proposal-card add-new-proposal">
                <p>
                  <?php D(__('profile_page_Create_A_New_Proposal',"Create A New Proposal"));?>
                </p>
              </div>
              </a>
              <?php } ?>
            </div>
        </div>
        <script>

        $(function() {

       		$('.col-lg-3').matchHeight({

		        byRow: true,

		        property: 'height',

		        target: null,

		        remove: true

	        });

        });

        </script>
        <?php

if($member_details['member']->total_review){

        ?>
        <div class="boxed-list user-reviews mb-4">
          <div class="boxed-list-headline d-md-flex align-items-center">
            <h3 class="mb-md-0 mr-md-4"><i class="icon-feather-user"></i>
              <?php /*D(ucfirst($member_details['member']->member_name));*/ D($username);?>
              's Reviews</h3>
            <div class="star-rating mb-2 mb-md-0" data-rating="<?php printf("%.1f",$member_details['member']->avg_rating);?>"> <span class="text-muted">(
              <?php D($member_details['member']->total_review); ?>
              )</span></div>
            <div class="dropdown ml-auto">
              <button id="<dropdown-button></dropdown-button>" class="btn btn-outline-site dropdown-toggle" data-toggle="dropdown">
              <?php D(__('profile_page_Most_Recent',"Most Recent"));?>
              </button>
              <ul class="dropdown-menu">
                <li class="dropdown-item active all">
                  <?php D(__('profile_page_Most_Recent',"Most Recent"));?>
                </li>
                <li class="dropdown-item good">
                  <?php D(__('profile_page_Positive_Reviews',"Positive Reviews"));?>
                </li>
                <li class="dropdown-item bad">
                  <?php D(__('profile_page_Negative_Reviews',"Negative Reviews"));?>
                </li>
              </ul>
            </div>
          </div>
          <ul class="boxed-list-ul reviews-list" id="all">
            <?php

if($buyer_reviews){

	$good_review=$bad_review=array();

	foreach($buyer_reviews as $r=>$review){

	if($review->buyer_rating>=4){

		$good_review[]=$review;

	}else{

		$bad_review[]=$review;

	}	

	?>
            <li>
              <div class="boxed-list-item"> 
                
                <!-- Avatar -->
                
                <div class="item-image"> <img src="<?php D(getMemberLogo($review->review_buyer_id))?>" alt=""> </div>
                
                <!-- Content -->
                
                <div class="item-content">
                  <div class="d-flex justify-content-between">
                    <h4>
                      <?php D($review->buyer_name); ?>
                    </h4>
                    <p class="mb-2 text-muted"><i class="icon-feather-calendar"></i>
                      <?php D(dateFormat($review->review_date,'F d, Y')); ?>
                    </p>
                  </div>
                  <div class="star-rating" data-rating="<?php echo $review->buyer_rating;?>"></div>
                  <?php /*?><div class="item-details margin-top-7">
                <div class="detail-item"><i class="icon-feather-calendar"></i> <?php D(dateFormat($review->review_date,'F d,Y')); ?></div>
            </div><?php */?>
                  <div class="item-description mt-0">
                    <p>
                      <?php D($review->buyer_review); ?>
                    </p>
                  </div>
                </div>
              </div>
            </li>
            
            <!-- star-rating-row Ends -->
            
            <?php

	}

}else{

?>
            <li>
              <div class="alert alert-danger mb-0">
                <?php D(__('profile_page_no_review_yet',"This proposal/service has no reviews yet. Be the first to post in a review."));?>
              </div>
            </li>
            <?php

}

?>
          </ul>
          
          <!-- reviews-list Ends -->
          
          <ul class="boxed-list-ul reviews-list" id="good">
            <?php if($good_review){

	foreach($good_review as $r=>$review){

		?>
            <li>
              <div class="boxed-list-item"> 
                
                <!-- Avatar -->
                
                <div class="item-image"> <img src="<?php D(getMemberLogo($review->review_buyer_id))?>" alt=""> </div>
                
                <!-- Content -->
                
                <div class="item-content">
                  <h4><a href="#">
                    <?php D($review->buyer_name); ?>
                    </a></h4>
                  <div class="star-rating" data-rating="<?php echo $review->buyer_rating;?>"></div>
                  <div class="item-details margin-top-7">
                    <div class="detail-item"><i class="icon-feather-calendar"></i>
                      <?php D(dateFormat($review->review_date,'F d,Y')); ?>
                    </div>
                  </div>
                  <div class="item-description">
                    <p>
                      <?php D($review->buyer_review); ?>
                    </p>
                  </div>
                </div>
              </div>
            </li>
            
            <!-- star-rating-row Ends -->
            
            <?php }

}else{

?>
            <li>
              <div class="alert alert-danger mb-0">
                <?php D(__('profile_page_no_positive_review_yet',"There is currently no positive review for this proposal/service."));?>
              </div>
            </li>
            <?php }?>
          </ul>
          <!-- reviews-list Ends --> 
          
          <!-- proposal-reviews Ends -->
          
          <article class="proposal-reviews">
          <!-- proposal-reviews Starts -->
          
          <ul class="boxed-list-ul reviews-list" id="bad">
            <?php if($bad_review){

	foreach($bad_review as $r=>$review){

		?>
            <li>
              <div class="boxed-list-item"> 
                
                <!-- Avatar -->
                
                <div class="item-image"> <img src="<?php D(getMemberLogo($review->review_buyer_id))?>" alt=""> </div>
                
                <!-- Content -->
                
                <div class="item-content">
                  <h4><a href="#">
                    <?php D($review->buyer_name); ?>
                    </a></h4>
                  <div class="star-rating" data-rating="<?php echo $review->buyer_rating;?>"></div>
                  <div class="item-details margin-top-7">
                    <div class="detail-item"><i class="icon-feather-calendar"></i>
                      <?php D(dateFormat($review->review_date,'F d,Y')); ?>
                    </div>
                  </div>
                  <div class="item-description">
                    <p>
                      <?php D($review->buyer_review); ?>
                    </p>
                  </div>
                </div>
              </div>
            </li>
            <?php }

}else{

?>
            <li>
              <div class="alert alert-danger mb-0">
                <?php D(__('profile_page_no_negative_review_yet',"There is currently no negative review for this proposal/service."));?>
              </div>
            </li>
            <?php }?>
          </ul>
          <!-- reviews-list Ends --> 
          
          <!-- proposal-reviews Ends --> 
          
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <!-- Container ends --> 
  
</section>
<script type="text/javascript">

var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

$(document).ready(function(){

$('#good').hide();

$('#bad').hide();

$('.all').click(function(){

	

$("#dropdown-button").html("<?php D(__('profile_page_Most_Recent',"Most Recent"));?>");

	

$(".all").attr('class','dropdown-item all active');

	

$(".bad").attr('class','dropdown-item bad');

	

$(".good").attr('class','dropdown-item good');

	

$("#all").show();



$("#good").hide();



$("#bad").hide();

	

});	







$('.good').click(function(){

	

$("#dropdown-button").html("<?php D(__('profile_page_Positive_Reviews',"Positive Reviews"));?>");

	

$(".all").attr('class','dropdown-item all');

	

$(".bad").attr('class','dropdown-item bad');

	

$(".good").attr('class','dropdown-item good active');

	

$("#all").hide();



$("#good").show();



$("#bad").hide();

	

});	





$('.bad').click(function(){

	

$("#dropdown-button").html("<?php D(__('profile_page_Negative_Reviews',"Negative Reviews"));?>");

	

$(".all").attr('class','dropdown-item all');

	

$(".bad").attr('class','dropdown-item bad active');

	

$(".good").attr('class','dropdown-item good');

	

$("#all").hide();



$("#good").hide();



$("#bad").show();

	

});	

	

	

});



function saveAccount(ev){

	var formID=$(ev).attr('id');

	var buttonsection=$('#'+formID).find('.saveBTN');

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('editaccountURLAJAX'))?>/",

        data:$('#'+formID).serialize(),

        dataType: "json",

        cache: false,

		success: function(msg) {

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: msg['message'],

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.reload();

                })	

			} else if (msg['status'] == 'FAIL') {

				registerFormPostResponse(formID,msg['errors']);

			}

		}

	})	

}

function DeleteLang(language_id){

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('editaccountURLAJAX'))?>/",

        data:{section:'deleteLang',language_id:language_id},

        dataType: "json",

        cache: false,

		success: function(msg) {

			clearErrors();

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: msg['message'],

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.reload();

                })	

			} else if (msg['status'] == 'FAIL') {

				swal({

	                  type: 'error',

	                  text: '<?php D(__('popup_global_invalid_request',"Invalid request. Please try again!"));?>',

	                  timer: 2000,

	                  onOpen: function(){

	                    swal.showLoading()

	                  }

                  }).then(function(){

                  	window.location.reload();

                })	

			}

		}

	})

}

function DeleteSkill(skill_id){

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('editaccountURLAJAX'))?>/",

        data:{section:'deleteSkill',skill_id:skill_id},

        dataType: "json",

        cache: false,

		success: function(msg) {

			clearErrors();

			if (msg['status'] == 'OK') {

				 swal({

                  type: 'success',

                  text: msg['message'],

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.reload();

                })	

			} else if (msg['status'] == 'FAIL') {

				swal({

	                  type: 'error',

	                  text: '<?php D(__('popup_global_invalid_request',"Invalid request. Please try again!"));?>',

	                  timer: 2000,

	                  onOpen: function(){

	                    swal.showLoading()

	                  }

                  }).then(function(){

                  	window.location.reload();

                })	

			}

		}

	})

}

</script>