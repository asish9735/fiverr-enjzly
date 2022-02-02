<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
<div class="container">
	<div class="row">
		<div class="col-md-12 mb-5 mt-5">
			<h1> <?php D(__('post_proposal_page_heading','Create a New Proposal/Service'))?> </h1>
		</div>
		<div class="col-md-12">
			<div class="card rounded-0 mb-5">
				<div class="card-body">
					<form action="" method="post" accept-charset="utf-8" id="postproposalform" class="form-horizontal" role="form" name="postproposalform" onsubmit="saveProposal(this);return false;">
						<div class="form-group row">
							<div class="col-md-3 control-label h6"> Proposal's Title </div>
							<div class="col-md-8">
								<input type="text" name="proposal_title" id="proposal_title" maxlength="70" class="form-control" value="">
								<small class="text-info"><i class="fa fa-info-circle"></i> Minimum 15 characters in length</small>
								<span id="proposal_titleError" class="rerror"></span>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-3 control-label h6"> Proposal's Category </div>
							<div class="col-md-8">
								<select class="form-control mb-3" name="category_id" id="category_id">
									<option value="" class="hidden"> Select A Category </option>
									<?php 
									if($all_category){
										foreach($all_category as $k=>$category){
									?>
									<option value="<?php D($category->category_id); ?>">  <?php D($category->name); ?> </option>
									<?php	
										}
									}
									?>
								</select>
								<span id="category_idError" class="rerror"></span>
								<div class="load_cubcategory_loader" style="display: none"></div>
								<select class="form-control" name="sub_category_id" id="sub_category_id" style="display: none">
									<option value="" class="hidden"> Select A Sub Category </option>
								</select>
								<span id="sub_category_idError" class="rerror"></span>
							</div>	
						</div>
						<div class="form-group row">
							<div class="col-md-3 control-label h6"> 
								Proposal's Description <br> <small> Briefly Describe Your Proposal. </small>
							</div>
							<div class="col-md-8">
								<textarea name="proposal_description" id="proposal_description" rows="7" placeholder="Enter Your Proposal's Description"  class="form-control proposal-desc"></textarea>
								<small class="text-info"><i class="fa fa-info-circle"></i> Minimum 150 characters in length</small>
								<span id="proposal_descriptionError" class="rerror"></span>
							</div>
						</div>	
						<div class="form-group row">
							<div class="col-md-3 control-label h6">
								Instructions to Buyer <br> <small> Give buyer a head start. </small>
								<br>
								<small class="text-justify"> 
									If you need to obtain information, files or other items from the buyer prior to starting your work, please add your instructions here. For example: Please send me your company name or please send me the photo you need me to edit.
								</small>
							</div>
							<div class="col-md-8">
								<textarea name="buyer_instruction" id="buyer_instruction" rows="7" class="form-control"></textarea>
							</div>
						</div>	
						<div class="form-group row">
							<div class="col-md-3 control-label h6"> Proposal's Tags
								<br> <small> Type a comma after each tag. </small>
							</div>
							<div class="col-md-8">
								<input type="text" name="proposal_tags" id="proposal_tags" placeholder="Tags" data-role="tagsinput" value="" class="form-control">
								<span id="proposal_tagsError" class="rerror"></span>
							</div>
						</div>
						
						<div class="form-group row d-none"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"> Enable Referrals : <br>
								<small class="text-justify"> 
									If enabled, other users can promote your proposal by sharing it on different platforms.
								</small>
							</label>
							<div class="col-md-8">
								<select name="proposal_enable_referrals" id="proposal_enable_referrals" class="proposal_enable_referrals form-control">
									<option value="0"> No </option>
									<option value="1"> Yes </option>
								</select>
								<small class="form-text text-muted">
									Enable or disable this option.
								</small>
								<span id="proposal_enable_referralsError" class="rerror"></span>
							</div>
						</div>	
						<div class="form-group row proposal_referral_money"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"> Promotion Commission: <br>
								<small> When another user promotes your proposal, how much would you like that user to get from the sale? (in percentage)
							</small>
							</label>
							<div class="col-md-8">
								<input type="number" name="proposal_referral_money" id="proposal_referral_money" class="form-control" min="1" value="" placeholder="Figure should be in percentage e.g 20">
								<span id="proposal_referral_moneyError" class="rerror"></span>
							  	<small>Figure should be in percentage. E.g 20 is the same as 20% from the sale of this proposal.</small>
							</div>
						</div><!--- form-group row Ends --->	
						<div class="form-group row">
							<div class="col-md-3 control-label h6"> Proposal's Delivery Time </div>
							<div class="col-md-8">
								<select name="delivery_id" class="form-control" >
								<?php 
								if($all_delivery_times){
									foreach($all_delivery_times as $k=>$delivery_times){
								?>
								<option value="<?php D($delivery_times->delivery_id); ?>"><?php D($delivery_times->delivery_proposal_title); ?></option>
								<?php	
									}
								}
								?>
								</select>	
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-3 control-label h6"> Add Proposal's Image
								<br><small>Supported image extentions include: 'gif', 'png', 'jpg', 'jpeg', 'tif'. </small>
							</div>
							<div class="col-md-8">
								<div class="choosefile">
									<input type="file" name="proposal_img1" id="proposal_img1" class="form-control" >
									<span class="btn btn-success">Choose File</span>
								</div>
								
								<div id="thumbnail_primary"></div>
								<span id="mainimageError" class="rerror"></span>
								<small class="text-info"><i class="fa fa-info-circle"></i> NB: Your Proposal image size must be 700 x 390 pixels and upto 25MB</small>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col-md-3 control-label h6"> Add More Images </div>
								<div class="col-md-8">
									<a href="#" data-toggle="collapse" data-target="#more-images" class="btn btn-success btn-block">
										Add More Images
									</a>
									<div id="more-images" class="collapse">
										<input type="file" name="fileinput" id="fileinput" multiple="true">
										<div class="upload-area" id="uploadfile">
							                <h4>Drag and Drop file here<br>Or<br>Click to select file</h4>
							            </div>
							            <div id="uploadfile_container"></div>
									</div>
								</div>
						</div>
						<div class="form-group row">
							<div class="col-md-3 control-label h6"> Add Proposal's Video (Optional)
								<br><small>Supported video extentions include: 'mp4', 'mov', 'avi', 'flv', 'wmv'. </small>    
							</div>
							<div class="col-md-8">
								<div class="choosefile">
									<input type="file" name="proposal_video" id="fileinputvideo" class="form-control">
									<span class="btn btn-success">Choose File</span>
								</div>
								<small class="text-info"><i class="fa fa-info-circle"></i> Maximum size 25MB</small>
								<div id="uploadfile_container_video"></div>
							</div>
						</div>
						<hr class="mt-4 mb-4"/>
						<div class="form-group row">
							<div class="col-md-3 control-label h6"> </div>
							<div class="col-md-8">
								<button type="submit" name="submit" class="btn btn-success btn-lg btn-block saveBTN"> Proceed To Next Step </button>
							</div>
						</div>
					</form>	
				</div>
			</div>
		</div>
	</div>
