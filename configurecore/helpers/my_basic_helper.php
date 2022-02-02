<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	Author: Mr. Asharam Pakhira
 *	E-mail: asish9735@gmail.com
 * 	Company: https://www.originatesoft.com
*/
/*-----------------------------------------------------------*/
$ci =& get_instance();
if ( ! function_exists('theme_url'))
{
	function theme_url($theme='')
	{
		$CI 	= get_instance();
		if($theme==''){
		$theme 	= get_active_theme();
		}
		return base_url('/themes/'.$theme).'/';	
	}
}
if ( ! function_exists('load_view'))
{
	function load_view($view='',$data=array(),$buffer=FALSE,$theme='')
	{
		$CI 	= get_instance();
		if($theme=='')
		$theme 	= get_active_theme();
		if(!$buffer)
		{
			if(@file_exists("themes/".$theme."/".$view.".php")){
			$CI->load->view($theme.'/'.$view,$data);
			}else{
			$CI->load->view($theme.'/'.$view,$data);	
			}
		}
		else
		{
			if(@file_exists("themes/".$theme."/".$view.".php"))
			$view_data = $CI->load->view($theme.'/'.$view,$data,TRUE);
			else
			$view_data = $CI->load->view('default/'.$view,$data,TRUE);	
			return $view_data;
		}
	}
}

if ( ! function_exists('load_template'))
{
	function load_template($templateLayout=array('type'=>'','buffer'=>'','theme'=>''),$data=array(),$type='default',$buffer=FALSE,$theme='default')
	{
	
	$CI 	= get_instance();
	$class=$CI->router->class;
		if($templateLayout['type']){
			$type=$templateLayout['type'];
		}
		if($templateLayout['buffer']){
			$buffer=$templateLayout['buffer'];
		}
		if($templateLayout['theme']){
			$theme=$templateLayout['theme'];
		}else{
			$theme 	= get_active_theme();
		}
		if($type=='default'){
			$dataHead=array();
			if($data['seo_tags']){
				$dataHead['seo_tags']=$data['seo_tags'];
			}
			$dataHead['load_css']=$data['load_css'];
			$dataHead['load_js']=$data['load_js'];
			load_view('inc/head_script',$dataHead,'',$theme);
			//load_view('inc/header',$data,'',$theme);
			load_view('inc/header',array(),'',$theme);
			if(@file_exists("themes/".$theme."/".$class."/".$templateLayout['view'].".php")){
				load_view($class."/".$templateLayout['view'],$data,$buffer,$theme);
			}else{
				load_view($templateLayout['view'],$data,$buffer,$theme);
			}
			load_view('inc/footer','','',$theme);
		}elseif($type=='ajax'){
			if(@file_exists("themes/".$theme."/".$class."/".$templateLayout['view'].".php")){
				if($buffer){
					return load_view($class."/".$templateLayout['view'],$data,$buffer,$theme);
				}else{
					load_view($class."/".$templateLayout['view'],$data,$buffer,$theme);
				}
				
			}else{
				if($buffer){
					return load_view($templateLayout['view'],$data,$buffer,$theme);
				}else{
				load_view($templateLayout['view'],$data,$buffer,$theme);
				}
				
			}
		}
		
	}
}
if ( ! function_exists('get_active_theme'))
{
	function get_active_theme()
	{
		if(defined('ACTIVE_THEME'))
		{
			return constant('ACTIVE_THEME');
		}
		else
		{	
			$row = get_option('active_theme');
			if(is_array($row) && isset($row['error']))
			{
				$theme = 'default';
			}
			else
				$theme = $row->setting_value;			
		
			if(!defined('ACTIVE_THEME'))
			{
				define('ACTIVE_THEME',$theme);
			}
			return $theme;
		}
	}
}

/**
 * Generate unique id
 *
 * @param  string  $prefix
 * @return string
 */
if ( ! function_exists('unique_id'))
{
    function unique_id($prefix = null)
    {	
    	$prefix = ($prefix == null)? 'originatesoft'.''.time().''.rand() : $prefix = $prefix.''.time().''.rand();
        return md5(uniqid ($prefix, true));
    }   
}

/**
 * String limitation or read more
 *
 * @param  string  $string
 * @param  string  $limit
 * @param  string  $url
 * @param  string  $url_text
 * @return string
 */


if ( ! function_exists('str_limit'))
{
    function str_limit($string = null, $limit = null, $url = null, $url_text = null)
    {	
    	$limit = ($limit == null)? 120 : $limit;
    	$final_url = "";
    	if(isset($url)){
    		$url_text = ($url_text == null)? 'read more' : $url_text;
    		$final_url = '<a href="http://'.$url.'">'.$url_text.'</a>';
    	}
    	if($string == null){
    		echo "Require string as first argument, limit as second argument [optional, default 120], url as third argument[optional]";
    		return false;
    	}
		// strip tags to avoid breaking any html
		$string = strip_tags($string);

		if (strlen($string) > $limit) {

		    // truncate string
		    $stringCut = substr($string, 0, $limit);

		    // make sure it ends in a word so assassinate doesn't become ass...
		    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'.$final_url; 
		}
		echo $string;
    }   
}

/**
 * Htmlentities check
 *
 * @param  string  $string
 * @return string
 */

//function for escape htmlentities
if ( ! function_exists('e'))
{
    function e($string = null)
    {	
    	$string = ($string == null)? false : $string;
    	return htmlspecialchars($string);       
    }   
}

/**
 * Die and dump an array or object
 *
 * @param  string  $data
 * @return string
 */
//start dd is die and dump
if ( ! function_exists('dd'))
{
	function dd($data = array() ,$return=false, $clean = false){
			if ($clean) {
				echo "<pre style='background-color:#ffffff; border:3px solid #ecf0f1; color:#df5000;'>";
				die(var_dump($data));
				echo "</pre>";
				
			}else{
				echo "<pre style='background-color:#ffffff; border:3px solid #ecf0f1; color:#df5000;'>";
				print_r($data);
				echo "</pre>";			
			}
			if ($return==false) {
				die();
			}
	}  
}
if ( ! function_exists('D'))
{
	function D($data, $clean = false){
		echo $data;
	}  
}
//end dd is die and dump

/**
 * Translate a string
 *
 * @param  string  $string
 * @return string
 */

//start translate
if ( ! function_exists('translate'))
{
	function translate($string = ''){
		$ci =& get_instance();
		return $ci->lang->line($string);
	}  
}

//end translate

/**
 * Encrypt a string to a hash value
 *
 * @param  string  $data, string $key
 * @return string
 */

// encryption function
if ( ! function_exists('enCrypt'))
{
	function enCrypt($data, $key = 'originatesoft') {
	   $salt = '967696b7709ebc3284e1foriginatesoftf233116761f6d06bce3';
	   $key = substr(hash('sha256', $salt.$key.$salt), 0, 32);
	   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	   $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));
	   return $encrypted;
	}
}
//end encryption function

/**
 * Decrypt a string to a hash value
 *
 * @param  string  $data, string $key
 * @return string
 */
// decryptin function
if ( ! function_exists('deCrypt'))
{
	function deCrypt($data, $key = 'originatesoft') {
	   $salt = '967696b7709ebc3284e1foriginatesoftf233116761f6d06bce3';
	   $key = substr(hash('sha256', $salt.$key.$salt), 0, 32);
	   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	   $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, $iv);
	   return $decrypted;
	}
}
// end decryptin function
	

	

/**
 * Send html email.
 *
 * @param  string  $from, string $to, string $subject, string $message
 * @return true on success or false on fail
 */

// sendMail function
if ( ! function_exists('sendMailphp'))
{
	function sendMailphp($from = null, $to = null, $subject = null, $message = null) {

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";        
        $headers .= "From: " . $from . ". \r\n";
        $headers .='Reply-To: webmaster@example.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();
        $htmlmessage = '<html><body>'.wordwrap($message).'</body></html>';
        $rtm = mail($to, $subject, $htmlmessage, $headers);
        return $rtm;

	}
}
// end sendMail function

/**
 * Convert the given string to upper-case.
 *
 * @param  string  $value
 * @return string
 */

// upper function
if ( ! function_exists('upper'))
{
	function upper($value = null) {
		return strtoupper($value);
	}
}
// end upper function
/**
 * Convert the given string to lower-case.
 *
 * @param  string  $value
 * @return string
 */
// lower function
if ( ! function_exists('lower'))
{
	function lower($value = null) {
		return strtolower($value);
	}
}
// end lower function

/**
 * Is a string contains matches?
 *
 * @param  string  $string, string $search
 * @return boolean
 */
// matches function
if ( ! function_exists('matches'))
{
	function matches($string = null, $search = null) {
		if (strpos($string, $search) !== false) {
		    return true;
		}else{
			return false;
		}
	}
}
// end matches function

/**
 * Generate font-awesome-icon
 *
 * @param  string  $url
 * @return stirng font-awesome-icon code
 */
// fontAwesomeSocial function
if ( ! function_exists('fontAwesomeSocial'))
{
	function fontAwesomeSocial($url = null) {
		$socials = array('facebook.com', 'twitter.com', 'linkedin.com', 'pinterest.com', 'google.com', 'instagram.com', 'flickr.com', 'dribbble.com', 'tumblr.com', 'github.com', 'bitbucket.org', 'youtube.com');
		$social_icons = array('facebook', 'twitter', 'linkedin', 'pinterest', 'google-plus', 'instagram', 'flickr', 'dribbble', 'tumblr', 'github', 'bitbucket', 'youtube');
		for ($i=0; $i <count($socials) ; $i++) { 
			if(matches($url, $socials[$i])){
				return "fa fa-".$social_icons[$i];
				break;
			}else{
				if($i+1 == count($socials)){ return "fa fa-check"; }
			}
		}
	}
}
// end fontAwesomeSocial function



/**
 * check if exist flash message
 *
 * @param  string  $key
 * @return boolean ture
 */

// hasFlash function
if ( ! function_exists('hasFlash'))
{
	function hasFlash($key) {
		$ci =& get_instance();
		if($ci->session->flashdata($key) !== null){
			return true;
		}else{
			return false;
		}
	}
}
// end hasFlash function

/**
 * return set flash message
 *
 * @param  string  $key
 * @return string $value in success and false if fail
 */

// setFMessage function
if ( ! function_exists('setFMessage'))
{
	function setFMessage($key, $value) {
		$ci =& get_instance();
		if (isset($key) && isset($value)) {
			$ci->session->set_flashdata($key, $value);
		}
	}
}
// end setFMessage function


/**
 * return flash message if exist
 *
 * @param  string  $key
 * @return string $value in success and false if fail
 */

// flashMessage function
if ( ! function_exists('flashMessage'))
{
	function flashMessage($key) {
		$ci =& get_instance();
		if($ci->session->flashdata($key) !== null){
			return $ci->session->flashdata($key);
		}else{
			return '';
		}
	}
}
// end flashMessage function

/**
 * formVRules
 *
 * @param  string  $name, $label, $rules
 * @return string $value in success and false if fail
 */

// fromVRules function
if ( ! function_exists('fromVRules'))
{
	function fromVRules($name, $label, $rules) {
		$ci =& get_instance();
		if(isset($name) && isset($label) && isset($rules)){
			$ci->form_validation->set_rules($name, $label, $rules);
		}
	}
}
// end fromVRules function

/**
 * formVRules
 *
 * @return string $value in success and false if fail
 */

