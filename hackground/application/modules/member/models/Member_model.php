<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'member';
		$this->primary_key = $this->table.'_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$admin_default_lang = admin_default_lang();
		$this->db->select('u.*,a.login_status,a.access_username,c.country_name,mb.member_phone,mb.member_mobile_code')
			->from($this->table.' as u');
		$this->db->join('profile_connection as p_c',"(u.member_id=p_c.member_id and p_c.organization_id IS NULL)",'left');
		$this->db->join('access_panel as a','p_c.access_user_id=a.access_user_id','left');
		$this->db->join('member_address as ma','u.member_id=ma.member_id','left');
		$this->db->join('member_basic as mb','u.member_id=mb.member_id','left');
		$this->db->join('country_names as c',"(ma.member_country=c.country_code and c.country_lang='".$admin_default_lang."')",'left');
		
		
		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('u.member_name', $srch['term']);
			$this->db->or_like('u.member_email', $srch['term']);
			$this->db->group_end();
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('u.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		return $result;
	}
	public function getListCSV($srch=array()){
		$admin_default_lang = admin_default_lang();
		$this->db->select('u.*,a.login_status,a.access_username,n.country_name,c.country_name as nationality_name,ma.member_city,mb.member_gender,count(p.proposal_id) as no_of_gigs')
			->from($this->table.' as u');
		$this->db->join('profile_connection as p_c',"(u.member_id=p_c.member_id and p_c.organization_id IS NULL)",'left');
		$this->db->join('access_panel as a','p_c.access_user_id=a.access_user_id','left');
		$this->db->join('member_address as ma','u.member_id=ma.member_id','left');
		$this->db->join('member_basic as mb','u.member_id=mb.member_id','left');
		$this->db->join('country_names as c',"(ma.member_country=c.country_code and c.country_lang='".$admin_default_lang."')",'left');
		//$this->db->join('nationality_names as n',"(ma.member_nationality=n.nationality_id and n.nationality_lang='".$admin_default_lang."')",'left');
		$this->db->join('country_names as n',"(ma.member_nationality=n.country_code and n.country_lang='".$admin_default_lang."')",'left');
		$this->db->join('proposals as p',"(u.member_id=p.proposal_seller_id  and p.proposal_status='".PROPOSAL_ACTIVE."')",'left');
		
		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('u.member_name', $srch['term']);
			$this->db->or_like('u.member_email', $srch['term']);
			$this->db->group_end();
		}

		$result = $this->db->group_by('u.'.$this->primary_key)->order_by('u.'.$this->primary_key, 'DESC')->get()->result_array();
		
		return $result;
	}
	
	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'member_name' => !empty($data['member_name']) ? $data['member_name'] : '',
			'member_email' => !empty($data['member_email']) ? $data['member_email'] : '',
			'is_email_verified' => !empty($data['is_email_verified']) ? $data['is_email_verified'] : '0',
			'is_phone_verified' => !empty($data['is_phone_verified']) ? $data['is_phone_verified'] : '0',
			'is_admin_verified' => !empty($data['is_admin_verified']) ? $data['is_admin_verified'] : '0',
			/*'is_freelancer' => !empty($data['is_freelancer']) ? $data['is_freelancer'] : '0',*/
			'bank_transfer_allowed' => !empty($data['bank_transfer_allowed']) ? $data['bank_transfer_allowed'] : '0',
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


