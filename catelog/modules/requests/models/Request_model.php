<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function getRequest($where=array()){
		$arr=array(
				'select'=>'b_r.request_id,b_r.request_title,b_r.request_description,b_r.delivery_time,b_r.request_budget,b_r.request_date,b_r.request_status',
				'table'=>'buyer_requests b_r',
				'where'=>array('b_r.request_status <>'=>REQUEST_DELETED),
				'order'=>array(array('b_r.request_id','desc')),
				);
		if($where && array_key_exists('member_id',$where)){
			$arr['where']['b_r.seller_id']=$where['member_id'];
		}
		$data=getData($arr);
		return $data;
	}
}
