<div class="row justify-content-center">
  <!-- row Starts -->
  <div class="col-md-9">
    <!--- col-md-10 Starts --->
    <div class="tabs accordion mt-2" id="allTabs">
      <!--- All Tabs Starts --->
      <?php 
      if($proposal_details['proposal_extras']){
	  	foreach($proposal_details['proposal_extras'] as $extra){
		?>
		      <div class="tab rounded border-1">
        <!-- tab rounded Starts -->
        <div class="tab-header" data-toggle="collapse" href="#tab-<?php D($extra->id); ?>">
          <a class="float-left"> <i class="fa fa-bars mr-2"></i> <?php D($extra->name); ?></a>
          <a class="float-right text-muted"><i class="fa fa-sort-down"></i></a>
          <div class="clearfix"></div>
        </div>
        <div class="tab-body p-3 pb-0 collapse" id="tab-<?php D($extra->id); ?>" data-parent="#allTabs">
			<form action="" method="post" accept-charset="utf-8" id="postproposalextraform_<?php D($extra->id); ?>" class="form-horizontal" role="form" name="postproposalform_<?php D($extra->id); ?>" onsubmit="saveProposalExtra(this);return false;">
				<input type="hidden" name="pid" value="<?php D($proposal_details['proposal']->proposal_id);?>"/>
				<input type="hidden" name="token" value="<?php D($token);?>"/>
				<input type="hidden" name="tab" value="extraupdate"/>
            <div class="form-group">
              <input type="hidden" name="id" value="<?php D($extra->id); ?>">
              <input type="text" name="extraname" id="extraname" placeholder="Extra Name" class="form-control" value="<?php D($extra->name); ?>" >
               <span id="extranameError" class="rerror"></span>
            </div>
            <div class="form-group">
              <div class="input-group">
                <!--- input-group Starts --->
                  <span class="input-group-addon"><?php D(CURRENCY); ?></span>
                <input type="number" name="extraprice" id="extraprice" placeholder="Extra Price" value="<?php D($extra->price); ?>" class="form-control" >
              </div>
              <span id="extrapriceError" class="rerror"></span>
              <!--- input-group Ends --->
            </div>
            <div class="form-group">
              <button type="button" name="delete_extra" class="btn btn-danger" onclick='deleteExtra(this)' data-id="<?php D($extra->id);?>">Delete</button>
              <button type="submit" name="update_extra" class="btn btn-success float-right saveBTN">Save</button>
            </div>
          </form>
        </div>
      </div>
		<?php	
		}
	  }
      ?>

      <!-- tab rounded Ends -->

      <div class="tab">
        <!-- tab rounded Starts -->
        <div class="tab-body rounded border-1 p-3 pb-0 collapse" id="insert-extra" data-parent="#allTabs">
          <form action="" method="post" accept-charset="utf-8" id="postproposalextraaddform" class="form-horizontal" role="form" name="postproposalextraaddform" onsubmit="saveProposalExtra(this);return false;">
				<input type="hidden" name="pid" value="<?php D($proposal_details['proposal']->proposal_id);?>"/>
				<input type="hidden" name="token" value="<?php D($token);?>"/>
				<input type="hidden" name="tab" value="extraadd"/>
            <div class="form-group">
              <input type="text" name="extraname" id="extraname" placeholder="Extra Name" class="form-control" >
              <span id="extranameError" class="rerror"></span>
            </div>
            <div class="form-group">
              <div class="input-group">
                <!--- input-group Starts --->
                  <span class="input-group-addon"><?php D(CURRENCY); ?></span>
                <input type="number" name="extraprice" id="extraprice" placeholder="Extra Price" class="form-control" >
                
              </div>
              <span id="extrapriceError" class="rerror"></span>
              <!--- input-group Ends --->
            </div>
            <div class="form-group">
              <button type="submit" name="insert_extra" class="btn btn-success float-right saveBTN">Insert</button>
              <div class="clearfix"></div>
            </div>
          </form>
        </div>
      </div>
      <!-- tab rounded Ends -->
    </div>
    <!--- All Tabs Ends --->
    <button data-toggle="collapse" href="#insert-extra" class="btn btn-success btn-block ">Add More +</button>
  </div>
  <!--- col-md-10 Ends --->
</div>