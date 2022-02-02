<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proposal_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function getProposal($where=array()){
		$arr=array(
				'select'=>'p.proposal_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_status,p_s.proposal_views,p_set.proposal_featured,p_set.featured_end_date,p.admin_reason',
				'table'=>'proposals p',
				'join'=>array(array('table'=>'proposal_stat as p_s','on'=>'p.proposal_id=p_s.proposal_id','position'=>'left'),array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left')),
				'where'=>array('p.proposal_status <>'=>PROPOSAL_DELETED),
				'order'=>array(array('p.proposal_id','desc')),
				);
		if($where && array_key_exists('member_id',$where)){
			$arr['where']['p.proposal_seller_id']=$where['member_id'];
		}
		
		$data=getData($arr);
		return $data;
	}
	public function getProposalPackage($proposal_id){
		$arr=array(
				'select'=>'p.package_name,p.price',
				'table'=>'proposal_packages p',
				'where'=>array(),
				'order'=>array(array('p.package_id','asc')),
				);
			$arr['where']['p.proposal_id']=$proposal_id;
		$data=getData($arr);
		return $data;
	}
}
