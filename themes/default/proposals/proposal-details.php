<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5d70d2e3181386bd"></script>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

//dd($proposal_details,TRUE);

$average_rating = 0;

$total_rating = 0;

$count_reviews = count($buyer_reviews);

if ($buyer_reviews) {

  foreach ($buyer_reviews as $rating) {

    $total_rating += $rating->buyer_rating;
  }

  $average_rating = $total_rating / $count_reviews;
}

$max_checkout_qty = get_option_value('max_checkout_qty');

?>
<?php
$section_data=array(
  array('name'=>'Logo transparency','key'=>'logo_transparency','tooltip'=>"You'll get a logo image with a transparent background. Ex. PNG"),
  array('name'=>'Vector file','key'=>'vector_file','tooltip'=>"You'll get a vector-based logo image that can be scaled without loss of quality or pixelation. Ex. EPS, AI, and PDF"),
  array('name'=>'Printable file','key'=>'printable_file','tooltip'=>"You'll get a high-resolution logo file suitable for printing—at least 300 dpi or 2000 px."),
  array('name'=>'3D mockup','key'=>'3d_mockup','tooltip'=>"You'll get a 3D mockup of your logo design to use for promotional purposes."),
  array('name'=>'Source file','key'=>'source_file','tooltip'=>"You'll get an original source file that you can edit according to your needs."),
  array('name'=>'Social media kit','key'=>'social_media_kit','tooltip'=>"You'll get graphics showing your logo that you can use on social media platforms. Ex. Facebook and Instagram."),
  array('name'=>'No. of concepts included','key'=>'no_of_concept','tooltip'=>"A number of logo concepts are included in the package and, from this, you'll get one final logo design."),
  array('name'=>'Revisions','key'=>'revisions','tooltip'=>"The number of tweaks the seller includes."),
);
?>
<div class="breadcrumbs">
  <div class="container">
    <h1>
      <?php D(ucfirst($proposal_details['proposal']->proposal_title)); ?>
    </h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php D(get_link('CategoryURL') . $proposal_details['proposal_category']->category_key) ?>">
        <?php D($proposal_details['proposal_category']->category_name); ?>
        </a> </li>
      <li class="breadcrumb-item"><a href="<?php D(get_link('CategoryURL') . $proposal_details['proposal_category']->category_key) ?>/<?php D($proposal_details['proposal_category']->category_subchild_key); ?>">
        <?php D($proposal_details['proposal_category']->category_subchild_name); ?>
        </a></li>
    </ol>
  </div>
