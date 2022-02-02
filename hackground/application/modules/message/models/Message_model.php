<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'conversations';
		$this->primary_key = 'conversations_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('c.*,s.member_name as sender_name,r.member_name as receiver_name,m.message,m.sending_date,c.conversations_id')
			->from($this->table . ' as  c')
			->join('member s', 's.member_id=c.sender_id', 'LEFT')
			->join('member r', 'r.member_id=c.receiver_id', 'LEFT')
			->join('conversations_message m', 'c.last_message_id=m.message_id', 'LEFT');

		$this->db->where('c.status', 1);
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('c.last_message_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	public function getMessageChatList($room_id){
		$this->db->select('c_m.message_id,c_m.message,c_m.attachment,c_m.sender_id,c_m.sending_date,c_m.offer_id,p.proposal_title,cf.amount,cf.description,cf.delivery_time,cf.order_id,cf.status,cf.sender_id as offer_sender,p.proposal_image')
			->from('conversations_message as c_m')
			->join('conversations_messages_offers as cf', 'c_m.offer_id=cf.offer_id', 'LEFT')
			->join('proposals as p', 'cf.proposal_id=p.proposal_id', 'LEFT');
		$this->db->where('c_m.conversations_id', $room_id);
		
		$result = $this->db->order_by('c_m.message_id', 'ASC')->get()->result();
		return $result;
	}
	

	
	
}


