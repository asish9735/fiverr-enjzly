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
	<div class="row">
		<div class="col-lg-3 col-12 filter-container">
        <div class="filter-header d-lg-none"><h3><i class="icon-feather-x"></i> Filters</h3></div>
		<form action="" id="filter_form" name="filter_form" onsubmit="return post_form();">
		<input type="hidden" name="is_search" id="is_search" value="<?php D($filter['is_search']);?>"/>
		<?php if($filter && $filter['is_search']==1){?>
		<input type="hidden" name="input" id="input" value="<?php D($filter['input']);?>"/>
		<?php }?>
		<input type="hidden" name="is_featured" id="is_featured" value="<?php D($is_featured);?>"/>
		<input type="hidden" name="is_top" id="is_top" value="<?php D($is_top);?>"/>
		<input type="hidden" name="is_random" id="is_random" value="<?php D($is_random);?>"/>
		
			<div class="card mb-4">
				<div class="card-header">
					<h5><?php D(__('filter_proposal_Categories',"Categories"));?></h5>
				</div>
				<div class="card-body">
					<div id="proposal_category">
					<?php
					if($all_category){
						foreach($all_category as $c=>$category){
					?>
                    <div class="custom-control custom-checkbox mt-2 mb-2">
                      <input type="checkbox" class="custom-control-input categoryids" name="category_ids[]" id="check_<?php D($category->category_id)?>" value="<?php D($category->category_id)?>">
                      <label class="custom-control-label" for="check_<?php D($category->category_id)?>"><?php D($category->name); ?></label>
                    </div>					
					
					<?php
						}
					}
					?>
					</ul>
				</div>
			</div>
            </div>
			<div class="card mb-4">
				<div class="card-body">
                	<div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input get_online_sellers" name="is_online_user" id="online_user" value="1">
                      <label class="custom-control-label" for="online_user"><?php D(__('filter_proposal_show_online_user',"Show Online Freelancers"));?></label>
                    </div>					
				</div>
			</div>
			<div class="card mb-4">
				<div class="card-header">
					<h5><?php D(__('filter_proposal_Delivery_Time',"Delivery Time"));?></h5>
					<button class="btn btn-secondary btn-sm float-right clear_delivery_time d-none" onclick="clearDelivery()">
						<i class="fa fa-times-circle"></i> <?php D(__('filter_proposal_Clear Filter',"Clear Filter"));?>
					</button>
				</div>
				<div class="card-body">
					
				<?php
				if($all_delivery_times){
					foreach($all_delivery_times as $t=>$delivery_time){
				?>
                	<div class="custom-control custom-checkbox mt-2 mb-2">
                      <input type="checkbox" class="custom-control-input get_delivery_time" name="delivery_id[]" id="check_<?php D($delivery_time->delivery_id);?>" value="<?php D($delivery_time->delivery_id);?>">
                      <label class="custom-control-label" for="check_<?php D($delivery_time->delivery_id);?>"><?php D($delivery_time->delivery_title);?></label>
                    </div>
                                            
				<?php
					}
				}
				?>
					
				</div>
			</div>
			<div class="card mb-4">
				<div class="card-header">
					<h5><?php D(__('filter_proposal_Freelancer_Level',"Freelancer Level"));?></h5>
					<button class="btn btn-secondary btn-sm float-right clear_seller_level d-none" onclick="clearLevel()">
						<i class="fa fa-times-circle"></i> <?php D(__('filter_proposal_Clear Filter',"Clear Filter"));?>
					</button>
				</div>
				<div class="card-body">
					
					<?php
					if($all_level){
						foreach($all_level as $l=>$level){
					?>
                    <div class="custom-control custom-checkbox mt-2 mb-2">
                      <input type="checkbox" class="custom-control-input get_seller_level" name="level_id[]" id="check_<?php D($l); ?>" value="<?php D($l); ?>">
                      <label class="custom-control-label" for="check_<?php D($l); ?>"><?php D($level['name']); ?></label>
                    </div>						
					<?php
						}
					}
					?>
					
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5><?php D(__('filter_proposal_Freelancer_Language',"Freelancer Language"));?></h5>
					<button class="btn btn-secondary btn-sm float-right clear_seller_language d-none" onclick="clearLanguage()">
						<i class="fa fa-times-circle"></i> <?php D(__('filter_proposal_Clear Filter',"Clear Filter"));?>
					</button>
				</div>
				<div class="card-body">
					
					<?php
					if($all_language){
						foreach($all_language as $l=>$language){
					?>
                    <div class="custom-control custom-checkbox mt-2 mb-2">
                      <input type="checkbox" class="custom-control-input get_seller_language" name="language_id[]" id="check_<?php D($language->language_id); ?>" value="<?php D($language->language_id); ?>">
                      <label class="custom-control-label" for="check_<?php D($language->language_id); ?>"><?php D($language->language_title); ?></label>
                    </div>					
					<?php
						}
					}
					?>
					
				</div>
			</div>
		</form>
		</div>
		<div class="col-lg-9 col-12">
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