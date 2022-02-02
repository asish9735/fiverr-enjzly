<?php
$section_data=array(
    array('name'=>'Logo transparency','key'=>'logo_transparency','tooltip'=>"You'll get a logo image with a transparent background. Ex. PNG"),
    array('name'=>'Vector file','key'=>'vector_file','tooltip'=>"You'll get a vector-based logo image that can be scaled without loss of quality or pixelation. Ex. EPS, AI, and PDF"),
    array('name'=>'Printable file','key'=>'printable_file','tooltip'=>"You'll get a high-resolution logo file suitable for printing—at least 300 dpi or 2000 px."),
    array('name'=>'3D mockup','key'=>'3d_mockup','tooltip'=>"You'll get a 3D mockup of your logo design to use for promotional purposes."),
    array('name'=>'Source file','key'=>'source_file','tooltip'=>"You'll get an original source file that you can edit according to your needs."),
    array('name'=>'Social media kit','key'=>'social_media_kit','tooltip'=>"You'll get graphics showing your logo that you can use on social media platforms. Ex. Facebook and Instagram."),
    array('name'=>'No. of concepts included','key'=>'no_of_concept','tooltip'=>"A number of logo concepts are included in the package and, from this, you'll get one final logo design."),
    array('name'=>'Revisions','key'=>'revisions','tooltip'=>"The number of tweaks the seller includes."),
  
);
//dd($all_attr_values,true);
?>
<?php
foreach($section_data as $sectionrow){
?>
    <div class="form-field">
        <label class="form-label mb-0"><?php echo $sectionrow['name']?><i class="icon-material-outline-info text-muted ml-1" data-toggle="popover" data-trigger="hover" data-content="<?php echo $sectionrow['tooltip']?>" data-placement="top"></i></label>
        <div class="input-group">
            <?php
            if($sectionrow['key']=='no_of_concept' || $sectionrow['key']=='revisions'){
                $selected='';
                if($all_attr_values && array_key_exists($sectionrow['key'],$all_attr_values)){
                  $selected=$all_attr_values[$sectionrow['key']][$section-1];                   
                } 
            ?>
            <select class="form-control mt-2" id="<?php echo $sectionrow['key']?>_<?php echo $section;?>" name="<?php echo $sectionrow['key']?>_<?php echo $section;?>">
                <option value="">Select</option>
                <?php
                for($i=0;$i<10;$i++){
                ?>
                    <option value="<?php echo $i;?>" <?php if($selected==$i){echo 'selected';}?>><?php echo $i;?></option>
                <?php
                }
                ?>
                <option value="Unlimited" <?php if($selected=='Unlimited'){echo 'selected';}?>>Unlimited</option>
            </select>
            <?php
            }
            else{
                $checked='';
                if($all_attr_values && array_key_exists($sectionrow['key'],$all_attr_values)){
                    if($all_attr_values[$sectionrow['key']][$section-1]=='1'){
                        $checked='checked';
                    }
                }
            ?>
            <div class="radio radio-inline">
                <input type="radio" <?php echo $checked;?> id="<?php echo $sectionrow['key']?>_yes_<?php echo $section;?>" name="<?php echo $sectionrow['key']?>_<?php echo $section;?>" value="0" checked> 
                <label for="<?php echo $sectionrow['key']?>_yes_<?php echo $section;?>" class="mb-0">
                    <span class="radio-label"></span> Yes
                </label>
            </div>
            <div class="radio radio-inline ml-3">
                <input type="radio" id="<?php echo $sectionrow['key']?>_no_<?php echo $section;?>" name="<?php echo $sectionrow['key']?>_<?php echo $section;?>" value="1"> 
                <label for="<?php echo $sectionrow['key']?>_no_<?php echo $section;?>" class="mb-0">
                    <span class="radio-label"></span> No
                </label>
            </div>
            <?php 
            }
            ?>
        </div>
    </div>
<?php
}
?>