</div>

<div id="insertimageModal" class="modal" role="dialog">
 	<div class="modal-dialog modal-lg">
  		<div class="modal-content">
     		 <div class="modal-header">
       			Crop & Insert Image
       			<button type="button" class="close" data-dismiss="modal" onclick="$('#proposal_img1').val('');">&times;</button>
      		</div>
      		<div class="modal-body">
        		<div id="image_demo" style="width:100% !important;"></div>
      		</div>

      		<div class="modal-footer">
		      	<input type="hidden" name="img_type" value="">
		      	<button class="btn btn-success crop_image">Crop Image</button>
		        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#proposal_img1').val('');">Close</button>
	     	</div>
    	</div>
  	</div>
</div>
<div id="wait"></div>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
$(document).ready(function(){
$('.proposal_referral_money').hide();
$(".proposal_enable_referrals").change(function(){
	var value = $(this).val();
	if(value == "1"){
		$('.proposal_referral_money').show();
	}else if(value == "0"){
		$('.proposal_referral_money').hide();	
	}
});
$('textarea:first').summernote({
        placeholder: 'Write Your Description Here.',
        height: 150,
        toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['height', ['height']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture']],
      ],
});
$("#category_id").change(function(){	
	$("#sub_category_id").hide();
	$( ".load_cubcategory_loader").html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 50px;">'+SPINNER+'<div>').show();
	
	$.get( "<?php echo get_link('getsubcatAJAXURL')?>",{'formtype':'getsubcat','Okey':$(this).val()}, function( data ) {
		var html='<option value=""> Select A Sub Category </option>';
		if(data){
			for(x in data){
				html+='<option value="'+data[x]['category_subchild_id']+'">'+data[x]['name']+'</option>';
			}
		}
		setTimeout(function(){ $("#sub_category_id").html(html).show();$( ".load_cubcategory_loader").hide();},1000)
	},'json');
});

$image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:700,
      height:390,
      type:'square' //circle
    },
    boundary:{
      width:100,
      height:400
    }    
});	
$('.crop_image').click(function(event){
	$('#wait').addClass("loader");
 	var name = $('input[type=hidden][name=img_type]').val();
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
    	$("#thumbnail_primary").html('<div class="center">'+SPINNER+'</div>');
	    $.ajax({
	        url:"<?php D(get_link('uploadFileProposalFormCheckAJAXURL'))?>?type=main",
	        type: "POST",
	        data:{image: response, name: name },
	        dataType: 'json',
	        success:function(response){
	        	$('#proposal_img1').val('');
	        	$('#wait').removeClass("loader");
		        $('#insertimageModal').modal('hide');
	        	 if(response.status=='OK'){
		          	var name = response.upload_response.original_name;
	    			$("#thumbnail_primary").html('<input type="hidden" name="mainimage" value=\''+JSON.stringify(response.upload_response)+'\'/> '+name+'<a href="<?php D(VZ);?>" class=" text-danger ripple-effect ico float-right" onclick="$(this).parent().remove()"><i class="fa fa-trash"></i></a>');
    			}
	          	//$('input[type=hidden][name='+ name +']').val(data);
	        }
	    });
    })
 });
});
$('#proposal_img1').on('change', function(){
	var size = $(this)[0].files[0].size; 
	var ext = $(this).val().split('.').pop().toLowerCase();
	if($.inArray(ext,['jpeg','jpg','gif','png']) == -1){
		alert('Your File Extension Is Not Allowed.');
		$(this).val('');
	}else{
   	 	crop(this);
	}
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
    $('input[type=hidden][name=img_type]').val(data.files[0].name);
}
$("#fileinputvideo").change(function(){
    var fd = new FormData();
	var all_files= $('#fileinputvideo')[0].files;
	for(var i=0;i<all_files.length;i++){
		var files = $('#fileinputvideo')[0].files[i];
		fd.append('fileinput',files);
        uploadDataVideo(fd);
	}
});
function uploadDataVideo(formdata){
	var vnum =1;	
	$("#uploadfile_container_video").html('<div id="thumbnailv_'+vnum+'" class="thumbnail_sec_video  mt-3">'+SPINNER+'</div>');
    $.ajax({
        url: "<?php D(get_link('uploadFileProposalFormCheckAJAXURL'))?>?type=video",
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
        	$('#fileinputvideo').val('');
           if(response.status=='OK'){
    			var name = response.upload_response.original_name;
    			$("#thumbnailv_"+vnum).html('<input type="hidden" name="projectvideo" value=\''+JSON.stringify(response.upload_response)+'\'/> '+name+'<a href="<?php D(VZ);?>" class=" ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"><i class="fa fa-trash"></i></a>');
		   }else{
		   		$("#thumbnailv_"+vnum).html('<p class="text-danger">Error in upload file</p>');
		   }
           
        },
        
    }).fail(function(){
    	$("#thumbnailv_"+vnum).html('<p class="text-danger">Error occurred</p>');
    });
	}
