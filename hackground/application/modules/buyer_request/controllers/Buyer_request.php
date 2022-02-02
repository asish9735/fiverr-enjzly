<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buyer_request extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('buyer_request_model', 'buyer_request');
		$this->data['table'] = 'buyer_requests';
		$this->data['primary_key'] = 'request_id';
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
		$this->data['main_title'] = 'Buyer Request Management';
		$this->data['second_title'] = 'All Buyer Request List';
		$this->data['title'] = 'Buyer Request';
		$breadcrumb = array(
			array(
				'name' => 'Buyer Request',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->buyer_request->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->buyer_request->getList($srch, $limit, $offset, FALSE);
		
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
			$this->data['detail'] = $this->buyer_request->getDetail($id);
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
				$insert = $this->buyer_request->addRecord($post);
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
				$update = $this->buyer_request->updateRecord($post, $ID);
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
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('request_status' => $sts));
			}else{
				$upd['data'] = array('request_status' => $sts);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
				
				$member_id=getField('seller_id','buyer_requests','request_id',$ID);
				$RECEIVER_EMAIL=getField('member_email','member','member_id',$member_id);
				$data_parse=array(
				'BUYER_NAME'=>getField('member_name','member','member_id',$member_id),
				'REQUEST_URL'=>URL.'requests/viewoffer/'.$ID,
				);
				if($sts==REQUEST_ACTIVE){
					$template='request-approved-by-admin';
				}elseif($sts==REQUEST_UNAPPROVED){
					$template='request-declined-by-admin';
				}elseif($sts==REQUEST_PAUSED){
					$template='request-paused-by-admin';
				}
				SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
				
				
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
			$this->test_three->deleteRecord($id);
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}else{
					$this->db->where($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	
	public function viewoffer($request_id=''){
		$srch = get();
		$srch['request_id'] = $request_id;
		$this->data['requestDetails']=$this->buyer_request->getDetail($request_id);
		$this->data['main_title'] = 'Request details of ';
		$this->data['second_title'] = $this->data['requestDetails']['request_title'];
		$this->data['title'] = 'Offer List';
		$breadcrumb = array(
			array(
				'name' => 'Request',
				'path' => base_url($this->data['curr_controller'].'list_record'),
			),
			array(
				'name' => $this->data['requestDetails']['request_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['request_offer']=$this->buyer_request->offerList($request_id);
		$this->layout->view('offer_detail', $this->data);
	}
	public function matchuser($request_id=''){
		$srch = get();
		$srch['request_id'] = $request_id;
		$this->data['requestDetails']=$this->buyer_request->getDetail($request_id);
		$this->data['main_title'] = 'Send notification of ';
		$this->data['second_title'] = $this->data['requestDetails']['request_title'];
		$this->data['title'] = 'Match User List';
		$breadcrumb = array(
			array(
				'name' => 'Request',
				'path' => base_url($this->data['curr_controller'].'list_record'),
			),
			array(
				'name' => $this->data['requestDetails']['request_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['matchuser']=$this->buyer_request->userList($request_id);
		$this->layout->view('matchuser', $this->data);
	}
	public function sendemail($id=''){
		$action_type = post('action_type');
		$request_id=post('request_id');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		if($id){
			$cmd = get('cmd');
			if($cmd && $cmd == 'sendemail'){
				if($id && is_array($id)){
					$template='notification-email-seller';
					foreach($id as $member_id){
						$RECEIVER_EMAIL=getField('member_email','member','member_id',$member_id);
						$data_parse=array(
							'SELLER_NAME'=>getUserName($member_id),
							'REQUESR_URL'=>URL.'requests/buyer_requests',
							'PRIORITY'=>1,
						);
						SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
					}
				}
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	
}





