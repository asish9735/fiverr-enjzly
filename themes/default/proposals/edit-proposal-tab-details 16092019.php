<form action="" method="post" accept-charset="utf-8" id="postproposalform" class="form-horizontal" role="form" name="postproposalform" onsubmit="saveProposal(this);return false;">
<input type="hidden" name="pid" value="<?php D($proposal_details['proposal']->proposal_id);?>"/>
<input type="hidden" name="token" value="<?php D($token);?>"/>
<input type="hidden" name="tab" value="main"/>
<div class="form-group row">

<div class="col-md-3 control-label h6"> Proposal's Title </div>

<div class="col-md-8">

<input type="text" name="proposal_title" id="proposal_title" maxlength="70" class="form-control" value="<?php if($proposal_details){D($proposal_details['proposal']->proposal_title);}?>">
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
		<option value="<?php D($category->category_id); ?>" <?php if($proposal_details && $proposal_details['proposal_category']->category_id==$category->category_id){D('selected');}?>>  <?php D($category->name); ?> </option>
		<?php	
			}
		}
		?>
	</select>
	<span id="category_idError" class="rerror"></span>
	<div class="load_cubcategory_loader" style="display: none"></div>
	<select class="form-control" name="sub_category_id" id="sub_category_id">
		<option value="" class="hidden"> Select A Sub Category </option>
		<?php 
		if($all_sub_category){
			foreach($all_sub_category as $k=>$sub_category){
		?>
		<option value="<?php D($sub_category->category_subchild_id); ?>" <?php if($proposal_details && $proposal_details['proposal_category']->category_subchild_id==$sub_category->category_subchild_id){D('selected');}?>>  <?php D($sub_category->name); ?> </option>
		<?php	
			}
		}
		?>
	</select>
	<span id="sub_category_idError" class="rerror"></span>
</div>

</div>


<div class="form-group row">

<div class="col-md-3 control-label h6"> 

Proposal's Description <br> <small> Briefly Describe Your Proposal. </small>

</div>

<div class="col-md-8">
<textarea name="proposal_description" id="proposal_description" rows="7" placeholder="Enter Your Proposal's Description"  class="form-control proposal-desc"><?php if($proposal_details){D($proposal_details['proposal_additional']->proposal_description);}?></textarea>
<small class="text-info"><i class="fa fa-info-circle"></i> Minimum 150 characters in length</small>
<span id="proposal_descriptionError" class="rerror"></span>


</textarea>

</div>


</div>


<div class="form-group row">

<div class="col-md-3 control-label h6">

Instructions to Buyer <br> <small> Give buyer a head start. </small>

<br>

<small class="text-justify"> 
If you need to obtain information, files or other items from the buyer prior to starting your work, please add your instructions here. For example: Please send me your company name or Please send me the photo you need me to edit.
</small>

</div>

<div class="col-md-8">

<textarea name="buyer_instruction" id="buyer_instruction" rows="7" class="form-control"><?php if($proposal_details){D($proposal_details['proposal_additional']->buyer_instruction);}?></textarea>

</div>


</div>


<div class="form-group row">

<div class="col-md-3 control-label h6"> Proposal's Tags

<br> <small> Type a comma after each tag. </small>

</div>

<div class="col-md-8">
<?php
$d_proposal_tags=array();
if($proposal_details){
	if($proposal_details['proposal_tags']){
		foreach($proposal_details['proposal_tags'] as $tagname){
			$d_proposal_tags[]=$tagname->tag_name;
		}
	}
}
?>
<input type="text" name="proposal_tags" id="proposal_tags" placeholder="Tags" data-role="tagsinput" value="<?php D(implode(',',$d_proposal_tags));?>" class="form-control">
<span id="proposal_tagsError" class="rerror"></span>

</div>

</div>



<div class="form-group row d-none"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Enable Referrals : </label>

<div class="col-md-8">

<select name="proposal_enable_referrals" id="proposal_enable_referrals" class="proposal_enable_referrals form-control">
	<option value="0" <?php if($proposal_details && $proposal_details['proposal_settings']->proposal_enable_referrals!=1){D('selected');}?>> No </option>
	<option value="1" <?php if($proposal_details && $proposal_details['proposal_settings']->proposal_enable_referrals==1){D('selected');}?>> Yes </option>
</select>
<small class="form-text text-muted">
	Enable or disable this option.
</small>
<span id="proposal_enable_referralsError" class="rerror"></span>

</div>

</div><!--- form-group row Ends --->


<div class="d-none form-group row proposal_referral_money" <?php if($proposal_details && $proposal_details['proposal_settings']->proposal_enable_referrals==1){D('style="display:flex"');} ?>><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Promotion Commission: <br>
<small> When another user promotes your proposal, how much would you like that user to get from the sale? (in dollars)
</small>
</label>

