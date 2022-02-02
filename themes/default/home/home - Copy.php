<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="home-banner" class="carousel slide home-banner" data-ride="carousel">
	<div class="home-banner-overlay"></div>
	<ul class="carousel-indicators">
	<?php
	if($slider){
		foreach($slider as $k=>$sliderimage){
			?>
	 <li data-target="#home-banner" data-slide-to="<?php echo $k; ?>" class="<?php if($k==0){D('active');}?>"></li>		
			<?php
		}
	}
	?>
	</ul>
	<div class="carousel-inner">
	<div class="carousel-caption">		
		<div class="row">
			<div class="offset-md-2 col-md-8 col-12">
            	<h1><?php D(__('home_page_banner_heading','Find The Perfect Freelance Services For Your Business'));?></h1>
				<?php /*?><h5><?php D(__('home_page_banner_info',"Freelance services. On demand."));?></h5><?php */?>
				<form action="<?php D(get_link('SearchURL'))?>" method="get">
					<div class="input-group input-group-lg mt-3">
						<input type="text" name="input" class="form-control" value="" placeholder="<?php D(__('home_page_search_input',"Find Services"));?>">
						<div class="input-group-append">
							<button name="search" type="submit" class="btn btn-site search_button">
								<img src="<?php D(theme_url().IMAGE);?>srch2.png" class="srch2" alt="search">
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
if($slider){
	foreach($slider as $k=>$sliderimage){
		?>
	<div class="carousel-item <?php if($k==0){D('active');}?>">
		<img src="<?php D(URL_USERUPLOAD.'slider/')?><?php D($sliderimage->slide_image);?>" alt="<?php D($sliderimage->slide_name);?>">
	</div>
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

<section class="market">
	<div class="container-fluid">
    <div class="section-headline">           
        <h2><?php D(__('home_page_market_heading',"Explore the Marketplace"));?></h2>
        <h5><?php D(__('home_page_market_info',"Get inspired to build your business"));?></h5>
    </div>
    <div class="row">
<?php
if($featured_category){
	foreach($featured_category as $k=>$category){
		?>
    <div class="col-md-3 col-6">
        <a href="<?php D(get_link('CategoryURL').$category->category_key);?>" class="category-box">
            <img src="<?php D(URL_USERUPLOAD.'category/')?><?php D($category->category_image);?>" alt="<?php D($category->name);?>">
            <h5><?php D($category->name);?></h5>
        </a>
    </div>
<?php
	if($k==3){
	?>
	</div>
        
	<div class="row">
	<?php	
	}
	}
}
?>
 </div>
 <div class="space20"></div>
 <div class="center-block text-center"><a href="<?php D(get_link('AllCategories'));?>" class="btn btn-border xs-block"><?php D(__('home_page_See_All_Categories',"See All Categories"));?></a> </div>
			
		
	</div>
</section>

<section class="section">
<div class="container-fluid">
	<div class="section-headline mb-5">
        <h3><?php D(__('home_page_card_heading',"What do you need?"));?></h3>
        <h5><?php D(__('home_page_card_info',"Empower your project with a powerful workforce"));?></h5>
    </div>
    <div class="owl-carousel home-cards-carousel owl-theme">
<?php
if($cards){
	foreach($cards as $k=>$cardsimage){
		?>
	<div class="card-box">
		<div>
			<a href="<?php D($cardsimage->card_link);?>" class="subcategory">
				<h4><small><?php D($cardsimage->card_desc);?></small><?php D($cardsimage->card_title);?></h4>
			<picture>
				<img src="<?php D(URL_USERUPLOAD.'card/')?><?php D($cardsimage->card_image);?>" alt="<?php D($cardsimage->card_title);?>">
			</picture>
			</a>
		</div>
	</div>
<?php
	}
}
?>		
	</div>
</div>
</section>

<section class="section d-none">
<div class="container-fluid">
	<div class="section-headline mb-5 d-none">
        <h3></h3>
        <h5></h5>
    </div>
	<div class="row">
	<?php if($popular_member){
		foreach($popular_member as $k=>$v){
			$username=getUserName($v->member_id);
			$url=get_link('viewprofileURL').$username;
			$country=getAllCountry(array('country_code'=>$v->member_country));	
	?>
	<article class="col-md-3 col-sm-6 col-12">
        <div class="team">
          <div class="hexagon">
          <a href="<?php D($url);?>"><img src="<?php D(getMemberLogo($v->member_id))?>" alt="<?php D($username)?>"></a> </div>
          <div class="uDetails">
            <h5><a href="<?php D($url);?>"><?php /*D(ucwords($v->member_name));*/ D($username)?></a></h5>
            <p class="slogan"><?php D($v->member_heading)?></p>
            <div class="row text-center">
              <!--<div class="col-6">Hourly Rate<br>
                <span class=""><b>AED 20.00/hr</b></span></div>-->
              <div class="col-12">
                <i class="fa fa-map-marker m-1"></i> <span class="f20"> <b><?php if($country){D($country->country_name);}?></b></span>
              </div>
            </div>
			<p><?php D(__('home_page_Job_Success',"Job Success"));?> <span class="pull-right"><b><?php D($v->seller_rating)?>%</b></span></p>
            <div class="progress">
              <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: <?php D($v->seller_rating)?>%">  </div>
            </div>
          </div>
        </div>
    </article>
	<?php		
		}
	}
	?>

    </div>
</div>
</section>


<section class="section_">
    <div class="container-fluid">
    	<div class="section-headline mb-5">
            <h3>Get Work Done Faster On Fiverr, With Confidence</h3>
            <h5>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet</h5>
        </div>
        <div class="row">
<?php
if($boxes){
	foreach($boxes as $k=>$box){
		?>
            <div class="col-md-4 pad0">
                <div class="box">
                    <h5><?php D($box->name);?></h5>
                    <p><?php D($box->description);?></p>
                </div>
            </div>
            <div class="col-md-4 pad0">
                <div class="blu_box">
                    <img src="<?php D(URL_USERUPLOAD.'box/')?><?php D($box->box_image);?>" class="img-fluid mx-auto d-block" alt="<?php D($box->name);?>">
                </div>
            </div>
<?php	
	}
}
?>
		</div>
	</div>
</section>

<section class="section">
    <div class="container-fluid">
    <div class="section-headline">
        <h3><?php D(__('home_page_top_heading',"Top Featured Proposals/Services"));?></h3>
        <h5><?php D(__('home_page_top_info',"Practical advice for every stage of doing"));?></h5>
    </div>
    <?php if($featured_proposal){?>
     <div class="owl-carousel home-featured-carousel owl-theme mt-5">
     <?php foreach($featured_proposal as $p=>$proposal){?>
            <?php 
                $proposaldata['proposal']=$proposal;
                $proposaldata['proposal']->hide_footer_action=1;
                $templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
                load_template($templateLayout,$proposaldata);
             ?>
        <?php }?>
     </div>
     <?php }else{?>
     
     <?php }?>     
    </div>
</section>