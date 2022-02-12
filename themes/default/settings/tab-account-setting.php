<?php

//$enable_paypal = get_option_value('enable_paypal');

$enable_paypal=$enable_bank=$enable_payoneer=1;

$enable_coinpayments =get_option_value('enable_coinpayments');

$enable_dusupay =get_option_value('enable_dusupay');

?>
<div class="card mb-4">
  <div class="card-body">
<?php // D(__('settings_page_Account_Settings_heading',"Account Settings"));?>
<?php if($enable_paypal == 1){ ?>


  	<h5>
      <?php D(__('account_settings_page_paypal_heading',"PayPal For Withdrawing Revenue"));?>
    </h5>
    <form action="" method="post" accept-charset="utf-8" id="accountPaypalform" class="mb-4" role="form" name="accountPaypalform" onsubmit="saveAccount(this);return false;">
      <input type="hidden" name="section" value="paypal"/>
      <div class="form-group">
        <label class="form-label">
          <?php D(__('account_settings_page_paypal_Enter_Email',"Enter Paypal Email"));?>
        </label>
        <input type="text" name="seller_paypal_email" id="seller_paypal_email" value="<?php if($member_details && $member_details['member_payment_settings']){D($member_details['member_payment_settings']->paypal_email);} ?>" placeholder="<?php D(__('account_settings_page_paypal_Enter_Email_input',"Enter Paypal Email"));?>" class="form-control" >
          <span id="seller_paypal_emailError" class="rerror"></span>
      </div>
      <button type="submit" name="submit_paypal_email" class="btn btn-dark saveBTN">
      <?php D(__('account_settings_page_paypal_button',"Change Paypal Email"));?>
      </button>        
    </form>

<?php }?>

    <h5>
      <?php D(__('account_settings_page_bank_heading',"Bank Transfer For Withdrawing Revenue"));?>
    </h5>
    <form action="" method="post" accept-charset="utf-8" id="accountBankform" class="mb-4" role="form" name="accountBankform" onsubmit="saveAccount(this);return false;">
      <input type="hidden" name="section" value="bank"/>      
      <?php /*?><div class="form-group row">
		<label class="form-label"> Bank Name </label>
		<div class="col-md-9">
			<input type="text" name="bank_account_name" id="bank_account_name" value="<?php if($member_details && $member_details['member_payment_settings']){D($member_details['member_payment_settings']->bank_account_name);} ?>" placeholder="Enter Account/Owner Name" class="form-control" >
			<span id="bank_account_nameError" class="rerror"></span>
		</div>
	</div><?php */?>
    <div class="row">
        <div class="col-sm-6">
          <div class="form-field">
          <label class="form-label">
          	<?php D(__('account_settings_page_Bank_Name',"Bank Name"));?>
          </label>
          <input type="text" name="bank_name" id="bank_name" value="<?php if($member_details && $member_details['member_payment_settings']){D($member_details['member_payment_settings']->bank_name);} ?>" placeholder="<?php D(__('account_settings_page_Bank_Name_input',"Enter Bank Name"));?>" class="form-control" >
          	<span id="bank_nameError" class="rerror"></span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-field">
          	<label class="form-label">
          <?php D(__('account_settings_page_Bank_Swift_Code',"Swift Code"));?>
        </label>
        <input type="text" name="bank_code" id="bank_code" value="<?php if($member_details && $member_details['member_payment_settings']){D($member_details['member_payment_settings']->bank_code);} ?>" placeholder="<?php D(__('account_settings_page_Bank_Swift_Code_input',"Enter Swift Code"));?>" class="form-control" >
          <span id="bank_codeError" class="rerror"></span>
          </div>
        </div>
	</div>
	<div class="row">
        <div class="col-sm-6">
          <div class="form-field">
          	<label class="form-label">
          <?php D(__('account_settings_page_Bank_IBAN',"IBAN"));?>
        </label>
        
          <input type="text" name="bank_account_number" id="bank_account_number" value="<?php if($member_details && $member_details['member_payment_settings']){D($member_details['member_payment_settings']->bank_account_number);} ?>" placeholder="<?php D(__('account_settings_page_Bank_IBAN_input',"Enter IBAN"));?>" class="form-control"  >
          <span id="bank_account_numberError" class="rerror"></span> 
          </div>
        </div>        
	</div>
	<button type="submit" name="submit_bank" class="btn btn-dark saveBTN">
	  <?php D(__('account_settings_page_Bank_button',"Update Bank Details"));?>
    </button>                        
    </form>

