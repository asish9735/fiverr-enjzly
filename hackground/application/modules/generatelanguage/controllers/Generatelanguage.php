<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Generatelanguage extends MX_Controller {

   

   private $data;

   

	public function __construct(){

		$this->data['curr_controller'] = $this->router->fetch_class()."/";

		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('Generatelanguage_model', 'generatelanguage');
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

		$this->data['main_title'] = 'Language Management';

		$this->data['second_title'] = 'All Language List';

		$this->data['title'] = 'Language';

		$breadcrumb = array(

			array(

				'name' => 'Language',

				'path' => '',

			),

		);

		$this->data['breadcrumb'] = breadcrumb($breadcrumb);

		$this->data['list'] = $this->generatelanguage->getList($srch, $limit, $offset);

		$this->data['list_total'] = $this->generatelanguage->getList($srch, $limit, $offset, FALSE);

		

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

		$this->data['add_btn'] = 'Add Test Four';

		$this->layout->view('list', $this->data);

       

	}

	
	public function viewlang(){
		$lang = get_lang();
		$selected_lang=$this->input->get('lang');
		if($selected_lang && in_array($selected_lang,$lang)){
			
		}else{
			redirect(base_url($this->data['curr_controller'].'list_record'));
		}
		$this->data['main_title'] = 'Language View';

		$this->data['second_title'] = $selected_lang;

		$this->data['title'] = 'Language';

		$breadcrumb = array(
			array(
				'name' => 'Language',
				'path' => base_url($this->data['curr_controller'].'list_record'),
			),
			array(
				'name' => $selected_lang,
				'path' => '',
			),

		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$Tables=array();
		$Tables[]=array('table'=>'category_names','column'=>'lang');
		$Tables[]=array('table'=>'category_subchild_names','column'=>'lang');
		$Tables[]=array('table'=>'content_names','column'=>'lang');
		$Tables[]=array('table'=>'country_names','column'=>'country_lang');
		$Tables[]=array('table'=>'nationality_names','column'=>'nationality_lang');
		$Tables[]=array('table'=>'delivery_times_names','column'=>'lang');
		$Tables[]=array('table'=>'languages_names','column'=>'lang');
		$Tables[]=array('table'=>'mailtemplate_names','column'=>'lang');
		$Tables[]=array('table'=>'notifications_template_names','column'=>'lang');
		$Tables[]=array('table'=>'section_boxes_names','column'=>'lang');
		$Tables[]=array('table'=>'skills_names','column'=>'lang');
		$Tables[]=array('table'=>'slider_names','column'=>'lang');
		$this->data['languageTables']=$Tables;
		$this->data['from']='en';
		$this->data['to']=$selected_lang;
		$this->layout->view('view-details', $this->data);
	}
	public function copy_record(){
		$res=array();
		$res['status']=0;
		$table_name=post('table_name');
		$column_name=post('column_name');
		$from=post('from');
		$to=post('to');
		if($table_name && $table_name && $from && $to){
		
		$this->db->where($column_name,$to)->delete($table_name);
		$table=$this->db->dbprefix($table_name);
		$temptable=$this->db->dbprefix.'temp_table';
		$this->db->trans_start();
		
		$this->db->query("CREATE TEMPORARY TABLE ".$temptable." SELECT * FROM ".$table." WHERE ".$column_name."='".$from."'");
		$this->db->query("UPDATE ".$temptable." SET ".$column_name."='".$to."' WHERE ".$column_name."='".$from."'");
		$this->db->query("INSERT INTO ".$table." SELECT * FROM ".$temptable.";");
		$this->db->query("DROP TEMPORARY TABLE ".$temptable.";");
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE){
			$res['status']=1;
		}
		}
		echo json_encode($res);
	}
}











