<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
<div class="breadcrumbs">
    <div class="container-fluid">
        <h1><?php D(__('post_proposal_page_heading','Create a New Proposal/Service'))?></h1>        
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
        <form action="" method="post" accept-charset="utf-8" id="postproposalform" class="form-horizontal" role="form" name="postproposalform" onsubmit="saveProposal(this);return false;">
            <div class="card mb-4">
            <div class="card-body">
                    <div class="form-field">
                    <label class="form-label"><?php D(__('post_proposal_page_title',"Proposal\'s Title"));?> <span class="required">*</span></label>
                    <input type="text" name="proposal_title" id="proposal_title" maxlength="70" class="form-control" value="">
                    <small class="text-help"><i class="icon-feather-info"></i> <?php D(__('post_proposal_page_title_note',"Minimum 15 characters in length"));?></small>
                    <span id="proposal_titleError" class="rerror"></span>
                    </div>                    
                    
                    <div class="form-field">
                    <label class="form-label"><?php D(__('post_proposal_page_category',"Gigs\'s Category"));?> <span class="required">*</span></label>
                    <div class="row">
                    <div class="col-sm-6">                        
                    <select class="form-control mb-3" name="category_id" id="category_id">
                        <option value="" class="hidden"> <?php D(__('post_proposal_page_select_category',"Select A Category"));?> </option>
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
                    <div class="col-sm-6">                        
                    <div class="load_cubcategory_loader" style="display: none"></div>
                    <select class="form-control" name="sub_category_id" id="sub_category_id" style="display: none">
                        <option value="" class="hidden"> <?php D(__('post_proposal_page_select_sub_category',"Select A Sub Category"));?> </option>
                    </select>
                    <span id="sub_category_idError" class="rerror"></span>
                    </div>
                    </div>
                </div>
                    
                
                <div class="form-group">                
                    <label class="form-label mb-0"> 
                        <?php D(__('post_proposal_page_Description',"Proposal\'s Description"));?> <span class="required">*</span>
                    </label>
                    <p><small><i><?php D(__('post_proposal_page_Description_info',"Briefly Describe Your Proposal."));?></i></small></p>                    
                    <textarea name="proposal_description" id="proposal_description" rows="7" placeholder="<?php D(__('post_proposal_page_Description_input',"Enter Your Proposal\'s Description"));?>"  class="form-control proposal-desc"></textarea>
                    <small class="text-help"><i class="icon-feather-info"></i> <?php D(__('post_proposal_page_Description_note',"Minimum 150 characters in length"));?></small>
                    <span id="proposal_descriptionError" class="rerror"></span>
                    
                </div>	
                
                <div class="form-group">
                    <label class="form-label mb-0">
                        <?php D(__('post_proposal_page_Instructions',"Instructions to Buyer"));?></label>
                        <p class="mb-0"><small><i><?php D(__('post_proposal_page_Instructions_info',"Give buyer a head start."));?></i></small></p>
                        <p><small><i><?php D(__('post_proposal_page_Instructions_info_description',"If you need to obtain information, files or other items from the buyer prior to starting your work, please add your instructions here. For example: Please send me your company name or Please send me the photo you need me to edit."));?></i></small></p>                    
                    <textarea name="buyer_instruction" id="buyer_instruction" rows="4" class="form-control"></textarea>                
                </div>	
                
                <div class="row">                
                    <div class="col-md-6">
                        <div class="form-field">
                        <label class="form-label"> <?php D(__('post_proposal_page_tags',"Proposal's Tags"));?> <span class="required">*</span></label>
                        <?php /*?><p><small><i>(<?php D(__('post_proposal_page_tags_info',"Type a comma after each tag."));?>)</small></i></p><?php */?>
                        <input type="text" name="proposal_tags" id="proposal_tags" placeholder="<?php D(__('post_proposal_page_tags_input',"Tags"));?>" data-role="tagsinput" value="" class="form-control">
                        <span id="proposal_tagsError" class="rerror"></span>
                        </div>
                    </div>
                    <div class="col-md-6 proposal_referral_money">
                    <div class="form-field">
                    <label class="form-label mb-0"> <?php D(__('post_proposal_page_Promotion_Commission',"Promotion Commission:"));?> <span class="required">*</span></label>
                    <p><small><i><?php D(__('post_proposal_page_Promotion_Commission_info',"When another user promotes your proposal, how much would you like that user to get from the sale? (in dollars)"));?></i></small></p>                    
                        <input type="number" name="proposal_referral_money" id="proposal_referral_money" class="form-control" min="1" value="" placeholder="Figure should be in percentage e.g 20">
                        <span id="proposal_referral_moneyError" class="rerror"></span>
                        <small><?php D(__('post_proposal_page_Promotion_Commission_note',"Figure should be in percentage. E.g 20 is the same as 20% from the sale of this proposal."));?></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <label class="form-label"><?php D(__('post_proposal_page_Delivery_Time',"Proposal\'s Delivery Time"));?> <span class="required">*</span></label>
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
                
                <div class="form-group row d-none"><!--- form-group row Starts --->
                    <label class="col-md-4 col-form-label"><?php D(__('post_proposal_page_Enable_Referrals',"Enable Referrals :"));?></label>
                    <p><small><i>If enabled, other users can promote your proposal by sharing it on different platforms.</i></small></p>                    
                    <div class="col-md-8">
                        <select name="proposal_enable_referrals" id="proposal_enable_referrals" class="proposal_enable_referrals form-control">
                            <option value="0"> No </option>
                            <option value="1"> Yes </option>
                        </select>
                        <small class="form-text text-muted">
                            <?php D(__('post_proposal_page_Enable_Referrals_info',"Enable or disable this option."));?>
                        </small>
                        <span id="proposal_enable_referralsError" class="rerror"></span>
                    </div>
                </div>	
                
                <div class="form-group">
                    <label class="form-label mb-0"> <?php D(__('post_proposal_page_Image',"Add Proposal\'s Image"));?> <span class="required">*</span></label>                    
                    <p><small><i><?php D(__('post_proposal_page_Image_info',"Supported image extentions include: \'gif\', \'png\', \'jpg\', \'jpeg\', \'tif\'."));?></i></small></p>
                    <div class="input-group">
                      <div class="custom-file upload-file upload-image">
                        <input type="file" class="custom-file-input" id="proposal_img1" accept="image/*, application/pdf">
                        <label class="custom-file-label" for="proposal_img1"><i class="icon-feather-upload mr-1"></i> <?php D(__('global_Choose_File',"Upload Files"));?></label>
                      </div>
                    </div>
                    
                    <?php /*?><div class="choosefile">
                        <input type="file" id="proposal_img1" class="form-control" >
                        <span class="btn btn-success"><?php D(__('global_Choose_File',"Choose File"));?></span>
                    </div><?php */?>
                    
                    <div id="thumbnail_primary"></div>
                    <span id="mainimageError" class="rerror"></span>
                    <small class="text-help"><i class="icon-feather-info"></i> <?php D(__('post_proposal_page_Image_note',"NB: Your Proposal image size must be 700 x 390 pixels and upto 25MB"));?></small>                
                </div>   
                
                <div class="form-group">
                    <label class="form-label"><?php D(__('post_proposal_page_add_more_Image',"Add Proposal More Images"));?> </label>
                    <a href="#" data-toggle="collapse" data-target="#more-images" class="btn btn-sm btn-outline-dark mb-3">
                        <i class="icon-feather-plus"></i> <?php D(__('post_proposal_page_add_more_Image_btn',"Add More Images"));?>
                    </a>
                    <div id="more-images" class="collapse">
                        <input type="file" name="fileinput" id="fileinput" multiple>
                        <div class="upload-area mt-2" id="uploadfile">
                            <h4><?php D(__('post_proposal_page_add_more_Image_drag_text',"Drag and Drop file here<br>Or<br>Click to select file"));?></h4>
                        </div>
                        <div id="uploadfile_container"></div>
                    </div>
                </div>
                        
                <div class="form-group">
                	<label class="form-label mb-0"><?php D(__('post_proposal_page_Video',"Add Proposal Video"));?></label>
                    <p><small><i><?php D(__('post_proposal_page_Video_info',"Supported video extentions include: \'mp4\', \'mov\', \'avi\', \'flv\', \'wmv\'."));?></i></small></p>
                    <div class="input-group">
                      <div class="custom-file upload-file upload-video">
                        <input type="file" class="custom-file-input" name="proposal_video" id="fileinputvideo" accept="video/*">
                        <label class="custom-file-label" for="fileinputvideo"><i class="icon-feather-upload mr-1"></i> <?php D(__('global_Choose_File',"Upload Files"));?></label>
                      </div>
                    </div>                    
                                        
                    <?php /*?><div class="choosefile">
                        <input type="file" id="fileinputvideo" class="form-control">
                        <span class="btn btn-success"><?php D(__('global_Choose_File',"Choose File"));?></span>
                    </div><?php */?>
                    
                    <small class="text-help"><i class="icon-feather-info"></i> <?php D(__('post_proposal_page_Video_note',"Maximum size 25MB"));?></small>
                    <div id="uploadfile_container_video"></div>
                </div>
                
                <div class="row">  
                    <div class="col-md-6">                 
                        <div class="form-field">
                            <label class="form-label"><?php D(__('post_proposal_page_Price_Type',"Price Type"));?> <span class="required">*</span></label>
                            <select class="pricing form-control" name="is_fixed">
                                <option value="0" <?php {D('selected');}?>> <?php D(__('post_proposal_page_Price_Type_Packages',"Packages"));?> </option>
                                <option value="1"> <?php D(__('post_proposal_page_Price_Type_Fixed_Price',"Fixed Price"));?> </option>
                            </select>               
                        </div>
                    </div>
                    <div class="col-md-6 proposal-price" style="display: none">
                    <label class="form-label">Fixed amount</label>
                    <div class="input-group form-curb">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                        <?php D(CURRENCY); ?>
                        </span>
                        </div>
                    <input type="text" class="form-control" id="proposal_price" name="proposal_price" value="" onkeypress="return isNumberKey(event)">
                    </div>
                    <span id="proposal_priceError" class="rerror"></span>
                    <!--<small>If you want to use packages, you need to set this field value to 0. </small>-->
                    </div>
                </div>                                
                </div>
            </div>
            
                
                
                <div class="packages mb-4">
                    <div class="row row-10">
                    <article class="col-md-4 col-12">
                    <div class="card package mb-4">  
                        <div class="card-body">
                        	<h4><?php D(__('post_proposal_page_package_Basic',"Basic"));?></h4>
                            <!--<table class="table table-bordered js-gig-packages"></table>-->
                            <div class="form-field">
                                <label class="form-label">Description</label>
                                <textarea rows="3" name="package_desc_1" id="package_desc_1" class="form-control description-value-64"></textarea>
                                <small class="text-help"><i class="icon-feather-info"></i> <?php D(__('post_proposal_page_package_description_note',"Minimum 70 characters in length"));?></small>
                                <span id="package_desc_1Error" class="rerror"></span>
                            </div>
                                <div class="form-field">
                                <label class="form-label"><?php D(__('post_proposal_page_package_Delivery_Time',"Delivery Time"));?></label>
                                <div class="input-group">
                                <input onkeypress="return isNumberKey(event)" name="package_time_1" id="package_time_1" class="form-control delivery-time-value-64" value="1">
                                </div>
                                </div>
                            <div class="extraoption extraoption_1"></div>
                            <div class="form-field mb-0 time_row" data-row-id="1">
                                <label class="form-label"><?php D(__('post_proposal_page_package_Price',"Price"));?></label>
                                <div class="input-group form-curb">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <?php D(CURRENCY); ?>
                                </span>
                                </div>
                                <input onkeypress="return isNumberKey(event)" name="package_price_1" id="package_price_1" class="form-control price-value-64" value="150">
                                </div>
                            </div>   
                        </div>	
                        </div>
                    </article>
                            
                    <article class="col-md-4 col-12">
                    <div class="card package mb-4">
                    <div class="card-body">
                    	<h4><?php D(__('post_proposal_page_package_Standard',"Standard"));?></h4>
                        <div class="form-field">
                            <label class="form-label">Description</label>
                            <textarea rows="3" name="package_desc_2" id="package_desc_2" class="form-control description-value-65"></textarea>
                            <small class="text-help"><i class="icon-feather-info"></i> <?php D(__('post_proposal_page_package_description_note',"Minimum 70 characters in length"));?></small>
                            <span id="package_desc_2Error" class="rerror"></span>
                        </div>
                        <div class="form-field">
                            <label class="form-label"><?php D(__('post_proposal_page_package_Delivery_Time',"Delivery Time"));?></label>
                            <div class="input-group">
                            <input onkeypress="return isNumberKey(event)" name="package_time_2" id="package_time_2" class="form-control delivery-time-value-65" value="2">
                            </div>
                        </div>
                        <div class="extraoption extraoption_2"></div>
                        <div class="form-field mb-0 time_row" data-row-id="2">
                            <label class="form-label"><?php D(__('post_proposal_page_package_Price',"Price"));?></label>
                            <div class="input-group form-curb">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <?php D(CURRENCY); ?>
                            </span>
                            </div>
                            <input onkeypress="return isNumberKey(event)" name="package_price_2" id="package_price_2" class="form-control price-value-65" value="250">
                            </div>
                        </div>
                        <!--<table class="table table-bordered js-gig-packages"></table>-->
    
                        </div>	
                    </div>
                    </article>	                    	
    
                    <article class="col-md-4 col-12">
                    <div class="card package mb-4">   
                    <div class="card-body">   
                    	<h4><?php D(__('post_proposal_page_package_Advance',"Advance"));?></h4>                     
                        <div class="form-field">
                            <label class="form-label">Description</label>
                            <textarea rows="3" name="package_desc_3" id="package_desc_3" class="form-control description-value-66"></textarea>
                            <small class="text-help"><i class="icon-feather-info"></i> <?php D(__('post_proposal_page_package_description_note',"Minimum 70 characters in length"));?></small>
                            <span id="package_desc_3Error" class="rerror"></span>
                        </div>
                        <div class="form-field">
                            <label class="form-label"><?php D(__('post_proposal_page_package_Delivery_Time',"Delivery Time"));?></label>                     
                            <div class="input-group">
                            <input onkeypress="return isNumberKey(event)" name="package_time_3" id="package_time_3" class="form-control delivery-time-value-66" value="3">
                            </div>
                        </div>
                        <div class="extraoption extraoption_3"></div>
                        <div class="form-field mb-0 time_row" data-row-id="3">
                            <label class="form-label"><?php D(__('post_proposal_page_package_Price',"Price"));?></label>
                            <div class="input-group form-curb">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <?php D(CURRENCY); ?>
                            </span>
                            </div>
                            <input onkeypress="return isNumberKey(event)" name="package_price_3" id="package_price_3" class="form-control price-value-66" value="450">
                            </div>
                        </div>
                        <!--<table class="table table-bordered js-gig-packages"></table>-->
                        </div>	
                    </div>
                    </article>	            
                    </div>
                    <div class="add-attribute">                
                        <div class="input-group">                                
                            <input class="form-control attribute-name" placeholder="<?php D(__('post_proposal_page_Add_New_Attribute_input',"Add New Attribute"));?>" name="">
                            <div class="input-group-append">
                            <button class="btn btn btn-dark insert-attribute" type="button">
                                <i class="icon-feather-upload" aria-hidden="true"></i> <?php D(__('post_proposal_page_Add_New_Attribute_btn',"Insert"));?> 
                            </button>
                            </div>
                            
                        </div>        
                    </div>               
                </div>
                
            
                <button type="submit" name="submit" class="btn btn-site saveBTN"> <?php D(__('post_proposal_page_Post_Proposal',"Post Gigs"));?> </button>
                
        </form>	
      </div>
    </div>
