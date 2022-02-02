<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/***************Application constant*********************/

define('PROJECT',								'fiverr-demoscript/');
define('URL'                ,                   'http://'.$_SERVER['HTTP_HOST'].'/'.PROJECT);
define('BASE_URL'			,					URL);
define('ASSETS'             ,                   URL.'assets/');
define('THEME'				,					'default/');
define('APP_ASSETS'         ,                   ASSETS.'app_assets/');
define('JS'                 ,                   APP_ASSETS.THEME.'js/');
define('CSS'                ,                   APP_ASSETS.THEME.'css/');
define('IMAGES'         	,                   APP_ASSETS.THEME.'images/');
define('EXTRA'         	    ,                   	APP_ASSETS.'extra/');
define('ICONS'           	,                   	ASSETS.'icons/');
define('CURRENCY'         	,                   	'&#8377;');
define('SHOW_MIN_ASSETS'    ,                   	FALSE);
define('USER_UPLOAD'    	,                   	URL.'userupload/');

define('LC_PATH'    ,                   	dirname(__DIR__).'/');


/***************Admin constant*********************/

define('ADMIN_URL'                ,                   'http://'.$_SERVER['HTTP_HOST'].'/'.PROJECT.'hackground/');
define('ADMIN_BASE_URL'			,					ADMIN_URL);
define('ADMIN_ASSETS'             ,                   ADMIN_URL.'assets/');
define('ADMIN_THEME'				,					'admin/'); // active theme
define('ADMIN_APP_ASSETS'         ,                   ADMIN_ASSETS.'app_assets/');
define('ADMIN_JS'                 ,                   ADMIN_APP_ASSETS.ADMIN_THEME.'js/');
define('ADMIN_CSS'                ,                   ADMIN_APP_ASSETS.ADMIN_THEME.'css/');
define('ADMIN_IMAGES'         	,                   ADMIN_APP_ASSETS.ADMIN_THEME.'images/');
define('ADMIN_COMPONENT'         	,                   ADMIN_APP_ASSETS.ADMIN_THEME.'bower_components/');
define('ADMIN_EXTRA'         	,                   	ADMIN_APP_ASSETS.'extra/');
define('ADMIN_LC_PATH'    ,                   		LC_PATH.'hackground/');



define('JS_VOID'    ,                   		'javascript:void(0)');
define('LOGO_NAME'    ,                   		'logo.png');
define('SET_EMAIL_CRON',	"1");







