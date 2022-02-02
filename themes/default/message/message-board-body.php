<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$deflang=$this->config->item('language');
if($this->session->userdata('current_lang')){
	$deflang=$this->session->userdata('current_lang');
}
//dd($member_details,TRUE);
?>
<!-- Message Content Inner -->
<div class="message-content-inner" id="load_conversation">

	        
</div>
 
            <!-- Reply Area -->
            <div class="message-reply">
				<?php 
                $status=1;
                $access_user_id=getFieldData('access_user_id','profile_connection','member_id',$member_details['member']->member_id);
                if($access_user_id){
                $status=getFieldData('login_status','access_panel','access_user_id',$access_user_id);
                }
                $chat_user_name=getUserName($member_details['member']->member_id);
                $data=array(
                'is_vacation'=>$member_details['member']->is_vacation,
                'status'=>$status,
                'message'=>$message,
                /*'seller_name'=>$member_details['member']->member_name,*/
                'seller_name'=>$chat_user_name,
                );
                $templateLayout=array('view'=>'message-box','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
                load_template($templateLayout,$data);
                ?>
                <!--<textarea cols="1" rows="1" placeholder="Your Message" data-autoresize></textarea>
                <button class="button ripple-effect">Send</button>-->
                </div>
        
<!-- Message Content Inner / End -->

<!--<div class="list-unstyled messages">
	<?php //require_once("display_messages.php"); ?>
</div>-->


<?php //require_once("sendMessageJs.php"); ?>
<div class="col-md-8 pr-lg-0 border-right"></div>

<div class="col-md-4 pl-0" id="msgSidebar" hidden>
	<h5 class="pt-3 p-2"><?php D(__('message_board_body_page_Orders',"Orders"));?></h5>
	<div class="dropdown">
		<a class="lead text-muted p-2 pt-0" href="#" role="button" data-toggle="dropdown">
			<?php D(__('message_board_body_page_Past_Orders',"Past Orders"));?> (<?php D($order); ?>)
		</a>
		<div class="dropdown-menu <?php if($deflang=='ar'){?>dropdown-menu-right<?php }?> pt-1 pb-1">
			<a href="<?php D(get_link('BuyingHistoryURL'))?><?php D($message->chatwith); ?>" class="dropdown-item">
			<?php D(__('message_board_body_page_Buying_History',"Buying History"));?>
			</a>
			<a href="<?php D(get_link('SellingHistoryURL'))?><?php D($message->chatwith); ?>" class="dropdown-item">
			<?php D(__('message_board_body_page_Selling_History',"Selling History"));?>
			</a>
		</div>
	</div>
	<hr>
	<h5 class="pb-0 p-2"><?php D(__('message_board_body_About',"About"));?></h5>
	<center class="mb-3">
		<img src="<?php D(getMemberLogo($message->chatwith));?>" width="50" class="rounded-circle">
		<a class="text-center" href="<?php D(get_link('viewprofileURL').$chat_user_name)?>">
			<h6 class="mb-0 mt-2"><?php /*D(ucfirst($member_details['member']->member_name));*/ D($chat_user_name);?></h6>
		</a>
		<p class="text-muted text-center"><?php D(getLevelName($member_details['member']->seller_level)); ?></p>
	</center>
	<div class="row p-3">
		<div class="col-md-6">
			<p><i class="fa fa-star pr-1"></i> <?php D(__('message_board_body_Rating',"Rating"));?> </p>
			<p><i class="fa fa-globe pr-1"></i> <?php D(__('message_board_body_From',"From"));?></p>
			<p><i class="fa fa-truck pr-1"></i> <?php D(__('message_board_body_Recent_Delivery',"Recent Delivery"));?></p>
			<?php
			if($member_details['member_languages']){
				foreach($member_details['member_languages'] as $language){
			?>
			<p> <i class="fa fa-language pr-1"></i> <?php D($language->language_title);?></p>
			<?
				}
			}
			?>
		</div>
		<div class="col-md-6 text-right">
			
			<p class="font-weight-bold"><?php D($member_details['member']->seller_rating); ?>%</p>
			<p class="font-weight-bold">
			<?php
			if($member_details['member_address'] && $member_details['member_address']->member_country){
				$getname=getAllCountry(array('country_code'=>$member_details['member_address']->member_country));
				if($getname){
					D($getname->country_name);
				}
			}
			?>
			</p>
			<p class="font-weight-bold"><?php if($member_details['member']->recent_delivery_date){D(dateFormat($member_details['member']->recent_delivery_date,'F d,Y'));}else{D(__('global_NA','NA'));} ?></p>
			<?php
			if($member_details['member_languages']){
				foreach($member_details['member_languages'] as $language){
			?>
			<p class="font-weight-bold"><?php D(getLanguageLevelName($language->language_level)); ?></p>
			<?
				}
			}
			?>
		</div>		
	</div>
</div>