</div>		
</div>
</section>

<div id="insertimageModal" class="modal" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <div class="modal-content mycustom-modal">
     		 <div class="modal-header">
     		 <button type="button" class="btn btn-dark pull-left" data-dismiss="modal" onclick="$('#proposal_img1').val('');"><?php D(__('global_Close',"Close"));?></button>
       			<h4 class="modal-title"><?php D(__('modal_insertimage_heading',"Crop & Insert Image"));?></h4>
       			<button class="btn btn-site crop_image pull-right"><?php D(__('modal_insertimage_Crop_Image',"Crop Image"));?></button>
      		</div>
      		<div class="modal-body">
      		<input type="hidden" name="img_type" value="">
        		<div id="image_demo" style="width:100% !important;"></div>
      		</div>
    	</div>
  	</div>
</div>
<div id="wait"></div>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
$(document).ready(function(){
$(".pricing").change(function(){
	var value = $(this).val();
	if(value == "1"){
		$('.packages').hide();
		$('.add-attribute').hide();
		$('.proposal-price').show();
	}else if(value == "0"){
		$('.packages').show();
		$('.add-attribute').show();
		$('.proposal-price').hide();	
	}
});	
                    
var attrcount=0;
$(".insert-attribute").on('click', function(){
	//$('#wait').addClass("loader");
	var attribute_name = $('.attribute-name').val();
	if(attribute_name){
		attrcount=attrcount+1;
		$('.time_row').each(function(){
			var id=$(this).data('row-id');
			var html='<div class="form-field newattribute attribute_'+attrcount+'" data-attr-id="'+attrcount+'"><label class="form-label">'+attribute_name+'<input type="hidden" name="attribute_count[]" value="'+attrcount+'"><input type="hidden" name="attribute_name_'+attrcount+'[]" value="'+attribute_name+'"></label><div class="input-group"><input type="text" class="form-control attribute-value-'+id+'" value="" data-attribute="'+attrcount+'" name="attribute_value_'+id+'_'+attrcount+'" id="attribute_value_'+id+'_'+attrcount+'" placeholder="Type"><div class="input-group-append"><button type="button" class="btn btn btn-danger delete-attribute" data-attribute="'+attrcount+'" onclick="$(\'.attribute_'+attrcount+'\').remove();"><i class="icon-feather-trash"></i></button></div></div></div>';
			
			$(this).before(html);
		})
		$(".attribute-name").val('');
	}
});	
	
	
	
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
        placeholder: '<?php D(__('post_proposal_page_textarea_input',"Write Your Description Here."));?>',
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
	$( ".extraoption").html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 50px;">'+SPINNER+'<div>').show();
	
	$.get( "<?php echo get_link('getsubcatAJAXURL')?>",{'formtype':'getsubcat','Okey':$(this).val()}, function( data ) {
		var html='<option value=""> <?php D(__('post_proposal_page_Select_Sub_Category',"Select A Sub Category"));?> </option>';
		if(data){
			for(x in data){
				html+='<option value="'+data[x]['category_subchild_id']+'">'+data[x]['name']+'</option>';
			}
		}
		setTimeout(function(){ $("#sub_category_id").html(html).show();$( ".load_cubcategory_loader").hide();},1000)
	},'json');
    $.get( "<?php echo get_link('getextraProposalAJAXURL')?>",{'Okey':$(this).val()}, function( data ) {
		$('.extraoption_1').html(data.section_1);
		$('.extraoption_2').html(data.section_2);
		$('.extraoption_3').html(data.section_3);
        setTimeout(function(){
             $('[data-toggle="popover"]').popover({
                trigger: 'hover',
                //container: 'body',
                html : true
            });
        },1000)
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
	    			$("#thumbnail_primary").html('<input type="hidden" name="mainimage" value=\''+JSON.stringify(response.upload_response)+'\'/> '+name+'<a href="<?php D(VZ);?>" class="ico btn btn-sm btn-circle btn-danger ml-3 float-right" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
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
    			$("#thumbnailv_"+vnum).html('<input type="hidden" name="projectvideo" value=\''+JSON.stringify(response.upload_response)+'\'/> '+name+'<a href="<?php D(VZ);?>" class="ico btn btn-sm btn-circle btn-danger ml-3" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
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
    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> <a href="<?php D(VZ);?>" class="ico btn btn-sm btn-circle btn-danger ml-3" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
    			
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
                 /* text: 'Step 1 completed. Moving to the next step ...',*/
                 text: '<?php D(__('popup_proposal_post_success_message',"Proposal successfully posted"));?>',
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