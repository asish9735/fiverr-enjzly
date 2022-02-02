<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_new extends MX_Controller {

	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='C';
		if($this->loggedUser){
			$this->member_id=$this->loggedUser['MID'];	
			$this->organization_id=$this->loggedUser['OID'];
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->profile_connection_id=$this->loggedUser['LAST_PCI'];
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->username=$this->loggedUser['UNAME'];	
			// $this->output->enable_profiler(TRUE);
			
		}elseif($this->router->fetch_method()=='update_service'){

		}else{
			redirect(get_link('loginURL'));
		}
		loadModel('message_model');
			parent::__construct();
	}
	public function index($selected_conversation_id=''){
		
		$data['login_member'] = $this->message_model->getMessageUser($this->member_id);
		if($selected_conversation_id){
			$data['active_chat'] = $this->message_model->getConversationUserById($selected_conversation_id, $this->member_id);
		}else{
			$data['active_chat'] = null;
		}
		$arr=array(
			'select'=>'p.proposal_id,p.proposal_title,p.proposal_image',
			'table'=>'proposals as p',
			'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'p.proposal_seller_id'=>$this->member_id),
		);
		$data['myproposal']=getData($arr);
		$data['all_delivery_times']=getAllDeliveryTimes();
		$templateLayout=array('view'=>'message','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function load_chat(){
		$json['status'] = 1;
		$json['chat_list'] = $this->message_model->getChatList($this->member_id);
		$this->_reset_message_file($this->member_id);
		echo json_encode($json);
	}
	public function chat_list_htm(){
		$data=array();
		$get = get();

		$limit = !empty($get['per_page']) ? $get['per_page'] : 0;
		$offset = 10;
		$next_limit = $limit + $offset;
		
		
		$data['chat_list'] = $this->message_model->getChatList($this->member_id,$limit, $offset);
		$data['chat_list_count'] = $this->message_model->getChatList($this->member_id,'','', FALSE);
		
		$json['chat_list'] = $data['chat_list'];
		$json['chat_list_count'] = $data['chat_list_count'];
		
		if($data['chat_list_count'] > $next_limit){
			$json['next'] = base_url('message_new/chat_list_htm?per_page='.$next_limit);
		}else{
			$json['next'] = null;
		}
		$templateLayout=array('view'=>'chat_list_htm','type'=>'ajax','buffer'=>TRUE,'theme'=>'');
		$json['html'] = load_template($templateLayout,$data);
		//$json['html'] = $this->load->view('chat_list_htm',$data, TRUE);
		
		$json['status'] = 1;
		
		echo json_encode($json);
	}
	private function _reset_message_file($member_id=''){
		if(!is_dir(ABS_USERUPLOAD_PATH.'updates')){
			mkdir(ABS_USERUPLOAD_PATH.'updates');
		}
		$u_file =ABS_USERUPLOAD_PATH.'updates/user_'.$member_id.'.update';
		if(file_exists($u_file)){
			$data = file_get_contents($u_file);
			$data = (array) json_decode($data);
			$data['new_message'] = 0;
		}else{
			$data['new_message'] = 0;
		}
		
		
		file_put_contents($u_file, json_encode($data));
	
	}
	public function online_uer_up(){
		$is_process=false;
		if(!$this->session->userdata('lastupdate')){
			$is_process=true;
			$this->session->set_userdata('lastupdate',time());
		}else{
			$lasttime=$this->session->userdata('lastupdate');
			if(time() > $lasttime+30){
				$is_process=true;	
				$this->session->set_userdata('lastupdate',time());
			}
		}
		if($is_process){
			$newtime=date('Y-m-d H:i:s',strtotime('-30 second'));
			$wh=" (last_active < '".$newtime."' or user_id='".$this->member_id."')";
			$this->db->where($wh)->delete('online_user');
			$this->db->insert('online_user',array('user_id'=>$this->member_id,'last_active'=>date('y-m-d H:i:s')));
		}
	}
	public function update_service(){
		if($this->loggedUser){
			$this->online_uer_up();
			if(!is_dir(ABS_USERUPLOAD_PATH.'updates')){
				mkdir(ABS_USERUPLOAD_PATH.'updates');
			}
			$member_id = $this->member_id;
			$u_file = ABS_USERUPLOAD_PATH.'updates/user_'.$member_id.'.update'; 
			if(file_exists($u_file)){
				$content = file_get_contents($u_file);
			}else{
				$content = '0';
			}
		}else{
			$content = '0';
		}
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		//echo "id: ".time() . PHP_EOL;
		//echo 'event: update'. PHP_EOL;
		echo "data: $content " . PHP_EOL;
		echo PHP_EOL;
		flush();
		
	}
	public function load_chat_message($conversation_id=''){
		$login_member = $this->member_id;
		$json['status'] = 1;
		$limit = get('limit') > 0 ? get('limit') : 0;
		$offset = 10;
		$json['chat_message'] = $this->message_model->getChatMessage($conversation_id, $login_member, $limit, $offset);
		$json['chat_message_total'] = $this->message_model->getChatMessage($conversation_id, $login_member, $limit, $offset, FALSE);
		$json['next_limit'] = ($limit+$offset);
		echo json_encode($json);
	}
	public function load_attachments($conversation_id=''){
		$login_member = $this->member_id;
		$json['status'] = 1;
		$limit = get('limit') > 0 ? get('limit') : 0;
		$offset = 10;
		$json['attachments'] = $this->message_model->getConversationAttachments($conversation_id, $limit, $offset);
		$json['attachment_total'] = $this->message_model->getConversationAttachments($conversation_id, $limit, $offset, FALSE);
		$json['next_limit'] = ($limit+$offset);
		echo json_encode($json);
	}
	public function send_msg(){
		$json['status'] = 1;
		if($this->input->post() && $this->input->is_ajax_request()){
			$message = post('message');
			$reply_to = post('reply_to');
			$conversations_id = post('conversations_id');
			$message = array(
				'sender_id' => $this->member_id,
				'conversations_id' => $conversations_id,
				'message' => $message,
				'sending_date' => date('Y-m-d H:i:s'),
			);
			if($reply_to){
				$message['reply_to']=$reply_to;
			}
			$json['last_message_id'] = $this->message_model->send_message($message);
			$message['message_id'] = $json['last_message_id'];
			$message['parent'] = $this->message_model->get_parent_msg($reply_to);
			$json['message_data'] = $message;
			
			
		}
		echo json_encode($json);
	}
	
	public function send_attachment(){
		$json['status'] = 1;
		if($this->input->post() && !empty($_FILES['file']['name'])){
			$config['upload_path'] = ABS_USERUPLOAD_PATH.'message-files';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xl|xls|zip|txt';
			$config['encrypt_name']  = TRUE;
			if(!is_dir($config['upload_path'])){
				mkdir($config['upload_path']);
			}
			$this->load->library('upload', $config);
			
				if ( ! $this->upload->do_upload('file')){
					$json['error'] = 'Error: '.$this->upload->display_errors();
					$json['status'] = 0;
                }else {
					$upload_data = $this->upload->data();
					$attachment = array(
						'file_name' => $upload_data['file_name'],
						'file_url' => URL_USERUPLOAD.'message-files/'.$upload_data['file_name'],
						'org_file_name' => $upload_data['orig_name'],
						'is_image' => $upload_data['is_image'],
						'file_size' => $upload_data['file_size'],
						'file_ext' => $upload_data['file_ext'],
						
					);
					$conversations_id = post('conversations_id');
					$reply_to = post('reply_to');
					$message = array(
						'sender_id' => $this->member_id,
						'conversations_id' => $conversations_id,
						'message' => '',
						'attachment' => json_encode($attachment),
						'sending_date' => date('Y-m-d H:i:s'),
						
					);
					if($reply_to){
						$message['reply_to']=$reply_to;
					}
					$json['last_message_id'] = $this->message_model->send_message($message);
					$json['message_data'] = $message;
			        $message['parent'] = $this->message_model->get_parent_msg($reply_to);
					$json['message'] = $message;
					
					$json['attachment'] = $attachment;
			
				}
				
		}
		echo json_encode($json);
	}
	public function load_new_message($conversation_id=''){
		$member_id = $this->member_id;
		$json = array();
		$json['status'] = 1;
		$json['new_message'] = $this->message_model->getNewMessage($conversation_id, $member_id);
		
		$this->_reset_message_file($member_id);
		
		echo json_encode($json);
	}
	public function reset_msg_seen(){
		$member_id = $this->member_id;
		if(!is_dir(ABS_USERUPLOAD_PATH.'updates')){
			mkdir(ABS_USERUPLOAD_PATH.'updates');
		}
		$u_file =ABS_USERUPLOAD_PATH.'updates/user_'.$member_id.'.update';
		if(file_exists($u_file)){
			$data = file_get_contents($u_file);
			$data = (array) json_decode($data);
			$data['msg_seen_update'] = array();
		}else{
			$data['msg_seen_update'] = array();
		}
		
		
		file_put_contents($u_file, json_encode($data));
	}
	public function star_toggle(){
        $id = post('ID');
        $type = post('type'); // message
        $user_id = $this->member_id;
        $table = 'conversations_message_favorite';

        $cond = [
            'member_id' => $user_id,
            'message_id' => $id
        ];
        $check = $this->db->where($cond)->count_all_results($table);
        if($check > 0){
            $this->db->where($cond)->delete($table);
            $action = 'removed';
        }else{
            $this->db->insert($table, $cond);
            $action = 'added';
        }
		$json['action']=$action;
		$json['status']=1;
		echo json_encode($json);
    }
	public function delete_msg($msg_id){
        $this->db->where(['sender_id' => $this->member_id, 'message_id' => $msg_id])->update('conversations_message', array('is_deleted' => date('Y-m-d H:i:s')));
        echo json_encode(array(
            'status' => 1,
            'deleted' => date('Y-m-d H:i:s'),
            'msg_txt' => 'This message is deleted ('.date('d M, Y h:i A').')'
		));
        die;
    }
	public function edit_ajax(){
		$edit_date=date('Y-m-d H:i:s');
        $ID = post('ID');
        $message = $this->input->post('message');
		$message_org=getFieldData('message','conversations_message','','',array('sender_id' =>$this->member_id, 'message_id' => $ID));
        $up=$this->db->where(['sender_id' =>$this->member_id, 'message_id' => $ID])->update('conversations_message', ['is_edited' => $edit_date, 'message' => $message]);
		if($up){
			$this->db->insert('conversations_message_edited',array('mesage_id'=>$ID,'message_org'=>$message_org,'edit_date'=>$edit_date));
		}
        echo json_encode([
            'status' => 1,
            'edited' =>$edit_date,
            'edited_display_date' => date('d M, Y h:i A',strtotime($edit_date)),
            'msg_txt' =>  nl2br($message)
        ]);
        die;
    }
	public function sendofferpopupajax(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			fromVRules('offer_proposal_id', 'proposal_id', 'required|trim|xss_clean|numeric');
			fromVRules('offer_receiver_id', 'receiver_id', 'required|trim|xss_clean|numeric');
			fromVRules('offer_delivery_time', 'delivery_time', 'required|trim|xss_clean|numeric');
			fromVRules('offer_description', 'description', 'required|trim|xss_clean');
			fromVRules('offer_amount', 'amount', 'required|trim|xss_clean|greater_than[4]');
			if (isVRulePassed() == FALSE){
				$error=isVRuleError();
				if($error){
					foreach($error as $key=>$val){
						$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = $key;
						$msg['errors'][$i]['message'] = $val;
		   				$i++;
					}
				}
			}else{
				$proposal_id=post('offer_proposal_id');
				$receiver_id=post('offer_receiver_id');
				$proposaldetails=getData(array(
						'select'=>'p.proposal_id',
						'table'=>'proposals as p',
						'where'=>array('p.proposal_id'=>$proposal_id,'proposal_seller_id'=>$this->member_id,'p.proposal_status'=>PROPOSAL_ACTIVE),
						'single_row'=>TRUE
					));
				if($proposaldetails){
					$wh1=" (sender_id='".$this->member_id."' and receiver_id='".$receiver_id."') or (receiver_id='".$this->member_id."' and sender_id='".$receiver_id."')";
					$checkConversation=$this->db->select('conversations_id')->where($wh1)->from('conversations')->get()->row();
					if($checkConversation){
						$conversation_id=$checkConversation->conversations_id;
					}else{
						$conversation_id=insertTable('conversations',array('sender_id'=>$this->member_id,'receiver_id'=>$receiver_id),true);
					}
					if($conversation_id){
						$conversations_messages_offers=array(
						'sender_id'=>$this->member_id,
						'receiver_id'=>$receiver_id,
						'proposal_id'=>$proposaldetails->proposal_id,
						'description'=>post('offer_description'),
						'delivery_time'=>post('offer_delivery_time'),
						'amount'=>post('offer_amount'),
						'status'=>0,
						);
						$offer_id=insertTable('conversations_messages_offers',$conversations_messages_offers,TRUE);
						if($offer_id){

							$conversations_message=array(
								'conversations_id'=>$conversation_id,
								'sender_id'=>$this->member_id,
								'sending_date'=>date('Y-m-d H:i:s'),
								'message'=>'',
								'offer_id'=>$offer_id,
								'is_read'=>0,
							);
							$message_id=$this->message_model->send_message($conversations_message);

							//$message_id=insertTable('conversations_message',$conversations_message,TRUE);
							if($message_id){
								//updateTable('conversations',array('last_message_id'=>$message_id),array('conversations_id'=>$conversation_id));
								
								//loadModel('notifications/notification_model');
								//$this->notification_model->insert_message_file($receiver_id,$message_id);
								
								$SENDER_NAME=getUserName($this->member_id);
								$RECEIVER_NAME=getUserName($receiver_id);
								$url=get_link('MessageBoard').'/'.$conversation_id;
								$template='inbox-message-offer';
								$data_parse=array(
								'SENDER_NAME'=>$SENDER_NAME,
								'RECEIVER_NAME'=>$RECEIVER_NAME,
								'MESSAGE'=>post('offer_description'),
								'MESSAGE_URL'=>$url,
								);
								SendMail('',$dataPost['email'],$template,$data_parse);
								
								$msg['status'] = 'OK';

								$message = array(
									'sender_id' => $this->member_id,
									'conversations_id' => $conversation_id,
									'message' => '',
									'sending_date' => date('Y-m-d H:i:s'),
								);
								$message['message_id'] = $message_id;
								$message['parent'] =0;
								$message['offer_id'] =$offer_id;
								$message['offer_data'] =$this->message_model->OfferDetails($offer_id);
								$msg['message_data'] = $message;

							}
						}
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'proposalid';
					$msg['errors'][$i]['message'] = 'invalid in request';
	   				$i++;
				}
				if($i==0){
				}
			}
		}
	unset($_POST);
	echo json_encode($msg);
	}
}
?>