</div>
<section class="section">
  <div class="container"> 
    <!-- Container starts -->    
    <div class="row">
      <div class="col-lg-8 mb-3">
        <div class="card rounded-0 mb-3">
          <div class="card-body">
            <div class="proposal-info">              
              <div class="row">                            
                  <div class="col-md">
                    <p><i class="icon-feather-calendar"></i> <?php echo dateFormat($proposal_details['proposal']->proposal_date,'F d, Y');?></p>
                    <div class="star-rating" data-rating="<?php echo round($average_rating); ?>"> </div>
                    <span>(<?php D($count_reviews); ?>)</span>
                    <?php
					  if($loggedUser){ 
						if($proposal_details['proposal']->proposal_seller_id != $loggedUser['MID']){
						  $is_favorite=is_favorite($loggedUser['MID'],$proposal_details['proposal']->proposal_id);
						  if($is_favorite){
							$show_favorite_class = "mark-unfav";
						  }else{
							$show_favorite_class = "mark-fav ";
						  }
						  ?>
						<div class="position-relative"><i style="top: 0.5rem; left:0;" data-id="<?php D($proposal_details['proposal']->proposal_id); ?>" href="#" class="icon-line-awesome-heart <?php D($show_favorite_class); ?>" data-toggle="tooltip" data-placement="top" title="Favorite"></i></div>
					  <?php }
					  }
					?>
                  </div>
                  <div class="col-md"> 
                    <p><i class="icon-feather-eye"></i> <?php echo getnoofviews($proposal_details['proposal']->proposal_id);?></p>
                    <p><i class="icon-feather-users"></i> <?php D($proposal_order_queue); ?> Order(s) In Queue.</p> 
                    <?php

					if ($is_login) {
	
					  if (!$is_owner) {
	
						if ($is_report == 0) {
	
					?>
					<p><i class="icon-feather-flag"></i> <a href="#" data-toggle="modal" data-target="#report-modal">Report </a></p>
					<?php
	
						}
					  }
					}
					?>
                  </div>
                  <div class="col-md">
                  	<p class="mb-2"><i class="icon-feather-share-2"></i> Share this Job:</p>
                      <ul class="share-job">
                        <li><a href=""><i class="icon-brand-facebook"></i></a></li>
                        <li><a href=""><i class="icon-brand-twitter"></i></a></li>
                        <li><a href=""><i class="icon-brand-linkedin"></i></a></li>
                        <li><a href=""><i class="icon-brand-instagram"></i></a></li>
                        <li><a href=""><i class="icon-brand-youtube"></i></a></li>
                      </ul>
                  </div>
              </div>                           
            </div>
          </div>
          <div id="myCarousel" class="carousel slide">
            <ol class="carousel-indicators">
              <?php if ($proposal_details['proposal_additional']->proposal_video) { ?>
              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
              <?php } ?>
              <li data-target="#myCarousel" data-slide-to="1" class="<?php if (empty($proposal_details['proposal_additional']->proposal_video)) { D("active");
              } ?>"></li>
              <?php

              $cntmyCarousel = 1;

              if ($proposal_details['proposal_files']) {

                foreach ($proposal_details['proposal_files'] as $f => $file) {

                  $cntmyCarousel++;

              ?>
              <li data-target="#myCarousel" data-slide-to="<?php D($cntmyCarousel); ?>"></li>
              <?php

                }
              } ?>
            </ol>
            <div class="carousel-inner">
              <?php if ($proposal_details['proposal_additional']->proposal_video) { ?>
              <div class="carousel-item active">
                <?php if (get_option_value('jwplayer_code')) { ?>
                <script type="text/javascript" src="<?php echo $jwplayer_code; ?>"></script>
                <div class="d-block w-100" id="player"></div>
                <script type="text/javascript">
                      var player = jwplayer('player');

                      player.setup({

                        file: "<?php D(URL_USERUPLOAD) ?>proposal-video/<?php D($proposal_details['proposal_additional']->proposal_video); ?>",

                        image: "<?php D(URL_USERUPLOAD) ?>proposal-files/<?php D($proposal_details['proposal']->proposal_image); ?>"

                      })
                    </script>
                <?php } else { ?>
                <video class="embed-responsive embed-responsive-16by9" style="background-color:black;" controls>
                  <source class="embed-responsive-item" src="<?php D(URL_USERUPLOAD) ?>proposal-video/<?php D($proposal_details['proposal_additional']->proposal_video); ?>" type="video/mp4">
                  <source src="<?php D(URL_USERUPLOAD) ?>proposal-video/<?php D($proposal_details['proposal_additional']->proposal_video); ?>" type="video/ogg">
                </video>
                <?php } ?>
              </div>
              <?php } ?>
              <div class="carousel-item <?php if (empty($proposal_details['proposal_additional']->proposal_video)) {
                                          D("active");
                                        } ?>"> <img class="d-block w-100" src="<?php D(URL_USERUPLOAD) ?>proposal-files/<?php D($proposal_details['proposal']->proposal_image); ?>"> </div>
              <?php

              $cntmyCarousel = 1;

              if ($proposal_details['proposal_files']) {

                foreach ($proposal_details['proposal_files'] as $f => $file) {

                  $cntmyCarousel++;

              ?>
              <div class="carousel-item"> <img class="d-block w-100" src="<?php D(URL_USERUPLOAD) ?>proposal-files/<?php D($file->server_name); ?>"> </div>
              <?php }
              }

              ?>
            </div>
            <a class="carousel-control-prev slide-nav slide-right" href="#myCarousel" data-slide="prev"> <span class="carousel-control-prev-icon carousel-icon"></span> </a> <a class="carousel-control-next slide-nav slide-left" href="#myCarousel" data-slide="next"> <span class="carousel-control-next-icon carousel-icon"></span> </a> </div>
          
        </div>
        <h3><?php D(__('proposal_details_page_description', "Description")); ?></h3>
        <div class="card mb-4">          
          <div class="card-body">            
            <?php D(html_entity_decode($proposal_details['proposal_additional']->proposal_description)); ?>                        
          </div>
        </div>
        <?php if ($proposal_details['proposal']->proposal_price == 0) {

          $attributedata = array();

        ?>
        <h3><?php D(__('proposal_details_page_Packages', "Packages")); ?></h3>
        <div class="card mb-3" id="compare">
          <div class="card-body p-0">
            <div class="package cpackage d-sm-none">
              <div class="column">
                <div>                  
                  <p class="mt-3 mb-3"><b>
                    <?php D(__('proposal_details_page_package_Total', "Total")); ?>
                    </b></p>
                  <p><b>
                    <?php D(__('proposal_details_page_package_Description', "Description")); ?>
                    </b></p>
                  <p><b>
                    <?php D(__('proposal_details_page_package_Delivery_Time', "Delivery Time")); ?>
                    </b></p>
                </div>
              </div>
              <?php if ($proposal_details['proposal_packages']) {

                  foreach ($proposal_details['proposal_packages'] as $package) {

                    $attributedata = getData(array(

                      'select' => 'p.attribute_id,p.attribute_name,p.attribute_value',

                      'table' => 'proposal_package_attributes p',

                      'where' => array('p.package_id' => $package->package_id),

                      'order' => array(array('p.attribute_id', 'asc')),

                    ));

                ?>
              <div class="column">
                <div>
                  <h4 class="text-center text-site">
                    <?php D(__('proposal_details_page_package_' . $package->package_name, $package->package_name)); ?>
                  </h4>
                  <h2 class="text-center">
                    <?php D(CURRENCY); ?>
                    <b>
                    <?php D($package->price); ?>
                    </b></h2>
                  <p><?php echo $package->description; ?></p>
                  <?php if ($attributedata) {
                          foreach ($attributedata as $a => $attributecontent) {

                        ?>
                  <p>
                  <?php D($attributecontent->attribute_name); ?>: <?php D($attributecontent->attribute_value); ?>
                  </p>
                  <?php

                          }
                        }

                        ?>
                  <p><?php echo $package->delivery_time; ?> Days</p>
                  <form method="post" id="checkoutFormP_<?php D($package->package_id); ?>" onsubmit="return checkoutForm(this);return false;">
                    <input type="hidden" name="proposal_id" value="<?php D($proposal_details['proposal']->proposal_id); ?>">
                    <input type="hidden" name="package_id" value="<?php D($package->package_id); ?>">
                    <select class="form-control mb-2 d-none" name="proposal_qty">
                      <?php

                            for ($i = 1; $i <= $max_checkout_qty; $i++) {

                            ?>
                      <option>
                      <?php D($i); ?>
                      </option>
                      <?php

                            }

                            ?>
                    </select>
                    <?php

                          if ($is_login) {

                            if ($owner_details['member']->is_vacation) {
                            } else { ?>
                    <button class="btn btn-site btn-block saveBTN" type="submit" name="add_order">
                    <?php D(__('proposal_details_page_package_Select', "Select")); ?>
                    </button>
                    <?php }
                          } else { ?>
                    <a href="<?php D(get_link('loginURL')) ?>" class="btn btn-site btn-block">
                    <?php D(__('proposal_details_page_package_Select', "Select")); ?>
                    </a>
                    <?php } ?>
                  </form>
                </div>
              </div>
              <?php

                  }
                }

                ?>
            </div>
            <div class="table-responsive package-table d-none d-sm-block">
              <table class="table table-bordered mb-0">
                <thead>
                  <tr>
                    <th><?php D(__('proposal_details_page_package_Total', "Total")); ?></th>
                    <?php if ($proposal_details['proposal_packages']) {
                        foreach ($proposal_details['proposal_packages'] as $package) {
                      ?>
                    <th><h2>
                        <?php D(CURRENCY); ?>
                        <b>
                        <?php D($package->price); ?>
                        </b> </h2></th>
                    <?php
                        }
                      }
                      ?>
                  </tr>
                  <tr>
                    <th><h4>Features</h4></th>
                    <?php if ($proposal_details['proposal_packages']) {
                        foreach ($proposal_details['proposal_packages'] as $package) {
                      ?>
                    <th><h4>
                        <?php D(__('proposal_details_page_package_' . $package->package_name, $package->package_name)); ?>
                      </h4></th>
                    <?php
                        }
                      }
                      ?>
                  </tr>
                </thead>
                <tbody>
                  
                  <tr>
                    <th class="b-ccc"><?php D(__('proposal_details_page_package_Description', "Description")); ?></th>
                    <?php if ($proposal_details['proposal_packages']) {
                        $attributedata=array();
                        foreach ($proposal_details['proposal_packages'] as $package) {
                          $attributedata[] = getData(array(
                            'select' => 'p.attribute_id,p.attribute_name,p.attribute_value',
                            'table' => 'proposal_package_attributes p',
                            'where' => array('p.package_id' => $package->package_id),
                            'order' => array(array('p.attribute_id', 'asc')),
                          ));
                      ?>
                    <td><?php echo $package->description; ?>
                      <?php /*?><?php echo getDefaultText($package->description,$package->description_ar,$this->currentlang); ?><?php */ ?></td>
                    <?php
                        }
                      }
                      ?>
                  </tr>
                  <?php
                  if($proposal_details['module_attributes']){
                    $all_attr_values=$proposal_details['module_attributes'];
                    foreach($section_data as $sectionrow){
                      if(array_key_exists($sectionrow['key'],$proposal_details['module_attributes'])){
                        if($sectionrow['key']=='no_of_concept' || $sectionrow['key']=='revisions'){
                          $text_section_1=$all_attr_values[$sectionrow['key']][0];
                          $text_section_2=$all_attr_values[$sectionrow['key']][1];
                          $text_section_3=$all_attr_values[$sectionrow['key']][2];
                        }else{
                          $text_section_1=($all_attr_values[$sectionrow['key']][0]=='1'?'<i class="icon-feather-check text-primary font-weight-bold"></i>':'<i class="icon-feather-check  text-secondary font-weight-bold"></i>');
                          $text_section_2=($all_attr_values[$sectionrow['key']][1]=='1'?'<i class="icon-feather-check text-primary font-weight-bold"></i>':'<i class="icon-feather-check  text-secondary font-weight-bold"></i>');
                          $text_section_3=($all_attr_values[$sectionrow['key']][2]=='1'?'<i class="icon-feather-check text-primary font-weight-bold"></i>':'<i class="icon-feather-check  text-secondary font-weight-bold"></i>');
                        }
                        
                    }
                     
                  ?>
                  <tr>
                    <th class="b-ccc" data-toggle="popover" data-trigger="hover"  data-content="<?php echo $sectionrow['tooltip']?>" data-placement="top"><?php D($sectionrow['name']); ?></th>
                    <td><?php echo $text_section_1;?></td>
                    <td><?php echo $text_section_2;?></td>
                    <td><?php echo $text_section_3;?></td>
                  </tr>
                  <?php
                    }
                  }

?>    

                  
                  <?php if ($attributedata) {
                      foreach ($attributedata[0] as $a => $attributecontent) {
                    ?>
                  <tr>
                    <th class="b-ccc"><?php D($attributecontent->attribute_name); ?></th>
                    <td><?php D($attributedata[0][$a]->attribute_value); ?></td>
                    <td><?php D($attributedata[1][$a]->attribute_value); ?></td>
                    <td><?php D($attributedata[2][$a]->attribute_value); ?></td>
                  </tr>
                  <?php
                      }
                    }
                    ?>
                  <tr>
                    <th class="b-ccc text-left" width="150"><?php D(__('proposal_details_page_package_Delivery_Time', "Delivery Time")); ?></th>
                    <?php if ($proposal_details['proposal_packages']) {
                        foreach ($proposal_details['proposal_packages'] as $package) {
                      ?>
                    <td><?php echo $package->delivery_time; ?>
                      <?php D(__('proposal_details_page_package_Days', "Days")); ?></td>
                    <?php
                        }
                      }
                      ?>
                  </tr>
                  <tr>
                    <th class="b-ccc text-left">&nbsp;</th>
                    <?php if ($proposal_details['proposal_packages']) {
                        foreach ($proposal_details['proposal_packages'] as $package) {
                      ?>
                    <td><form method="post" id="checkoutFormP_<?php D($package->package_id); ?>" onsubmit="return checkoutForm(this);return false;">
                        <input type="hidden" name="proposal_id" value="<?php D($proposal_details['proposal']->proposal_id); ?>">
                        <input type="hidden" name="package_id" value="<?php D($package->package_id); ?>">
                        <select class="form-control mb-2 d-none" name="proposal_qty">
                          <?php
                                for ($i = 1; $i <= $max_checkout_qty; $i++) {
                                ?>
                          <option>
                          <?php D($i); ?>
                          </option>
                          <?php
                                }
                                ?>
                        </select>
                        <?php
                              if ($is_login) {
                                if ($owner_details['member']->is_vacation) {
                                } else { ?>
                        <button class="btn btn-site btn-block saveBTN" type="submit" name="add_order">
                        <?php D(__('proposal_details_page_package_Select', "Select")); ?>
                        </button>
                        <?php }
                              } else { ?>
                        <a href="<?php D(get_link('loginURL')) ?>" class="btn btn-site btn-block">
                        <?php D(__('proposal_details_page_package_Select', "Select")); ?>
                        </a>
                        <?php } ?>
                      </form></td>
                    <?php
                        }
                      }
                      ?>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php } ?>
        
        
        <!-- End Proposal -->
        <div class="d-flex align-items-center mb-3">
            <h3>
              <?php D(__('proposal_details_page_Reviews', "Reviews")); ?>
              <span class="comments-amount">(<?php echo $count_reviews; ?>)</span> </h3>
            <div class="d-flex align-items-center ml-auto">
            <div class="star-rating d-block" data-rating="<?php echo round($average_rating); ?>"></div>            
            <span class="text-muted">
            <?php

              printf("%.1f", $average_rating);

              ?>
            </span>
            <div class="dropdown ml-3">
              <button id="dropdown-button" class="btn btn-outline-site dropdown-toggle" data-toggle="dropdown">
              <?php D(__('proposal_details_page_Most_Recent', "Most Recent")); ?>
              </button>
              <div class="dropdown-menu"> <a href="javascript:void(0)" class="dropdown-item active all">
                <?php D(__('proposal_details_page_Most_Recent', "Most Recent")); ?>
                </a> <a href="javascript:void(0)" class="dropdown-item good">
                <?php D(__('proposal_details_page_Positive_Reviews', "Positive Reviews")); ?>
                </a> <a href="javascript:void(0)" class="dropdown-item bad">
                <?php D(__('proposal_details_page_Negative_Reviews', "Negative Reviews")); ?>
                </a> </div>
            </div>
            </div>
          </div>
        
        <div class="card">  
          <div class="card-body comments">
            <ul id="all">
              <?php

              if ($buyer_reviews) {

                $good_review = $bad_review = array();

                foreach ($buyer_reviews as $r => $review) {

                  if ($review->buyer_rating >= 4) {

                    $good_review[] = $review;
                  } else {

                    $bad_review[] = $review;
                  }

              ?>
              <li>
                <div class="avatar"><img src="<?php D(getMemberLogo($review->review_buyer_id)) ?>" alt=""></div>
                <div class="comment-content">
                  <div class="arrow-comment"></div>
                  <div class="comment-by">
                    <h5>
                      <?php D($review->buyer_name); ?>
                    </h5>
                    <span class="date">
                    <i class="icon-feather-calendar"></i> <?php D(dateFormat($review->review_date, 'F d,Y')); ?>
                    </span> 
                    
                    <!--<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>--> 
                    
                  </div>
                  <div class="star-rating proposal-rating" data-rating="<?php echo round($average_rating, 1); ?>"></div>
                  <p>
                    <?php D($review->buyer_review); ?>
                  </p>
                </div>
                <?php if ($review->seller_review_id) { ?>
                <ul>
                  <li>
                    <div class="avatar"><img src="<?php D(getMemberLogo($proposal_details['proposal']->proposal_seller_id)) ?>" alt=""></div>
                    <div class="comment-content">
                      <div class="arrow-comment"></div>
                      <div class="comment-by">
                        <h5>
                          <?php D(__('proposal_details_page_Freelancers_Feedback', "Freelancer\'s Feedback")); ?>
                        </h5>
                        <span class="date">
                        <?php D(dateFormat($review->review_date, 'F d,Y')); ?>
                        </span> 
                        
                        <!--<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>--> 
                        
                      </div>
                      <div class="star-rating proposal-rating" data-rating="<?php echo round($average_rating, 1); ?>"></div>
                      <p>
                        <?php D($review->seller_review); ?>
                      </p>
                    </div>
                  </li>
                </ul>
                <?php } ?>
              </li>
              <?php

                }
              } else {

                ?>
              <li style="margin:0;">
                <p class='text-center text-muted'>
                  <?php D(__('proposal_details_page_no_review_yet', "This proposal/service has no reviews yet. Be the first to post in a review.")); ?>
                </p>
              </li>
              <?php

              }

              ?>
              <li class="text-center"><a href="#" class="btn btn-outline-site">More Reviews</a></li>
            </ul>
            <ul id="good">
              <?php if ($good_review) {

                foreach ($good_review as $r => $review) {

              ?>
              <li>
                <div class="avatar"><img src="<?php D(getMemberLogo($review->review_buyer_id)) ?>" alt=""></div>
                <div class="comment-content">
                  <div class="arrow-comment"></div>
                  <div class="comment-by">
                    <h5>
                      <?php D($review->buyer_name); ?>
                    </h5>
                    <span class="date">
                    <?php D(dateFormat($review->review_date, 'F d,Y')); ?>
                    </span> 
                    
                    <!--<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>--> 
                    
                  </div>
                  <div class="star-rating proposal-rating" data-rating="<?php echo round($average_rating, 1); ?>"></div>
                  <p>
                    <?php D($review->buyer_review); ?>
                  </p>
                </div>
                <?php if ($review->seller_review_id) { ?>
                <ul>
                  <li>
                    <div class="avatar"><img src="<?php D(getMemberLogo($proposal_details['proposal']->proposal_seller_id)) ?>" alt=""></div>
                    <div class="comment-content">
                      <div class="arrow-comment"></div>
                      <div class="comment-by">
                        <h5>
                          <?php D(__('proposal_details_page_Freelancers_Feedback', "Freelancer\'s Feedback")); ?>
                        </h5>
                        <span class="date">
                        <?php D(dateFormat($review->review_date, 'F d,Y')); ?>
                        </span> 
                        
                        <!--<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>--> 
                        
                      </div>
                      <div class="star-rating proposal-rating" data-rating="<?php echo round($average_rating, 1); ?>"></div>
                      <p>
                        <?php D($review->seller_review); ?>
                      </p>
                    </div>
                  </li>
                </ul>
                <?php } ?>
              </li>
              <?php

                }
              } else {

                ?>
              <li>
                <h5 class='text-center text-muted'>
                  <?php D(__('proposal_details_page_no_positive_review_yet', "There is currently no positive review for this proposal/service.")); ?>
                </h5>
              </li>
              <?php

              }

              ?>
            </ul>
            <ul id="bad">
              <?php if ($bad_review) {

                foreach ($bad_review as $r => $review) {

              ?>
              <li>
                <div class="avatar"><img src="<?php D(getMemberLogo($review->review_buyer_id)) ?>" alt=""></div>
                <div class="comment-content">
                  <div class="arrow-comment"></div>
                  <div class="comment-by">
                    <h5>
                      <?php D($review->buyer_name); ?>
                    </h5>
                    <span class="date">
                    <?php D(dateFormat($review->review_date, 'F d,Y')); ?>
                    </span> 
                    
                    <!--<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>--> 
                    
                  </div>
                  <div class="star-rating proposal-rating" data-rating="<?php echo round($average_rating, 1); ?>"></div>
                  <p>
                    <?php D($review->buyer_review); ?>
                  </p>
                </div>
                <?php if ($review->seller_review_id) { ?>
                <ul>
                  <li>
                    <div class="avatar"><img src="<?php D(getMemberLogo($proposal_details['proposal']->proposal_seller_id)) ?>" alt=""></div>
                    <div class="comment-content">
                      <div class="arrow-comment"></div>
                      <div class="comment-by">
                        <h5>
                          <?php D(__('proposal_details_page_Freelancers_Feedback', "Freelancer\'s Feedback")); ?>
                        </h5>
                        <span class="date">
                        <?php D(dateFormat($review->review_date, 'F d,Y')); ?>
                        </span> 
                        
                        <!--<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>--> 
                        
                      </div>
                      <div class="star-rating proposal-rating" data-rating="<?php echo round($average_rating, 1); ?>"></div>
                      <p>
                        <?php D($review->seller_review); ?>
                      </p>
                    </div>
                  </li>
                </ul>
                <?php } ?>
              </li>
              <?php

                }
              } else {

                ?>
              <li>
                <h5 class='text-center text-muted'>
                  <?php D(__('proposal_details_page_no_negative_review_yet', "There is currently no Negative review for this proposal/service.")); ?>
                </h5>
              </li>
              <?php

              }

              ?>
            </ul>
          </div>
        </div>
        <?php /*?><div class="card proposal-reviews rounded-0 mb-3">

          <div class="card-header d-flex">

            <h4> <?php D(__('proposal_details_page_Reviews',"Reviews"));?> <span class="comments-amount"><?php echo $count_reviews; ?></span></h4>

            <div class="star-rating d-block ml-auto" data-rating="<?php echo round($average_rating);?>"></div>

            <span class="text-muted">

            <?php

			printf("%.1f", $average_rating);

			?>

            </span>

            <div class="dropdown ml-auto">

              <button id="dropdown-button" class="btn btn-site dropdown-toggle" data-toggle="dropdown">

              <?php D(__('proposal_details_page_Most_Recent',"Most Recent"));?>

              </button>

              <ul class="dropdown-menu">

                <li class="dropdown-item active all">

                  <?php D(__('proposal_details_page_Most_Recent',"Most Recent"));?>

                </li>

                <li class="dropdown-item  good">

                  <?php D(__('proposal_details_page_Positive_Reviews',"Positive Reviews"));?>

                </li>

                <li class="dropdown-item  bad">

                  <?php D(__('proposal_details_page_Negative_Reviews',"Negative Reviews"));?>

                </li>

              </ul>

            </div>

          </div>

          <div class="card-body">

            <article id="all" class="proposal-reviews">

              <ul class="reviews-list">

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

                <li class="star-rating-row"><!-- star-rating-row Starts --> 

                  <span class="user-picture"><!-- user-picture Starts --> 

                  <img src="<?php D(getMemberLogo($review->review_buyer_id))?>" width="60" height="60"> </span><!-- user-picture Ends -->

                  <h4><!-- h4 Starts --> 

                    <a class="test mr-1" href="#">

                    <?php D($review->buyer_name); ?>

                    </a>

					<?php

                        for($buyer_i=0; $buyer_i<$review->buyer_rating; $buyer_i++){

                            echo " <img class='rating' src='".theme_url().IMAGE."user_rate_full.png' > ";

                        }

                        for($buyer_i=$review->buyer_rating; $buyer_i<5; $buyer_i++){

                            echo " <img class='rating' src='".theme_url().IMAGE."user_rate_blank.png' > ";

                        }

                    ?>

                  </h4>

                  <!-- h4 Ends -->

                  

                  <div class="msg-body"><!-- msg-body Starts -->

                    <?php D($review->buyer_review); ?>

                  </div>

                  <!-- msg-body Ends --> 

                  <span class="rating-date">

                  <?php D(dateFormat($review->review_date,'F d,Y')); ?>

                  </span> </li>

                <!-- star-rating-row Ends -->

                <?php if($review->seller_review_id){ ?>

                <li class="rating-seller"><!-- rating-seller Starts -->

                  <h4><!-- h4 Starts --> 

                    <span class="mr-1">

                    <?php D(__('proposal_details_page_Freelancers_Feedback',"Freelancer\'s Feedback"));?>

                    </span>

                    <?php

						for($seller_i=0; $seller_i<$review->seller_rating; $seller_i++){

							echo " <img class='rating' src='".theme_url().IMAGE."user_rate_full.png' > ";

						}

						for($seller_i=$review->seller_rating; $seller_i<5; $seller_i++){

							echo " <img class='rating' src='".theme_url().IMAGE."user_rate_blank.png' > ";

						}

					?>

                  </h4>

                  <!-- h4 Ends --> 

                  <span class="user-picture"><!-- user-picture Starts --> 

                  <img src="<?php D(getMemberLogo($proposal_details['proposal']->proposal_seller_id))?>" width="40" height="40"> </span><!-- user-picture Ends -->

                  <div class="msg-body"><!-- msg-body Starts -->

                    <?php D($review->seller_review); ?>

                  </div>

                  <!-- msg-body Ends --> 

                </li>

                <!-- rating-seller Ends -->

                <?php }?>

                <hr>

				<?php

					}

				}else{

				?>

                <li>

                  <h5 class='text-center text-muted'>

                    <?php D(__('proposal_details_page_no_review_yet',"This proposal/service has no reviews yet. Be the first to post in a review."));?>

                  </h5>

                </li>

                <?php

}

?>

              </ul>

              <!-- reviews-list Ends --> 

            </article>

			

            <article id="good" class="proposal-reviews"><!-- proposal-reviews Starts -->

              <ul class="reviews-list">

                <!-- reviews-list Starts -->

                <?php if($good_review){

					foreach($good_review as $r=>$review){

				?>

                <li class="star-rating-row"><!-- star-rating-row Starts --> 

                  <span class="user-picture"><!-- user-picture Starts --> 

                  <img src="<?php D(getMemberLogo($review->review_buyer_id))?>" width="60" height="60"> </span><!-- user-picture Ends -->

                  <h4><!-- h4 Starts --> 

                    <a class="text-success mr-1" href="#">

                    <?php D($review->buyer_name); ?>

                    </a>

                    <?php

	for($buyer_i=0; $buyer_i<$review->buyer_rating; $buyer_i++){

		echo " <img class='rating' src='".theme_url().IMAGE."user_rate_full.png' > ";

	}

	for($buyer_i=$review->buyer_rating; $buyer_i<5; $buyer_i++){

		echo " <img class='rating' src='".theme_url().IMAGE."user_rate_blank.png' > ";

	}

?>

                  </h4>

                  <!-- h4 Ends -->

                  

                  <div class="msg-body"><!-- msg-body Starts -->

                    <?php D($review->buyer_review); ?>

                  </div>

                  <!-- msg-body Ends --> 

                  <span class="rating-date">

                  <?php D(dateFormat($review->review_date,'F d,Y')); ?>

                  </span> </li>

                <!-- star-rating-row Ends -->

                <?php if($review->seller_review_id){ ?>

                <li class="rating-seller"><!-- rating-seller Starts -->

                  <h4><!-- h4 Starts --> 

                    <span class="mr-1">

                    <?php D(__('proposal_details_page_Freelancers_Feedback',"Freelancer\'s Feedback"));?>

                    </span>

                    <?php

		for($seller_i=0; $seller_i<$review->seller_rating; $seller_i++){

			echo " <img class='rating' src='".theme_url().IMAGE."user_rate_full.png' > ";

		}

		for($seller_i=$review->seller_rating; $seller_i<5; $seller_i++){

			echo " <img class='rating' src='".theme_url().IMAGE."user_rate_blank.png' > ";

		}

	?>

                  </h4>

                  <!-- h4 Ends --> 

                  <span class="user-picture"><!-- user-picture Starts --> 

                  <img src="<?php D(getMemberLogo($proposal_details['proposal']->proposal_seller_id))?>" width="40" height="40"> </span><!-- user-picture Ends -->

                  <div class="msg-body"><!-- msg-body Starts -->

                    <?php D($review->seller_review); ?>

                  </div>

                  <!-- msg-body Ends --> 

                </li>

                <!-- rating-seller Ends -->

                <?php }?>

                <hr>

                <?php }

}else{

?>

                <li>

                  <h5 class='text-center text-muted'>

                    <?php D(__('proposal_details_page_no_positive_review_yet',"There is currently no positive review for this proposal/service."));?>

                  </h5>

                </li>

                <?php }?>

              </ul>

              <!-- reviews-list Ends --> 

            </article>

            <!-- proposal-reviews Ends -->

            <article id="bad" class="proposal-reviews"><!-- proposal-reviews Starts -->

              <ul class="reviews-list">

                <!-- reviews-list Starts -->

                <?php if($bad_review){

					foreach($bad_review as $r=>$review){

				?>

                <li class="star-rating-row"><!-- star-rating-row Starts --> 

                  <span class="user-picture"><!-- user-picture Starts --> 

                  <img src="<?php D(getMemberLogo($review->review_buyer_id))?>" width="60" height="60"> </span><!-- user-picture Ends -->

                  <h4><!-- h4 Starts --> 

                    <a class="text-success mr-1" href="#">

                    <?php D($review->buyer_name); ?>

                    </a>

                    <?php

	for($buyer_i=0; $buyer_i<$review->buyer_rating; $buyer_i++){

		echo " <img class='rating' src='".theme_url().IMAGE."user_rate_full.png' > ";

	}

	for($buyer_i=$review->buyer_rating; $buyer_i<5; $buyer_i++){

		echo " <img class='rating' src='".theme_url().IMAGE."user_rate_blank.png' > ";

	}

?>

                  </h4>

                  <!-- h4 Ends -->

                  

                  <div class="msg-body"><!-- msg-body Starts -->

                    <?php D($review->buyer_review); ?>

                  </div>

                  <!-- msg-body Ends --> 

                  <span class="rating-date">

                  <?php D(dateFormat($review->review_date,'F d,Y')); ?>

                  </span> </li>

                <!-- star-rating-row Ends -->

                <?php if($review->seller_review_id){ ?>

                <li class="rating-seller"><!-- rating-seller Starts -->

                  <h4><!-- h4 Starts --> 

                    <span class="mr-1">

                    <?php D(__('proposal_details_page_Freelancers_Feedback',"Freelancer\'s Feedback"));?>

                    </span>

                    <?php

		for($seller_i=0; $seller_i<$review->seller_rating; $seller_i++){

			echo " <img class='rating' src='".theme_url().IMAGE."user_rate_full.png' > ";

		}

		for($seller_i=$review->seller_rating; $seller_i<5; $seller_i++){

			echo " <img class='rating' src='".theme_url().IMAGE."user_rate_blank.png' > ";

		}

	?>

                  </h4>

                  <!-- h4 Ends --> 

                  <span class="user-picture"><!-- user-picture Starts --> 

                  <img src="<?php D(getMemberLogo($proposal_details['proposal']->proposal_seller_id))?>" width="40" height="40"> </span><!-- user-picture Ends -->

                  <div class="msg-body"><!-- msg-body Starts -->

                    <?php D($review->seller_review); ?>

                  </div>

                  <!-- msg-body Ends --> 

                </li>

                <!-- rating-seller Ends -->

                <?php }?>

                <hr>

                <?php }

}else{

?>

                <li>

                  <h5 class='text-center text-muted'>

                    <?php D(__('proposal_details_page_no_negative_review_yet',"There is currently no Negative review for this proposal/service."));?>

                  </h5>

                </li>

                <?php }?>

              </ul>

              <!-- reviews-list Ends --> 

            </article>

            <!-- proposal-reviews Ends --> 

          </div>

        </div><?php */ ?>
        
      </div>
      <div class="col-lg-4 proposal-sidebar">
       
        <!-- Col starts -->
        <div>
          	<?php
                $token = md5('FVRR' . '-' . date("Y-m-d") . '-' . $proposal_details['proposal']->proposal_id);
                if ($owner_details['member']->is_vacation) {
                  if ($is_owner) {
                ?>
              <div class="card">
                <div class="card-body">
                  <h4 style="line-height:25px;">
                    <?php D(__('proposal_details_page_vacation_on_text_for_freelancer', "Your vacation mode has been switched to")); ?>
                    <span class="badge badge-success">
                    <?php D(__('proposal_details_page_vacation_ON', "ON")); ?>
                    </span>
                    <?php D(__('proposal_details_page_vacation_ON_info_for_freelancer', "for this reason, no one is able to purchase any of your proposals/services until you switch vacation mode back to ")); ?>
                    <span class="badge badge-success">
                    <?php D(__('proposal_details_page_vacation_OFF', "OFF")); ?>
                    </span><br>
                    <?php D(__('proposal_details_page_vacation_ON_ready_to_off', "Ready to switch it back off?")); ?>
                    <a class="text-success" href="<?php D(get_link('manageproposalURL')) ?>">
                    <?php D(__('proposal_details_page_Click_here', "Click here")); ?>
                    </a> </h4>
                </div>
              </div>
          		<?php

                } else {
                ?>
              <div class="card">
                <div class="card-body">
                  <h4 style="line-height: 25px;">
                    <?php D(__('proposal_details_page_vacation_on_text_for_buyer', "Freelancer vacation mode has been switched to")); ?>
                    <span class="badge badge-success">
                    <?php D(__('proposal_details_page_vacation_ON', "ON")); ?>
                    </span>
                    <?php D(__('proposal_details_page_vacation_ON_info_for_buyer', "At this momment, you are unable to purchase this proposal/service until the freelancer swiches vacation mode back to ")); ?>
                    <span class="badge badge-success">
                    <?php D(__('proposal_details_page_vacation_OFF', "OFF")); ?>
                    </span> </h4>
                </div>
              </div>
          	<?php

              }
              } else {
                  if ($proposal_details['proposal']->proposal_price == 0) {
              ?>
                <div class="card mb-4 mt-4 mt-lg-0">
                  <div class="card-header pt-0">
                    <ul class="nav nav-tabs card-header-tabs justify-content-center" id="myTab" role="tablist">
                    <?php
                    if ($proposal_details['proposal_packages']) {
                      foreach ($proposal_details['proposal_packages'] as $i => $package) {
                    ?> 
                      <li class="nav-item" role="presentation">
                        <a class="nav-link <?php if ($package->package_name == "Standard") {  echo "active"; } ?>" id="basic-tab" data-toggle="tab" href="#tab_<?php D($package->package_id); ?>" role="tab" aria-selected="true"><?php D(__('proposal_details_page_package_' . $package->package_name, $package->package_name)); ?></a>
                      </li>
                      <?php }
                      }?>
                    </ul>
                  </div>

          	      <div class="tab-content" id="myTabContent">
                    <?php
                    if ($proposal_details['proposal_packages']) {
                      foreach ($proposal_details['proposal_packages'] as $i => $package) {
                        $priceClass = "total-price-$i";
                    ?>
                      
                          
                          <div id="tab_<?php D($package->package_id); ?>" class="tab-pane fade <?php if ($package->package_name == "Standard") {  echo "show active"; } ?>" data-parent="#accordionExample">
                            <div class="card-body">
                              <h3><strong>
                                <?php D(CURRENCY); ?>
                                <span class="<?php D($priceClass); ?>">
                                <?php D($package->price); ?>
                                </span></strong></h3>
                              <h5><i class="icon-feather-clock"></i>
                                <?php D($package->delivery_time); ?>
                                <?php D(__('proposal_details_page_Days_Delivery', "Days Delivery")); ?>
                              </h5>

                              <?php
                            if($proposal_details['module_attributes']){
                              $all_attr_values=$proposal_details['module_attributes'];
                              $importantfeature=array();
                              $allowed=array();
                              $notallowed=array();
                              foreach($section_data as $sectionrow){
                                if(array_key_exists($sectionrow['key'],$proposal_details['module_attributes'])){
                                  if($sectionrow['key']=='no_of_concept' || $sectionrow['key']=='revisions'){
                                    $importantfeature[]=$all_attr_values[$sectionrow['key']][$i].' '.$sectionrow['name'];
                                  }else{
                                    if($all_attr_values[$sectionrow['key']][$i]=='1'){
                                      $allowed[]=$sectionrow['name'];
                                    }else{
                                      $notallowed[]=$sectionrow['name'];
                                    }
                                  }
                                }  
                              }
                           
                              if($importantfeature){
                               
                              ?>
                              <p class="mb-0"><b><i class="icon-feather-repeat font-weight-bold"></i> <?php echo $importantfeature[1];?></b></p>
                              <p class="mb-0"><i class="icon-feather-check text-success font-weight-bold"></i> <?php echo $importantfeature[0];?></p>
                              <?php
                                
                              }
                              if($allowed){
                                foreach($allowed as $f=>$featureinclude){
                              ?>
                              <p class="mb-0"><i class="icon-feather-check text-success font-weight-bold"></i> <?php echo $featureinclude;?></p>
                              <?php
                                }
                              }
                              if($notallowed){
                                foreach($notallowed as $f=>$featureinclude){
                              ?>
                              <p class="mb-0"><i class="icon-feather-check text-secondary font-weight-bold"></i> <?php echo $featureinclude;?></p>
                              <?php
                                }
                              }
                            }

                            ?>    


                              
                              <?php

                                  if ($is_login) {

                                    if ($is_owner) {  ?>
                              <a class="btn btn-site" href="<?php D(get_link('editproposalURL')) ?>/<?php D($proposal_details['proposal']->proposal_id); ?>/<?php D($token); ?>"> <i class="icon-feather-edit"></i>
                              <?php D(__('proposal_details_page_Edit_Proposal', "Edit Proposal")); ?>
                              </a>
                              <?php } else { ?>
                              <form method="post" action="" id="checkoutForm_<?php D($i + 1); ?>" onsubmit="return checkoutForm(this);return false;">
                                <input type="hidden" name="proposal_id" value="<?php D($proposal_details['proposal']->proposal_id); ?>">
                                <input type="hidden" name="package_id" value="<?php D($package->package_id); ?>">

                                <div class="row mb-3">
                                  <label class="col-md-6 col-form-label"><?php D(__('proposal_details_page_Proposal_Quantity', "Proposal\'s Quantity")); ?></label>
                                  <div class="col-md-6">
                                      <div class="qty-cart">
                                        <div class="cart-plus-minus">
                                          <input type="tel" class="form-control proposal_qty" value="1" name="proposal_qty" min="1">
                                          <div class="minus qtybutton" onclick="addtocartBtn(this,'down')"><i class="icon-line-awesome-minus"></i></div>
                                          <div class="plus qtybutton" onclick="addtocartBtn(this,'up')"><i class="icon-line-awesome-plus"></i></div>
                                        </div>
                                    </div>	            
                                  </div>
                                </div>
                                <div class="text-right"><button type="submit" class="btn btn-site saveBTN" name="add_order"><?php D(__('proposal_details_page_Order_Now', "Order Now")); ?></button></div>

                                
                                <!--<button class="btn btn-site button-lg2 btn-lg saveBTNCart" type="button" name="add_cart">

                                  <i class="fa fa-lg fa-shopping-cart"></i>

                                  </button>--> 
                                  <!-- <a class="btn btn-web mb-2" href="<?php D(get_link('messageLink')) ?>/<?php D($owner_details['member']->member_id); ?>">
                                  <?php D(__('proposal_details_page_Contact_Seller', "Contact Seller")); ?>
                                </a> -->
                                <!-- <a href="#compare" class="btn btn-outline-site mb-2">
                                <?php D(__('proposal_details_page_Compare_Packages', "Compare Packages")); ?>
                                </a> -->
                              </form>
                              <?php }
                                  } else { ?>
                              <a href="<?php D(get_link('loginURL')) ?>" class="btn btn-site">
                              <?php D(__('proposal_details_page_Order_Now', "Order Now")); ?>
                              (
                              <?php D(CURRENCY); ?>
                              <span class="total-price">
                              <?php D($package->price); ?>
                              </span>) </a>
                              <?php } ?>
                            </div>
                          </div>
                        
                    <?php
                    }
                  }
                  ?>
                </div>
              </div>
              <?php
              } else {

              ?>
          
          <div class="card mb-3">
            <div class="card-body">
              <h3><strong><?php D(CURRENCY); ?><span class="total-price"><?php D($proposal_details['proposal']->proposal_price); ?></span></strong></h3>
              <h5><i class="icon-feather-clock"></i>
                <?php D($proposal_details['proposal']->delivery_time); ?>
                <?php D(__('proposal_details_page_Days_Delivery', "Days Delivery")); ?>
              </h5>
          
          <?php

                if ($is_login) {
                  if ($is_owner) {
                ?>
                  <a class="btn btn-block btn-site" href="<?php D(get_link('editproposalURL')) ?>/<?php D($proposal_details['proposal']->proposal_id); ?>/<?php D($token); ?>"> <i class="icon-feather-edit"></i>
                  <?php D(__('proposal_details_page_Edit_Proposal', "Edit Proposal")); ?>
                  </a>
                  <?php
                 } 
                 else {
                ?>
          
                  <form method="post" action="" id="checkoutForm" onsubmit="return checkoutForm(this);return false;">
                    <input type="hidden" name="proposal_id" value="<?php D($proposal_details['proposal']->proposal_id); ?>">
                    <div class="row mb-3">
                      <label class="col-md-6 col-form-label"><?php D(__('proposal_details_page_Proposal_Quantity', "Proposal\'s Quantity")); ?></label>
                      <div class="col-md-6">
                          <div class="qty-cart">
                          <div class="cart-plus-minus">
                              <input type="tel" class="form-control proposal_qty" value="1" name="proposal_qty" min="1">
                              <div class="minus qtybutton" onclick="addtocartBtn(this,'down')"><i class="icon-line-awesome-minus"></i></div>
                              <div class="plus qtybutton" onclick="addtocartBtn(this,'up')"><i class="icon-line-awesome-plus"></i></div>
                          </div>
                        </div>	            
                      </div>
                    </div>
                    <div class="text-right"><button type="submit" class="btn btn-site saveBTN" name="add_order"><?php D(__('proposal_details_page_Order_Now', "Order Now")); ?></button></div>

                    
                    <!--<button class="btn btn-site button-lg2 btn-lg saveBTNCart" type="button" name="add_cart">

                      <i class="fa fa-lg fa-shopping-cart"></i>

                    </button>-->
                    
                  </form>   
          <?php
		            }
		        } 
            else {
		        ?>
            <a href="<?php D(get_link('loginURL')) ?>" class="btn btn-site">
              <?php D(__('proposal_details_page_Order_Now', "Order Now")); ?> (<?php D(CURRENCY); ?><span class="total-price"><?php D($proposal_details['proposal']->proposal_price); ?></span>)
            </a> 
          
          <!--<button class="btn btn-lg button-lg2 btn-site" data-toggle="modal" data-target="#login-modal">

            <i class="fa fa-lg fa-shopping-cart"></i>

            </button>-->
          
          <?php
              }
              ?>
          </div> 
        </div>    
              <?php
          }
          ?>
        
          <?php
              if (!$is_owner) {

                if ($proposal_details['proposal']->proposal_price == 0) {

                  $form = "checkoutForm_2";
                } else {

                  $form = "checkoutForm";
                }

          ?>
          
         
          <?php

              }

              /*if($proposal_details['proposal_extras']){

            ?>

            <hr>

            <ul class="buyables m-b-25 list-unstyled">

            <?php

              foreach($proposal_details['proposal_extras'] as $i=>$extra){

              ?>

              <li>

                <label>

                <input type="checkbox" name="proposal_extras[<?php echo $i; ?>]" value="<?php D($extra->id); ?>" form="<?php D($form); ?>">

                <span class="js-express-delivery-text">

                <?php D($extra->name); ?> (+<span class="price"><?php D(CURRENCY.$extra->price); ?></span>)

                </span>

                </label>

              </li>

              <?php	

              }

              ?>

            </ul> 	

              <?php

            }*/
            }

            ?>
   <?php /*?>        
<!-- STATIC DESIGN START -->    
   
<div class="card mb-4 mt-4 mt-lg-0">
<div class="card-header pt-0">
<ul class="nav nav-tabs card-header-tabs justify-content-center" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">Basic</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="standard-tab" data-toggle="tab" href="#standard" role="tab" aria-controls="standard" aria-selected="false">Standard</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="advance-tab" data-toggle="tab" href="#advance" role="tab" aria-controls="advance" aria-selected="false">Advance</a>
  </li>
</ul>
</div>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="basic">
  	<div class="card-body">
    	<h2>$99</h2>
    	<p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <div class="row mb-3">
            <label class="col-md-6 col-form-label">Gigs's Quantity</label>
            <div class="col-md-6">
                <div class="qty-cart">
                <div class="cart-plus-minus">
                    <input type="text" class="form-control" value="1">
                    <div class="minus qtybutton"><i class="icon-line-awesome-minus"></i></div>
                    <div class="plus qtybutton"><i class="icon-line-awesome-plus"></i></div>
                </div>
              </div>	            
            </div>
          </div>
        <div class="text-right"><button type="submit" class="btn btn-site">Order Now</button></div>
    </div>
  </div>
  <div class="tab-pane fade" id="standard">
  	<div class="card-body">
    	<h2>$149</h2>
    	<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        <div class="row mb-3">
            <label class="col-md-6 col-form-label">Gigs's Quantity</label>
            <div class="col-md-6">
                <div class="qty-cart">
                <div class="cart-plus-minus">
                    <input type="text" class="form-control" value="1">
                    <div class="minus qtybutton"><i class="icon-line-awesome-minus"></i></div>
                    <div class="plus qtybutton"><i class="icon-line-awesome-plus"></i></div>
                </div>
              </div>	            
            </div>
          </div>
        <div class="text-right"><button type="submit" class="btn btn-site">Order Now</button></div>
    </div>
  </div>
  <div class="tab-pane fade" id="advance">
  	<div class="card-body">
    	<h2>$199</h2>
    	<p>Utenim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        <div class="row mb-3">
            <label class="col-md-6 col-form-label">Gigs's Quantity</label>
            <div class="col-md-6">
                <div class="qty-cart">
                <div class="cart-plus-minus">
                    <input type="text" class="form-control" value="1">
                    <div class="minus qtybutton"><i class="icon-line-awesome-minus"></i></div>
                    <div class="plus qtybutton"><i class="icon-line-awesome-plus"></i></div>
                </div>
              </div>	            
            </div>
          </div>
        <div class="text-right"><button type="submit" class="btn btn-site">Order Now</button></div>
    </div>
  </div>
</div>
</div>

<div class="card mb-4">          
	<div class="card-body">
    	<h2>$99</h2>
        <h5><i class="icon-feather-clock"></i> 6 Months Delivery</h5>
        <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <button type="submit" class="btn btn-site">Order Now</button>
    </div>
</div>
<!-- STATIC DESIGN END -->
 <?php */?>
        <?php

        if ($is_login && $proposal_details['proposal_settings']->proposal_enable_referrals && !$is_owner) {

        ?>
        <div class="card mb-4">          
          <div class="card-body">
          <h3>
              <?php D(__('proposal_details_page_Referral_Link', "Referral Link")); ?>
            </h3>
            <h6>
              <?php D(__('proposal_details_page_Referral_Link_text_part_1', "If anyone buys this proposal with your unique referral link, you will get")); ?>
              <?php D($proposal_details['proposal_settings']->proposal_referral_money); ?>
              %
              <?php D(__('proposal_details_page_Referral_Link_text_part_2', "from every purchase.")); ?>
            </h6>
            <input class="form-control mb-1" disabled value="<?php D(get_link('referralShareLink') . "/" . $proposal_details['proposal']->proposal_id . '/' . $proposal_details['proposal_settings']->proposal_referral_code . '/' . $loggedUser['MID']); ?>">
          </div>
        </div>
        <?php

        }

        ?>
        <?php /*?><center class="mb-4">          
          <!-- Go to www.addthis.com/dashboard to customize your tools -->          
          <div class="addthis_inline_share_toolbox"></div>
        </center><?php */?>
        <div class="card mb-4">
          <div class="card-body"> 
          	<h3>Tags</h3>
            <div class="task-tags mt-2 test">
                  <?php
    
                  if ($proposal_details['proposal_tags']) {
    
                    foreach ($proposal_details['proposal_tags'] as $t => $tag) {
    
                  ?>
                  <span>
                  <?php D($tag->tag_name); ?>
                  </span>
                  <?php
    
                    }
                  }
    
                  ?>
             </div>
           </div>
        </div>
        <div class="card mb-4">
          <div class="card-body"> 
          	<h3>Services</h3>
            <p class="d-flex justify-content-between">
            	<span>Delivery Time:</span> <span>6 Months</span>
            </p>
            <p class="d-flex justify-content-between">
            	<span>Category:</span> <span>SEO</span>
            </p>
            <p class="d-flex justify-content-between mb-0">
            	<span>English level:</span> <span>Professional</span>
            </p>
          </div>
        </div>
        <div class="card seller-bio mb-4 rounded-0">
          <div class="card-body">
          <h3>About Seller</h3>
            <div class="text-center mb-3">
              <div class="user-profile"> <img src="<?php D(getMemberLogo($owner_details['member']->member_id)); ?>" width="100" class="rounded-circle">
                <?php

                if ($owner_details['member']->seller_level > 1) {

                  $badgeimage = $owner_details['member']->seller_level - 1;

                ?>
                <img src="<?php D(theme_url() . IMAGE) ?>level_badge_<?php D($badgeimage) ?>.png" width="55" class="seller_level_badge">
                <?php

                }

                ?>
                <?php if (is_online($owner_details['member']->member_id) == 1) { ?>
                <i class="status-icon status-2x status-online"></i>
                <?php // D(__('proposal_details_page_online',"online"));
                  ?>
                <?php } ?>
              </div>
              <?php $seller_user_name = getUserName($owner_details['member']->member_id);

              ?>
              <h4><a class="text-dark" href="<?php D(get_link('viewprofileURL')) ?><?php D($seller_user_name); ?>">
                <?php /*D(ucfirst($owner_details['member']->member_name));*/ D($seller_user_name); ?>
                </a> <div class="verified-badge" data-tippy-placement="top" title="Verified Employer"></div></h4>
              
              <p class="text-muted text-center">
                <?php D(getLevelName($owner_details['member']->seller_level)); ?>
              </p>
              
            </div>
            <p class="d-flex justify-content-between">
            	<span><i class="icon-feather-map-pin pr-1"></i> Location:</span>
            	<span><img src="<?php D(theme_url() . IMAGE); ?>flags/in.svg" alt="" class="flag" height="16" />
                <?php

                if ($owner_details['member_address'] && $owner_details['member_address']->member_country) {

                  $getname = getAllCountry(array('country_code' => $owner_details['member_address']->member_country));

                  if ($getname) {

                    D($getname->country_name);
                  }
                }

                ?>
                </span>
            </p>
            <p class="d-flex justify-content-between"><span><i class="icon-feather-message-square pr-1"></i>
              <?php D(__('proposal_details_page_Speaks', "Speaks")); ?>:</span>
               <b>
              <?php

                if ($owner_details['member_languages']) {

                  foreach ($owner_details['member_languages'] as $language) {

                    D($language->language_title) . ' ';
                  }
                }

                ?>
              </b></p>
            <p class="d-flex justify-content-between"><span><i class="icon-feather-star pr-1"></i>
              <?php D(__('proposal_details_page_Positive_Reviews', "Positive Reviews")); ?>
              :</span> <b>
              <?php D($owner_details['member']->seller_rating); ?>
              % </b></p>
            <p class="d-flex justify-content-between"><span><i class="icon-feather-shopping-cart pr-1"></i>
              <?php D(__('proposal_details_page_Recent_Delivery', "Recent Delivery")); ?>
              :</span> <b>
              <?php if ($owner_details['member']->recent_delivery_date) {
                  D(dateFormat($owner_details['member']->recent_delivery_date, 'F d, Y'));
                } else {
                  D(__('global_None', 'None'));
                } ?>
              </b></p>
            
            <div class="text-center mt-3">
              <?php

              echo $proposal_seller_about;

              ?>
              <?php /*?><a href="<?php D(get_link('viewprofileURL')) ?><?php D(getUserName($owner_details['member']->member_id)); ?>" class="btn btn-site">
              <?php D(__('proposal_details_page_Read_More', "Read More")); ?><?php */?>
              </a> &nbsp; <a class="btn btn-site" href="<?php D(get_link('messageLink')) ?>/<?php D($owner_details['member']->member_id); ?>">
              <?php D(__('proposal_details_page_Contact_Me', "Contact Me")); ?>
              </a> </div>
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-body"> 
          	<h3>Payments</h3>
            <div class="media">
              <img src="<?php D(theme_url().IMAGE);?>payment-shield.png" class="align-self-center mr-3" alt="" />
              <div class="media-body">
                <h4>
				  <?php D(__('proposal_details_page_100_Secured', "100% Secured")); ?>
                </h4> 
                <p class="mb-0"><?php D(__('proposal_details_page_100_Secured_info', "The task will be completed, or money back guaranteed.")); ?></p>
              </div>
            </div> 
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4>
              <?php D(__('proposal_details_page_How_It_Works', "How It Works")); ?>
            </h4>
          </div>
          <div class="card-body">
            <ul class="list-2 mb-3">
              <li>
                <?php D(__('proposal_details_page_user_info_row_1', "You pay the proposal/service price listed above.")); ?>
              </li>
              <li>
                <?php D(__('proposal_details_page_user_info_row_2', "Money is saved in an encryted vault until you are satified with the work delivered.")); ?>
              </li>
              <li>
                <?php D(__('proposal_details_page_user_info_row_3', "Provide ratings & feedback about your freelancer after you've accepted the delivery.")); ?>
              </li>
            </ul>
            <a href="<?php D(get_link('CMShowitwork')); ?>" class="btn btn-outline-site ml-4">
              <?php D(__('proposal_details_page_How_It_Works', "How It Works")); ?>
              </a>
          </div>
        </div>
        
        <!-- Col ends --> 
        
      </div>
    </div>
    </div>
    <h4 class="mb-3">
      <?php D(__('proposal_details_page_Other_Proposals_Offered_By', "Other Proposals/Services Offered By")); ?>
      <a href="<?php D(get_link('viewprofileURL')) ?><?php D($seller_user_name); ?>" class="text-success">
      <?php /*D(ucfirst($owner_details['member']->member_name));*/ D($seller_user_name); ?>
      </a> </h4>
    <?php

    $proposaldata = array();

    if ($other_proposal) { ?>
    <div class="row flex-wrap mb-4">
      <?php

        foreach ($other_proposal as $proposal) {

        ?>
      <div class="col-lg-3 col-md-4 col-sm-6 col-12">
        <?php
            $proposaldata['proposal'] = $proposal;

            $proposaldata['proposal']->hide_footer_action = 1;

            $templateLayout = array('view' => 'proposals/proposal-list', 'type' => 'ajax', 'buffer' => FALSE, 'theme' => '');

            load_template($templateLayout, $proposaldata);

            ?>
      </div>
      <?php

        }

        ?>
    </div>
    <?php

    } else { ?>
    <div class="alert alert-danger text-center">
      <?php D(__('proposal_details_page_no_other_proposal', "This freelancer has no other proposals/services.")); ?>
    </div>
    <?php

    }

    ?>
    <?php if ($is_login) { ?>
    <h4 class="mb-3">
      <?php D(__('proposal_details_page_recent_proposal_view', "Recently Viewed Proposals/Services")); ?>
    </h4>
    <?php

      $proposaldata = array();

      if ($recent_proposals) { ?>
    <div class="row flex-wrap -mb-3">
      <?php

          foreach ($recent_proposals as $proposal) {

          ?>
      <div class="col-lg-3 col-md-4 col-sm-6 col-12">
        <?php

              $proposaldata['proposal'] = $proposal;

              $proposaldata['proposal']->hide_footer_action = 1;

              $templateLayout = array('view' => 'proposals/proposal-list', 'type' => 'ajax', 'buffer' => FALSE, 'theme' => '');

              load_template($templateLayout, $proposaldata);

              ?>
      </div>
      <?php

          }

          ?>
    </div>
    <?php

      } else {

      ?>
    <div class="alert alert-danger text-center">
        <?php D(__('proposal_details_page_no_recent_proposal_view', "This freelancer has no other proposals/services.")); ?>
    </div>
    <?php

      }

      ?>
    <?php } ?>
  </div>
  
  <!-- Container ends --> 
  
