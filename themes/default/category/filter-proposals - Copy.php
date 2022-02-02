<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($filter);
?>
<div class="breadcrumbs">
	<?php
	if($is_featured==1){
		$title=__('filter_proposal_featured_title',"Featured Proposals/Services");
		$info=__('filter_proposal_featured_text',"This is an extended list of our top featured proposals/services");
	}elseif($is_top==1){
		$title=__('filter_proposal_top_title',"Top Rated Proposals/Services");
		$info=__('filter_proposal_top_text',"This is an extended list of our top rated proposals/services");
	}elseif($is_random==1){
		$title=__('filter_proposal_random_title',"Random Proposals/Services");
		$info=__('filter_proposal_random_text',"This is an extended list of our random proposals/services");
	}
	?>
    <div class="container">
        <h1> <?php D($title); ?> </h1>
        <p><?php D($info); ?></p>
    </div>
</div>        
<section class="section">
<div class="container-fluid">
	<div class="row mt-3">
		<div class="col-lg-3 col-md-4 col-sm-12">
		<form action="" id="filter_form" name="filter_form" onsubmit="return post_form();">
		<input type="hidden" name="is_search" id="is_search" value="<?php D($filter['is_search']);?>"/>
		<?php if($filter && $filter['is_search']==1){?>
		<input type="hidden" name="input" id="input" value="<?php D($filter['input']);?>"/>
		<?php }?>
		<input type="hidden" name="is_featured" id="is_featured" value="<?php D($is_featured);?>"/>
		<input type="hidden" name="is_top" id="is_top" value="<?php D($is_top);?>"/>
		<input type="hidden" name="is_random" id="is_random" value="<?php D($is_random);?>"/>
		
			<div class="card border-success mb-3">
				<div class="card-header bg-success">
					<h3 class="h5 text-white"><?php D(__('filter_proposal_Categories',"Categories"));?></h3>
				</div>
				<div class="card-body">
					<ul class="nav flex-column" id="proposal_category">
					<?php
					if($all_category){
						foreach($all_category as $c=>$category){
					?>
					<li class="nav-item checkbox checkbox-success">
						<label>
							<input type="checkbox" name="category_ids[]" value="<?php D($category->category_id)?>" class="categoryids">
							<span><?php D($category->name); ?></span>
						</label>
					</li>
					
					<?php
						}
					}
					?>
					</ul>
				</div>
			</div>
			<div class="card border-success mb-3">
				<div class="card-body pb-2 pt-2">
					<ul class="nav flex-column">
						<li class="nav-item checkbox checkbox-success">
							<label class="pt-2">
								<input type="checkbox" name="is_online_user" value="1" class="get_online_sellers">
								<span><?php D(__('filter_proposal_show_online_user',"Show Online Freelancers"));?></span>
							</label>
						</li>
					</ul>
				</div>
			</div>
			<div class="card border-success mb-3">
				<div class="card-header bg-success">
					<h3 class="float-left text-white h5"><?php D(__('filter_proposal_Delivery_Time',"Delivery Time"));?></h3>
					<button class="btn btn-secondary btn-sm float-right clear_delivery_time clearlink" onclick="clearDelivery()">
						<i class="fa fa-times-circle"></i> <?php D(__('filter_proposal_Clear Filter',"Clear Filter"));?>
					</button>
				</div>
				<div class="card-body">
					<ul class="nav flex-column">
				<?php
				if($all_delivery_times){
					foreach($all_delivery_times as $t=>$delivery_time){
				?>
						<li class="nav-item checkbox checkbox-success">
							<label>
							<input type="checkbox" name="delivery_id[]" value="<?php D($delivery_time->delivery_id);?>" class="get_delivery_time">
			                <span> <?php D($delivery_time->delivery_title);?> </span>
							</label>
						</li>
				<?php
					}
				}
				?>
					</ul>
				</div>
			</div>
			<div class="card border-success mb-3">
				<div class="card-header bg-success">
					<h3 class="float-left text-white h5"><?php D(__('filter_proposal_Freelancer_Level',"Freelancer Level"));?></h3>
					<button class="btn btn-secondary btn-sm float-right clear_seller_level clearlink" onclick="clearLevel()">
						<i class="fa fa-times-circle"></i> <?php D(__('filter_proposal_Clear Filter',"Clear Filter"));?>
					</button>
				</div>
				<div class="card-body">
					<ul class="nav flex-column">
					<?php
					if($all_level){
						foreach($all_level as $l=>$level){
					?>
						<li class="nav-item checkbox checkbox-primary">
				            <label>
				            <input type="checkbox" name="level_id[]" value="<?php D($l); ?>" class="get_seller_level">
				            <span> <?php D($level['name']); ?> </span>
				            </label>
			            </li>
					<?php
						}
					}
					?>
					</ul>
				</div>
			</div>
			<div class="card border-success mb-3">
				<div class="card-header bg-success">
					<h2 class="float-left text-white h5"><?php D(__('filter_proposal_Freelancer_Language',"Freelancer Language"));?></h2>
					<button class="btn btn-secondary btn-sm float-right clear_seller_language clearlink" onclick="clearLanguage()">
						<i class="fa fa-times-circle"></i> <?php D(__('filter_proposal_Clear Filter',"Clear Filter"));?>
					</button>
				</div>
				<div class="card-body">
					<ul class="nav flex-column">
					<?php
					if($all_language){
						foreach($all_language as $l=>$language){
					?>
					<li class="nav-item checkbox checkbox-primary">
			            <label>
			            <input type="checkbox" name="language_id[]" value="<?php D($language->language_id); ?>" class="get_seller_language">
			            <span> <?php D($language->language_title); ?> </span>
			            </label>
			        </li>
					<?php
						}
					}
					?>
					</ul>
				</div>
			</div>
		</form>
		</div>
		<div class="col-lg-9 col-md-8 col-sm-12">
            <div class="row flex-wrap_" id="ajax_table">
            </div>
            <div class="text-center" id="loader" style="display: none">
            	<?php load_view('inc/spinner',array('size'=>30));?>
            </div>
			 <br>
			<div class="container text-center padding-bottom-40">
				<button class="btn btn-primary" id="load_more" data-page = "0"><?php D(__('filter_proposal_load_more',"Load more.."));?></button>
			</div>
		</div>
	</div>
