<div class="card">
  <div class="card-body">
    <form action="" method="post" accept-charset="utf-8" id="profileform" class="form-horizontal" role="form" name="profileform" onsubmit="saveProfile(this);return false;">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-field">
            <label class="form-label">
              <?php D(__('profile_settings_page_Full_Name',"Full Name"));?>
            </label>
            <input type="text" name="seller_name" id="seller_name" value="<?php if($member_details){D($member_details['member']->member_name);} ?>" class="form-control" disabled>
            <span id="seller_nameError" class="rerror"></span> </div>
        </div>
        <div class="col-sm-6">
          <div class="form-field">
            <label class="form-label">
              <?php D(__('profile_settings_page_Nationality',"Nationality"));?>
            </label>
            <select name="seller_country" id="seller_country" class="form-control" disabled>
              <option value="" class="hidden">
              <?php D(__('profile_settings_page_Select_Nationality',"Select Nationality"));?>
              </option>
              <?php 
    
                    if($all_country){
    
                        foreach($all_country as $k=>$country){
    
                    ?>
              <option value="<?php D($country->country_code); ?>" <?php if($member_details && $member_details['member_address'] && $member_details['member_address']->member_country==$country->country_code){D('selected');} ?>>
              <?php D($country->country_name); ?>
              </option>
              <?php	
    
                        }
    
                    }
    
                ?>
            </select>
            <span id="seller_countryError" class="rerror"></span> </div>
        </div>
      </div>
      <div class="form-group row d-none">
        <label class="col-md-3 col-form-label">Notification Email </label>
        <div class="col-md-9">
          <input type="text" name="seller_email" id="seller_email" value="<?php if($member_details){D($member_details['member']->member_email);} ?>" class="form-control" >
          <span id="seller_emailError" class="rerror"></span> </div>
      </div>
      <?php /*?><div class="form-group row">

		<label class="col-md-3 col-form-label"> <?php D(__('profile_settings_page_Nationality',"Nationality"));?> </label>

		<div class="col-md-9">

			<select name="seller_nationality" id="seller_nationality" class="form-control" <?php if($member_details && $member_details['member_address'] && $member_details['member_address']->member_nationality){D('disabled');} ?>>

				<option value="" class="hidden"> <?php D(__('profile_settings_page_Select_Nationality',"Select Nationality"));?> </option>

				<?php 

				if($all_nationality){

					foreach($all_nationality as $k=>$nationality){

				?>

				<option value="<?php D($nationality->nationality_id); ?>" <?php if($member_details && $member_details['member_address'] && $member_details['member_address']->member_nationality==$nationality->nationality_id){D('selected');} ?>><?php D($nationality->nationality_name); ?> </option>

				<?php	

					}

				}

			?>

			</select>

			<span id="seller_nationalityError" class="rerror"></span>

		</div>

	</div><?php */?>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-field">
            <label class="form-label">
              <?php D(__('profile_settings_page_Country',"Country"));?>
            </label>
            <select name="seller_nationality" id="seller_nationality" class="form-control" <?php if($member_details && $member_details['member_address'] && $member_details['member_address']->member_nationality){D('disabled');} ?>>
              <option value="" class="hidden">
              <?php D(__('profile_settings_page_Select_Country',"Select Country"));?>
              </option>
              <?php 

				if($all_country){

					foreach($all_country as $k=>$country){

				?>
              <option value="<?php D($country->country_code); ?>" <?php if($member_details && $member_details['member_address'] && $member_details['member_address']->member_nationality==$country->country_code){D('selected');} ?>>
              <?php D($country->country_name); ?>
              </option>
              <?php	

					}

				}

			?>
            </select>
            <span id="seller_nationalityError" class="rerror"></span> </div>
        </div>
        <div class="col-sm-6">
          <div class="form-field">
            <label class="form-label">
              <?php D(__('profile_settings_page_City',"City"));?>
            </label>
            <input name="seller_city" id="seller_city"  class="form-control" value="<?php if($member_details && $member_details['member_basic']){D($member_details['member_address']->member_city);} ?>">
            <span id="seller_cityError" class="rerror"></span> </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-field">
            <label class="form-label">
              <?php D(__('profile_settings_page_Phone',"Phone"));?>
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <select class="form-control skip-valid input-group-text" name="seller_mobile_code" id="seller_mobile_code" style="width:100px" >
                  <option value="">
                  <?php D(__('profile_settings_page_Select_Code',"Select"));?>
                  </option>
                  <?php 

					if($all_mobile_codes){

						foreach($all_mobile_codes as $k=>$codes){

					?>
                  <option value="<?php D($codes->codes); ?>" <?php if($member_details && $member_details['member_basic'] && $member_details['member_basic']->member_mobile_code==$codes->codes){D('selected');} ?>>
                  <?php D($codes->codes); ?>
                  </option>
                  <?php	

						}

					}

				?>
                </select>
              </div>
              <input name="seller_phone" id="seller_phone"  class="form-control" value="<?php if($member_details && $member_details['member_basic']){D($member_details['member_basic']->member_phone);} ?>">
            </div>
            <!--<span id="seller_mobile_codeError" class="rerror"></span>--> 
            <span id="seller_phoneError" class="rerror"></span> </div>
        </div>
        <div class="col-sm-6">
          <div class="form-field">
            <label class="form-label">
              <?php D(__('profile_settings_page_Gender',"Gender"));?>
            </label>
            <select name="seller_gender" id="seller_gender" class="form-control">
              <option value="" class="hidden">
              <?php D(__('profile_settings_page_Select_Gender',"Select Gender"));?>
              </option>
              <option value="F" <?php if($member_details && $member_details['member_basic'] && $member_details['member_basic']->member_gender=='F'){D('selected');} ?>>
              <?php D(__('profile_settings_page_Gender_Female','Female')); ?>
              </option>
              <option value="M" <?php if($member_details && $member_details['member_basic'] && $member_details['member_basic']->member_gender=='M'){D('selected');} ?>>
              <?php D(__('profile_settings_page_Gender_Male','Male')); ?>
              </option>
            </select>
            <span id="seller_genderError" class="rerror"></span> </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-field">
            <label class="form-label">
              <?php D(__('profile_settings_page_Conversational_Language',"Main Conversational Language"));?>
            </label>
            <select name="seller_language" id="seller_language" class="form-control">
              <option value="" class="hidden">
              <?php D(__('profile_settings_page_Select_Language',"Select Language"));?>
              </option>
              <?php 

				if($all_languages){

					foreach($all_languages as $k=>$languages){

				?>
              <option value="<?php D($languages->language_id); ?>" <?php if($member_details && $member_details['member_basic'] && $member_details['member_basic']->prefer_language==$languages->language_id){D('selected');} ?>>
              <?php D($languages->language_title); ?>
              </option>
              <?php	

					}

				}

												?>
            </select>
            <span id="seller_languageError" class="rerror"></span> </div>
        </div>
        <div class="col-sm-6">
          <div class="form-field"> </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-field">
            <label class="form-label">
              <?php D(__('profile_settings_page_Profile_Photo',"Profile Photo"));?>
            </label>
            <div class="input-group">              
              <div class="custom-file upload-file upload-image">
                <input type="file" class="custom-file-input" name="profile_photo" id="profile_photo">
                <label class="custom-file-label" for="profile_photo"><i class="icon-feather-upload mr-1"></i> <?php D(__('global_Choose_File',"Upload Files"));?></label>
              </div>
            </div>
            <p class="text-help mt-2"><i class="icon-feather-info"></i>
              <?php D(__('profile_settings_page_Profile_Photo_info',"This photo is your identity on ".$get_site_name.". It appears on your profile, messages and proposals/services pages."));?></p>
            <div id="thumbnail_logo">
              <?php if($member_details['member_logo'] && $member_details['member_logo']->logo && file_exists(ABS_USERUPLOAD_PATH.'member_logo/'.$member_details['member_logo']->logo)){

				/*$filejson=array(

					'file_name'=>$member_details['member_logo']->logo,

					'original_name'=>$member_details['member_logo']->logo,

				);*/

				?>
              
              <!--<input type="hidden" name="userlogo" value='<?php D(json_encode($filejson))?>'>--> 
              
              <img src="<?php D(URL_USERUPLOAD.'member_logo/'.$member_details['member_logo']->logo)?>" height="80" width="80" class="rounded">
              <?php }else{?>
              <img src="<?php D(theme_url().IMAGE)?>default/empty-image.png" height="80" width="80" class="rounded">
              <?php }?>
            </div>
            <small class="text-help"><i class="icon-feather-info"></i>
            <?php D(__('profile_settings_page_Profile_Photo_note',"Maximum size 25MB"));?>
            </small> </div>
        </div>
        <div class="col-sm-6">
          <div class="form-field">
            <label class="form-label">
              <?php D(__('profile_settings_page_Cover_Photo',"Cover Photo"));?>
            </label>
            <div class="input-group">              
              <div class="custom-file upload-file upload-image">
                <input type="file" class="custom-file-input" name="cover_photo" id="cover">
                <label class="custom-file-label" for=""><i class="icon-feather-upload mr-1"></i> <?php D(__('global_Choose_File',"Upload Files"));?></label>
              </div>
            </div>
            
            <p class="text-help mt-2" style="min-height:37px"><i class="icon-feather-info"></i>
              <?php D(__('profile_settings_page_Cover_Photo_info',"This is your cover photo on your"));?>
              <a target="_blank" class="text-success" href="<?php D(get_link('viewprofileURL').$access_username)?>">
              <?php D(__('profile_settings_page_Profile_Page',"Profile Page"));?>
              </a></p>
            <div id="thumbnail_banner">
              <?php if($member_details['member_logo'] && $member_details['member_logo']->banner && file_exists(ABS_USERUPLOAD_PATH.'member_banner/'.$member_details['member_logo']->banner)){

				/*$filejson=array(

					'file_name'=>$member_details['member_logo']->banner,

					'original_name'=>$member_details['member_logo']->banner,

				);*/

				?>              
              <!--<input type="hidden" name="userbanner" value='<?php D(json_encode($filejson))?>'>-->               
              <img src="<?php D(URL_USERUPLOAD.'member_banner/'.$member_details['member_logo']->banner)?>" height="80" width="160" class="rounded">
              <?php }else{?>
              <img src="<?php D(theme_url().IMAGE)?>default/empty-cover.png" height="80" width="160" class="rounded">
              <?php }?>
            </div>
            <small class="text-help"><i class="icon-feather-info"></i>
            <?php D(__('profile_settings_page_Cover_Photo_note',"Maximum size 25MB"));?>
            </small> </div>
        </div>
      </div>
      
      <div class="form-group">
        <label class="form-label">
          <?php D(__('profile_settings_page_Headline',"Headline"));?>
        </label>
        <textarea name="seller_headline" id="textarea-headline" rows="2" class="form-control" maxlength="150"><?php if($member_details && $member_details['member_basic']){D($member_details['member_basic']->member_heading);} ?>