</section>
<script>
  $(document).ready(function(){
    $('.collapse').on('shown.bs.collapse', function(){
    $(this).parent().find(".icon-feather-plus").removeClass("icon-feather-plus").addClass("icon-feather-minus");
    }).on('hidden.bs.collapse', function(){
    $(this).parent().find(".icon-feather-minus").removeClass("icon-feather-minus").addClass("icon-feather-plus");
    });
  });
</script>
<div id="report-modal" class="modal fade"> 
  <!-- report-modal modal fade Starts -->
  
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content mycustom-modal">
      <form action="" method="post" id="reportsubmitForm" name="reportsubmitForm" onsubmit="return submitReport(this);return false;">
        <div class="modal-header"> 
          <!-- modal-header Starts -->
          
          <button type="button" class="btn btn-dark pull-left" data-dismiss="modal">
          <?php D(__('global_Close', "Close")); ?>
          </button>
          <h4 class="modal-title">
            <?php D(__('modal_report_proposal_heading', "Report This Proposal")); ?>
          </h4>
          <button type="submit" name="submit_report" class="float-right btn  btn-site saveBTN">
          <?php D(__('modal_report_proposal_Submit_Report', "Submit Report")); ?>
          </button>
        </div>
        
        <!-- modal-header Ends -->
        
        <div class="modal-body"> 
          <!-- modal-body p-0 Starts -->
          
          <h6>
            <?php D(__('modal_report_proposal_text', "Report This Proposal")); ?>
          </h6>
          <div class="form-group mt-3"> 
            <!--- form-group Starts --->
            
            <select class="form-control float-right" name="reason" id="reason">
              <option value="">
              <?php D(__('modal_report_proposal_Select_Reason', "Select Reason")); ?>
              </option>
              <option>
              <?php D(__('modal_report_proposal_Reason_option_1', "Non Original Content")); ?>
              </option>
              <option>
              <?php D(__('modal_report_proposal_Reason_option_2', "Inappropriate Proposal")); ?>
              </option>
              <option>
              <?php D(__('modal_report_proposal_Reason_option_3', "Trademark Violation")); ?>
              </option>
              <option>
              <?php D(__('modal_report_proposal_Reason_option_4', "Copyrights Violation")); ?>
              </option>
            </select>
          </div>
          
          <!--- form-group Ends ---> 
          
          <br>
          <br>
          <div class="form-group mt-1 mb-3"> 
            <!--- form-group Starts --->
            
            <label>
              <?php D(__('modal_report_proposal_Additional_Information', "Additional Information")); ?>
            </label>
            <textarea name="additional_information" id="additional_information" rows="3" class="form-control"></textarea>
          </div>
          
          <!--- form-group Ends ---> 
          
        </div>
        
        <!-- modal-body p-0 Ends -->
        
      </form>
    </div>
    
    <!-- modal-content Ends --> 
    
  </div>
  
  <!-- modal-dialog Ends --> 
  
