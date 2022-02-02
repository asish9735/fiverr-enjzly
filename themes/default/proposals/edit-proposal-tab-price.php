<form action="" method="post" accept-charset="utf-8" id="postproposalpriceFixedform" class="form-horizontal" role="form" name="postproposalpriceFixedform" onsubmit="saveProposalPriceFixed(this);return false;">
<input type="hidden" name="pid" value="<?php D($proposal_details['proposal']->proposal_id);?>"/>
<input type="hidden" name="token" value="<?php D($token);?>"/>
<input type="hidden" name="tab" value="pricefixed"/>

<div class="form-group row">

<div class="col-md-3 control-label h6">  </div>

<div class="col-md-6">

<center><h5 class="pb-1 ">Packages or Fixed Price</h5></center>

<select class="pricing form-control">
	<option value="p-packages" <?php if($proposal_details['proposal']->proposal_price>0){}else{D('selected');}?>> Packages </option>
	<option value="fixed-price" <?php if($proposal_details['proposal']->proposal_price>0){D('selected');}?>> Fixed Price </option>
</select>

</div>

</div>

<div class="form-group row proposal-price" <?php if($proposal_details['proposal']->proposal_price>0){}else{D('style="display:none"');}?>>

<div class="col-md-3 control-label h6">  </div>

<div class="col-md-6">

<div class="input-group form-curb">

    <span class="input-group-addon font-weight-bold">
    <?php D(CURRENCY); ?>
    </span>

<input type="text" class="form-control" id="proposal_price" name="proposal_price" value="<?php D($proposal_details['proposal']->proposal_price); ?>">
</div>
<span id="proposal_priceError" class="rerror"></span>
<small>If you want to use packages, you need to set this field value to 0. </small>

</div>

</div>

<div class="form-group row proposal-price" <?php if($proposal_details['proposal']->proposal_price>0){}else{D('style="display:none"');}?>>

<div class="col-md-3 control-label h6">  </div>

<div class="col-md-6">
<button type="submit" name="update_price" class="form-control btn btn-success saveBTN"> Insert Price </button>

</div>

</div>

</form>
<div class="packages">
<form action="" method="post" accept-charset="utf-8" id="postproposalpricePakageform" class="form-horizontal" role="form" name="postproposalpricePakageform" onsubmit="saveProposalPricePackage(this);return false;">
<div class="row" <?php if($proposal_details['proposal']->proposal_price>0){D('style="display:none"');}?>>

<input type="hidden" name="pid" value="<?php D($proposal_details['proposal']->proposal_id);?>"/>
<input type="hidden" name="token" value="<?php D($token);?>"/>
<input type="hidden" name="tab" value="savepackageall"/>
<?php
if($proposal_details['proposal_packages']){
	$j=0;
	foreach($proposal_details['proposal_packages'] as $package){
		$j++;
		?>
	
<div class="col-md-4 package">
<input type="hidden" name="package_<?php D($j); ?>" value="<?php D($package->package_id); ?>"/>	
<table class="table table-bordered js-gig-packages">

<tr>
<td><h4><?php D($package->package_name); ?></h4></td>
</tr>
<tr>
<td><textarea name="package_desc_<?php D($j); ?>" id="package_desc_<?php D($j); ?>" class="form-control description-value-<?php D($package->package_id); ?>"><?php D($package->description); ?></textarea>
<small class="text-info"><i class="fa fa-info-circle"></i> Minimum 70 characters in length</small>
<span id="package_desc_<?php D($j); ?>Error" class="rerror"></span>
</td>
</tr>
<?php
$arr=array(
	'select'=>'p.attribute_id,p.attribute_name,p.attribute_value',
	'table'=>'proposal_package_attributes p',
	'where'=>array('p.package_id'=>$package->package_id),
	'order'=>array(array('p.attribute_id','asc')),
);
$attribute=getData($arr);
if($attribute){
	foreach($attribute as $attributeData){
		?>
	<tr>
		<td>
			<small><?php D($attributeData->attribute_name); ?></small>
			<div class="input-group">
				<input class="form-control attribute-value-<?php D($attributeData->attribute_id); ?>" value="<?php D($attributeData->attribute_value); ?>" data-attribute="<?php D($attributeData->attribute_id); ?>">
                <div class="input-group-append">
				<button class="btn btn btn-success save-attribute" data-attribute="<?php D($attributeData->attribute_id); ?>">
					<i class="fa fa-floppy-o"></i>&nbsp;Save
				</button>
				<button class="btn btn btn-success delete-attribute" data-attribute="<?php D($attributeData->attribute_name); ?>">
					<i class="fa fa-trash"></i>
				</button>
                </div>
			</div>
		</td>
	</tr>	
		<?php
	}
}
?>

<tr>

<td>

<small>Delivery Time</small>
 
<div class="input-group">
<input onkeypress="return isNumberKey(event)" name="package_time_<?php D($j); ?>" id="package_time_<?php D($j); ?>" class="form-control delivery-time-value-<?php D($package->package_id); ?>" value="<?php D($package->delivery_time); ?>" name="<?php echo $attribute_id; ?>">

</div>
 
</td>

</tr>


<tr>

<td>

<small>Price</small>
 
<div class="input-group">

<input onkeypress="return isNumberKey(event)" name="package_price_<?php D($j); ?>" id="package_price_<?php D($j); ?>"  class="form-control price-value-<?php D($package->package_id); ?>" value="<?php D($package->price); ?>" >

</div>
 
</td>

</tr>

<tr class=" d-none">

<td align="center">
 
<button type="button" class="btn btn btn-success save-package" data-package="<?php D($package->package_id); ?>">
<i class="fa fa-floppy-o"></i> Update Package Details
</button>
 
</td>

</tr>

</table>

</div>		
		<?php
	}
}
?>
</div>
<div class="row">
<div class="col-md-4 control-label h6">  </div>

<div class="col-md-4">
<button type="submit" name="update_package" class="form-control btn btn-success saveBTN"><i class="fa fa-floppy-o"></i>  Update Package Details</button>

</div>
</div>
</form>
</div>

<div class="space5"></div>
<div class="form-group row add-attribute" <?php if($proposal_details['proposal']->proposal_price>0){D('style="display:none"');}?>>

<label class="col-md-3 control-label"> </label>

<div class="col-md-6">

<div class="input-group">
<input class="form-control attribute-name" placeholder="Add New Attribute" name="<?php echo $attribute_name; ?>">
<div class="input-group-append">
<button class="btn btn btn-success insert-attribute" >
<i class="fa fa-cloud-upload" aria-hidden="true"></i> &nbsp;Insert 
</button>
</div>
</div>

</div>

</div>