<?php if($enable_payoneer == 1){ ?>

    <h5>
      <?php D(__('account_settings_page_Payoneer_heading',"Payoneer For Withdrawing Revenue"));?>
    </h5>
    <form action="" method="post" accept-charset="utf-8" id="accountPayoneerform" role="form" name="accountPayoneerform" onsubmit="saveAccount(this);return false;">
      <input type="hidden" name="section" value="payoneer"/>
      <div class="form-group">
        <label class="form-label">
          <?php D(__('account_settings_page_Payoneer_Email',"Enter Email"));?>
        </label>        
          <input type="text" name="seller_payoneer_email" id="seller_payoneer_email" value="<?php if($member_details && $member_details['member_payment_settings']){D($member_details['member_payment_settings']->payoneer_email);} ?>" placeholder="<?php D(__('account_settings_page_Payoneer_Email_input',"Enter Email"));?>" class="form-control" >
          <span id="seller_payoneer_emailError" class="rerror"></span>
      </div>
      <button type="submit" name="submit_payoneer_email" class="btn btn-dark saveBTN">
          <?php D(__('account_settings_page_payoneer_button',"Change  Email"));?>
          </button>
      
    </form>

<?php }?>
<div class="d-none">
  <?php if($enable_dusupay == 1){ ?>
  <h5 class="mb-4">
    <?php D(__('account_settings_page_Mobile_Money_heading',"Mobile Money For Withdrawing Revenue"));?>
  </h5>
  <form action="" method="post" accept-charset="utf-8" id="accountMmoneyform" class="form-horizontal" role="form" name="accountMmoneyform" onsubmit="saveAccount(this);return false;">
    <input type="hidden" name="section" value="m_money"/>
    <div class="form-group row">
      <label class="form-label">
        <?php D(__('account_settings_page_Mobile_Money_Account_Number',"Account Number"));?>
      </label>
      <div class="col-md-9">
        <input type="text" name="m_account_number" id="m_account_number" value="<?php if($member_details && $member_details['member_payment_settings']){D($member_details['member_payment_settings']->mobile_money_account_number);} ?>" placeholder="<?php D(__('account_settings_page_Mobile_Money_Account_Number_input',"Enter Account Number"));?>" class="form-control"  >
        <span id="m_account_numberError" class="rerror"></span> </div>
    </div>
    <div class="form-group row">
      <label class="form-label">
        <?php D(__('account_settings_page_Mobile_Money_Account_Owner',"Account/Owner Name"));?>
      </label>
      <div class="col-md-9">
        <input type="text" name="m_account_name" id="m_account_name" value="<?php if($member_details && $member_details['member_payment_settings']){D($member_details['member_payment_settings']->mobile_money_account_name);} ?>" placeholder="<?php D(__('account_settings_page_Mobile_Money_Account_Owner_input',"Enter Account/Owner Name"));?>" class="form-control" >
        <span id="m_account_nameError" class="rerror"></span> </div>
    </div>
    <div class="row">
      <div class="col-md-9 offset-md-3">
        <button type="submit" name="update_mobile_money" class="btn btn-site saveBTN">
        <?php D(__('account_settings_page_Mobile_Money_button',"Update Mobile Money"));?>
        </button>
      </div>
    </div>
  </form>
  <hr>
  <?php }?>
  <?php if($enable_coinpayments == 1){ ?>
  <h5 class="mb-4">
    <?php D(__('account_settings_page_Bitcoin_heading',"Bitcoin Wallet For Withdrawing Revenue"));?>
  </h5>
  <form action="" method="post" accept-charset="utf-8" id="accountBitcoinform" class="form-horizontal" role="form" name="accountBitcoinform" onsubmit="saveAccount(this);return false;">
    <input type="hidden" name="section" value="bitcoin"/>
    <div class="form-group row">
      <label class="form-label">
        <?php D(__('account_settings_page_Bitcoin_Address',"Wallet Address"));?>
      </label>
      <div class="col-md-9">
        <input type="text" name="bitcoin_seller_wallet" id="bitcoin_seller_wallet" value="<?php if($member_details && $member_details['member_payment_settings']){D($member_details['member_payment_settings']->bitcoin_wallet_address);} ?>" placeholder="<?php D(__('account_settings_page_Bitcoin_Address_input',"Enter Wallet Address"));?>" class="form-control"/>
        <span id="bitcoin_seller_walletError" class="rerror"></span> <small class="text-danger">
        <?php D(__('account_settings_page_Bitcoin_Address_note',"! Warning You Only Need To Enter Your Bitcoin Wallet Address Not Any Other."));?>
        </small> </div>
    </div>
    <div class="row">
      <div class="col-md-9 offset-md-3">
        <button type="submit" name="submit_wallet" class="btn btn-site saveBTN">
        <?php D(__('account_settings_page_Bitcoin_button',"Update Wallet Address"));?>
        </button>
      </div>
    </div>
  </form>
  <hr>
  <?php }?>
