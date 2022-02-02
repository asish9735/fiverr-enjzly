<!-- Registration Modal starts -->
<div class="modal fade" id="register-modal" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"><!-- modal-header Starts -->
				<h4 class="modal-title"> <?php D(__('modal_register_heading',"Sign Up"));?></h4>
				<button class="close" data-dismiss="modal"><span>&times;</span></button>
			</div><!-- modal-header Ends -->
			<div class="modal-body"><!-- modal-body Starts -->
				<form action="" method="post" accept-charset="utf-8" id="singupform" class="form-horizontal" role="form" name="singupform" onsubmit="saveRegister(this);return false;"> 
					<div class="form-group d-none">
					
					<label class="custom-control custom-radio">
						<input type="radio" value="1" id="is_freelancer_1" name="is_freelancer" class="custom-control-input" onclick="setProfileType('1')">
						<span class="custom-control-indicator"></span>
						<span class="custom-control-description"> <?php D(__('modal_register_user_type_freelancer',"I want to provide a service "));?></span>
					</label>
					<label class="custom-control custom-radio">
						<input type="radio" value="0" id="is_freelancer_0" name="is_freelancer" class="custom-control-input" checked onclick="setProfileType('0')">
						<span class="custom-control-indicator"></span>
						<span class="custom-control-description">  <?php D(__('modal_register_user_type_employer',"I want to buy a service"));?> </span>
					</label>
					</div>
					<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_Gender',"Gender:"));?> </label>
                    <div class="custom-control custom-radio">
                      <input type="radio" value="F" id="gender_female" name="gender" class="custom-control-input" checked>
                      <label class="custom-control-label" for="gender_female"><?php D(__('modal_register_Female',"Female"));?></label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" value="M" id="gender_male" name="gender" class="custom-control-input" >
                      <label class="custom-control-label" for="gender_male"><?php D(__('modal_register_Male',"Male"));?></label>
                    </div>
					</div>
					<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_full_name',"Full Name:"));?> </label>
					<input type="text" class="form-control" name="name" id="name" placeholder="<?php D(__('modal_register_full_name_input',"Enter Your Full Name"));?>" value="" required1>
					<span id="nameError" class="rerror"></span>
					</div>
					<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_username',"Username:"));?> </label>
					<input type="text" class="form-control" name="u_name" id="u_name" placeholder="<?php D(__('modal_register_username_input',"Enter Your Username"));?>" value="" required1>
					<small class="form-text text-muted">
					<?php D(__('modal_register_username_info',"Note: You will not be able to change username once your account has been created."));?>
					</small>
					<span id="u_nameError" class="rerror"></span>
					</div>
					<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_email',"Email:"));?> </label>
					<input type="email" class="form-control" name="email" id="email" placeholder="<?php D(__('modal_register_email_input',"Enter Email"));?>" value="" required1>
					<span id="emailError" class="rerror"></span>
					</div>
					<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_password',"Password:"));?> </label>
					<input type="password" class="form-control" name="pass" id="pass" placeholder="<?php D(__('modal_register_password_input',"Enter Password"));?>" required1>
					<span id="passError" class="rerror"></span>
					</div>

					<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_confirm_password',"Confirm Password:"));?> </label>
					<input type="password" class="form-control" name="con_pass" id="con_pass" placeholder="<?php D(__('modal_register_confirm_password_input',"Confirm Password"));?>" required1>
					<span id="con_passError" class="rerror"></span>
					</div>
					<div class="form-group">
						<label class="form-label"> <?php D(__('modal_register_phone',"Phone:"));?> </label>
						<div class="input-group">
							<div class="input-group-prepend">
							<select class="form-control input-group-text bg-white" name="seller_mobile_code" id="seller_mobile_code" style="width:85px" >
								<option value=""><?php D(__('modal_register_phone_option_select_phone_',"Code"));?></option>
								<?php 
								if($all_mobile_codes){
									foreach($all_mobile_codes as $k=>$codes){
								?>
								<option value="<?php D($codes->codes); ?>"><?php D($codes->codes); ?> </option>
								<?php	
									}
								}
							?>

								</select>
							</div>
							<input name="seller_phone" id="seller_phone"  class="form-control" value="">
						</div>
						<span id="seller_phoneError" class="rerror"></span>
					</div>
					<div class="form-group is_freelancer_section" style="display: none">
					<label class="form-label"> <?php D(__('modal_register_phone',"Phone:"));?> </label>
					<input type="text" class="form-control" name="phone" id="phone" placeholder="<?php D(__('modal_register_phone_input',"Enter Phone"));?>" value="" required1>
					<span id="phoneError" class="rerror"></span>
					</div>
					<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_nationality',"Nationality:"));?> </label>
					<select name="country" id="country" class="form-control">
						<option value="" class="hidden"> <?php D(__('modal_register_nationality_option_select_nationality',"Select Nationality"));?> </option> </option>
						<?php 
						if($all_country){
							foreach($all_country as $k=>$country){
						?>
						<option value="<?php D($country->country_code); ?>"><?php D($country->country_name); ?> </option>
						<?php	
							}
						}
					?>
					</select>
					<span id="countryError" class="rerror"></span>
					</div>
					
					<!--<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_nationality',"Nationality:"));?> </label>
					<select name="nationality" id="nationality" class="form-control">
						<option value="" class="hidden"> <?php D(__('modal_register_nationality_option_select_nationality',"Select Nationality"));?> </option>
						<?php 
						if($all_nationality){
							foreach($all_nationality as $k=>$nationality){
						?>
						<option value="<?php D($nationality->nationality_id); ?>"><?php D($nationality->nationality_name); ?> </option>
						<?php	
							}
						}
					?>
					</select>
					<span id="nationalityError" class="rerror"></span>
					</div>-->
					<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_country',"Country:"));?> </label>
					<select name="nationality" id="nationality" class="form-control">
						<option value="" class="hidden"> <?php D(__('modal_register_country_option_select_country',"Select Country"));?> </option>
						<?php 
						if($all_country){
							foreach($all_country as $k=>$country){
						?>
						<option value="<?php D($country->country_code); ?>" <?php if($member_details && $member_details['member_address'] && $member_details['member_address']->member_country==$country->country_code){D('selected');} ?>><?php D($country->country_name); ?> </option>
						<?php	
							}
						}
					?>
					</select>
					<span id="nationalityError" class="rerror"></span>
					</div>
					<div class="form-group">
					<label class="form-label"> <?php D(__('modal_register_city',"City of Resident:"));?> </label>
					<input type="text" class="form-control" name="city" id="city" placeholder="<?php D(__('modal_register_city_input',"Enter City of Resident"));?>" value="">
					<span id="cityError" class="rerror"></span>
					</div>
					<input type="hidden" class="form-control" name="referral" value="<?php D($referral);?>">
					<button type="submit" class="btn btn-site btn-block saveBTN" ><?php D(__('modal_register_button_submit',"Register Now"));?></button>
				</form>
				
			<?php if($enable_social_login == 1){ ?>
					<div class="clearfix"></div>
					<div class="text-center"><?php D(__('modal_register_social_info',"or, register with either:"));?></div>
					<hr class="">
					<div class="line mt-3">
						<span></span>
					</div>
					<div class="text-center">
						<a href="#" onclick="window.location = '<?php echo $fLoginURL ?>';" class="btn btn-primary btn-fb-connect" >
							<i class="fa fa-facebook"></i> <?php D(__('modal_register_social_FACEBOOK',"FACEBOOK"));?>
						</a>
						<a href="#" onclick="window.location = '<?php echo $gLoginURL ?>';" class="btn btn-danger btn-gplus-connect " >
						<i class="fa fa-google-plus"></i> <?php D(__('modal_register_social_GOOGLE',"GOOGLE"));?>
						</a>
					</div>			

					<div class="clearfix"></div>
			<?php } ?>
				<div class="text-center mt-3 text-muted">
					<?php D(__('modal_register_login_info',"Already Have An Account?"));?>
					<a href="#" class="text-success" data-toggle="modal" data-target="#login-modal" data-dismiss="modal"><?php D(__('modal_register_login_button',"Log In."));?></a>
				</div>
			</div><!-- modal-body Ends -->
		</div>
	</div>
