<?php
if(flashMessage('level_up')){
	if(flashMessage('level_up')=='1'){
?>
<div id="level-one-modal" class="modal fade"><!-- level-one-modal modal fade Starts -->
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> <?php D(__('popup_level_one_modal_heading','Promoted To Level One'));?> </h5>
				<button class="close" data-dismiss="modal">
				  <span> &times; </span>
				</button>
			</div>
			<div class="modal-body text-center">
				<h2> <?php D(__('popup_level_one_modal_Great','Great'));?> </h2>
				<p class="lead">
				<?php D(__('popup_level_one_modal_text','We Have Some Great News For You!<br>You\'re now a level one freelancer.'));?>
				</p>
				<img src="<?php D(theme_url().IMAGE); ?>level_badge_1.png" >
			</div>
			<div class="modal-footer">
			<button class="btn btn-secondary" data-dismiss="modal"><?php D(__('popup_global_Close','Close'));?></button>
			</div>
		</div>
	</div>
</div>
<?php }elseif(flashMessage('level_up')=='2'){?>
<div id="level-two-modal" class="modal fade"><!-- level-two-modal modal fade Starts -->
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> <?php D(__('popup_level_two_modal_heading','Promoted To Level Two'));?></h5>
				<button class="close" data-dismiss="modal">
				  <span> &times; </span>
				</button>
			</div>
			<div class="modal-body text-center">
				<h2> <?php D(__('popup_level_two_modal_Awesome','Awesome'));?> </h2>
				<p class="lead">
				<?php D(__('popup_level_two_modal_text','We Have Some Awesome News For You!<br>You\'re now a level 2 freelancer. Good Job!'));?>
				</p>
				<img src="<?php D(theme_url().IMAGE); ?>level_badge_2.png" >
			</div>
			<div class="modal-footer">
			<button class="btn btn-secondary" data-dismiss="modal"><?php D(__('popup_global_Close','Close'));?></button>
			</div>
		</div>
	</div>
</div><!-- level-two-modal modal fade Ends -->
<?php }elseif(flashMessage('level_up')=='3'){?>
<div id="top-rated-modal" class="modal fade"><!-- top-rated-modal modal fade Starts -->
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> <?php D(__('popup_level_top_modal_heading','Top Rated Freelancer'));?> </h5>
				<button class="close" data-dismiss="modal">
				  <span> &times; </span>
				</button>
			</div>
			<div class="modal-body text-center">
				<h2> <?php D(__('popup_level_top_modal_Splendid','Splendid'));?> </h2>
				<p class="lead">
				<?php D(__('popup_level_top_modal_text','We Have Some Splendid News For You!<br>You\'re Now a Top Rated Freelancer. More Custmers Will Trust You. Great Job!'));?>
				</p>
				<img src="<?php D(theme_url().IMAGE); ?>level_badge_3.png" >
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal"><?php D(__('popup_global_Close','Close'));?></button>
			</div>
		</div>
	</div>
</div><!-- top-rated-modal modal fade Ends -->
<?php }
}?>
<script type="text/javascript">
$(document).ready(function(){
<?php
if(flashMessage('level_up')){
	if(flashMessage('level_up')=='1'){
		?>
	$("#level-one-modal").modal('show');
		<?php
	}elseif(flashMessage('level_up')=='2'){
		?>
	$("#level-two-modal").modal('show');
		<?php
	}elseif(flashMessage('level_up')=='3'){
		?>
	$("#top-rated-modal").modal('show');
		<?php
	}
}
?>
});
</script>
<?php
setFMessage('level_up','');
?>