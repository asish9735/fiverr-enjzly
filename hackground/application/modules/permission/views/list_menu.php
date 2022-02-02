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

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $title ? $title : '';?></h3>

          <div class="box-tools pull-right">
			
		   <?php if(!get('show')){ ?>
			<div class="btn-group" id="global_action_btn" style="display:none">
			  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete selected" onclick="deleteSelected()"><i class="fa fa-trash"></i></button>
			  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Make active" onclick="changeStatusAll(1)"><i class="fa fa-thumbs-o-up"></i></button>
			   <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Make inactive" onclick="changeStatusAll(0)"><i class="fa  fa-thumbs-o-down"></i></button>
			</div>
			&nbsp;
			<?php } ?>
            <button type="button" class="btn btn-primary btn-sm" onclick="add()">
              <i class="fa fa-plus"></i>
				Add New Menu
			</button>
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
					<th style="width:3%">
						<input type="checkbox" class="check_all_main magic-checkbox" data-target=".check_all" id="all_item">
						<label for="all_item"></label>
					</th>
                  <th style="width:10%">ID</th>
                  <th style="width:20%">Menu</th>
                  <th style="width:20%">Sub Menu</th>
                  <th style="width:20%">Menu Code</th>
                  <th style="width:10%">Status</th>
                  <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
				$child = $v['child'];
				$status = '';
				if($v['status'] == ACTIVE_STATUS){
					$status = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, '.$v[$primary_key].',this)"><span class="label label-success">Active</span></a>';
				}else if($v['status'] == INACTIVE_STATUS){
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, '.$v[$primary_key].', this)"><span class="label label-danger">Inactive</span></a>';
				}else{
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Restore"  onclick="changeStatus(1, '.$v[$primary_key].', this)"><span class="label label-danger">Deleted</span></a>';
				}
				
				?>
				<tr>
					<td>
						
						<input type="checkbox" class="check_all magic-checkbox" name="ID[]" value="<?php echo $v[$primary_key]; ?>" id="item_<?php echo $v[$primary_key];?>">
						<label for="item_<?php echo $v[$primary_key];?>"></label>
						
					</td>
                  <td><?php echo $v[$primary_key]; ?></td>
                  <td>
					<?php echo $v['name']; ?>
					<div><small><?php echo $v['menu_desc'];?></small></div>
				  </td>
				  <td></td>
                  <td><?php echo $v['menu_code']; ?></td>
                  <td><?php echo $status; ?></td>
                  <td class="text-right" style="padding-right:20px;">
					<?php if($v['status'] != DELETE_STATUS){ ?>
					<a href="<?php echo JS_VOID; ?>" onclick="add('<?php echo $v[$primary_key]; ?>')" data-toggle="tooltip" title="Add Sub Menu"><i class="fa fa-plus <?php echo ICON_SIZE;?>"></i></a>
					&nbsp;
					<a href="<?php echo JS_VOID; ?>" onclick="edit('<?php echo $v[$primary_key]; ?>')" data-toggle="tooltip" title="Edit"><i class="fa fa-edit green <?php echo ICON_SIZE;?>"></i></a>
					&nbsp;
					<a href="<?php echo JS_VOID; ?>" onclick="return deleteRecord('<?php echo $v[$primary_key]; ?>', true)"data-toggle="tooltip" title="Delete"><i class="fa fa-trash red <?php echo ICON_SIZE;?>"></i></a>
					
					
					<?php } ?>
					
				  </td>
                </tr>
				
				<?php if($child){foreach($child as $key => $child_menu){ 
				$status = '';
				if($child_menu['status'] == ACTIVE_STATUS){
					$status = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, '.$child_menu[$primary_key].',this)"><span class="label label-success">Active</span></a>';
				}else if($child_menu['status'] == INACTIVE_STATUS){
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, '.$child_menu[$primary_key].', this)"><span class="label label-danger">Inactive</span></a>';
				}else{
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Restore"  onclick="changeStatus(1, '.$child_menu[$primary_key].', this)"><span class="label label-danger">Deleted</span></a>';
				}
				?>
				<tr class="child_menu childof-<?php echo $v[$primary_key];?>">
					<td>
						
						<input type="checkbox" class="check_all magic-checkbox" name="ID[]" value="<?php echo $child_menu[$primary_key]; ?>" id="item_<?php echo $child_menu[$primary_key];?>">
						<label for="item_<?php echo $child_menu[$primary_key];?>"></label>
						
					</td>
				  <td><?php echo $child_menu[$primary_key]; ?></td>
				  <td></td>	
                   <td>
					<?php echo $child_menu['name']; ?>
					<div><small><?php echo $child_menu['menu_desc'];?></small></div>
				  </td>
                  <td><?php echo $child_menu['menu_code']; ?></td>
                  <td><?php echo $status; ?></td>
                  <td class="text-right" style="padding-right:20px;">
					<?php if($child_menu['status'] != DELETE_STATUS){ ?>
					<a href="<?php echo JS_VOID; ?>" onclick="edit('<?php echo $child_menu[$primary_key]; ?>')" data-toggle="tooltip" title="Edit"><i class="fa fa-edit green <?php echo ICON_SIZE;?>"></i></a>
					&nbsp;
					<a href="<?php echo JS_VOID; ?>" onclick="return deleteRecord('<?php echo $child_menu[$primary_key]; ?>', true)"data-toggle="tooltip" title="Delete"><i class="fa fa-trash red <?php echo ICON_SIZE;?>"></i></a>
					&nbsp;
					<?php } ?>
					
				  </td>
                </tr>
				<?php } } ?>
				
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

function add(p_id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$add_command);?>';
	if(p_id > 0){
		url += '&parent_id='+p_id;
	}
	Modal.openURL({
		title : 'Add Menu',
		url : url
	});
	/* load_ajax_modal(url); */
}

function edit(id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$edit_command);?>&id='+id;
	Modal.openURL({
		title : 'Edit Menu',
		url : url
	});
	/* load_ajax_modal(url); */
}

function deleteRecord(id, permanent){
	permanent = permanent || false;
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		console.log('ok');
		var url = '<?php echo base_url($curr_controller.'delete_menu');?>/'+id;
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
