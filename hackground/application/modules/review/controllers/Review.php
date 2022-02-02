<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('review_model', 'review');
		
		parent::__construct();
		
		admin_log_check();
	}

	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	
	public function buyer(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Buyer Review Management';
		$this->data['second_title'] = 'All Buyer Review List';
		$this->data['title'] = 'Buyer Review';
		$breadcrumb = array(
			array(
				'name' => 'Buyer Review',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->review->getBuyerReview($srch, $limit, $offset);
		$this->data['list_total'] = $this->review->getBuyerReview($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'buyer');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		/* get_print($this->data['list']); */
		$this->layout->view('list', $this->data);
       
	}
	
	public function seller(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Freelancer Review Management';
		$this->data['second_title'] = 'All Freelancer Review List';
		$this->data['title'] = 'Freelancer Review';
		$breadcrumb = array(
			array(
				'name' => 'Freelancer Review',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->review->getSellerReview($srch, $limit, $offset);
		$this->data['list_total'] = $this->review->getSellerReview($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'seller');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
	
		$this->layout->view('list_seller', $this->data);
       
	}
	
	public function delete_record($id=''){
		$action_type = post('action_type');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		if($id){
			/* $this->test_three->deleteRecord($id); */
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in('review_id' ,  $id)->delete('buyer_reviews');
					foreach($id as $id_one){
						$this->review_model->update_buyer_averate_rating($id_one);
					}
				}else{
					$this->db->where('review_id' ,  $id)->delete('buyer_reviews');
					$this->review_model->update_buyer_averate_rating($id);
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	
	public function delete_record_seller($id=''){
		$action_type = post('action_type');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		if($id){
			/* $this->test_three->deleteRecord($id); */
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in('review_id' ,  $id)->delete('seller_reviews');
				}else{
					$this->db->where('review_id' ,  $id)->delete('seller_reviews');
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	
}





