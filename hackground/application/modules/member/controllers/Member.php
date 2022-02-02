<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Member extends MX_Controller {

   

   private $data;

   

	public function __construct(){

		$this->data['curr_controller'] = $this->router->fetch_class()."/";

		$this->data['curr_method'] = $this->router->fetch_method()."/";

		$this->load->model('member_model', 'member');

		$this->data['table'] = 'member';

		$this->data['primary_key'] = $this->data['table'].'_id';

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
			$csvarr[]=array('Member ID','Name','Username','Email','Gender','Nationality','Country','City','Registered On','Email Verified','Mesh Verified','Status','No of Gigs');
			$list = $this->member->getListCSV($srch);
			if($list){
				foreach($list as $k=>$v){
				if($v['is_email_verified'] > 0){
					$email_ver_txt = 'Verified';
				}else{
					$email_ver_txt = 'Not Verified';
				}		
				if($v['login_status'] == 1){
					$status_txt="Active";
				}else if($v['login_status'] == 0){
					$status_txt="Inactive";
				}else{
					$status_txt="Deleted";
				}
				if($v['is_admin_verified'] > 0){
					$is_admin_verified_ver_txt = 'Admin Verified';
				}else{
					$is_admin_verified_ver_txt = 'Admin Not Verified';
				}
				$gender="";
				if($v['member_gender']=='F'){
					$gender="Female";
				}elseif($v['member_gender']=='M'){
					$gender="Male";
				}
					$csvarr[]=array($v['member_id'],$v['member_name'],$v['access_username'],$v['member_email'],$gender,$v['nationality_name'],$v['country_name'],$v['member_city'],$v['member_register_date'],$email_ver_txt,$is_admin_verified_ver_txt,$status_txt,$v['no_of_gigs']);
				}
			}
			$file_name='Member-List-'.date("dmY").'.csv';
			array_to_csv($csvarr, $file_name);
			exit();
		}
		$curr_limit = get('per_page');

		$limit = !empty($curr_limit) ? $curr_limit : 0; 

		$offset = 20;

		$this->data['main_title'] = 'Member Management';

		$this->data['second_title'] = 'All Member List';

		$this->data['title'] = 'Member';

		$breadcrumb = array(

			array(

				'name' => 'Member',

				'path' => '',

			),

		);

		$this->data['breadcrumb'] = breadcrumb($breadcrumb);

		$this->data['list'] = $this->member->getList($srch, $limit, $offset);

		$this->data['list_total'] = $this->member->getList($srch, $limit, $offset, FALSE);

		

		$this->load->library('pagination');

		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');

		$config['total_rows'] =$this->data['list_total'];

		$config['per_page'] = $offset;

		$config['page_query_string'] = TRUE;

		$config['reuse_query_string'] = TRUE;

		

		$this->pagination->initialize($config);

		

		$this->data['links'] = $this->pagination->create_links();

		$this->data['add_command'] = null;

		$this->data['edit_command'] = 'edit';

		$this->layout->view('list', $this->data);

       

	}

	

	public function load_ajax_page(){

		$page = get('page');

		$this->data['page'] = $page;

		if($page == 'add'){

			$this->data['title'] = 'Add Member';

			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');

		}else if($page == 'edit'){

			$id = get('id');

			$this->data['ID']= $id;

			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');

			$this->data['detail'] = $this->member->getDetail($id);
			$this->data['detail']['is_login']=0;
			$this->db->select('a.login_status');
			$this->db->from('profile_connection as p_c');
			$this->db->join('access_panel as a','p_c.access_user_id=a.access_user_id','left');
			$this->db->where(array('p_c.member_id'=>$id,'p_c.organization_id'=>NULL));
			$dataD=$this->db->get()->row();
			if($dataD){
				$this->data['detail']['is_login']=$dataD->login_status;
				$this->data['detail']['member_country']=getField('member_country','member_address','member_id',$id);
				$this->data['detail']['member_nationality']=getField('member_nationality','member_address','member_id',$id);
				$this->data['detail']['member_city']=getField('member_city','member_address','member_id',$id);
				$this->data['detail']['member_phone']=getField('member_phone','member_basic','member_id',$id);
				$this->data['detail']['member_mobile_code']=getField('member_mobile_code','member_basic','member_id',$id);
				$this->data['detail']['member_gender']=getField('member_gender','member_basic','member_id',$id);
			}
			$this->data['title'] = 'Edit Member';
			$admin_default_lang = admin_default_lang();
			$this->db->select('*')
				->from('nationality a')
				->join('nationality_names b', 'a.nationality_id='.'b.nationality_id');
			$this->db->where('b.nationality_lang', $admin_default_lang);
			$this->data['nationality_list'] = $this->db->order_by('b.nationality_name', 'ASC')->get()->result_array();
			$this->db->select('*')
				->from('country a')
				->join('country_names b', 'a.country_code='.'b.country_code');
			$this->db->where('b.country_lang', $admin_default_lang);
			$this->data['country_list'] = $this->db->order_by('b.country_name', 'ASC')->get()->result_array();
			$this->data['mobile_codes'] = $this->db->select('codes')->from('mobile_code')->where('status','1')->order_by('display_order', 'ASC')->get()->result_array();
		}

		$this->load->view('ajax_page', $this->data);

	}

	

	public function add(){

		if(post() && $this->input->is_ajax_request()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');

			$this->form_validation->set_rules('status', 'status', '');

			if($this->form_validation->run()){

				$post = post();

				$insert = $this->member->addRecord($post);

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

			$this->form_validation->set_rules('member_name', 'name', 'required|trim|max_length[100]');

			$this->form_validation->set_rules('member_email', 'email', 'required|trim|max_length[100]|valid_email');

			if($this->input->post('new_pass')){
				$this->form_validation->set_rules('new_pass', 'New Password', 'required|trim');
				$this->form_validation->set_rules('new_pass_again', 'Confirm Password', 'required|trim|matches[new_pass]');
			}
			
			$this->form_validation->set_rules('ID', 'id', 'required');
			$this->form_validation->set_rules('member_nationality', 'country', 'required|trim');
			$this->form_validation->set_rules('member_country', 'nationality', 'required|trim');

			if($this->form_validation->run()){

				$post = post();

				$ID = post('ID');
				$login_status=post('is_login');
				$member_nationality=post('member_nationality');
				$member_country=post('member_country');
				$member_city=post('member_city');
				$member_gender=NULL;
				if($this->input->post('member_gender') && post('member_gender')!=''){
					$member_gender=post('member_gender');
				}
				$member_phone=$member_mobile_code=NULL;
				if($this->input->post('phone') && post('phone')!=''){
					$member_phone=post('phone');
				}
				if($this->input->post('mobile_code') && post('mobile_code')!=''){
					$member_mobile_code=post('mobile_code');
				}
				
				unset($post['ID']);
				unset($post['is_login']);
				unset($post['member_nationality']);
				unset($post['member_country']);
				unset($post['member_city']);
				unset($post['member_gender']);
				unset($post['phone']);
				unset($post['mobile_code']);
				
				
				unset($post['new_pass_again']);
				unset($post['new_pass']);
				$update = $this->member->updateRecord($post, $ID);
				$profile_connection=$this->db->select('access_user_id')->where('member_id',$ID)->where('organization_id',NULL)->from('profile_connection')->get()->row();
				$this->db->where('access_user_id',$profile_connection->access_user_id)->update('access_panel',array('login_status'=>$login_status));
				
				if($this->input->post('new_pass')){
					if($profile_connection){
						$this->load->library('bcrypt');
						$pass=post('new_pass');
						$hash = $this->bcrypt->hash_password($pass);
						//$password=md5($this->input->post('new_pass'));
						$this->db->where('access_user_id',$profile_connection->access_user_id)->update('access_panel',array('access_user_password'=>$hash));
					}
				}
				
				$this->db->where('member_id',$ID)->update('profile_connection',array('profile_name'=>post('member_name')));
				$this->db->where('user_id',$ID)->update('wallet',array('title'=>post('member_name')));
				$this->db->where('member_id',$ID)->update('member_basic',array('member_phone'=>$member_phone,'member_mobile_code'=>$member_mobile_code,'member_gender'=>$member_gender));
				$this->db->where('member_id',$ID)->update('member_address',array('member_nationality'=>$member_nationality,'member_country'=>$member_country,'member_city'=>$member_city));
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
				$profile_connection=$this->db->select('access_user_id')->where('member_id',$ID)->where('organization_id',NULL)->from('profile_connection')->get()->row();
				$upd['data'] = array('login_status' => $sts);
				$upd['where'] = array('access_user_id' => $profile_connection->access_user_id);
				$upd['table'] = 'access_panel';
				update($upd);
				
				$updm['where'] = array('member_id' => $ID);
				$updm['table'] = 'member';
				if($sts==1){
					$updm['data'] = array('is_inactive' => 0);
				}else{
					$updm['data'] = array('is_inactive' =>1);
				}
				update($updm);
				if($sts==0){
					$member_id=$ID;
					$RECEIVER_EMAIL=getField('member_email','member','member_id',$member_id);
					$data_parse=array(
					'SELLER_NAME'=>getUserName($member_id),
					'CUSTOMER_SUPPORT_URL'=>URL.'cms/support',
					);
					$template='member-blocked-by-admin';
					SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
				}
				

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

			$this->member->deleteRecord($id);

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











