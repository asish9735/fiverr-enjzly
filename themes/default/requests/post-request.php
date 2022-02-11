<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="breadcrumbs">
  <div class="container">
  	<h1><?php D(__('post_request_page_heading','Post A New Request'))?></h1>
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
	    <div class="row">
		<div class="col-xl-8 col-lg-8 post-request col-12">
			<div class="card">
            <div class="card-body">
            <form action="" method="post" accept-charset="utf-8" id="postrequestform" class="form-horizontal" role="form" name="postrequestform" onsubmit="saveRequest(this);return false;"> 
            
                <div class="form-group">
                    <label class="form-label">Request Title <span class="required">*</span></label>
                    <input type="text" name="request_title" id="request_title" placeholder="<?php D(__('post_request_page_request_title_input','Request Title'))?>" class="form-control input-lg">
                    <span id="request_titleError" class="rerror"></span>	
                </div>
                <div class="form-group">
                    <label class="form-label">Request Description <span class="required">*</span></label>
                    <textarea name="request_description" id="request_description" rows="5" cols="73" maxlength="380" class="form-control" placeholder="<?php D(__('post_request_page_request_description_input','Request Description'))?>" ></textarea>
                    <span id="request_descriptionError" class="rerror"></span>
                    <p class="text-right"><small class="text-help"><span class="count">0</span> / 380 <?php D(__('post_request_page_Max','Max'))?></small></p>
                </div>
                <div class="form-group">
                    <div class="input-group">
                      <div class="custom-file upload-file">
                        <input type="file" class="custom-file-input" name="request_file" id="fileinput">
                        <label class="custom-file-label" for="fileinput"><i class="icon-feather-upload mr-1"></i> <?php D(__('global_Choose_File','Choose File'));?></label>
                      </div>
                    </div>                    
                    <div id="uploadfile_container"></div>
                    <small class="text-help"><i class="icon-feather-info"></i> <?php D(__('post_request_page_attachment_note','Maximum size 25MB'))?></small>
                </div>
            
            <div class="row-2">

                <label class="form-label"><?php D(__('post_request_page_Chose_A_Category','Chose A Category'))?> <span class="required">*</span></label>
                <div class="row row-10">
                    <div class="col-md-6 mb-3">
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="" class="hidden"> <?php D(__('post_request_page_Select_A_Category','Select A Category'))?> </option>
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
                    </div>
                    <div class="col-md-6 mb-3">
                    <div class="load_cubcategory_loader" style="display: none"></div>
                        <select class="form-control" name="sub_category_id" id="sub_category_id" style="display: none">
                            <option value="" class="hidden"> <?php D(__('post_request_page_Select_A_Sub_Category','Select A Sub Category'))?> </option>
                        </select>
                        <span id="sub_category_idError" class="rerror"></span>
                    </div>
                </div>
            </div>
            <div class="row-3 mb-3">
                <label class="form-label"><?php D(__('post_request_page_delivery_time_text','Once you place your order, when would you like your service delivered?'));?> <span class="required">*</span></label>
                <div class="radio-col radio-col-4">
                <?php 
                if($all_delivery_times){
                    foreach($all_delivery_times as $k=>$delivery_times){
                ?>
                <div class="custom-control custom-radio">
                <input type="radio" value="<?php D($delivery_times->delivery_id); ?>" id="delivery_time_<?php D($delivery_times->delivery_id); ?>" name="delivery_time" class="custom-control-input" >									
                <label for="delivery_time_<?php D($delivery_times->delivery_id); ?>" class="custom-control-label"><?php D($delivery_times->delivery_proposal_title); ?></label></div>
                <?php	
                    }
                }
                ?>
                </div>
                <span id="delivery_timeError" class="rerror"></span>
            </div>
            <div class="row-4 mb-4">               
                <label class="form-label"><?php D(__('post_request_page_budget_text','What is your budget for this service?'));?></label>
                <div class="row">
                <div class="col-md-6 col-12">
                    <div class="input-group form-curb">
                        <div class="input-group-prepend"><span class="input-group-text"><?php D(CURRENCY); ?></span></div>
                        <input type="number" class="form-control" id="request_budget" name="request_budget" min="5" placeholder="<?php D(__('post_request_page_amount_input',"5 Minimum"));?>">
                    </div>
                    <span id="request_budgetError" class="rerror"></span>
                </div>
                </div>
            </div>
            
           
            <button type="submit" class="btn btn-site saveBTN" ><?php D(__('post_request_page_button_submit',"Post Request"));?></button>
            </form>
            </div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-4 col-12 request-sidebar "><!--- col-xl-3 col-md-2 request-sidebar p-0 Starts --->
			<div class="card mb-5 h-1">
				<div class="card-body">
					<h5><?php D(__('post_request_page_Define_in_Detail','Define in Detail'));?></h5>
					<p><?php D(__('post_request_page_Define_in_Detail_info','Include all the necessary details needed to complete your request.'));?></p>
					<p class="breadcrumb mb-0">
						<b><?php D(__('post_request_page_For_example','For example:'));?></b> <?php D(__('post_request_page_Define_in_Detail_example','if you are looking for a logo, you can specify your company name, business type, preferred, color, etc.'));?>
					</p>
				</div>
			</div>
			<div class="card h-2">
				<div class="card-body">
					<h5><?php D(__('post_request_page_Refine_your_Request','Refine your Request'));?></h5>
					<p><?php D(__('post_request_page_Refine_your_Request_info','Choose the category and subcategory that best fits your request.'));?></p>
					<p class="breadcrumb mb-0">
						<b><?php D(__('post_request_page_For_example','For example:'));?></b> <?php D(__('post_request_page_Refine_your_Request_example','if you are looking for a logo, you should choose "Logo Design" within the \"Graphics & Design\" category.'));?>
					</p>
				</div>
			</div>
			<div class="card h-3">
				<div class="card-body">
					<h5><?php D(__('post_request_page_Set_Delivery_Time','Set a Delivery Time'));?></h5>
					<p><?php D(__('post_request_page_Set_Delivery_Time_info','This is the amount of time the seller has to work on your order. Please note that a request for faster delivery may impact the price.'));?></p>
				</div>
			</div>
			<div class="card h-4">	
				<div class="card-body">					
					<h5><?php D(__('post_request_page_Set_Your_Budget','Set Your Budget'));?></h5>
					<p><?php D(__('post_request_page_Set_Your_Budget_info','Enter an amount you are willing to spend for this service.'));?></p>
				</div>
			</div>
		</div><!--- col-xl-3 col-md-2 request-sidebar p-0 Ends --->
	</div>
      </div>
    </div>
