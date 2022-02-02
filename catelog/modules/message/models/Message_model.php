<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function load_conversation($member_id){
		$wh1=" (c.sender_id='".$member_id."' or c.receiver_id='".$member_id."')";
		
		$this->db->select('c.conversations_id,c.sender_id,c.receiver_id,c.last_message_id,c_m.message,c_m.sending_date,c_m.is_read,c_m.offer_id,c_m.sender_id as message_sender');
		$this->db->from('conversations as c');
		$this->db->join('conversations_message as c_m','c.last_message_id=c_m.message_id','left');
		$this->db->where($wh1);
		$this->db->where('c.status',1);
		return $this->db->order_by('c.last_message_id','desc')->order_by('c.conversations_id','desc')->group_by('c.conversations_id')->get()->result();
	}
}

