<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['setdefaultlang'] = 'home/setlang';
$route['select-profile']='accesspanel/setprofile';
$route['login']='accesspanel';

$route['ajax/login-check']='accesspanel/userloginCheckAjax';
$route['sign-up']='accesspanel/signup';
$route['ajax/signup-check']='accesspanel/usersignupCheckAjax';

$route['forgot-password']='accesspanel/forgot';


$route['logout']='accesspanel/signout';
$route['order-details/(:num)']='orders/order_details/$1';
$route['buying-orders']='orders/buyer';
$route['selling-orders']='orders/seller';
$route['revenue']='orders/revenue';
$route['verify-user-forgot/(:any)']='accesspanel/resetpassword/$1';
$route['verify-user/(:any)']='accesspanel/userverify/$1';

$route['all-categories']='category/allcategory';
$route['featured-proposals']='category/proposals/featured';
$route['top-proposals']='category/proposals/top';
$route['random-proposals']='category/proposals/random';
$route['search']='category/index';
$route['c/(:any)/(:any)']='category/index/category-$1:subcategory-$2';
$route['c/(:any)']='category/index/category-$1';
$route['add_delete_favorite']='proposals/updatefavorite';
$route['referral_modal']='proposals/referralmodal';
$route['p-(:any)']='home/profileview/$1';

$route['terms-and-conditions']='cms/policy/terms-and-conditions';
$route['refund-policy']='cms/policy/refund-policy';
$route['pricing-and-promotions-policy']='cms/policy/pricing-and-promotions-policy';
$route['about-us']='cms/index/about-us';
$route['vision']='cms/index/vision';
$route['mission']='cms/index/mission';
$route['news']='cms/index/news';
$route['founders']='cms/index/founders';
$route['knowledge-bank']='cms/knowledgebank';
$route['how-it-works']='cms/howitworks';
$route['contact-us']='cms/contactus';

$route['transaction-history']='dashboard/transaction_history';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
