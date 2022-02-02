<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('coupon_model', 'coupon');
		$this->data['table'] = 'coupons';
		$this->data['primary_key'] = 'coupon_id';
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
		$this->data['main_title'] = 'Coupon Management';
		$this->data['second_title'] = 'All Coupon List';
		$this->data['title'] = 'Coupon';
		$breadcrumb = array(
			array(
				'name' => 'Coupon',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->coupon->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->coupon->getList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		$this->load->model('proposal/proposal_model');
		if($page == 'add'){
			$this->data['title'] = 'Add Coupon';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
			$this->data['proposals'] = $this->proposal_model->get_active_proposal();
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->coupon->getDetail($id);
			$this->data['title'] = 'Edit Coupon';
			$this->data['proposals'] = $this->proposal_model->get_active_proposal();
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('coupon_title', 'title', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('coupon_type', 'coupon type', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('coupon_price', 'coupon price', 'required|trim|numeric');
			$this->form_validation->set_rules('coupon_code', 'coupon code', 'required|trim|is_unique[coupons.coupon_code]|regex_match[/^[a-zA-Z0-9]+$/]');
			$this->form_validation->set_rules('coupon_limit', 'coupon limit', 'required|numeric');
			$this->form_validation->set_rules('proposal_id', 'proposal_id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->coupon->addRecord($post);
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
			$this->form_validation->set_rules('coupon_title', 'title', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('coupon_type', 'coupon type', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('coupon_price', 'coupon price', 'required|trim|numeric');
			/* $this->form_validation->set_rules('coupon_code', 'coupon code', 'required|trim|is_unique[coupons.coupon_code]'); */
			$this->form_validation->set_rules('coupon_limit', 'coupon limit', 'required|numeric');
			$this->form_validation->set_rules('proposal_id', 'proposal id', 'required');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->coupon->updateRecord($post, $ID);
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
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('status' => $sts));
			}else{
				$upd['data'] = array('status' => $sts);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
			}
			
			if($action_type == 'multiple'){
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
			}
			
			
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
			/* $this->coupon->deleteRecord($id); */
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
}





