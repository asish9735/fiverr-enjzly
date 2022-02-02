<?php
$loggedUser=$this->session->userdata('loggedUser');	
$get_all_category=getAllCategory(array('limit'=>5));
?>
<!-- Footer
================================================== -->
<footer class="footer">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 col-sm-6">
      <div class="footer-links">
      <h4><?php D(__('footer_heading_categories','Categories'));?></h4>

      <ul class="foot-nav">
      <?php for($i=0;$i<5;$i++){
      ?>
      <li><a href="<?php D(get_link('CategoryURL').$get_all_category[$i]->category_key);?>"><?php D($get_all_category[$i]->name); ?></a></li>
      <?php	
      }?>
      <li><a href="<?php D(get_link('AllCategories'));?>"><?php D(__('footer_link_ALL_CATEGORIES','All Categories'));?></a></li>
      </ul>
      </div>
      </div>
      <?php /*?><div class="col-md-2 col-6">
       <h4>&nbsp;</h4>
        <ul class="foot-nav">

        <?php for($i=3;$i<5;$i++){
      ?>
      <li><a href="<?php D(get_link('CategoryURL').$get_all_category[$i]->category_key);?>"><?php D($get_all_category[$i]->name); ?></a></li>
      <?php	
      }?>
        </ul>
      </div><?php */?>
	  <div class="col-md-3 col-sm-6">
    	<div class="footer-links">
        <h4><?php D(__('footer_heading_','By Location'));?></h4>
        <ul class="foot-nav">
          	<li><a href="">United States</a></li>
            <li><a href="">Australia</a></li>
            <li><a href="">England</a></li>
            <li><a href="">United Emirates</a></li>
            <li><a href="">Canada</a></li>
            <li><a href="">United Kingdom</a></li>
            <li><a href="">India</a></li>
            <li><a href="">Turkey</a></li>
            <li><a href="">View All</a></li>                 
        </ul>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="footer-links">
        <h4><?php D(__('footer_heading_','Company'));?></h4>
        <ul class="foot-nav">
         <li><a href="<?php D(get_link('CMSaboutus'))?>"> <?php D(__('footer_link_about_us','About Us'));?></a></li>
         <li><a href="<?php D(VPATH);?>how-it-works"><?php D(__('header_how_it_works','How it works?'))?></a></li> 
         <li><a href="<?php D(get_link('CMSpricingandpromotionspolicy'))?>">Privacy Policy</a></li> 	
         <li><a href="<?php D(get_link('CMStermsandconditions'))?>"><?php D(__('footer_link_termandconditions','Terms &amp; Conditions'));?></a></li>
         <li><a href="<?php D(get_link('ContactUsURL'))?>"><?php D(__('footer_link_contact_us','Contact Us'));?></a></li>
        </ul>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="footer-links">
        <h4><?php D(__('footer_heading_','Resources'));?></h4>
        <ul class="foot-nav">
        	<li><a href="">Membership</a></li>
            <li><a href="">Help & Support</a></li>
            <li><a href="">Trust & Safety</a></li>
            <li><a href="">Resources</a></li>
            <li><a href="">Customer Stories</a></li>
            <li><a href="">Business Resources</a></li>
        </ul>
	  </div>
  <!--<img src="<?php echo theme_url().IMAGE.'visa.jpg';?>"   alt="Visa">
  <img src="<?php echo theme_url().IMAGE.'master.jpg';?>"alt="Master card">
  <img src="<?php echo theme_url().IMAGE.'verified-visa-mastercard.jpg';?>" alt="Verified by visa mastercard" height="38">
  <img src="<?php echo theme_url().IMAGE.'footer-payment-methods.png';?>" alt="Verified by visa mastercard">-->
<!--<a href="<?php D(VZ)?>" onclick="setlang('en')">EN</a>|<a href="<?php D(VZ)?>" onclick="setlang('ar')">AR</a>
  <?php D($this->session->userdata('current_lang'));?>-->
  
      </div>

    </div>
  </div>
