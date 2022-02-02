<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function getProposal($where=array(),$start = '', $limit = '',$count=FALSE){
	
		$arr=array(
				'select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_set.proposal_featured,p_set.featured_end_date,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'proposals p',
				'join'=>array(
				array('table'=>'proposal_stat as p_s','on'=>'p.proposal_id=p_s.proposal_id','position'=>'left'),
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left'),
				array('table'=>'member_basic as mb','on'=>'m.member_id=mb.member_id','position'=>'left'),
				array('table'=>'proposal_category as pc','on'=>'p.proposal_id=pc.proposal_id','position'=>'left'),
				array('table'=>'online_user as o','on'=>'m.member_id=o.user_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'m.is_inactive <>'=>1),
				'order'=>array(array('p.proposal_id','desc')),
				'group'=>'p.proposal_id',
				);
		if($where && array_key_exists('member_id',$where)){
			//$arr['where']['p.proposal_seller_id']=$where['member_id'];
		}
		if($where && array_key_exists('is_featured',$where) && $where['is_featured']==1){
			$arr['where']['p_set.proposal_featured']=1;
			$arr['where']['p_set.featured_end_date >=']=date('Y-m-d H:i:s');
		}
		if($where && array_key_exists('is_top',$where) && $where['is_top']==1){
			$arr['where']['m.seller_level']=4;
		}
		if($where && array_key_exists('is_random',$where) && $where['is_random']==1){
			$arr['order']=array(array('rand()',NULL));
		}
		if($where && array_key_exists('category_id',$where) && $where['category_id']>0){
			$arr['where']['pc.category_id']=$where['category_id'];
		}
		if($where && array_key_exists('category_ids',$where)){
			$arr['where_in']['pc.category_id']=$where['category_ids'];
		}
		if($where && array_key_exists('category_subchild_id',$where) && $where['category_subchild_id']>0){
			$arr['where']['pc.category_subchild_id']=$where['category_subchild_id'];
		}
		if($where && array_key_exists('is_online_user',$where)){
			$arr['where']['o.user_id <>']=NULL;
		}
		if($where && array_key_exists('delivery_id',$where)){
			$arr['where_in']['p.delivery_time']=$where['delivery_id'];
		}
		if($where && array_key_exists('is_search',$where) && $where['is_search']==1){
			$liketitle="p.proposal_title like '%".$this->db->escape_like_str($where['input'])."%' ESCAPE '!'";
			$arr['where'][$liketitle]=NULL;
		}
		if($where && array_key_exists('level_id',$where)){
			if(in_array('1',$where['level_id'])){
				$where['level_id'][]='0';
			}
			$arr['where_in']['m.seller_level']=$where['level_id'];
		}
		if($where && array_key_exists('language_id',$where)){
			$arr['where_in']['mb.prefer_language']=$where['language_id'];
		}
		if($count==TRUE){
			$arr['return_count']=TRUE;
			$data=getData($arr);
		}else{
			$arr['limit']=array($limit,$start);
			$data=getData($arr);
		}
		
		//lastQuery();
        return $data;	
	}
}
