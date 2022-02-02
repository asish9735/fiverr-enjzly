var error_icon='<span class=" icon-line-awesome-exclamation-circle" aria-hidden="true"></span>';
function FormPost(e,formType,calback) {
	var url=VPATH;
	var formID="invalid";
	var buttonsection="invalid";
	var formdata=[];
	if(formType=='Login_form'){
		var url=url+"ajax/login-check";
		formID=$(e).closest('form').attr('id');
		formdata=$('#'+formID).serialize();
		buttonsection=$(e);
		buttonval = $(e).html();
	}else if(formType=='Register_form'){
		var url=url+"ajax/signup-check";
		formID=$(e).closest('form').attr('id');
		formdata=$('#'+formID).serialize();
		buttonsection=$(e);
		buttonval = $(e).html();
	}
	buttonsection.html('<i class="fa fa-spinner fa-spin fa-fw"></i><span class="sr-only">Loading...</span>').attr('disabled','disabled');
    $.ajax({
        type: "POST",
        url: url,
        data:formdata,
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			if (msg['status'] == 'OK') {
				if(msg['redirect']){
					window.location.replace( msg['redirect']);
					return false;
				}
				if(msg['calback']){
					var fnName = msg['calback'];
					var param=[];
					if(msg['calbackdata']){
						var params = msg['calbackdata'];
					}
					window[fnName](params);
					//var fn = window[msg['calback']];

				    // is object a function?
				    /*if (typeof fn === "function"){
				    	 fn.apply(null, msg['calbackdata']);
				    	 fn();
				    };*/

				}else{
					$('#'+formnameid).trigger('reset');
					$('#'+formnameid).hide();
				}
				
				clearErrors();
				
			} else if (msg['status'] == 'FAIL') {
				registerFormPostResponse(formID,msg['errors']);
			}
		},
        error: function(msg) {
            buttonsection.html(buttonval).removeAttr('disabled');
        }
    });
    return false;
}
 function registerFormPostResponse(formnameid,errors) {
    clearErrors();
	 $('#'+formnameid+' input[type="text"] , #'+formnameid+' input[type="password"], #'+formnameid+' input[type="date"], #'+formnameid+' input[type="number"] , #'+formnameid+' textarea').removeClass('is-invalid').addClass('is-valid');
    if (errors.length > 0) {
        for (i = 0; i < errors.length; i++) {
            showError(formnameid,errors[i].id, errors[i].message);
        }
    }
   
	/*var error_ele = $('.rerror').not(':empty').offset();
	if(error_ele){
		$(window).scrollTop(error_ele.top - 150);
	}*/
}
function clearErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.rerror').hide();
    $('.invalid-feedback').removeClass('invalid-feedback');
    
    /*$(".rtfLoading  .errmsg").remove();
	$(".rtfLoading").removeClass('rtfError');
	$(".rtfLoading").addClass('rtfOk');
	$(".rtfLoading").removeClass('rtfLoading');*/
    	
}
function showError(formnameid,field,message) {
	
	if(formnameid=='postprojectform'){
		$('#'+formnameid+' #'+field).removeClass('is-valid');
		$('#'+formnameid+' #'+field).addClass('is-invalid');
		$('#'+formnameid+' #item-'+field+'-p').removeClass('rtfTip');
		$('#'+formnameid+' #item-'+field+'-p').removeClass('rtfOk');
		$('#'+formnameid+' #item-'+field+'-p').addClass('rtfLoading');		
		$('#'+formnameid+' #item-'+field+'-p').addClass('rtfError'); 
		$('#'+formnameid+' #item-'+field+'-p .errmsg').remove();
		$('#'+formnameid+' #item-'+field+'-p .tip').prepend('<span class="errmsg errin">'+message+'<br></span>');
		$('#'+formnameid+' #item-'+field+'-p').find('.tip').show(); 
		$('#'+formnameid+' #item-'+field+'-p .realtip').slideUp('slow');
		
	}else{
		$('#'+formnameid+' #'+field).addClass('is-invalid');
		$('#'+formnameid+' #'+field+'Error').addClass('invalid-feedback').html(error_icon+' '+message).show();
		if($("#"+formnameid+" input[name=recaptcha_response_field]").length){
			Recaptcha.reload();
		}
	}
}
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
   /* console.log(charCode);*/
    if(charCode=='46'){
		 return true;
	}else if (charCode > 31 && (charCode < 48 || charCode > 57)){
       return false;
    }
    else{
      return true;
    }
}
function swal(option){

	return new Promise(function(resolve, reject){
		var bootbox_option ={
			title: "Alert!",
			message: "hello there",
			buttons: {
				ok: {
					label: 'Ok',
					className: 'btn-site pull-right'
				}
			},
			callback: function(res){ 
				resolve(res);
			}
		};

		if(option.type == 'success'){
			bootbox_option.title = 'Success';
		}else if(option.type == 'error'){
			bootbox_option.title = 'Error';
		}

		if(option.text){
			bootbox_option.message = option.text;
		}

		bootbox.alert(bootbox_option);
	});

} 
