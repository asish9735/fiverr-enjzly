<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($filter);
?>
<style>
.cmsPage ul, .cmsPage li {
	list-style:inside disc;
	padding-left:20px
}
</style>
<div class="breadcrumbs">
  <div class="container">
  	<h1><?php D(__('policy_page_heading','Our Policies'));?></h1>    
	<h5><?php D(__('policy_page_sub_heading','Terms & Conditions, Refund Policy, Pricing & Promotion Policy.'));?></h5>
  </div>
</div>
<section class="section">
<div class="container-fluid">
	<div class="terms-page">
		
        <ul class="nav nav-tabs justify-content-center" id="myTab">
						<li class="nav-item">
							<a class="nav-link <?php if($cms->content_slug=='terms-and-conditions'){D('active');}?>" href="<?php D(get_link('CMStermsandconditions'))?>">
							 	<?php D(__('policy_page_tab_terms','Terms And Conditions'));?>
							</a>
						</li>
                      	<li class="nav-item">
                            <a class="nav-link <?php if($cms->content_slug=='refund-policy'){D('active');}?>"  href="<?php D(get_link('CMSrefundpolicy'))?>">
                            	<?php D(__('policy_page_tab_refund','Refund Policy'));?>
                            </a>
                        </li>
						<li class="nav-item">
                            <a class="nav-link <?php if($cms->content_slug=='pricing-and-promotions-policy'){D('active');}?>"  href="<?php D(get_link('CMSpricingandpromotionspolicy'))?>">
                            <?php D(__('policy_page_tab_price','Pricing And Promotions Policy'));?>
                            </a>
                        </li>
					</ul>
                    
        <div class="card border-top-0">
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