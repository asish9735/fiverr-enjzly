<?php if($page == 'add'){ ?>

<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal" aria-label="Close">

	  <span aria-hidden="true">&times;</span></button>

	<h4 class="modal-title"><?php echo $title;?></h4>

</div>

<div class="modal-body">

		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">

              <div class="box-body">

				

				<div class="form-group">

                  <label for="name">Name </label>

                  <input type="text" class="form-control reset_field" id="name" name="member_name" autocomplete="off">

                </div>

               

			  

			  <div class="form-group">

				<div>

			     <input type="checkbox" name="add_more" value="1" class="magic-checkbox" id="add_more">

                  <label for="add_more">Add more record</label>

				</div>

              </div>

			  

              </div>

              <!-- /.box-body -->

			  <div class="box-footer">

                <button type="submit" class="btn-block btn btn-primary">Add</button>

              </div>

        </form>

</div>



<script>



init_plugin();



function submitForm(form, evt){

	evt.preventDefault();

	ajaxSubmit($(form), onsuccess);

}



function onsuccess(res){

	if(res.cmd){

		if(res.cmd == 'reload'){

			location.reload();

		}else if(res.cmd == 'reset_form'){

			var form = $('#add_form');

			form.find('.reset_field').val('');

		}		

		

	}

}



</script>

<?php } ?>



<?php if($page == 'edit'){ ?>

<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal" aria-label="Close">

	  <span aria-hidden="true">&times;</span></button>

	<h4 class="modal-title"><?php echo $title;?></h4>

</div>

<div class="modal-body">

		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">

			  <input type="hidden" name="ID" value="<?php echo $ID?>"/>

              <div class="box-body">

			  

				<div class="form-group">

                  <label for="name">Title </label>

                  <input type="text" class="form-control reset_field" id="proposal_title" name="proposal_title" autocomplete="off" value="<?php echo !empty($detail['proposal_title']) ? $detail['proposal_title'] : ''; ?>">

                </div>
                <div class="form-group">

                  <label for="member_country">Category </label>

                 <select class="form-control" name="category_id" id="category_id" onchange="getSubcategory(this.value)">
					<option value="">-select-</option>
					<?php print_select_option($category, 'category_id', 'name', !empty($detail['category_id']) ? $detail['category_id'] : '');?>
				</select>

                </div>

				<div class="form-group">

                  <label for="member_country">Sub Category </label>
				<div id="subcategory_section">
                 <select class="form-control" name="category_subchild_id">
					<option value="">-select-</option>
					<?php print_select_option($sub_category, 'category_subchild_id', 'name', !empty($detail['category_subchild_id']) ? $detail['category_subchild_id'] : '');?>
				</select>
				</div>
                </div>

			  

              </div>

              <!-- /.box-body -->

			  <div class="box-footer">

                <button type="submit" class="btn-block btn btn-primary">Save</button>

              </div>

        </form>

</div>



<script>

function getSubcategory(id){
	$.get('<?php echo base_url('proposal/load_ajax_page');?>?page=subcat&id='+id, function(res){
			$('#subcategory_section').html(res);
	});
}

init_plugin();



function submitForm(form, evt){

	evt.preventDefault();

	ajaxSubmit($(form), onsuccess);

}



function onsuccess(res){

	if(res.cmd && res.cmd == 'reload'){

		location.reload();

	}

}



</script>

<?php } ?>
<?php if($page == 'subcat'){ ?>
<select class="form-control" name="category_subchild_id" id="category_subchild_id">
	<option value="">-select-</option>
	<?php print_select_option($sub_category, 'category_subchild_id', 'name');?>
</select>
<?php }?>

<?php if($page == 'reason'){ ?>

<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal" aria-label="Close">

	  <span aria-hidden="true">&times;</span></button>

	<h4 class="modal-title"><?php echo $title;?></h4>

</div>

<div class="modal-body">

		<form role="form" id="reason_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">

			  <input type="hidden" name="ID" value="<?php echo $ID?>"/>
			  <input type="hidden" name="status" value="<?php echo $status?>"/>

              <div class="box-body">

			  

				<div class="form-group">

                  <label for="name">Reason/Comment </label>

                  <textarea  class="form-control reset_field" id="reason" name="reason" autocomplete="off"></textarea>

                </div>

				

			
			  

              </div>

              <!-- /.box-body -->

			  <div class="box-footer">

                <button type="submit" class="btn-block btn btn-primary">Save</button>

              </div>

        </form>

</div>



<script>



init_plugin();



function submitForm(form, evt){

	evt.preventDefault();

	ajaxSubmit($(form), onsuccess);

}



function onsuccess(res){

	if(res.cmd && res.cmd == 'reload'){

		location.reload();

	}

}



</script>

<?php } ?>