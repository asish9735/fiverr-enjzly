<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($all_category,TRUE);
?>
<div class="breadcrumbs">
    <div class="container">
        <h1>All Categories</h1>
    </div>
</div>
<section class="section">
<div class="container-fluid market" hidden><!-- container mt-5 Starts -->
	<div class="section-headline">
		<h2>All <?php D(__('category_page_all_heading',"Categories"));?> </h2>
        <h5>Get inspired to build your business</h5>
    </div>
	<div class="row">
	<?php
if($all_category){
	foreach($all_category as $k=>$category){
		if($category->category_image && file_exists(ABS_USERUPLOAD_PATH.'category/'.$category->category_image)){
			$image=URL_USERUPLOAD.'category/'.$category->category_image;
		}else{
			$image=theme_url().IMAGE.'default/no-image.png';
		}
		?>
		<div class="col-md-3 col-6">
				<a href="<?php D(get_link('CategoryURL').$category->category_key);?>" class="category-box" title="<?php D($category->name);?>"> 
					<img src="<?php D($image);?>" alt="<?php D($category->name);?>"> 
				
          		<h5><?php D(ucfirst($category->name));?></h5>
                </a>
		</div>
	<?php	
	}
	}
?>	
	</div>
</div>
<div class="container-fluid skills_child">

    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical" hidden>
    <span class="nav-link text-dark">
    	<h4>All Categories</h4>
    </span>
    <?php
	if($all_category){
	foreach($all_category as $k=>$category){
		?>
		<a href="<?php D(get_link('CategoryURL').$category->category_key);?>" class="nav-link" title="<?php D($category->name);?>">  
	          <h5><?php D(ucfirst($category->name));?></h5>
	        </a>
	<?php
	}
	}
	?>

  </div>

    <?php
	if($all_category){
	foreach($all_category as $k=>$category){
		if($category->category_image && file_exists(ABS_USERUPLOAD_PATH.'category/'.$category->category_image)){
			$image=URL_USERUPLOAD.'category/'.$category->category_image;
		}else{
			$image=theme_url().IMAGE.'default/no-image.png';
		}
		?>
		<div class="card mb-4"> 
        <div class="card-header"> 
	        <a href="<?php D(get_link('CategoryURL').$category->category_key);?>" class="heading text-dark d-flex" title="<?php D($category->name);?>">  
	          <img src="<?php D($image);?>" alt="<?php D($category->name);?>" width="32" class=" mr-3">
              <h4> <?php D(ucfirst($category->name));?></h4>
	        </a>
        </div>
        
        <div class="card-body child_row">
        <?php if($all_category){?>
        	<ul class="list-2 category-list">
        	<?php foreach($category->subcategory as $ks=>$subcategory){
        		$class="";
        		if($ks>=20){
					$class="display_none";
				}
        		?>
        		<li class="<?php D($class);?>">
                	<a href="<?php D(get_link('CategoryURL').$category->category_key.'/'.$subcategory->category_subchild_key);?>">
                		<?php D(ucfirst($subcategory->name));?>
                	</a>                    
                </li>
             <?php }?>
        	</ul>
        	<?php if(count($category->subcategory)>20){?>
        	<div class="text-center clearfix">
        		<a href="javascript:void(0)" class="btn btn-border show_more" style="display: inline-block;"><?php D(__('category_page_all_See_more',"See more"));?></a>
        		<a href="javascript:void(0)" class="btn btn-border show_less" style="display: none;"><?php D(__('category_page_all_See_less',"See less"));?></a>
        	</div>
        	<?php }?>
        <?php }?>
        </div>
      
      </div>
      <?php /*?><div class="text-center" style="margin-bottom: 30px">
      	<a href="javascript:void(0)" class="scrolltotop"><i class="fa fa-angle-up fa-3x"></i></a>
    </div><?php */?>
	
      

	<?php
	}
	}
	?>


    
</div>	
</section>
<style>
.display_none {
    display: none;
}
</style>
<script>
$(document).ready(function(){
	 
});
</script>
<script>
$("a.scrolltotop").click(function() {
  $("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
});
$('.show_more').click(function() {
	$(this).hide();
	$(this).closest('.child_row').find('.display_none').show();
	$(this).closest('.child_row').find('.show_less').show();
})
$('.show_less').click(function() {
	$(this).hide();
	$(this).closest('.child_row').find('.display_none').hide();
	$(this).closest('.child_row').find('.show_more').show();
})
</script>