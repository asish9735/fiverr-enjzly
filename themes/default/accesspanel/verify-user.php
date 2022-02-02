<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php if($is_valid){?>
<section style="background-color: #2c3e50;min-height: 200px">
	
</section>
<script type="text/javascript">
$(document).ready(function(){

bootbox.alert({
	title:'Verify User',
	message: '<?php D(__('verify_user_success',"Your account has been activated successfully. Welcome on board."));?>',
	buttons: {
	'ok': {
		label: 'Ok',
		className: 'btn-site pull-right'
		}
	},
	callback: function () {
		window.location.href='<?php D(get_link('dashboardURL'))?>';
    }
});
	
})

</script>
<?php }else{?>
<section style="background-color: #2c3e50;min-height: 200px">
	
</section>
<script type="text/javascript">
$(document).ready(function(){
bootbox.alert({
	title:'Verify User',
	message: '<?php D(__('verify_user_invalid_link',"Your account activation link is invalid."));?>',
	buttons: {
	'ok': {
		label: 'Ok',
		className: 'btn-site pull-right'
		}
	},
	callback: function () {
		window.location.href='<?php D(get_link('homeURL'))?>';
    }
});
})
</script>
<?php }?>