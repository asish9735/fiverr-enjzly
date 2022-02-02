<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*  @author             Venkatesh bishu
 *   This function filter any input data
 *   @param                 Description 
 *  @ data                  The data to filter
 *  
 */

if (!function_exists('filter_data')) {

    function filter_data($data) {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if(!is_array($v)){
                     $data[$k] = trim(htmlentities($v));
                }
            }
            return $data;
        } else {
            return trim(htmlentities($data));
        }
    }

}

/*
 *  @author                 Venkatesh bishu
 * This function will print everything and die the rest of the codes
 * 
 */


 if (!function_exists('filter_decode')) {

    function filter_decode($data) {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if(!is_array($v)){
                     $data[$k] = html_entity_decode($v);
                }
            }
            return $data;
        } else {
            return html_entity_decode($data);
        }
    }

}


if (!function_exists('get_print')) {

    function get_print($arr, $die = true) {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        if ($die) {
            die;
        }
    }

}

if (!function_exists('getField')) {
    /*
     *  @author              Venkatesh bishu
     *  This function will return a single column from the database
     *  
     *  Parameter                   Description
     *  $column                     The name of column which you want to select [String]
     *  $table                      the name of the table from which you want to fetch the condition [string]
     *  $con_where                  the where condition your want to apply
     *  $con_value                  The value of the where condition
     *  @Return                     The value of that column [String]
     * 
     *
     */

    function getField($column = '', $table = '', $where = '', $val = '') {
        $ci = &get_instance();
        $res = $ci->db->select($column)
                ->from($table)
                ->where($where, $val)
                ->get()
                ->row_array();

        return $res[$column];
    }

}


if(!function_exists('get_row')){
	/*
		Date : 03/08/2016
		
		This function fetch a single row from database in array or json format 
		@param1 $q				Array of query format
		@param2	$format			Result format
		@param3 $for_list		Whether the query is used for list or for count purpose
		@return 				Query Result either in json or array format
		
		Author					Venkatesh bishu
		Note :					If you are using multiple table joining use multidimensional array in join key Ex. $q['join'] = array(array(table , base , jointype) , array(table , base , jointype))
		Query array format
		------------------
			* Same as get_results
			
	*/
	function get_row($q=array() , $format='array'){
		
		if(empty($q) OR empty($q['from']) OR empty($q['select'])){
			die("Enter a valid paramenter");
		}
		
		$ci = &get_instance();
		
		$result = '';
		
		$ci->db->select($q['select']);
		$ci->db->from($q['from']);
		if(!empty($q['join'])){
			foreach($q['join'] as $k => $v){
				if(empty($q['join'][$k][2])){
					$q['join'][$k][2] = 'INNER';
				}
				$ci->db->join($q['join'][$k][0] , $q['join'][$k][1] , $q['join'][$k][2]);
			}
		}
		
		
		if(!empty($q['where'])){
			$ci->db->where($q['where']);
		}
		
		$result = $ci->db->get()->row_array();
		$format = strtolower($format);
		if($format == 'json'){
			return json_encode($result);
		}
		if($format == 'object'){
			return (object) $result;
		}
		return $result;
		
	}
}

