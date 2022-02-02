<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$lang_key="";
/*$ci =& get_instance();
$lang_ignore=$ci->config->item('lang_ignore');
if($_COOKIE['user_lang'] && $lang_ignore==FALSE){
	$lang_key=$_COOKIE['user_lang']."/";	
}
define('USER_LANG_KEY',$lang_key);
$lang_key=USER_LANG_KEY;*/
$config['homeURL'] = '';
$config['loginURL'] = 'login/'; #router
$config['registerURL'] = 'sign-up/';#router
$config['ForgotPasswordURL'] = 'forgot-password/';#router
$config['loginURLAJAX']= 'accesspanel/userloginCheckAjax';
$config['forgotURLAJAX']= 'accesspanel/userforgotCheckAjax';
$config['resetURLAJAX']= 'accesspanel/userresetCheckAjax';
$config['registerURLAJAX'] = 'ajax/signup-check'; #router
$config['VerifyURL'] = 'verify-user/'; #router
$config['resetPasswordURL'] = 'accesspanel/resetpassword';
$config['logoutURL'] = 'logout/';
$config['dashboardURL'] = 'dashboard/';
$config['settingsURL'] = 'settings';
$config['resendEmailURL'] = 'settings/resendemail';
$config['uploadFilememberFormCheckAJAXURL'] = 'settings/uploadattachment';
$config['editprofileURLAJAX'] = 'settings/editprofileCheckAjax';
$config['editaccountURLAJAX'] = 'settings/editaccountCheckAjax';
$config['redirectToURL'] = '';
$config['SetLangURL'] = 'setdefaultlang'; #router

$config['FeaturedProposalsURL'] = 'featured-proposals'; #router
$config['TopProposalsURL'] = 'top-proposals'; #router
//$config['RandomProposalsURL'] = 'random-proposals';#router 
$config['RandomProposalsURL'] = 'search';#router 
$config['MobileCategory'] = 'category/categorylist'; #router
$config['AllCategories'] = 'all-categories'; #router
$config['SearchURL'] = 'search'; #router
$config['CategoryURL'] = 'c/'; #router
$config['ProposalListURLAJAX'] = 'category/load_proposal'; 
$config['getsubcatAJAXURL'] = 'home/getdata';

$config['managerequestURL'] = 'requests/manage_request';
$config['editrequestURL'] = 'requests/edit_request';
$config['viewofferURL'] = 'requests/viewoffer';
$config['actionrequestURLAJAX'] = 'requests/actionrequestCheckAjax';
$config['postrequestURL'] = 'requests/post_request';
$config['postrequestURLAJAX'] = 'requests/postrequestCheckAjax';
$config['editrequestURLAJAX'] = 'requests/editrequestCheckAjax';
$config['uploadFileRequestFormCheckAJAXURL'] = 'requests/uploadattachment';
$config['sendOfferRequest'] = 'requests/sendoffermodal';
$config['sendOfferRequestDerails'] = 'requests/sendoffermodaldetails';
$config['saveOfferRequest'] = 'requests/saveoffer';
$config['buyerRequests'] = 'requests/buyer_requests';
$config['OfferAcceptRequest'] = 'requests/acceptofferpayment'; 


