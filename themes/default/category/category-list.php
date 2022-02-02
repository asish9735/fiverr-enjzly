<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($all_category,TRUE);
?>

<div class="container mt-5 pb-4"><!-- container mt-5 Starts -->
	<h1 class="text-center mb-4"> <?php D(get_option_value('website_name')); ?> <?php D(__('category_page_mobile_heading',"Categories"));?> </h1>
	<div class="row flex-wrap_"><!-- row flex-wrap Starts -->
	<?php
if($all_category){
	foreach($all_category as $k=>$category){
		?>
		<div class="col-lg-3 col-md-4 col-sm-6">
			<div class="mobile-category">
				<a href="<?php D(get_link('CategoryURL').$category->category_key);?>" title="<?php D($category->name);?>">
					<div class="ml-2 mt-3 category-picture">
						<img src="<?php D(URL_USERUPLOAD.'category/')?><?php D($category->category_image);?>" alt="<?php D($category->name);?>">
					</div>
					<div class="category-text">
						<p class="category-title">
							<strong> <?php D($category->name);?> </strong>
						</p>
						<p class="mb-4 category-desc">
							<?php D(substr($category->info,0,60));?>
						</p>
					</div>
				</a>
			</div>
		</div>
	<?php	
	}
	}
?>	
	</div>
</div>