if(!function_exists('get_results')){
	/*
		Date : 03/08/2016
		
		This function fetch results from database in array or json format 
		@param1 $q				Array of query format
		@param2	$format			Result format
		@param3 $for_list		Whether the query is used for list or for count purpose
		@return 				Query Result either in json or array format
		
		Author					Venkatesh bishu
		Note :					If you are using multiple table joining use multidimensional array in join key Ex. $q['join'] = array(array(table , base , jointype) , array(table , base , jointype))
		Query array format
		------------------
			$q = array(
			'select' => 'a.* , b.category as n_category ,  c.type as n_type',
			'from' => 'news a',
			'join' => array(array('newscategory b' , 'b.id=a.category' , 'INNER'),array('newstype c' , 'c.id=a.type' , 'INNER')),
			'where' => array('a.id' => '1'),
			'limit' => 0,
			'offset' => 10
		);
			
	*/
	function get_results($q=array() , $format='array' , $for_list = TRUE){
		
		if(empty($q) OR empty($q['from']) OR empty($q['select'])){
			die("Enter a valid parameter");
		}
		
		$ci = &get_instance();
		$limit = 0;
		$offset = 10;
		$result = '';
		
		if(!empty($q['limit'])){
			$limit = $q['limit'];
		}
		
		if(!empty($q['offset'])){
			$offset = $q['offset'];
		}
		
		$ci->db->select($q['select']);
		$ci->db->from($q['from']);
		
		if(!empty($q['join']) AND is_array($q['join'])){
			
			// $q['join'] = array(array('table2' , 'col=val AND col=val' , 'JOINTYPE'),array('table2' , 'col=val AND col=val' , 'JOINTYPE'));
			foreach($q['join'] as $k => $v){
					if(empty($q['join'][$k][2])){
						$q['join'][$k][2] = 'INNER';
					}
					$ci->db->join($q['join'][$k][0] , $q['join'][$k][1] , $q['join'][$k][2]);
			}
		}
		
		if(!empty($q['where'])){
			$ci->db->where($q['where']);
		}
		
		if($for_list){
			if(!empty($q['order_by'])){
				$ci->db->order_by($q['order_by'][0] , $q['order_by'][1]);
			}
			if($offset != 'all'){
				$result = $ci->db->limit($offset , $limit)->get()->result_array();
			}else{
				$result = $ci->db->get()->result_array();
			}
			$format = strtolower($format);
			if($format == 'json'){
				return json_encode($result);
			}
			if($format == 'object'){
				return (object) $result;
			}
			return $result;
		}else{
			$result = $ci->db->get()->num_rows();
			return $result;
		}
	}
}



if (!function_exists('count_results')) {
    /*
     *  @author                     Venkatesh bishu
     *  This function will count all the active records return by the query made against database
     *  
     *  @param                          Description
     *  $table_name                     The name of the table [String]
     *  @condition                      The condition you want to make against database[array]
     *  
     *  @Return                         Number of active records made from the above query [intger]                    
     */

    function count_results($table = '', $condition = array(),$col='*') {
        $ci = &get_instance();
        $ci->db->select($col)
                ->from($table);
        if (!empty($condition)) {
            $ci->db->where($condition);
        }

        $res = $ci->db->count_all_results();

        return $res;
    }

}

if (!function_exists('insert_record')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will insert data in the database
     *   @param                             Description
     * 
     *   $table                               The name of table in which you want to insert data[String]
     *   $data                               The data you want to insert [Array]
     *  
     *  $is_return_key                        Is the function return last insert key default is no
     * 
     *  @return                             Boolean [in case of simple entry of data] Interger [In case of returning the last inserted key]
     */

    function insert_record($table = '', $data=array(), $is_return_key = false) {
        $ci = &get_instance();
        if ($is_return_key) {
            $ci->db->insert($table, $data);
            $res = $ci->db->insert_id();
            return $res;
        } else {
            return $ci->db->insert($table, $data);
        }
    }

}

if (!function_exists('update_record')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will update the record in the database
     *  
     *  @param                  Description
     *  $table                  The name of the table you want to update
     *  $data                   The data to update
     *  $condition              The condition for updating data
     * 
     *  Return                  Boolean
     */

    function update_record($table = '', $data = array(), $condition = array()) {
        $ci = &get_instance($data);
		$ci->db->set($data);
        $ci->db->where($condition);
		return $ci->db->update($table);
    }

}


if(!function_exists('update')){
	/*
		Date : 03/08/2016
		
		This function fetch a single row from database in array or json format 
		@param1 $query			Array of query format
		@return 				Void 
		
		Author					Venkatesh bishu
		
	*/
	
	function update($query=array()){
		if(empty($query['data']) OR empty($query['table'])){
			die("Enter a valid parameter");
		}
		
		$ci = &get_instance();
		
		if(!empty($query['where'])){
			$ci->db->where($query['where']);
		}
		
		return $ci->db->update($query['table'] , $query['data']);
		
	}
}




if(!function_exists('delete')){
	/*
		Date : 03/08/2016
		
		This function fetch a single row from database in array or json format 
		@param1 $query			Array of query format
		@return 				Void
		
		Author					Venkatesh bishu
		
	*/
	function delete($query=array()){
		if(empty($query['table']) OR empty($query['where'])){
			die("Enter a valid parameter");
		}
		
		$ci = &get_instance();
		
		$ci->db->where($query['where']);
		return $ci->db->delete($query['table']);
		
	}
}


