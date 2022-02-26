<?php

$loggedUser=$this->session->userdata('loggedUser');

$all_connected_profile=array();

if($loggedUser){

	$all_profile=getConnectedProfile($loggedUser['LID']);

	$all_connected_profile=$all_profile[0];

	//dd($all_connected_profile,TRUE);

	//$count_cart=$this->db->where('member_id',$all_connected_profile->member_id)->count_all_results('cart');

	$count_cart=0;

	$count_favorites=$this->db->where('member_id',$all_connected_profile->member_id)->count_all_results('favorites');

}

$site_url=VPATH;

$username=$loggedUser['UNAME'];

$website_name=get_option_value('website_name');

?>

<body>

<!-- Wrapper -->

<div id="wrapper">
<!-- Header Container -->
<header id="header-container" class="fullwidth">   
  <!-- Header -->  
  <div id="header">
    <div class="container">       
      <!-- Left Side Content -->      
      <div class="left-side">         
        <!-- Logo -->        
        <div id="logo">
          <a class="<?php if($loggedUser){ D("text-success"); } ?>" href="<?php D(VPATH);?>" title="Home">
            <img src="<?php echo theme_url().IMAGE.LOGO_NAME;?>" alt="<?php D($website_name);?>">
          </a>
        </div>                         
      </div>
      
      <!-- Left Side Content / End --> 
      
      <!-- Right Side Content / End -->
      
      <div class="right-side">
      <!-- Main Navigation -->        
        <nav id="navigation" class="navigationTop">
          <ul class="visible-under-991" id="responsive">
            <?php if($loggedUser){?>
            <li><a href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>">               
              <!-- User Name / Avatar -->              
              <div class="user-details">
                <div class="user-avatar status-online"><img src="<?php D(getMemberLogo($loggedUser['MID'])); ?>" alt=""></div>
                <div class="user-name text-white">
                  <?php D($all_connected_profile->profile_name); ?>
                  <span>Freelancer</span> </div>
              </div>
              </a> </li>
            <?php }else{?>
            <?php }?>
            <li><a href="<?php D(VPATH);?>how-it-works"><?php D(__('header_how_it_works','How it works?'))?></a></li>
            <li><a href="<?php D(get_link('SearchURL'));?>"><?php D(__('header_','Explore Gigs'))?></a></li>
            <?php if(!$loggedUser){?>
              <li><a href="<?php D(get_link('registerURL'))?>"><?php D(__('header_','Become a Seller'))?></a></li>
            <?php }?>
            <?php if($loggedUser){?>
            <li><a href="<?php D(get_link('NotificationListURL')); ?>"><i class="icon-feather-bell"></i>
              <?php D(__('notification_list_page_notifications',"Notifications"));?>
              <span class="messages-counter"></span></a></li>
            <li><a href="<?php D(get_link('MessageBoard')); ?>"><i class="icon-feather-mail"></i>
              <?php D(__('message_list_page_messages',"Messages"));?>
              <span class="messages-counter"></span></a></li>
            <li><a class="dropdown-item" href="<?php D(get_link('dashboardURL'))?>"> <i class="icon-material-outline-dashboard"></i>
              <?php D(__('header_dashboard','Dashboard'))?>
              </a> </li>
            <li><a class="dropdown-item" href="<?php D(get_link('FavoritesURL'))?>"><i class="icon-feather-heart"></i> Favorites</a></li>
            <li><a href="#" class="current"><i class="icon-feather-anchor"></i>
              <?php D(__('header_for_seller','For Seller'))?>
              </a>
              <ul class="dropdown-nav">
                <li><a class="dropdown-item" href="<?php D(get_link('sellingOrderURL'))?>">
                  <?php D(__('header_orders','Orders'))?>
                  </a></li>
                <li><a class="dropdown-item" href="<?php D(get_link('manageproposalURL'))?>">
                  <?php D(__('header_My_Proposals','My proposals'))?>
                  </a></li>
                <li><a class="dropdown-item" href="<?php D(get_link('buyerRequests')); ?>">
                  <?php D(__('header_Buyer_Requests','Custom Requests'))?>
                  </a></li>
                <li><a class="dropdown-item" href="<?php D(get_link('revenueURL')); ?>">
                  <?php D(__('header_Revenues','Earnings'))?>
                  </a></li>
                <li><a class="dropdown-item" href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>">
                  <?php D(__('header_Profile_View','Profile view'))?>
                  </a></li>
              </ul>
            </li>
            <li><a href="#" class="current"><i class="icon-feather-user"></i>
              <?php D(__('header_My_Account','My Account'))?>
              </a>
              <ul class="dropdown-nav">
                <li><a class="dropdown-item" href="<?php D(get_link('buyingOrderURL'))?>">
                  <?php D(__('header_Orders','My Orders'))?>
                  </a></li>
                <li><a class="dropdown-item" href="<?php D(get_link('PurchasesURL'))?>">
                  <?php D(__('header_Purchases','My Purchases'))?>
                  </a></li>
                <li><a class="dropdown-item" href="<?php D(get_link('TransactionHistoryURL'));?>">
                  <?php D(__('header_Transaction_history','Transaction History'))?>
                  </a></li>
                <li><a class="dropdown-item" href="<?php D(get_link('FavoritesURL'))?>">
                  <?php D(__('header_Favorites','Favorites'))?>
                  </a></li>
              </ul>
            </li>
            <li><a href="#" class="current"><i class="icon-line-awesome-hand-stop-o"></i>
              <?php D(__('header_Requests','Requests'))?>
              </a>
              <ul class="dropdown-nav">
                <li><a class="dropdown-item" href="<?php D(get_link('postrequestURL'))?>">
                  <?php D(__('header_Post A Request','Post A Custom Request'))?>
                  </a></li>
                <li><a class="dropdown-item" href="<?php D(get_link('managerequestURL'))?>">
                  <?php D(__('header_Manage_Requests','My Custom Requests'))?>
                  </a></li>
              </ul>
            </li>
            <li><a class="dropdown-item" href="<?php D(get_link('ContactURL'));?>"> <i class="icon-feather-phone"></i>
              <?php D(__('header_Contacts','Manage Contacts'))?>
              </a></li>
            <li><a class="dropdown-item" href="<?php D(get_link('MessageBoard'));?>"> <i class="icon-feather-mail"></i>
              <?php D(__('header_Inbox_Messages','Messages'))?>
              </a></li>
            <li><a class="dropdown-item" href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>"> <i class="icon-feather-user"></i>
              <?php D(__('header_My_Profile','My Profile'))?>
              </a></li>
            <li><a href="#" class="current"><i class="icon-feather-settings"></i>
              <?php D(__('header_Settings','Settings'))?>
              </a>
              <ul class="dropdown-nav">
                <li><a class="dropdown-item" href="<?php D(get_link('settingsURL'))?>">
                  <?php D(__('header_Profile_Settings','Profile Settings'))?>
                  </a></li>
                <li><a class="dropdown-item" href="<?php D(get_link('settingsURL'))?>?tab=account">
                  <?php D(__('header_Account_Settings','Account Settings'))?>
                  </a></li>
              </ul>
            </li>
            <li><a class="dropdown-item" href="<?php D(get_link('logoutURL'))?>"> <i class="icon-material-outline-power-settings-new"></i>
              <?php D(__('header_Logout','Logout'))?>
              </a></li>
            <?php }else{?>
            <li><a href="<?php D(get_link('loginURL'))?>">
              <i class="icon-feather-log-in"></i> <?php D(__('header_login','Log In'))?>
              </a></li>
            <li><a href="<?php D(get_link('registerURL'))?>">
              <i class="icon-line-awesome-mouse-pointer"></i> <?php D(__('header_post_gigs','Post Gigs'))?>
              </a></li>
            <?php }?>
            
          </ul>
          <ul class="hide-under-991">
            <?php /*?><li>
              <form class="form-inline ml-auto" action="<?php D(get_link('SearchURL'))?>" method="get">
                <div class="input-group">
                  <input type="text" name="input" class="form-control" placeholder="<?php D(__('header_search_input','Find Services'))?>" value="<?php if($this->input->get('input')){D($this->input->get('input'));}?>">
                  <div class="input-group-append">
                    <button name="search" class="btn btn-outline-site">
                    <?php D(__('header_search_button','Search'))?>
                    </button>
                  </div>
                </div>
              </form>
            </li>            
            <!--<li><a href="#" class="current">Home</a>
                <ul class="dropdown-nav">
                    <li><a href="index.html">Home 1</a></li>
                    <li><a href="index-2.html">Home 2</a></li>
                </ul>
            </li>-->
			<?php */?>
            
            <li><a href="<?php D(VPATH);?>how-it-works"><?php D(__('header_how_it_works','How it works?'))?></a></li>
            <li><a href="<?php D(get_link('SearchURL'));?>"><?php D(__('header_explore_gigs','Explore Gigs'))?></a></li>
            <?php if($loggedUser){?>
            <li><a href="<?php D(get_link('dashboardURL'))?>"><?php D(__('header_dashboard','Dashboard'))?></a></li>
            <?php }else{?>
            <li><a href="<?php D(get_link('registerURL'))?>"><?php D(__('header_become_a_seller','Become a Seller'))?></a></li>
            <?php }?>
          </ul>
        </nav>
        
        <!-- Main Navigation / End -->
        <?php if($loggedUser){?>
        
        <!--  User Notifications -->
        
        <div class="header-widget hide-under-991"> 
          
          <!-- Notifications -->
          
          <div class="header-notifications"> 
            
            <!-- Trigger -->
            
            <div class="header-notifications-trigger"> <a href="#"><i class="icon-feather-bell"></i><span class="notifications-counter"></span></a> </div>
            
            <!-- Dropdown -->
            
            <div class="header-notifications-dropdown">
              <div class="header-notifications-content notifications-dropdown">
                <?php //require("c-notifications-body.php"); ?>
              </div>
              <a href="<?php D(get_link('NotificationListURL')); ?>" class="header-notifications-button button-sliding-icon">
              <?php D(__('notification_list_page_See_All_',"View All Notifications"));?>
              <i class="icon-material-outline-arrow-right-alt"></i></a> </div>
          </div>
          
          <!-- Messages -->
          <!-- Messages -->
					<div class="header-notifications">
						<div class="header-notifications-trigger message-trigger">
							<a href="#"><i class="icon-feather-mail"></i><span class="new-message-counter" style="display:none"></span></a>
						</div>

						<!-- Dropdown -->
						<div class="header-notifications-dropdown">
            			<div class="" style="display: block;width: 100%;">
							<div class="header-notifications-headline">
								<h4><?php echo __('messages','Messages'); ?></h4>
								<button class="mark-as-read" title="Mark all as read" data-tippy-placement="left" hidden>
									<i class="icon-feather-check-square"></i>
								</button>
							</div>

							<div class="header-notifications-content with-icon">
								<div class="header-notifications-scroll" id="header-message-container" data-simplebar>
									<ul id="header-message-list">
										
									</ul>
									<a id="load_more_msg_btn" href="javascript:void(0)" style="display:none;"><?php echo __('load_more','Load more'); ?></a>
								</div>
							</div>
              </div>
							<a href="<?php echo get_link('MessageURL');?>" style="display:none" class="header-notifications-button button-sliding-icon viewallbtnmessage"><?php echo __('view_all_message','View All Messages'); ?><i class="icon-material-outline-arrow-right-alt"></i></a>
						</div>
					</div>
          
        </div>
        
        <!--  User Notifications / End --> 
        
        <!-- User Menu -->
        
        <div class="header-widget hide-under-991"> 
          
          <!-- Messages -->
          
          <div class="header-notifications user-menu">
            <div class="header-notifications-trigger"> <a href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>">
              <div class="user-avatar status-online"><img src="<?php D(getMemberLogo($loggedUser['MID'])); ?>" alt=""></div>
              </a> <em class="badge badge-outline-site ml-2"><?php D(CURRENCY); ?><?php echo getMemberBalance($loggedUser['MID'],false);?></em></div>
            
            <!-- Dropdown -->
            
            <div class="header-notifications-dropdown"> 
              
              <!-- User Status -->
              
              <div class="user-status"> 
                
                <!-- User Name / Avatar -->
                
                <div class="user-details">
                  <div class="user-avatar status-online"><img src="<?php D(getMemberLogo($loggedUser['MID'])); ?>" alt=""></div>
                  <div class="user-name">
                    <?php D($all_connected_profile->profile_name); ?>
                    <span>Freelancer</span> </div>
                </div>
              </div>
              <ul class="user-menu-small-nav">
                <a class="dropdown-item" href="<?php D(get_link('dashboardURL'))?>"> <i class="icon-feather-grid"></i>
                <?php D(__('header_dashboard','Dashboard'))?>
                </a> <a class="dropdown-item" href="<?php D(get_link('FavoritesURL'))?>"><i class="icon-feather-heart"></i> Favorites</a>
                <div class="dropdown"> <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-feather-anchor"></i>
                  <?php D(__('header_for_seller','For Seller'))?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php D(get_link('sellingOrderURL'))?>">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_orders','Orders'))?>
                    </a>
                    <a class="dropdown-item" href="<?php D(get_link('manageproposalURL'))?>">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_My_Proposals','My proposals'))?>
                    </a>
                    <a class="dropdown-item" href="<?php D(get_link('buyerRequests')); ?>">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Buyer_Requests','Custom Requests'))?>
                    </a>
                    <a class="dropdown-item" href="<?php D(get_link('revenueURL')); ?>">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Revenues','Earnings'))?>
                    </a>
                    <a class="dropdown-item" href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Profile_View','Profile view'))?>
                    </a> </div>
                </div>
                <div class="dropdown">
                  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-feather-user"></i>
                  <?php D(__('header_My_Account','My Account'))?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="<?php D(get_link('buyingOrderURL'))?>">
                      <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Orders','My Orders'))?>
                    </a>
                    <a class="dropdown-item" href="<?php D(get_link('PurchasesURL'))?>">
                      <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Purchases','My Purchases'))?>
                    </a>
                    <a class="dropdown-item" href="<?php D(get_link('TransactionHistoryURL'));?>">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Transaction_history','Transaction History'))?>
                    </a>
                    <a class="dropdown-item" href="<?php D(get_link('FavoritesURL'))?>">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Favorites','Favorites'))?>
                    </a>
                  </div>
                </div>
                <div class="dropdown">
                  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-line-awesome-hand-stop-o"></i>
                  <?php D(__('header_Requests','Requests'))?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item" href="<?php D(get_link('postrequestURL'))?>">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Post A Request','Post A Custom Request'))?>
                    </a> <a class="dropdown-item" href="<?php D(get_link('managerequestURL'))?>">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Manage_Requests','My Custom Requests'))?>
                    </a> </div>
                </div>
                <a class="dropdown-item" href="<?php D(get_link('ContactURL'));?>"> <i class="icon-feather-phone"></i>
                <?php D(__('header_Contacts','Manage Contacts'))?>
                </a> <a class="dropdown-item" href="<?php D(get_link('MessageBoard'));?>"> <i class="icon-feather-mail"></i>
                <?php D(__('header_Inbox_Messages','Messages'))?>
                </a> <a class="dropdown-item" href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>"> <i class="icon-feather-user"></i>
                <?php D(__('header_My_Profile','My Profile'))?>
                </a>
                <div class="dropdown"> <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-feather-settings"></i>
                  <?php D(__('header_Settings','Settings'))?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item" href="<?php D(get_link('settingsURL'))?>">
                  <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Profile_Settings','Profile Settings'))?>
                    </a>
                    <a class="dropdown-item" href="<?php D(get_link('settingsURL'))?>?tab=account">
                    <i class="icon-line-awesome-hand-o-right"></i> <?php D(__('header_Account_Settings','Account Settings'))?>
                    </a> </div>
                </div>
                <a class="dropdown-item" href="<?php D(get_link('logoutURL'))?>"> <i class="icon-material-outline-power-settings-new"></i>
                <?php D(__('header_Logout','Logout'))?>
                </a>
              </ul>
            </div>
          </div>
        </div>
        
        <!-- User Menu / End -->
        
        <?php }else{?>
        <div class="header-widget hide-under-991">                     
          <div class="widget-center">
            <div class="widget-center-align">
              <a href="<?php D(get_link('loginURL'))?>" class="btn btn-outline-site" >
              <!--<img src="<?php // D(theme_url().IMAGE)?>log-in.png" alt="">--><i class="icon-feather-log-in"></i> <?php D(__('header_login','Log In'))?>
              </a>
              <a href="<?php D(get_link('registerURL'))?>" class="btn btn-site ml-3 mr-3_">
              <i class="icon-line-awesome-mouse-pointer"></i> <?php D(__('header_post_gigs','Post Gigs'))?>
              </a>
              <?php /*?>

              <a href="<?php D(VZ)?>" onClick="setlang('en')" class="<?php if($this->session->userdata('current_lang')=='en' || $this->session->userdata('current_lang')=='' ){D('active');}?>"><!--<img src="<?php echo theme_url().IMAGE;?>en.svg" alt="" width="30" />-->EN</a> <span class="text-muted">|</span> <a href="<?php D(VZ)?>" onClick="setlang('ar')" class="<?php if($this->session->userdata('current_lang')=='ar'){D('active');}?>"><!--<img src="<?php echo theme_url().IMAGE;?>ae.svg" alt="" width="30" />-->AR</a><?php */?>
              <?php // D($this->session->userdata('current_lang'));?>
            </div>
          </div>
        </div>
        <?php }?>
        <div class="header-widget setlang">
          <a href="<?php D(VZ)?>" onClick="setlang('en')" class="log-in-button <?php if($this->session->userdata('current_lang')=='en' || $this->session->userdata('current_lang')=='' ){D('active');}?>"><img src="<?php echo theme_url().IMAGE;?>en.svg" alt="English" height="32" width="32" /><!--<i class="icon-line-awesome-language"></i> EN--></a> 
            <!--<span class="text-muted pl-0 pr-0">|</span>-->
          <a href="<?php D(VZ)?>" onClick="setlang('ar')" class="log-in-button <?php if($this->session->userdata('current_lang')=='ar'){D('active');}?>"><img src="<?php echo theme_url().IMAGE;?>ae.svg" alt="Arabic" height="32" width="32" /><!--<i class="icon-line-awesome-language"></i> AR--></a>
              <?php // D($this->session->userdata('current_lang'));?>
        </div>
        <!-- Mobile Navigation Button --> 
        
        <span class="mmenu-trigger">
        <button class="hamburger hamburger--collapse" type="button"> <span class="hamburger-box"> <span class="hamburger-inner"></span> </span> </button>
        </span>
        <?php if($loggedUser && $this->router->fetch_class()!='home' && $this->router->fetch_class()!='category'){

		}else{ ?>
        <a href="javascript:void(0)" class="cat-trigger"><i class="icon-feather-more-vertical"></i></a>
        <?php }?>
      </div>
      
      <!-- Right Side Content / End --> 
      
    </div>
    
    <!-- Header / End --> 
    
  </div>
