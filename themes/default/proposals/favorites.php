<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$s_currency=CURRENCY;
//dd($all_orders,TRUE);
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
            <div class="left-side" id="favorites">
                <div class="header-image freelancer-avatar"><img src="<?php D(getMemberLogo($loggedUser['MID'])) ?>" alt=""></div>
                <div class="header-details">
                	<h1></h1>
    
                    <h3><?php D(__('Favorites_page_heading',"Favorites"));?>
                    <span class="text-secondary"><?php D(__('Favorites_page_Collected_By',"Collected By"));?>: <?php D($member_details['member']->member_name) ?></span> </h3>
                    <h4><?php D(count($all_favorites)); ?> <?php D(__('Favorites_page_count_text',"proposals/services in favorite"));?></h4>
                    <h5><?php D(__('Favorites_page_info',"This page contains all your favorite proposals/services. You can either remove a proposal/service from this page by clicking on the heart icon, or add all your favorite items to your cart. Cheers!"));?></h5>
                    <!--<p><a href="favorites?add_favorites" class="btn btn-lg btn-success">
							<i class="fa fa-shopping-cart"></i> Add Favorites To Cart							
						</a></p>-->
                        <!-- Go to www.addthis.com/dashboard to customize your tools -->
                   <div class="addthis_inline_share_toolbox_d0jy"></div>
                </div>
            </div>
        </div>		
	</div>
</div>

<section class="section">
	<div class="container">
    <div class="row">       
    <?php
    foreach($all_favorites as $proposal){
    ?>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3">
    <?php
            $proposaldata['proposal']=$proposal;
            $templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
            load_template($templateLayout,$proposaldata);
            ?>	
    
    </div>
    <?php } ?>
    </div>
<?php 
    if(!$all_favorites){
?>
<span class='text-center'><h3 class='pt-5 pb-5'><i class='fa fa-meh-o'></i> <?php D(__('Favorites_page_no_record',"Your favorites page is empty"));?></h3></span>
<?php 
}
?>
</div>
</section>
<div class="append-modal"></div>