</div>


<!-- Registration modal ends -->





<!-- Login modal start -->

<div class="modal fade login" id="login-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"> <!-- Modal header start -->
				<h4 class="modal-title"><?php D(__('modal_login_heading',"Log In"));?></h4>
				<button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
			</div> <!-- Modal header end -->

			<div class="modal-body"> <!-- Modal body start -->
				<form action="" method="post" accept-charset="utf-8" id="loginform" class="form-horizontal" role="form" name="loginform" onsubmit="saveLogin(this);return false;"> 

					<div class="form-group">
						<label class="form-label"> <?php D(__('modal_login_username',"Username:"));?></label>
						<input type="text" class="form-control" name="seller_user_name" id="seller_user_name" placeholder="<?php D(__('modal_login_username_input',"Enter Username"));?>"  value= "">
						<span id="seller_user_nameError" class="rerror"></span>
					</div>
					<div class="form-group">
						<label class="form-label"> <?php D(__('modal_login_password',"Password:"));?></label>
						<input type="password" class="form-control" name="seller_pass" id="seller_pass" placeholder="<?php D(__('modal_login_password_input',"Enter Password"));?>" >
						<span id="seller_passError" class="rerror"></span>
					</div>
					<button type="submit" class="btn btn-site btn-block saveBTN" ><?php D(__('modal_login_button_submit',"Login Now"));?></button>
				</form>

