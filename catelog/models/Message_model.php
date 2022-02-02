<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function getMessageUser($member_id=''){
		$user = new StdClass();
		$user->avatar = getMemberLogo($member_id);
		$user->name = getFieldData('member_name', 'member', 'member_id', $member_id);
		
		$user->member_id = $member_id;
		$user->profile_url=get_link('viewprofileURL').''.getUserName($member_id);
		$user->online_status = (bool) $this->db->where('user_id', $member_id)->count_all_results('online_user');
		
		return $user;
	}
	public function getChatList($member_id='', $limit=0, $offset=100, $for_list=TRUE){
		$this->db->select("c.conversations_id,c_m.sender_id,c_m.message_id,c_m.sending_date,c_m.message,c_m.attachment,c_m.is_read,c.sender_id as message_sender,c.receiver_id  as message_receiver,c_m.offer_id")
				->from('conversations c')
				->join('conversations_message c_m', 'c_m.message_id=c.last_message_id', 'LEFT');
		$wh="(c.sender_id='".$member_id."' or c.receiver_id='".$member_id."')";
		$this->db->where($wh);
		$this->db->group_by('c.conversations_id');
		$this->db->order_by('c.last_message_id', 'DESC');
		$return_result = array();
		if( $for_list){
			$this->db->limit($offset, $limit);
			$result = $this->db->get()->result();
			//echo $this->db->last_query();
			if($result){
				foreach($result as $k => $v){
					$v->chat_user_id=($v->message_sender==$member_id ? $v->message_receiver:$v->message_sender);
					$return_result[$k] = $this->getMessageUser($v->chat_user_id);
					$return_result[$k]->message = !empty($v->message) ? $v->message : (!empty($v->attachment) ? '<i class="icon-material-outline-attach-file"></i> Attachment' : '');
					$return_result[$k]->attachment = $v->attachment;
					$return_result[$k]->sending_date = $v->sending_date;
					$return_result[$k]->sender_id = $v->sender_id;
					$return_result[$k]->conversations_id = $v->conversations_id;
					$return_result[$k]->message_id = $v->message_id;
					$return_result[$k]->last_seen_msg = $this->getLastSeenMsg($v->chat_user_id, $v->conversations_id,($v->message_sender==$v->chat_user_id ?'sender':'receiver'));
					$return_result[$k]->unread_msg_count = $this->getUnreadMsgCount($member_id, $v->conversations_id);
					$return_result[$k]->time_ago = get_time_ago($v->sending_date);
					$return_result[$k]->offer_id = $v->offer_id;
					if($v->offer_id){
						$return_result[$k]->message ='<i class="icon-material-outline-local-offer"></i> Sent you an offer';
					}
					$return_result[$k]->offer_data =$this->OfferDetails($v->offer_id);
				}
			}
		}else{
			$return_result = $this->db->get()->num_rows();
		}
		
		
		return $return_result;
		
	}
	public function OfferDetails($offer_id){
		$result=$this->db->select('p.proposal_title,cf.amount,cf.description,cf.delivery_time,cf.order_id,cf.status,cf.sender_id as offer_sender')
		->from('conversations_messages_offers as cf')
		->join('proposals as p','cf.proposal_id=p.proposal_id','left')
		->where('cf.offer_id',$offer_id)->get()->row();
		return $result;
	}
	public function getLastSeenMsg($user_id, $conversations_id='',$type=''){
		if($type=='sender'){
			$result = $this->db->select('sender_last_seen_msg as last_seen_msg')
			->from('conversations')
			->where(array(
				'sender_id' => $user_id,
				'conversations_id' => $conversations_id,
			))
			->get()
			->row();
		}else{
			$result = $this->db->select('receiver_last_seen_msg as last_seen_msg')
			->from('conversations')
			->where(array(
				'receiver_id' => $user_id,
				'conversations_id' => $conversations_id,
			))
			->get()
			->row();
		}
		
		if($result){
			return $result->last_seen_msg;
		}else{
			return '0';
		}
	}
	public function getUnreadMsgCount($user_id='', $conversations_id=''){
		$result=$this->db->from('conversations_message')->where('conversations_id', $conversations_id)->where('sender_id <>', $user_id)->where('is_read',0)->count_all_results();
		return $result;
		
	}
	public function getChatMessage($conversation_id='', $login_member='', $limit=0, $offset=30, $for_list=TRUE){

		$this->db->select("c_m.*,c_m_f.message_id as starred")
			->from('conversations_message c_m')
			->join('conversations_message_favorite c_m_f', "(c_m.message_id=c_m_f.message_id and c_m_f.member_id='".$login_member."')", 'LEFT');
		$this->db->where('c_m.conversations_id', $conversation_id);
		$this->db->order_by('c_m.message_id', 'DESC');
		if($for_list){
			$this->db->limit($offset, $limit);
			$result = $this->db->get()->result();
			
			if(count($result) > 0){
				$this->markAsRead($conversation_id, $login_member);
				$this->updateSeenStatus($login_member, $conversation_id);

				foreach($result as $k => $v){
					$result[$k]->message = nl2br($v->message);
                    if($v->reply_to > 0){
                        $result[$k]->parent = $this->get_parent_msg($v->reply_to);
                    }
                    if(!empty($v->is_deleted)){
                        $result[$k]->message = 'This message is deleted ('.date('d M, Y h:i A', strtotime($v->is_deleted)).')';
                        $result[$k]->attachment = null;
                    }
                    if(!empty($v->is_edited)){
                        $result[$k]->edited_display_date = date('d M, Y h:i A', strtotime($v->is_edited));
                    }else{
                        $result[$k]->edited_display_date = null;
                    }
					$result[$k]->offer_data =$this->OfferDetails($v->offer_id);
                }
			}
			
		}else{
			$result =  $this->db->get()->num_rows();
		}
		
	
		return $result;
	}
	public function getConversationAttachments($conversation_id='', $limit=0, $offset=30, $for_list=TRUE){
		$this->db->select("c_m.*")
			->from('conversations_message c_m');
		$this->db->where('c_m.conversations_id', $conversation_id);
		$this->db->where('c_m.attachment <>', NULL);
		$this->db->order_by('c_m.message_id', 'DESC');
		if($for_list){
			$this->db->limit($offset, $limit);
			$result = $this->db->get()->result();
			if($result){
				foreach($result as $k => $v){
					$result[$k]->attachment = json_decode($v->attachment);
				}
			}
		}else{
			$result =  $this->db->get()->num_rows();
		}
		
		return $result;
	}
	public function get_parent_msg($msg_id){
        $result = $this->db->select("c_m.*")
                ->from('conversations_message c_m')
                ->where('c_m.message_id', $msg_id)
                ->get()->row();
        if(!empty($result->deleted)){
            $result->message = 'This message is deleted ('.date('d M, Y h:i A', strtotime($result->deleted)).')';
            $result->attachment = null;
        }
        return $result;
    }
	public function markAsRead($conversation_id='', $login_member=''){

		$this->db->where('conversations_id', $conversation_id);
		$this->db->where('sender_id <>', $login_member);
		$this->db->update('conversations_message', array('is_read' => 1));
		$users = $this->db->select('sender_id,receiver_id')->from('conversations')->where('conversations_id', $conversation_id)->get()->row();
		if($users){
			$last_message_id = $this->getLastMessageId($conversation_id);
			if($users->sender_id==$login_member){
				$udate=array('sender_last_seen_msg' => $last_message_id);
			}else{
				$udate=array('receiver_last_seen_msg' => $last_message_id);
			}
			$this->db->where(array('conversations_id' =>  $conversation_id))->update('conversations', $udate);
		}
	}
	public function getLastMessageId($conversation_id=''){
		$this->db->select_max('message_id');
		$this->db->from('conversations_message');
		$result = $this->db->where('conversations_id',$conversation_id)->get()->row();
		if($result){
			$last_msg_id = $result->message_id;
		}else{
			$last_msg_id = 0;
		}
		
		return $last_msg_id;
	}
	public function updateSeenStatus($login_user='', $conversation_id=''){
		/*
			Update the user update file
		*/
		if(!is_dir(ABS_USERUPLOAD_PATH.'updates')){
			mkdir(ABS_USERUPLOAD_PATH.'updates');
		}
		
		$conversation_users = array_diff($this->conversation_user($conversation_id), array($login_user));
		$last_message_id = $this->getLastMessageId($conversation_id);
		if($conversation_users){
			foreach($conversation_users as $k => $member_id){
				$u_file = ABS_USERUPLOAD_PATH.'updates/user_'.$member_id.'.update'; 
				if(!file_exists($u_file)){
					$data['msg_seen_update'] = array(
						'conversations_id' => $conversation_id,
						'last_message_id' => $last_message_id,
					); 
					file_put_contents($u_file, json_encode($data));
				}else{
					$data = file_get_contents($u_file);
					
					$data = (array) json_decode($data);
					$data['msg_seen_update'] = array(
						'conversations_id' => $conversation_id,
						'last_message_id' => $last_message_id,
					); 
					file_put_contents($u_file, json_encode($data));
				}
			}
		}
		
		
	}
	public function send_message($msg=array()){
		$this->db->insert('conversations_message', $msg);
		$last_message_id = $this->db->insert_id();
		$this->db->where('conversations_id', $msg['conversations_id'])->update('conversations', array('last_message_id' => $last_message_id));
		$this->markAsRead($msg['conversations_id'], $msg['sender_id']);
		$conversation_user = $this->message_model->conversation_user($msg['conversations_id']);
		if($conversation_user){
			foreach($conversation_user as $k => $member_id){
				if($member_id == $msg['sender_id']){
					continue;
				}
				$this->_update_file_msg($member_id);
				loadModel('notifications/notification_model');
				$this->notification_model->insert_message_file($member_id,$last_message_id);
			}
		}

		return $last_message_id;
	}
	private function _update_file_msg($ukey=''){
		/*
			Update the user update file
		*/
		if(!is_dir(ABS_USERUPLOAD_PATH.'updates')){
			mkdir(ABS_USERUPLOAD_PATH.'updates');
		}
		$u_file = ABS_USERUPLOAD_PATH.'updates/user_'.$ukey.'.update'; 
		if(!file_exists($u_file)){
			$data['new_message'] = 1; 
			file_put_contents($u_file, json_encode($data));
		}else{
			$data = file_get_contents($u_file);
			
			$data = (array) json_decode($data);
			$data['new_message'] = $data['new_message'] + 1;
			file_put_contents($u_file, json_encode($data));
		}
	}
	public function conversation_user($conversation_id=''){
		$users = $this->db->select('sender_id,receiver_id')->from('conversations')->where('conversations_id', $conversation_id)->get()->row();
		if($users){
			$users_array = array($users->sender_id,$users->receiver_id);
		}else{
			$users_array = array();
		}
		
		return $users_array;
	}
	public function getConversationUserById($conversation_id_enc='', $member_id=''){
		$this->db->select("c.conversations_id,c_m.sender_id,c_m.message_id,c_m.sending_date,c_m.message,c_m.attachment,c_m.is_read,c.sender_id as message_sender,c.receiver_id  as message_receiver,c_m.offer_id")
		->from('conversations c')
		->join('conversations_message c_m', 'c_m.message_id=c.last_message_id', 'LEFT');
		$wh="(c.sender_id='".$member_id."' or c.receiver_id='".$member_id."')";
		$this->db->where($wh);
		$this->db->where('md5(c.conversations_id)', $conversation_id_enc);
		$this->db->group_by('c.conversations_id');
		$this->db->order_by('c.last_message_id', 'DESC');
		
		$result = $this->db->get()->row();
		if($result){
			$result->chat_user_id=($result->message_sender==$member_id ? $result->message_receiver:$result->message_sender);
			$return_result = $this->getMessageUser($result->chat_user_id);
			$return_result->message = $result->message;
			$return_result->attachment = $result->attachment;
			$return_result->sending_date = $result->sending_date;
			$return_result->sender_id = $result->sender_id;
			$return_result->conversations_id = $result->conversations_id;
			$return_result->message_id = $result->message_id;
			$return_result->last_seen_msg = $this->getLastSeenMsg($result->chat_user_id, $result->conversations_id,($result->message_sender==$result->chat_user_id ?'sender':'receiver'));
			$return_result->unread_msg_count = $this->getUnreadMsgCount($member_id, $result->conversations_id);
			$return_result->time_ago = get_time_ago($result->sending_date);
			$return_result->offer_id = $result->offer_id;
			$return_result->offer_data =$this->OfferDetails($result->offer_id);
			return $return_result;
		}
		return null;
	}
	public function getNewMessage($conversation_id='', $user_id=''){
		$last_seen_msg=0;
		$users = $this->db->select('sender_id,receiver_id,sender_last_seen_msg,receiver_last_seen_msg')->from('conversations')->where('conversations_id', $conversation_id)->get()->row();
		if($users){
			if($user_id==$users->sender_id){
				$last_seen_msg=$users->sender_last_seen_msg;
			}else{
				$last_seen_msg=$users->receiver_last_seen_msg;
			}
		}
		

		$this->db->select("c_m.*,".$last_seen_msg." as last_seen_msg")
			->from('conversations_message c_m');
		$this->db->where('c_m.conversations_id', $conversation_id);
		$this->db->where('c_m.sender_id <>', $user_id);
		$this->db->where("c_m.message_id > ", $last_seen_msg);
		$result = $this->db->get()->result();
		if($result){
			foreach($result as $k => $v){
				$v->offer_data =$this->OfferDetails($v->offer_id);
				$result[$k]=$v;
			}
			
		}
		if(count($result) > 0){
			$this->markAsRead($conversation_id, $user_id);
			$this->updateSeenStatus($user_id, $conversation_id);
			
		}
		
		
		return $result;
	}
}

