<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accesspanel_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function getLastActive($LID){
		$data=array(
		'LAST_PCI'=>0,
		'TYP'=>'',
		'MID'=>NULL,
		'OID'=>NULL,
		'NAME'=>NULL,
		);
		$lastData=getData(array(
				'select'=>'p_c.profile_connection_id,p_c.organization_id,p_c.member_id,p_c.profile_name',
				'table'=>'profile_connection p_c',
				'where'=>array('p_c.access_user_id'=>$LID,'p_c.is_last_active'=>1),
				'order'=>array(array('p_c.profile_connection_id','asc')),
				'single_row'=>true,
				));
		if($lastData){
			
		}else{
			$lastData=getData(array(
				'select'=>'p_c.profile_connection_id,p_c.organization_id,p_c.member_id,p_c.profile_name',
				'table'=>'profile_connection p_c',
				'where'=>array('p_c.access_user_id'=>$LID),
				'order'=>array(array('p_c.profile_connection_id','asc')),
				'single_row'=>true,
				));
		}
		if($lastData){
			$data['LAST_PCI']=$lastData->profile_connection_id;
			$data['NAME']=$lastData->profile_name;
			if($lastData->organization_id){
				$data['TYP']='C';
				$data['OID']=$lastData->organization_id;
			}else{
				$data['TYP']='F';
				$data['MID']=$lastData->member_id;
			}
		}
		return $data;
	}
}