function uploadData(formdata){
	var len = $("#uploadfile_container div.thumbnail_sec").length;
   	var num = Number(len);
	num = num + 1;	
	if(num>4){
		swal({
          type: 'error',
           text: 'Max limit 4',
          timer: 2000,
          onOpen: function(){
            swal.showLoading()
          }
      });
		return false;
	}
	$("#uploadfile_container").append('<div id="thumbnail_'+num+'" class="thumbnail_sec">'+SPINNER+'</div>');
    $.ajax({
        url: "<?php D(get_link('uploadFileProposalFormCheckAJAXURL'))?>?type=image",
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
           if(response.status=='OK'){
    			var name = response.upload_response.original_name;
    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> <a href="<?php D(VZ);?>" class=" ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"><i class="fa fa-trash"></i></a>');
    			
    			var urlImg='<?php D(get_link('downloadTempURL'))?>/'+response.upload_response.file_name;
    			$("#thumbnail_"+num).css({"background-image": "url('"+urlImg+"')"})
		   }else{
		   		$("#thumbnail_"+num).html('<p class="text-danger">Error in upload file</p>');
		   }
           
        },
        
    }).fail(function(){
    	$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');
    });
}
function saveProposal(ev){
	var formID="postproposalform";
	var buttonsection=$('#'+formID).find('.saveBTN');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('postproposalURLAJAX'))?>/",
        data:$('#'+formID).serialize(),
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				 swal({
                  type: 'success',
                  text: 'Step 1 completed. Moving to the next step ...',
                  timer: 3000,
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
</script>