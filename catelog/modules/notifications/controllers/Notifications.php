<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MX_Controller {

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
			// $this->output->enable_profiler(TRUE);
		}else{
			redirect(get_link('loginURL'));
		}
		loadModel('notification_model');
			parent::__construct();
	}
	public function index(){
		$lang=getSetlang();
		$arr=array(
		'select'=>'nt_n.template_content,m.member_name,n.notification_id,n.sender_id,n.notification_date,n.notification_template,n.is_read',
		'table'=>'notifications as n',
		'join'=>array(
			array('table'=>'notifications_template as nt','on'=>'n.notification_template=nt.template_key','position'=>'left'),
			array('table'=>'notifications_template_names as nt_n','on'=>"nt.notification_template_id=nt_n.notification_template_id and nt_n.lang='".$lang."'",'position'=>'left'),
			array('table'=>'member as m','on'=>"n.sender_id=m.member_id",'position'=>'left'),
		),
		'where_in'=>array('n.receiver_id'=>$this->member_id),
		'where'=>array('n.is_deleted <>'=>1),
		'order'=>array(array('n.notification_id','desc')),
		);
		$data['notifications']=getData($arr);
		$templateLayout=array('view'=>'all-notification','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function online_uer_up(){
		$newtime=date('Y-m-d H:i:s',strtotime('-30 second'));
		$wh=" (last_active < '".$newtime."' or user_id='".$this->member_id."')";
		$this->db->where($wh)->delete('online_user');
		$this->db->insert('online_user',array('user_id'=>$this->member_id,'last_active'=>date('y-m-d H:i:s')));
		//echo 1;
		//create_update('online_user',array('user_id'=>$this->member_id,'last_active'=>date('y-m-d H:i:s')),array('user_id'=>$this->member_id));
	}
	public function check_notification(){
		$this->online_uer_up();
		$lang=getSetlang();
		$unread=0;
		$poupmessage=$poupmessageDM=array();
		$poupmessagecontant=array();
		$filename=ABS_USERUPLOAD_PATH."ECnote/".$this->member_id.".echo";
		$filenamemessage=ABS_USERUPLOAD_PATH."ECnote/m-".$this->member_id.".echo";
		if(!file_exists($filename)){
			
		}else{
			$data=json_decode(file_get_contents($filename));
			if($data && $data->unread){
				$unread=$data->unread;
			}
			if($data && $data->poupmessage){
				$poupmessageD=getData(array(
				'select'=>'nt_n.template_content,m.member_name,n.notification_id,n.sender_id,n.notification_date,n.notification_template',
				'table'=>'notifications as n',
				'join'=>array(
					array('table'=>'notifications_template as nt','on'=>'n.notification_template=nt.template_key','position'=>'left'),
					array('table'=>'notifications_template_names as nt_n','on'=>"nt.notification_template_id=nt_n.notification_template_id and nt_n.lang='".$lang."'",'position'=>'left'),
					array('table'=>'member as m','on'=>"n.sender_id=m.member_id",'position'=>'left'),
				),
				'where_in'=>array('n.notification_id'=>$data->poupmessage),
				'order'=>array(array('n.notification_id','asc')),
				));
				if($poupmessageD){
					//dd($poupmessage);
					foreach($poupmessageD as $k=>$notification){
						$poupmessage[$k]=$notification;
						if($notification->sender_id){
							$poupmessage[$k]->member_name=getUserName($notification->sender_id);
						}else{
							$poupmessage[$k]->member_name='Admin';
						}
						$poupmessage[$k]->logo=getMemberLogo($notification->sender_id);
						$poupmessage[$k]->url=get_link('NotificationDetailsLink').$notification->notification_id;
						$poupmessage[$k]->date=date('H:i',strtotime($notification->notification_date)).dateFormat($notification->notification_date,'F d, Y');
					}
				}
				$this->load->helper('file');
				$arrayW=array(
					'unread'=>$unread,
					'poupmessage'=>array(),
				);
				if ( !write_file($filename, json_encode($arrayW), 'w')){
					echo 'Unable to write the file';
				}
			}
		}
		if(!file_exists($filenamemessage)){
			$countM=0;
		}else{
			$data=json_decode(file_get_contents($filenamemessage));
			if($data && $data->poupmessage){
				$poupmessageDM=getData(array(
				'select'=>'m.member_name,n.message_id,n.sender_id,n.sending_date,n.message as template_content,n.offer_id,n.conversations_id',
				'table'=>'conversations_message as n',
				'join'=>array(
					array('table'=>'member as m','on'=>"n.sender_id=m.member_id",'position'=>'left'),
				),
				'where_in'=>array('n.message_id'=>$data->poupmessage),
				'order'=>array(array('n.message_id','asc')),
				));
				if($poupmessageDM){
					//dd($poupmessage);
					foreach($poupmessageDM as $k=>$message){
						$poupmessage[$k]=$message;
						if($message->offer_id){
							$poupmessage[$k]->template_content='Offer';	
						}
						$poupmessage[$k]->template_content=strip_tags($poupmessage[$k]->template_content);
						if($message->sender_id){
							$poupmessage[$k]->member_name=getUserName($message->sender_id);
						}else{
							$poupmessage[$k]->member_name='Admin';
						}
						$poupmessage[$k]->logo=getMemberLogo($message->sender_id);
						$poupmessage[$k]->url=get_link('MessageBoard').'/'.$message->conversations_id;
						$poupmessage[$k]->date=date('H:i',strtotime($message->sending_date)).dateFormat($message->sending_date,'F d, Y');
					}
				}
				$this->load->helper('file');
				$arrayW=array(
					'poupmessage'=>array(),
				);
				if ( !write_file($filenamemessage, json_encode($arrayW), 'w')){
					echo 'Unable to write the file';
				}
			}
			$countM=count($poupmessageDM);
		}
		
		$array=array(
		'unread'=>$unread,
		'unreadMessage'=>$countM,
		'poupmessage'=>$poupmessage,
		);
	
	echo json_encode($array);
	}
	public function details($nid){
		$dataN=getData(array(
		'select'=>'notification_url,notification_id',
		'table'=>'notifications',
		'where'=>array('notification_id'=>$nid,'receiver_id'=>$this->member_id),
		'single_row'=>true
		));
		if($dataN){
			updateTable('notifications',array('is_read'=>1),array('notification_id'=>$dataN->notification_id));
			$count_notic=$this->db->where('receiver_id',$this->member_id)->where('is_deleted <>',1)->where('is_read',0)->from('notifications')->count_all_results();
			$filename=ABS_USERUPLOAD_PATH."ECnote/".$this->member_id.".echo";
			if(file_exists($filename)){
				$this->load->helper('file');
				$data=json_decode(file_get_contents($filename));
				$arrayW=array(
					'unread'=>$count_notic,
					'poupmessage'=>$data->poupmessage,
				);
				if ( !write_file($filename, json_encode($arrayW), 'w')){
					echo 'Unable to write the file';
				}
			}
			redirect(get_link('homeURL').$dataN->notification_url);
		}else{
			redirect(get_link('homeURL'));
		}
	}
	public function notificationlist(){
		$lang=getSetlang();
		$arr=array(
		'select'=>'nt_n.template_content,m.member_name,n.notification_id,n.sender_id,n.notification_date,n.notification_template,n.is_read',
		'table'=>'notifications as n',
		'join'=>array(
			array('table'=>'notifications_template as nt','on'=>'n.notification_template=nt.template_key','position'=>'left'),
			array('table'=>'notifications_template_names as nt_n','on'=>"nt.notification_template_id=nt_n.notification_template_id and nt_n.lang='".$lang."'",'position'=>'left'),
			array('table'=>'member as m','on'=>"n.sender_id=m.member_id",'position'=>'left'),
		),
		'where_in'=>array('n.receiver_id'=>$this->member_id),
		'where'=>array('n.is_deleted <>'=>1),
		'order'=>array(array('n.notification_id','desc')),
		'limit'=>4
		);
		$data['notification']=getData($arr);
		
		$data['notification_count']=$this->db->where('receiver_id',$this->member_id)->from('notifications')->count_all_results();
		$templateLayout=array('view'=>'notification-list','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function deletenotification($nid=''){
		$msg['status']='FAIL';
		$dataN=getData(array(
		'select'=>'notification_url,notification_id',
		'table'=>'notifications',
		'where'=>array('notification_id'=>$nid,'receiver_id'=>$this->member_id),
		'single_row'=>true
		));
		if($dataN){
			updateTable('notifications',array('is_deleted'=>1),array('notification_id'=>$dataN->notification_id));
			$count_notic=$this->db->where('receiver_id',$this->member_id)->where('is_deleted <>',1)->where('is_read',0)->from('notifications')->count_all_results();
			$filename=ABS_USERUPLOAD_PATH."ECnote/".$this->member_id.".echo";
			if(file_exists($filename)){
				$this->load->helper('file');
				$data=json_decode(file_get_contents($filename));
				$arrayW=array(
					'unread'=>$count_notic,
					'poupmessage'=>$data->poupmessage,
				);
				if ( !write_file($filename, json_encode($arrayW), 'w')){
					echo 'Unable to write the file';
				}
			}
			$msg['status']='OK';
		}
	echo json_encode($msg);
	}
}
?>