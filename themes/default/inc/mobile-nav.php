<?php
$loggedUser=$this->session->userdata('loggedUser');
if($loggedUser){
	?>
 <ul class="list-inline top_login_btn">
 	<li class="list-inline-item">
		<a class="" href="<?php D(get_link('dashboardURL'))?>" >
			<img src="<?php echo theme_url().IMAGE;?>icon1.png"><span class="d-lg-none"> <?php D(__('header_dashboard','Dashboard'))?></span>
		</a>
	</li>
	<br>
	<li class="list-inline-item">
		<a href="#" class="d-icon dropdown-toggle mr-lg-2 c-notifications-header-action"  data-toggle="dropdown" title="Notifications">
			<img src="<?php echo theme_url().IMAGE;?>icon2.png">
            <span class="d-lg-none">
            	<?php D(__('header_Notification','Notification'))?>
            	<span class="badge badge-pill badge-danger notificationCnt" style="display: none"><ec></ec> <?php D(__('header_Notification_New','New'))?></span>
			</span>
		</a>
		<div class="dropdown-menu notifications-dropdown" style="width:110% !important; position:relative;">
	 		
      	</div>
	</li>
	<br>
	<li class="list-inline-item">
	 	<a href="#" class="d-icon dropdown-toggle mr-lg-2 c-messages-header-action" data-toggle="dropdown" title="Inbox Messages">
	 		<img src="<?php echo theme_url().IMAGE;?>icon4.png">
	 		<span class="d-lg-none">
	 		<?php D(__('header_Messages','Messages'))?>
	         	<span class="badge badge-pill badge-danger messageCnt" style="display: none"><ec></ec> <?php D(__('header_Messages_New','New'))?></span>
	 		</span>
 		</a>
		<div class="dropdown-menu messages-dropdown" style="width:135% !important; position:relative;">
			
		</div>
    </li><br>
	<li class="list-inline-item">
		<a class=" mr-lg-2" href="<?php D(get_link('FavoritesURL'))?>" title="Favorites">
		 	<img src="<?php echo theme_url().IMAGE;?>icon5.png">
            <span class="d-lg-none">
	 			<?php D(__('header_Favorites','Favorites'))?> 
	            <?php if($count_favorites > 0){ ?>
	             	<span class="badge badge-pill badge-success"> <?php D($count_favorites); ?> </span> 
	            <?php } ?>
 		  </span>
		</a>
    </li><br>
			

	<li class="list-inline-item d-none">
		<a class=" mr-lg-2" href="<?php D(get_link('CartURL'))?>" title="Cart">
			<img src="<?php echo theme_url().IMAGE;?>icon6.png">
			<span class="d-lg-none">
				<?php D(__('header_Cart','Cart'))?>
			        <?php if($count_cart > 0){ ?>
			            <span class="badge badge-pill badge-success"> <?php D($count_cart); ?> </span> 
			        <?php } ?>
			</span>
		</a>
	</li><br>
	<li class="list-inline-item">
		<div class="dropdown">
			<a href="#" style="color:black;" class="dropdown-toggle" data-toggle="dropdown">
           <img src="<?php D(getMemberLogo($loggedUser['MID'])); ?>" width="36" height="35" class="rounded-circle">
           <?php D($seller_name); ?>     
				<span class="badge badge-success">
					<?php D(CURRENCY); ?><?php D(getMemberBalance($loggedUser['MID'])); ?>	
			 	</span>
			</a>
			<div class="dropdown-menu dropdown-menu-2" style="width:200px !important;">
				<a class="dropdown-item" href="<?php D(get_link('dashboardURL'))?>">
					<?php D(__('header_dashboard','Dashboard'))?>
				</a>
				<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#selling-2">
					<b style="color:#85364D"><?php D(__('header_Mesher_Tools','Mesher Tools'))?></b>
				</a>
				<div id="selling-2" class="dropdown-submenu collapse">
					<a class="dropdown-item" href="<?php D(get_link('sellingOrderURL'))?>">
						<?php D(__('header_orders','Orders'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('manageproposalURL'))?>">
						<?php D(__('header_My_Proposals','My proposals'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('buyerRequests')); ?>">
						<?php D(__('header_Buyer_Requests','Custom Requests'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('revenueURL')); ?>">
						<?php D(__('header_Revenues','Earnings'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>">
                		<?php D(__('header_Profile_View','Profile view'))?>
                	</a>
                    
				</div>
				<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#buying-2">
					<?php D(__('header_My_Account','My Account'))?>
				</a>
				<div id="buying-2" class="dropdown-submenu collapse">
					<a class="dropdown-item" href="<?php D(get_link('buyingOrderURL'))?>">
						<?php D(__('header_Orders','My Orders'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('PurchasesURL'))?>">
						<?php D(__('header_Purchases','My Purchases'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('TransactionHistoryURL'));?>">
					<?php D(__('header_Transaction_history','Transaction History'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('FavoritesURL'))?>">
						<?php D(__('header_Favorites','Favorites'))?>
					</a>
				</div>
				<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#requests-2">
					<?php D(__('header_Requests','Requests'))?>
				</a>
				<div id="requests-2" class="dropdown-submenu collapse">
					<a class="dropdown-item" href="<?php D(get_link('postrequestURL'))?>">
						<?php D(__('header_Post A Request','Post A Custom Request'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('managerequestURL'))?>">
						<?php D(__('header_Manage_Requests','My Custom Requests'))?>
					</a>
				</div>
				<a class="dropdown-item" href="<?php D(get_link('ContactURL'));?>">
					<?php D(__('header_Contacts','Manage Contacts'))?>
				</a>
				<!--<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#contacts-2">
					<?php D(__('header_Contacts','Contacts'))?>
				</a>
				<div id="contacts-2" class="dropdown-submenu collapse">
					<a class="dropdown-item" href="<?php D(get_link('ContactURL')); ?>?tab=buyer">
						<?php D(__('header_My_Buyers','My Buyers'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('ContactURL')); ?>?tab=seller">
						<?php D(__('header_My_Sellers','My Sellers'))?>
					</a>
				</div>
				<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#referrals-2">
					<?php D(__('header_My_Referrals','My Referrals'))?>
				</a>
				<div id="referrals-2" class="dropdown-submenu collapse">
					<?php if($enable_referrals == "yes"){ ?>
					<a class="dropdown-item" href="<?php echo $site_url; ?>/my_referrals">
						<?php D(__('header_User_Referrals','User Referrals'))?>
					</a>
					<?php } ?>
					<a class="dropdown-item" href="<?php D(get_link('ProposalReferralURL'));?>">
						<?php D(__('header_Proposal_Referrals','Proposal Referrals'))?>
					</a>
				</div>-->
				<a class="dropdown-item" href="<?php D(get_link('MessageBoard'));?>">
					<?php D(__('header_Inbox_Messages','Inbox Messages'))?>
				</a>
				
				<a class="dropdown-item" href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>">
					<?php D(__('header_My_Profile','My Profile'))?>
				</a>
				<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#settings-2">
					<?php D(__('header_Settings','Settings'))?>
				</a>
				<div id="settings-2" class="dropdown-submenu collapse">
					<a class="dropdown-item" href="<?php D(get_link('settingsURL'))?>">
						<?php D(__('header_Profile_Settings','Profile Settings'))?>
					</a>
					<a class="dropdown-item" href="<?php D(get_link('settingsURL'))?>?tab=account">
						<?php D(__('header_Account_Settings','Account Settings'))?>
					</a>
				</div>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="<?php D(get_link('logoutURL'))?>">
					<?php D(__('header_Logout','Logout'))?>
				</a>
			</div>
		</div>
	</li>
	 <li class="list-inline-item flag d-none"><a href="<?php D(VZ)?>" onClick="setlang('en')" class="<?php if($this->session->userdata('current_lang')=='en' || $this->session->userdata('current_lang')=='' ){D('active');}?>"><!--<img src="<?php echo theme_url().IMAGE;?>en.svg" alt="" width="30" />-->EN</a> <span class="text-muted">|</span> <a href="<?php D(VZ)?>" onClick="setlang('ar')" class="<?php if($this->session->userdata('current_lang')=='ar'){D('active');}?>"><!--<img src="<?php echo theme_url().IMAGE;?>ae.svg" alt="" width="30" />-->ع</a>
  <?php // D($this->session->userdata('current_lang'));?></li>
</ul>	
	<?php
}else{
	?>
<ul class="list-inline top_btn">
	<li class="list-inline-item"><a href="<?php D(VPATH);?>how-it-works"><?php D(__('header_how_it_works','How it works?'))?></a></li><br>
	<li class="list-inline-item"><a href="<?php D(get_link('registerURL'))?>"> <?php D(__('header_became_seller','Become a Freelancer'))?></a></li><br>
	<li class="list-inline-item"><a href="<?php D(get_link('loginURL'))?>"><?php D(__('header_signin','Sign In'))?></a></li><br>
	<li class="list-inline-item">  
		<a class="btn btn_join" href="<?php D(get_link('registerURL'))?>"><?php D(__('header_join_now','Join Now'))?></a>
	</li>
	<li class="list-inline-item flag d-none"><a href="<?php D(VZ)?>" onClick="setlang('en')" class="<?php if($this->session->userdata('current_lang')=='en' || $this->session->userdata('current_lang')=='' ){D('active');}?>"><!--<img src="<?php echo theme_url().IMAGE;?>en.svg" alt="" width="30" />-->EN</a> <span class="text-muted">|</span> <a href="<?php D(VZ)?>" onClick="setlang('ar')" class="<?php if($this->session->userdata('current_lang')=='ar'){D('active');}?>"><!--<img src="<?php echo theme_url().IMAGE;?>ae.svg" alt="" width="30" />-->ع</a>
  <?php // D($this->session->userdata('current_lang'));?></li>
</ul>	
	<?php
}
?>