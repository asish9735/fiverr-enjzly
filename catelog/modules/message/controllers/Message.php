<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends MX_Controller {

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
		}else{
			redirect(get_link('loginURL'));
		}
		loadModel('message_model');
			parent::__construct();
	}
	public function index($receiver_id=''){
		if($receiver_id>0 && $receiver_id!=$this->member_id){
			$wh1=" (sender_id='".$this->member_id."' and receiver_id='".$receiver_id."') or (receiver_id='".$this->member_id."' and sender_id='".$receiver_id."')";
			$checkConversation=$this->db->select('conversations_id')->where($wh1)->from('conversations')->get()->row();
			if($checkConversation){
				$conversation_id=$checkConversation->conversations_id;
			}else{
				$conversation_id=insertTable('conversations',array('sender_id'=>$this->member_id,'receiver_id'=>$receiver_id),true);
			}
			redirect(base_url('message_new').'/index/'.md5($conversation_id));
			//redirect(get_link('MessageBoard').'/'.$conversation_id);
		}else{
			redirect(base_url('message_new'));
			//redirect(get_link('MessageBoard'));
		}
		exit();
	}
	public function checkroom(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			fromVRules('contact_id', 'contact_id', 'required|trim|xss_clean|numeric');
			fromVRules('message_content', 'message', 'required|trim|xss_clean');
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
				$msg['status'] = 'OK';
				$receiver_id=$this->input->post('contact_id');
				$message=strip_tags(post('message_content'));
				if($receiver_id>0){
					$wh1=" (sender_id='".$this->member_id."' and receiver_id='".$receiver_id."') or (receiver_id='".$this->member_id."' and sender_id='".$receiver_id."')";
					$checkConversation=$this->db->select('conversations_id')->where($wh1)->from('conversations')->get()->row();
					if($checkConversation){
						$conversation_id=$checkConversation->conversations_id;
					}else{
						$conversation_id=insertTable('conversations',array('sender_id'=>$this->member_id,'receiver_id'=>$receiver_id),true);
					}
					if($conversation_id){
						$msg['redirect'] = get_link('MessageBoard').'/'.$conversation_id;
					}
					
				}else{
					$msg['redirect'] = get_link('MessageBoard');
				}
			}
			
		}	
		unset($_POST);
	echo json_encode($msg);	
	}
	public function messageboard($room_id='')
	{
		if($room_id){
			redirect(base_url('message_new').'/index/'.md5($room_id));
		}else{
			redirect(base_url('message_new'));
		}
		
		die;
		loadModel('message_model');
		$data=array();
		$conversation_group=array();
		$data['log_member_id']=$log_member_id=$this->member_id;
		$conversation_group_all=$this->message_model->load_conversation($log_member_id);
		if($conversation_group_all){
			$log_member_id_logo=getMemberLogo($log_member_id);
			foreach($conversation_group_all as $i=>$conversation){
				$conversation_group[$i]=$conversation;
				$conversation_group[$i]->my_logo=$log_member_id_logo;
				if($conversation->sender_id==$log_member_id){
					$chatwith=$conversation->receiver_id;
				}else{
					$chatwith=$conversation->sender_id;
				}
				$conversation_group[$i]->chatwith=$chatwith;
				$username=getUserName($chatwith);
				$conversation_group[$i]->chatmember=new stdClass();
				if($username){
					$conversation_group[$i]->chatmember->member_name=$username;
				}else{
					$conversation_group[$i]->chatmember->member_name='Unknown';
				}
				/*$conversation_group[$i]->chatmember=getData(array(
					'select'=>'m.member_name',
					'table'=>'member m',
					'single_row'=>true,
					'where'=>array('m.member_id'=>$chatwith),
					));
				if(empty($conversation_group[$i]->chatmember->member_name)){
					$conversation_group[$i]->chatmember->member_name='Unknown';
				}*/
				$conversation_group[$i]->chatmember->logo=getMemberLogo($chatwith);
			}
		}
		$data['selected_room']=0;
		if($room_id){
			$arr=array(
					'select'=>'c.sender_id,c.receiver_id',
					'table'=>'conversations as c',
					'where'=>array('c.conversations_id'=>$room_id),
					'single_row'=>TRUE
				);
			$message=getData($arr);
			if($message){
				if($message->sender_id==$log_member_id || $message->receiver_id==$log_member_id){
					$data['selected_room']=$room_id;
				}else{
					redirect(get_link('MessageBoard'));
				}
			}
		}	
		$data['conversation_group']=$conversation_group;
		$arr=array(
					'select'=>'p.proposal_id,p.proposal_title,p.proposal_image',
					'table'=>'proposals as p',
					'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'p.proposal_seller_id'=>$this->member_id),
				);
		$data['myproposal']=getData($arr);
		$data['all_delivery_times']=getAllDeliveryTimes();
		$data['load_js']=load_js(array('mycustom.js','emoji.js'));
		$data['load_css']=load_js(array('inbox-style.css','emoji.css'));
		//$data['left_panel']=load_view('inc/freelancer-setting-left',$data,TRUE);
		$templateLayout=array('view'=>'message-board','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function load_part(){
		//checkrequestajax();
		$room_id=post('message_group_id');
		$part=post('part');
		if($room_id){
			$arr=array(
					'select'=>'c.sender_id,c.receiver_id,c.conversations_id',
					'table'=>'conversations as c',
					'where'=>array('c.conversations_id'=>$room_id),
					'single_row'=>TRUE
				);
			$message=getData($arr);
			if($message){
				if($message->sender_id==$this->member_id || $message->receiver_id==$this->member_id){
					if($message->sender_id==$this->member_id){
						$chatwith=$message->receiver_id;
					}else{
						$chatwith=$message->sender_id;
					}
					$data['message']=$message;
					$data['message']->chatwith=$chatwith;
					
					if($part=='head'){
						$data['member_details']=getMemberDetails($chatwith,array('main'=>1));
						$templateLayout=array('view'=>'message-board-header','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
						load_template($templateLayout,$data);
					}elseif($part=='body'){
						$data['member_details']=getMemberDetails($chatwith,array('main'=>1,'member_languages'=>1,'member_address'=>1));
						$wh=" (buyer_id='".$message->receiver_id."' and seller_id='".$message->sender_id."') or (seller_id='".$message->receiver_id."' and buyer_id='".$message->sender_id."')";
						$data['order']=$this->db->where($wh)->where('order_status <>',0)->from('orders')->count_all_results();

						$templateLayout=array('view'=>'message-board-body','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
						load_template($templateLayout,$data);
					}
					
				}
			}
			
		}
	}
	public function load_conversation($room_id=''){
		checkrequestajax();
		$msg=$last_data=array();
		if($room_id){
			$arr=array(
				'select'=>'c_m.message_id,c_m.message,c_m.attachment,c_m.sender_id,c_m.sending_date,c_m.offer_id,p.proposal_title,cf.amount,cf.description,cf.delivery_time,cf.order_id,cf.status,cf.sender_id as offer_sender',
				'table'=>'conversations_message as c_m',
				'join'=>array(
				array('table'=>'conversations_messages_offers as cf','on'=>'c_m.offer_id=cf.offer_id','position'=>'left'),
				array('table'=>'proposals as p','on'=>'cf.proposal_id=p.proposal_id','position'=>'left'),
				),
				'where'=>array('c_m.conversations_id'=>$room_id),
				'order'=>array(array('c_m.message_id','asc')),
			);
			if($this->input->get('from')){
				$arr['where']['c_m.message_id >']=$this->input->get('from');
			}
			$message=getData($arr);
			$msg['status']='OK';
			if($message){
				$last_data=$message[count($message)-1];
				updateTable('conversations_message',array('is_read'=>1),array('conversations_id'=>$room_id,'sender_id <>'=>$this->member_id));
			}
			$msg['last_data']=$last_data;
			$msg['conversation']=$message;
			
		}
		echo json_encode($msg);
	}
	public function typestatus(){
		checkrequestajax();
		$mode=post('mode');
		$room_id=post('message_group_id');
		$status=post('status');
		if($room_id){
			$this->load->helper('file');
			if($mode=='update'){
			$filename=ABS_USERUPLOAD_PATH."ECnote/".$room_id.'-'.$this->member_id.".room";
			if ( !write_file($filename, $status, 'w')){
			 	echo 'Unable to write the file';
			}
			echo 1;
			}elseif($mode=='receive'){
				$sender_id=post('seller_id');
				$filename=ABS_USERUPLOAD_PATH."ECnote/".$room_id.'-'.$sender_id.".room";
				if(!file_exists($filename)){
					echo '';
				}else{
					echo file_get_contents($filename);
				}
			}
		}
		
	}
	public function sendmessage(){
		checkrequestajax();
		$room_id=post('single_message_id');
		if($room_id){
			$arr=array(
					'select'=>'c.sender_id,c.receiver_id,c.conversations_id',
					'table'=>'conversations as c',
					'where'=>array('c.conversations_id'=>$room_id),
					'single_row'=>TRUE
				);
			$groupData=getData($arr);
			if($groupData){
				if($groupData->sender_id==$this->member_id || $groupData->receiver_id==$this->member_id){
					$message=$this->input->post('message',TRUE);
					if(trim($message)!=''){
						$conversations_message=array(
						'conversations_id'=>$groupData->conversations_id,
						'sender_id'=>$this->member_id,
						'sending_date'=>date('Y-m-d H:i:s'),
						'message'=>$message,
						'is_read'=>0,
						);
						if($this->input->post('attachment')){
						$attachment=post('attachment');
						$file_data=json_decode($attachment);
						if($file_data){
								if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
									rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."message-files/".$file_data->file_name);	
									$conversations_message['attachment']=$file_data->file_name;
								}
							}
						}
						$message_id=insertTable('conversations_message',$conversations_message,TRUE);
						if($message_id){
							updateTable('conversations',array('last_message_id'=>$message_id,'status'=>1),array('conversations_id'=>$groupData->conversations_id));
							loadModel('notifications/notification_model');
							if($groupData->sender_id==$this->member_id){
								$receiver_id=$groupData->receiver_id;
							}else{
								$receiver_id=$groupData->sender_id;
							}
							$this->notification_model->insert_message_file($receiver_id,$message_id);
							if(!is_online($receiver_id)){
								$SENDER_NAME=getUserName($this->member_id);
								$RECEIVER_NAME=getUserName($receiver_id);
								$RECEIVER_email=getFieldData('member_email','member','member_id',$receiver_id);
								$url=get_link('MessageBoard').'/'.$groupData->conversations_id;
								$template='inbox-message';
								$data_parse=array(
								'SENDER_NAME'=>$SENDER_NAME,
								'RECEIVER_NAME'=>$RECEIVER_NAME,
								'MESSAGE'=>$message,
								'MESSAGE_URL'=>$url,
								);
								SendMail('',$RECEIVER_email,$template,$data_parse);	
							}
							echo 1;
						}
					}
					
				}
			}
		}
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
							$message_id=insertTable('conversations_message',$conversations_message,TRUE);
							if($message_id){
								updateTable('conversations',array('last_message_id'=>$message_id),array('conversations_id'=>$conversation_id));
								loadModel('notifications/notification_model');
								$this->notification_model->insert_message_file($receiver_id,$message_id);
								
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
	public function acceptofferpayment(){
		checkrequestajax();
		$data=array();
		$room_id=post('single_message_id');
		$offer_id=post('offer_id');
		$arr=array(
			'select'=>'o.amount,o.proposal_id,o.offer_id,p.proposal_title,o.delivery_time,o.description',
			'table'=>'conversations_messages_offers o',
			'join'=>array(
			array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','position'=>'left')
			),
			'where'=>array('o.offer_id'=>$offer_id,'o.receiver_id'=>$this->member_id),
			'single_row'=>true,
		);
		$data['offer_details']=getData($arr);
		$data['member_details']=getMemberDetails($this->member_id);
		
		$templateLayout=array('view'=>'offer-payment','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function saveAction($room_id){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			$action=post('action');
			$roomdetails=getData(array(
						'select'=>'c.conversations_id,c.sender_id,c.receiver_id',
						'table'=>'conversations as c',
						'where'=>array('c.conversations_id'=>$room_id),
						'single_row'=>TRUE
					));
			if($roomdetails){
				$conversations_id=$roomdetails->conversations_id;
				if(in_array($this->member_id,array($roomdetails->sender_id,$roomdetails->receiver_id))){
					if($action=='submit_report'){
						fromVRules('additional_information', 'message', 'required|trim|xss_clean');
						fromVRules('reason', 'reason', 'required|trim|xss_clean');
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
							if($i==0){
								$reports=array(
									'reporter_id'=>$this->member_id,
									'content_id'=>$conversations_id,
									'content_type'=>'message',
									'reason'=>post('reason'),
									'additional_information'=>post('additional_information'),
								);
								$reports_id=insertTable('reports',$reports,TRUE);
								if($reports_id){
									$msg['message'] = 'Your Report Has Been Successfully Submited';
								}
								$msg['status'] = 'OK';
								//$msg['redirect'] = get_link('MessageBoard').'/'.$conversations_id;
							}
						}
					}		
				}	
			}
		}
	unset($_POST);
	echo json_encode($msg);
	}
	public function messagelist(){
		$wh1=" (c.sender_id='".$this->member_id."' or c.receiver_id='".$this->member_id."')";
		
		$this->db->select('c.conversations_id,c.sender_id,c.receiver_id,c.last_message_id,c_m.message,c_m.sending_date,c_m.is_read,c_m.offer_id,c_m.sender_id as message_sender');
		$this->db->from('conversations as c');
		$this->db->join('conversations_message as c_m','c.last_message_id=c_m.message_id','left');
		/*$this->db->join('member as m','c_m.sender_id=m.member_id','left');*/
		$this->db->where($wh1);
		$this->db->where('c.status',1);
		$data['message']=$this->db->limit(4)->order_by('c.last_message_id','desc')->order_by('c.conversations_id','desc')->group_by('c.conversations_id')->get()->result();
		
		$data['message_count']=$this->db->where($wh1)->where('c.status',1)->from('conversations as c')->count_all_results();
		$data['login_id']=$this->member_id;
		$templateLayout=array('view'=>'message-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
}
?>