</footer>
<section class="post_footer">
<div class="container-fluid">
<div class="row align-items-center">
	<div class="col-auto" style="min-width:200px;">
    	<a class="<?php if($loggedUser){ D("text-success"); } ?>" href="<?php D(VPATH);?>" title="Home">
        	<img src="<?php echo theme_url().IMAGE.LOGO_NAME;?>" alt="<?php D($website_name);?>" height="40">
        </a>
    </div>
	<div class="col text-center">&copy; Copyright <?php D(date('Y'))?>. All Rights Reserved.</div>
    <div class="col-auto">
    	<ul class="social-links">
            <li><a href="<?php D(get_option_value('facebook_url'))?>" target="_blank"><i class="icon-brand-facebook-f"></i></a></li>
            <li><a href="<?php D(get_option_value('twitter_url'))?>" target="_blank"><i class="icon-brand-twitter"></i></a></li>    
            <li><a href="<?php D(get_option_value('linkedin_url'))?>" target="_blank"><i class="icon-brand-linkedin"></i></a></li>
            <li><a href="<?php D(get_option_value('youtube_url'))?>" target="_blank"><i class="icon-brand-google-plus"></i></a></li>    
  		</ul>
    </div>
</div>
</div>
</section>
<section class="messagePopup animated slideInRight">
</section>
</div>
<!-- Wrapper / End -->

<?php
if(!$loggedUser){
	$data=array(
	'referral'=>(($this->input->get('referral')) ? $this->input->get('referral'):''),
	'enable_social_login'=>get_option_value('enable_social_login'),
	'all_country'=>getAllCountry(),
	'all_nationality'=>getAllNationality(),
	'all_mobile_codes'=>$this->db->select('codes')->from('mobile_code')->where('status','1')->order_by('display_order', 'ASC')->get()->result(),
	);
	$templateLayout=array('view'=>'accesspanel/access-modal','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
	//load_template($templateLayout,$data);
}
?>
<?php
if($loggedUser){
	$data=array();
	$templateLayout=array('view'=>'inc/seller-levels','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
	load_template($templateLayout,$data);
}
?>
<!-- Scripts
================================================== -->
<?php
$this->minify->add_css('msdropdown.css','footer');
echo $this->minify->deploy_css(FALSE, 'footer.min.css','footer');

$this->minify->js(array('msdropdown.js', 'jquery.sticky.js','mmenu.min.js','customjs.js','popper.min.js','bootstrap.min.js','summernote.js','custom.js','bootbox_custom.js','app-service.js'));
echo $this->minify->deploy_js(FALSE, 'footer.min.js');

	?>
<!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
<script>
// Snackbar for user status switcher
$('#snackbar-user-status label').click(function() { 
	Snackbar.show({
		text: 'Your status has been changed!',
		pos: 'bottom-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 3000,
		textColor: '#fff',
		backgroundColor: '#383838'
	}); 
}); 
function setlang(langset){
	$.post( "<?php D(get_link('SetLangURL'));?>",{lang:langset} ,function( data ) {
	 window.location.reload();
	});
}
</script>
<?php if($loggedUser){?>
<div id="template" style="display:none">
	<div id="notificationTemplate">
	<div class="header-message-div">
		<a href="{URL}">
		{LOGO}
		<strong class="heading">{NAME}</strong>
		<p class="message" {EXTRA_STYLE}>
		{CONETNT}
		</p>
		<p class="date text-muted mb-0"><small>{DATE}</small></p>
		</a>
		<a href="#" class="close closePopup"><i class="icon-feather-x"></i></a>
	</div>
	</div>
</div>
<script>
var mySound = new Sound("<?php echo theme_url().IMAGE;?>beep.mp3");
function Sound(src) {
    this.sound = document.createElement("audio");
    this.sound.src = src;
    this.sound.setAttribute("preload", "auto");
    this.sound.setAttribute("controls", "none");
    this.sound.style.display = "none";
    document.body.appendChild(this.sound);
    this.play = function(){
        this.sound.play();
    }
    this.stop = function(){
        this.sound.pause();
    }    
}
var recallAjaxFooter;
function onlineUserUP(){
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('OnlineUserUp'))?>",
        dataType: "json",
        cache: false,
		success: function(msg) {
			setTimeout(function(){onlineUserUP()},30000);
		}
	})
}
function notification(){
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('CheckNotification'))?>",
        dataType: "json",
        cache: false,
		success: function(res) {
			var template=$('#template #notificationTemplate').html();
			if(res){
				
				
				if(res.unread>0){
					//$('.c-notifications-header .number').html(res.unread);
					$('.notifications-counter').html(res.unread);
					$('.c-notifications-header .new-indicator .fa').show();
					$('.notificationCnt ec').html(res.unread);
					$('.notificationCnt').show();
				}else{
					$('.c-notifications-header .new-indicator .fa').hide();
					$('.notifications-counter').hide();
				}
				if(res.unreadMessage>0){
					//$('.c-messages-header .number').html(res.unreadMessage);
					$('.notifications-counter').html(res.unreadMessage);
					$('.c-messages-header .new-indicator .fa').show();
					$('.messages-counter').html(res.unreadMessage);
					$('.messages-counter').show();
				}else{
					$('.c-messages-header .new-indicator .fa').hide();
					$('.messages-counter').hide();
				}
				if (res['poupmessage'].length>0) {
					for (x in res['poupmessage']) {
						var EXTRA_STYLE='';
						if(res['poupmessage'][x] ['notification_template']=='declined'){
							EXTRA_STYLE="style='font-size: 14px;'";
						}
						template=template.replace(/{URL}/g, res['poupmessage'][x] ['url']);
						template=template.replace(/{LOGO}/g, '<img src="'+res['poupmessage'][x] ['logo']+'" width="40" height="40" class="rounded-circle">');
						template=template.replace(/{NAME}/g, res['poupmessage'][x] ['member_name']);
						template=template.replace(/{EXTRA_STYLE}/g, EXTRA_STYLE);
						template=template.replace(/{CONETNT}/g, res['poupmessage'][x] ['template_content']);
						template=template.replace(/{DATE}/g, res['poupmessage'][x] ['date']);
						
						$( ".messagePopup").prepend(template);
					}
				}
				if(res.unreadMessage>0){
					mySound.play();
				}
				recallAjaxFooter = setTimeout(function(){notification()},10000);
			}
		}
	})
}

