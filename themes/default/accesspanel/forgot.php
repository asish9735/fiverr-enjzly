<style>
header, footer, .post_footer{
	display: none;
}
</style>
<section class="section">
<div class="container">
	<div class="row justify-content-center">
		<div class="col-lg-5 col-md-7">			
		<div class="box-login">
        	<a href="<?php D(VPATH);?>" class="logo" title="Home">
                <img src="<?php echo theme_url().IMAGE.LOGO_NAME;?>" alt="<?php D($website_name);?>">
            </a>
            <form action="" method="post" accept-charset="utf-8" id="forgotform" class="form-horizontal" role="form" name="forgotform" onsubmit="saveForgotpassword(this);return false;">              
           <h1 class="text-center m-0">Forgot Password</h1>
           <div class="m-lg-3 d-none d-sm-block"> </div>
           <div id="agree_termsError" class="error-msg5 error alert-error alert alert-danger" style="display:none"></div>
             <div class="form-group">
                            <input type="email" class="form-control" name="forgot_email" id="forgot_email" placeholder="<?php D(__('modal_forgot_email_input',"Enter Email"));?>"  value= "">
                            <span id="forgot_emailError" class="rerror"></span>
                </div>                                         
            <button class="btn btn-site btn-block mb-3 saveBTN" id="submit-btn">Submit</button>
            </form>
        
        <div class="text-center mt-3">
        <p>Don't have an account? <a href="<?php echo get_link('registerURL'); ?>">Register Now</a></p>
		 </div>


		</div>
	</div>
</div>
</div>
</section>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
function saveForgotpassword(ev){
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
			
			clearErrors();
			if (msg['status'] == 'OK') {
					bootbox.alert({
						title:'Forgot Password',
						message: '<?php D(__('Forgot_password_success_message','An email has been sent to your email address with instructions on how to change your password.'));?>',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-site pull-right'
							}
						},
						callback: function () {
							if(msg['redirect']){
									window.location.href=msg['redirect'];
								return false;
							}
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