</div><!-- Container ends -->
</section>

<div class="append-modal"></div>
<script>
	$(document).ready(function(){
	
	$("#load_more").click(function(e){
		e.preventDefault();
		var page = $(this).data('page');
		getprojects(page);
	});
	getprojects(1);
});
var getprojects = function(page){
	var filter_data=$('#filter_form').serializeArray();
	var uniquekey = {
	      name: "page",
	      value: page
	};
	filter_data.push(uniquekey);
	$("#loader").show();
	$.ajax({
		url:"<?php D(get_link('ProposalListURLAJAX'))?>",
		type:'post',
		dataType:'json',
		data: filter_data,
	}).done(function(response){
		var newpage= parseInt(page)+1;
		if(response){
			$("#ajax_table").append(response.list);
			$("#loader").hide();
			$('#load_more').data('page', newpage);
			$('[data-toggle="tooltip"]').tooltip();
			if(response.total_page>=newpage){
				$('#load_more').show();
			}else{
				$('#load_more').hide();
			}
			//scroll();
		}else{
			$("#loader").hide();
			$('#load_more').hide();
			if(page==1){
				if($('#is_search').val()==1){
					$("#ajax_table").append('<h1 class="text-center mt-4"> <?php D(__('filter_proposal_search_no_result',"We haven\'t found any proposals/services matching that search."));?> </h1>');
				}else{
					$("#ajax_table").append('<h1 class="text-center mt-4"> <?php D(__('filter_proposal_category_no_result',"No proposals/services to Show in this Category Yet."));?> </h1>');
				}
				
			}
		}

	});
};
var scroll  = function(){
/*$('html, body').animate({
scrollTop: $('#load_more').offset().top
}, 1000);*/
};
$("#filter_form").on('change','.get_online_sellers, .get_delivery_time, .get_seller_level, .get_seller_language,.categoryids',function(){
 	$("#ajax_table").empty();
 	getprojects(1);
 })

</script>