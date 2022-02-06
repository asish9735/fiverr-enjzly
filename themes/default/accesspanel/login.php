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
		<div class="col-lg-6 col-md-7 col-12">			
			<div class="box-login">
            	<div>
				<?php /*?><a href="<?php D(VPATH);?>" class="logo" title="Home">
					<img src="<?php echo theme_url().IMAGE.LOGO_NAME;?>" alt="<?php D($website_name);?>">
				</a><?php */?>
				<h1><?php D(__('login_page_heading',"Login To"));?> <?php // D(get_option_value('website_name')); ?></h1>
                <p>Don't have an account? <a href="<?php D(get_link('registerURL'))?>"><?php D(__('login_page_signup',"Sign Up"));?></a></p>
				<form action="" method="post" accept-charset="utf-8" id="signinform" class="form-horizontal" role="form" name="signinform" onsubmit="saveSignin(this);return false;"> 
					<div class="mb-4">
						<input type="text" name="seller_user_name" id="seller_user_name" class="form-control" placeholder= "<?php D(__('login_page_username_input',"Enter Username"));?>" >	
						<span id="seller_user_nameError" class="rerror"></span>			
                    </div>
                    <div class="mb-4">
						<input type="password" name="seller_pass" id="seller_pass" class="form-control" placeholder="<?php D(__('login_page_password_input',"Enter Password"));?>" >
						<span id="seller_passError" class="rerror"></span>
                    </div>
                    <div class="mb-4">
                    	<button type="submit" class="btn btn-site btn-block saveBTN" ><?php D(__('login_page_button_submit',"Login"));?></button>
                    </div>
				</form>
				
				<div class="mt-3">										
                    <p class="text-center mb-0"><a href="<?php D(get_link('ForgotPasswordURL'))?>"><?php D(__('login_page_forgot_password_button',"Forgot Password?"));?></a></p>
             	</div>
				<?php if($enable_social_login == 0){ ?>
                    <div class="social-login-separator"><span><?php D(__('login_page_or',"OR"));?></span></div>						
                    <div class="social-login-buttons">
                    <a href="#" onclick="window.location = '<?php echo $fLoginURL ?>';" class="facebook-login" >
                    <i class="fa fa-facebook"></i> Log In via <?php D(__('login_page_social_FACEBOOK',"FACEBOOK"));?>
                    </a>
                    <a href="#" onclick="window.location = '<?php echo $gLoginURL ?>';" class="google-login">
                    <i class="fa fa-google-plus"></i> Log In via <?php D(__('login_page_social_GOOGLE',"GOOGLE"));?>
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
var SPINNER='<?php load_view('inc/spinner',array('size'=>20));?>';
	function saveSignin(ev){
		var formID="signinform";
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
				
				clearErrors();
				if (msg['status'] == 'OK') {
					if(msg['is_block'] == '1'){
					bootbox.alert({
						title:'Login ',
						message: '<?php D(__('login_page_member_block',"You have been blocked by the Admin. Please contact customer support."));?>',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-site pull-right'
							}
						},
						callback: function () {
							
					    }
					});

					}else{
						window.location.href=msg['redirect'];
						return false;
					}
					
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
					
				}
				buttonsection.html(buttonval).removeAttr('disabled');
			}
		})	
	}

</script>