<?php if($enable_social_login == 1){ ?>
					<div class="clearfix"></div>
					<div class="text-center pt-4 pb-2"><?php D(__('modal_login_or',"OR"));?></div>
					<hr class="">
					<div class="line mt-3">
						<span></span>
					</div>
					<div class="text-center">
						<a href="#" onclick="window.location = '<?php echo $fLoginURL ?>';" class="btn btn-primary btn-fb-connect" >
							<i class="fa fa-facebook"></i> <?php D(__('modal_login_social_FACEBOOK',"FACEBOOK"));?>
						</a>
						<a href="#" onclick="window.location = '<?php echo $gLoginURL ?>';" class="btn btn-danger btn-gplus-connect " >
						<i class="fa fa-google-plus"></i> <?php D(__('modal_login_social_GOOGLE',"GOOGLE"));?>
						</a>
					</div>			
					<div class="clearfix"></div>
			<?php } ?>
				<div class="text-center mt-3">
					<p>Don't have an account? <a href="#" data-toggle="modal" data-target="#register-modal"><?php D(__('login_page_signup',"Sign Up"));?></a></p>
					<p><a href="#" class="text-success" data-toggle="modal" data-target="#forgot-modal" data-dismiss="modal"><?php D(__('modal_login_forgot_password_button',"Forgot Password?"));?></a></p>
				</div>
			</div><!-- Modal body ends -->
		</div>
	</div>
</div> <!-- Login modal end -->