$config['startsellingURL'] = 'proposals/start_selling';
$config['postproposalURL'] = 'proposals/post_proposal';
$config['getextraProposalAJAXURL'] = 'proposals/getextra';
$config['postproposalURLAJAX'] = 'proposals/postproposalCheckAjax';
$config['editproposalURLAJAX'] = 'proposals/editproposalCheckAjax';
$config['actionproposalURLAJAX'] = 'proposals/actionproposalCheckAjax';
$config['uploadFileProposalFormCheckAJAXURL'] = 'proposals/uploadattachment';
$config['manageproposalURL'] = 'proposals/manage_proposal';
$config['ProposalDetailsURL'] = 'proposals/view';
$config['editproposalURL'] = 'proposals/edit_proposal';
$config['ReportProposalURLAJAX'] = 'proposals/report';
$config['ProposalReferralURL'] = 'proposals/referral_proposal';
$config['actionproposalPayfeatureURLAJAX'] = 'proposals/pay_featured_listing';
$config['actionvacationsetURLAJAX'] = 'proposals/vacation';
$config['viewprofileURL'] = 'p-';
$config['referralShareLink'] = 'proposals/sharereferral';
$config['FavoritesURL'] = 'proposals/favorites'; 
$config['messageLink'] = 'message/index';
$config['messageLinkAJAX'] = 'message/checkroom';
$config['MessageBoard'] = 'message/messageboard'; 
$config['MessageBoardPartLoad'] = 'message/load_part'; 
$config['MessageBoardLoadAjax'] = 'message/load_conversation'; 
$config['SendMessageBoard'] = 'message/sendmessage'; 
$config['TypeStatus'] = 'message/typestatus'; 
$config['SendOfferMessageAjax'] = 'message_new/sendofferpopupajax'; 
$config['MessageOfferAccept'] = 'message/acceptofferpayment'; 
$config['saveActionURLMessageAJAX'] = 'message/saveAction';

$config['NotificationListURL'] = 'notifications/index';
$config['getNotificationList'] = 'notifications/notificationlist';
$config['deleteActionURLNotificationAJAX'] = 'notifications/deletenotification';

$config['getMessageList'] = 'message/messagelist';


$config['NotificationDetailsLink'] = 'notifications/details/';
$config['OnlineUserUp'] = 'notifications/online_uer_up';
$config['CheckNotification'] = 'notifications/check_notification';

$config['saveCheckoutFormCheckAJAXURL'] = 'cart/savecheckoutCheckAjax';
$config['checkoutURL'] = 'cart/checkout';
$config['CartURL'] = 'cart/index';
$config['processCheckoutFormCheckAJAXURL'] = 'cart/checkoutprocessCheckAjax';
$config['processCartFormCheckAJAXURL'] = 'cart/cartprocessCheckAjax';
$config['actionCartCheckAJAXURL'] = 'cart/cartaction';
$config['processFeaturedFormCheckAJAXURL'] = 'proposals/makefeature/';
$config['processOfferFormCheckAJAXURL'] = 'proposals/offercheckout/';
$config['processOfferFormCheckRequestAJAXURL'] = 'proposals/requestoffercheckout/';
$config['OrderDetailsURL'] = 'order-details/'; #router
$config['sendMessageURL'] = 'orders/sendmessage'; 
$config['saveActionURLAJAX'] = 'orders/saveAction'; 
 
$config['transferActionURLAJAX'] = 'orders/transferAction'; 
$config['loadConversationURL'] = 'orders/load_conversation'; 
$config['buyingOrderURL'] = 'buying-orders'; #router
$config['sellingOrderURL'] = 'selling-orders'; #router
$config['PurchasesURL'] = 'orders/purchases'; 
$config['BuyingHistoryURL'] = 'orders/buyinghistory/'; 
$config['SellingHistoryURL'] = 'orders/sellinghistory/'; 
$config['ContactURL'] = 'orders/contacts'; 
$config['revenueURL'] = 'revenue'; #router
$config['PaypalCheckOut'] = 'payment/paypal/';
$config['PaypalNotify'] = 'payment/paypalnotify/';
$config['TelrNotify'] = 'payment/telrnotify/';


$config['CMShowitwork'] = 'how-it-works'; #router
$config['CMStermsandconditions'] = 'terms-and-conditions'; #router
$config['CMSrefundpolicy'] = 'refund-policy'; #router
$config['CMSpricingandpromotionspolicy'] = 'pricing-and-promotions-policy'; #router
$config['CMSknowledgebank'] = 'knowledge-bank'; #router
$config['CMSaboutus'] = 'about-us';
$config['CMSVision'] = 'vision';
$config['CMSMission'] = 'mission';
$config['CMSNews'] = 'news';
$config['CMSfoundersURL'] = 'founders';
$config['CustomerSupportURL'] = 'cms/support';
$config['ContactUsURL'] = 'contact-us';
$config['TransactionHistoryURL'] = 'transaction-history'; #router















