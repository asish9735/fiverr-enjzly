<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="header-notifications-headline">
    <h4><?php D(__('notification_list_page_Notifications',"Notifications"));?> (<?php D($notification_count); ?>)
<?php /*?><a class="float-right make-black" href="<?php D(get_link('NotificationListURL'));?>" style="color:black;">
<?php D(__('notification_list_page_View_Notifications',"View Notifications"));?>
</a><?php */?>
</h4>
</div>
<div class="header-notifications-scroll" data-simplebar>
<ul>            
    <!--<h3 class="dropdown-header"></h3>-->
    <?php
    if($notification){
    foreach($notification as $notificationmsg){
        if($notificationmsg->sender_id){
            //$member_name=$notificationmsg->member_name;
            $sender_user_name=getUserName($notificationmsg->sender_id);
        }else{
            $member_name=$sender_user_name=__('notification_list_page_Admin','Admin');
        }
    ?>

<!-- Notification -->
<li class="<?php if($notificationmsg->is_read != 1){D('notifications-not-read header-message-div-unread_');}else{D('header-message-div_');	}?>">
<a href="<?php D(get_link('NotificationDetailsLink').$notificationmsg->notification_id); ?>">
    <span class="notification-avatar"><img src="<?php D(getMemberLogo($notificationmsg->sender_id)); ?>" alt=""></span>
    <span class="notification-text">
        <strong><?php /*D($member_name);*/ D($sender_user_name);?></strong>
        <p class="notification-msg-text" <?php if($notificationmsg->notification_template == "declined"){D("style='font-size: 14px;'"); } ?>><?php D($notificationmsg->template_content);?></p>
        <span class="date text-muted"><?php D(date('H:i ',strtotime($notificationmsg->notification_date)).dateFormat($notificationmsg->notification_date,'F d, Y')); ?></span>
    </span>
</a>
</li>	

<?php	
}
?>

<?php
}else{
?>
<h6 class='text-center mt-3'> <?php D(__('notification_list_page_no_notification',"No Notifications Are Available"));?> </h6>
<?php
}
?>
</ul>
</div>