<div class="col-md-8">

<input type="number" name="proposal_referral_money" id="proposal_referral_money" class="form-control" min="1" value="<?php if($proposal_details && $proposal_details['proposal_settings']->proposal_enable_referrals==1){D($proposal_details['proposal_settings']->proposal_referral_money);}?>" placeholder="Figure should be in percentage e.g 20">
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
<option value="<?php D($delivery_times->delivery_id); ?>" <?php if($proposal_details && $proposal_details['proposal']->delivery_time==$delivery_times->delivery_id){D('selected');}?>><?php D($delivery_times->delivery_proposal_title); ?></option>
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


<?php
if($proposal_details['proposal']->proposal_image){
$filejson=array(
	'file_name'=>$proposal_details['proposal']->proposal_image,
	'original_name'=>$proposal_details['proposal']->proposal_image,
	);
?>
<div id="thumbnail_primary" class="thumbnail_sec mt-3" style="background-image: url('<?php D(URL_USERUPLOAD.'proposal-files/'.$proposal_details['proposal']->proposal_image);?>');"><input type="hidden" name="mainimageprevious" value='<?php D(json_encode($filejson))?>'><a href="javascript:void(0)" class=" ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"><i class="fa fa-trash"></i></a></div>
<?php }else{?>
<div id="thumbnail_primary" class="thumbnail_sec"></div>
<?php }?>

<span id="mainimageError" class="rerror"></span>
<small class="text-info"><i class="fa fa-info-circle"></i> NB: Your Proposal image size must be 700 x 390 pixels and upto 25MB</small>	



</div>

</div>

<div class="form-group row"><!-- form-group row Starts -->

<div class="col-md-3 control-label h6"> Add Proposal More Images </div>

<div class="col-md-8">

<a href="#" data-toggle="collapse" data-target="#more-images" class="btn btn-success btn-block">
Add More Images
</a>

<div id="more-images" class="collapse">
	<input type="file" name="fileinput" id="fileinput" multiple="true">
	<div class="upload-area" id="uploadfile">
        <h4>Drag and Drop file here<br>Or<br>Click to select file</h4>
    </div>
    <div id="uploadfile_container">
    <?php if($proposal_details && $proposal_details['proposal_files']){
   	$inc=0;
   	foreach($proposal_details['proposal_files'] as $files){
   		$inc++;
   		$filejson=array(
   		'file_id'=>$files->file_id,
   		'file_name'=>$files->server_name,
   		'original_name'=>$files->original_name,
   		);
		?>
		<div id="thumbnail_<?php D($inc)?>" class="thumbnail_sec" style="background-image: url('<?php D(URL_USERUPLOAD.'proposal-files/'.$files->server_name);?>');"><input type="hidden" name="projectfileprevious[]" value='<?php D(json_encode($filejson))?>'><a href="javascript:void(0)" class="  ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"><i class="fa fa-trash"></i></a></div>
		<?php
	}
   	
   }?>	
    </div>
</div>

</div>

</div>

<div class="form-group row">

<div class="col-md-3 control-label h6"> Add Proposal Video (Optional)

<br><small>Supported video extentions include: 'mp4', 'mov', 'avi', 'flv', 'wmv'. </small>    

</div>

<div class="col-md-8">
<div class="choosefile">
<input type="file" name="proposal_video" id="fileinputvideo" class="form-control">
<span class="btn btn-success">Choose File</span>
</div>

<div id="uploadfile_container_video">
<?php
if($proposal_details['proposal_additional']->proposal_video){
$filejson=array(
	'file_name'=>$proposal_details['proposal_additional']->proposal_video,
	'original_name'=>$proposal_details['proposal_additional']->proposal_video,
	);
?>
<div id="thumbnailv_1" class="thumbnail_sec mt-3" style="width: 250px;height:200px">
<video width="220" height="150" controls>
<source src="<?php D(URL_USERUPLOAD.'proposal-video/'.$proposal_details['proposal_additional']->proposal_video);?>" >
</video>
<input type="hidden" name="videoprevious" value='<?php D(json_encode($filejson))?>'><a href="javascript:void(0)" class=" ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()">

<i class="fa fa-trash"></i></a>

</div>
<?php }?>	
	
	
</div>
<small class="text-info"><i class="fa fa-info-circle"></i>Maximum size 25MB</small>
</div>

</div>

<div class="form-group row">

<div class="col-md-3 control-label h6"> </div>

<div class="col-md-8">

<button type="submit" name="update" class="btn btn-success form-control saveBTN"> Update Proposal </button>

</div>

</div>

</form>