<!-- Forgot password starts -->
<div class="modal fade login" id="forgot-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"> <!-- Modal header start -->
				<h4 class="modal-title"><?php D(__('modal_forgot_heading',"Login To Your Account"));?></h4>
				<button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
			</div> <!-- Modal header end -->
			<div class="modal-body"> <!-- Modal body start -->
				<p class="text-muted mb-2"><?php D(__('modal_forgot_heading_info',"Enter your email to receive a password reset link."));?></p>
				<form action="" method="post" accept-charset="utf-8" id="forgotform" class="form-horizontal" role="form" name="forgotform" onsubmit="saveForgot(this);return false;"> 
					<div class="form-group">
						<input type="email" class="form-control" name="forgot_email" id="forgot_email" placeholder="<?php D(__('modal_forgot_email_input',"Enter Email"));?>"  value= "">
						<span id="forgot_emailError" class="rerror"></span>
					</div>
					<button type="submit" class="btn btn-site btn-block saveBTN" ><?php D(__('modal_forgot_button_submit',"Send"));?></button>
					<p class="text-muted text-center mt-4">
						<?php D(__('modal_forgot_register',"Not A Member Yet?"));?>
						<a href="#" class="text-success" data-toggle="modal" data-target="#register-modal" data-dismiss="modal">
							<?php D(__('modal_forgot_register_button',"Join Now."));?>
						</a>
					</p>
				</form>
			</div><!-- Modal body ends -->
		</div>
	</div>
</div> <!-- Forgot password ends -->

<script type="text/javascript">
var SPINNER='<?php load_view('inc/spinner',array('size'=>20));?>';
function setProfileType(ptype){
	if(ptype==1){
		$('.is_freelancer_section').show();
	}else{
		$('.is_freelancer_section').hide();
	}
}
	function saveRegister(ev){
		var formID="singupform";
		var buttonsection=$('#'+formID).find('.saveBTN');
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('registerURLAJAX'))?>/",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#register-modal').modal('hide');
					  swal({
		                  type: 'success',
		                  text: '<?php D(__('modal_register_success_message','Successfully Registered! Welcome onboard'))?>, '+msg['name']+'. ',
		                  timer: 6000,
		                  onOpen: function(){
		                    	swal.showLoading()
		                  }
	                  }).then(function(){
	                  	window.location.href=msg['redirect'];
	                })
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})	
	}
	function saveLogin(ev){
		var formID="loginform";
		var buttonsection=$('#'+formID).find('.saveBTN');
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('loginURLAJAX'))?>/",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#login-modal').modal('hide');
					if(msg['is_block'] == '1'){
						 swal({
				              type: 'warning',
				              html: $('<div>')
				                .text('<?php D(__('modal_login_member_block','You have been blocked by the Admin. Please contact customer support.'))?>'),
				              animation: false,
				              customClass: 'animated tada'
				            })
					}else{
						 swal({
		                  type: 'success',
		                  text: '<?php D(__('modal_login_success_Hey','Hey'))?> '+msg['name']+', <?php D(__('modal_login_success_welcome_back','welcome back!'))?>',
		                  timer: 2000,
		                  onOpen: function(){
		                    swal.showLoading()
		                  }
		                  }).then(function(){
		                  	window.location.href=msg['redirect'];
		                })
					}
				} else if (msg['status'] == 'FAIL') {
					$('#login-modal').modal('hide');
					swal({
		              type: 'warning',
		              html: $('<div>')
		                .text('<?php D(__('modal_login_error_message','Opps! password or username is incorrect. Please try again.'))?>'),
		              animation: false,
		              customClass: 'animated tada'
		            }).then(function(){
		                $('#login-modal').modal('show');
		            })
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})	
	}
	function saveForgot(ev){
		var formID="forgotform";
		var buttonsection=$('#'+formID).find('.saveBTN');
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('forgotURLAJAX'))?>/",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#forgot-modal').modal('hide');
						 swal({
		                  type: 'success',
		                  text: '<?php D(__('modal_forgot_success_message','An email has been sent to your email address with instructions on how to change your password.'))?>',
		                  timer: 2000,
		                  onOpen: function(){
		                    swal.showLoading()
		                  }
		                  }).then(function(){
		                  	window.location.href=msg['redirect'];
		                })
				} else if (msg['status'] == 'FAIL') {
					$('#forgot-modal').modal('hide');
					swal({
		              type: 'warning',
		              html: $('<div>')
		                .text('<?php D(addslashes(__('modal_forgot_error_message',"Hmm! We don\'t seem to have this email in our system.")))?>'),
		            }).then(function(){
		                $('#forgot-modal').modal('show');
		            })
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})	
	}
</script>