</div>

<!-- report-modal modal fade Ends --> 

<script>
  $(document).ready(function() {

    $("#sticker").sticky({
      topSpacing: 100,
      zIndex: 99
    });

  });
</script> 
<script type="text/javascript">
  var SPINNER = '<?php load_view('inc/spinner', array('size' => 30)); ?>';

  $(document).ready(function() {

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {

      var newForm = e.target.getAttribute("formid"); // newly activated tab

      var prevForm = e.relatedTarget.getAttribute("formid"); // previous active tab

      // console.log(newForm+prevForm);

      $("select[form=" + prevForm + "]").attr('form', newForm);

      $("input[form=" + prevForm + "]").attr('form', newForm);

    })

    function changePrice(name, price, checked) {

      var value = $(name).first().text();

      var num = parseInt(value);

      var calc = num + price;

      if (checked) {

        $(name).html(calc);

      } else {

        $(name).html(num - price);

      }

    }

    $(".buyables li label input").click(function(event) {

      var price = parseInt($(this).parent().find(".price").text().replace(/\D/g, ''));

      changePrice('.total-price', price, this.checked);

      changePrice('.total-price-0', price, this.checked);

      changePrice('.total-price-1', price, this.checked);

      changePrice('.total-price-2', price, this.checked);

    });

    $('#good').hide();

    $('#bad').hide();

    $('.all').click(function() {

      $("#dropdown-button").html("<?php D(__('proposal_details_page_Most_Recent', "Most Recent")); ?>");

      $(".all").attr('class', 'dropdown-item all active');

      $(".bad").attr('class', 'dropdown-item bad');

      $(".good").attr('class', 'dropdown-item good');

      $("#all").show();

      $("#good").hide();

      $("#bad").hide();

    });

    $('.good').click(function() {

      $("#dropdown-button").html("<?php D(__('proposal_details_page_Positive_Reviews', "Positive Reviews")); ?>");

      $(".all").attr('class', 'dropdown-item all');

      $(".bad").attr('class', 'dropdown-item bad');

      $(".good").attr('class', 'dropdown-item good active');

      $("#all").hide();

      $("#good").show();

      $("#bad").hide();

    });

    $('.bad').click(function() {

      $("#dropdown-button").html("<?php D(__('proposal_details_page_Negative_Reviews', "Negative Reviews")); ?>");

      $(".all").attr('class', 'dropdown-item all');

      $(".bad").attr('class', 'dropdown-item bad active');

      $(".good").attr('class', 'dropdown-item good');

      $("#all").hide();

      $("#good").hide();

      $("#bad").show();

    });

    $('.saveBTNCart').click(function() {

      var formID = $(this).closest('form').attr('id');

      var buttonsection = $('#' + formID).find('.saveBTNCart');

      var buttonval = buttonsection.html();

      buttonsection.html(SPINNER).attr('disabled', 'disabled');

      $.ajax({

        method: "POST",

        dataType: 'json',

        url: "<?php D(get_link('saveCheckoutFormCheckAJAXURL')) ?>",

        data: $('#' + formID).serialize() + '&ptype=cart',

        success: function(msg) {

          buttonsection.html(buttonval).removeAttr('disabled');

          clearErrors();

          if (msg['status'] == 'OK') {

            swal({

              type: 'success',

              text: 'Added to cart',

              padding: 40,

              timer: 3000,

              onOpen: function() {

                swal.showLoading();

              }

            }).then(function() {

              window.location.reload();

            })

          } else if (msg['status'] == 'FAIL') {



          }

        }

      })



      return false;

    })

  });

  function checkoutForm(ev) {



    var formID = $(ev).attr('id');

    var buttonsection = $('#' + formID).find('.saveBTN');

    var buttonval = buttonsection.html();

    buttonsection.html(SPINNER).attr('disabled', 'disabled');

    $.ajax({

      method: "POST",

      dataType: 'json',

      url: "<?php D(get_link('saveCheckoutFormCheckAJAXURL')) ?>",

      data: $('#' + formID).serialize(),

      success: function(msg) {

        buttonsection.html(buttonval).removeAttr('disabled');

        clearErrors();

        if (msg['status'] == 'OK') {

          window.location.href = msg['redirect'];

        } else if (msg['status'] == 'FAIL') {

          if (msg['notverified']) {

            swal({

              type: 'warining',

              text: msg['notverified'],

              /*timer: 2000,

              onOpen: function(){

               	 swal.showLoading()

              }*/

            })

          }

        }

      }

    })



    return false;

  }

  function submitReport(ev) {

    var formID = $(ev).attr('id');

    var modal = $(ev).closest('.modal');

    var buttonsection = $('#' + formID).find('.saveBTN');

    var forminput = $('#' + formID).serialize();

    var buttonval = buttonsection.html();

    buttonsection.html(SPINNER).attr('disabled', 'disabled');

    $.ajax({

      type: "POST",

      url: "<?php D(get_link('ReportProposalURLAJAX')) ?>/<?php D($proposal_details['proposal']->proposal_id) ?>",

      data: forminput,

      dataType: "json",

      cache: false,

      success: function(msg) {

        buttonsection.html(buttonval).removeAttr('disabled');

        clearErrors();

        if (msg['status'] == 'OK') {

          $(modal).modal('hide');

          var message = '<?php D(__('popup_proposal_details_repost_success', "Your request has been submitted successfully!")); ?>';

          if (msg['message']) {

            message = msg['message'];

          }

          swal({

            type: 'success',

            text: message,

            timer: 2000,

            onOpen: function() {

              swal.showLoading()

            }

          }).then(function() {

            window.location.reload();

          })

        } else if (msg['status'] == 'FAIL') {

          registerFormPostResponse(formID, msg['errors']);

        }

      }

    })

    return false;

  }
  function addtocartBtn(sec,type){
    var section=$(sec).closest('.cart-plus-minus');
    var current_qty=section.find('.proposal_qty').val();
    var new_qty=1;
    if(type=='up'){
      new_qty=parseInt(current_qty)+1;
    }else{
      if(current_qty>1){
        new_qty=parseInt(current_qty)-1;
      }
    }
    section.find('.proposal_qty').val(new_qty);
  }
</script>