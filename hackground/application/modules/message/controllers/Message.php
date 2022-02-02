<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('message_model', 'message');
		$this->data['table'] = 'conversations';
		$this->data['primary_key'] = 'conversations_id';
		parent::__construct();
		
		admin_log_check();
	}

	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	
	public function list_record(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = ' Inbox Messages';
		$this->data['second_title'] = 'All Messages Room';
		$this->data['title'] = 'Room';
		$breadcrumb = array(
			array(
				'name' => 'Rooms',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->message->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->message->getList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('list', $this->data);
       
	}
	public function room($room_id=''){
		if(!$room_id){
			show_404(); return;
		}
		$srch = get();
		$srch['room_id'] = $room_id;
		
		$this->data['conversation_details']=$this->db->select('sender_id,receiver_id,conversations_id')->where('conversations_id',$room_id)->from('conversations')->get()->row();
		if(!$this->data['conversation_details']){
			show_404(); return;
		}
		$this->data['conversation_details']->sender=$this->db->select('member_name')->where('member_id',$this->data['conversation_details']->sender_id)->from('member')->get()->row();
		$this->data['conversation_details']->receiver=$this->db->select('member_name')->where('member_id',$this->data['conversation_details']->receiver_id)->from('member')->get()->row();
		
		$this->data['conversation_details']->conversations=$this->message->getMessageChatList($room_id);
		
		
		$this->data['main_title'] = 'View  Conversations of ';
		$this->data['second_title'] = $room_id;
		$this->data['title'] = 'Conversations details';
		
		$breadcrumb = array(
			array(
				'name' => 'Orders',
				'path' => base_url($this->data['curr_controller'].'list_record'),
			),
			array(
				'name' => $room_id,
				'path' => '',
			),
		);
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		//$this->data['list'] = $this->wallet->getTxnDetail($srch, $limit, $offset);
		
		
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('conversations', $this->data);
       
	}
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Test Three';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->proposal->getDetail($id);
			$this->data['title'] = 'Edit Test Three';
		}
		$this->load->view('ajax_page_global', $this->data);
	}
	
	public function add(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->proposal->addRecord($post);
				if(post('add_more') && post('add_more') == '1'){
					$this->api->cmd('reset_form');
				}else{
					$this->api->cmd('reload');
				}
				
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function edit(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->proposal->updateRecord($post, $ID);
				$this->api->cmd('reload');
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function change_status(){
		if(post() && $this->input->is_ajax_request()){
			
			$ID = post('ID');
			$sts = post('status');
			$action_type = post('action_type');
			
			if(is_array($ID)){
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('proposal_status' => $sts));
			}else{
				$upd['data'] = array('proposal_status' => $sts);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
				$send_notification=0;
				$receiver_id=getField('proposal_seller_id', 'proposals', 'proposal_id', $ID);
				$notificationData=array(
				'sender_id'=>0,
				'receiver_id'=>$receiver_id,
				'url'=>'proposals/manage_proposal',
				'content'=>json_encode(array('PID'=>$ID)),
				);
				if($sts==PROPOSAL_ACTIVE){
					$send_notification=1;
					$notificationData['template']='approved';
				}elseif($sts==PROPOSAL_MODIFICATION){
					$send_notification=1;
					$notificationData['template']='modification';
				}elseif($sts==PROPOSAL_DECLINED){
					$send_notification=1;
					$notificationData['template']='declined';
				}
				if($send_notification){
					$this->load->model('notifications/notification_model');
					$this->notification_model->savenotification($notificationData);
				}
				
				
									
				
			}
			
			$this->api->cmd('reload');
			
			/* if($action_type == 'multiple'){
				$this->api->cmd('reload');
			}else{
				
				$html = '';
				if($sts == ACTIVE_STATUS){
					$html = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, '.$ID.', this)"><span class="label label-success">Active</span></a>';
				}else{
					$html = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, '.$ID.', this)"><span class="label label-danger">Inactive</span></a>';
				}
			
			
				$this->api->data('html', $html);
				$this->api->cmd('replace');
			} */
			
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function delete_record($id=''){
		$action_type = post('action_type');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		if($id){
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}else{
					$this->db->where($this->data['primary_key'] ,  $id)->update($this->data['table'],array('status'=>1));
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	
	public function referral(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Proposal Referral Management';
		$this->data['second_title'] = 'All Proposal Referral List';
		$this->data['title'] = 'Proposal Referral';
		$breadcrumb = array(
			array(
				'name' => 'Proposal Referral',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->proposal->getReferralList($srch, $limit, $offset);
		$this->data['list_total'] = $this->proposal->getReferralList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'referral');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('referral', $this->data);
       
	}
	
}