</div>
</section>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
$(document).ready(function(){
	

$('.h-2').css("visibility", "hidden");
$('.h-3').css("visibility", "hidden");
$('.h-4').css("visibility", "hidden");


$('.container-fluid').hover(function(){

$('.h-1').css("visibility", "visible");
$('.h-2').css("visibility", "hidden");
$('.h-3').css("visibility", "hidden");
$('.h-4').css("visibility", "hidden");

});


$('.row-1').mouseover(function(){

$('.h-1').css("visibility", "visible");
$('.h-2').css("visibility", "hidden");
$('.h-3').css("visibility", "hidden");
$('.h-4').css("visibility", "hidden");

});


$('.row-2').mouseover(function(){

$('.h-1').css("visibility", "hidden");
$('.h-2').css("visibility", "visible");
$('.h-3').css("visibility", "hidden");
$('.h-4').css("visibility", "hidden");

});


$('.row-3').mouseover(function(){

$('.h-1').css("visibility", "hidden");
$('.h-2').css("visibility", "hidden");
$('.h-3').css("visibility", "visible");
$('.h-4').css("visibility", "hidden");

});


$('.row-4').mouseover(function(){

$('.h-1').css("visibility", "hidden");
$('.h-2').css("visibility", "hidden");
$('.h-3').css("visibility", "hidden");
$('.h-4').css("visibility", "visible");

});




$('.row-2,.row-3,.row-4').mouseout(function(){

$('.h-1').css("visibility", "visible");
$('.h-2').css("visibility", "hidden");
$('.h-3').css("visibility", "hidden");
$('.h-4').css("visibility", "hidden");

});


	$("#request_description").keydown(function(){
		var textarea = $("#request_description").val();
		$(".count").text(textarea.length);	
	});	

	
	$("#category_id").change(function(){	
	$("#sub_category_id").hide();
	$( ".load_cubcategory_loader").html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 50px;">'+SPINNER+'<div>').show();
	
	$.get( "<?php echo get_link('getsubcatAJAXURL')?>",{'formtype':'getsubcat','Okey':$(this).val()}, function( data ) {
		var html='<option value=""> <?php D(__('post_request_page_Select_A_Sub_Category','Select A Sub Category'))?> </option>';
		if(data){
			for(x in data){
				html+='<option value="'+data[x]['category_subchild_id']+'">'+data[x]['name']+'</option>';
			}
		}
		setTimeout(function(){ $("#sub_category_id").html(html).show();$( ".load_cubcategory_loader").hide();},1000)
	},'json');
	});
	
});
function saveRequest(ev){
	var formID="postrequestform";
	var buttonsection=$('#'+formID).find('.saveBTN');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('postrequestURLAJAX'))?>/",
        data:$('#'+formID).serialize(),
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				 swal({
                  type: 'success',
                  text: '<?php D(__('popup_post_request_success_message','Your request has been submitted successfully!'))?>',
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
$("#fileinput").change(function(){
    var fd = new FormData();
	var all_files= $('#fileinput')[0].files;
	for(var i=0;i<all_files.length;i++){
		var files = $('#fileinput')[0].files[i];
		fd.append('fileinput',files);
        uploadData(fd);
	}
});
function uploadData(formdata){
	num =1;	
	$("#uploadfile_container").html('<div id="thumbnail_'+num+'" class="thumbnail_sec_">'+SPINNER+'</div>');
    $.ajax({
        url: "<?php D(get_link('uploadFileRequestFormCheckAJAXURL'))?>",
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
        	$('#fileinput').val('');
           if(response.status=='OK'){
    			var name = response.upload_response.original_name;
    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> '+name+'<a href="<?php D(VZ);?>" class=" text-danger ico float-right" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
		   }else{
		   		$("#thumbnail_"+num).html('<p class="text-danger">Error in upload file</p>');
		   }
           
        },
        
    }).fail(function(){
    	$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');
    });
	}
</script>