// isVRulePassed function
if ( ! function_exists('isVRulePassed'))
{
	function isVRulePassed() {
		$ci =& get_instance();
		$rtn = $ci->form_validation->run();
		if ($rtn == FALSE) {
			return FALSE;
		}else{
			return TRUE;
		}
	}
}
if ( ! function_exists('isVRuleError'))
{
	function isVRuleError() {
		$ci =& get_instance();
		return $ci->form_validation->error_array();
	}
}
if ( ! function_exists('get_link'))
{
	function get_link($var){
		$ci =& get_instance();
		return VPATH.$ci->config->item($var);
	}
}
// end isVRulePassed function
function checkrequestajax() {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        
    } else {
        die('error ');
    }
}
if ( ! function_exists('load_css'))
{
    function load_css($file=array(), $atts=array())
    {
       return $file;
        if(!empty($file)){
        	$element='';
            foreach($file as $e) {
                $element .= '<link rel="stylesheet" href="' . theme_url().CSS . $e  .'?v='.VERSION_CSS.'"';

                foreach ( $atts as $key => $val )
                    $element .= ' ' . $key . '="' . $val . '"';
                $element .= ' />'."\n";
              
            }
             return $element;
        } 
    }
}

if ( ! function_exists('load_js'))
{
    function load_js($file=array(), $atts = array())
    {
         return $file;
         if(!empty($file)){
        	$element='';
        	foreach($file as $e) {
            $element .= '<script type="text/javascript" src="' . theme_url().JS . $e .'?v='.VERSION_JS.'"';

            foreach ( $atts as $key => $val )
                $element .= ' ' . $key . '="' . $val . '"';
            $element .= '></script>'."\n";
			}
            return $element;
        }
       
    }
}
//=============================Codeigniter CRUD & Other functionalities====================================

/**
 * loadModel
 * 
 * @param  strring  $model_name
 *
 */

// loadModel function
if ( ! function_exists('loadModel'))
{
	function loadModel($model_name = null) {
		$ci =& get_instance();
		if($model_name !== null){
			$ci->load->model($model_name);
		}else{
			return false;
		}
	}
}
// end loadModel function

/**
 * Return a segment of the URI
 * 
 * @param  integer  $segment
 * @return strin $value
 */

// segment function
if ( ! function_exists('segment'))
{
	function segment($segment = null) {
		$ci =& get_instance();
		$segment = ($segment != null)? (int)trim($segment) : 1;
		return $ci->uri->segment($segment);
	}
}
// end segment function


/**
 * Redirect to previous URL using JS
 */

// goPrevious function
if ( ! function_exists('goPrevious'))
{
	function goPrevious() {
		echo "javascript:window.history.go(-1);";
	}
}
// end goPrevious function


/**
 * Return value of the given key of form data posted in POST method
 *
 * @param  string  $field_name
 * @return strinv $value
 */

// post function
if ( ! function_exists('post'))
{
	function post($field_name = null, $xxs_filter = null) {
		$ci =& get_instance();
		if($field_name != null){
			return $ci->input->post(trim($field_name), $xxs_filter);
		}else{
			return '';
		}
	}
}
// end post function



/**
 * * Return value of the given key of form data posted in GET method
 *
 * @param  string  $field_name
 * @return strinv $value
 */

// get function
if ( ! function_exists('get'))
{
	function get($field_name = null, $xxs_filter = null) {
		$ci =& get_instance();
		if($field_name != null){
			return $ci->input->get(trim($field_name), $xxs_filter);
		}else{
			return '';
		}
	}
}
// end get function



/**
 * Load a view
 *
 * @param  string  $view_name, array $data
 * @return strinv $value
 */

// view function
if ( ! function_exists('view'))
{
	function view() {
		$ci =& get_instance();
		$args = func_get_args();
		$num_of_args = func_num_args();
		if ($num_of_args == 0) {
			echo "View method required at least one argument! Note: Required a view page.";
			return false;
		}
		$pos = strpos($args[0], '.');
		$view_path = ($pos)? str_replace('.', '/', $args[0]) : $args[0];
		if($num_of_args==1){
			return $ci->load->view($view_path);
		}
		if($num_of_args==2){
			return $ci->load->view($view_path, $args[1]);
		}	    

	}
}
// end view function


function getFieldData($select, $table, $feild = "", $value = "", $where = null, $limit_from = 0, $limit_to = 0) {
	$ci =& get_instance();
    $ci->db->select($select);
    if ($value != '' AND $feild != '') {
        if ($limit_from > 0) {
            $rs = $ci->db->get_where($table, array($feild => $value), $limit_to, $limit_from);
        } else {
            $rs = $ci->db->get_where($table, array($feild => $value));
        }
    } else {
        if ($limit_from > 0) {
            $rs = $ci->db->get_where($table, $where, $limit_to, $limit_from);
        } else {
            $rs = $ci->db->get_where($table, $where);
        }
    }
    $data = '';
    foreach ($rs->result() as $row) {
        $data = $row->$select;
    }
    return $data;
}

/**
 * Insert data into Table
 *
 * @param  string  $table, array $data
 * @return boolean ture on success, false on fail
 */

// create function
if ( ! function_exists('insertTable'))
{
	function insertTable($table = null, $data = null, $inserted_id = false, $batch = false) {
		$ci = & get_instance();

		if(!empty($table) || $table != null){
			$table = strtolower(trim($table));
		}else{
			echo "Table is not set! Please use a table name as first argument.";
			return false;
		}

		if(!is_array($data)){
			echo "Please use an array as second argument!";
			return false;
		}else{
			if($batch){
				$rtn = $ci->db->insert_batch($table, $data);
			}else{
				$rtn = $ci->db->insert($table, $data);
			}
			if($inserted_id){
				return $ci->db->insert_id();
			}else{
				return $rtn;
			}
		}

	}
}
// end create function
if ( ! function_exists('lastQuery'))
{
	function lastQuery($return=true){
		$ci =& get_instance();
		$ci->load->database();
		echo $ci->db->last_query();
		if($return!='true'){
			die();
		}
	}
}
if ( ! function_exists('getData'))
{
	function getData($data=array()){
	$ci =& get_instance();
	$ci->load->database();
	$select='*';
	$table =null;
	$type=null;
	$order=null;
	$group=null;
	$limit=null;
	$where=array();
	$where_in=array();
	$join=array();
	$return_count=FALSE;
	$single_row=FALSE;
	if(!empty($data['select'])){
		$select=$data['select'];
	}
	if(!empty($data['table'])){
		$table=$data['table'];
	}
	if(!empty($data['where'])){
		$where=$data['where'];
	}
	if(!empty($data['where_in'])){
		$where_in=$data['where_in'];
	}
	if(!empty($data['join'])){
		$join=$data['join'];
	}
	if(!empty($data['type'])){
		$type=$data['type'];
	}
	if(!empty($type) || $type != null){
		$type = strtolower(trim($type));
	}else{
		$type = 'object';
	}
	if(!empty($data['order'])){
		$order=$data['order'];
	}
	if(!empty($data['limit'])){
		$limit=$data['limit'];
	}
	if(!empty($data['group'])){
		$group=$data['group'];
	}
	if(!empty($data['return_count'])){
		$return_count=$data['return_count'];
	}
	if(!empty($data['single_row'])){
		$single_row=$data['single_row'];
	}
	$ci->db->select($select);
	if(!empty($table) || $table != null){
		 $table = trim($table);
	}else{
		echo "Table is not set! Please use a table name as first argument.";
		return false;
	}
	
	
	$ci->db->from($table);
	if ($join) {
		foreach($join as $j){
			$ci->db->join($j['table'],$j['on'],$j['position']);		
		}
			
	}
	if ($where) {
		$ci->db->where($where);			
	}
	if ($where_in) {
		foreach($where_in as $key=>$where_in_data){
			$ci->db->where_in($key,$where_in_data);	
		}
	}
	if(!empty($group) || $group != null){
			$ci->db->group_by($group);
	}
	if(!empty($order) || $order != null){
		foreach($order as $ord){
			$ci->db->order_by($ord[0],$ord[1]);
		}
	}
	if((!empty($group) || $group != null) && strtoupper($return_count)==TRUE){
		 return $ci->db->get()->num_rows();
	}elseif(strtoupper($return_count)==TRUE){
		 return $ci->db->count_all_results();
		
	}else{
		if($limit != null){
			if(is_array($limit)){
				$ci->db->limit($limit[0],$limit[1]);
			}else{
				$ci->db->limit($limit);
			}
			
		}
		 $query = $ci->db->get();
		 if($type == 'object'){
		 	if($single_row){
		 		return $query->row();
		 	}else{
				return $query->result();
			}
		}elseif($type == 'array'){
			if($single_row){
		 		return $query->row_array();
		 	}else{
				return $query->result_array();
			}
		}	
	}
	
	
	}

}

/**
 * Retreive all the records of a table
 *
 * @param  string  $table, string $order, string $type
 * @return object , array
 */

// all function
if ( ! function_exists('all'))
{
	function all($table = null, $columns = '*', $order = null, $limit = null, $type = null) {
		$ci =& get_instance();

		if(!empty($table) || $table != null){
			$table = strtolower(trim($table));
		}else{
			echo "Table is not set! Please use a table name as first argument.";
			return false;
		}

		

		if(!empty($type) || $type != null){
			$type = strtolower(trim($type));
		}else{
			$type = 'object';
		}

		if($columns === '*'){
			$ci->db->select('*');
		}else{
			$ci->db->select($columns);
		}

		if(!empty($order) || $order != null){
			$order = strtolower(trim($order));
			$ci->db->order_by($order);
		}

		$ci->db->from($table);
		
		if($limit != null){
			$ci->db->limit($limit);
		}

		$query = $ci->db->get();

		if($type == 'object'){
			return $query->result();
		}

		if($type == 'array'){
			return $query->result_array();
		}
	}
}
// end all function


/**
 * Retreive a single record of an table
 *
 * @param  string  $table, string $column, string $where, string $type
 * @return row object , row array
 */

// find function
if ( ! function_exists('findtable'))
{
	function findtable($table = null, $columns = '*', $array = array(), $wild = null, $or_array = array(), $where = true, $order_by = null ,$type = null) {
		$ci =& get_instance();

		if(!empty($table) || $table != null){
			$table = strtolower(trim($table));
		}else{
			echo "Table is not set! Please use a table name as first argument.";
			return false;
		}

		if (!is_array($array)) {
			echo "An associative array required!";
			return false;
		}


		if(!empty($type) || $type != null){
			$type = strtolower(trim($type));
		}else{
			$type = 'object';
		}

		if($columns === '*'){
			$ci->db->select('*');
		}else{
			$ci->db->select($columns);
		}

		$ci->db->from($table);

		if ($where) {
			$ci->db->where($array);
			if(is_array($or_array) && !empty($or_array)){
				$ci->db->or_where($or_array);
			}		
		}else{
			$ci->db->like($array);
			if(is_array($or_array) && !empty($or_array)){
				$ci->db->or_like($or_array);
			}
		}

		if (!empty($order_by) && $order_by != null) {
			$ci->db->order_by($order_by);
		}

		if ($wild == null) {
			$ci->db->limit(1);
		}
		if (is_int($wild) && $wild != null){
			$ci->db->limit($wild);
		}

		$query = $ci->db->get();
		if($wild != '*' && !is_int($wild)){
			if($type == 'object'){
				return $query->row();
			}

			if($type == 'array'){
				return $query->row_array();
			}			
		}else{
			if($type == 'object'){
				return $query->result();
			}

			if($type == 'array'){
				return $query->result_array();
			}
		}


	}
}
// end find function

/**
 * Update data / record of the Table
 *
 * @param  string  $table, array $data, string $where, string $find_row
 * @return boolean ture on success, false on fail
 */

