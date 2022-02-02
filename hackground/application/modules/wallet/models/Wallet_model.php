<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'wallet';
		$this->primary_key = $this->table.'_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('*')
			->from($this->table);
		
		
		
		if(!empty($srch['term'])){
			$this->db->like('title', $srch['term']);
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by($this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function getTxnDetail($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		if(!empty($srch['is_list_transaction_details'])){
			$this->db->select('t.*,t_type.title_tkey,t_type.description_tkey as type_description_tkey,sum(r.credit) as credit,sum(r.debit) as debit,w.wallet_id,w.user_id,w.title as wallet_title');
		}else{
			$this->db->select('t.*,t_type.title_tkey,t_type.description_tkey as type_description_tkey,r.*,w.wallet_id,w.user_id,w.title as wallet_title');
		}
		$this->db->from('wallet_transaction t')
			->join('wallet_transaction_type t_type', 't_type.wallet_transaction_type_id=t.wallet_transaction_type_id', 'LEFT')
			->join('wallet_transaction_row r', 'r.wallet_transaction_id=t.wallet_transaction_id')
			->join('wallet w', 'r.wallet_id=w.wallet_id');
		
		if(!empty($srch['term'])){
			$this->db->like('t_type.description_tkey', $srch['term']);
		}
		
		if(!empty($srch['wallet_id'])){
			$this->db->where('r.wallet_id', $srch['wallet_id']);
		}
		if(!empty($srch['fromdate']) && !empty($srch['enddate'])){
			$this->db->where('t.transaction_date >=', $srch['fromdate']);
			$this->db->where('t.transaction_date <=', $srch['enddate']);
		}
		if(!empty($srch['txn_id'])){
			$this->db->where('r.wallet_transaction_id', $srch['txn_id']);
		}
		if(!empty($srch['is_list_transaction_details'])){
		$this->db->where('t_type.description_tkey <>', 'Online_payment_from');
		$this->db->group_by('t.wallet_transaction_id');
		}
		if($for_list){
			$this->db->limit($offset, $limit);
			if(!empty($srch['order_asc']) && $srch['order_asc']==1){
				$this->db->order_by('t.wallet_transaction_id', 'ASC')->order_by('r.wallet_transaction_row_id', 'ASC');
			}else{
				$this->db->order_by('t.wallet_transaction_id', 'DESC')->order_by('r.wallet_transaction_row_id', 'ASC');
			}
			$result = $this->db->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		return $result;
	}
	
	public function getTxnList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('t.*,t_type.title_tkey,t_type.description_tkey as type_description_tkey')
			->from('wallet_transaction t')
			->join('wallet_transaction_type t_type', 't_type.wallet_transaction_type_id=t.wallet_transaction_type_id', 'LEFT');
			
		
		if(!empty($srch['term'])){
			$this->db->like('t_type.description_tkey', $srch['term']);
		}
		
		if(isset($srch['status']) && $srch['status'] != ''){
			$this->db->where('t.status', $srch['status']);
		}
		
		$this->db->group_by('t.wallet_transaction_id');
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('t.wallet_transaction_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}
	
	public function getWithdrawnList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$wallet_transaction_type_id=get_setting('WITHDRAW');
		//$this->db->select('t.*,sum(r.debit-r.credit) as amount,r.description_tkey,r.relational_data,m.member_name,w.balance')
		$this->db->select('t.*,sum(r.debit) as amount,sum(r.credit) as processing_fee,r.description_tkey,r.relational_data,m.member_name,w.balance')
			->from('wallet_transaction t')
			->join('wallet_transaction_row r', 'r.wallet_transaction_id=t.wallet_transaction_id','left')
			->join('wallet as w', 'r.wallet_id=w.wallet_id', 'LEFT')
			->join('member  as m', 'w.user_id=m.member_id', 'LEFT');
		
		
		$this->db->where('t.wallet_transaction_type_id',$wallet_transaction_type_id);
		//if($srch && $srch['is_list']){
			$this->db->where('r.description_tkey <>','Transfer_from');
		//}
		
		
		$this->db->group_by('t.wallet_transaction_id');
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('t.wallet_transaction_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}
	
	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'title' => !empty($data['name']) ? $data['name'] : '',
			'status' => !empty($data['status']) ? $data['status'] : '0',
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
	
	public function wallet_debit_balance($wallet_id=''){
		$wallet_transaction_type_id=get_setting('WITHDRAW');
		$res = $this->db->select("sum(tr.debit) as debit")
				->from('wallet_transaction_row tr')
				->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
				->where('tr.wallet_id', $wallet_id)
				//->where('t.status', 1)
				->where("IF(t.wallet_transaction_type_id='".$wallet_transaction_type_id."' , t.status!='2',t.status='1')")
				->get()
				->row_array();
		
		return $res['debit'];
	}
	
	public function wallet_credit_balance($wallet_id=''){
		$wallet_transaction_type_id=get_setting('WITHDRAW');
		$res = $this->db->select("sum(tr.credit) as credit")
				->from('wallet_transaction_row tr')
				->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
				->where('tr.wallet_id', $wallet_id)
				/*->where('t.status', 1)*/
				->where("IF(t.wallet_transaction_type_id='".$wallet_transaction_type_id."' , t.status!='2',t.status='1')")
				->get()
				->row_array();
				
			
		
		return $res['credit'];
	}
	
	
}


