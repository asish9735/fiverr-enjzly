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
                  <label for="title">Proposal </label>
                  <select class="form-control" name="proposal_id">
					<option value=""> - Select - </option>
					<?php print_select_option($proposals, 'proposal_id', 'proposal_title'); ?>
				  </select>
                </div>
				
				<div class="form-group">
                  <label for="coupon_title">Title </label>
                  <input type="text" class="form-control reset_field" id="coupon_title" name="coupon_title" autocomplete="off">
                </div>
				
				<div class="form-group">
					<label for="coupon_price">Price </label>
					<div class="row">
					<div class="col-sm-4">
						<select class="form-control" name="coupon_type" id="coupon_type">
							<option value="fixed_price">Fixed Price</option>
							<option value="discount_price">Discount Percentage</option>
						</select>
					</div>
					<div class="col-sm-8">
						<div class="input-group" id="coupon_price_wrapper">
							<span class="input-group-addon" data-val="fixed_price"><?php echo get_setting('site_currency');?></span>
							<span class="input-group-addon" data-val="discount_price" style="display:none;">%</span>
							<input type="text" class="form-control" placeholder="Price" name="coupon_price">
						</div>
					</div>
					</div>
					
				</div>
			  
               <div class="form-group">
                  <label for="coupon_code">Coupon Code </label>
                  <input type="text" class="form-control reset_field" id="coupon_code" name="coupon_code" autocomplete="off">
				  <span class="help-block">Use only letters and numbers. Space and special characters are not allowed </span>
                </div>
				
				<div class="form-group">
                  <label for="coupon_limit">Coupon Limit </label>
                  <input type="number" class="form-control reset_field" id="coupon_limit" name="coupon_limit" autocomplete="off">
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

function checkPriceType(e){
	var val = $(e.target).val();
	$('#coupon_price_wrapper').find('.input-group-addon').hide();
	$('#coupon_price_wrapper').find('[data-val="'+val+'"]').show();
}

$('#coupon_type').on('change', checkPriceType);

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
                  <label for="title">Proposal </label>
                  <select class="form-control" name="proposal_id">
					<option value=""> - Select - </option>
					<?php print_select_option($proposals, 'proposal_id', 'proposal_title', $detail['proposal_id']); ?>
				  </select>
                </div>
				
				<div class="form-group">
                  <label for="coupon_title">Title </label>
                  <input type="text" class="form-control reset_field" id="coupon_title" name="coupon_title" autocomplete="off" value="<?php echo !empty($detail['coupon_title']) ? $detail['coupon_title'] : '';?>">
                </div>
				
				<div class="form-group">
					<label for="coupon_price">Price </label>
					<div class="row">
					<div class="col-sm-4">
						<select class="form-control" name="coupon_type" id="coupon_type">
							<option value="fixed_price" <?php echo (!empty($detail['coupon_type']) && $detail['coupon_type'] == 'fixed_price') ? 'selected' : '';?>>Fixed Price</option>
							<option value="discount_price" <?php echo (!empty($detail['coupon_type']) && $detail['coupon_type'] == 'discount_price') ? 'selected' : '';?>>Discount Percentage</option>
						</select>
					</div>
					<div class="col-sm-8">
						<div class="input-group" id="coupon_price_wrapper">
							<span class="input-group-addon" data-val="fixed_price"><?php echo get_setting('site_currency');?></span>
							<span class="input-group-addon" data-val="discount_price" style="display:none;">%</span>
							<input type="text" class="form-control" placeholder="Price" name="coupon_price" value="<?php echo !empty($detail['coupon_price']) ? $detail['coupon_price'] : '';?>">
						</div>
					</div>
					</div>
					
				</div>
			  
               <div class="form-group">
                  <label for="coupon_code">Coupon Code </label>
                  <input type="text" class="form-control reset_field" id="coupon_code" name="coupon_code" autocomplete="off" value="<?php echo !empty($detail['coupon_code']) ? $detail['coupon_code'] : '';?>" readonly />
				  <span class="help-block">Use only letters and numbers. Space and special characters are not allowed </span>
                </div>
				
				<div class="form-group">
                  <label for="coupon_limit">Coupon Limit </label>
                  <input type="number" class="form-control reset_field" id="coupon_limit" name="coupon_limit" autocomplete="off" value="<?php echo !empty($detail['coupon_limit']) ? $detail['coupon_limit'] : '';?>">
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
function checkPriceType(e){
	var val = $(e.target).val();
	$('#coupon_price_wrapper').find('.input-group-addon').hide();
	$('#coupon_price_wrapper').find('[data-val="'+val+'"]').show();
}

$('#coupon_type').on('change', checkPriceType);

$('#coupon_type').change();

</script>
<?php } ?>