</div>
<div style="display: none">
  <h5 class="mb-4"> REAL-TIME NOTIFICATIONS </h5>
  <form method="post" class="clearfix">
    <div class="form-group row mb-3">
      <label class="form-label"> Enable/disable sound </label>
      <div class="col-md-9">
        <select name="enable_sound" class="form-control">
          <option value="yes"> Yes </option>
          <option value="no"> No </option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9 offset-md-3">
        <button type="submit" name="update_sound" class="btn btn-site mt-1 float-right">Update Changes</button>
      </div>
    </div>
  </form>
  <hr>
</div>
</div>
</div>
<div class="card mb-4">
  <div class="card-body">
  <h5>
      <?php D(__('account_settings_page_Change_Password_heading',"Change Password"));?>
    </h5>
    <form action="" method="post" accept-charset="utf-8" id="accountPasswordform" class="form-horizontal" role="form" name="accountPasswordform" onsubmit="saveAccount(this);return false;">
      <input type="hidden" name="section" value="password"/>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-field">
          <label class="form-label">
          	<?php D(__('account_settings_page_Change_Password_Old_Password',"Enter Old Password"));?>
          </label>
          <input type="password" name="old_pass" id="old_pass" class="form-control" >
          <span id="old_passError" class="rerror"></span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-field">
          <label class="form-label">
          	<?php D(__('account_settings_page_Change_Password_New_Password',"Enter New Password"));?>
          </label>
          <input type="password" name="new_pass" id="new_pass" class="form-control"  >
          <span id="new_passError" class="rerror"></span>
          </div>
        </div>
      </div>            
      <div class="row">        
        <div class="col-sm-6">
        <div class="form-field">
          <label class="form-label">
          	<?php D(__('account_settings_page_Change_Password_Confirm_Password',"Confirm New Password"));?>
          </label>
          <input type="text" name="new_pass_again" id="new_pass_again" class="form-control"  >
          <span id="new_pass_againError" class="rerror"></span>
        </div>
        </div>
      </div>
      <button type="submit" name="change_password" class="btn btn-dark saveBTN">
          <?php D(__('account_settings_page_Change_Password_button',"Change Password"));?>
          </button>
    </form>
  </div>
</div>
<div class="card mb-4">
  <div class="card-body">
  <h5>
      <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_heading',"Account Deactivation"));?>
    </h5>
    <div class="form-field">
      <label class="form-label">
        <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_text',"What happens when you deactivate your account?"));?>
      </label>
        <ul class="list-2 mb-3">
          <li>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_info_1',"Your profile and services won't be shown on ".$get_site_name." anymore."));?>
          </li>
          <li>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_info_2',"Any open orders will be cancelled and refunded."));?>
          </li>
          <li>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_info_3',"You won\'t be able to re-activate your proposals/services."));?>
          </li>
          <li>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_info_4',"You won\'t be able to restore your account."));?>
          </li>
        </ul>      
    </div>
    <?php 

        if(!$current_balance == 0){

    ?>
    <div class="form-group">      
      <h5 class="pt-3 pb-3">
        <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_withdraw',"Please withdraw your revenues before deactivating your account."));?>
      </h5>
    </div>
    <!-- form-group Ends -->
    
    <button type="submit" name="deactivate_account" disabled class="btn btn-danger"> <i class="fa fa-frown-o"></i>
    <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_button',"Deactivate Account"));?>
    </button>
    <?php }elseif($current_balance == 0){ ?>
    <form action="" method="post" accept-charset="utf-8" id="accountDeactivateform" class="form-horizontal" role="form" name="accountDeactivateform" onsubmit="saveAccount(this);return false;">
      <input type="hidden" name="section" value="deactivate"/>
      <div class="form-group">
        <label class="form-label">
          <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason',"Why Are You Leaving?"));?>
        </label>
          <select name="deactivate_reason" id="deactivate_reason" class="form-control">
            <option class="hidden">
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_select',"Choose A Reason"));?>
            </option>
            <option>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_option_1',"The quality of service was less than expected"));?>
            </option>
            <option>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_option_2',"I just don\'t have the time"));?>
            </option>
            <option>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_option_3',"I can’t find what I am looking for"));?>
            </option>
            <option>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_option_4',"I had a bad experience with a seller / buyer"));?>
            </option>
            <option>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_option_5',"I found the site difficult to use"));?>
            </option>
            <option>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_option_6',"The level of customer service was less than expected"));?>
            </option>
            <option>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_option_7',"I have another ".$get_site_name." account"));?>
            </option>
            <option>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_option_8',"I\'m not receiving enough orders"));?>
            </option>
            <option>
            <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_reason_option_9',"Other"));?>
            </option>
          </select>
          <span id="deactivate_reasonError" class="rerror"></span> 
      </div>
      <button type="submit" name="deactivate_account" class="btn btn-danger saveBTN">
          <?php D(__('account_settings_page_ACCOUNT_DEACTIVATION_button',"Deactivate Account"));?>
          </button>
    </form>
    <?php } ?>
  </div>
</div>