// update function
if ( ! function_exists('updateTable'))
{
	function updateTable($table = null, $data = null, $array = array(), $or_array = array()) {
		$ci =& get_instance();

		if(!empty($table) || $table != null){
			$table = strtolower(trim($table));
		}else{
			echo "Table is not set! Please use a table name as first argument.";
			return false;
		}

		if(!is_array($data)){
			echo "Please use an array as second argument!";
			return false;
		}else{
			if (!is_array($array)) {
				echo "An associative array required!";
				return false;
			}else{
				$ci->db->where($array);
				if(!empty($or_array)){
					$ci->db->or_where($or_array);
				}
				return $ci->db->update($table, $data);
			}
		}

	}
}
// end update function


/**
 * Update record if exist or Create new record if not exist
 *
 * @param  string  $table, array $data, string $where, string $find_row
 * @return boolean ture on success, false on fail
 */

// create_update function
if ( ! function_exists('create_update'))
{
	function create_update($table = null, $data = null, $array = array(), $or_array = array()) {
		$ci =& get_instance();

		if(!empty($table) || $table != null){
			$table = strtolower(trim($table));
		}else{
			echo "Table is not set! Please use a table name as first argument.";
			return false;
		}

		//check the row exist?
		$exist = findtable($table, '*', $array, 1, $or_array);
		$isExist = (empty($exist))? false : true;

		if ($isExist) {
			// update the row
			if(!is_array($data)){
				echo "Please use an array as second argument!";
				return false;
			}else{
				if (!is_array($array)) {
					echo "An associative array required!";
					return false;
				}else{
					$ci->db->where($array);
					if(!empty($or_array)){
						$ci->db->or_where($or_array);
					}
					$return = $ci->db->update($table, $data);
					return ($return)? 2 : false;
				}
			}			
		}else{
			//create row
			if(!is_array($data)){
				echo "Please use an array as second argument!";
				return false;
			}else{
				$return = $ci->db->insert($table, $data);
				return ($return)? 1 : false;
			}		
		}



	}
}
if ( ! function_exists('getSlug'))
{
	function getSlug($string , $separator = '-', $lowercase = FALSE) {
		return mb_strtolower(url_title($string,$separator));
	}
}

if ( ! function_exists('generateProjectSlug'))
{
	function generateProjectSlug($string,$inc=0) {
		$url=getSlug($string);
		if($url){
			$CI = get_instance();
			$CI->load->database();
			if($inc>0){
				$url=$url.'-'.$inc;
			}
			$query= $CI->db->get_where('proposals',array('proposal_url'=>$url));		
			if($query->num_rows()>0)
			{
				$inc++;
				$url=generateProjectSlug($string,$inc);
			}
		}
		return $url;
	}
}

// end create_update function


/**
 * Update record of the Table
 *
 * @param  string  $table, array $data, string $where, string $find_row
 * @return boolean ture on success, false on fail
 */

// delete function
if ( ! function_exists('delete'))
{
	function delete($table = null, $array= array(), $or_array = array()) {
		$ci =& get_instance();

		if(!empty($table) || $table != null){
			$table = strtolower(trim($table));
		}else{
			echo "Table is not set! Please use a table name as first argument.";
			return false;
		}

		if (!is_array($array)) {
			echo "An associative array required!";
			return false;
		}else{
			$ci->db->where($array);
			if(!empty($or_array)){
				$ci->db->or_where($or_array);
			}
			return $ci->db->delete($table);
		}
	}
}
// end delete function


/*-------------------------------------------------------------------*/

if ( ! function_exists('org_url_title'))
{
	function org_url_title($str, $separator = 'dash', $lowercase = FALSE)
	{

		if ($separator == 'dash')
		{
			$search		= '_';
			$replace	= '-';
		}
		else
		{
			$search		= '-';
			$replace	= '_';
		}

		$trans = array(
						'&\#\d+?;'				=> '',
						'&\S+?;'				=> '',
						'\s+'					=> $replace,
						$replace.'+'			=> $replace,
						$replace.'$'			=> $replace,
						'^'.$replace			=> $replace,
						'\.+$'					=> ''
					);

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = strtolower($str);
		}

//        $str = str_replace('&','and',$str);
        $str = preg_replace('/[^A-Za-z0-9\-]/', 'and', $str);
		return trim(stripslashes($str));
	}
}
/**
 * Slugify Helper
 *
 * Outputs the given string as a web safe filename
 */
if ( ! function_exists('slugify'))
{
	function slugify($string, $replace = array(), $delimiter = '-', $locale = 'en_US.UTF-8', $encoding = 'UTF-8') {
		if (!extension_loaded('iconv')) {
			throw new Exception('iconv module not loaded');
		}
		// Save the old locale and set the new locale
		$oldLocale = setlocale(LC_ALL, '0');
		setlocale(LC_ALL, $locale);
		$clean = iconv($encoding, 'ASCII//TRANSLIT', $string);
		if (!empty($replace)) {
			$clean = str_replace((array) $replace, ' ', $clean);
		}
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower($clean);
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		$clean = trim($clean, $delimiter);
		// Revert back to the old locale
		setlocale(LC_ALL, $oldLocale);
		return $clean;
	}
}

if ( ! function_exists('default_lang'))
{
	function default_lang()
	{
		if(is_installed('return')!='no')
		{
			if(defined('DEFAULT_LANG'))
			{
				return constant('DEFAULT_LANG');
			}
			else
			{	
				if(is_installed()=='yes')
				{
					$CI = get_instance();
					$CI->load->database();
					$query 		= $CI->db->get_where('settings',array('setting_key'=>'site_settings'));		
					if($query->num_rows()>0)
					{
						$row = $query->row();
						$settings = json_decode($row->setting_value);
						$default_lang = $settings->site_lang;			
					}
					else
						$default_lang = 'en';

					if(!defined('DEFAULT_LANG'))
						define('DEFAULT_LANG',$default_lang);
					return $default_lang;											
				}		
				else
					return 'en';
			}
		}
		else
			return 'en';
	}
}
if ( ! function_exists('get_current_page'))
{
	function get_current_page()
	{
		$uri =  uri_string();
		$segements = explode('/',$uri);
		$i=0;
		$url = '';
		foreach ($segements as $seg) {
			if($i>0)
			{
				$url .= $seg.'/';
			}
			$i++;
		}

		$CI = get_instance();
		$CI->load->model('show/show_model');
		$query = $CI->show_model->get_page_by_url(rtrim($url,"/"));
		if($query->num_rows()>0)
			return $query->row_array();
		else
			return array('error'=>'page_not_found');
	}
}
if ( ! function_exists('translate'))
{
	function translate($html='')
	{
		preg_match_all("^\[(.*?)\]^",$html,$fields, PREG_PATTERN_ORDER);
		foreach ($fields[1] as $key) 
		{
			$res = lang_key($key);
			$html = str_replace('['.$key.']',$res,$html);
		}

		return $html;

	}
}


if ( ! function_exists('lang_key'))
{
	function lang_key($key='')
	{
		if(defined('LANG_ARRAY'))
		{
			$lang = (array)json_decode(constant('LANG_ARRAY'));
			return (isset($lang[$key]))?$lang[$key]:$key;				
		}
		else
		{
			$CI = get_instance();
			$curr_lang 	= get_current_lang();

			$default_lang = default_lang();
				
			if($curr_lang=='')
				$file_name = $default_lang.'.yml';
			else
			{
				if(!@file_exists(FCPATH."org_config/locals/".$curr_lang.'.yml'))
				{
					$file_name = $default_lang.'.yml';
				}
				else
				{					
					$file_name = $curr_lang.'.yml';
				}
			}

			$CI->load->library('yaml');
			$lang =  $CI->yaml->parse_file('./org_config/locals/'.$file_name);

			if(count($lang)>0)
			{
				if(!defined('LANG_ARRAY'))
					define('LANG_ARRAY',json_encode($lang));

				return (isset($lang[$key]))?$lang[$key]:$key;				
			}
			else
				return $key;
			
		}
	}
}







if ( ! function_exists('configPagination'))
{
	function configPagination($url,$total_rows,$segment,$per_page=10)
	{


		$CI = get_instance();
		$CI->load->library('pagination');
		$config['base_url'] 		= site_url($url);
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $per_page;
		$config['uri_segment'] 		= $segment;
		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '</li>';
		$config['cur_tag_open'] 	= '<li class="active"><a href="#">';
		$config['cur_tag_close']	= '</a></li>';
		$config['num_links'] 		= 3;
		$config['next_tag_open'] 	= "<li>";
		$config['next_tag_close'] 	= "</li>";
		$config['prev_tag_open'] 	= "<li>";
		$config['prev_tag_close'] 	= "</li>";
		
		$config['first_link'] 	= FALSE;
		$config['last_link'] 	= FALSE;
		$CI->pagination->initialize($config);
		
		return $CI->pagination->create_links();
	}
}

/*if ( ! function_exists('get_current_lang'))
{
	function get_current_lang()
	{
		$CI 		= get_instance();
		$lang = ($CI->uri->segment(1)!='')?$CI->uri->segment(1):default_lang();
		if(!@file_exists(FCPATH."org_config/locals/".$lang.'.yml'))
			$lang = default_lang();
		return $lang;	
	}
}*/

