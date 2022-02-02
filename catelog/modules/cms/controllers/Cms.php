<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends MX_Controller {

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
		loadModel('cms_model');
			parent::__construct();
	}
	public function policy($page){
		
		$arr=array(
				'select'=>'c.content_id,c.content_slug,c_n.title,c_n.content,c_n.meta_title,c_n.meta_keys,c_n.meta_description',
				'table'=>'content as c',
				'join'=>array(
				array('table'=>'content_names as c_n','on'=>"c.content_id=c_n.content_id and c_n.lang='".getSetlang()."'",'posiotion'=>'left'),
				),
				'where'=>array('c.content_slug'=>$page),
				'single_row'=>TRUE
			);
		$data['cms']=getData($arr);
		$data['seo_tags']=array(
		'meta_title'=>$data['cms']->meta_title,
		'meta_key'=>$data['cms']->meta_keys,
		'meta_description'=>strip_tags(html_entity_decode($data['cms']->meta_description)),
		'seo_images'=>array(),
		);
		$templateLayout=array('view'=>'policy','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
		
	}
	public function index($page){
		
		$arr=array(
				'select'=>'c.content_id,c.content_slug,c_n.title,c_n.content,c_n.meta_title,c_n.meta_keys,c_n.meta_description',
				'table'=>'content as c',
				'join'=>array(
				array('table'=>'content_names as c_n','on'=>"c.content_id=c_n.content_id and c_n.lang='".getSetlang()."'",'posiotion'=>'left'),
				),
				'where'=>array('c.content_slug'=>$page),
				'single_row'=>TRUE
			);
		$data['cms']=getData($arr);
		$data['seo_tags']=array(
		'meta_title'=>$data['cms']->meta_title,
		'meta_key'=>$data['cms']->meta_keys,
		'meta_description'=>strip_tags(html_entity_decode($data['cms']->meta_description)),
		'seo_images'=>array(),
		);
		if($page=='founders'){
			$arr=array(
				'select'=>'c.founder_id,c.founder_name,c.founder_image,c_n.content',
				'table'=>'founder as c',
				'join'=>array(
				array('table'=>'founder_names as c_n','on'=>"c.founder_id=c_n.founder_id and c_n.lang='".getSetlang()."'",'posiotion'=>'left'),
				),
				'where'=>array('c.status'=>1),
				'order_by'=>array(array('c.display_order'=>'asc'))
			);
			$data['founder']=getData($arr);
			//dd($data['founder']);
			$templateLayout=array('view'=>'founder','type'=>'default','buffer'=>FALSE,'theme'=>'');
		}else{
			$templateLayout=array('view'=>'cms','type'=>'default','buffer'=>FALSE,'theme'=>'');
		}
		
		load_template($templateLayout,$data);
		
	}
	public function howitworks(){
		$data['seo_tags']=array(
		'meta_title'=>'How It Works',
		'meta_key'=>'',
		'meta_description'=>'How It Works',
		'seo_images'=>array(),
		);
		$templateLayout=array('view'=>'howitwork','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function knowledgebank($page='knowledge-bank'){
		$arr=array(
				'select'=>'c.content_id,c.content_slug,c_n.title,c_n.content,c_n.meta_title,c_n.meta_keys,c_n.meta_description',
				'table'=>'content as c',
				'join'=>array(
				array('table'=>'content_names as c_n','on'=>"c.content_id=c_n.content_id and c_n.lang='".getSetlang()."'",'posiotion'=>'left'),
				),
				'where'=>array('c.content_slug'=>$page),
				'single_row'=>TRUE
			);
		$data['cms']=getData($arr);
		$data['seo_tags']=array(
		'meta_title'=>$data['cms']->meta_title,
		'meta_key'=>$data['cms']->meta_keys,
		'meta_description'=>strip_tags(html_entity_decode($data['cms']->meta_description)),
		'seo_images'=>array(),
		);
		$data['load_css']=array('knowledge_base.css');
		$templateLayout=array('view'=>'knowledgebank','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	/*public function support(){
		
		exit();
	}*/
	public function contactus(){
		$data['seo_tags']=array(
		'meta_title'=>'Contact Us',
		'meta_key'=>'',
		'meta_description'=>'Contact Us',
		'seo_images'=>array(),
		);
		$templateLayout=array('view'=>'contactus','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
}
