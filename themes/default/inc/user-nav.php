<?php

$loggedUser=$this->session->userdata('loggedUser');
$username=$loggedUser['UNAME'];
$arr=array(
    'select'=>'m.member_name,c.country_name,cnt.country_code_short',
    'table'=>'member as m',
    'join'=>array(
        array('table'=>'member_address as a','on'=>'m.member_id=a.member_id','position'=>'left'),
        array('table'=>'country as cnt','on'=>'a.member_country=cnt.country_code','position'=>'left'),
        array('table'=>'country_names as c','on'=>"`a`.`member_country`=`c`.`country_code` and `c`.`country_lang`='".getSetlang()."'",'position'=>'left'),
        
    ),
    'where'=>array('m.member_id'=>$loggedUser['MID']),
    'single_row'=>true,
);
$member_details=getData($arr);
$avg_rating=$total_review=0;
$rating=$this->db->select('AVG(buyer_rating) as rating,count(review_id) as total_review')->where('review_seller_id',$loggedUser['MID'])->from('buyer_reviews')->get()->row();
if($rating){
    $avg_rating=$rating->rating;
    $total_review=$rating->total_review;
}
?>
<div class="card">
    <div class="card-body">
        <div class="avatar-wrapper"><img src="<?php D(getMemberLogo($loggedUser['MID'])); ?>" class="rounded-circle img-fluid"></div>
        <div class="text-center mb-3">
            <h4><a href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>">Richard Wills</a></h4>
            <!-- <h5>Civil Engineer</h5> -->
            <div class="star-rating d-block" data-rating="<?php printf("%.1f",$avg_rating);?>" data-showcount="true" data-digit="(25)"></div>
            <img class="flag" src="<?php D(theme_url().IMAGE);?>flags/<?php echo strtolower($member_details->country_code_short);?>.svg" alt="" title="<?php D($member_details->country_name); ?>" data-tippy-placement="top"> <?php D($member_details->country_name); ?>
        </div>
            
        <div class="mp-box mp-box-white">	
            <div class="box-row">
                <ul class="main-cat-list active">
                    <li>
                        <a class="active" href="<?php D(get_link('dashboardURL'))?>">
                        <i class="icon-feather-grid text-blue"></i> <?php D(__('header_dashboard','Dashboard'))?>
                        </a>
                    </li>
                    
                    <!--<li>
                    <a href="#">
                        <?php // D(__('header_My_Account','My Account'))?> <i class="icon-feather-chevron-down"></i>
                    </a>
                    </li>-->
        
                    <li>
                        <a  href="<?php D(get_link('TransactionHistoryURL'));?>">
                        <i class="icon-feather-dollar-sign text-pink"></i> <?php D(__('header_Transaction_history','Transaction History'))?>
                        </a>
                    </li>
                                
                    <li>
                        <a href="<?php D(get_link('viewprofileURL'));?><?php D($username); ?>">
                        <i class="icon-feather-user text-yellow"></i> <?php D(__('header_My_Profile','My Profile'))?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php D(get_link('settingsURL'))?>"><i class="icon-feather-settings text-purple"></i> <?php D(__('header_Profile_Settings','Profile Settings'))?></a>
                    </li>
                    <li>
                        <a href="<?php D(get_link('settingsURL'))?>?tab=account"><i class="icon-feather-settings text-green"></i> <?php D(__('header_Account_Settings','Account Settings'))?></a>
                    </li>
                                                
                    <li>
                        <a href="<?php D(get_link('ContactURL')); ?>">
                        <i class="icon-feather-phone text-orange"></i> <?php D(__('header_Contacts','Contacts'))?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php D(get_link('MessageBoard'));?>"><i class="icon-feather-mail text-teal"></i> <?php D(__('header_Inbox_Messages','Inbox Messages'))?></a>
                    </li>
                    <li>
                        <a href="<?php D(get_link('NotificationListURL'));?>"><i class="icon-feather-bell text-red"></i> <?php D(__('header_Notifications','Notifications'))?></a>
                    </li>
                    <li>
                        <h5><?php D(__('header_for_seller','For Seller'))?></h5>
                        <div class="menu-cont">
                            <ul>
                                <li>
                                    <a href="<?php D(get_link('sellingOrderURL'))?>">
                                        <i class="icon-feather-shopping-bag text-indigo"></i> <?php D(__('header_orders','Orders'))?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php D(get_link('manageproposalURL'))?>">
                                    <i class="icon-feather-file-text text-blue"></i> <?php D(__('header_My_Proposals','My Proposal'))?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php D(get_link('buyerRequests')); ?>">
                                    <i class="icon-line-awesome-hand-stop-o text-pink"></i> <?php D(__('header_Buyer_Requests','Custom Requests'))?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php D(get_link('revenueURL')); ?>">
                                    <i class="icon-line-awesome-money text-green"></i> <?php D(__('header_Revenues','Earnings'))?>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>

                    <li>
                        
                        <h5><?php D(__('header_for_buyer','For Buyer'))?></h5>
                    
                        <div class="menu-cont">
                            <ul>
                                <li>
                                    <a href="<?php D(get_link('buyingOrderURL'))?>">
                                    <i class="icon-feather-shopping-cart text-orange"></i> <?php D(__('header_Orders','My Orders'))?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php D(get_link('managerequestURL'))?>">
                                    <i class="icon-line-awesome-hand-stop-o text-purple"></i> <?php D(__('header_Manage_Requests','My Custom Requests'))?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php D(get_link('PurchasesURL'))?>">
                                    <i class="icon-feather-tag text-teal"></i> <?php D(__('header_Purchases','My Purchases'))?>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
</div>
</div>