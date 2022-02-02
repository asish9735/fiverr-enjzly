<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($filter);
?>
<style>
.cmsPage ul, .cmsPage li {
	list-style:inside disc;
}
</style>
<div class="breadcrumbs">
  <div class="container">
  	<h1><?php D(__('cms_page_heading','About Us'));?></h1>    
	<h5><?php D(__('cms_page_sub_heading','About Us, Vision, Mission, News.'));?></h5>
  </div>
</div>
<section class="section">
<div class="container-fluid">
	<div class="terms-page">	
    	<ul class="nav nav-tabs justify-content-center" id="myTab">
            <li class="nav-item">
                <a class="nav-link <?php if($cms->content_slug=='about-us'){D('active');}?>" href="<?php D(get_link('CMSaboutus'))?>">
                    <?php D(__('cms_page_tab_about','About us'));?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($cms->content_slug=='vision'){D('active');}?>"  href="<?php D(get_link('CMSVision'))?>">
                    <?php D(__('cms_page_tab_Vision','Vision'));?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($cms->content_slug=='mission'){D('active');}?>"  href="<?php D(get_link('CMSMission'))?>">
                <?php D(__('cms_page_tab_Mission','Mission'));?>
                </a>
            </li>
            <?php
            /*$display_founder_page=get_option_value('display_founder_page');
            if($display_founder_page==1){
            ?>
           <li class="nav-item">
                <a class="nav-link"  href="<?php D(get_link('CMSfoundersURL'))?>">
                <?php D(__('cms_page_tab_Founders','Founders'));?>
                </a>
            </li>
             <?php
            }*/
            ?>
           <!--  <li class="nav-item">
                <a class="nav-link <?php if($cms->content_slug=='news'){D('active');}?>"  href="<?php D(get_link('CMSNews'))?>">
                News
                </a>
            </li>-->
        </ul>
                    
        <div class="card cmsPage border-top-0">
        	<div class="card-header"><h4><?php D($cms->title); ?></h4></div>
            <div class="card-body">
                <div class="tab-content">
                    <div  class="tab-pane fade show active">
                        
                        <p class="text-justify">
                            <?php D(html_entity_decode($cms->content)); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
</section>