if(!function_exists('insert')){
	/*
		Date : 03/08/2016
		
		This function fetch a single row from database in array or json format 
		@param1 $query			Array of query format
		@return 				Void
		
		Author					Venkatesh bishu
		
	*/
	function insert($query=array() , $insert_id=FALSE){
		if(empty($query['table']) OR empty($query['data'])){
			die("Enter a valid parameter");
		}
		
		$ci = &get_instance();
		
		$ins = $ci->db->insert($query['table'] , $query['data']);
		if($ins){
			if($insert_id){
				return $ci->db->insert_id();
			}
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
}


if(!function_exists('query')){
	/*
		Date : 03/08/2016
		
		This function fetch result from a sql string and return array
		@param1 $query			String of query
		@param2 $format			The return data format
		
		@return 				Array result
		
		Author					Venkatesh bishu
	*/
	
	function query($sql='' , $format='array' , $for_list=TRUE){
		if(empty($sql)){
			die("Enter a valid paramter");
		}
		$ci = &get_instance();
		$query = $ci->db->query($sql);
		if($for_list){
			$result = $query->result_array();
			$format = strtolower($format);
			if($format == 'object'){
				return (object) $result;
			}else if($format == 'json'){
				return json_encode($result);
			}else{
				return $result;
			}
		}else{
			return $query->num_rows();
		}
		
	}
}


if (!function_exists('delete_record')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will delete record from the database table
     *  @param                      Description
     *  $table                      The name of table you want to delete the record from 
     *  $condition                  The condition you want to make against database
     *  
     * Return                       Boolean
     */

    function delete_record($table = '', $condition = array()) {
        $ci = &get_instance();
        $ci->db->where($condition);
        return $ci->db->delete($table);
    }

}

if (!function_exists('get_last_query')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will return the last database query
     */

    function get_last_query() {
        $ci = &get_instance();
        return $ci->db->last_query();
    }

}


if (!function_exists('get_affected_rows')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will return all the affected rows when a query run against database
     */

    function get_affected_rows() {
        $ci = &get_instance();
        return $ci->db->affected_rows();
    }

}

if (!function_exists('simple_query')) {
    /*
     *  @param              Venkatesh bishu
     *  This function will make a simple query 
     * Return               Array of generated result
     */

    function simple_query($sql = '', $for_listing = TRUE) {
        if (empty($sql)) {
            return false;
        }

        $ci = &get_instance();
        $q = $ci->db->query($sql);
        if ($for_listing) {
            return $q->result_array();
        } else {
            return $q->num_rows();
        }
    }

}

if (!function_exists('get_last_row')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will return the last row from an active record
     * 
     *  @param                 `Description
     *  $table                  the name of table[string]
     *  $condition              the condition you want to make against databas[array]
     * 
     *  Return                  Single array
     */

    function get_last_row($table = '', $condition = array(), $col = '*') {
        $ci = &get_instance();
        $ci->db->select($col)
                ->from($table);
        if (!empty($condition)) {
            $ci->db->where($condition);
        }
        $res = $ci->db->get()
                ->last_row('array');

        return $res;
    }

}

if (!function_exists('get_dbprefix')) {
    /*
     *  This function will return the db prefix of a given table
     *  @param              the tablename
     *  retrun              The table name with the db prefix
     */

    function get_dbprefix($table = '') {
        $ci = &get_instance();
        $result = $ci->db->dbprefix($table);
        return $result;
    }

}


if (!function_exists('today_time')) {
    /*
     *  @author             Venkatesh bishu
     *  This function will return the today timestamp 
     */

    function today_time($type = 'timestamp') {
        $result = null;
        switch ($type) {
            case 'timestamp':
                $result = date('Y-m-d h:i:s');
                break;

            case 'date':
                $result = date('Y-m-d');
                break;

            case 'time':
                $result = date('h:i:s');
                break;

            case 'year':
                $result = date('Y');
        }

        return $result;
    }

}


if (!function_exists('get_time')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will compute the difference from today to past provided date
     *  if no any parameter are passed through this function then on that condition the 
     *  function will return a simple timestamp
     * 
     *  @param                  Description
     *  $date                   The previous timestamp [string]
     *  @Return                 The difference from the provided timestamp and current day
     */

    function get_time($date = '') {
        if (empty($date)) {
            return date("Y-m-d h:i:s");
        }

        $prev_time = strtotime($date);
        $curr_tme = time();
		if(($curr_tme - $prev_time) < 60*1000){
			$return_date = round((($curr_tme - $prev_time)/1000)) . " seconds ago";
		}else if((($curr_tme -  $prev_time) > 60*1000) && (($curr_tme -  $prev_time) < 60*60*1000)){
			$return_date = round((($curr_tme - $prev_time)/(60*1000))) . " minute ago";
		}else if(($curr_tme -  $prev_time) > 60*60*1000 && ($curr_tme - $prev_time) < 60*60*24*1000){
			$return_date = round((($curr_tme - $prev_time)/(60*60*1000))) ." hour ago";
		}else if(($curr_tme - $prev_time) > 60*60*24*1000 && ($curr_tme -  $prev_time) < 60*60*24*30*1000){
			$return_date = round((($curr_tme - $prev_time)/(60*60*24*1000))) . " days ago";
		}else{
			$return_date= date('d M , Y', strtotime($date));
		}
		return $return_date;
	}

}
if (!function_exists('age')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will compute the difference from today to past provided date
     *  if no any parameter are passed through this function then on that condition the 
     *  function will return a simple timestamp
     * 
     *  @param                  Description
     *  $birthday                   The birthday of the user
     *  @Return                 	age of the user
*/

	function age($birthday){
		 list($year, $month, $date) = explode("-", $birthday);
		 $year_diff  = date("Y") - $year;
		 $month_diff = date("m") - $month;
		 $day_diff   = date("d") - $day;
		 if ($day_diff < 0 && $month_diff==0) $year_diff--;
		 if ($day_diff < 0 && $month_diff < 0) $year_diff--;
		 return $year_diff;
	}

}

if (!function_exists('get_session')) {
    /*
     *  @author                 Venkatesh bishu
     *  this function will return the session data by passing key to it
     *  @param                  Session Key
     */

    function get_session($key = '') {
        $ci = &get_instance();
        $ci->load->library('session');
        $data = $ci->session->userdata($key);
        return $data;
    }

}

if (!function_exists('set_session')) {
    /*
     *  @author             Venkatesh bishu
     *  This function will set the session data
     *  @param              key
     *  @param              value
     */

    function set_session($key = '', $value = '') {
        $ci = &get_instance();
        $ci->load->library('session');
        $ci->session->set_userdata($key, $value);
    }

}

if (!function_exists('delete_session')) {
    /*
     *  @param              Venkatesh bishu
     *  This function will delete the session data
     *  @param              Key
     */

    function delete_session($key = '') {
        $ci = &get_instance();
        $ci->load->library('session');
        $ci->session->unset_userdata($key);
    }

}

if (!function_exists('destroy_session')) {
    /*
     *  @author             Venkatesh bishu
     *  This function will destroy all the sessions data
     */

    function destroy_session() {
        $ci = &get_instance();
        $ci->load->library('session');
        $ci->session->sess_destroy();
    }

}


if(!function_exists('load_helper')){
	/*
		Date : 03/08/2016
		
		This function will load a helper
		@param 					Helper Name
		@return 				Void
		
		Author					Venkatesh bishu
	*/
	
	function load_helper($helper=''){
		$ci = &get_instance();
		$ci->load->helper($helper);
	}
}

if(!function_exists('load_class')){
	/*
		Date : 03/08/2016
		
		This function will load a library/class
		@param 					Class Name
		@return 				Void
		
		Author					Venkatesh bishu
	*/
	
	function load_class($class=''){
		$ci = &get_instance();
		$ci->load->library($class);
	}
}

if(!function_exists('load_config')){
	/*
		Date : 03/08/2016
		
		This function will load config file 
		@param 					Config Name
		@return 				Void
		
		Author					Venkatesh bishu
	*/
	
	function load_config($config=''){
		$ci = &get_instance();
		$ci->load->config($config);
	}
}

if(!function_exists('post')){
	/*
		Date : 03/08/2016
		
		This function will return all the $_POST value
		@param1 $key			The $_POST key
		@return 				$_POST array or single value
		
		Author					Venkatesh bishu
		
	*/
	function post($key=''){
		$ci = &get_instance();
		if($key == ''){
			return $ci->input->post();
		}else{
			return $ci->input->post($key);
		}
		
	}
}

if(!function_exists('get')){
	/*
		Date : 05/08/2016
		
		This function will return all the $_GET value
		@param1 $key			The $_GET key
		@return 				$_GET array or single value
		
		Author					Venkatesh bishu
		
	*/
	function get($key=''){
		$ci = &get_instance();
		if($key == ''){
			return $ci->input->get();
		}else{
			return $ci->input->get($key);
		}
		
	}
}



if(!function_exists('getIP')){
	/*
		Date : 05/08/2016
		
		This function fetch the user ip address
		
		@return 				IP Address
		
		Author					Venkatesh bishu
		
	*/
	function getIP(){
		$ip = '';
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}



if(!function_exists('get_ip_info')){
	/*
		Date : 05/08/2016
		
		This function return array of ip information
		$param ip				The ip address
		@return 				Ip details Array
		
		Author					Venkatesh bishu
		
	*/
	
	function get_ip_info($ip=''){
		if($ip == ''){
			return FALSE;
		}
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		return $details;
	}
}

if (!function_exists('check_user_log')) {
    /*
     *  this function check where the user is login or not
     *  If the user is not login then this function will redirect the user to the login page
     */

    function check_user_log() {
        $ci = &get_instance();
        $ci->load->library('session');
        $ci->load->helper('url');
        $flag = FALSE;
        $curr_url = base_url(uri_string());
        $get = $ci->input->get();
        if($get){
            $get = "?".http_build_query($get);
            $curr_url .= $get;
        }
        if (!$ci->session->has_userdata('user_id')) {
            $flag = TRUE;
        }
        if ($flag) {
			if($ci->input->is_ajax_request()){
				$location = base_url('login/?ref='.urlencode($curr_url));
				//echo '<script type="text/javascript">window.location.href ="'.$location.'";</script>';
			}else{
				redirect(base_url('login/?ref='.urlencode($curr_url)));
			}
        }
    }

}

if (!function_exists('is_login_user')) {
    /*
     *  This function will check whether the user is login or not and it will return true or false
     */

    function is_login_user() {
        $ci = &get_instance();
        $ci->load->library('session');
        $status = null;
        if ($ci->session->has_userdata('user_id')) {
            $status = TRUE;
        } else {
            $status = FALSE;
        }
        return $status;
    }

}


if (!function_exists('set_flash')) {
    /*
     *  This function will set a flash session 
     *  $key       The session name or key
     *  value       The session value
     */

    function set_flash($key = '' , $value = '') {
        $ci = &get_instance();
        if(empty($key) || empty($value)){
            die("please check your paramenter");
            return false;
        }
        $ci->load->library('session');
        $ci->session->set_flashdata($key, $value);
    }

}

if (!function_exists('get_flash')) {
    /*
     *  This function will set a flash session 
     *  $key       The key of the session which is set by set_flash
     *  return      The flash session value associated with the key or false if no any key is associated with that session
     */

    function get_flash($key = '') {
        $ci = &get_instance();
        if(empty($key)){
            die("please enter a key");
            return false;
        }
        $ci->load->library('session');
        $data = $ci->session->flashdata($key);
        if(!$data){
            return false;
        }
        return $data;
    }

}

if (!function_exists('simple_array')) {
    /*
     *  This function will make any array as simple in key value pair specified in the second and third parameter 
     *  $array      The array which has to make simple
     *  $key        The array key name
     *  $value      The array key value format
     *  return      The simple array in key value pair
     */

    function simple_array($array = array() , $key = '' , $val = '') {
        if(empty($array)){
            return false;
        }
        $new_array = array();
        if(count($array) > 0){
            foreach($array as $k => $v){
                $new_array[$v[$key]] = $v[$val];
            }
        }else{
            return FALSE;
        }
        return $new_array;

    }

}

if(!function_exists('clean_url')){
	/*
     * 	This function removes all whitespaces with - and return string
	 *	@param  	String
	 *	return 		String
     */
	 
	function clean_url($str){
		return trim(strtolower(preg_replace('/[\s+\'+\"+&+\?+]/', '-', $str)), '-');
	}
}


if(!function_exists('clean_string')){
	/*
     * 	This function removes all whitespaces with - and return string
	 *	@param  	String
	 *	return 		String
     */
	 
	function clean_string($str){
		return trim(strtolower(preg_replace('/[\s+\'+\"+&+\?+]/', '-', $str)), '-');
	}
}


if(!function_exists('seo_string')){
	/*
     * 	This function create a seo string
	 *	@param  	String
	 *	return 		String
     */
	 
	function seo_string($vp_string){
    
		$vp_string = trim($vp_string);
		
		$vp_string = html_entity_decode($vp_string);
		
		$vp_string = strip_tags($vp_string);
		
		$vp_string = strtolower($vp_string);
		
		$vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);
		
		$vp_string = preg_replace('~ ~', '-', $vp_string);
		
		$vp_string = preg_replace('~-+~', '-', $vp_string);
			
		return $vp_string;
    } 
}

