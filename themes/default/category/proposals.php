<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($filter);
?>
<div class="breadcrumbs">
	<?php
    if($filter && $filter['category_subchild_id']){
        $title=$filter['sub_category_details']->name;
        $info=$filter['sub_category_details']->description;
    }elseif($filter && $filter['category_id']){
        $title=$filter['category_details']->name;
        $info=$filter['category_details']->info;
    }elseif($filter && $filter['is_search']){
        $title=__('proposal_search_title',"Search Results");
        $info='"'.$filter['input'].'"';
    }
    ?>
    <div class="container-fluid">
        <h1><?php D($title); ?></h1>
        <!--<p><?php // D($info); ?></p>-->
    </div>
</div>        
<section class="section pt-3">    
<div class="container-fluid"> <!-- Container start -->
	
 	<a href="javascript:void(0)" class="btn btn-sm btn-site float-right d-lg-none" id="filterLeft"><i class="icon-feather-filter"></i> Filter</a>
    <h4 class="mb-3"><?php D(__('proposal_Categories',"Categories"));?></h4>
    
	<div class="row">
		<div class="col-lg-3 col-12 filter-container">
        <div class="filter-header d-lg-none"><h3><i class="icon-feather-x"></i> Filters</h3></div>
		<form action="" id="filter_form" name="filter_form" onsubmit="return post_form();">
		<input type="hidden" name="is_search" id="is_search" value="<?php D($filter['is_search']);?>"/>
		<?php if($filter && $filter['is_search']==1){?>
		<input type="hidden" name="input" id="input" value="<?php D($filter['input']);?>"/>
		<?php }?>
		<input type="hidden" name="category_id" id="category_id" value="<?php D($filter['category_id']);?>"/>
		<?php if($filter && $filter['category_subchild_id']){?>
		<input type="hidden" name="category_subchild_id" id="category_subchild_id" value="<?php D($filter['category_subchild_id']);?>"/>
		<?php }?>
        <!-- Accordion -->
                <div class="accordion js-accordion mb-4" id="proposal_category_old">
                <?php /*?><h4 class="list-group-header"><?php D(__('proposal_Categories',"Categories"));?></h4><?php */?>
                <?php
                if($all_category){
                    foreach($all_category as $c=>$category){
                ?>
               
                <!-- Accordion Item -->
				<div class="accordion__item js-accordion-item <?php if($filter && $filter['category_id']==$category->category_id){D('active');}?>">
					<div class="accordion-header js-accordion-header"><?php D($category->name); ?></div> 

					<!-- Accordtion Body -->
					<div class="accordion-body js-accordion-body">

						<!-- Accordion Content -->
						<div class="accordion-body__contents p-0">
                        <div class="sub-cat" data-simplebar>
							<?php
                    if($category->subcategory){
                        foreach($category->subcategory as $sc=>$subcategory){
                    ?>
                        
                            <a class="list-group-item list-group-item-action border-0 <?php if($filter && $filter['category_subchild_id']==$subcategory->category_subchild_id){D('active');}?>" href="<?php D(get_link('CategoryURL').$category->category_key.'/'.$subcategory->category_subchild_key);?>">
                                <i class="icon-line-awesome-hand-o-right"></i> <?php D($subcategory->name); ?>
                            </a>
                        
                    <?php
                        }
                    }
                    ?>
                    </div>
					</div>
					</div>
					<!-- Accordion Body / End -->
				</div>
				<!-- Accordion Item / End -->
                    
                    
                    
                    <?php /*?>
					<a href="<?php D(get_link('CategoryURL').$category->category_key);?>" class="list-group-item list-group-item-action <?php if($filter && $filter['category_id']==$category->category_id){D('active');}?>" data-toggle="collapse" data-target="#cat_<?php D($category->category_id); ?>"><?php D($category->name); ?> <i class="icon-feather-plus float-right"></i></a>
					<div id="cat_<?php D($category->category_id); ?>" class="list-group sub-cat collapse">
                    
                    </div><?php */?>
               
                
                <?php
                    }
                }
                ?>
                </div>
			
			<!-- Accordion / End -->
            
			<div class="card mb-4">
				<div class="card-body">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input get_online_sellers" name="is_online_user" value="1" id="customCheck1">
                  <label class="custom-control-label" for="customCheck1"><?php D(__('proposal_show_online_user',"Show Online Freelancers"));?></label>
                </div>
					
				</div>
			</div>			                
                
                
                <div class="card mb-4">
                <div class="card-header"><h5><?php D(__('proposal_Delivery_Time',"Delivery Time"));?></h5>
                <button class="btn btn-secondary btn-sm float-right clear_delivery_time d-none" onclick="clearDelivery()">
                    <i class="fa fa-times-circle"></i> <?php D(__('proposal_Clear Filter',"Clear Filter"));?>
                </button>
                </div>
                <div class="card-body">
                <?php
                if($all_delivery_times){
                    foreach($all_delivery_times as $t=>$delivery_time){
                ?>
                        
                            <div class="custom-control custom-checkbox mt-2 mb-2">
                            <input type="checkbox" class="custom-control-input get_delivery_time" name="delivery_id[]" value="<?php D($delivery_time->delivery_id);?>" id="time_<?php D($delivery_time->delivery_id);?>">
                            <label for="time_<?php D($delivery_time->delivery_id);?>" class="custom-control-label"><?php D($delivery_time->delivery_title);?> </label>
                            </div>
                        
                <?php
                    }
                }
                ?>
                </div>
                </div>
				
			
			<div class="card mb-4">
				<div class="card-header">
					<h5><?php D(__('proposal_Freelancer_Level',"Freelancer Level"));?></h5>
					<button class="btn btn-secondary btn-sm float-right clear_seller_level d-none" onclick="clearLevel()">
						<i class="fa fa-times-circle"></i> <?php D(__('proposal_Clear Filter',"Clear Filter"));?>
					</button>
				</div>
				<div class="card-body">
					
					<?php
					if($all_level){
						foreach($all_level as $l=>$level){
					?>
						<div class="custom-control custom-checkbox mt-2 mb-2">
				            <input type="checkbox" name="level_id[]" value="<?php D($l); ?>" class="custom-control-input get_seller_level" id="level_<?php D($l); ?>">
				             
				            <label for="level_<?php D($l); ?>" class="custom-control-label"><?php D($level['name']); ?></label>
			            </div>
					<?php
						}
					}
					?>
					
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5><?php D(__('proposal_Freelancer_Language',"Freelancer Language"));?></h5>
					<button class="btn btn-secondary btn-sm float-right clear_seller_language d-none" onclick="clearLanguage()">
						<i class="fa fa-times-circle"></i> <?php D(__('proposal_Clear Filter',"Clear Filter"));?>
					</button>
				</div>
				<div class="card-body">
					
					<?php
					if($all_language){
						foreach($all_language as $l=>$language){
					?>
					<div class="custom-control custom-checkbox mt-2 mb-2">			            
			            <input type="checkbox" name="language_id[]" value="<?php D($language->language_id); ?>" class="custom-control-input get_seller_language" id="lang_<?php D($language->language_id); ?>">			            
			            <label for="lang_<?php D($language->language_id); ?>" class="custom-control-label"><?php D($language->language_title); ?></label>
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
            <div class="row" id="ajax_table">
            </div>
            <div class="text-center" id="loader" style="display: none">
            	<?php load_view('inc/spinner',array('size'=>30));?>
            </div>
			 <br>
			<div class="container text-center padding-bottom-40">
				<button class="btn btn-primary" id="load_more" data-page="0"><?php D(__('proposal_load_more',"Load more.."));?></button>
			</div>
		</div>
	</div>
</div>
<!-- Container ends -->
</section>

<div class="append-modal"></div>
<script>
$(document).ready(function(){
	$('#filterLeft').click(function(){
	$('.filter-container').show();
	});
	$(".icon-feather-x").click(function(){
	$('.filter-container').hide();
	});
});
</script>
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
					$("#ajax_table").append('<div class="col-12"><div class="alert alert-danger"><?php D(__('proposal_search_no_result',"We haven\'t found any proposals/services matching that search."));?></div></div>');
				}else{
					$("#ajax_table").append('<div class="col-12"><div class="alert alert-danger"><?php D(__('proposal_category_no_result',"No proposals/services to Show in this Category Yet."));?></div></div>');
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
$("#filter_form").on('change','.get_online_sellers, .get_delivery_time, .get_seller_level, .get_seller_language',function(){
 	$("#ajax_table").empty();
 	getprojects(1);
 })

</script>