$config['settingpasswordURL'] = 'password/security_password';
$config['settingchangepasswordFormAJAXURL'] = 'password/change_password_form';
$config['settingchangepasswordFormCheckAJAXURL'] = 'password/change_password_form_check';

/*freelancer*/
$config['settingaccountInfoURL'] = 'settings/contact_info';
$config['settingaccountInfoDataAJAXURL'] = 'settings/contact_info_account_data';
$config['settingaccountInfoFormAJAXURL'] = 'settings/contact_info_account_form';
$config['settingaccountInfoFormCheckAJAXURL'] = 'settings/contact_info_account_form_check';
$config['settinglocationDataAJAXURL'] = 'settings/contact_location_data';
$config['settinglocationFormAJAXURL'] = 'settings/contact_location_form';
$config['settinglocationFormCheckAJAXURL'] = 'settings/contact_location_form_check';


/*client*/
$config['settingclientaccountInfoURL'] = 'clientsettings/client_contact_info';
$config['settingclientaccountInfoDataAJAXURL'] = 'clientsettings/contact_info_account_data';
$config['settingclientaccountInfoFormAJAXURL'] = 'clientsettings/contact_info_account_form';
$config['settingclientaccountInfoFormCheckAJAXURL'] = 'clientsettings/contact_info_account_form_check';
$config['settingclientcompanyDataAJAXURL'] = 'clientsettings/contact_company_data';
$config['settingclientcompanyFormAJAXURL'] = 'clientsettings/contact_company_form';
$config['settingclientcompanyFormCheckAJAXURL'] = 'clientsettings/contact_company_form_check';
$config['settingclientlogoFormCheckAJAXURL'] = 'clientsettings/logo_form_check';



$config['settingclientlocationDataAJAXURL'] = 'clientsettings/contact_location_data';
$config['settingclientlocationFormAJAXURL'] = 'clientsettings/contact_location_form';
$config['settingclientlocationFormCheckAJAXURL'] = 'clientsettings/contact_location_form_check';


$config['myprofileAJAXURL'] = 'profileview/index';
$config['editprofileAJAXURL'] = 'profileview/get_form';
$config['editprofileFormCheckAJAXURL'] = 'profileview/get_form_check';
$config['editprofileloadDataAJAXURL'] = 'profileview/load_data';
$config['deleteprofileDataAJAXURL'] = 'profileview/delete_data';



$config['conatctURL'] = 'contact-us/';


$config['postprojectFormCheckAJAXURL'] = 'postproject/post_project_form_check';
$config['uploadFileFormCheckAJAXURL'] = 'postproject/uploadattachment';
$config['postprojectSuccessURL'] = 'postproject/success';



$config['downloadProjectFileURL'] = 'project/downloadfile';
$config['viewapplicationURLAJAX'] = 'projectview/application';

$config['myprojectrecentClientURL'] = 'projectclient/recent';
$config['myProjectClientURL'] = 'projectclient/all';
$config['myProjectClientAJAXURL'] = 'projectclient/load_project';
$config['myProjectDetailsBidsClientURL'] = 'projectclient/bids_details';

$config['myProjectDetailsBidsClientloadCountAjaxURL'] = 'projectclient/load_proposal_count';
$config['myProjectDetailsBidsClientloadAjaxURL'] = 'projectclient/load_propasal';
$config['myProjectBidsClientStatusAjaxURL'] = 'projectclient/update_propasal';



$config['myContractClientURL'] = 'projectclient/contract';



$config['downloadTempURL'] = 'welcome/downloadtempfile';




