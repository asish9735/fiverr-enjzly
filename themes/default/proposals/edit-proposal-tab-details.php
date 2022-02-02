<form action="" method="post" accept-charset="utf-8" id="postproposalform" class="form-horizontal" role="form" name="postproposalform" onsubmit="saveProposal(this);return false;">
  <input type="hidden" name="pid" value="<?php D($proposal_details['proposal']->proposal_id);?>"/>
  <input type="hidden" name="token" value="<?php D($token);?>"/>
  <input type="hidden" name="tab" value="main"/>
  <div class="card mb-4">
  <div class="card-body">
  <div class="form-group">
    <h5>
      <?php D(__('edit_proposal_tab_details_page_title',"Proposal\'s Title"));?>
    </h5>
    <input type="text" name="proposal_title" id="proposal_title" maxlength="70" class="form-control" value="<?php if($proposal_details){D($proposal_details['proposal']->proposal_title);}?>">
    <small class="text-help"><i class="icon-feather-info"></i>
    <?php D(__('edit_proposal_tab_details_page_title_note',"Minimum 15 characters in length"));?>
    </small> <span id="proposal_titleError" class="rerror"></span> </div>
  <div class="row">
    <div class="col-md-6">
      <h5>
        <?php D(__('edit_proposal_tab_details_page_category',"Proposal\'s Category"));?>
      </h5>
      <select class="form-control mb-3" name="category_id" id="category_id">
        <option value="" class="hidden">
        <?php D(__('edit_proposal_tab_details_page_select_category',"Select A Category"));?>
        </option>
        <?php 

		if($all_category){

			foreach($all_category as $k=>$category){

		?>
        <option value="<?php D($category->category_id); ?>" <?php if($proposal_details && $proposal_details['proposal_category']->category_id==$category->category_id){D('selected');}?>>
        <?php D($category->name); ?>
        </option>
        <?php	

			}

		}

		?>
      </select>
      <span id="category_idError" class="rerror"></span> </div>
    <div class="col-md-6">
      <div class="load_cubcategory_loader" style="display: none"></div>
      <h5>
        <?php D(__('edit_proposal_tab_details_page_sub_category',"Sub Category"));?>
      </h5>
      <select class="form-control mb-3" name="sub_category_id" id="sub_category_id">
        <option value="" class="hidden">
        <?php D(__('edit_proposal_tab_details_page_select_sub_category',"Select A Sub Category"));?>
        </option>
        <?php 

		if($all_sub_category){

			foreach($all_sub_category as $k=>$sub_category){

		?>
        <option value="<?php D($sub_category->category_subchild_id); ?>" <?php if($proposal_details && $proposal_details['proposal_category']->category_subchild_id==$sub_category->category_subchild_id){D('selected');}?>>
        <?php D($sub_category->name); ?>
        </option>
        <?php	

			}

		}

		?>
      </select>
      <span id="sub_category_idError" class="rerror"></span> </div>
  </div>
  <div class="form-group">
    <h5>
      <?php D(__('edit_proposal_tab_details_page_Description',"Proposal\'s Description"));?>
      <br>
      <small>
      <?php D(__('edit_proposal_tab_details_page_Description_info',"Briefly Describe Your Proposal."));?>
      </small></h5>
    <textarea name="proposal_description" id="proposal_description" rows="7" placeholder="<?php D(__('edit_proposal_tab_details_page_Description_input',"Enter Your Proposal\'s Description"));?>"  class="form-control proposal-desc"><?php if($proposal_details){D($proposal_details['proposal_additional']->proposal_description);}?>
</textarea>
    <small class="text-help"><i class="icon-feather-info"></i>
    <?php D(__('edit_proposal_tab_details_page_Description_note',"Minimum 150 characters in length"));?>
    </small> <span id="proposal_descriptionError" class="rerror"></span> </div>
  <div class="form-group">
    <h5>
      <?php D(__('edit_proposal_tab_details_page_Instructions',"Instructions to Buyer"));?>
      <small>
      <?php D(__('edit_proposal_tab_details_page_Instructions_info',"Give buyer a head start."));?>
      </small></h5>
    <p><small>
      <?php D(__('edit_proposal_tab_details_page_Instructions_info_description',"If you need to obtain information, files or other items from the buyer prior to starting your work, please add your instructions here. For example: Please send me your company name or Please send me the photo you need me to edit."));?>
      </small></p>
    <textarea name="buyer_instruction" id="buyer_instruction" rows="5" class="form-control"><?php if($proposal_details){D($proposal_details['proposal_additional']->buyer_instruction);}?>
