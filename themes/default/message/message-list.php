<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="header-notifications-headline">
    <h4><?php D(__('message_list_page_Inbox',"Inbox"));?> (<?php D($message_count); ?>)
<?php /*?><a class="float-right make-black" href="<?php D(get_link('MessageBoard'));?>" style="color:black;">
<?php D(__('message_list_page_View_Inbox',"View Inbox"));?>
</a><?php */?></h4>
</div>

<div class="header-notifications-scroll" data-simplebar>
<ul>

<?php
if($message){
	foreach($message as $messagemsg){
		if($messagemsg->sender_id==$login_id){
			//$member_name=getFieldData('member_name','member','member_id',$messagemsg->receiver_id);
			$sender_user_name=getUserName($messagemsg->receiver_id);
			$receiver_id=$messagemsg->receiver_id;
		}elseif($messagemsg->receiver_id==$login_id){
			//$member_name=getFieldData('member_name','member','member_id',$messagemsg->sender_id);
			$sender_user_name=getUserName($messagemsg->sender_id);
			$receiver_id=$messagemsg->sender_id;
		}else{
			$member_name=$sender_user_name=__('message_list_page_Admin','Admin');
			$receiver_id=0;
		}
		$is_online=is_online($receiver_id);
	?>
	<!-- Notification -->
<li class="<?php if($messagemsg->is_read != 1 && $messagemsg->message_sender!=$login_id){D('notifications-not-read header-message-div-unread_');}else{D('header-message-div_');	}?>">
<a href="<?php D(get_link('MessageBoard').'/'.$messagemsg->conversations_id); ?>">
    <span class="notification-avatar status-<?php echo ($is_online==1 ?'online':'offline');?>"><img src="<?php D(getMemberLogo($receiver_id)); ?>" alt=""></span>
    <div class="notification-text">
        <strong><?php /*D($member_name);*/ D($sender_user_name); ?></strong>
        <p class="notification-msg-text">
        <?php 
if($messagemsg->offer_id){
	D(__('message_list_page_Sent_you_an_offer','Sent you an offer'));
}else{
	D(strip_tags($messagemsg->message));
}
?>
        </p>
        <span class="date"><?php D(date('H:i ',strtotime($messagemsg->sending_date)).dateFormat($messagemsg->sending_date,'F d, Y')); ?></span>
    </div>
</a>
</li>
	<?php	
	}
	?>

	<?php
}else{
	?>
	<h6 class='text-center mt-3'> <?php D(__('message_list_page_no_message',"No Messages Are Available"));?> </h6>
	<?php
}
?>
</ul>
</div>
