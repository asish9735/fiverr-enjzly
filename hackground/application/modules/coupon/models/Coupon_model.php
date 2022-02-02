<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'coupons';
		$this->primary_key = 'coupon_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('a.*,p.proposal_title as proposal')
			->from($this->table . ' a')
			->join('proposals p', 'p.proposal_id=a.proposal_id', 'LEFT');
		
		
		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('a.coupon_title', $srch['term']);
			$this->db->or_like('a.coupon_code', $srch['term']);
			$this->db->or_like('p.proposal_title', $srch['term']);
			$this->db->group_end();
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('a.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function addRecord($data=array()){
		$structure = array(
			'proposal_id' => !empty($data['proposal_id']) ? $data['proposal_id'] : '',
			'coupon_title' => !empty($data['coupon_title']) ? $data['coupon_title'] : '',
			'coupon_type' => !empty($data['coupon_type']) ? $data['coupon_type'] : '',
			'coupon_price' => !empty($data['coupon_price']) ? $data['coupon_price'] : '',
			'coupon_code' => !empty($data['coupon_code']) ? $data['coupon_code'] : '',
			'coupon_limit' => !empty($data['coupon_limit']) ? $data['coupon_limit'] : '',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		return $insert_id;
	}

	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'proposal_id' => !empty($data['proposal_id']) ? $data['proposal_id'] : '',
			'coupon_title' => !empty($data['coupon_title']) ? $data['coupon_title'] : '',
			'coupon_type' => !empty($data['coupon_type']) ? $data['coupon_type'] : '',
			'coupon_price' => !empty($data['coupon_price']) ? $data['coupon_price'] : '',
			/* 'coupon_code' => !empty($data['coupon_code']) ? $data['coupon_code'] : '', */
			'coupon_limit' => !empty($data['coupon_limit']) ? $data['coupon_limit'] : '',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$ins['where'] = array($this->primary_key => $id);
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
		return $result;
	}
	
}