</textarea>
  </div>
  <div class="form-group">
    <h5>
      <?php D(__('edit_proposal_tab_details_page_tags',"Proposal's Tags"));?>
      <small>
      <?php D(__('edit_proposal_tab_details_page_tags_info',"Type a comma after each tag."));?>
      </small></h5>
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
    <input type="text" name="proposal_tags" id="proposal_tags" placeholder="<?php D(__('edit_proposal_tab_details_page_tags_input',"Tags"));?>" data-role="tagsinput" value="<?php D(implode(',',$d_proposal_tags));?>" class="form-control">
    <span id="proposal_tagsError" class="rerror"></span> </div>
  <div class="form-group row d-none"><!--- form-group row Starts --->
    
    <h5>
      <?php D(__('edit_proposal_tab_details_page_Enable_Referrals',"Enable Referrals :"));?>
    </h5>
    <select name="proposal_enable_referrals" id="proposal_enable_referrals" class="proposal_enable_referrals form-control">
      <option value="0" <?php if($proposal_details && $proposal_details['proposal_settings']->proposal_enable_referrals!=1){D('selected');}?>> No </option>
      <option value="1" <?php if($proposal_details && $proposal_details['proposal_settings']->proposal_enable_referrals==1){D('selected');}?>> Yes </option>
    </select>
    <small class="form-text text-muted">
    <?php D(__('edit_proposal_tab_details_page_Enable_Referrals_info',"Enable or disable this option."));?>
    </small> <span id="proposal_enable_referralsError" class="rerror"></span> </div>
  <!--- form-group row Ends --->
  
  <div class="d-none form-group row proposal_referral_money" <?php if($proposal_details && $proposal_details['proposal_settings']->proposal_enable_referrals==1){D('style="display:flex"');} ?>><!--- form-group row Starts --->
    
    <h5>
      <?php D(__('edit_proposal_tab_details_page_Promotion_Commission',"Promotion Commission:"));?>
      <br>
      <small>
      <?php D(__('edit_proposal_tab_details_page_Promotion_Commission_info',"When another user promotes your proposal, how much would you like that user to get from the sale? (in dollars)"));?>
      </small></h5>
    <input type="number" name="proposal_referral_money" id="proposal_referral_money" class="form-control" min="1" value="<?php if($proposal_details && $proposal_details['proposal_settings']->proposal_enable_referrals==1){D($proposal_details['proposal_settings']->proposal_referral_money);}?>" placeholder="Figure should be in percentage e.g 20">
    <span id="proposal_referral_moneyError" class="rerror"></span> <small>
    <?php D(__('edit_proposal_tab_details_page_Promotion_Commission_note',"Figure should be in percentage. E.g 20 is the same as 20% from the sale of this proposal."));?>
    </small> </div>
  
  <!--- form-group row Ends --->
  
  <div class="form-group">
    <?php D(__('edit_proposal_tab_details_page_Delivery_Time',"Proposal\'s Delivery Time"));?>
    <select name="delivery_id" class="form-control" >
      <?php 

if($all_delivery_times){

	foreach($all_delivery_times as $k=>$delivery_times){

?>
      <option value="<?php D($delivery_times->delivery_id); ?>" <?php if($proposal_details && $proposal_details['proposal']->delivery_time==$delivery_times->delivery_id){D('selected');}?>>
      <?php D($delivery_times->delivery_proposal_title); ?>
      </option>
      <?php	

	}

}

?>
    </select>
  </div>
  <div class="form-group">
    <h5>
      <?php D(__('edit_proposal_tab_details_page_Image',"Add Proposal\'s Image"));?>
      <br>
      <small>
      <?php D(__('edit_proposal_tab_details_page_Image_info',"Supported image extentions include: \'gif\', \'png\', \'jpg\', \'jpeg\', \'tif\'."));?>
      </small></h5>
    <div class="uploadButton">
      <input type="file" class="uploadButton-input" name="proposal_img1" accept="image/*, application/pdf" id="proposal_img1" multiple/>
      <label class="uploadButton-button ripple-effect" for="proposal_img1">
        <?php D(__('global_Choose_File',"Upload Files"));?>
      </label>
    </div>
    <?php

