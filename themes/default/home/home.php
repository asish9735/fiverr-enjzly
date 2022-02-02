<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?>
<?php 

if($loggedUser ){

$data=array('seller_name'=>$all_connected_profile->profile_name,'count_cart'=>$count_cart,'count_favorites'=>$count_favorites,'username'=>$username);	

}

if($loggedUser && $this->router->fetch_class()!='home' && $this->router->fetch_class()!='category'){

}else{

$templateLayout=array('view'=>'inc/categorie-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

load_template($templateLayout,$data);

}
?>
<div id="home-banner" class="home-banner">
	<div class="row align-items-end align-items-md-center h-100">
    <div class="col-md-6 offset-md-6 col-12">
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
          <!--<div class="home-banner-overlay"></div>-->
          <ul class="carousel-indicators">
            <?php
        
            if($slider){
        
                foreach($slider as $k=>$sliderimage){
        
                    ?>
            <li data-target="#carouselExampleFade" data-slide-to="<?php echo $k; ?>" class="<?php if($k==0){D('active');}?>"></li>
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
            <div class="carousel-item <?php if($k==0){D('active');}?>"> <img src="<?php D(URL_USERUPLOAD.'slider/')?><?php D($sliderimage->slide_image);?>" alt="<?php D($sliderimage->slide_name);?>"> </div>
            <?php
        
            }
        
        }
        
        ?>
          </div>
          <?php /*?><a class="carousel-control-prev " href="#home-banner" data-slide="prev" style="width: 6%; opacity: 1;">
        
            <i class="fa fa-arrow-circle-o-left fa-3x"></i>
        
            </a>
        
            <a class="carousel-control-next" href="#home-banner" data-slide="next" style="width: 6%; opacity: 1;">
        
            <i class="fa fa-arrow-circle-o-right fa-3x"></i>
        
            </a><?php */?>
        </div>
	</div>
    </div>
    <div class="banner-search">
    <div class="container-fluid h-100">
      <div class="row align-items-md-center h-100">
        <div class="col-md-6 col-12">                            
          <div class="banner-headline">
            <h1>
            <?php D(__('home_page_banner_heading','Find the perfect Gigs services for your business'));?>
          </h1>
          <h5 class="d-none d-md-block">
            <?php D(__('home_page_banner_info',"A better career is out there. We'll help you find it. We're your first step to becoming everything you want to be."));?>
          </h5>
          <form action="<?php D(get_link('SearchURL'))?>" method="get">
            <div class="intro-banner-search-form mb-3 mt-4"> 
              <!-- Search Field -->
              <div class="intro-search-field">
                <input type="text" class="form-control" name="input" id="intro-keywords" value="" placeholder="<?php D(__('home_page_search_gigs',"Search gigs..."));?>">
              </div>
              <!-- Button -->
              <div class="intro-search-button">
                <button type="submit" name="search" class="button btn btn-site search_button">Search</button>
              </div>
            </div>
          </form>
          <p>Popular: <a href="#">React Native,</a> <a href="#">Flutter,</a> <a href="#">Plumber,</a> <a href="#">Artist,</a> <a href="#">Singer,</a> <a href="#">Writer</a></p>      
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<section class="section bg-white job-category">
  <div class="container-fluid">
    <div class="section-headline centered">
      <h2>
        <?php D(__('home_page_market_heading',"Popular Category"));?>
      </h2>
      <p>
        <?php D(__('home_page_market_info',"50+ Catetories work wating for you"));?>
      </p>
    </div>
    
    <!--<div class="col-lg-3 col-md-4 col-6"></div>-->
    
    <div class="row">
      <?php

    if($featured_category){

      foreach($featured_category as $k=>$category){
        $count_list=$this->db->from('proposal_category as c')->join('proposals as p','c.proposal_id=p.proposal_id')->where('c.category_id',$category->category_id)->where('p.proposal_status',PROPOSAL_ACTIVE)->group_by('p.proposal_id')->get()->num_rows();
        ?>
          <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
          <a href="<?php D(get_link('CategoryURL').$category->category_key);?>" class="card">
          <div class="card-image">
          	<img src="<?php D(URL_USERUPLOAD.'category/')?><?php D($category->category_image);?>" alt="<?php D($category->name);?>" class="card-img-top">
          </div>
          <div class="card-body">
		  	<?php // echo $count_list;?>
          	<h4><?php D($category->name);?></h4>
          	<p><i><?php D($category->info);?></i></p>
          </div>
          </a>
          </div>
          <?php	
      }
    }

    ?>
    </div>
    <div class="center-block text-center"><a href="<?php D(get_link('AllCategories'));?>" class="btn btn-site xs-block">
      <?php D(__('home_page_See_All_Categories',"See All Categories"));?>
      </a> </div>
  </div>
</section>
<!-- Recent Gigs -->
<section class="section">
  <div class="container-fluid">
    <div class="section-headline">
      <h2><?php D(__('home_page_top_heading',"Featured Gigs"));?></h2>
      <p><?php D(__('home_page_top_info',"The most comprehensive search engine for gigs."));?></p>
    </div>
    <?php if($featured_proposal){?>
    <!--<div class="owl-carousel home-featured-carousel owl-theme"></div>-->
    <div class="row">
      <?php foreach($featured_proposal as $p=>$proposal){?>
        <div class="col-lg-31 col-md-4 col-sm-6 col-12">
        <?php 

          $proposaldata['proposal']=$proposal;

          $proposaldata['proposal']->hide_footer_action=1;

          $templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

          load_template($templateLayout,$proposaldata);

        ?>
        </div>
      <?php }?>
    
    <?php }else{?>

    <?php }?>
    </div>
    <div class="center-block text-center">
      <a href="<?php D(get_link('SearchURL'));?>" class="btn btn-site xs-block"><?php D(__('view_more','View All Gigs'))?></a></div>
  </div>
</section>
<!-- HOW IT WORKS -->
<section class="section bg-white how-it-works">
  <div class="container-fluid">
    <div class="section-headline centered">
      <h2>
        <?php D(__('header_how_it_works','How it works?'))?>
      </h2>
      <p>Working Process</p>
    </div>
    <div class="row">
      <?php
      if($boxes){
      foreach($boxes as $k=>$box){
      ?>
      <div class="col-lg-3 col-sm-6 col-12">
        <div class="icon-box with-line">
        	<div class="ibborder"><img src="<?php D(theme_url().IMAGE);?>border.png" alt=""></div> 
          <!-- Icon -->
          <div class="icon-box-circle">
            <div class="icon-box-circle-inner">
            
            <img src="<?php D(URL_USERUPLOAD.'box/')?><?php D($box->box_image);?>" class="mb-3" alt="<?php D($box->name);?>" height="64" width="64">
              <?php /*?><div class="icon-box-check">0<?php echo ($k+1);?></div><?php */?>
            </div>
          </div>
          <h4><?php D($box->name);?></h4>
          <p><?php D($box->description);?></p>
        </div>
      </div>
      <?php	
        }
      }
      ?>
    </div>
    <div class="center-block text-center"><a href="<?php D(VPATH);?>how-it-works" class="btn btn-site xs-block"><?php D(__('','View More'))?></a></div>
  </div>
</section>


<!-- Highest Rated Freelancers -->
<section class="section">
  <div class="container-fluid">
    <div class="section-headline">
      <h2>Hire Expert Candidate</h2>
      <p>Hire experts or be hired for any job, any time</p>
    </div>
    <div class="owl-carousel owl-theme freelancer-carousel">
      <?php if($popular_member){

		foreach($popular_member as $k=>$v){

			$username=getUserName($v->member_id);

			$url=get_link('viewprofileURL').$username;

			$country=getAllCountry(array('country_code'=>$v->member_country));	

	?>
      <article class="freelancer"> 
        <!-- Overview -->
        <div class="freelancer-overview">
          <div class="freelancer-overview-inner"> 
            <!-- Bookmark Icon --> 
            <!-- <span class="bookmark-icon"></span> -->
            <!-- Avatar -->
            <div class="freelancer-avatar">
            <div class="star-rate"><i class="icon-material-outline-star"></i> <?php printf("%.1f",$v->avg_rating);?></div>
            <div class="verified-badge"></div>
            <a href="<?php D($url);?>"><img src="<?php D(getMemberLogo($v->member_id))?>" alt="<?php D($username)?>"></a> </div>
            <!-- Name -->
            <div class="freelancer-name">
                <h4><a href="<?php D($url);?>">
                <?php /*D(ucwords($v->member_name));*/ D($username)?>
                </a> </h4>
                <p class="mb-2"><?php D($v->member_heading)?>&nbsp;</p>
            </div>
            <?php /*?><!-- Rating -->
            <div class="freelancer-rating">
               <div class="star-rating" data-rating="<?php printf("%.1f",$v->avg_rating);?>" data-showcount="true" data-digit="(25)"></div>
            </div><?php */?>
            
            <p><!--<i class="icon-feather-map-pin"></i>--><img class="flag" src="<?php D(theme_url().IMAGE);?>flags/gb.svg" alt="" title="United Kingdom" data-tippy-placement="top"> <?php if($country){D($country->country_name);}?></p>            
            <p> 
              <?php
              if($v->skills){
              ?>
              <span><?php echo $v->skills[0]->skill_title;?></span> <?php if(count($v->skills)>1){?><span>+<?php echo count($v->skills)-1?></span><?php }?>
              <?php }else{?>
                <span class="text-muted"><i>No skill</i></span>
              <?php }?>
             </p>
            <a href="<?php D($url);?>" class="btn btn-site button-sliding-icon">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a> </div>
        </div>
        
        <!-- Details -->
        <?php /*?>
        <div class="freelancer-details">
          <div class="freelancer-details-list">
            <ul>
              <li>
                <?php D(__('home_page_Job_Success',"Job Success"));?>
                <strong>
                <?php D($v->seller_rating)?>
                %</strong>
                <div class="progress" style="height:8px">
                  <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?php D($v->seller_rating)?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </li>
            </ul>
          </div>
        </div>
		<?php */?>
      </article>
      <?php		

		}

	}

	?>
    </div>
  </div>
</section>
<!-- Highest Rated Freelancers / End--> 
<?php /*?><section class="section bg-white">
  <div class="container-fluid">
	<div class="row">
    <aside class="col-md-6 col-12">
      <div class="section-headline"></div>
      <h2 class="font-weight-bold">
        <?php D(__('home_page_millions_of_gigs',"Millions of gigs. Find the one that’s right for you."));?>
      </h2>
      
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
      <ul class="list">
      	<li>Connect to freelancers with proven business experience</li>
        <li>Get matched with the perfect talent by a customer success manager</li>
        <li>Manage teamwork and boost productivity with one powerful workspace</li>
      </ul>
		<a href="<?php D(get_link('SearchURL'));?>" class="btn btn-site">Search Gigs</a>
    </aside>
    <aside class="col-md-6 col-12">
    	<img src="<?php D(theme_url().IMAGE);?>video_preview.png" alt="video preview" class="img-fluid" />
    </aside>
	</div>
</div>
</section><?php */?>
<!-- WHAT DO YOU NEED? -->
<section class="section bg-white">
  <div class="container-fluid">
    <div class="section-headline">
      <h2>
        <?php D(__('home_page_card_heading',"What do you need?"));?>
      </h2>
      <p><?php D(__('home_page_card_info',"Empower your project with a powerful workforce"));?></p>
    </div>
    <div class="owl-carousel owl-theme carousel-abc">
      <?php

      if($cards){

      foreach($cards as $k=>$cardsimage){

        ?>
      
      <a href="<?php D($cardsimage->card_link);?>" class="photo-box" data-background-image="<?php D(URL_USERUPLOAD.'card/')?><?php D($cardsimage->card_image);?>?a1">
      <div class="photo-box-content">
        <div>       
            <h5><?php D($cardsimage->card_desc);?></h5>
            <h4><?php D($cardsimage->card_title);?></h4>
        </div>
      </div>
      </a>
      <?php /*?><div class="card-box">

		<div>

			<a href="<?php D($cardsimage->card_link);?>" class="subcategory">

				<h4><small><?php D($cardsimage->card_desc);?></small><?php D($cardsimage->card_title);?></h4>

			<picture>

				<img src="<?php D(URL_USERUPLOAD.'card/')?><?php D($cardsimage->card_image);?>" alt="<?php D($cardsimage->card_title);?>">

			</picture>
			</a>
		</div>
    </div><?php */?>
        <?php
        }
      }
    ?>
    </div>
  </div>
</section>

<!-- Feedback -->
<section class="section bg-gray feedback">
  <div class="container"> 
    <!-- Section Headline -->
    <div class="section-headline centered">
      <h2><?php echo __('home_page_feedback_header','Client Testimonials');?></h2>
      <!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua enim ad minim veniam, quis nostrud exercitation ullamco.</p>--> 
    </div>
    <div class="owl-carousel owl-theme feedback-carousel">
        <div class="card">       
          <div class="card-body">          
            <div class="testimonial-desc">
              <p><img src="<?php D(theme_url().IMAGE);?>quote-left.png" alt="" class="quote-left"> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ehnim ad minim veniam, quis nostrud exercitation ullamco laboris nisi. <img src="<?php D(theme_url().IMAGE);?>quote-right.png" alt=""></p>
            </div>
            <div class="testimonal-img">
            <div class="star-rate"><i class="icon-material-outline-star"></i> <?php printf("%.1f",$v->avg_rating);?></div>
            <img src="<?php D(theme_url().IMAGE);?>client-1.jpg" alt="" height="128" width="128">
            </div>
            <h4 class="text-center">Eric Muniz</h4>
            <p class="text-muted text-center"><i>Project Manager</i></p>
          </div>
        </div>
    	<div class="card">       
          <div class="card-body">          
            <div class="testimonial-desc">
              <p><img src="<?php D(theme_url().IMAGE);?>quote-left.png" alt="" class="quote-left"> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. <img src="<?php D(theme_url().IMAGE);?>quote-right.png" alt=""></p>
            </div>
            <div class="testimonal-img">
            <div class="star-rate"><i class="icon-material-outline-star"></i> <?php printf("%.1f",$v->avg_rating);?></div>
            <img src="<?php D(theme_url().IMAGE);?>client-2.jpg" alt="" height="128" width="128">
            </div>
            <h4 class="text-center">Millena Williams</h4>
            <p class="text-muted text-center"><i>Event Manager</i></p>
          </div>
        </div>
		<div class="card">       
          <div class="card-body">          
            <div class="testimonial-desc">
              <p><img src="<?php D(theme_url().IMAGE);?>quote-left.png" alt="" class="quote-left"> Nam libero tempore, cum soluta nobis eligendige optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor autem commodi quibusdam. <img src="<?php D(theme_url().IMAGE);?>quote-right.png" alt=""></p>
            </div>
            <div class="testimonal-img">
            <div class="star-rate"><i class="icon-material-outline-star"></i> <?php printf("%.1f",$v->avg_rating);?></div>
            <img src="<?php D(theme_url().IMAGE);?>client-3.jpg" alt="" height="128" width="128">
            </div>
            <h4 class="text-center">Harry Steve</h4>
            <p class="text-muted text-center"><i>CEO and co-founder</i></p>
          </div>
        </div>
    </div>
  </div>
</section>
<!-- Feedback End -->

<script>
var direction=<?php echo ($this->session->userdata('current_lang')=='ar' ? 'true':'false');?>;
$('.freelancer-carousel').owlCarousel({
  margin: 10,
	loop: true,
	nav: true,	
	responsiveClass: true,
	rtl:direction,
	responsive: {
	  0:{
	items:1,
	margin:15,
	nav:false
	},
	575:{
	items:2,
	nav:false
	},
	768:{
		items:3
	},
	992:{
		items:4,
		nav:true
	}
	}
});

$(".carousel-abc").owlCarousel({
	margin:20,
	loop:true,
	autoplay:true,
	nav:true,
	autoplaySpeed:1000,
	responsiveClass:true,
    rtl:direction,
	responsive:{
    0:{
    items:1,
    margin:15,
    nav:false
    },
    480:{
    items:2,
    nav:false
    },
    768:{
      items:3
    },
    992:{
      items:4
    },
    1140:{
      items:5
    }
	}
});
$(".feedback-carousel").owlCarousel({
	margin:0,
	loop:true,
	autoplay:true,
	nav:true,
	autoplaySpeed:1000,
	responsiveClass:true,
    rtl:direction,
	responsive:{
    0:{
    items:1,
    nav:false
    },
    768:{
      items:2
    },
    992:{
      items:3
    },
    1140:{
      items:3
    }
	}
});
</script> 
