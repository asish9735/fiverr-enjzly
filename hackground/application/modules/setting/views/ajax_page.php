<?php if($page == 'add'){ ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title;?></h4>
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
              <div class="box-body">
			  
				<div class="form-group">
                  <label for="name">Setting Name</label>
                  <input type="text" class="form-control" id="name" name="title" autocomplete="off">
                </div>
				
				<div class="form-group">
                  <label for="name">Setting Key</label>
                  <input type="text" class="form-control" id="name" name="setting_key" autocomplete="off">
                </div>
				
				<div class="form-group">
                  <label for="name">Setting Value</label>
                  <input type="text" class="form-control" id="name" name="setting_value" autocomplete="off">
                </div>
				
				<div class="form-group">
                  <label for="display_order">Display Order</label>
                  <input type="text" class="form-control" id="display_order" name="display_order" autocomplete="off">
                </div>
				
               
			   <div class="form-group">
			   <p><b>Editable</b></p>
                <div class="radio-inline">
					<input type="radio" name="editable" value="1" class="magic-radio" id="editable_1" checked>
					<label for="editable_1">Yes</label> 
				</div>
				 <div class="radio-inline">
					  <input type="radio" name="editable" value="0" class="magic-radio" id="editable_0">
					  <label for="editable_0">No</label> 
				  </div>
              </div>
			  
			   <div class="form-group">
			   <p><b>Deletable</b></p>
                <div class="radio-inline">
					<input type="radio" name="deletable" value="1" class="magic-radio" id="delete_1" checked>
					<label for="delete_1">Yes</label> 
				</div>
				 <div class="radio-inline">
					  <input type="radio" name="deletable" value="0" class="magic-radio" id="delete_0">
					  <label for="delete_0">No</label> 
				  </div>
              </div>
			  
			  
			  <div class="form-group">
				<div>
			     <input type="checkbox" name="add_more" value="1" class="magic-checkbox" id="add_more">
                  <label for="add_more">Add more record</label>
				</div>
              </div>
			  
              </div>
              <!-- /.box-body -->
			  <div class="box-footer">
                <button type="submit" class="btn-block btn btn-primary">Add</button>
              </div>
        </form>
</div>

<script>

init_plugin();

function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd){
		if(res.cmd == 'reload'){
			location.reload();
		}else if(res.cmd == 'reset_form'){
			var form = $('#add_form');
			form.find('[name="title"]').val('');
			form.find('[name="setting_key"]').val('');
			form.find('[name="setting_value"]').val('');
		}		
		
	}
}

</script>
<?php } ?>

<?php if($page == 'edit'){ ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title;?></h4>
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
			  <input type="hidden" name="ID" value="<?php echo $ID?>"/>
              <div class="box-body">
				<div class="form-group">
                  <label for="title">Setting Name</label>
                  <input type="text" class="form-control" id="title" name="title" value="<?php echo !empty($detail['title']) ? $detail['title'] : '';?>">
                </div>
				
				<div class="form-group">
                  <label>Setting Key</label>
                  <input type="text" class="form-control" name="setting_key" autocomplete="off" value="<?php echo !empty($detail['setting_key']) ? $detail['setting_key'] : '';?>" readonly>
                </div>
				
				<div class="form-group">
                  <label>Setting Value</label>
                  <input type="text" class="form-control" name="setting_value" autocomplete="off" value="<?php echo !empty($detail['setting_value']) ? $detail['setting_value'] : ($detail['setting_value']!='') ? $detail['setting_value']:'' ;?>">
                </div>
				
				<div class="form-group">
                  <label for="display_order">Display Order</label>
                  <input type="text" class="form-control" id="display_order" name="display_order" autocomplete="off" value="<?php echo !empty($detail['display_order']) ? $detail['display_order'] : '';?>">
                </div>
				
				
              </div>
              <!-- /.box-body -->
			  <div class="box-footer">
                <button type="submit" class="btn-block btn btn-primary">Save</button>
              </div>
        </form>
</div>

<script>

init_plugin();

function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>
<?php } ?>