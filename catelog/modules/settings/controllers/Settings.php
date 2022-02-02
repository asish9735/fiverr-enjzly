<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MX_Controller {

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
			
		}else{
			redirect(get_link('loginURL'));
		}
		parent::__construct();
	}
	public function index()
	{
		//$updateDataAddress=array('member_country'=>'IND');
		//updateTable('member_address',$updateDataAddress,array('member_id'=>$this->member_id,'member_country'=>NULL));
		$data=array();
		$data['tab']=get('tab');
		$data['load_js']=load_js(array('mycustom.js','croppie.js'));
		$data['load_css']=load_css(array('croppie.css'));
		$data['access_username']=$this->username;
		$data['member_details']=getMemberDetails($this->member_id,array('main'=>1,'member_address'=>1,'member_basic'=>1,'member_logo'=>1,'member_payment_settings'=>1));
		$data['all_nationality']=getAllNationality();
		$data['all_country']=getAllCountry();
		$data['all_mobile_codes']=$this->db->select('codes')->from('mobile_code')->where('status','1')->order_by('display_order', 'ASC')->get()->result();
		$data['all_languages']=getAlllanguages();
		$data['profile_settings']=load_view('settings/tab-profile-setting',$data,TRUE);
		$data['account_settings']=load_view('settings/tab-account-setting',$data,TRUE);
		$templateLayout=array('view'=>'settings','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);		
	}
	public function uploadattachment(){
		if($this->loggedUser){
		$config['upload_path']          = ABS_USERUPLOAD_PATH."tempfile/";
		if($this->input->get('type') && $this->input->get('type')=='main'){
			$dataimg=$this->input->post("image",FALSE);
			$formatdata=explode(';base64,',$dataimg);
			
			$image = base64_decode($formatdata[1]);
			$image_name = md5(uniqid(rand(), true));
			$filename = $image_name . '.' . 'png';
			
			$path =$config['upload_path'];
			$file_extension = pathinfo($name, PATHINFO_EXTENSION);
		
			file_put_contents($path . $filename, $image);
			if(file_exists($path . $filename)){
				$msg['status']='OK';
   				$msg['upload_response']=array('file_name'=>$filename,'original_name'=>$filename);
			}else{
				$msg['status']='FAIL';
			}
		}else{
			if($this->input->get('type') && $this->input->get('type')=='image'){
				$allowed = array('jpeg','jpg','gif','png');
				 $config['max_size']             = 1024*25;
			}else{ 
				 $msg['status']='FAIL';
				 echo json_encode($msg);
				die;
			}
        $config['allowed_types']        = implode('|',$allowed);
        $config['file_name']            = md5($this->profile_connection_id.'-'.time());
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('fileinput'))
        {
            $msg['status']='FAIL';
            $msg['error']= $this->upload->display_errors();
        }
        else
        {
        	$msg['status']='OK';
        	$upload_data=$this->upload->data();
        	$msg['upload_response']=array('file_name'=>$upload_data['file_name'],'original_name'=>$upload_data['client_name']);
        }
        }
		echo json_encode($msg);
		}
	}
	public function editprofileCheckAjax(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			$member_nationality=getFieldData('member_nationality','member_address','member_id',$this->member_id);
			//fromVRules('seller_name', 'name', 'required|trim|xss_clean');
			//fromVRules('seller_email', 'email', 'required|trim|xss_clean|valid_email');
			//fromVRules('seller_country', 'country', 'required|trim|xss_clean');
			if($member_nationality){
				
			}else{
				fromVRules('seller_nationality', 'country', 'required|trim|xss_clean');
			}
			
			fromVRules('seller_language', 'language', 'required|trim|xss_clean|numeric');
			fromVRules('seller_gender', 'gender', 'required|trim|xss_clean');
			fromVRules('seller_phone', 'phone', 'required|trim|xss_clean|numeric|min_length[6]');
			fromVRules('seller_mobile_code', 'mobile_code', 'required|trim|xss_clean');

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
					$updateData=array(
						'member_name'=>post('seller_name'),
						/*'member_email'=>post('seller_email'),*/
					);
					$member_details=getMemberDetails($this->member_id,array('main'=>1));
					if($member_details['member']->member_email!=$updateData['member_email']){
						//$updateData['is_email_verified']=0;	
					}
					//updateTable('member',$updateData,array('member_id'=>$this->member_id));
					//updateTable('wallet',array('title'=>$updateData['member_name']),array('user_id'=>$this->member_id));
					$updateDataAddress=array('member_city'=>post('seller_city'));
					//$updateDataAddress['member_country']=post('seller_country');
					if($member_nationality){
						
					}else{
						$updateDataAddress['member_nationality']=post('seller_nationality');
						updateTable('member_address',$updateDataAddress,array('member_id'=>$this->member_id));
					}
					updateTable('member_address',$updateDataAddress,array('member_id'=>$this->member_id));
					
					$updateDataBasic=array(
						'member_heading'=>strip_tags(post('seller_headline')),
						'member_overview'=>strip_tags(post('seller_about')),
						'prefer_language'=>post('seller_language'),
						'member_gender'=>post('seller_gender'),
						'member_phone'=>post('seller_phone'),
						'member_mobile_code'=>post('seller_mobile_code'),
					);
					updateTable('member_basic',$updateDataBasic,array('member_id'=>$this->member_id));
					
					$updateDataLogo=array(
						'member_id'=>$this->member_id,
					);
					if($this->input->post('userlogo')){
						$file_data=json_decode(post('userlogo'));
						if($file_data){
							if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
								rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."member_logo/".$file_data->file_name);
								$updateDataLogo['logo']=$file_data->file_name;
							}
						}
					}
					if($this->input->post('userbanner')){
						$file_data=json_decode(post('userbanner'));
						if($file_data){
							if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
								rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."member_banner/".$file_data->file_name);
								$updateDataLogo['banner']=$file_data->file_name;
							}
						}
					}
					create_update('member_logo',$updateDataLogo,array('member_id'=>$this->member_id));
					$member_details=getMemberDetails($this->member_id,array('member_logo'));
					if($updateDataLogo['logo'] && $member_details['member_logo'] && $member_details['member_logo']->logo){
						@unlink(ABS_USERUPLOAD_PATH."member_logo/".$member_details['member_logo']->logo);
					}
					if($updateDataLogo['banner'] && $member_details['member_logo'] && $member_details['member_logo']->banner){
						@unlink(ABS_USERUPLOAD_PATH."member_banner/".$member_details['member_logo']->banner);
					}
					
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('settingsURL');
				}
			}
		}
	unset($_POST);
	echo json_encode($msg);		
	}
	public function editaccountCheckAjax(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			$tab=post('section');
			if($tab=='paypal'){
				fromVRules('seller_paypal_email', 'paypal email', 'required|trim|xss_clean|valid_email');
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
						$updateData=array(
							'member_id'=>$this->member_id,
							'paypal_email'=>strpost('seller_paypal_email'),
						);
						create_update('member_payment_settings',$updateData,array('member_id'=>$this->member_id));
						$msg['status'] = 'OK';
						$msg['message'] ='PayPal email updated successfully!';
						$msg['redirect'] =get_link('settingsURL').'?tab=account';
					}
				}	
			}
			if($tab=='payoneer'){
				fromVRules('seller_payoneer_email', 'payoneer email', 'required|trim|xss_clean|valid_email');
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
						$updateData=array(
							'member_id'=>$this->member_id,
							'payoneer_email'=>post('seller_payoneer_email'),
						);
						create_update('member_payment_settings',$updateData,array('member_id'=>$this->member_id));
						$msg['status'] = 'OK';
						$msg['message'] ='Payoneer email updated successfully!';
						$msg['redirect'] =get_link('settingsURL').'?tab=account';
					}
				}	
			}
			elseif($tab=='bank'){
				fromVRules('bank_account_number', 'IBAN', 'required|trim|xss_clean');
			/*	fromVRules('bank_account_name', 'account name', 'required|trim|xss_clean');*/
				fromVRules('bank_name', 'bank name', 'required|trim|xss_clean');
				fromVRules('bank_code', 'Swift', 'required|trim|xss_clean');
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
						$updateData=array(
							'member_id'=>$this->member_id,
							'bank_account_number'=>strip_tags(post('bank_account_number')),
							/*'bank_account_name'=>post('bank_account_name'),*/
							'bank_name'=>strip_tags(post('bank_name')),
							'bank_code'=>strip_tags(post('bank_code')),
						);
						create_update('member_payment_settings',$updateData,array('member_id'=>$this->member_id));
						$msg['status'] = 'OK';
						$msg['message'] ='Bank details updated successfully!';
						$msg['redirect'] =get_link('settingsURL').'?tab=account';
					}
				}	
			}
			elseif($tab=='m_money'){
				fromVRules('m_account_number', 'account number', 'required|trim|xss_clean');
				fromVRules('m_account_name', 'account name', 'required|trim|xss_clean');
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
						$updateData=array(
							'member_id'=>$this->member_id,
							'mobile_money_account_number'=>post('m_account_number'),
							'mobile_money_account_name'=>post('m_account_name'),
						);
						create_update('member_payment_settings',$updateData,array('member_id'=>$this->member_id));
						$msg['status'] = 'OK';
						$msg['message'] ='Mobile Money Updated Successfully!';
						$msg['redirect'] =get_link('settingsURL').'?tab=account';
					}
				}	
			}
			elseif($tab=='bitcoin'){
				fromVRules('bitcoin_seller_wallet', 'wallet address', 'required|trim|xss_clean');
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
						$updateData=array(
							'member_id'=>$this->member_id,
							'bitcoin_wallet_address'=>post('bitcoin_seller_wallet'),
						);
						create_update('member_payment_settings',$updateData,array('member_id'=>$this->member_id));
						$msg['status'] = 'OK';
						$msg['message'] ='Wallet Address updated successfully!';
						$msg['redirect'] =get_link('settingsURL').'?tab=account';
					}
				}	
			}
			elseif($tab=='password'){
				fromVRules('old_pass', 'old password', 'required|trim|xss_clean');
				fromVRules('new_pass', 'new Password', 'required|trim|xss_clean');
				fromVRules('new_pass_again', 'confirm Password', 'required|trim|xss_clean|matches[new_pass]');
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
					$getData=getData(array(
							'select'=>'a.access_user_password',
							'table'=>'access_panel as a',
							'where'=>array('a.access_user_id'=>$this->access_user_id),
							'single_row'=>true,
						)
					);	
					$password=post('old_pass');
					//if($getData && $getData->access_user_password==md5(post('old_pass'))){
					if($getData && $this->bcrypt->check_password($password, $getData->access_user_password)){
						
					}else{
						$msg['status'] = 'FAIL';
						$msg['is_invalid'] = '1';
						$msg['errors'][$i]['id'] = 'old_pass';
						$msg['errors'][$i]['message'] = 'Invalid password';
						$i++;
					}
					if($i==0){
						$pass=post('new_pass');
						$hash = $this->bcrypt->hash_password($pass);
						$updateData=array(
							'access_user_password'=>$hash,
						);
						updateTable('access_panel',$updateData,array('access_user_id'=>$this->access_user_id));
						$msg['status'] = 'OK';
						$msg['message'] ='Password updated successfully. Login with your new password.';
						$msg['redirect'] =get_link('logoutURL');
					}
				}	
			}
			elseif($tab=='deactivate'){
				fromVRules('deactivate_reason', 'old password', 'required|trim|xss_clean');
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
						updateTable('buyer_requests',array('request_status'=>REQUEST_PAUSED),array('seller_id'=>$this->member_id,'request_status'=>REQUEST_ACTIVE));
						updateTable('proposals',array('proposal_status'=>PROPOSAL_PAUSED),array('proposal_seller_id'=>$this->member_id,'proposal_status'=>PROPOSAL_ACTIVE));
						$updateData=array(
							'login_status'=>2,
						);
						updateTable('access_panel',$updateData,array('access_user_id'=>$this->access_user_id));
						updateTable('member',array('is_inactive'=>1),array('member_id'=>$this->member_id));
						$msg['status'] = 'OK';
						$msg['message'] ='Your account has been deactivated successfully. Goodbye!';
						$msg['redirect'] =get_link('logoutURL');
					}
				}	
			}elseif($tab=='language'){
				fromVRules('language_id', 'name', 'required|trim|xss_clean|numeric');
				fromVRules('language_level', 'level', 'required|trim|xss_clean|numeric');
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
						$updateData=array(
							'member_id'=>$this->member_id,
							'language_id'=>post('language_id'),
							'language_level'=>post('language_level'),
						);
						create_update('member_languages',$updateData,array('member_id'=>$this->member_id,'language_id'=>post('language_id')));
						$msg['status'] = 'OK';
						$msg['message'] ='Freelancer language updated Successfully!';
					}
				}
			}elseif($tab=='skills'){
				fromVRules('skill_id', 'name', 'required|trim|xss_clean|numeric');
				fromVRules('skill_level', 'level', 'required|trim|xss_clean|numeric');
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
						$updateData=array(
							'member_id'=>$this->member_id,
							'skill_id'=>post('skill_id'),
							'skill_level'=>post('skill_level'),
						);
						create_update('member_skills',$updateData,array('member_id'=>$this->member_id,'skill_id'=>post('skill_id')));
						$msg['status'] = 'OK';
						$msg['message'] ='Freelancer skills updated Successfully!';
					}
				}	
			}elseif($tab=='deleteLang'){
				fromVRules('language_id', 'name', 'required|trim|xss_clean|numeric');
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
						$is_delete=delete('member_languages',array('language_id'=>post('language_id'),'member_id'=>$this->member_id));
						if($is_delete){
							$msg['status'] = 'OK';
							$msg['message'] ='One Language has been deleted.';
						}else{
							$msg['status'] = 'FAIL';
						}
						
					}
				}
			}elseif($tab=='deleteSkill'){
				fromVRules('skill_id', 'name', 'required|trim|xss_clean|numeric');
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
						$is_delete=delete('member_skills',array('skill_id'=>post('skill_id'),'member_id'=>$this->member_id));
						if($is_delete){
							$msg['status'] = 'OK';
							$msg['message'] ='One Skill has been deleted.';
						}else{
							$msg['status'] = 'FAIL';
						}
						
					}
				}	
			}
		}
	unset($_POST);
	echo json_encode($msg);	
	}
	public function resendemail(){
		checkrequestajax();
		$msg=array();
		$member_details=getMemberDetails($this->member_id,array('main'=>1));
		$token = md5(time().'-'.$this->access_user_id);
		delete('profile_verify_token',array('access_user_id'=>$customerData->access_user_id,'token_type'=>'REGISTER'));
		$insdataToken=array('access_user_id'=>$this->access_user_id,'member_id'=>$this->member_id,'token_value'=>$token,'sent_date'=>date('Y-m-d H:i:s'),'access_ip'=>$this->input->ip_address(),'token_type'=>'REGISTER');
		$id=insertTable('profile_verify_token',$insdataToken,TRUE);
		$url=get_link('VerifyURL').$token;
		$template='email-verification';
		$data_parse=array(
		'SELLER_NAME'=>getUserName($member_details['member']->member_id),
		'VERIFICATION_URL'=>$url,
		);
		SendMail('',$member_details['member']->member_email,$template,$data_parse);
		$msg['status']='OK';			
		unset($_POST);
		echo json_encode($msg);	
	}
}
