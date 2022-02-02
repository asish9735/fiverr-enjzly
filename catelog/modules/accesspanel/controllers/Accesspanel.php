<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accesspanel extends MX_Controller {

	function __construct()
	{
		loadModel('accesspanel_model');
			parent::__construct();
	}
	public function index()
	{
		$data=array();
		$data['load_js']=load_js(array('mycustom.js'));
		$data['seo_tags']=array(
		'meta_title'=>'Login  for an account',
		'meta_key'=>'',
		'meta_description'=>'Login for an account on , a fast growing freelance marketplace, where sellers provide their services at extremely affordable prices.',
		'seo_images'=>array(),
		);
		$templateLayout=array('view'=>'login','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function userloginCheckAjax()
	{
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		fromVRules('seller_user_name', 'Username', 'required|trim|xss_clean');
		fromVRules('seller_pass', 'Password', 'required|trim|xss_clean');
		if (isVRulePassed() == FALSE){
			$error=isVRuleError();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}else{
			$this->load->library('bcrypt');
			if($i==0){
				$customerData=getData(array(
				'select'=>'a.access_user_id,a.access_user_password,login_status,a.access_username',
				'table'=>'access_panel a',
				'where'=>array('a.access_username'=>trim(post('seller_user_name'))),
				'single_row'=>true,
				));
				$password=post('seller_pass');
				$mdpass= md5(post('seller_pass'));
				//if($customerData && md5(post('seller_pass'))==$customerData->access_user_password){
				if($customerData && ($this->bcrypt->check_password($password, $customerData->access_user_password) || $mdpass==$customerData->access_user_password )){
					if($mdpass==$customerData->access_user_password){
						$hash = $this->bcrypt->hash_password($password);
						update(array('table'=>'access_panel','where'=>array('access_user_id'=>$customerData->access_user_id),'data'=>array('access_user_password'=>$hash)));
					}
					if($customerData->login_status==1){
						$LAST_PCI=$this->accesspanel_model->getLastActive($customerData->access_user_id);
						$customer=array('LID'=>$customerData->access_user_id,'LAST_PCI'=>$LAST_PCI['LAST_PCI'],'ACC_P_TYP'=>$LAST_PCI['TYP'],'MID'=>$LAST_PCI['MID'],'OID'=>$LAST_PCI['OID'],'UNAME'=>$customerData->access_username);
						$this->session->set_userdata('loggedUser',$customer);	
						$msg['status'] = 'OK';
						$msg['name'] = $LAST_PCI['NAME'];
						$msg['redirect'] =get_link('dashboardURL');
					}else{
						$msg['status'] = 'OK';
						$msg['is_block'] = 1;
					}
					
					
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'seller_user_name';
					$msg['errors'][$i]['message'] = 'Invalid username or password';
				}
			}	
		}		
	}
	unset($_POST);
	echo json_encode($msg);		
	}
	
	public function signup()
	{
		$data=array();
		$data['load_js']=load_js(array('mycustom.js'));
		$data['seo_tags']=array(
		'meta_title'=>'Register for an account',
		'meta_key'=>'',
		'meta_description'=>'Register for an account on , a fast growing freelance marketplace, where sellers provide their services at extremely affordable prices.',
		'seo_images'=>array(),
		);
		$data['all_country']=getAllCountry();
		$templateLayout=array('view'=>'signup','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function usersignupCheckAjax()
	{
		checkrequestajax();
		$i=0;
		$msg=array();
		$step=1;
		if($this->input->post()){
		
		fromVRules('name', 'Name', 'required|trim|xss_clean');
		fromVRules('email', 'Email', 'required|trim|xss_clean|valid_email|is_unique[access_panel.access_user_email]',array('is_unique' => 'This email already exists. Please use the reset password link'));
		fromVRules('u_name', 'username', 'required|trim|xss_clean|alpha_dash|is_unique[access_panel.access_username]',array('is_unique' => 'Already exists'));
		fromVRules('pass', 'Password', 'required|trim|xss_clean');
		//fromVRules('con_pass', 'Confirm Password', 'required|trim|xss_clean|matches[pass]');
		//fromVRules('nationality', 'country', 'required|trim|xss_clean');
		fromVRules('country', 'country', 'required|trim|xss_clean');
		//fromVRules('is_freelancer', 'is_freelancer', 'required|trim|xss_clean|numeric');
		//fromVRules('seller_phone', 'phone', 'required|trim|xss_clean|numeric|min_length[6]');
		//fromVRules('seller_mobile_code', 'mobile_code', 'required|trim|xss_clean');
		$is_freelancer=post('is_freelancer');
		$is_freelancer_d=0;
		if($is_freelancer==1){
			fromVRules('phone', 'phone', 'required|trim|xss_clean');
			$is_freelancer_d=1;
		}
		if (isVRulePassed() == FALSE){
			$error=isVRuleError();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}
		if($i==0){
			$this->load->library('bcrypt');
			$pass=post('pass');
			$hash = $this->bcrypt->hash_password($pass);
				$dataPost=array(
						'access_user_email'=>trim(post('email')),
						'access_user_password'=>$hash,
						'access_username'=>trim(post('u_name')),
						'login_status'=>1,
					);
				$LID=insertTable('access_panel',$dataPost,TRUE);
				if($LID){
					$token = md5(time().'-'.$LID);
					$code=md5(post('email'));
					$insdata=array('member_name'=>trim(strip_tags(post('name'))),'member_email'=>trim(post('email')),'member_register_date'=>date('Y-m-d H:i:s'),'is_email_verified'=>0/*,'is_freelancer'=>$is_freelancer_d*/);
					$member_id=insertTable('member',$insdata,TRUE);
					if($member_id){
						$phone=NULL;
						if($is_freelancer){
							$phone=trim(strip_tags(post('phone')));
						}
						
						$profile_name=$insdata['member_name'];
						insertTable('profile_connection',array('member_id'=>$member_id,'profile_name'=>$profile_name,'access_user_id'=>$LID,'connection_status'=>1,'is_last_active'=>1,'ip_address'=>$this->input->ip_address()),TRUE);
						insertTable('member_address',array('member_id'=>$member_id,'member_country'=>post('country')),FALSE);
						insertTable('member_basic',array('member_id'=>$member_id),FALSE);
						$insdataToken=array('access_user_id'=>$LID,'member_id'=>$member_id,'token_value'=>$token,'sent_date'=>date('Y-m-d H:i:s'),'access_ip'=>$this->input->ip_address(),'token_type'=>'REGISTER');
						insertTable('wallet',array('user_id'=>$member_id,'title'=>$profile_name,'balance'=>0),TRUE);
						//insertTable('member_stats',array('member_id'=>$member_id));
					$LAST_PCI=$this->accesspanel_model->getLastActive($LID);
					$customer=array('LID'=>$LID,'LAST_PCI'=>$LAST_PCI['LAST_PCI'],'ACC_P_TYP'=>$LAST_PCI['TYP'],'MID'=>$LAST_PCI['MID'],'OID'=>$LAST_PCI['OID'],'UNAME'=>$dataPost['access_username']);
					$this->session->set_userdata('loggedUser',$customer);
					$this->session->set_flashdata('FirstLogin',1);
					
					}
					$id=insertTable('profile_verify_token',$insdataToken,TRUE);
					if($token){
						$url=get_link('VerifyURL').$token;
						$template='email-verification';
						$data_parse=array(
						'SELLER_NAME'=>trim(post('u_name')),
						'VERIFICATION_URL'=>$url,
						);
						SendMail('',post('email'),$template,$data_parse);
						
						$template='new-registration';
						$data_parse=array(
						'MEMBER_URL'=>ADMIN_URL.'member/list_record',
						);
						SendMail('',get_option_value('admin_email'),$template,$data_parse);
						$msg['status'] = 'OK';
						$msg['name'] = $profile_name;
						//$msg['redirect'] = VPATH."user-signup-verify";
						$msg['redirect'] =get_link('dashboardURL');
					}else{
						$msg['status'] = 'FAIL';
						$msg['errors'][$i]['id'] = 'email';
						$msg['errors'][$i]['message'] = 'Token not create';
					}
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'email';
					$msg['errors'][$i]['message'] = 'Error in process';
				}
			
				
		}		
	}
	unset($_POST);
	echo json_encode($msg);		
	}
	public function forgot() {
		$data=array();
		$data['load_js']=load_js(array('mycustom.js'));
		$data['seo_tags']=array(
		'meta_title'=>'Register for an account',
		'meta_key'=>'',
		'meta_description'=>'Register for an account on , a fast growing freelance marketplace, where sellers provide their services at extremely affordable prices.',
		'seo_images'=>array(),
		);
		$templateLayout=array('view'=>'forgot','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function userforgotCheckAjax()
	{
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		fromVRules('forgot_email', 'Email', 'required|trim|xss_clean|valid_email');
		if (isVRulePassed() == FALSE){
			$error=isVRuleError();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}else{

			if($i==0){
				$customerData=getData(array(
				'select'=>'a.access_user_id,a.access_user_password,login_status',
				'table'=>'access_panel a',
				'where'=>array('a.access_user_email'=>trim(post('forgot_email'))),
				'single_row'=>true,
				));
				if($customerData){
					$LAST_PCI=$this->accesspanel_model->getLastActive($customerData->access_user_id);
					$token = md5(time().'-'.$customerData->access_user_id);
					$insdataToken=array('access_user_id'=>$customerData->access_user_id,'member_id'=>$LAST_PCI['MID'],'token_value'=>$token,'sent_date'=>date('Y-m-d H:i:s'),'access_ip'=>$this->input->ip_address(),'token_type'=>'FORGOT');
					delete('profile_verify_token',array('access_user_id'=>$customerData->access_user_id,'token_type'=>'FORGOT'));
					$id=insertTable('profile_verify_token',$insdataToken,TRUE);
					$url=VPATH."verify-user-forgot/".$token;
					$template='forgot-password';
					$data_parse=array(
					'SELLER_NAME'=>getUserName($LAST_PCI['MID']),
					'VERIFY_URL'=>$url,
					);
					SendMail('',post('forgot_email'),$template,$data_parse);
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('homeURL');
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'forgot_email';
					$msg['errors'][$i]['message'] = 'Email not match ';
				}
			}	
		}		
	}
	unset($_POST);
	echo json_encode($msg);		
	}
	public function resetpassword($verifycode=''){
		$data=array();
		$data['verifycode']=$verifycode;
		$data['is_valid']=0;
		if($verifycode){
			$time=date('Y-m-d H:i:s',strtotime('-1 hours'));
			$verifyData=getData(array(
				'select'=>'a.member_id',
				'table'=>'profile_verify_token a',
				'where'=>array('a.token_value'=>$verifycode,'token_type'=>'FORGOT','sent_date >='=>$time),
				'single_row'=>true,
				));
			if($verifyData){
				$data['is_valid']=1;
			}
		}
		$templateLayout=array('view'=>'resetpassword','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function signout(){
		$this->loggedUser=$this->session->userdata('loggedUser');
		if($this->loggedUser){
			$this->member_id=$this->loggedUser['MID'];
			$this->db->where('user_id',$this->member_id)->delete('online_user');
		}
		$this->session->unset_userdata('loggedUser');
		redirect(get_link('homeURL'));
		
		
		/*$this->session->unset_userdata('loggedUser');
		$data=array();
		$templateLayout=array('view'=>'logout','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);*/
	}
	public function userresetCheckAjax()
	{
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		fromVRules('verifycode', 'verifycode', 'required|trim|xss_clean');
		fromVRules('new_pass', 'new password', 'required|trim|xss_clean');
		fromVRules('new_pass_again', 'confirm', 'required|trim|xss_clean|matches[new_pass]');
		if (isVRulePassed() == FALSE){
			$error=isVRuleError();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}else{

			if($i==0){
				$this->load->library('bcrypt');
				$customerData=getData(array(
				'select'=>'a.access_user_id,login_status',
				'table'=>'profile_verify_token p_v',
				'join'=>array(array('table'=>'access_panel a','on'=>'p_v.access_user_id=a.access_user_id')),
				'where'=>array('p_v.token_type'=>'FORGOT','token_value'=>trim(strip_tags(post('verifycode')))),
				'single_row'=>true,
				));
				if($customerData){
					$pass=post('new_pass');
					$hash = $this->bcrypt->hash_password($pass);
					updateTable('access_panel',array('access_user_password'=>$hash),array('access_user_id'=>$customerData->access_user_id));
					delete('profile_verify_token',array('access_user_id'=>$customerData->access_user_id,'token_type'=>'FORGOT'));
					
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('loginURL');
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'verifycode';
					$msg['errors'][$i]['message'] = 'Invalid verify code';
				}
			}	
		}		
	}
	unset($_POST);
	echo json_encode($msg);		
	}
	public function userverify($verifycode=''){
		$data=array();
		$data['verifycode']=$verifycode;
		$data['is_valid']=0;
		if($verifycode){
			$time=date('Y-m-d H:i:s',strtotime('-1 hours'));
			$verifyData=getData(array(
				'select'=>'a.member_id',
				'table'=>'profile_verify_token a',
				'where'=>array('a.token_value'=>$verifycode,'token_type'=>'REGISTER','sent_date >='=>$time),
				'single_row'=>true,
				));
			if($verifyData){
				$data['is_valid']=1;
				updateTable('member',array('is_email_verified'=>1),array('member_id'=>$verifyData->member_id));
				delete('profile_verify_token',array('member_id'=>$verifyData->member_id,'token_type'=>'REGISTER'));
				
				$member_id=$verifyData->member_id;
				$RECEIVER_EMAIL=getFieldData('member_email','member','member_id',$member_id);
				$data_parse=array(
				'SELLER_NAME'=>getUserName($member_id),
				);
				$template='user-email-verified';
				SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
				
			/*	loadModel('notifications/notification_model');
				$notificationData=array(
				'sender_id'=>0,
				'receiver_id'=>$member_id,
				'template'=>'welcome_notification_verified',
				'url'=>$this->config->item('dashboardURL'),
				'content'=>json_encode(array('MID'=>$member_id)),
				);
				$this->notification_model->savenotification($notificationData);*/
			}
		}
		$templateLayout=array('view'=>'verify-user','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	
	/*public function tologin(){
		$email=get('email');
		$token=get('token');
		$vtoken=md5(date('Y-m-d').'-'.'YMD');
		$customerData=getData(array(
				'select'=>'a.access_user_id,a.access_user_password,login_status,a.access_username',
				'table'=>'access_panel a',
				'where'=>array('a.access_user_email'=>trim($email)),
				'single_row'=>true,
				));
				if($customerData && $vtoken==$token){
					if($customerData->login_status==1){
						$LAST_PCI=$this->accesspanel_model->getLastActive($customerData->access_user_id);
						$customer=array('LID'=>$customerData->access_user_id,'LAST_PCI'=>$LAST_PCI['LAST_PCI'],'ACC_P_TYP'=>$LAST_PCI['TYP'],'MID'=>$LAST_PCI['MID'],'OID'=>$LAST_PCI['OID'],'UNAME'=>$customerData->access_username);
						$this->session->set_userdata('loggedUser',$customer);	
						$msg['status'] = 'OK';
						$msg['name'] = $LAST_PCI['NAME'];
						redirect(get_link('dashboardURL'));
					}
					
					
				}
	}*/
	
}
