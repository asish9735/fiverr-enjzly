<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($member_details,true);
?>
<div class="breadcrumbs">
  <div class="container-fluid">
  	<h1><?php D(__('','Settings'));?></h1>
  </div>
</div>
<section class="section">
<div class="container-fluid">
	<div class="row">
      <div class="col-xl-3 col-lg-4 col-12">
        <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
      </div>
      <div class="col-xl-9 col-lg-8 col-12">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link <?php if(empty($tab) || $tab=='profile'){D("active"); }?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php D(__('settings_page_tab_Profile_Settings',"Profile Settings"));?></a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?php if(!empty($tab) && $tab=='account'){D("active"); }?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php D(__('settings_page_tab_Account_Settings',"Account Settings"));?></a>
		</li>  
		<!--<li class="nav-item">
				<a  href="https://forms.gle/asaS9WF5AD2o7BBx9" target="_blank" class="nav-link ">
				<?php D(__('settings_page_tab_Profile_Documents',"Profile Documents"));?>
			</a>
			</li>-->
		</ul>
		<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade <?php if(empty($tab) || $tab=='profile'){D("show active"); }?>" id="home" role="tabpanel" aria-labelledby="home-tab">
		<?php D($profile_settings); ?>
		</div>
		<div class="tab-pane fade <?php if(!empty($tab) || $tab=='account'){D("show active"); }?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
		<?php D($account_settings); ?>
		</div>
		</div>
	  </div>
	</div>
</div>
</section>
<script type="text/javascript">
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
$(document).ready(function(){
	$("#textarea-headline").keydown(function(){
		var textarea_headline = $("#textarea-headline").val();
		$(".count-headline").text(textarea_headline.length);	
	});
	$("#textarea-about").keydown(function(){
		var textarea_about = $("#textarea-about").val();
		$(".count-about").text(textarea_about.length);
	});
	$image_crop = $('#image_demo').croppie({
	    enableExif: true,
	    viewport: {
	      width:200,
	      height:200,
	      type:'square' //circle
	    },
	    boundary:{
	      width:100,
	      height:250
	    }    
  	});
	$('#profile_photo').on('change', function(){
		var size = $(this)[0].files[0].size; 
		var ext = $(this).val().split('.').pop().toLowerCase();
		if($.inArray(ext,['jpeg','jpg','gif','png']) == -1){
			alert('Your File Extension Is Not Allowed.');
			$(this).val('');
		}else{
	   	 	crop(this);
		}
	});
	$("#cover").change(function(){
	    var fd = new FormData();
		var all_files= $('#cover')[0].files;
		for(var i=0;i<all_files.length;i++){
			var files = $('#cover')[0].files[i];
			fd.append('fileinput',files);
	        uploadDataCover(fd);
		}
	});
	 $('.crop_image').click(function(event){
	  	$('#wait').addClass("loader");
	  	var name = $('input[type=hidden][name=img_type]').val();
	    $image_crop.croppie('result', {
	      type: 'canvas',
	      size: 'viewport'
	    }).then(function(response){
	    	$("#thumbnail_logo").html('<div class="center">'+SPINNER+'</div>');
		    $.ajax({
		        url:"<?php D(get_link('uploadFilememberFormCheckAJAXURL'))?>?type=main",
		        type: "POST",
		        data:{image: response, name: name },
		        dataType: 'json',
		        success:function(response){
		        	$('#proposal_img1').val('');
		        	$('#wait').removeClass("loader");
			        $('#insertimageModal').modal('hide');
		        	 if(response.status=='OK'){
			          	var name = response.upload_response.file_name;
			          	var imageUrl="<?php D(URL_USERUPLOAD.'tempfile/');?>"+name;
		    			$("#thumbnail_logo").html('<input type="hidden" name="userlogo" value=\''+JSON.stringify(response.upload_response)+'\'/> <img src="'+imageUrl+'" width="80" class="img-thumbnail img-circle" >');
	    			}
		        }
		    });
	    });
	});
});
function crop(data){
	var reader = new FileReader();
	reader.onload = function (event) {
		$image_crop.croppie('bind',{
	 		url: event.target.result
		}).then(function(){
	  		console.log('jQuery bind complete');
 		});
	}
	reader.readAsDataURL(data.files[0]);
	$('#insertimageModal').modal('show');
	$('input[type=hidden][name=img_type]').val($(data).attr('name'));
}
function uploadDataCover(formdata){
	$("#thumbnail_banner").html('<div class="center">'+SPINNER+'</div>');
    $.ajax({
        url:"<?php D(get_link('uploadFilememberFormCheckAJAXURL'))?>?type=image",
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
           if(response.status=='OK'){
    			var name = response.upload_response.file_name;
    			var imageUrl="<?php D(URL_USERUPLOAD.'tempfile/');?>"+name;
		    	$("#thumbnail_banner").html('<input type="hidden" name="userbanner" value=\''+JSON.stringify(response.upload_response)+'\'/> <img src="'+imageUrl+'" width="80" class="img-thumbnail img-circle" >');	
    		
		   }else{
		   		$("#thumbnail_"+num).html('<p class="text-danger">Error in upload file</p>');
		   }
           
        },
        
    }).fail(function(){
    	$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');
    });
}
function saveProfile(ev){
	var formID="profileform";
	var buttonsection=$('#'+formID).find('.saveBTN');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('editprofileURLAJAX'))?>/",
        data:$('#'+formID).serialize(),
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				 swal({
                  type: 'success',
                  text: '<?php D(__('popup_setting_profile_setting_save_success_message','Profile settings updated successfully!'));?>',
                  timer: 2000,
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
function saveAccount(ev){
	var formID=$(ev).attr('id');
	var buttonsection=$('#'+formID).find('.saveBTN');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('editaccountURLAJAX'))?>/",
        data:$('#'+formID).serialize(),
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				 swal({
                  type: 'success',
                  text: msg['message'],
                  timer: 2000,
                  onOpen: function(){
                    swal.showLoading()
                  }
                  }).then(function(){
                  	window.location.href=msg['redirect'];
                })	
			} else if (msg['status'] == 'FAIL') {
				registerFormPostResponse(formID,msg['errors']);
				if(formID=='accountPasswordform' && msg['is_invalid'] == '1'){
					swal({
	                  type: 'error',
	                  text: '<?php D(__('popup_setting_change_password__error_message',"Your password is invalid. Please try again!"));?>',
	                  timer: 2000,
	                  onOpen: function(){
	                    swal.showLoading()
	                  }
                  }).then(function(){
                  	window.location.reload();
                })
					
				}
			}
		}
	})	
}
</script>