</textarea>
          <span class="float-right mt-1"> <span class="count-headline"> 0 </span> / 150
          <?php D(__('profile_settings_page_MAX',"MAX"));?>
          </span>
      </div>
      <div class="form-group">
        <label class="form-label">
          <?php D(__('profile_settings_page_Description',"Description"));?>
        </label>
          <textarea name="seller_about" id="textarea-about" rows="4" class="form-control" maxlength="300" placeholder="Tell us something about yourself..."><?php if($member_details && $member_details['member_basic']){D($member_details['member_basic']->member_overview);} ?>
</textarea>
          <span class="float-right mt-1"> <span class="count-about"> 0 </span> / 300
          <?php D(__('profile_settings_page_MAX',"MAX"));?>
          </span>
      </div>
      <button type="submit" name="submit" class="btn btn-site saveBTN">
	  	<?php D(__('profile_settings_page_Save_Changes',"Save Changes"));?>
      </button>
    </form>
  </div>
</div>
<div id="insertimageModal" class="modal" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content mycustom-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark pull-left" data-dismiss="modal">
        <?php D(__('global_Close',"Close"));?>
        </button>
        <h4 class="modal-title">
          <?php D(__('modal_insertimage_heading',"Crop & Insert Image"));?>
        </h4>
        <button class="btn btn-site pull-right crop_image">
        <?php D(__('modal_insertimage_Crop_Image',"Crop Image"));?>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="img_type" value="">
        <div id="image_demo" style="width:100% !important;"></div>
      </div>
    </div>
  </div>
</div>
<div id="wait"></div>
