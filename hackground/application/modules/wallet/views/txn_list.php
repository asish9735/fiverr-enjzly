  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
        <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
     <?php echo $breadcrumb ? $breadcrumb : '';?>
    </section>

	 <!-- Content Filter -->
	<?php $this->layout->load_filter(); ?>
	
    <!-- Main content -->
    <section class="content">
	
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $title ? $title : '';?></h3>

          <div class="box-tools pull-right">
          <form action="<?php echo base_url($curr_controller.'csv')?>" method="get" target="_blank" style="max-width: 470px">
          	<input type="hidden"  name="type" value="transaction"/>
          	<div class="input-group">
				<input type="text" class="datepicker form-control" id="fromdate" name="daterange"/>
				<div class="input-group-btn">
				  	<button type="submit" class="btn btn-success"><i class="fa fa-download"></i> Download CSV</button>
				</div>
		  	</div>
          </form>
          
			<!--<a href="<?php echo base_url($curr_controller.'csv')?>?type=transaction" target="_blank" class="btn btn-success">Download CSV</a>-->
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
				  <th style="width:10%">ID</th>
                  <th style="width:30%">Detail</th>
                  <th style="width:20%">Transaction Date</th>
                  <th style="width:20%">Created Date</th>
                  <th style="width:10%">Status</th>
                 
                  <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
				$status = '';
				if($v['status'] == '1'){
					$status = '<span class="label label-success">Active</span>';
				}else if($v['status'] == '0'){
					$status = '<span class="label label-default">Pending</span>';
				}else if($v['status'] == '2'){
					$status = '<span class="label label-danger">Deleted</span>';
				}
				?>
				<tr>
					
                  <td><?php echo $v['wallet_transaction_id']; ?></td>
                  <td><?php echo $v['type_description_tkey']; ?></td>
                  <td><?php echo format_date_time($v['transaction_date']); ?></td>
                  <td><?php echo format_date_time($v['created_date']); ?></td>
                  <td><?php echo $status; ?></td>
                 
                  <td class="text-right" style="padding-right:20px;">
					<a href="<?php echo JS_VOID; ?>" data-toggle="tooltip" title="View Detail" onclick="view_txn_detail('<?php echo $v['wallet_transaction_id']; ?>')"><i class="fa fa-info-circle green <?php echo ICON_SIZE;?>"></i></a>
				  </td>
                </tr>
				<?php } }else{  ?>
				<tr>
                  <td colspan="10"><?php echo NO_RECORD; ?></td>
                 </tr>
				<?php } ?>
                
               </tbody>
			  </table>
        </div>
		 <!-- /.box-body -->
		<div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
               <?php echo $links;?>
              </ul>
            </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<div class="modal fade" id="ajaxModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		 
		</div>
	  </div>
</div>

<script>

function view_txn_detail(txn_id){
	Modal.openURL({
		title : 'Transaction Detail',
		url: '<?php echo base_url($curr_controller.'load_ajax_page?page=single_txn_detail');?>&id='+txn_id
	});
}


function add(){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$add_command);?>';
	load_ajax_modal(url);
}

function edit(id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$edit_command);?>&id='+id;
	load_ajax_modal(url);
}

function deleteRecord(id, permanent){
	permanent = permanent || false;
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		console.log('ok');
		var url = '<?php echo base_url($curr_controller.'delete_record');?>/'+id;
		if(permanent){
			url += '?cmd=remove';
		}
		$.getJSON(url, function(res){
			if(res.cmd && res.cmd == 'reload'){
				location.reload();
			}
		});
	}else{
		return false;
	}
}

function changeStatus(sts, id, ele){
	var status = [1, 0];
	if(status.indexOf(sts) !== -1){
		var url = '<?php echo base_url($curr_controller.'change_status');?>';
		$.ajax({
			url : url,
			data: {ID: id, status: sts},
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}else if(res.cmd == 'replace'){
						if(typeof ele !== 'undefined'){
							$('[data-toggle="tooltip"]').tooltip("destroy");
							$(ele).replaceWith(res.data.html);
							init_plugin();
						}
					}
				}
				
			}
		});
	}
	return false;
}

function changeStatusAll(sts){
	var data = $('#main_table').find('input').serialize();
	var status = [1, 0];
	if(status.indexOf(sts) !== -1){
		data += '&status=' + sts;
		data += '&action_type=multiple';
		var url = '<?php echo base_url($curr_controller.'change_status');?>';
		$.ajax({
			url : url,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}else if(res.cmd == 'replace'){
						if(typeof ele !== 'undefined'){
							$('[data-toggle="tooltip"]').tooltip("destroy");
							$(ele).replaceWith(res.data.html);
							init_plugin();
						}
					}
				}
				
			}
		});
	}
	return false;
}

function deleteSelected(){
	var c = confirm('Are you sure to delete selected record ?');
	if(c){
		var data = $('#main_table').find('input').serialize();
		data += '&action_type=multiple';
		var url = '<?php echo base_url($curr_controller.'delete_record');?>';
		$.ajax({
			url : url,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}
				}
				
			}
		});
	}
	
	return false;
}

function init_event(){
	
	var item  = $('.check_all_main').data('target');
	
	$(item).on('change', function(){
		checkSelected();
	});
	
	$('.check_all_main').on('change', function(){
		var is_checked = $(this).is(':checked');
		var target = $(this).data('target');
		if(is_checked){
			$(target).prop('checked', true);
		}else{
			$(target).prop('checked', false);
		}
		$(target).triggerHandler('change');
	});
	
	function checkSelected(){
		var target  = $('.check_all_main').data('target');
		var l = $(target + ':checked').length;
		if(l == 0){
			$('#global_action_btn').find('button').attr('disabled', 'disabled');
			$('#global_action_btn').hide();
		}else{
			$('#global_action_btn').find('button').removeAttr('disabled');
			$('#global_action_btn').show();
		}
	} 
}

$(function(){
	
	init_plugin(); /* global.js */
	init_event();
	
	
});
</script>
<script src="<?php echo ADMIN_COMPONENT;?>moment/moment.js"></script>
<script src="<?php echo ADMIN_COMPONENT;?>bootstrap-daterangepicker/daterangepicker.js"></script>
  <link rel="stylesheet" href="<?php echo ADMIN_COMPONENT;?>bootstrap-daterangepicker/daterangepicker.css">
  <script>
  	$('.datepicker').daterangepicker({
  		"startDate": "<?php echo date('Y/01/01');?>",
    	"endDate": "<?php echo date('Y/m/d');?>",
  		locale: {
            format: 'YYYY-MM-DD'
        }
  		
  	});
  </script>