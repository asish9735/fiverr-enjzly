<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
if($all_proposals){
	foreach($all_proposals as $p=>$proposal){
		?>
		<div class="col-md-4 col-sm-6 col-12">
			<?php
			$proposaldata['proposal']=$proposal;
			$templateLayout=array('view'=>'proposals/proposal-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
			load_template($templateLayout,$proposaldata);
			?>	
		</div>
		<?php
	}
}
?>
