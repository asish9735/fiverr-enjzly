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
			
		   <div class="btn-group" id="global_action_btn" style="display:none">
			  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Send mail selected" onclick="sendmailSelected()"><i class="fa fa-envelope"></i> Send Email</button>
			</div>
			&nbsp;
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding table_visible" id="main_table">
              <table class="table table-hover" style="margin-bottom: 50px">
                <tbody>
				<tr>
				  <th style="width:3%">
						<input type="checkbox" class="check_all_main magic-checkbox" data-target=".check_all" id="all_item">
						<label for="all_item"></label>
				  </th>
				  <th style="width:10%">ID</th>
                  <th style="width:20%">Member Name</th>
                  <th style="width:20%">Member Email</th>
                  <th style="width:20%">Member Username</th>
                 <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php $currency = get_setting('site_currency');
				if(count($matchuser) > 0){foreach($matchuser as $k => $v){ 
				$seller_user_name=getUserName($v['member_id']);
				$profileurl=URL.'p-'.$seller_user_name;
				?>
				<tr>
					
                  <td>
						
					<input type="checkbox" class="check_all magic-checkbox" name="ID[]" value="<?php echo $v['member_id']; ?>" id="item_<?php echo $v['member_id'];?>">
					<label for="item_<?php echo $v['member_id'];?>"></label>
						
				</td>
                  <td><?php echo $v['member_id']; ?></td>
                  <td><?php echo $v['member_name']; ?></td>
                  <td><?php echo $v['member_email']; ?></td>
                  <td><?php echo $seller_user_name; ?></td>
                  <td class="text-right" style="padding-right:20px;"><a href="<?php echo $profileurl; ?>"  data-toggle="tooltip" title="view details" target="_blank"><i class="fa fa-eye red <?php echo ICON_SIZE;?>"></i></a></td>
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
function sendmailSelected(){
	var c = confirm('Are you sure to send email to selected record ?');
	if(c){
		var data = $('#main_table').find('input').serialize();
		data += '&action_type=multiple&request_id=<?php echo $requestDetails["request_id"]?>';
		var url = '<?php echo base_url($curr_controller.'sendemail');?>?cmd=sendemail';
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