function getSetlang(){
	$ci =& get_instance();
	if($ci->session->userdata('current_lang')){
		$lang = $ci->session->userdata('current_lang');
	}else{
		$lang = get_option_value('default_lang');
	}
	return $lang;
}
function is_favorite($member_id,$proposal_id){
	return getData(array(
				'select'=>'*',
				'table'=>'favorites as f',
				'where'=>array('f.member_id'=>$member_id,'f.proposal_id'=>$proposal_id),
				'return_count'=>true,
			)
		);	
}
function getLoginUser($access_user_id,$profile_connection_id){
	return getData(array(
				'select'=>'p_c.organization_id,p_c.member_id',
				'table'=>'profile_connection as p_c',
				'join'=>array(array('table'=>'organization as o','on'=>'p_c.organization_id=o.organization_id','position'=>'left')),
				'where'=>array('p_c.profile_connection_id'=>$profile_connection_id,'p_c.access_user_id'=>$access_user_id),
				'single_row'=>true,
			)
		);
}
function getUserName($member_id){
	$data=getData(array(
				'select'=>'a.access_username',
				'table'=>'profile_connection as p_c',
				'join'=>array(array('table'=>'access_panel as a','on'=>'p_c.access_user_id=a.access_user_id','position'=>'left')),
				'where'=>array('p_c.member_id'=>$member_id,'p_c.organization_id'=>NULL),
				'single_row'=>true,
			)
		);
	if($data){
		return $data->access_username;
	}else{
		return $member_id;
	}
}
function getnoofviews($proposal_id){
	$total=0;
	$res=getData(array(
		'select'=>'m.proposal_views',
		'table'=>'proposal_stat as m',
		'where'=>array('m.proposal_id'=>$proposal_id),
		'single_row'=>true,
	));	
	if($res){
		$total=$res->proposal_views;
	}
	return $total;
}
function getMemberLogo($member_id,$type='logo'){
	if($type=='logo'){
		$userimage=theme_url().IMAGE.'default/empty-image.png';
	}else{
		$userimage=theme_url().IMAGE.'default/empty-cover.png';
	}
	$logo=getData(array(
				'select'=>'m.logo,m.banner',
				'table'=>'member_logo as m',
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			)
		);	
	if($logo){
		if($type=='logo' && $logo->logo && file_exists(ABS_USERUPLOAD_PATH."member_logo/".$logo->logo)){
			$userimage=URL_USERUPLOAD.'member_logo/'.$logo->logo;
		}elseif($type!='logo' && $logo->cover && file_exists(ABS_USERUPLOAD_PATH."member_banner/".$logo->banner)){
			$userimage=URL_USERUPLOAD.'member_banner/'.$logo->banner;
		}
	}
	return $userimage;
}
function getMemberBalance($member_id,$is_round=TRUE){
	$balance=0;
	$data=getData(array(
				'select'=>'w.balance',
				'table'=>'wallet as w',
				'where'=>array('w.user_id'=>$member_id),
				'single_row'=>true,
			)
		);	
	if($data){
		if($is_round){
			$balance =round($data->balance);
		}else{
			$balance =$data->balance;
		}
	}
	return $balance;
}
function getWallet($wallet_id){
	$data=getData(array(
				'select'=>'w.wallet_id,w.balance,w.user_id,w.title',
				'table'=>'wallet as w',
				'where'=>array('w.wallet_id'=>$wallet_id),
				'single_row'=>true,
			)
		);	
	return $data;
}
function updateMemberReview($member_id){
	$CI =& get_instance();
	$CI->load->database();
	$positive=$CI->db->where('review_seller_id',$member_id)->where('buyer_rating >=',4)->from('buyer_reviews')->count_all_results();
	$negetive=$CI->db->where('review_seller_id',$member_id)->where('buyer_rating <',4)->from('buyer_reviews')->count_all_results();
	$total=$positive+$negetive;
	$positive_percent=($positive*100)/$total;
	updateTable('member',array('seller_rating'=>$positive_percent),array('member_id'=>$member_id));
}
function getMemberDetails($member_id,$filter=array()){
	$data=array();
	if(empty($filter) || array_key_exists('main',$filter) ){
		$data['member']=getData(array(
				'select'=>'m.member_id,m.member_name,m.is_email_verified,m.is_vacation,w.balance,w.wallet_id,w.title,m.seller_level,m.seller_rating,m.member_email,m.recent_delivery_date,m.member_register_date,m.bank_transfer_allowed',
				'table'=>'member as m',
				'join'=>array(array('table'=>'wallet as w','on'=>'m.member_id=w.user_id','position'=>'left')),
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			)
		);	
	}
	if(empty($filter) || array_key_exists('member_address',$filter) ){
		$data['member_address']=getData(array(
				'select'=>'m.member_nationality,m.member_country,m.member_city',
				'table'=>'member_address as m',
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			)
		);	
	}
	if(empty($filter) || array_key_exists('member_basic',$filter) ){
		$data['member_basic']=getData(array(
				'select'=>'m.member_heading,m.member_overview,m.prefer_language,m.member_gender,m.member_phone,m.member_mobile_code',
				'table'=>'member_basic as m',
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			)
		);	
	}
	if(empty($filter) || array_key_exists('member_logo',$filter) ){
		$data['member_logo']=getData(array(
				'select'=>'m.logo,m.banner',
				'table'=>'member_logo as m',
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			)
		);	
	}
	if(empty($filter) || array_key_exists('member_payment_settings',$filter) ){
		$data['member_payment_settings']=getData(array(
				'select'=>'m.*',
				'table'=>'member_payment_settings as m',
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			)
		);	
	}
	if(empty($filter) || array_key_exists('member_languages',$filter) ){
		$data['member_languages']=getData(array(
				'select'=>'m_l.language_id,l_n.language_title,m_l.language_level',
				'table'=>'member_languages as m_l',
				'join'=>array(
					array('table'=>'languages as l','on'=>'m_l.language_id=l.language_id','position'=>'left'),
					array('table'=>'languages_names as l_n','on'=>'l.language_id=l_n.language_id','position'=>'left'),
					
				),
				'where'=>array('m_l.member_id'=>$member_id,'l_n.lang'=>getSetlang()),
			)
		);	
	}
	if(empty($filter) || array_key_exists('member_skills',$filter) ){
		$data['member_skills']=getData(array(
				'select'=>'s.skill_id,s_n.skill_title,m_s.skill_level',
				'table'=>'member_skills as m_s',
				'join'=>array(
					array('table'=>'skills as s','on'=>'m_s.skill_id=s.skill_id','position'=>'left'),
					array('table'=>'skills_names as s_n','on'=>'s.skill_id=s_n.skill_id','position'=>'left'),
					
				),
				'where'=>array('m_s.member_id'=>$member_id,'s_n.lang'=>getSetlang()),
			)
		);	
	}
	return $data;
}
function getAllCountry($param=array()){
	$arr=array(
		'select'=>'c.country_code,c_n.country_name,c.country_code_short',
		'table'=>'country c',
		'join'=>array(array('table'=>'country_names as c_n','on'=>'c.country_code=c_n.country_code','position'=>'left')),
		'where'=>array('c_n.country_lang'=>getSetlang()),
		'order'=>array(array('c_n.country_name','asc'))
	);
	if($param){
		if(array_key_exists('country_code',$param)){
			$arr['where']['c.country_code']=$param['country_code'];
			$arr['single_row']=TRUE;
		}
	}else{
		$arr['where']['c.country_status']=1;
	}	
	return getData($arr);
		
}
function getAllNationality($param=array()){
	$arr=array(
		'select'=>'c.nationality_id,c_n.nationality_name',
		'table'=>'nationality c',
		'join'=>array(array('table'=>'nationality_names as c_n','on'=>'c.nationality_id=c_n.nationality_id','position'=>'left')),
		'where'=>array('c_n.nationality_lang'=>getSetlang()),
		'order'=>array(array('c_n.nationality_name','asc'))
	);
	if($param){
		if(array_key_exists('nationality_id',$param)){
			$arr['where']['c.nationality_id']=$param['nationality_id'];
			$arr['single_row']=TRUE;
		}
	}else{
		$arr['where']['c.nationality_status']=1;
	}	
	return getData($arr);
		
}
function getAllDeliveryTimes($time=''){
	$arr=array(
					'select'=>'d.delivery_id,d_n.delivery_title,d_n.delivery_proposal_title',
					'table'=>'delivery_times as d',
					'join'=>array(array('table'=>'delivery_times_names as d_n','on'=>"d.delivery_id=d_n.delivery_id and d_n.lang='".getSetlang()."'",'position'=>'left')),
					'where'=>array('d.status'=>1),
					'order'=>array(array('d.display_order','asc'),array('d_n.delivery_id','asc'))
				);
	if($time){
		$arr['where']['d.delivery_id']=$time;
		$arr['single_row']=TRUE;
	}
	return getData($arr);
		
}
function getAlllanguages(){
	return getData(array(
					'select'=>'l.language_id,l_n.language_title',
					'table'=>'languages as l',
					'join'=>array(array('table'=>'languages_names as l_n','on'=>"l.language_id=l_n.language_id and l_n.lang='".getSetlang()."'",'position'=>'left')),
					'where'=>array('l.status'=>1),
					'order'=>array(array('l_n.language_title','asc'))
				));
		
}
function getAllSkills(){
	return getData(array(
					'select'=>'s.skill_id,s_n.skill_title',
					'table'=>'skills as s',
					'join'=>array(array('table'=>'skills_names as s_n','on'=>"s.skill_id=s_n.skill_id and s_n.lang='".getSetlang()."'",'position'=>'left')),
					'where'=>array('s.status'=>1),
					'order'=>array(array('s_n.skill_title','asc'))
				));
		
}
function getAllCategory($filter=array()){
	$arr=array(
		'select'=>'c.category_id,c.category_key,c.category_image,c_n.name',
		'table'=>'category as c',
		'join'=>array(array('table'=>'category_names as c_n','on'=>"c.category_id=c_n.category_id and c_n.lang='".getSetlang()."'",'position'=>'left')),
		'where'=>array('c.status'=>1),
		'order'=>array(array('c.display_order','asc'),array('c_n.name','asc'))
	);
	if($filter){
		if(array_key_exists('limit',$filter)){
			$arr['limit']=$filter['limit'];
		}
		if(array_key_exists('show_details',$filter)){
			$arr['select']='c.category_id,c.category_key,c.category_image,c_n.name,c_n.info';
		}
		if(array_key_exists('category_key',$filter)){
			$arr['select']='c.category_id,c.category_key,c.category_image,c_n.name,c_n.info';
			$arr['where']['c.category_key']=$filter['category_key'];
			$arr['single_row']=TRUE;
		}elseif(array_key_exists('category_id',$filter)){
			$arr['select']='c.category_id,c.category_key,c.category_image,c_n.name,c_n.info';
			$arr['where']['c.category_id']=$filter['category_id'];
			$arr['single_row']=TRUE;
		}
	}
	return getData($arr);
		
}
function getAllSubCategory($category_id='',$filter=array()){
	$r=array(
		'select'=>'sc.category_subchild_id,sc.category_subchild_key,sc_n.name',
		'table'=>'category_subchild sc',
		'join'=>array(array('table'=>'category_subchild_names as sc_n','on'=>"sc.category_subchild_id=sc_n.category_subchild_id and sc_n.lang='".getSetlang()."'",'position'=>'left')),
		'where'=>array('sc.category_subchild_status'=>'1'),
		'order'=>array(array('sc.display_order','asc'),array('sc_n.name','asc')),
		);
	if($category_id){
		$r['where']['sc.category_id']=$category_id;
	}
	if($filter){
		if(array_key_exists('show_details',$filter)){
			$arr['select']='sc.category_subchild_id,sc.category_subchild_key,sc_n.name,sc_n.description';
		}
		if(array_key_exists('category_subchild_key',$filter)){
			$r['select']='sc.category_subchild_id,sc.category_subchild_key,sc_n.name,sc_n.description';
			$r['where']['sc.category_subchild_key']=$filter['category_subchild_key'];
			$r['single_row']=TRUE;
		}
		
	}
	return getData($r);
}
function getProjectDetails($project_id,$show=array()){
	$data=array();
	if(empty($show) || in_array('project',$show)){
	$arr=array(
				'select'=>'p.project_id,p.project_title,p.project_member_required,p.project_posted_date,p.project_expired_date,p.project_status',
				'table'=>'project as p',
				'where'=>array('p.project_id'=>$project_id),
				'single_row'=>true,
			);
	$project=getData($arr);	
	$data['project']=$project;
	}
	if(empty($show) || in_array('project_additional',$show)){
	$arr=array(
				'select'=>'p_a.project_description,p_a.project_is_cover_required',
				'table'=>'project_additional as p_a',
				'where'=>array('p_a.project_id'=>$project_id),
				'single_row'=>true,
			);
	$project_additional=getData($arr);
	$data['project_additional']=$project_additional;
	}
	if(empty($show) || in_array('project_category',$show)){
	$arr=array(
				'select'=>'c.category_id,c.category_key,c_n.category_name,s_c.category_subchild_id,s_c.category_subchild_key,sc_n.category_subchild_name',
				'table'=>'project_category as p_c',
				'join'=>array(
					array('table'=>'category as c','on'=>'p_c.category_id=c.category_id','position'=>'left'),
					array('table'=>'category_names as c_n','on'=>'c.category_id=c_n.category_id','position'=>'left'),
					array('table'=>'category_subchild as s_c','on'=>'p_c.category_subchild_id=s_c.category_subchild_id','position'=>'left'),
					array('table'=>'category_subchild_names as sc_n','on'=>'s_c.category_subchild_id=sc_n.category_subchild_id','position'=>'left')
				),
				'where'=>array('p_c.project_id'=>$project_id,'c_n.category_lang'=>getSetlang(),'sc_n.category_subchild_lang'=>getSetlang()),
				'single_row'=>true,
			);
	$project_category=getData($arr);
	$data['project_category']=$project_category;
	}
	if(empty($show) || in_array('project_files',$show)){
	$arr=array(
				'select'=>'f.file_id,f.original_name,f.server_name,f.file_ext',
				'table'=>'project_files as p_f',
				'join'=>array(
					array('table'=>'files as f','on'=>'p_f.file_id=f.file_id','position'=>'left'),
				),
				'where'=>array('p_f.project_id'=>$project_id),
			);
	$project_files=getData($arr);
	$data['project_files']=$project_files;
	}
	if(empty($show) || in_array('project_owner',$show)){
	$arr=array(
				'select'=>'p_o.organization_id,p_o.member_id,o.organization_name,m.member_fname,m.member_lname',
				'table'=>'project_owner as p_o',
				'join'=>array(
					array('table'=>'organization as o','on'=>'p_o.organization_id=o.organization_id','position'=>'left'),
					array('table'=>'member as m','on'=>'p_o.member_id=m.member_id','position'=>'left'),
				),
				'where'=>array('p_o.project_id'=>$project_id),
				'single_row'=>true,
			);
	$project_owner=getData($arr);
	$data['project_owner']=$project_owner;
	}
	if(empty($show) || in_array('project_question',$show)){
	$arr=array(
				'select'=>'q.question_id,q.question_title',
				'table'=>'project_question as p_q',
				'join'=>array(
					array('table'=>'question as q','on'=>'p_q.question_id=q.question_id','position'=>'left'),
				),
				'where'=>array('p_q.project_id'=>$project_id,'p_q.project_question_status'=>1),
			);
	$project_question=getData($arr);
	$data['project_question']=$project_question;
	}
	if(empty($show) || in_array('project_settings',$show)){
	$arr=array(
				'select'=>'p_s.is_visible_anyone,p_s.is_visible_private,p_s.is_visible_invite,p_s.is_hourly,p_s.is_fixed,p_s.budget,e_l.experience_level_key,e_l_n.experience_level_name,p_s.hourly_duration,p_s.hourly_time_required,p_s.project_type_code',
				'table'=>'project_settings as p_s',
				'join'=>array(
					array('table'=>'experience_level as e_l','on'=>'p_s.experience_level=e_l.experience_level_id','position'=>'left'),
					array('table'=>'experience_level_name as e_l_n','on'=>'e_l.experience_level_id=e_l_n.experience_level_id','position'=>'left'),
				),
				'where'=>array('p_s.project_id'=>$project_id,'e_l_n.experience_level_lang'=>getSetlang()),
				'single_row'=>true,
			);
	$project_settings=getData($arr);
	$data['project_settings']=$project_settings;
	}
	if(empty($show) || in_array('project_skills',$show)){
	$arr=array(
				'select'=>'s.skill_id,s.skill_key,s_n.skill_name',
				'table'=>'project_skills as p_s',
				'join'=>array(
					array('table'=>'skills as s','on'=>'p_s.skill_id=s.skill_id','position'=>'left'),
					array('table'=>'skill_names as s_n','on'=>'s.skill_id=s_n.skill_id','position'=>'left')
				),
				'where'=>array('p_s.project_id'=>$project_id,'s_n.skill_lang'=>getSetlang(),'p_s.project_skill_status'=>1),
			);
	$project_skills=getData($arr);	
	$data['project_skills']=$project_skills;
	}
	
	/*$data=array(
		'project'=>$project,
		'project_additional'=>$project_additional,
		'project_category'=>$project_category,
		'project_files'=>$project_files,
		'project_owner'=>$project_owner,
		'project_question'=>$project_question,
		'project_settings'=>$project_settings,
		'project_skills'=>$project_skills,
	);*/
	return $data;
}
function getAllProjectType($select=''){
	$data=array(
	'OneTime'=>array('name'=>'One time project'),
	'Ongoing'=>array('name'=>'Ongoing project'),
	'NotSure'=>array('name'=>'Not sure'),
	);
	if($select){
		$data=$data[$select];
	}
	return $data;
}
function getAllProjectStatus($status=''){
	$data=array(
		PROJECT_DRAFT=>array('name'=>'Dtaft','class'=>''),
		PROJECT_OPEN=>array('name'=>'Open','class'=>''),
		PROJECT_HIRED=>array('name'=>'Hired','class'=>''),
		PROJECT_CLOSED=>array('name'=>'Closed','class'=>''),
		PROJECT_DELETED=>array('name'=>'Deleted','class'=>''),
	);
	if($status){
		$data=$data[$status];
	}
	return $data;
}
function getBids($project_id='',$param=array(),$count=FALSE){
	$r=array(
		'select'=>'b.bid_id,',
		'table'=>'project_bids b',
		'where'=>array('b.project_id'=>$project_id),
		);
	if($param){
		if(array_key_exists('is_hired',$param)){
			$r['where']['b.is_hired']=1;
			$r['where']['b.is_archive']=NULL;
		}
		if(array_key_exists('is_archive',$param)){
			$r['where']['b.is_archive']=1;
		}
		if(array_key_exists('is_shortlisted',$param)){
			$r['where']['b.is_shortlisted']=1;
		}
		if(array_key_exists('is_interview',$param)){
			$r['where']['b.is_interview']=1;
		}	
		if(array_key_exists('is_hired',$param)){
			$r['where']['b.is_hired']=1;
		}	
		if(array_key_exists('only_active',$param) || array_key_exists('is_proposal',$param)){
			$r['where']['b.is_archive']=NULL;
		}
	}
	if($count){
		$r['return_count']=TRUE;
	}
	return getData($r);
}
function getBidsListDetails($project_id='',$param=array(),$count=FALSE){
	$r=array(
		'select'=>'b.bid_id,b.bid_amount,b.bid_by_project,b.bid_duration,b.bid_details,b.bid_date,b.is_archive,b.is_shortlisted,b.is_interview,b.is_hired,b.member_id,b.organization_id,m.member_fname,m.member_lname,m.is_email_verified,m_b.member_heading,m_b.member_hourly_rate,m_a.member_country,m_l.logo',
		'table'=>'project_bids b',
		'join'=>array(
			array('table'=>'member m','on'=>'b.member_id=m.member_id','position'=>'left'),
			array('table'=>'member_address m_a','on'=>'b.member_id=m_a.member_id','position'=>'left'),
			array('table'=>'member_basic m_b','on'=>'b.member_id=m_b.member_id','position'=>'left'),
			array('table'=>'member_logo m_l','on'=>'b.member_id=m_l.member_id','position'=>'left'),

		),
		'where'=>array('b.project_id'=>$project_id),
		);
	if($param){
		if(array_key_exists('is_hired',$param)){
			$r['where']['b.is_hired']=1;
			$r['where']['b.is_archive']=NULL;
		}
		if(array_key_exists('is_archive',$param)){
			$r['where']['b.is_archive']=1;
		}
		if(array_key_exists('is_shortlisted',$param)){
			$r['where']['b.is_shortlisted']=1;
		}
		if(array_key_exists('is_interview',$param)){
			$r['where']['b.is_interview']=1;
		}	
		if(array_key_exists('is_hired',$param)){
			$r['where']['b.is_hired']=1;
		}
		if(array_key_exists('only_active',$param) || array_key_exists('is_proposal',$param)){
			$r['where']['b.is_archive']=NULL;
		}
	}
	if($count){
		$r['return_count']=TRUE;
	}
	return getData($r);
}
function getRequestDetails($request_id,$show=array()){
	$data=array();
	if(empty($show) || in_array('request',$show)){
	$arr=array(
				'select'=>'r.request_id,r.request_title,r.request_description,r.delivery_time,r.request_budget,r.request_date,r.request_status',
				'table'=>'buyer_requests as r',
				'where'=>array('r.request_id'=>$request_id),
				'single_row'=>true,
			);
	$proposal=getData($arr);	
	$data['request']=$proposal;
	}
	if(empty($show) || in_array('request_category',$show)){
	$arr=array(
				'select'=>'c.category_id,c.category_key,c_n.name as category_name,s_c.category_subchild_id,s_c.category_subchild_key,sc_n.name as category_subchild_name',
				'table'=>'request_category as p_c',
				'join'=>array(
					array('table'=>'category as c','on'=>'p_c.category_id=c.category_id','position'=>'left'),
					array('table'=>'category_names as c_n','on'=>'c.category_id=c_n.category_id','position'=>'left'),
					array('table'=>'category_subchild as s_c','on'=>'p_c.category_subchild_id=s_c.category_subchild_id','position'=>'left'),
					array('table'=>'category_subchild_names as sc_n','on'=>'s_c.category_subchild_id=sc_n.category_subchild_id','position'=>'left')
				),
				'where'=>array('p_c.request_id'=>$request_id,'c_n.lang'=>getSetlang(),'sc_n.lang'=>getSetlang()),
				'single_row'=>true,
			);
	$proposal_category=getData($arr);
	$data['request_category']=$proposal_category;
	}
	if(empty($show) || in_array('request_files',$show)){
	$arr=array(
				'select'=>'f.file_id,f.original_name,f.server_name,f.file_ext',
				'table'=>'request_files as p_f',
				'join'=>array(
					array('table'=>'files as f','on'=>'p_f.file_id=f.file_id','position'=>'left'),
				),
				'where'=>array('p_f.request_id'=>$request_id),
			);
	$request_files=getData($arr);
	$data['request_files']=$request_files;
	}
	return $data;
}
function is_online($memberId){
	$ci = &get_instance();
    $ci->load->database();
	return $ci->db->where('user_id',$memberId)->from('online_user')->count_all_results();
}
function getProposalRating($proposal_id,$type=array()){
	if($type && in_array('stat',$type)){
		$arr=array(
			'select'=>'AVG(b.buyer_rating) as avg_review,count(b.review_id) as total_review',
			'table'=>'buyer_reviews b',
			'where'=>array('b.proposal_id'=>$proposal_id),
			'group'=>'b.proposal_id',
			'single_row'=>TRUE,
		);
	$data=getData($arr);
	if(!$data){
		$data=(object)array('avg_review'=>0,'total_review'=>0);
	}
	}else{
	$arr=array(
			'select'=>'b.buyer_rating,b.buyer_review,b.order_id,b.review_date,m.member_name as buyer_name,s.seller_rating,s.seller_review,s.review_date,s.review_id as seller_review_id',
			'table'=>'buyer_reviews b',
			'join'=>array(array('table'=>'seller_reviews as s','on'=>'b.order_id=s.order_id','position'=>'left'),array('table'=>'member as m','on'=>'b.review_buyer_id=m.member_id','position'=>'left')),
			'where'=>array('b.proposal_id'=>$proposal_id),
			'order'=>array(array('b.review_date','asc')),
	);
	$data=getData($arr);
	}
	return $data;
}
function getLevelName($id=''){
	$data=array(
	'1'=>array('name'=>__('NewSeller','New Freelancer')),
	'2'=>array('name'=>__('LevelOne','Level One')),
	'3'=>array('name'=>__('LevelTwo','Level Two')),
	'4'=>array('name'=>__('TopRated','Top Rated')),
	);
	if($id!=''){
		if($data[$id]['name']){
			$dataName=$data[$id]['name'];
		}else{
			$dataName=$data[1]['name'];
		}
		
	}else{
		$dataName=$data;
	}

	return $dataName;
}
function getSkillsLevelName($id=''){
	$data=array(
	'1'=>array('name'=>__('Beginner','Beginner')),
	'2'=>array('name'=>__('Intermediate','Intermediate')),
	'3'=>array('name'=>__('Expert','Expert')),
	);
	if($id){
		$dataName=$data[$id]['name'];
	}else{
		$dataName=$data;
	}
	return $dataName;
}
function getLanguageLevelName($id=''){
	$data=array(
	'1'=>array('name'=>__('Basic','Basic')),
	'2'=>array('name'=>__('Fluent','Fluent')),
	'3'=>array('name'=>__('Conversational','Conversational')),
	'4'=>array('name'=>__('Native_or_Bilingual','Native or Bilingual')),
	);
	if($id){
		$dataName=$data[$id]['name'];
	}else{
		$dataName=$data;
	}
	return $dataName;
}
function getProposalDetails($proposal_id,$show=array()){
	$data=array();
	if(empty($show) || in_array('proposal',$show)){
	$arr=array(
				'select'=>'p.proposal_id,p.proposal_title,p.proposal_url,p.delivery_time,p.proposal_price,p.display_price,p.proposal_date,p.proposal_status,p.proposal_image,p.proposal_seller_id',
				'table'=>'proposals as p',
				'where'=>array('p.proposal_id'=>$proposal_id),
				'single_row'=>true,
			);
	$proposal=getData($arr);	
	$data['proposal']=$proposal;
	}
	if(empty($show) || in_array('project_category',$show)){
	$arr=array(
				'select'=>'c.category_id,c.category_key,c_n.name as category_name,s_c.category_subchild_id,s_c.category_subchild_key,sc_n.name as category_subchild_name,c.category_module',
				'table'=>'proposal_category as p_c',
				'join'=>array(
					array('table'=>'category as c','on'=>'p_c.category_id=c.category_id','position'=>'left'),
					array('table'=>'category_names as c_n','on'=>'c.category_id=c_n.category_id','position'=>'left'),
					array('table'=>'category_subchild as s_c','on'=>'p_c.category_subchild_id=s_c.category_subchild_id','position'=>'left'),
					array('table'=>'category_subchild_names as sc_n','on'=>'s_c.category_subchild_id=sc_n.category_subchild_id','position'=>'left')
				),
				'where'=>array('p_c.proposal_id'=>$proposal_id,'c_n.lang'=>getSetlang(),'sc_n.lang'=>getSetlang()),
				'single_row'=>true,
			);
	$proposal_category=getData($arr);
	$data['proposal_category']=$proposal_category;
	}
	if(empty($show) || in_array('proposal_additional',$show)){
	$arr=array(
				'select'=>'p_a.proposal_video,p_a.proposal_description,p_a.buyer_instruction',
				'table'=>'proposal_additional as p_a',
				'where'=>array('p_a.proposal_id'=>$proposal_id),
				'single_row'=>true,
			);
	$proposal_additional=getData($arr);
	$data['proposal_additional']=$proposal_additional;
	}
	if(empty($show) || in_array('proposal_tags',$show)){
	$arr=array(
				'select'=>'p_t.tag_name',
				'table'=>'proposal_tags as p_t',
				'where'=>array('p_t.proposal_id'=>$proposal_id),
			);
	$proposal_tags=getData($arr);
	$data['proposal_tags']=$proposal_tags;
	}
	if(empty($show) || in_array('proposal_settings',$show)){
	$arr=array(
				'select'=>'p_s.proposal_referral_code,p_s.proposal_enable_referrals,p_s.proposal_referral_money,p_s.proposal_featured',
				'table'=>'proposal_settings as p_s',
				'where'=>array('p_s.proposal_id'=>$proposal_id),
				'single_row'=>true,
			);
	$proposal_settings=getData($arr);
	$data['proposal_settings']=$proposal_settings;
	}
	if(empty($show) || in_array('proposal_files',$show)){
	$arr=array(
				'select'=>'f.file_id,f.original_name,f.server_name,f.file_ext',
				'table'=>'proposal_files as p_f',
				'join'=>array(
					array('table'=>'files as f','on'=>'p_f.file_id=f.file_id','position'=>'left'),
				),
				'where'=>array('p_f.proposal_id'=>$proposal_id),
			);
	$proposal_files=getData($arr);
	$data['proposal_files']=$proposal_files;
	}
	if(empty($show) || in_array('proposal_packages',$show)){
	$arr=array(
				'select'=>'p.package_id,p.package_name,p.price,p.description,p.revisions,p.delivery_time',
				'table'=>'proposal_packages as p',
				'where'=>array('p.proposal_id'=>$proposal_id),
			);
	$proposal_packages=getData($arr);
	$data['proposal_packages']=$proposal_packages;
	}
	if(empty($show) || in_array('proposal_extras',$show)){
	$arr=array(
				'select'=>'p.id,p.name,p.price',
				'table'=>'proposal_extras as p',
				'where'=>array('p.proposal_id'=>$proposal_id),
			);
	$proposal_extras=getData($arr);
	$data['proposal_extras']=$proposal_extras;
	}
	if(empty($show) || in_array('module_attributes',$show)){
		$module_attributes=array();
		if($proposal_category->category_module){
			$arr=array(
						'select'=>'p.package_id,p.attribute_key,p.attribute_value',
						'table'=>'proposal_package_module_attributes as p',
						'where'=>array('p.proposal_id'=>$proposal_id),
						'order_by'=>array(array('p.package_id','asc'))
					);
			$module_attributesData=getData($arr);
			if($module_attributesData){
				foreach($module_attributesData as $k=>$attrdata){
					$module_attributes[$attrdata->attribute_key][]=$attrdata->attribute_value;
				}
			}
		}
		$data['module_attributes']=$module_attributes;
		}
	return $data;
}
function is_bidder($project_id='',$bidder_id=''){
	
}
function is_owner($project_id='',$poster_id=''){
	
}
function getAllProjectDuration($select=''){
	$data=array(
	'Less1month'=>array('name'=>'Less than 1 month'),
	'1To2month'=>array('name'=>'1 to 2 month'),
	'More3month'=>array('name'=>'More than 3 month'),
	);
	if($select){
		$data=$data[$select];
	}
	return $data;
}
function getAllProjectDurationTime($select=''){
	$data=array(
	'FullTime'=>array('name'=>'More then 30hr/week'),
	'PartTime'=>array('name'=>'Less then 30hr/week'),
	'NotSure'=>array('name'=>'Not sure'),
	);
	if($select){
		$data=$data[$select];
	}
	return $data;
}
function getAllExperienceLevel(){
	return getData(array(
				'select'=>'e_l.experience_level_id,e_l.experience_level_key,e_l_n.experience_level_name',
				'table'=>'experience_level e_l',
				'join'=>array(array('table'=>'experience_level_name as e_l_n','on'=>'e_l.experience_level_id=e_l_n.experience_level_id','position'=>'left')),
				'where'=>array('e_l.experience_level_status'=>'1','e_l_n.experience_level_lang'=>getSetlang()),
				'order'=>array(array('e_l.experience_level_id','asc'))
		));
}
function get_time_ago( $time )
{
    $time_difference = time() - strtotime($time);
    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );
    foreach( $condition as $secs => $str )
    {
        $d = $time_difference / $secs;
        if( $d >= 1 )
        {
            $t = round( $d );
            return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }
}
if ( ! function_exists('get_settings'))
{
	function get_settings($option='',$key='',$default='Yes')
	{
		$settings = get_option($option);
		if(is_array($settings)==FALSE)
		{
			$settings = (array)json_decode($settings->setting_value);
			$val = (isset($settings[$key]))?$settings[$key]:$default;
		}
		else
			$val = $default;

		return $val;
	}
}


