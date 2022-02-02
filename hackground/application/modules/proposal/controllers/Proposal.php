<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proposal extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('proposal_model', 'proposal');
		$this->data['table'] = 'proposals';
		$this->data['primary_key'] = 'proposal_id';
		parent::__construct();
		
		admin_log_check();
	}

	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	
	public function list_record(){
		$srch = get();
		if($srch && get('csv') && $srch['csv']==1){
			$this->load->helper('csv');	
			$csvarr=array();
			$csvarr[]=array('ID','Title','Category','Sub Category','Created On','Created By ID','Creator Name','Status');
			$list = $this->proposal->getListCSV($srch);
			if($list){
				foreach($list as $k=>$v){
				$status_txt = '';
				if($v['proposal_status'] == PROPOSAL_ACTIVE){
					$status_txt = 'Active';
				}else if($v['proposal_status'] == PROPOSAL_PENDING){
					$status_txt = 'Pending';
				}else if($v['proposal_status'] == PROPOSAL_DECLINED){
					$status_txt = 'Declined';
				}else if($v['proposal_status'] == PROPOSAL_MODIFICATION){
					$status_txt = 'Modification';
				}else if($v['proposal_status'] == PROPOSAL_PAUSED){
					$status_txt = 'Pause';
				}else{
					$status_txt = 'Deleted';
				}
					$csvarr[]=array($v['proposal_id'],$v['proposal_title'],$v['category_name'],$v['sub_category'],$v['proposal_date'],$v['proposal_seller_id'],$v['member_name'],$status_txt);
				}
			}
			$file_name='Proposal-List-'.date("dmY").'.csv';
			array_to_csv($csvarr, $file_name);
			exit();
		}
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Proposal Management';
		$this->data['second_title'] = 'All Proposal List';
		$this->data['title'] = 'Proposal';
		$breadcrumb = array(
			array(
				'name' => 'Proposal',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->proposal->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->proposal->getList($srch, $limit, $offset, FALSE);
		
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
		
		/* search parameter */
		$this->load->model('category/category_model');
		$this->load->model('delivery_times/delivery_times_model');
		$this->data['delivery_times'] = $this->delivery_times_model->get_all_data();
		$this->data['category'] = $this->category_model->get_all_category();
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
			$this->data['detail'] = $this->proposal->getDetail($id);
			$this->load->model('category/category_model');
			$this->data['category'] = $this->category_model->get_all_category();
			$this->data['sub_category'] = $this->proposal->get_all_sub_category($this->data['detail']['category_id']);
			$this->data['title'] = 'Edit Proposal';
		}else if($page == 'subcat'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['sub_category'] = $this->proposal->get_all_sub_category($id);
		}else if($page == 'reason'){
			$id = get('id');
			$type = get('type');
			$this->data['ID']= $id;
			$this->data['status']= $type;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'reason');
			//$this->data['detail'] = $this->proposal->getDetail($id);
			$this->data['title'] = 'Edit Test Three';
			if($type==PROPOSAL_MODIFICATION){
				$this->data['title'] = 'Modification reason';
			}elseif($type==PROPOSAL_PAUSED){
				$this->data['title'] = 'Pause reason';
			}
			
		}
		$this->load->view('ajax_page', $this->data);
	}
	public function reason(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('reason', 'reason', 'required|trim');
			$this->form_validation->set_rules('ID', 'ID', 'required|trim');
			$this->form_validation->set_rules('status', 'status', 'required|trim');
			if($this->form_validation->run()){
				$post = post();
				
				$ID = post('ID');
				$sts = post('status');
				$reason = post('reason');
				$upd['data'] = array('proposal_status' => $sts,'admin_reason'=>$reason);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
				$member_id=getField('proposal_seller_id','proposals','proposal_id',$ID);
				$RECEIVER_EMAIL=getField('member_email','member','member_id',$member_id);
				$data_parse=array(
				'SELLER_NAME'=>getUserName($member_id),
				'REASON'=>$reason,
				'PROPOSAL_URL'=>URL.'proposals/view/'.getUserName($member_id).'/'.getField('proposal_url','proposals','proposal_id',$ID),
				);
				if($sts==PROPOSAL_MODIFICATION){
					$template='modification-request-by-admin';
				}elseif($sts==PROPOSAL_PAUSED){
					$template='proposal-paused-by-admin';
				}
				SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
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
			$this->form_validation->set_rules('proposal_title', 'title', 'required|trim');
			$this->form_validation->set_rules('category_id', 'category_id', 'required|trim|numeric');
			$this->form_validation->set_rules('category_subchild_id', 'category_subchild_id', 'required|trim|numeric');
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
	public function makefeatured(){
		if(post() && $this->input->is_ajax_request()){
			
			$ID = post('ID');
			$status = post('status');
			
			if($status==1){
				$featured_end_date=date('Y-m-d H:i:s',strtotime('+3 years'));
				$upd['data'] = array('proposal_featured' => 1,'featured_end_date'=>$featured_end_date);
			}else{
				$upd['data'] = array('proposal_featured' => 0,'featured_end_date'=>NULL);
			}
			
			$upd['where'] = array($this->data['primary_key'] => $ID);
			$upd['table'] = 'proposal_settings';
			update($upd);
			$this->api->cmd('reload');

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
				$upd['data'] = array('proposal_status' => $sts,'admin_reason'=>'');
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
				$member_id=getField('proposal_seller_id','proposals','proposal_id',$ID);
				$RECEIVER_EMAIL=getField('member_email','member','member_id',$member_id);
				$data_parse=array(
				'SELLER_NAME'=>getUserName($member_id),
				'PROPOSAL_URL'=>URL.'proposals/view/'.getUserName($member_id).'/'.getField('proposal_url','proposals','proposal_id',$ID),
				);
				if($sts==PROPOSAL_ACTIVE){
					$template='proposal-approved-by-admin';
				}elseif($sts==PROPOSAL_MODIFICATION){
					$template='modification-request-by-admin';
				}elseif($sts==PROPOSAL_DECLINED){
					$template='proposal-declined-by-admin';
				}elseif($sts==PROPOSAL_PAUSED){
					$template='proposal-paused-by-admin';
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