</header>
<div class="clearfix"></div>

<!-- Header Container / End --> 

<script>

$(document).ready(function(){

  $('.cat-trigger').click(function(){

    $('.filter-container').show();

  });

  $('.filter-header h3 > .icon-feather-x').click(function(){

	$('.filter-container').hide();

  });  

});

</script>
<?php // if($loggedUser && $this->router->fetch_class()!='home' && $this->router->fetch_class()!='category'){}else{D('');}?>
<?php /*?><li>

	<form action="<?php D(get_link('SearchURL'))?>" method="get">

	<div class="input-group">

		<input type="text" name="input" class="form-control" placeholder="<?php D(__('header_search_input','Find Services'))?>" value="<?php if($this->input->get('input')){D($this->input->get('input'));}?>">

		<div class="input-group-append">

			<button name="search" class="btn btn_search"><?php D(__('header_search_button','Search'))?></button>

		</div>

	</div>

</form>

</li>



<li>

<?php

	$data=array('seller_name'=>$all_connected_profile->profile_name,'count_cart'=>$count_cart,'count_favorites'=>$count_favorites,'username'=>$username);

	$templateLayout=array('view'=>'inc/mobile-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

	load_template($templateLayout,$data); ?>

</li>

<?php */?>
<?php /*?><?php 

if($loggedUser && $this->router->fetch_class()!='home' && $this->router->fetch_class()!='category'){

$templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');

load_template($templateLayout,$data);

}

?><?php */?>
<?php if($loggedUser){

	$login_member_id=$loggedUser['MID'];

	$memberdetails=getMemberDetails($login_member_id,array('main'=>1));

	if($memberdetails['member']->is_email_verified!=1){

	?>
<div class="alert alert-warning clearfix activate-email-class">
  <div class="float-left mt-2"> <i style="font-size: 125%; pr-2px;" class="fa fa-exclamation-circle"></i>
    <?php D(__('header_verify_email_reminder_message','Please activate your account. Confirmation email has been sent to your email. If you need help, contact'));?>
    <a  target="_blank" href="<?php D(get_link('CustomerSupportURL'))?>" style="color: #856404; font-weight: bold;">
    <?php D(__('header_verify_email_reminder_message_customer_support','Customer Support'));?>
    </a> </div>
  <div class="float-right">
    <button id="send-email" class="btn btn-success btn-sm float-right text-white">
    <?php D(__('header_Resend_Email','Resend Email'));?>
    </button>
  </div>
</div>
<script>

$(document).ready(function(){

	$("#send-email").click(function(){

		$.ajax({

			method: "POST",

			url: "<?php D(get_link('resendEmailURL')); ?>",

			success:function(){

				$("#send-email").html("Resend Email");

				swal({

					type: 'success',

					text: 'Confirmation email sent. Please check your email.',

				})

			}

		});

	});

});



</script>
<?php }



	}?>
