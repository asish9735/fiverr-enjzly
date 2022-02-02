<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
$(document).ready(function(){
bootbox.alert({
	title:'Sign out',
	message: '<?php D(__('logout_page_logout_message',"Good Bye!"));?>',
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
<section style="background-color: #2c3e50;min-height: 200px">
	
</section>