if ( ! function_exists('get_option'))
{
	function get_option($key='')
	{
		$defined = 0;
		if(defined('OPTIONS_ARRAY'))
		{						
			$options = (array)json_decode(constant('OPTIONS_ARRAY'));
			if(isset($options[$key]))
			{
				$defined = 1;
				return $options[$key];
			}
		}


		if($defined==0)
		{
			$CI = get_instance();
			$CI->load->database();
			$query = $CI->db->select('setting_key,setting_value')->get_where('settings',array('setting_key'=>$key));		
			if($query->num_rows()>0)
				$option = $query->row();
			else
				$option = array('error'=>'Key not found');

			$options[$key] = $option;
			if(!defined('OPTIONS_ARRAY'))
				define('OPTIONS_ARRAY',json_encode($options));

			return $option;
		}
	}
}



if ( ! function_exists('update_option'))
{
	function update_option($key='',$values=array())
	{
		$CI = get_instance();
		$data['values'] = json_encode($values);
		$query = $CI->db->update('settings',$data,array('setting_key'=>$key));		
	}
}

if ( ! function_exists('add_option'))
{
	function add_option($key='',$values='')
	{
		$CI = get_instance();
		$data['setting_value'] = $values;
		$result = get_option($key);
		if(is_array($result) && isset($result['error']))
		{
			$data['setting_key'] = $key;
			$query = $CI->db->insert('settings',$data);					
		}
		else
		{
			$query = $CI->db->update('settings',$data,array('setting_key'=>$key));		
		}
	}
}
if ( ! function_exists('truncate'))
{
	function truncate($s, $l, $e = '...', $isHTML = false){
		$i = 0;
		$tags = array();
		if($isHTML){
			preg_match_all('/<[^>]+>([^<]*)/', $s, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
			foreach($m as $o){
				if($o[0][1] - $i >= $l)
					break;
				$t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
				if($t[0] != '/')
					$tags[] = $t;
				elseif(end($tags) == substr($t, 1))
					array_pop($tags);
				$i += $o[1][1] - $o[0][1];
			}
		}
		return substr($s, 0, $l = min(strlen($s),  $l + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '') . (strlen($s) > $l ? $e : '');
	}
}

if ( ! function_exists('encode_html'))
{
	function encode_html($html){
		$html = str_replace('<','&lt;', $html);
		$html = str_replace('>','&gt;', $html);
		return $html;
	}
}
if ( ! function_exists('positionupdate'))
{
 function positionupdate($sectionid, $section_name, $table, $column, $direction) {
		$CI = get_instance();
        $queryc = $CI->db->select($column)->get_where($table, array($section_name => $sectionid))->row();
        $curpos = $queryc->$column;
        
        if ($direction > 0) {
            // To be moved up
            if($curpos!='0'){
				
			
            $queryup = $CI->db->select($column)->select($section_name)
                    ->order_by($column, 'DESC')
                    ->limit('1')
                    ->get_where($table, array($column . ' < ' => $curpos))
                    ->row();
            $newpos = $queryup->$column;
            $overritePos = $queryup->$section_name;
            }else{
			$newpos=0;
			$overritePos =0;	
			}
        } else {
            // To be moved down			
            $queryup = $CI->db->select($column)->select($section_name)
                    ->order_by($column, 'ASC')
                    ->limit('1')
                    ->get_where($table, array($column . ' > ' => $curpos))
                    ->row();
            $newpos = $queryup->$column;
            $overritePos = $queryup->$section_name;
        }
        if ($newpos) {
            $CI->db->where($column, $newpos);
            $suc = $CI->db->update($table, array($column => $curpos));
            if ($suc) {
                $CI->db->where($section_name, $sectionid);
                $suc2 = $CI->db->update($table, array($column => $newpos));
                if ($suc2) {
                    $msg['status'] = 'OK';
                    $msg['currentPosition'] = $curpos;
                    $msg['newPosition'] = $newpos;
                    $msg['overritePos'] = $overritePos;
                } else {
                    $msg['status'] = 'FAIL';
                }
            } else {
                $msg['status'] = 'FAIL';
            }
        } else {
            $msg['status'] = 'FAIL';
        }
        return $msg;
    }
}
if ( ! function_exists('generate_order_number')){
function generate_order_number() {
	$CI = get_instance();
	$getinv=$CI->db->select('*')->from('setting')->get()->row();
	if($getinv){
			$INV=$getinv->orderNumber+1;	
			$updated_data=array('orderNumber'=>$INV);
	}
	$CI->db->update('setting', $updated_data);
	//$int = rand(0,25);
	//$a_z = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	//$rand_letter = $a_z[$int];
	$rand_letter="FP";
    return  $rand_letter."-".makenumber($INV);
    }
}
if ( ! function_exists('makenumber')){
function makenumber($INV){
	if(strlen($INV)<3){
		$num =str_pad($INV,3,'0',STR_PAD_LEFT);
	}else{
		$num =$INV;
	}
		
	return $num;
}
}

if ( ! function_exists('getConnectedProfile'))
{
	function getConnectedProfile($LID) {
		$resData=array();
		$ci =& get_instance();
		$allData=getData(array(
				'select'=>'p_c.profile_connection_id,p_c.organization_id,p_c.member_id,p_c.profile_name,o_l.logo as organization_logo,m_l.logo as member_logo',
				'table'=>'profile_connection p_c',
				'join'=>array(
					array('table'=>'organization_logo as o_l','on'=>'p_c.organization_id=o_l.organization_id','position'=>'left'),
					array('table'=>'member_logo as m_l','on'=>'p_c.member_id=m_l.member_id','position'=>'left')
				),
				'where'=>array('p_c.access_user_id'=>$LID,'p_c.connection_status'=>1,'(o_l.status=1 or o_l.status is NULL)'=>NULL,'(m_l.status=1 or m_l.status is NULL)'=>NULL),
				'order'=>array(array('p_c.profile_name','asc')),
				)
		);
		if($allData){
			foreach($allData as $m){
				$m->profile_type=($m->organization_id >0  ? "Client":"Freelancer");
				$resData[]=$m;
			}
		}
		return $resData;
	}
}
function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function priceFormat($number) {
      if ($number) {
          $text = number_format($number, 2, '.', ' ');
      }else{
	  	$text="0.00";
	  }
      return $text;
}
function dateFormat($date,$format='d m,Y') {
	if($format){
		if($format=='F d, Y'){
			$ci =& get_instance();
			$ci->config->set_item('language', getSetlang());
			$ci->load->library('calendar');
			$df='';
			$df.=$ci->calendar->get_month_name(date('m',strtotime($date))).' ';
			$df.=date('d, Y',strtotime($date));
		}else{
			$df=date($format,strtotime($date));
		}
		
	}else{
		$df=date('d m Y',strtotime($date));
	}
    return $df;
}
function getRoleUserEmployemnt($role_id='') {
    $role=array(
    '1'=>'Intern',
    '2'=>'Individual Contributor',
    '3'=>'Lead',
    '4'=>'Manager',
    '5'=>'Executive',
    '6'=>'Owner',
    );
    if($role_id && $role[$role_id]){
		return $role[$role_id];
	}elseif(!$role_id){
		return $role;
	}
}
function getMonth($month_id='') {
    $month=array(
    '1'=>'January',
    '2'=>'February',
    '3'=>'March',
    '4'=>'April',
    '5'=>'May',
    '6'=>'June',
    '7'=>'July',
    '8'=>'August',
    '9'=>'September',
    '10'=>'October',
    '11'=>'November',
    '12'=>'December',
    );
    if($month_id && $month[$month_id]){
		return $month[$month_id];
	}elseif(!$month_id){
		return $month;
	}
}
function get_option_value($key) {
    $res=get_option($key);
    return $res->setting_value;
}
function CurrencySymbol(){
    return get_option_value('site_currency');
}
function CurrencyCode() {
    return get_option_value('site_currency_code');
}
function PaymentMethod($id) {
	$allmethod=array('0'=>'COD','1'=>'Card','2'=>'Paypal');
      if ($id) {
          $text =$allmethod[$id];
      }else{
	  	$text=$allmethod[0];
	  }
      return $text;
}
function PaymentStatus($id) {
	$allmethod=array('0'=>'Not paid','1'=>'Other','2'=>'Payment failed','3'=>'Paid');
      if ($id) {
          $text =$allmethod[$id];
      }else{
	  	$text=$allmethod[0];
	  }
      return $text;
}
function OrderStatus($id) {
	$allstatus=array('0'=>'Pending','1'=>'Picking','2'=>'Almost picking','3'=>'Waiting at pickup','4'=>'Delivering','5'=>'Almost delivering','6'=>'Waiting at dropoff','7'=>'Delivered','8'=>'Cancelled');
      if ($id) {
          $text =$allstatus[$id];
      }else{
	  	$text=$allstatus[0];
	  }
      return $text;
}
function OrderStatusapi($status) {
	$allstatus=array('pending'=>'0','picking'=>'1','almost_picking'=>'2','waiting_at_pickup'=>'3','delivering'=>'4','almost_delivering'=>'5','waiting_at_dropoff'=>'6','delivered'=>'7','cancelled'=>'8');
      if ($status) {
          $id =$allstatus[$status];
      }else{
	  	$id=0;
	  }
      return $id;
}
function orderNotification($type='SHOW') {
	$CI = get_instance();
	$CI->load->helper('file');
	if($type=='NEW'){
		$filename=ABS_USERUPLOAD_PATH."order/notification.echo";
		$count_notic=1;
		if(!file_exists($filename)){
			if ( !write_file($filename, $count_notic, 'w')){
				 error_log('Unable to write the file') ;
			}
		}else{
			$count_notic=file_get_contents($filename);
			$count_notic++;
			if ( !write_file($filename, $count_notic, 'w')){
				 error_log('Unable to write the file') ;
			}
		}
		$neworder=$count_notic;
	}elseif($type=='SHOW'){
		$filename=ABS_USERUPLOAD_PATH."order/notification.echo";
		if(!file_exists($filename)){
			$neworder=0;
		}else{
			$neworder=file_get_contents($filename);
		}
		if($neworder>0){
			if ( !write_file($filename, 0, 'w')){
				 error_log('Unable to write the file') ;
			}
		}
	}
    return $neworder;
}
function SendMailCron() {
	$CI = get_instance();
	$send='';
	$mailcontentAll = getData(array(
 		'select'=>'to_email,email_subject,email_content,request_date,email_unique_id',
 		'table'=>'pending_emails',
 		'order'=>array(array('process_order','ASC'),array('request_date','ASC')),
 		'limit'=>'5',
 		));
 	if($mailcontentAll){
 		$CI->load->library("PhpMailerLib");
   		$mail = $CI->phpmailerlib->load();
    	$sendtype=get_option_value('protocol');
    	$user=get_option_value('smtp_user');
    	$mail->SMTPDebug = 0;
    	$mail->isSMTP();

    	if($sendtype!='smtp'){
			$mail->Host = 'localhost'; 
			$mail->SMTPAuth =FALSE;
			$mail->SMTPSecure=FALSE;
			$mail->Port=25; 
		}else{
			$mail->Host = get_option_value('smtp_host'); 
			$mail->SMTPAuth =true; 
			$mail->Username = $user;
    		$mail->Password = get_option_value('smtp_pass');                           // SMTP password
	   	 	$mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
	    	$mail->Port = get_option_value('smtp_port');                                    // TCP port to connect to
		}
    	$mail->setFrom($user);
    	$mail->addReplyTo($user);
    	$mail->isHTML(true);                                  // Set email format to HTML
	foreach($mailcontentAll as $k=>$mailcontent){
		$to=$mailcontent->to_email;
		$subject=$mailcontent->email_subject;
		$contents=$mailcontent->email_content;
		
	    $mail->addAddress($to);
	    $mail->Subject = $subject;
	    $mail->Body    = $contents;
	    $send=$mail->send();
    	$mail->ClearAllRecipients(); 
		$mail->ClearAttachments();   //Remove all attachements
		if($send){
			delete('pending_emails',array('to_email'=>$to,'email_unique_id'=>$mailcontent->email_unique_id));	
		}
		
   

	}
	}
    ob_clean();
return $send;
}
function SendMail($from='', $to, $template, $data_parse,$type='html',$bcc=array(),$cc=array(),$data_subject=array()) {
 		$CI = get_instance();
 		$mailemailID=get_option_value('admin_email');
		$name=get_option_value('website_name');
		$site_logo=theme_url().IMAGE.LOGO_NAME;
 		$mailcontent = getData(array(
 		'select'=>'m.template_id,mt_n.template_content,mt_n.template_subject',
 		'table'=>'mailtemplate as m',
 		'join'=>array(array('table'=>'mailtemplate_names as mt_n','on'=>"m.template_id=mt_n.template_id and mt_n.lang='".getSetlang()."'",'position'=>'left')),
 		'where'=>array('m.template_type'=>$template),
 		'single_row'=>TRUE
 		));
       if($mailcontent){
            $subject = $mailcontent->template_subject;
            $contents = $mailcontent->template_content;
	   }else{
	   		 $contents = 'Invalid Template';
            $subject ='Invalid Template';
	   }
        if($data_subject){
			foreach ($data_subject as $key => $val) {
           	 $subject = str_replace('{' . $key . '}', $val, $subject);
        	}
		}
		$preparse=array(
		'WEBSITE_NAME'=>$name,
		'WEBSITE_LOGO'=>"<img src='".$site_logo."' width='100' >",
		'ADMIN_URL'=>ADMIN_URL,
		);
		foreach ($data_parse as $key => $val) {
            $contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
            
        }
        foreach($preparse as $key=>$val){
			$contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
		}
		
		if(SET_EMAIL_CRON==1 && $to!=''){
			
 		$pending_emails=array(
 		'to_email'=>$to,
 		'email_subject'=>$subject,
 		'email_content'=>$contents,
 		'request_date'=>date('Y-m-d H:i:s'),
 		'email_unique_id'=>time().'_'.rand(1,10000),
 		);
 		$CI->db->insert('pending_emails',$pending_emails);
 		return 1;
 		die;
		}
 		$send='';
 		$CI->load->library("PhpMailerLib");
        $mail = $CI->phpmailerlib->load();
        try {
        	$user=get_option_value('smtp_user');
        	$mail->SMTPDebug = 0;
        	$mail->isSMTP();
        	$mail->Host = get_option_value('smtp_host'); 
        	$mail->SMTPAuth =true; 
        	$mail->Username = $user;
        	$mail->Password = get_option_value('smtp_pass');                           // SMTP password
		    $mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = get_option_value('smtp_port');                                    // TCP port to connect to
		    //Recipients
		    $mail->setFrom($user);
		    $mail->addAddress($to);
		    $mail->addReplyTo($user);
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body    = $contents;
		    $send=$mail->send();
        	$mail->ClearAllRecipients(); 
    		$mail->ClearAttachments();   //Remove all attachements
        } catch (Exception $e) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
       	ob_clean();
        return $send;
    }
 function SendMailCI($from='', $to, $template, $data_parse,$type='html',$bcc=array(),$cc=array(),$data_subject=array()) {
 		$CI = get_instance();
 		$config['protocol'] = get_option_value('protocol');
		$config['smtp_host'] = get_option_value('smtp_host');
		$config['smtp_port'] = get_option_value('smtp_port');
		$config['smtp_user'] = get_option_value('smtp_user');
		$config['smtp_pass'] = get_option_value('smtp_pass');
		$config['mailtype'] = get_option_value('mailtype');
		$config['charset'] = get_option_value('charset'); 
		
		
 		$mailemailID=get_option_value('admin_email');
		$name=get_option_value('website_name');
		$site_logo=theme_url().IMAGE.LOGO_NAME;
 		$mailcontent = getData(array(
 		'select'=>'m.template_id,mt_n.template_content,mt_n.template_subject',
 		'table'=>'mailtemplate as m',
 		'join'=>array(array('table'=>'mailtemplate_names as mt_n','on'=>"m.template_id=mt_n.template_id and mt_n.lang='".getSetlang()."'",'position'=>'left')),
 		'where'=>array('m.template_type'=>$template),
 		'single_row'=>TRUE
 		));
       if($mailcontent){
            $subject = $mailcontent->template_subject;
            $contents = $mailcontent->template_content;
	   }else{
	   		 $contents = 'Invalid Template';
            $subject ='Invalid Template';
	   }
        if($data_subject){
			foreach ($data_subject as $key => $val) {
           	 $subject = str_replace('{' . $key . '}', $val, $subject);
        	}
		}
		$preparse=array(
		'WEBSITE_NAME'=>$name,
		'WEBSITE_LOGO'=>"<img src='".$site_logo."' width='100' >",
		'ADMIN_URL'=>ADMIN_URL,
		);
		foreach ($data_parse as $key => $val) {
            $contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
            
        }
        foreach($preparse as $key=>$val){
			$contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
		}
		
		//$to='asish9735@gmail.com';
        //$CI->load->library('email');
        //$CI->email->initialize($config);
        $CI->load->library('email', $config);
		$CI->email->from($config['smtp_user'], $name);
		//$CI->email->reply_to($mailemailID, $name);

        $CI->email->to($to);
        //$CI->email->bcc('asish9735@gmail.com');
        $CI->email->subject($subject);
		$CI->email->set_mailtype($type);
		if($bcc){
			$CI->email->bcc($bcc);	
		}
		if($cc){
			$CI->email->bcc($cc);	
		}
        $CI->email->message($contents);
        $send=$CI->email->send();
       // echo $CI->email->print_debugger();
       ob_clean();
        return $send;
    }
if ( ! function_exists('syncMailchimp')){
function syncMailchimp($data,$apiKey,$listId) {
    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
    $json = json_encode(array(
        'email_address' => $data['email'],
        'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
        'merge_fields'  => array(
            'FNAME'     => $data['firstname'],
            'LNAME'     => $data['lastname']
        			)
    ));
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode;
}
}

if ( ! function_exists('D')){
	function D($data) {
		echo $data;
	}
}
/*if ( ! function_exists('__')){
	function __($key,$default='') {
		return $default;
	}
}*/


if (!function_exists('system_notification')) {

    function system_notification($data=array(),$type='') {
    	$ci = &get_instance();
    	$ci->load->database();
        if($type=='wallet_error'){
        	$message="Wallet balance error for wallet ".$data['wallet_id']." ".$data['wallet_name']." detected after transaction ".$data['transaction_id'];
			$ci->db->insert('admin_notifications',array('message'=>$message,'created_date'=>date('Y-m-d H:i:s')));
		}
    }

}
if (!function_exists('wallet_balance_check')) {

    function wallet_balance_check($wallet_id,$data=array()) {
    	$ci = &get_instance();
    	$ci->load->database();
        $error=0;
        $wallet_name='';
        $transaction_id='';
        if($data['transaction_id']){
			$transaction_id=$data['transaction_id'];
			
		}
		$wallet_transaction_type_id=get_option_value('WITHDRAW');
        $ci->db->select('w.title,w.balance as wallet_balance,SUM( wtr.credit ) - SUM( wtr.debit ) AS balance,w.user_id, m.member_name');
        $ci->db->from('wallet_transaction_row  as wtr');
        $ci->db->join('wallet_transaction  as wt', 'wtr.wallet_transaction_id=wt.wallet_transaction_id', 'LEFT');
        $ci->db->join('wallet as w', 'wtr.wallet_id=w.wallet_id', 'LEFT');
		$ci->db->join('member  as m', 'w.user_id=m.member_id', 'LEFT');
		$ci->db->where('w.wallet_id',$wallet_id);
		$ci->db->where("IF(wt.wallet_transaction_type_id='".$wallet_transaction_type_id."' , wt.status!='2',wt.status='1')");
		
		//$ci->db->where('wt.status', '1');
		$ci->db->where('wt.transaction_date !=', '0000-00-00 00:00:00');
		$ci->db->group_by('w.wallet_id');	
		$result = $ci->db->get()->row_array();
		if($result){
			list($wallet_balance) = explode(".", $result['wallet_balance']);
			list($balance) = explode(".", $result['balance']);
			//if($result['wallet_balance']!=displayamount($result['balance'],2)){
			if($wallet_balance!=$balance){
				  $error=1;
				  if(!empty($result['title'])){
						$wallet_name= $result['title'];
					}else{
						if($result['user_id'] > 0){
							$wallet_name= $result['member_name'];
						}else{
							$wallet_name= 'Site Wallet';
						}
						
					}
			}
		}
       if($error==1){
       	error_log('wallet balance:'.$result['wallet_balance'].'!='.'system balance:'.$result['balance']." tranastion id:".$transaction_id." wallet_id:".$wallet_id);
	   		system_notification(array('wallet_id'=>$wallet_id,'wallet_name'=>$wallet_name,'transaction_id'=>$transaction_id),'wallet_error');
	   }
    }

}
if ( ! function_exists('displayamount'))
{
	function displayamount($amount,$limit='2'){
		$amount=number_format($amount,$limit, '.', '');
		if($limit==4){
			return sprintf("%1.4f",$amount);
		}elseif($limit==2){
			return sprintf("%1.2f",$amount);
		}else{
			return sprintf("%1.2f",$amount);
		}
		
	}
}
function getTextFromString($string=''){
	 $string = str_replace(array("\r\n", "\r", "\n"), " ", $string);
	 $string = str_replace('<', " <", $string);
	 $string = str_replace('>', "> ", $string);
	 $string=strip_tags($string);
	 $string = preg_replace('/\s+/', ' ',$string);
	return trim($string);
}
function generateProcessingFee($type,$amount){
	$data=array('processing_fee_text'=>'','processing_fee'=>0,'total_amount'=>0);
	$is_valid=0;
	if($type=='paypal'){
		$processing_fee_fixed=get_option_value('paypal_processing_fee_fixed');
		$processing_fee_percent=get_option_value('paypal_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='telr'){
		$processing_fee_fixed=get_option_value('telr_processing_fee_fixed');
		$processing_fee_percent=get_option_value('telr_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='ngenius'){
		$processing_fee_fixed=get_option_value('ngenius_processing_fee_fixed');
		$processing_fee_percent=get_option_value('ngenius_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='bank'){
		$processing_fee_fixed=0;
		$processing_fee_percent=0;
		$is_valid=1;
	}elseif($type=='withdrawal_paypal'){
		$processing_fee_fixed=get_option_value('withdrawal_paypal_processing_fee_fixed');
		$processing_fee_percent=get_option_value('withdrawal_paypal_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='withdrawal_bank'){
		$processing_fee_fixed=get_option_value('withdrawal_bank_processing_fee_fixed');
		$processing_fee_percent=get_option_value('withdrawal_bank_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='withdrawal_payoneer'){
		$processing_fee_fixed=get_option_value('withdrawal_payoneer_processing_fee_fixed');
		$processing_fee_percent=get_option_value('withdrawal_payoneer_processing_fee_percent');
		$is_valid=1;
	}
	if($is_valid){
		$total_fee_text="";
		if($processing_fee_percent>0){
			$total_fee_text.=$processing_fee_percent.'%';
		}
		if($processing_fee_percent>0 && $processing_fee_fixed>0){
			$total_fee_text.=' + ';
		}
		if($processing_fee_fixed>0){
			$total_fee_text.=CURRENCY.$processing_fee_fixed;
		}
		$data['processing_fee_text']=$total_fee_text;
		$processing_fee_percent_amt=($amount*$processing_fee_percent)/100;
		$total_fee=$processing_fee_percent_amt+$processing_fee_fixed;
		$data['processing_fee']=displayamount($total_fee);
		$data['total_amount']=displayamount($total_fee+$amount);
	}
	return $data;
}
function curl_telr($post_data)
	{
		$url='https://secure.telr.com/gateway/order.json';
		$fields='';
		foreach ($post_data as $k => $v) {
			$fields.=$k .'='.$v . '&';
		}
		$fields = rtrim($fields, '&');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($fields)));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch,CURLOPT_POST, count($post_data));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
		//curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,10);
		curl_setopt($ch,CURLOPT_TIMEOUT, 30);
		$returnData = json_decode(curl_exec($ch),true);
		//$returnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return $returnData;
}
function curl_ngenius($post_data,$type='token',$member_id='')
	{
		$ch = curl_init();
		if($type=='order'){
			$outlet=get_option_value('ngenius_outlet');
			//$url='https://api-gateway-uat.ngenius-payments.com/transactions/outlets/'.$outlet.'/orders';
			$url=get_option_value('NGENIUS_TRANSACTION_BASE_URL');
			
		}else{
			$url='https://identity-uat.ngeniuspayments.com/auth/realms/ni/protocol/openidconnect/token';
			//$url='https://api-gateway-uat.ngenius-payments.com/identity/auth/access-token';
			$url=get_option_value('NGENIUS_TOKEN_BASE_URL');	
		}
		curl_setopt($ch, CURLOPT_URL, $url); 
		if($type=='order'){
			$member_name=getFieldData('member_name','member','member_id',$member_id);
			$Name=explode(' ',$member_name,2);
			$member_email=getFieldData('member_email','member','member_id',$member_id);
			$member_country=getFieldData('member_country','member_address','member_id',$member_id);
			$post_data['emailAddress']=$member_email;
			$post_data['billingAddress']=array();
			$post_data['billingAddress']['firstName']=$Name[0];
			$post_data['billingAddress']['lastName']=$Name[1];
			$post_data['billingAddress']['countryCode']=$member_country;
			$post_data['merchantAttributes']['skipConfirmationPage']=true;
			$token=$post_data['token'];
			unset($post_data['token']);	
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token, 'Content-Type: application/vnd.ni-payment.v2+json', 'Accept: application/vnd.ni-payment.v2+json'));
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		}else{
			$apikey=get_option_value('ngenius_apikey');
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$apikey, 'Content-Type: application/x-www-form-urlencoded')); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$apikey, 'Content-Type: application/x-www-form-urlencoded'));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		 
		
		if($type=='order'){
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data)); 
		}else{
			curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query($post_data)); 
		}
		curl_setopt($ch,CURLOPT_TIMEOUT, 30);
		$returnData = json_decode(curl_exec($ch),true);
		$err = curl_error($ch);
		if ($err) {
			  echo "cURL Error #:" . $err;
		}
		curl_close($ch);
		return $returnData;
}
function getComissionPercentage($member_id){
	$comission_percentage=get_option_value('comission_percentage');
	$userdata=getData(array(
	'select'=>'c.comission_percentage',
	'table'=>'member_address as m',
	'join'=>array(array('table'=>'country as c','on'=>'m.member_country=c.country_code','position'=>'left')),
	'where'=>array('m.member_id'=>$member_id),
	'single_row'=>TRUE
	));
	if($userdata){
		if($userdata->comission_percentage>0){
			$comission_percentage=$userdata->comission_percentage;
		}
	}
	return $comission_percentage;
}
