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
	<div class="row">        
        <div class="col-lg-9 offset-lg-3 col-12">
        	<div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="mb-0"><strong>250</strong> Gigs Found</p>
                </div>
                <div class="sortFilter sort-by">
                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary <?php if($view_type=='list'){echo 'btn-active';}?>" onclick="setview('grid')"><i class="icon-feather-list"></i></a>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary ml-2 <?php if($view_type!='list'){echo 'btn-active';}?>" onclick="setview('grid')"><i class="icon-feather-grid"></i></a>
					<select class="selectpicker hide-tick w-auto">
                    	<option>Recent</option>
                        <option>Popular</option>
                        <option>Price - Low to High</option>
                        <option>Price - High to Low</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
 	<a href="javascript:void(0)" class="btn btn-sm btn-site float-right d-lg-none" id="filterLeft"><i class="icon-feather-filter"></i> Filter</a>
        
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
                <h4 class="list-group-header bg-white" style="padding: 0.75rem 1rem; margin: 0;"><?php D(__('proposal_Categories',"Categories"));?></h4>
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
                <div class="sidebar-widget">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input get_online_sellers" name="is_online_user" value="1" id="customCheck1">
                  <label class="custom-control-label" for="customCheck1"><?php D(__('proposal_show_online_user',"Show Online Freelancers"));?></label>
                </div>
				</div>
							                
                
                <!-- Hourly Rate -->
				<div class="sidebar-widget">
					<h3>Price</h3>
					<div class="margin-top-50"></div>
					<!-- Range Slider -->
					<input class="range-slider" type="text" value="" data-slider-currency="$" data-slider-min="10" data-slider-max="250" data-slider-step="5" data-slider-value="[10,250]"/>
				</div>
                <div class="sidebar-widget">
                <h5><?php D(__('proposal_Delivery_Time',"Delivery Time"));?></h5>
                <button class="btn btn-secondary btn-sm float-right clear_delivery_time d-none" onclick="clearDelivery()">
                    <i class="fa fa-times-circle"></i> <?php D(__('proposal_Clear Filter',"Clear Filter"));?>
                </button>
                <?php
                if($all_delivery_times){
                    foreach($all_delivery_times as $t=>$delivery_time){
                ?>
                <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input get_delivery_time" name="delivery_id[]" value="<?php D($delivery_time->delivery_id);?>" id="time_<?php D($delivery_time->delivery_id);?>">
                <label class="custom-control-label" for="time_<?php D($delivery_time->delivery_id);?>"><?php D($delivery_time->delivery_title);?></label>
                </div>
                        
                <?php
                    }
                }
                ?>
                
                </div>
				
			
				<div class="sidebar-widget">
            		<h5><?php D(__('proposal_Freelancer_Level',"Freelancer Level"));?></h5>
					<button class="btn btn-secondary btn-sm float-right clear_seller_level d-none" onclick="clearLevel()">
						<i class="fa fa-times-circle"></i> <?php D(__('proposal_Clear Filter',"Clear Filter"));?>
					</button>
					
					<?php
					if($all_level){
						foreach($all_level as $l=>$level){
					?>
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input get_seller_level" name="level_id[]" value="<?php D($l); ?>" id="level_<?php D($l); ?>">
                      <label class="custom-control-label" for="level_<?php D($l); ?>"><?php D($level['name']); ?></label>
                    </div>						
					<?php
						}
					}
					?>
				
				</div>
				<div class="sidebar-widget">
				
					<h5><?php D(__('proposal_Freelancer_Language',"Freelancer Language"));?></h5>
					<button class="btn btn-secondary btn-sm float-right clear_seller_language d-none" onclick="clearLanguage()">
						<i class="fa fa-times-circle"></i> <?php D(__('proposal_Clear Filter',"Clear Filter"));?>
					</button>
				
		
					
					<?php
					if($all_language){
						foreach($all_language as $l=>$language){
					?>
					<div class="custom-control custom-switch">			            
			            <input type="checkbox" name="language_id[]" value="<?php D($language->language_id); ?>" class="custom-control-input get_seller_language" id="lang_<?php D($language->language_id); ?>">			            
			            <label for="lang_<?php D($language->language_id); ?>" class="custom-control-label"><?php D($language->language_title); ?></label>
			        </div>
					<?php
						}
					}
					?>
					
				
			</div>
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
// Default Bootstrap Range Slider
function ThousandSeparator(nStr) {
	nStr += '';
	var x = nStr.split('.');
	var x1 = x[0];
	var x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
	var currencyAttr = $(".range-slider").attr('data-slider-currency');
	
	$(".range-slider").slider({
		formatter: function(value) {
			return currencyAttr + ThousandSeparator(parseInt(value[0])) + " - " + currencyAttr + ThousandSeparator(parseInt(value[1]));
		}
	});
	$( ".range-slider" ).on( "slideStop", function( event ) {
		var data=event.value;
		$('.min_price').val(data[0]);
		$('.max_price').val(data[1]);
		filterForm();
	} );
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
functio setview(type){
	$.ajax({
		url:"<?php D(base_url('category/setloadview'))?>",
		type:'post',
		dataType:'json',
		data: {type:type},
	}).done(function(response){
		getprojects(1);
	});
}
</script>