if($proposal_details['proposal']->proposal_image){

$filejson=array(

	'file_name'=>$proposal_details['proposal']->proposal_image,

	'original_name'=>$proposal_details['proposal']->proposal_image,

	);

?>
    <div id="thumbnail_primary" class="thumbnail_sec mt-3" style="background-image: url('<?php D(URL_USERUPLOAD.'proposal-files/'.$proposal_details['proposal']->proposal_image);?>');">
      <input type="hidden" name="mainimageprevious" value='<?php D(json_encode($filejson))?>'>
      <a href="javascript:void(0)" class=" ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a></div>
    <?php }else{?>
    <div id="thumbnail_primary" class="thumbnail_sec"></div>
    <?php }?>
    <span id="mainimageError" class="rerror"></span> <small class="text-help"><i class="icon-feather-info"></i>
    <?php D(__('edit_proposal_tab_details_page_Image_note',"NB: Your Proposal image size must be 700 x 390 pixels and upto 25MB"));?>
    </small> </div>
  <div class="form-group"><!-- form-group row Starts -->
    
    <h5>
      <?php D(__('edit_proposal_tab_details_page_add_more_Image',"Add Proposal More Images"));?>
    </h5>
    <a href="#" data-toggle="collapse" data-target="#more-images" class="btn btn-site mb-2">
    <?php D(__('edit_proposal_tab_details_page_add_more_Image_btn',"Add More Images"));?>
    </a>
    <div id="more-images" class="collapse">
      <input type="file" name="fileinput" id="fileinput" multiple>
      <div class="upload-area" id="uploadfile">
        <h4>
          <?php D(__('edit_proposal_tab_details_page_add_more_Image_drag_text',"Drag and Drop file here<br>Or<br>Click to select file"));?>
        </h4>
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
        <div id="thumbnail_<?php D($inc)?>" class="thumbnail_sec" style="background-image: url('<?php D(URL_USERUPLOAD.'proposal-files/'.$files->server_name);?>');">
          <input type="hidden" name="projectfileprevious[]" value='<?php D(json_encode($filejson))?>'>
          <a href="javascript:void(0)" class="  ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"><i class="fa fa-trash"></i></a></div>
        <?php

	}

   	

   }?>
      </div>
    </div>
  </div>
  <div class="form-group">
    <h5>
      <?php D(__('edit_proposal_tab_details_page_Video',"Add Proposal Video (Optional)"));?>
      <br>
      <small>
      <?php D(__('edit_proposal_tab_details_page_Video_info',"Supported video extentions include: \'mp4\', \'mov\', \'avi\', \'flv\', \'wmv\'."));?>
      </small> </h5>
    <div class="uploadButton">
      <input type="file" class="uploadButton-input" name="proposal_video" accept="image/*, application/pdf" id="fileinputvideo" multiple/>
      <label class="uploadButton-button ripple-effect" for="fileinputvideo">
        <?php D(__('global_Choose_File',"Upload Files"));?>
      </label>
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
        <input type="hidden" name="videoprevious" value='<?php D(json_encode($filejson))?>'>
        <a href="javascript:void(0)" class=" ripple-effect ico btn btn-sm btn-circle  btn-danger" onclick="$(this).parent().remove()"> <i class="icon-feather-trash"></i></a> </div>
      <?php }?>
    </div>
    <small class="text-help"><i class="icon-feather-info"></i>
    <?php D(__('edit_proposal_tab_details_page_Video_note',"Maximum size 25MB"));?>
    </small> </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-field">
        <h5>
          <?php D(__('edit_proposal_tab_details_page_Price_Type',"Price Type"));?>
        </h5>
        <select class="pricing form-control" name="is_fixed">
          <option value="0" <?php if($proposal_details['proposal']->proposal_price>0){}else{D('selected');}?>>
          <?php D(__('edit_proposal_tab_details_page_Price_Type_Packages',"Packages"));?>
          </option>
          <option value="1" <?php if($proposal_details['proposal']->proposal_price>0){D('selected');}?>>
          <?php D(__('edit_proposal_tab_details_page_Price_Type_Fixed_Price',"Fixed Price"));?>
          </option>
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-field proposal-price" <?php if($proposal_details['proposal']->proposal_price>0){}else{D('style="display:none"');}?>>
        <label class="form-label">Fixed amount</label>
        <div class="input-group form-curb">
          <div class="input-group-prepend"> <span class="input-group-text">
            <?php D(CURRENCY); ?>
            </span> </div>
          <input type="text" class="form-control" id="proposal_price" name="proposal_price" value="<?php D($proposal_details['proposal']->proposal_price); ?>" onkeypress="return isNumberKey(event)">
        </div>
        <span id="proposal_priceError" class="rerror"></span> 
        
        <!--<small>If you want to use packages, you need to set this field value to 0. </small>--> 
        
      </div>
    </div>
  </div>
  </div>
  </div>
  <div class="card mb-4">
  <div class="card-body">
  <div class="packages row-column-3" <?php if($proposal_details['proposal']->proposal_price>0){D('style="display:none"');}?>>
    <div class="row">
      <?php

    if($proposal_details['proposal_packages']){

	$j=0;

	foreach($proposal_details['proposal_packages'] as $package){

		$j++;

	?>
      
      <div class="col-md-4 col-12">
      <input type="hidden" name="package_<?php D($j); ?>" value="<?php D($package->package_id); ?>"/>
        <div class="card package mb-4 js-gig-packages">
          <div class="card-header">
            <h4>
              <?php D(__('edit_proposal_tab_details_page_package_'.$package->package_name,$package->package_name)); ?>
            </h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label>Description</label>
              <textarea rows="4" name="package_desc_<?php D($j); ?>" id="package_desc_<?php D($j); ?>" class="form-control description-value-<?php D($package->package_id); ?>"><?php D($package->description); ?>
</textarea>
              <small class="text-help"><i class="icon-feather-info"></i>
              <?php D(__('edit_proposal_tab_details_page_package_description_note',"Minimum 70 characters in length"));?>
              </small> <span id="package_desc_<?php D($j); ?>Error" class="rerror"></span> </div>
            <?php

    $arr=array(

        'select'=>'p.attribute_id,p.attribute_name,p.attribute_value',

        'table'=>'proposal_package_attributes p',

        'where'=>array('p.package_id'=>$package->package_id),

        'order'=>array(array('p.attribute_id','asc')),

    );

    $attribute=getData($arr);

    if($attribute){

        foreach($attribute as $a=>$attributeData){

            $a++;

            ?>
            <div class="form-group newattribute attribute_<?php D($a); ?>" data-attr-id="<?php D($a); ?>">
              <p><small>
              <?php D($attributeData->attribute_name); ?>
              <input type="hidden" name="attribute_count[]" value="<?php D($a); ?>">
              <input type="hidden" name="attribute_name_<?php D($a); ?>[]" value="<?php D($attributeData->attribute_name); ?>">
              </small></p>
              <div class="input-group">
                <input class="form-control attribute-value-<?php D($j); ?>" value="<?php D($attributeData->attribute_value); ?>" data-attribute="<?php D($j); ?>" name="attribute_value_<?php D($j); ?>_<?php D($a);?>" id="attribute_value_<?php D($j); ?>_<?php D($a);?>">
                <div class="input-group-append">
                  <button class="btn btn btn-outline-danger" type="button" data-attribute="<?php D($attributeData->attribute_name); ?>" onclick="$('.attribute_<?php D($a); ?>').remove();"> <i class="icon-feather-trash"></i> </button>
                </div>
              </div>
            </div>
            <?php

        }

    }

    ?>
            <div class="form-group time_row" data-row-id="<?php D($j); ?>">
              <label>
                <?php D(__('edit_proposal_tab_details_page_package_Delivery_Time',"Delivery Time"));?>
              </label>
              <div class="input-group">
                <?php /*?><input onkeypress="return isNumberKey(event)" name="package_time_<?php D($j); ?>" id="package_time_<?php D($j); ?>" class="form-control delivery-time-value-<?php D($package->package_id); ?>" value="<?php D($package->delivery_time); ?>" ><?php */?>
                <input id="package_time_<?php D($j); ?>" class="form-control delivery-time-value-<?php D($package->package_id); ?>" value="<?php D($package->delivery_time); ?>" onkeypress="return isNumberKey(event)" name="package_time_<?php D($j); ?>">
              </div>
            </div>

            <div class="extraoption extraoption_<?php D($j); ?>"></div>

            <label>
              <?php D(__('edit_proposal_tab_details_page_package_Price',"Price"));?>
            </label>
            <div class="input-group form-curb">
              <div class="input-group-prepend"> <span class="input-group-text">
                <?php D(CURRENCY); ?>
                </span> </div>
              <input onkeypress="return isNumberKey(event)" name="package_price_<?php D($j); ?>" id="package_price_<?php D($j); ?>"  class="form-control price-value-<?php D($package->package_id); ?>" value="<?php D($package->price); ?>" >
            </div>
            <div class="d-none">
              <button type="button" class="btn btn btn-success save-package" data-package="<?php D($package->package_id); ?>"> <i class="fa fa-floppy-o"></i>
              <?php D(__('edit_proposal_tab_details_page_Update_Package_Details',"Update Package Details"));?>
              </button>
            </div>
          </div>
        </div>
      </div>
      <?php

	}

}

?>
    </div>
    <div class="space5"></div>
    <div class="form-group add-attribute">
      <div class="input-group">          
          <div class="input-group-prepend">
            <button class="btn btn-outline-site insert-attribute" type="button"> <i class="icon-feather-upload" aria-hidden="true"></i> <?php D(__('edit_proposal_tab_details_page_Add_New_Attribute_btn',"Insert"));?>
            </button>
          </div>
          <input class="form-control attribute-name" placeholder="<?php D(__('edit_proposal_tab_details_page_Add_New_Attribute_input',"Add New Attribute"));?>" name="">
        </div>
    </div>
  </div>
  </div>
  </div>
  <button type="submit" name="update" class="btn btn-site saveBTN">
  <?php D(__('edit_proposal_tab_details_page_Update_Proposal',"Update Proposal"));?>
  </button>
</form>
