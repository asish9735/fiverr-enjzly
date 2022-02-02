<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Generatelanguage_model extends CI_Model{

	

	private $table , $lang_table, $primary_key;

	

	public function __construct(){

        return parent::__construct();

	}

	

	

	

	

	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){

		

		$lang = get_lang();

		if($for_list){

			$result = $lang;

		}else{

			$result = count($lang);

		}

		

		return $result;

	}

	

	public function addRecord($data=array()){

		$structure = array(

			'name' => !empty($data['name']) ? $data['name'] : '',

			'status' => !empty($data['status']) ? $data['status'] : '0',

		);

		$ins['data'] = $structure;

		$ins['table'] = $this->table;

		$insert_id = insert($ins, TRUE);

		

		$lang_fields = $data['lang'];

		$this->insert_lang_data($lang_fields, $insert_id);

		

		

		return $insert_id;

	}

	

	public function insert_lang_data($lang_fields=array(), $insert_id=''){

		$all_lang = get_lang();

		

		$this->db->where($this->primary_key, $insert_id)->delete($this->lang_table);

		foreach($all_lang as $k => $v){

			

			

			$structure = array(

				$this->primary_key => $insert_id,

				'lang' => $v,

			);

			

			foreach($lang_fields as $field_name => $lang_val){

				$structure[$field_name] = $lang_fields[$field_name][$v];

			}

			

			$lang_record['data'] = $structure;

			$lang_record['table'] = $this->lang_table;

			

			insert($lang_record);

		}

	}





	public function updateRecord($data=array(), $id=''){

		$structure = array(

			'name' => !empty($data['name']) ? $data['name'] : '',

			'status' => !empty($data['status']) ? $data['status'] : '0',

		);

		$ins['data'] = $structure;

		$ins['table'] = $this->table;

		$ins['where'] = array($this->primary_key => $id);

		

		

		$lang_fields = $data['lang'];

		$this->insert_lang_data($lang_fields, $id);

		

		

		return  update($ins);

	}

	

	public function deleteRecord($id=''){

		if($id && is_array($id)){

			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('status' => DELETE_STATUS));

		}else{

			$ins['data'] = array('status' => DELETE_STATUS);

			$ins['table'] = $this->table;

			$ins['where'] = array($this->primary_key => $id);

			return  update($ins);

		}

		

	}

	

	public function getDetail($id=''){

		$result = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();

		$lang_result = $this->db->where($this->primary_key, $id)->get($this->lang_table)->result_array();

		

		$lang_name=array();

		

		foreach($lang_result as $k => $v){

			$lang_name[$v['lang']] = $v['name'];

		}

		$result['lang'] = array();

		foreach($result as $k => $v){

			$result['lang']['name'] = $lang_name;

		}

		return $result;

	}

	

}





