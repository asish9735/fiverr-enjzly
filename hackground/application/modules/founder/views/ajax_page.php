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
                  <label for="content_slug">Name</label>
                  <input type="text" class="form-control reset_field" id="founder_name" name="founder_name" autocomplete="off">
                </div>
				<?php $this->load->view('upload_file_component', array('input_name' => 'founder_image', 'url' => base_url('founder/upload_file'))); ?>
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>

				
				<div class="form-group">
                  <label for="content_<?php echo $v;?>">Content (<?php echo $v;?>)</label>
				  <div data-error-wrapper="lang[content][<?php echo $v; ?>]">
                  <textarea class="form-control reset_field" id="content_<?php echo $v;?>" name="lang[content][<?php echo $v; ?>]" autocomplete="off"></textarea>
				  </div>
                </div>
				
				<?php echo get_editor('content_'.$v);?>
				
				<?php } ?>
             	 <div class="form-group">
                  <label for="display_order">Display Order </label>
                  <input type="text" class="form-control reset_field" id="display_order" name="display_order" autocomplete="off">
                </div>
			   <div class="form-group">
			   <p><b>Status</b></p>
                <div class="radio-inline">
					<input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
					<label for="status_1">Active</label> 
				</div>
				 <div class="radio-inline">
					  <input type="radio" name="status" value="0" class="magic-radio" id="status_0">
					  <label for="status_0">Inactive</label> 
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

function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}

function submitForm(form, evt){
	evt.preventDefault();
	CKupdate();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd){
		if(res.cmd == 'reload'){
			location.reload();
		}else if(res.cmd == 'reset_form'){
			var form = $('#add_form');
			form.find('.reset_field').val('');
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
                  <label for="founder_name">Content Slug</label>
                  <input type="text" class="form-control reset_field" id="founder_name" name="founder_name" autocomplete="off" value="<?php echo !empty($detail['founder_name']) ? $detail['founder_name'] : '';?>"  />
                </div>
				<?php if(!empty($detail['founder_image']) && file_exists(LC_PATH.'userupload/founder/'.$detail['founder_image'])){ ?>
				<div class="form-group">
                  <label>Previous Image </label>
                  <div class="image-wrapper" id="previous_image">
					<button type="button" class="close" onclick="removeByID('previous_image')"><span aria-hidden="true">&times;</span></button>
					<img src="<?php echo USER_UPLOAD.'founder/'.$detail['founder_image']; ?>" class="img-rounded" alt="" width="210">
					<input type="hidden" name="founder_image" value="<?php echo $detail['founder_image'];?>"/>
				</div>
                </div>
				<?php } ?>
				
				
				<?php $this->load->view('upload_file_component', array('input_name' => 'founder_image', 'url' => base_url('founder/upload_file'))); ?>
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>

				<div class="form-group">
                  <label for="content_<?php echo $v;?>">Content (<?php echo $v;?>)</label>
				  <div data-error-wrapper="lang[content][<?php echo $v; ?>]">
                  <textarea class="form-control reset_field" id="content_<?php echo $v;?>" name="lang[content][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['content'][$v]) ? $detail['lang']['content'][$v] : '';?></textarea>
				  </div>
                </div>
				
				<?php echo get_editor('content_'.$v);?>
				
				<?php } ?>
				<div class="form-group">
                  <label for="display_order">Display Order </label>
                  <input type="text" class="form-control reset_field" id="display_order" name="display_order" autocomplete="off" value="<?php echo !empty($detail['display_order']) ? $detail['display_order'] : '';?>">
                </div>
			   <div class="form-group">
			   <p><b>Status</b></p>
                <div class="radio-inline">
					<input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
					<label for="status_1">Active</label> 
				</div>
				 <div class="radio-inline">
					  <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['status'] == '0' ?  'checked' : ''; ?>>
					  <label for="status_0">Inactive</label> 
				  </div>
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

function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}

function submitForm(form, evt){
	evt.preventDefault();
	CKupdate();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>
<?php } ?>