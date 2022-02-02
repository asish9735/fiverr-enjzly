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
			<?php if(ALLOW_TRASH_VIEW){ ?>
			<?php if(get('show') && get('show') == 'trash'){ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method);?>" type="button" class="btn btn-box-tool"><i class="fa fa-check-circle-o <?php echo ICON_SIZE;?>"></i> Show Main</a>&nbsp;&nbsp;
			<?php }else{ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method.'?show=trash');?>" type="button" class="btn btn-box-tool"><i class="fa fa-trash <?php echo ICON_SIZE;?>"></i> Show Trash</a>&nbsp;&nbsp;
			<?php } ?>
			<?php } ?>
		   
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding table_visible" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
					<th style="width:10%">ID</th>
                  <th style="width:30%">Name</th>
                  <th style="width:30%">User</th>
                  <th style="width:5%">Status</th>
                  <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){
				
				$status = $status_txt = '';
				if($v['proposal_status'] == PROPOSAL_ACTIVE){
					$status = '<span class="label label-success">Active</span>';
					$status_txt = 'Active';
				}else if($v['proposal_status'] == PROPOSAL_PENDING){
					$status = '<span class="label label-warning">Pending</span>';
					$status_txt = 'Pending';
				}else if($v['proposal_status'] == PROPOSAL_DECLINED){
					$status = '<span class="label label-danger">Declined</span>';
					$status_txt = 'Declined';
				}else if($v['proposal_status'] == PROPOSAL_MODIFICATION){
					$status = '<span class="label label-primary">Modification</span>';
					$status_txt = 'Modification';
				}else if($v['proposal_status'] == PROPOSAL_PAUSED){
					$status = '<span class="label label-default">Pause</span>';
					$status_txt = 'Pause';
				}else{
					$status = '<span class="label label-danger">Deleted</span>';
					$status_txt = 'Deleted';
				}
				if($v['proposal_status'] == PROPOSAL_ACTIVE){
				if($v['proposal_featured']==1){
					$status.='<br><small class="">Featured till '.date('d-m-Y',strtotime($v['featured_end_date'])).'</small>';
				}
				}
				?>
				<tr>
					
                  <td><?php echo $v[$primary_key]; ?></td>
                  <td><?php echo $v['proposal_title']; ?></td>
                  <td><?php echo $v['member_name']; ?></td>
                  <td><?php echo $status; ?></td>
                  <td class="text-right" style="padding-right:20px;">
					<div class="btn-group">
					  <button type="button" class="btn btn-default"><?php echo $status_txt; ?></button>
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
					  <li><a href="<?php echo URL?>proposals/view/<?php echo getUserName($v['proposal_seller_id']); ?>/<?php echo $v['proposal_url']; ?>" target="_blank">View Details</a></li>
						<?php if($v['proposal_status'] == PROPOSAL_PENDING){ ?>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('<?php echo PROPOSAL_ACTIVE; ?>', '<?php echo $v[$primary_key]; ?>')">Active</a></li>
						<li><a href="<?php echo JS_VOID;?>" onclick="SetReason('<?php echo PROPOSAL_MODIFICATION; ?>', '<?php echo $v[$primary_key]; ?>')">Modification</a></li>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('<?php echo PROPOSAL_DECLINED; ?>', '<?php echo $v[$primary_key]; ?>')">Declined</a></li>
						<?php }else if($v['proposal_status'] == PROPOSAL_ACTIVE){  ?>
						<li><a href="<?php echo JS_VOID;?>" onclick="SetReason('<?php echo PROPOSAL_PAUSED; ?>', '<?php echo $v[$primary_key]; ?>')">Pause</a></li>
						<?php }else if($v['proposal_status'] == PROPOSAL_PAUSED){  ?>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('<?php echo PROPOSAL_ACTIVE; ?>', '<?php echo $v[$primary_key]; ?>')">Active</a></li>
						<?php }else if($v['proposal_status'] == PROPOSAL_MODIFICATION){  ?>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('<?php echo PROPOSAL_ACTIVE; ?>', '<?php echo $v[$primary_key]; ?>')">Active</a></li>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('<?php echo PROPOSAL_DECLINED; ?>', '<?php echo $v[$primary_key]; ?>')">Declined</a></li>
						<?php }else if($v['proposal_status'] == PROPOSAL_DECLINED){  ?>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('<?php echo PROPOSAL_ACTIVE; ?>', '<?php echo $v[$primary_key]; ?>')">Active</a></li>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('<?php echo PROPOSAL_MODIFICATION; ?>', '<?php echo $v[$primary_key]; ?>')">Modification</a></li>
						<?php }else{  ?>
						<!--<li>No Action </li>-->
						<?php } ?>
						<?php 
						if($v['proposal_status'] == PROPOSAL_ACTIVE){
						if($v['proposal_featured']==1){?>
						<li><a href="<?php echo JS_VOID;?>" onclick="makeFeature('0','<?php echo $v[$primary_key]; ?>')">Remove Featured</a></li>
						<?php }else{?>
						<li><a href="<?php echo JS_VOID;?>" onclick="makeFeature('1','<?php echo $v[$primary_key]; ?>')">Make Featured</a></li>
						<?php }
						}
						?>
						<li><a href="<?php echo JS_VOID; ?>" onclick="edit('<?php echo $v[$primary_key]; ?>')" data-toggle="tooltip" title="Edit">Edit</a></li>
					  </ul>
					</div>
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
function makeFeature(sts,id, ele){
	var url = '<?php echo base_url($curr_controller.'makefeatured');?>';
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
	return false;
}
function SetReason(type,id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page=reason');?>&id='+id+"&type="+type;
	load_ajax_modal(url);
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
