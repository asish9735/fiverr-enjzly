<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo get_setting('site_title')?>| Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="<?php echo ADMIN_IMAGES;?>favicon.png" type="image/x-icon">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo ADMIN_COMPONENT;?>bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo ADMIN_COMPONENT;?>font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo ADMIN_COMPONENT;?>Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo ADMIN_CSS;?>adminLTE.min.css">
  <link rel="stylesheet" href="<?php echo ADMIN_CSS;?>style.css">
  <script src="<?php echo ADMIN_COMPONENT; ?>jquery/dist/jquery.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->

  <!-- Google Font -->

  <style>
.invalid {
	border: 1px solid red;
}
.error {
	color: red;
}
</style>
</head>
<body class="hold-transition login-page">
  
	<div class="login-box">  
    <div id="ajax_status"></div>
    <div class="login-box-body" id="login_form_wrapper">
        <div class="login-logo">
        <a href="#"><img src="<?php echo ADMIN_IMAGES;?>logo.png" alt="<?php echo get_setting('site_title')?>"></a>
        </div>
    <p class="login-box-msg">Sign in to start your session</p>
    <?php

	$login_str = get_cookie('l_info');

	if($login_str){

		$login_info = unserialize($login_str);

	}else{

		$login_info = array();

	}

	?>
    <form onsubmit="login.checkLogin(this, event)">
        <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo !empty($login_info['uname']) ? $login_info['uname'] : '';?>">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span> </div>
        <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password" value="<?php echo !empty($login_info['pwd']) ? $login_info['pwd'] : '';?>">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div id="loginError" class="error"></div>
      </div>

        <?php /*?><div class="checkbox">
        <label>
            <input type="checkbox" name="remember_me" value="1" <?php echo !empty($login_info['uname']) ? 'checked' : '';?>>
            Remember Me </label>
        </div><?php */?>

        <button type="submit" class="btn btn-site btn-block">Sign In</button>      
      </form>
      
      <div class="social-auth-links text-center hidden" hidden>
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
        <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
      </div>
    
    <!-- /.social-auth-links --> 
    <br>
    <p class="text-center"><a href="javascript:void(0)"  onclick="forgot_password.open();">I forgot my password</a></p>
  </div>
    <div class="login-box-body" id="forget_password_wrapper" style="display:none;">
        <div class="login-logo">
        <a href="#"><img src="<?php echo ADMIN_IMAGES;?>logo.png" alt="<?php echo get_setting('site_title')?>"></a>
        </div>
    	<p class="login-box-msg">Enter your email id below to get reset link</p>
        <form onsubmit="forgot_password.checkEmail(this, event)">
            <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Email" name="email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span> </div>
            <div class="form-group has-feedback"> <a href="javascript:void(0)" onclick="login.open();">Back to login</a><br>
          </div>
            <div class="row"> 
            
            <!-- /.col -->
            
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
              </div>
            
            <!-- /.col --> 
            
          </div>
      </form>
	</div>
	</div>


<!-- Bootstrap 3.3.7 --> 
<script src="<?php echo ADMIN_COMPONENT; ?>bootstrap/dist/js/bootstrap.min.js"></script> 
<script>
(function($){

	

	var $ajax_status = $('#ajax_status');

	var login  = {};

	var forgot_password = {};

	

	login.open = function(){

		$ajax_status.empty();

		$('#login_form_wrapper').show();

		$('#forget_password_wrapper').hide();

		

	};

	

	login.checkLogin = function(form, evt){

		

		evt.preventDefault();

		

		var fdata = $(form).serialize();

		$('.invalid').removeClass('invalid');

		$.ajax({

			url: '<?php echo base_url('login/login_ajax')?>',

			data: fdata,

			dataType: 'json',

			type: 'POST',

			success: function(res){

				if(res.status == 0){

					

					var errors = res.errors;

					for(var i in errors){

						

						$('#'+i+'Error').html(errors[i]);

						$('[name="'+i+'"]').addClass('invalid');

						

					}

					

				}else{

					location.href = res.next;

				}

			}

		});

		

	};

	

	forgot_password.checkEmail = function(form, evt){

		$ajax_status.empty();

		evt.preventDefault();

		

		var fdata = $(form).serialize();

		$('.invalid').removeClass('invalid');

		$.ajax({

			url: '<?php echo base_url('login/forgot_password_ajax'); ?>',

			data: fdata,

			dataType: 'json',

			type: 'POST',

			success: function(res){

				if(res.status == 0){

					

					var errors = res.errors;

					for(var i in errors){

						

						$('#'+i+'Error').html(errors[i]);

						$('[name="'+i+'"]').addClass('invalid');

						

					}

					

				}

				if(res.msg){

					$ajax_status.html(res.msg);

				}

			}

		});

		

	};

	

	forgot_password.open = function(){

		$ajax_status.empty();

		$('#login_form_wrapper').hide();

		$('#forget_password_wrapper').show();

	};

	

	window.login = login;

	window.forgot_password = forgot_password;

	

})(jQuery);





</script>
</body>
</html>