$(document).ready(function(){
	//onlineUserUP();
	notification();
	//.c-notifications-header-action
	$('.header-notifications').click(function(){
		$('.notifications-dropdown').empty().html("<div class='text-center'><i class='fa fa-spinner fa-pulse fa-lg fa-fw'></i></div>");
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('getNotificationList'))?>",
	        cache: false,
			success: function(res) {
				$('.notifications-dropdown').html(res);
			}
		})
		$('.messages-dropdown').empty().html("<div class='text-center'><i class='fa fa-spinner fa-pulse fa-lg fa-fw'></i></div>");
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('getMessageList'))?>",
	        cache: false,
			success: function(res) {
				$('.messages-dropdown').html(res);
			}
		})
	});
	/*$('.c-messages-header-action').click(function(){
		
	})*/
});

$('document').ready(function(){
if(typeof AppService !== 'undefined'){
	AppService.setUrl('<?php echo base_url('message_new/update_service'); ?>');
	AppService.init();
	
	AppService.on('new_message', function(data){
		if(data > 0){
			
			$('.new-message-counter').html(data);
			$('.new-message-counter').show();
		}else{
			$('.new-message-counter').hide();
		}
	});
	
	AppService.on('new_notification', function(data){
		if(data > 0){
			
			//$('.new-notification-counter').html(data);
			//$('.new-notification-counter').show();
		}else{
			//$('.new-notification-counter').hide();
		}
	});
	
};
});
(function(){
		/* Message loading */
		var message_open_state = false;
		/* var simpleBar = new SimpleBar(document.getElementById('header-message-container'));
		var scrollElement = simpleBar.getScrollElement(); */
		
		$('.header-notifications-trigger.message-trigger').click(function(){
			// load message 
			
			var $msg_list = $('#header-message-list');
			message_open_state = !message_open_state;
			$.getJSON('<?php echo base_url('message_new/chat_list_htm');?>', function(res){
				$msg_list.html(res.html);
				updateheadscroll('message');
			});
		});
	})();
</script>
<?php }?>
</body>
</html>