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
			
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding table_visible" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
					
                  <th style="width:5%">ID</th>
                  <th style="width:15%">Name</th>
                  <th style="width:20%">Email/Phone</th>
                  <!--<th style="width:5%">Type</th>-->
                  <th style="width:10%">Country</th>
                  <th style="width:10%" class="text-center">Verification</th>
                  <th style="width:10%">Registered On</th>
                  <th style="width:10%">Status</th>
                  <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
				$email_ver_txt = $phone_ver_txt = $email_ver_class = $phone_ver_class = '';
				if($v['is_email_verified'] > 0){
					$email_ver_txt = 'Verified';
					$email_ver_class = 'green';
				}else{
					$email_ver_txt = 'Not Verified';
					$email_ver_class = '';
				}
				
				if($v['is_admin_verified'] > 0){
					$is_admin_verified_ver_txt = 'Admin Verified';
					$is_admin_verified_ver_class = 'green';
				}else{
					$is_admin_verified_ver_txt = 'Admin Not Verified';
					$is_admin_verified_ver_class = '';
				}
				
				
				if($v['is_phone_verified'] > 0){
					$phone_ver_txt = 'Verified';
					$phone_ver_class = 'green';
				}else{
					$phone_ver_txt = 'Not Verified';
					$phone_ver_class = '';
				}
				
				$status = '';
				if($v['login_status'] == 1){
					$status = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, '.$v[$primary_key].',this)"><span class="label label-success">Active</span></a>';
					$status_txt="Active";
				}else if($v['login_status'] == 0){
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, '.$v[$primary_key].', this)"><span class="label label-danger">Inactive</span></a>';
					$status_txt="Inactive";
				}else{
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Restore"  onclick="changeStatus(1, '.$v[$primary_key].', this)"><span class="label label-danger">Deleted</span></a>';
					$status_txt="Deleted";
				}
				?>
				<tr>
					
                  <td><?php echo $v[$primary_key]; ?></td>
                  <td><?php echo $v['member_name']; ?></td>
                  
                  <td><?php echo $v['member_email']; ?><br><?php echo $v['member_mobile_code']; ?> <?php echo $v['member_phone']; ?></td>
                  <!--<td><?php echo ($v['is_freelancer']==1 ? 'Freelancer':'Buyer'); ?></td>-->
                  <td><?php echo $v['country_name']; ?></td>
                  <td class="text-center">
					<i class="fa fa-id-badge fa-lg <?php echo ICON_SIZE;?> <?php echo $is_admin_verified_ver_class; ?>" data-toggle="tooltip" title="<?php echo $is_admin_verified_ver_txt;?>"></i>
					&nbsp;<i class="fa fa-envelope <?php echo ICON_SIZE;?> <?php echo $email_ver_class; ?>" data-toggle="tooltip" title="<?php echo $email_ver_txt;?>"></i>
					&nbsp;
					<i class="fa fa-phone <?php echo ICON_SIZE;?> <?php echo $phone_ver_class; ?>" data-toggle="tooltip" title="<?php echo $phone_ver_txt;?>"></i>
				  </td>
                  <td><?php echo date('d M,Y h:i A', strtotime($v['member_register_date'])); ?></td>
				   <td><?php echo $status; ?></td>
      
				  <td class="text-right" style="padding-right:20px;">
					<div class="btn-group">
					  <button type="button" class="btn btn-default"><?php echo $status_txt; ?></button>
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
					  <li><a href="<?php echo URL?>p-<?php echo $v['access_username']; ?>" target="_blank">View Details</a></li>
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
