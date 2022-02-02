<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$is_online=is_online($member_details['member']->member_id);
$chat_user_name=getUserName($member_details['member']->member_id);
if($is_online){
	$statusClass = 'online';
	//$seller_status=__('message_board_header_page_Online',"Online");
}else{	
	$statusClass = 'offline'; 
	//$seller_status=__('message_board_header_page_Online',"Offline");
	//$seller_status='<i class="icon-feather-circle"></i>';
}
$date=date('Y-m-d H:i:s');
$count_starred=0;
$count_unread=0;
$count_archived=0;
if($count_starred == 1){ 
	$star = "unstar"; 
	$star_i = "fa-star"; 
}else{ 
	$star = "star"; 
	$star_i = "fa-star-o"; 
}
if($count_unread == 1){ 
	$unread = "read"; 
	$unread_i = "fa-envelope-o";
}else{ 
	$unread = "unread";
	$unread_i = "fa-envelope-open-o";
}
if($count_archived == 1){ 
	$archive = "unarchive"; 
	$archive_i = "fa-upload";
}else{ 
	$archive = "archive"; 
	$archive_i = "fa-download";
}
?>
<div class="messages-headline" style="max-height: 75px;">
	<a href="<?php D(get_link('viewprofileURL')) ?><?php D($chat_user_name);?>" target="_bank">
	<div class="user-avatar mr-2 status-<?php D($statusClass); ?>">
    	<img src="<?php D(getMemberLogo($message->chatwith));?>" alt="">
    </div>
    <h4><?php /*D(ucfirst($member_details['member']->member_name));*/ D($chat_user_name); ?> </h4>
	</a>
	 <?php // D(__('message_board_header_page_Local_Time',"Local Time"));?> 
    <?php /*?><a href="#" class="message-action"> <i class="icon-feather-clock"></i> <?php D(date('H:i').' '.dateFormat($date,'F d, Y')); ?></a><?php */?>
	<a href="javascript:void(0)" class="show_inbox visible-under-991 message-action"><i class="icon-feather-message-square"></i></a>
<p class="float-right">
	<a href="inbox<?php echo "?$star=$message_group_id"; ?>" class="btn d-none <?=$star;?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo ucfirst($star); ?>">
		<i class="fa <?=$star_i;?>"></i>
	</a>
	<a href="inbox<?php echo "?$unread=$message_group_id"; ?>" class="btn d-none unread" data-toggle="tooltip" data-placement="bottom" title="Mark As <?php echo ucfirst($unread); ?>">
		<i class="fa <?=$unread_i;?>"></i>
	</a>
	<a href="inbox<?php echo "?$archive=$message_group_id"; ?>" class="btn d-none <?=$archive;?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo ucfirst($archive); ?>">
		<i class="fa <?=$archive_i;?>"></i>
	</a>
	<a href="inbox?hide_seller=<?php echo $seller_id; ?>" class="btn d-none" data-toggle="tooltip" data-placement="bottom" title="Delete">
		<i class="fa fa-trash-o"></i>
	</a>
	<div class="dropdown float-right d-none mt-2">
		<a class="dropdown-toggle closeMsgIcon" href="#" role="button" data-toggle="dropdown">
			<i class="mr-3 fa fa-2x fa-ellipsis-v"></i>
		</a>
		<div class="dropdown-menu pt-1 pb-1" style="margin-right: 15px; max-width: 30px !important; min-width: 150px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-126px, 38px, 0px);" x-placement="bottom-start">
			<a href="inbox?hide_seller=<?php echo $sender_id; ?>" class="dropdown-item  d-none">
			<i class="fa fa-trash-o"></i> Delete
			</a>
			<a href="<?php D(VZ);?>" class="dropdown-item closeMsg">
			<i class="fa fa-times"></i> <?php D(__('global_Close',"Close"));?>
			</a>
		</div>
	</div>
</p>
</div>
<?php
if($message->sender_id==$message->chatwith){
	$my_logo=getMemberLogo($message->receiver_id);
	$chatwith_logo=getMemberLogo($message->sender_id);
}else{
	$my_logo=getMemberLogo($message->sender_id);
	$chatwith_logo=getMemberLogo($message->receiver_id);
}
?>
<script>
	
$('[data-toggle="tooltip"]').tooltip();
var my_logo="<?php echo $my_logo?>";
var chatwith_logo="<?php echo $chatwith_logo?>";
var chatwith_name="<?php /*D($member_details['member']->member_name);*/ D($chat_user_name);?>";
</script>
<script>
$(document).ready(function(){
	$('.show_inbox').click(function(){
	$('.messages-inbox').toggle();
});	
});
</script>