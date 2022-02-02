<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

$httpH="http";
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');


define('FOLDERNAME','fiverr-demoscript/');
define('ADMIN_FOLDERNAME','hackground/');
define('APS_PATH',		$_SERVER['DOCUMENT_ROOT']."/".FOLDERNAME);
define('VPATH',		$httpH.'://'.$_SERVER['HTTP_HOST']."/".FOLDERNAME);
define('APATH',		$_SERVER['DOCUMENT_ROOT']."/".FOLDERNAME);
define('SITE_VPATH',		$httpH.'://'.$_SERVER['HTTP_HOST']."/".FOLDERNAME);
define('ADMIN_URL',SITE_VPATH.ADMIN_FOLDERNAME);
define('ABS_USERUPLOAD_PATH',APATH."userupload/");
define('URL_USERUPLOAD',SITE_VPATH."userupload/");
define('SITE_URL',VPATH);
define('ASSETS',	"assets/");
define('CSS',		ASSETS."css/");
define('JS',		ASSETS."js/");
define('IMAGE',		ASSETS."images/");
define('ACTIVE_THEME',			"default");
define('SETTINGS_LOGO',			"logo.png");
define('VERSION_JS',			"1.0");
define('VERSION_CSS',			"1.0");
define('VZ',			  	"javascript:void(0)");
define('LOGO_NAME',			  	"logo.png",TRUE);
define('SET_EMAIL_CRON',	"1");
define('NO_IMAGE', SITE_URL.'themes/default/assets/images/no_image.jpg');
define('REQUEST_PENDING',						"1"); #request 
define('REQUEST_ACTIVE',			  			"2"); #request 
define('REQUEST_PAUSED',						"3"); #request 
define('REQUEST_UNAPPROVED',					"5"); #request 
define('REQUEST_DELETED',						"6"); #request 

define('PROPOSAL_PENDING',						"1"); #proposal 
define('PROPOSAL_ACTIVE',						"2"); #proposal 
define('PROPOSAL_PAUSED',						"3"); #proposal 
define('PROPOSAL_MODIFICATION',					"4"); #proposal 
define('PROPOSAL_DECLINED',						"5"); #proposal 
define('PROPOSAL_DELETED',						"6"); #proposal 

define('ORDER_PENDING',							"1"); #order 
define('ORDER_PROCESSING',						"2"); #order 
define('ORDER_REVISION',						"3"); #order 
define('ORDER_CANCELLATION',					"4"); #order 
define('ORDER_CANCELLED',						"5"); #order 
define('ORDER_DELIVERED',						"6"); #order 
define('ORDER_COMPLETED',						"7"); #order  