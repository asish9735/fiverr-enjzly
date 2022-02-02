<?php if($page == 'add'){ ?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title"><?php echo $title;?></h4>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <div class="box-body">
      <div class="form-group">
        <label for="name">Name </label>
        <input type="text" class="form-control reset_field" id="name" name="member_name" autocomplete="off">
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
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title"><?php echo $title;?></h4>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID?>"/>
    <div class="box-body">
      <div class="form-group">
        <label for="name">Name </label>
        <input type="text" class="form-control reset_field" id="name" name="member_name" autocomplete="off" value="<?php echo !empty($detail['member_name']) ? $detail['member_name'] : ''; ?>">
      </div>
      <div class="form-group">
        <label for="member_email">Email </label>
        <input type="email" class="form-control reset_field" id="member_email" name="member_email" autocomplete="off" value="<?php echo !empty($detail['member_email']) ? $detail['member_email'] : ''; ?>">
      </div>
      <div class="form-group">
        <label for="member_country">Nationality </label>
        <select class="form-control" name="member_country">
          <option value="">-select-</option>
          <?php print_select_option($country_list, 'country_code', 'country_name', !empty($detail['member_country']) ? $detail['member_country'] : '');?>
        </select>
      </div>
      <div class="form-group">
        <label for="member_nationality">Country </label>
        <select class="form-control" name="member_nationality">
          <option value="">-select-</option>
          <?php print_select_option($country_list, 'country_code', 'country_name', !empty($detail['member_nationality']) ? $detail['member_nationality'] : '');?>
        </select>
      </div>
      <div class="form-group">
        <label for="city">City </label>
        <input type="text" class="form-control reset_field" id="city" name="member_city" autocomplete="off" value="<?php echo !empty($detail['member_city']) ? $detail['member_city'] : ''; ?>">
      </div>
      <div class="form-group">
        <label for="member_email">New Password </label>
        <input type="password" class="form-control reset_field" id="new_pass" name="new_pass" autocomplete="off" value="">
      </div>
      <div class="form-group">
        <label for="member_email">Confirm Password </label>
        <input type="text" class="form-control reset_field" id="new_pass_again" name="new_pass_again" autocomplete="off" value="">
      </div>
      <div class="form-group">
        <p><b>Gender</b></p>
        <div class="radio-inline">
          <input type="radio" name="member_gender" value="F" class="magic-radio" id="member_gender_f" <?php echo $detail['member_gender'] == 'F' ?  'checked' : ''; ?>>
          <label for="member_gender_f">Female</label>
        </div>
        <div class="radio-inline">
          <input type="radio" name="member_gender" value="M" class="magic-radio" id="member_gender_m" <?php echo $detail['member_gender'] == 'M' ?  'checked' : ''; ?>>
          <label for="member_gender_m">Male</label>
        </div>
      </div>
      <!--<div class="form-group hidden">

			   <p><b>Profile Type</b></p>

                <div class="radio-inline">

					<input type="radio" name="is_freelancer" value="1" class="magic-radio" id="is_freelancer_1" checked>

					<label for="is_freelancer_1">Freelancer</label> 

				</div>

				 <div class="radio-inline">

					  <input type="radio" name="is_freelancer" value="0" class="magic-radio" id="is_freelancer_0" <?php echo $detail['is_freelancer'] == '0' ?  'checked' : ''; ?>>

					  <label for="is_freelancer_0">Buyer</label> 

				  </div>

              </div>-->
      <div class="form-group">
        <label for="member_phone">Phone </label>
        <div class="input-group">
          <div class="input-group-addon" style="padding: 0px;border: 0;">
            <select class="form-control" name="mobile_code" style="width:85px" >
              <option value="">-select-</option>
              <?php print_select_option($mobile_codes, 'codes', 'codes', !empty($detail['member_mobile_code']) ? $detail['member_mobile_code'] : '');?>
            </select>
          </div>
          <input type="text" class="form-control reset_field" id="phone" name="phone" autocomplete="off" value="<?php echo !empty($detail['member_phone']) ? $detail['member_phone'] : ''; ?>">
        </div>
      </div>
      <div class="form-group">
        <p><b>Email Verified</b></p>
        <div class="radio-inline">
          <input type="radio" name="is_email_verified" value="1" class="magic-radio" id="is_email_verified_1" checked>
          <label for="is_email_verified_1">Yes</label>
        </div>
        <div class="radio-inline">
          <input type="radio" name="is_email_verified" value="0" class="magic-radio" id="is_email_verified_0" <?php echo $detail['is_email_verified'] == '0' ?  'checked' : ''; ?>>
          <label for="is_email_verified_0">No</label>
        </div>
      </div>
      <div class="form-group">
        <p><b>Phone Verified</b></p>
        <div class="radio-inline">
          <input type="radio" name="is_phone_verified" value="1" class="magic-radio" id="is_phone_verified_1" checked>
          <label for="is_phone_verified_1">Yes</label>
        </div>
        <div class="radio-inline">
          <input type="radio" name="is_phone_verified" value="0" class="magic-radio" id="is_phone_verified_0" <?php echo $detail['is_phone_verified'] == '0' ?  'checked' : ''; ?>>
          <label for="is_phone_verified_0">No</label>
        </div>
      </div>
      <div class="form-group">
        <p><b>Status</b></p>
        <div class="radio-inline">
          <input type="radio" name="is_login" value="1" class="magic-radio" id="is_login_1" checked>
          <label for="is_login_1">Yes</label>
        </div>
        <div class="radio-inline">
          <input type="radio" name="is_login" value="0" class="magic-radio" id="is_login_0" <?php echo $detail['is_login'] == '0' ?  'checked' : ''; ?>>
          <label for="is_login_0">No</label>
        </div>
      </div>
      <div class="form-group">
        <p><b>Admin Verified</b></p>
        <div class="radio-inline">
          <input type="radio" name="is_admin_verified" value="1" class="magic-radio" id="is_admin_verified_1" checked>
          <label for="is_admin_verified_1">Yes</label>
        </div>
        <div class="radio-inline">
          <input type="radio" name="is_admin_verified" value="0" class="magic-radio" id="is_admin_verified_0" <?php echo $detail['is_admin_verified'] == '0' ?  'checked' : ''; ?>>
          <label for="is_admin_verified_0">No</label>
        </div>
      </div>
      <div class="form-group">
        <p><b>Bank Transfer Allowed</b></p>
        <div class="radio-inline">
          <input type="radio" name="bank_transfer_allowed" value="1" class="magic-radio" id="bank_transfer_allowed_1" checked>
          <label for="bank_transfer_allowed_1">Yes</label>
        </div>
        <div class="radio-inline">
          <input type="radio" name="bank_transfer_allowed" value="0" class="magic-radio" id="bank_transfer_allowed_0" <?php echo $detail['bank_transfer_allowed'] == '0' ?  'checked' : ''; ?>>
          <label for="bank_transfer_allowed_0">No</label>
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
