<?php if($page == 'add'){ ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?php echo $title;?></h4>
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
              <div class="box-body">
				
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[name][<?php echo $v; ?>]" autocomplete="off">
                </div>
				
				<div class="form-group">
                  <label for="info_<?php echo $v;?>">Info (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="info_<?php echo $v;?>" name="lang[info][<?php echo $v; ?>]" autocomplete="off">
                </div>
				
				<?php } ?>
				
			   <?php $this->load->view('upload_file_component', array('input_name' => 'category_image', 'url' => base_url('category/upload_file'))); ?>
			   
			   <div class="form-group">
                  <label for="category_key">Category Key </label>
                  <input type="text" class="form-control reset_field" id="category_key" name="category_key" autocomplete="off">
                </div>
				
			   <div class="form-group">
                  <label for="display_order">Display Order </label>
                  <input type="text" class="form-control reset_field" id="display_order" name="display_order" autocomplete="off">
                </div>
				
			    <div class="form-group">
				<div>
				<input type="hidden" name="is_featured" value="0"/>
			     <input type="checkbox" name="is_featured" value="1" class="magic-checkbox" id="is_featured">
                  <label for="is_featured">Featured</label>
				</div>
              </div>
			  <div class="form-group">
				<div>
				<input type="hidden" name="category_module" value="0"/>
			     <input type="checkbox" name="category_module" value="1" class="magic-checkbox" id="category_module">
                  <label for="category_module">Apply Design Feature</label>
				</div>
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
			  
				<?php
				
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['name'][$v]) ? $detail['lang']['name'][$v] : '';?>">
                </div>
				
				<div class="form-group">
                  <label for="info_<?php echo $v;?>">Info (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="info_<?php echo $v;?>" name="lang[info][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['info'][$v]) ? $detail['lang']['info'][$v] : '';?>">
                </div>
				
				<?php } ?>
				
				<?php if(!empty($detail['category_image']) && file_exists(LC_PATH.'userupload/category/'.$detail['category_image'])){ ?>
				<div class="form-group">
                  <label>Previous Image </label>
                  <div class="image-wrapper" id="previous_image">
					<button type="button" class="close" onclick="removeByID('previous_image')"><span aria-hidden="true">&times;</span></button>
					<img src="<?php echo USER_UPLOAD.'category/'.$detail['category_image']; ?>" class="img-rounded" alt="" height="64" width="64">
					<input type="hidden" name="category_image" value="<?php echo $detail['category_image'];?>"/>
				</div>
                </div>
				<?php } ?>
				
				
				<?php $this->load->view('upload_file_component', array('input_name' => 'category_image', 'url' => base_url('category/upload_file'))); ?>
				
				 <div class="form-group">
                  <label for="category_key">Category Key </label>
                  <input type="text" class="form-control reset_field" id="category_key" name="category_key" autocomplete="off" value="<?php echo !empty($detail['category_key']) ? $detail['category_key'] : '';?>">
                </div>
				
				<div class="form-group">
                  <label for="display_order">Display Order </label>
                  <input type="text" class="form-control reset_field" id="display_order" name="display_order" autocomplete="off" value="<?php echo !empty($detail['display_order']) ? $detail['display_order'] : '';?>">
                </div>
				
				<div class="form-group">
				<div>
				<input type="hidden" name="is_featured" value="0"/>
			     <input type="checkbox" name="is_featured" value="1" class="magic-checkbox" id="is_featured" <?php echo $detail['is_featured'] == '1' ? 'checked' : ''; ?> />
                  <label for="is_featured">Featured</label>
				</div>
              </div>
			  <div class="form-group">
				<div>
				<input type="hidden" name="category_module" value="0"/>
			     <input type="checkbox" name="category_module" value="1" class="magic-checkbox" id="category_module" <?php echo $detail['category_module'] == '1' ? 'checked' : ''; ?>>
                  <label for="category_module">Apply Design Feature</label>
				</div>
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