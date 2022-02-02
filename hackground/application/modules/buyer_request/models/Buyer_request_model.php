<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buyer_request_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'buyer_requests';
		$this->primary_key = 'request_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('p.*,m.member_name')
			->from($this->table . ' p')
			->join('member m', 'm.member_id=p.seller_id', 'LEFT');
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('p.request_status', REQUEST_DELETED);	
		}else{
			$this->db->where('p.request_status <>', REQUEST_DELETED);	
		}
		
		if(!empty($srch['term'])){
			$this->db->like('p.request_title', $srch['term']);
		}
		
		if(!empty($srch['status'])){
			$this->db->where('p.request_status', $srch['status']);
		}
		
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by($this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function deleteRecord($id=''){
		if($id && is_array($id)){
			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('request_status' => REQUEST_DELETED));
		}else{
			$ins['data'] = array('request_status' => REQUEST_DELETED);
			$ins['table'] = $this->table;
			$ins['where'] = array($this->primary_key => $id);
			return  update($ins);
		}
		
	}
	
	public function getDetail($id=''){
		$result = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
		return $result;
	}
	public function offerList($request_id=''){
		$this->db->select('s_o.offer_id,m.member_name,s_o.delivery_time as delivery_time_offer,s_o.amount as amount_offer,s_o.description as description_offer,p.proposal_title,p.proposal_image,p.proposal_seller_id,m.seller_level,p.proposal_url,s_o.reg_date');
		$this->db->from('send_offers s_o');
		$this->db->join('proposals as p','s_o.proposal_id=p.proposal_id','left');
		$this->db->join('member as m','p.proposal_seller_id=m.member_id','left');
		$this->db->where(array('s_o.request_id'=>$request_id));
		$result = $this->db->order_by('s_o.offer_id','asc')->get()->result_array();
		return $result;
	}
	public function userList($request_id=''){
		$this->db->select('m.member_name,m.member_id,m.member_email');
		$this->db->from('request_category r');
		$this->db->join('proposal_category as p_c','r.category_subchild_id=p_c.category_subchild_id','left');
		$this->db->join('proposals as p','p_c.proposal_id=p.proposal_id','left');
		$this->db->join('member as m','p.proposal_seller_id=m.member_id','left');
		$this->db->where(array('r.request_id'=>$request_id,'m.member_id >'=>0,'p.proposal_status'=>PROPOSAL_ACTIVE));
		$result = $this->db->group_by('m.member_id')->order_by('m.member_id','asc')->get()->result_array();
		return $result;
	}
}


