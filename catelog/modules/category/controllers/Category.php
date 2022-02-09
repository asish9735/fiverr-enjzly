<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MX_Controller {

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
		}
		loadModel('category_model');
			parent::__construct();
	}
	public function index($dataparse=''){
		$data['filter']['action_url']=uri_string();
		$data['filter']['is_search']=0;
		if($dataparse){
			$alldataparse=explode(':',$dataparse);
			if($alldataparse){
				foreach($alldataparse as $k=>$parse){
					$keyparse=explode('-',$parse,2);
					if($keyparse[0]=='category'){
						$data['filter']['category_key']=$keyparse[1];
						$category_info=getAllCategory(array('category_key'=>$keyparse[1]));
						$data['filter']['category_id']=$category_info->category_id;
						$data['filter']['category_details']=$category_info;
						$meta_title=$category_info->name;
						$meta_description=$category_info->info;
					}elseif($keyparse[0]=='subcategory'){
						$data['filter']['category_subchild_key']=$keyparse[1];
						$sub_category_info=getAllSubCategory($data['filter']['category_id'],array('category_subchild_key'=>$keyparse[1]));
						$data['filter']['category_subchild_id']=$sub_category_info->category_subchild_id;
						$data['filter']['sub_category_details']=$sub_category_info;
						$meta_title=$sub_category_info->name;
						$meta_description=$sub_category_info->description;
					}
				}
				
			}
		}
		if($this->input->get('input')){
			$data['filter']['input']=$this->input->get('input');
			$data['filter']['is_search']=1;
			$meta_title=$meta_description='Search Results for '.$data['filter']['input'];	
		}
		$data['all_category']=getAllCategory();
		if($data['all_category']){
			foreach($data['all_category'] as $k=>$category){
				$data['all_category'][$k]->subcategory=getAllSubCategory($category->category_id);
			}
		}
		$view_type='';
		if($this->session->userdata('view_type')){
			$view_type=$this->session->userdata('view_type');
		}
		$data['view_type']=$view_type;
		$data['loggedUser']=$this->loggedUser;
		$data['all_delivery_times']=getAllDeliveryTimes();
		$data['all_level']=getLevelName();
		$data['all_language']=getAlllanguages();
		//dd($data,TRUE);
		$data['seo_tags']=array(
		'meta_title'=>$meta_title,
		'meta_key'=>'',
		'meta_description'=>strip_tags(html_entity_decode($meta_description)),
		'seo_images'=>array(),
		);
		$data['load_js']=load_js(array('bootstrap-select.min.js', 'bootstrap-slider.min.js'));
		//$data['load_css']=load_css(array(''));
		$templateLayout=array('view'=>'proposals','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
		
	}
	public function setloadview(){
		$dataRes=array();
		if($this->input->post()){
			$type=$this->input->post('type');
			$this->session->set_userdata('view_type',$type);
			$dataRes['status']=1;
		}else{
			$dataRes['status']=0;
		}
		echo json_encode($dataRes);			
	}
	public function load_proposal()
	{
		$data=$dataRes=array();
		$limit=10;
		$start=0;
		$project_total=0;
		if(post('page')){
			$start=(post('page')-1)*$limit;
		}
			
		$where=array();
		if($this->input->post()){
			$where=$this->input->post();
			unset($where['page']);
		}
		$data['loggedUser']=$this->loggedUser;
		$view_type='';
		if($this->session->userdata('view_type')){
			$view_type=$this->session->userdata('view_type');
		}
		$data['view_type']=$view_type;
		$data['all_proposals']=$this->category_model->getProposal($where,$start,$limit);
		$project_total=$this->category_model->getProposal($where,$start,$limit,TRUE);
		if($project_total){
			$templateLayout=array('view'=>'ajax-proposal-list-display','type'=>'ajax','buffer'=>TRUE,'theme'=>'');
			$dataRes['list']=load_template($templateLayout,$data,'',TRUE);
		}
		$dataRes['total_page']=$dataRes['project_total']=ceil($project_total/$limit);
		$data['page']=post('page');
		
		echo json_encode($dataRes);			
	}
	public function categorylist(){
		$data['seo_tags']=array(
		'meta_title'=>'Categories',
		'meta_key'=>'',
		'meta_description'=>'Categories',
		'seo_images'=>array(),
		);
		$data['all_category']=getAllCategory(array('show_details'=>1));
		$templateLayout=array('view'=>'category-list','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function allcategory(){
		$data['seo_tags']=array(
		'meta_title'=>'Categories',
		'meta_key'=>'',
		'meta_description'=>'Categories',
		'seo_images'=>array(),
		);
		$data['all_category']=getAllCategory();
		if($data['all_category']){
			foreach($data['all_category'] as $k=>$category){
				$data['all_category'][$k]->subcategory=getAllSubCategory($category->category_id);
			}
		}
		$templateLayout=array('view'=>'all-category-list','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function proposals($type=''){
		$data['all_category']=getAllCategory();
		$data['all_delivery_times']=getAllDeliveryTimes();
		$data['all_level']=getLevelName();
		$data['all_language']=getAlllanguages();
		$data['is_featured']=0;
		$data['is_top']=0;
		$data['is_random']=0;
		if($type=='featured'){
			$data['is_featured']=1;
			$meta_title='Featured Proposals/Services';
			$meta_description='This is an extended list of our top featured proposals/services';
		}elseif($type=='top'){
			$data['is_top']=1;
			$meta_title='Top Rated Proposals/Services';
			$meta_description='This is an extended list of our top rated proposals/services';
		}elseif($type=='random'){
			$data['is_random']=1;
			$meta_title='Random Proposals/Services';
			$meta_description='This is an extended list of our random proposals/services';
		}
		$data['seo_tags']=array(
		'meta_title'=>$meta_title,
		'meta_key'=>'',
		'meta_description'=>strip_tags(html_entity_decode($meta_description)),
		'seo_images'=>array(),
		);
		
		$templateLayout=array('view'=>'filter-proposals','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
}
