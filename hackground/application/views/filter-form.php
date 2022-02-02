<style>
.advance-search-panel{
	background-color: #d7d8ed;
    padding: 12px;
    margin-top: 10px;
}
</style>
<?php 
$srch = get();
$similar_search_module = array(
		'category/list_record',
		'country/list_record',
		'nationality/list_record',
		'delivery_times/list_record',
		'language/list_record',
		'sections/list_record',
		'slider/list_record',
		'email_template/list_record',
		'notification_template/list_record',
		'skills/list_record',
		'cms/list_record',
		'coupon/list_record',
		'member/list_record',
		'wallet/list_record',
		'setting/general',
);

$advance_search_module = array(
		'buyer_request/list_record',
		'sub_category/list_record',
		'proposal/list_record',
		'wallet/txn_list',
		'orders/list_record',
		'setting/custom',
		
);
$csv_export_module = array(
		'member/list_record',
		'proposal/list_record',
		
);
if(in_array($url_segment, $similar_search_module)){ ?>
<form action="">
<section class="content-header">
  <div class="row">
  	<?php if(in_array($url_segment, $csv_export_module)){ ?>
  	<div class="col-sm-offset-7 col-sm-4">
  	<?php }else{?>
  	<div class="col-sm-offset-8 col-sm-4">
  	<?php }?>
	
		<div class="input-group">
			<input class="form-control" placeholder="Search..." name="term" value="<?php echo !empty($srch['term']) ? $srch['term'] : '';?>">

			<div class="input-group-btn">
			  <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
			</div>
		  </div>
	</div>
	<div class="col-sm-1">
	<?php if(in_array($url_segment, $csv_export_module)){ ?>
	<button type="submit" name="csv" value="1" class="btn btn-success" formtarget="_blank"><i class="fa fa-download"></i> CSV</button>
	<?php }?>
	</div>
  </div>
</section>
</form>
<?php }else if(in_array($url_segment, $advance_search_module)){  ?>
<form action="">
<input type="hidden" name="panel_open" value="<?php echo !empty($srch['panel_open']) ? $srch['panel_open'] : '0';?>" id="advance_search_panel_state"/>
<section class="content-header">
  <div class="row">
	<div class="col-sm-3">
		<a href="javascript:void(0)" onclick="toggleAdvanceSearch()" class="btn btn-box-tool" style="font-size: 15px; font-weight: bold;">Advance Search &nbsp; <i class="fa fa-search-plus <?php echo ICON_SIZE;?>"></i></a>
	</div>
	<?php if(in_array($url_segment, $csv_export_module)){ ?>
  	<div class="col-sm-offset-4 col-sm-4">
  	<?php }else{?>
  	<div class="col-sm-offset-5 col-sm-4">
  	<?php }?>
		<div class="input-group">
			<input class="form-control" placeholder="Search..." name="term" value="<?php echo !empty($srch['term']) ? $srch['term'] : '';?>">

			<div class="input-group-btn">
			  <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
			</div>
		  </div>
	</div>
	<div class="col-sm-1">
	<?php if(in_array($url_segment, $csv_export_module)){ ?>
	<button type="submit" name="csv" value="1" class="btn btn-success" formtarget="_blank"><i class="fa fa-download"></i> CSV</button>
	<?php }?>
	</div>
  </div>
  
  <div class="advance-search-panel" style="<?php echo !empty($srch['panel_open']) ? '' : 'display:none;';?>">
	<?php if($url_segment == 'buyer_request/list_record'){ ?>
	<a href="<?php echo base_url('buyer_request/list_record?panel_open=1'); ?>">All</a> |
	<a href="<?php echo base_url('buyer_request/list_record?status='.REQUEST_ACTIVE.'&panel_open=1'); ?>">Active</a> |
	<a href="<?php echo base_url('buyer_request/list_record?status='.REQUEST_PENDING.'&panel_open=1'); ?>">Pending</a> |
	<a href="<?php echo base_url('buyer_request/list_record?status='.REQUEST_UNAPPROVED.'&panel_open=1'); ?>">Declined</a> |
	<a href="<?php echo base_url('buyer_request/list_record?status='.REQUEST_PAUSED.'&panel_open=1'); ?>">Paused</a> 
	<?php } ?>
	
	<?php if($url_segment == 'proposal/list_record'){ ?>
	
	<div class="row">
		<div class="col-sm-4">
			<label>Delivery Time</label>
			<select class="form-control" name="delivery_time">
				<option value="">-select-</option>
				<?php print_select_option($delivery_times, 'delivery_id', 'delivery_title', $srch['delivery_time']);?>
			</select>
		</div>
		<div class="col-sm-4">
			<label>Category</label>
			<select class="form-control" name="category">
				<option value="">-select-</option>
				<?php print_select_option($category, 'category_id', 'name', $srch['category']);?>
			</select>
		</div>
		
		<div class="col-sm-12">
			<button class="btn btn-sm btn-success" style="margin-top: 10px; margin-bottom: 15px;">Filter</button>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
			<a href="<?php echo base_url('proposal/list_record?panel_open=1'); ?>">All</a> |
			<a href="<?php echo base_url('proposal/list_record?status='.PROPOSAL_ACTIVE.'&panel_open=1'); ?>">Active</a> |
			<a href="<?php echo base_url('proposal/list_record?status=featured&panel_open=1'); ?>">Featured</a> |
			<a href="<?php echo base_url('proposal/list_record?status='.PROPOSAL_PENDING.'&panel_open=1'); ?>">Pending Approval</a> |
			<a href="<?php echo base_url('proposal/list_record?status='.PROPOSAL_PAUSED.'&panel_open=1'); ?>">Paused</a> 
		</div>
	</div>
	
	<?php } ?>
	
	<?php if($url_segment == 'sub_category/list_record'){ ?>
	
	<div class="row">
		<div class="col-sm-4">
			<label>Category</label>
			<select class="form-control" name="category">
				<option value="">-select-</option>
				<?php print_select_option($category, 'category_id', 'name', $srch['category']);?>
			</select>
		</div>
		
		<div class="col-sm-12">
			<button class="btn btn-sm btn-success" style="margin-top: 10px; margin-bottom: 15px;">Filter</button>
		</div>
	</div>
	
	<?php } ?>
	
	
	<?php if($url_segment == 'orders/list_record'){ ?>
	<a href="<?php echo base_url('orders/list_record?panel_open=1'); ?>">All</a> |
	<a href="<?php echo base_url('orders/list_record?status='.ORDER_PENDING.'&panel_open=1'); ?>">Pending</a> |
	<a href="<?php echo base_url('orders/list_record?status='.ORDER_PROCESSING.'&panel_open=1'); ?>">Process</a> |
	<a href="<?php echo base_url('orders/list_record?status='.ORDER_COMPLETED.'&panel_open=1'); ?>">Completed</a> |
	<a href="<?php echo base_url('orders/list_record?status='.ORDER_CANCELLED.'&panel_open=1'); ?>">Cancelled</a> 
	<?php } ?>
	
	<?php if($url_segment == 'wallet/txn_list'){ ?>
	<a href="<?php echo base_url('wallet/txn_list?panel_open=1'); ?>">All</a> |
	<a href="<?php echo base_url('wallet/txn_list?status=1&panel_open=1'); ?>">Active</a> |
	<a href="<?php echo base_url('wallet/txn_list?status=0&panel_open=1'); ?>">Pending</a> |
	<a href="<?php echo base_url('wallet/txn_list?status=2&panel_open=1'); ?>">Deleted</a>
	
	<?php } ?>
	
	<?php if($url_segment == 'setting/custom'){ ?>
	<a href="<?php echo base_url('setting/custom?show=all&panel_open=1'); ?>">All</a> |
	<a href="<?php echo base_url('setting/custom?panel_open=1'); ?>">Editable</a> 
	
	<?php } ?>
	
	
  </div>
  
</section>
</form>

<script>
(function(){
	
var panel_open = <?php echo !empty($srch['panel_open']) ? 'true' : 'false';?>;
function toggleAdvanceSearch(){
	if(panel_open){
		$('#advance_search_panel_state').val(0);
		panel_open = false;
		$('.advance-search-panel').hide('fast');
	}else{
		panel_open = true;
		$('#advance_search_panel_state').val(1);
		$('.advance-search-panel').show('fast');
	}
}	

window.toggleAdvanceSearch = toggleAdvanceSearch;
	
})();
	
</script>
<?php } ?>