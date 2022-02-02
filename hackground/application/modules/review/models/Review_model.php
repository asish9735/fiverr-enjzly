<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model{
	
	
	public function __construct(){
        return parent::__construct();
	}
	
	public function getBuyerReview($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('b_r.*,p.proposal_title,o.order_number,b.member_name as buyer_name')
			->from('buyer_reviews b_r')
			->join('proposals p', 'p.proposal_id=b_r.proposal_id', 'LEFT')
			->join('orders o', 'o.order_id=b_r.order_id', 'LEFT')
			->join('member b', 'b.member_id=b_r.review_buyer_id', 'LEFT');
			
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('b_r.review_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}
	
	public function getSellerReview($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('s_r.*,p.proposal_title,o.order_number,s.member_name as seller_name')
			->from('seller_reviews s_r')
			->join('orders o', 'o.order_id=s_r.order_id', 'LEFT')
			->join('proposals p', 'p.proposal_id=o.proposal_id', 'LEFT')
			->join('member s', 's.member_id=s_r.review_seller_id', 'LEFT');
			
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('s_r.review_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}
	
	public function update_buyer_averate_rating($id=''){
		$member_id = getField('review_seller_id', 'buyer_reviews', 'review_id', $id);
		$positive=$CI->db->where('review_seller_id',$member_id)->where('buyer_rating >=',4)->from('buyer_reviews')->count_all_results();
		$negetive=$CI->db->where('review_seller_id',$member_id)->where('buyer_rating <',4)->from('buyer_reviews')->count_all_results();
		$total=$positive+$negetive;
		$positive_percent=($positive*100)/$total;
		$this->db->where('member_id', $member_id)->update('member', array('seller_rating'=>$positive_percent));
	}
	
}


