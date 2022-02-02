<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function getTransaction($where=array(),$start = '', $limit = '',$count=FALSE){
		$wallet_transaction_type_id=get_option_value('WITHDRAW');
		$this->db->select('w.*, wr.wallet_id,(sum(wr.credit) - sum(wr.debit)) as Amount , wt.description_tkey as name,wt.title_tkey')
				->from('wallet_transaction w')
				->join('wallet_transaction_type wt', 'wt.wallet_transaction_type_id = w.wallet_transaction_type_id', 'INNER')
				->join('wallet_transaction_row wr', 'wr.wallet_transaction_id = w.wallet_transaction_id', 'LEFT');
		$this->db->where('wr.wallet_id', $where['wallet_id']);			
		$this->db->where('wr.description_tkey <>', 'Online_payment_from');		
		//$this->db->where_in('wt.title_tkey', $transaction_type);
		$this->db->where("IF(wt.wallet_transaction_type_id='".$wallet_transaction_type_id."' , w.status!='2',w.status='1')");
		$this->db->where('w.transaction_date !=', '0000-00-00 00:00:00');
		$this->db->group_by('w.wallet_transaction_id');
		if($count){
			$result = $this->db->get()->num_rows();
		}else{
			$result = $this->db->limit($start, $limit)->order_by('wr.wallet_transaction_row_id', 'DESC')->get()->result();
		}
		return $result;	
	}
	public function getOdderChartSattus($member_id){
		
		$order_active=$this->db->where('seller_id',$member_id)->where('order_active',1)->from('orders')->count_all_results();
		$order_active+=$this->db->where('buyer_id',$member_id)->where('order_active',1)->from('orders')->count_all_results();
		$ORDER_DELIVERED=$this->db->where('seller_id',$member_id)->where('order_status',ORDER_DELIVERED)->from('orders')->count_all_results();
		$ORDER_DELIVERED+=$this->db->where('buyer_id',$member_id)->where('order_status',ORDER_DELIVERED)->from('orders')->count_all_results();
		$ORDER_COMPLETED=$this->db->where('seller_id',$member_id)->where('order_status',ORDER_COMPLETED)->from('orders')->count_all_results();
		$ORDER_COMPLETED+=$this->db->where('buyer_id',$member_id)->where('order_status',ORDER_COMPLETED)->from('orders')->count_all_results();
		$ORDER_CANCELLED=$this->db->where('seller_id',$member_id)->where('order_status',ORDER_CANCELLED)->from('orders')->count_all_results();
		$ORDER_CANCELLED+=$this->db->where('buyer_id',$member_id)->where('order_status',ORDER_CANCELLED)->from('orders')->count_all_results();
		$result=array(
			'active'=>$order_active,
			'delivered'=>$ORDER_DELIVERED,
			'completed'=>$ORDER_COMPLETED,
			'cancelled'=>$ORDER_CANCELLED,
		);
		return $result;
	}
}
