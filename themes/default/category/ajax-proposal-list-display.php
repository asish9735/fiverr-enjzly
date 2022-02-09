<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
if($view_type=='list'){
	if($all_proposals){
		foreach($all_proposals as $p=>$proposal){
			?>
			<div class="col-12 list-view">
				<?php
				$proposaldata['proposal']=$proposal;
				$templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
				load_template($templateLayout,$proposaldata);
				?>	
			</div>
			<?php
		}
	}
}else{
	if($all_proposals){
		foreach($all_proposals as $p=>$proposal){
			?>
			<div class="col-md-4 col-sm-6 col-12 grid-view">
				<?php
				$proposaldata['proposal']=$proposal;
				$templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
				load_template($templateLayout,$proposaldata);
				?>	
			</div>
			<?php
		}
	}
}
?>

