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
                  <label for="template_for">Template For</label>
                  <input type="text" class="form-control reset_field" id="template_for" name="template_for" autocomplete="off">
                </div>
				
				<div class="form-group">
                  <label for="template_key">Template Key</label>
                  <input type="text" class="form-control reset_field" id="template_key" name="template_key" autocomplete="off">
                </div>
				
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				
				<div class="form-group">
                  <label for="template_content_<?php echo $v;?>">Content (<?php echo $v;?>)</label>
                  <textarea class="form-control reset_field" id="template_content_<?php echo $v;?>" name="lang[template_content][<?php echo $v; ?>]" autocomplete="off"></textarea>
                </div>
				
				<?php } ?>
               
			   <div class="form-group">
                  <label for="display_order">Display Order</label>
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
				
				<div class="form-group">
                  <label for="template_for">Template For</label>
                  <input type="text" class="form-control reset_field" id="template_for" name="template_for" autocomplete="off" value="<?php echo !empty($detail['template_for']) ? $detail['template_for'] : '';?>">
                </div>
				
				<div class="form-group">
                  <label for="template_key">Template Key</label>
                  <input type="text" class="form-control reset_field" id="template_key" name="template_key" autocomplete="off" value="<?php echo !empty($detail['template_key']) ? $detail['template_key'] : '';?>" readonly />
                </div>
				
				
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				
				<div class="form-group">
                  <label for="template_content_<?php echo $v;?>">Content (<?php echo $v;?>)</label>
                  <textarea class="form-control reset_field" id="template_content_<?php echo $v;?>" name="lang[template_content][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['template_content'][$v]) ? $detail['lang']['template_content'][$v] : '';?></textarea>
                </div>
				
				<?php } ?>
				
				<div class="form-group">
                  <label for="display_order">Display Order</label>
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