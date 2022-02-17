<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$enable_social_login=get_option_value('enable_social_login');
?>
<!--<style>
header, footer, .post_footer{
	display: none;
}
</style>-->
<section class="login">
<div class="container">
	<div class="row justify-content-center1">
		<div class="col-lg-6 col-md-8 col-12">			
			<div class="box-login">
            	<div>
            	<!--<a href="<?php D(VPATH);?>" class="logo" title="Home">
					<img src="<?php echo theme_url().IMAGE.LOGO_NAME;?>" alt="<?php D($website_name);?>">
				</a>-->
				<h1><?php D(__('signup_page_heading',"Sign Up"));?> <?php // D(get_option_value('website_name')); ?></h1>
                <p>Already have an account? <a href="<?php echo get_link('loginURL'); ?>">Log In</a></p>	
				<form action="" method="post" accept-charset="utf-8" id="singupform" class="form-horizontal" role="form" name="singupform" onsubmit="saveRegister(this);return false;"> 
				<input type="hidden" name="ref" value="<?php D(get('ref'));?>"/>
    			<input type="hidden" name="refer" value="<?php D(get('refer'));?>" readonly/>
                <div class="row">
                	<div class="col-lg-6 col-12">
                        <div class="form-group">
                            <label class="form-label"> <?php D(__('register_full_name',"Name:"));?> </label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="<?php D(__('register_full_name_input',"Enter Your Full Name"));?>" value="" required1>
                            <span id="nameError" class="rerror"></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">                    	    
                        <div class="form-group">
                            <label class="form-label"><?php D(__('register_username',"Username:"));?> </label>
                            <div class="position-relative">
                            	<input type="text" class="form-control" name="u_name" id="u_name" placeholder="<?php D(__('register_username_input',"Enter Your Username"));?>" value="" required>
                            	<a class="popover-info" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="" data-content="<?php D(__('register_username_info',"Note: You will not be able to change username later."));?>"><i class="icon-feather-info"></i></a> 
                            </div>                           
                            <span id="u_nameError" class="rerror"></span>
                        </div>
                    </div>
                </div>	
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            <label class="form-label"> <?php D(__('register_email',"Email:"));?> </label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="<?php D(__('register_email_input',"Enter Email"));?>" value="" required1>
                            <span id="emailError" class="rerror"></span>
                        </div>	
                    </div>
                    <div class="col-lg-6 col-12">    
                        <div class="form-group">
                            <label class="form-label"> <?php D(__('register_password',"Password:"));?> </label>
                            <input type="password" class="form-control" name="pass" id="pass" placeholder="<?php D(__('register_password_input',"Enter Password"));?>" required1>
                            <span id="passError" class="rerror"></span>
                        </div>	
                    </div>
                </div>
				<div class="form-group">
					<label class="form-label"> <?php D(__('register_country',"Country:"));?> </label>
					<select name="country" id="country" class="form-control">
						<option value="" class="hidden"> <?php D(__('register_country_option_select_country',"Select Country"));?> </option> </option>
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
					
                <button type="submit" class="btn btn-site btn-block saveBTN" ><?php D(__('signup_page_button_submit',"Submit"));?></button>
				</form>
                
				<?php if($enable_social_login == 0){ ?>
                    <div class="social-login-separator"><span><?php D(__('login_page_or',"OR"));?></span></div>						
                    <div class="social-login-buttons">
                    <a href="#" onclick="window.location = '<?php echo $fLoginURL ?>';" class="facebook-login" >
                    <i class="fa-brands fa-facebook"></i> Log In via <?php D(__('login_page_social_FACEBOOK',"FACEBOOK"));?>
                    </a>
                    <a href="#" onclick="window.location = '<?php echo $gLoginURL ?>';" class="google-login">
                    <i class="fa-brands fa-google"></i> Log In via <?php D(__('login_page_social_GOOGLE',"GOOGLE"));?>
                    </a>
                    </div>									
				<?php } ?>
        				
   				</div>
            </div>


		</div>
	</div>
</div>
</section>

<script type="text/javascript">
$(function () {
  $('[data-toggle="popover"]').popover()
});
var SPINNER='<?php load_view('inc/spinner',array('size'=>20));?>';
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
			
			clearErrors();
			if (msg['status'] == 'OK') {
				$('#register-modal').modal('hide');
				bootbox.alert({
					title:'Register ',
					message: '<?php D(__('modal_register_success_message',"Successfully Registered! A link sent to your email id please verify your email id."));?>',
					buttons: {
					'ok': {
						label: 'Ok',
						className: 'btn-site pull-right'
						}
					},
					callback: function () {
						window.location.href=msg['redirect'];
				    }
				});
			} else if (msg['status'] == 'FAIL') {
				registerFormPostResponse(formID,msg['errors']);
				buttonsection.html(buttonval).removeAttr('disabled');
			}
			
		}
	})	
}

</script>