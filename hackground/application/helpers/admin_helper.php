<?php
defined('BASEPATH') OR exit('No direct script access allowed');



if(!function_exists('admin_log_check')){
	
	
	function admin_log_check(){
		
		$admin_id = get_session('admin_id');
		$flag = FALSE;
        $curr_url = base_url(uri_string());
        $get = get();
        if($get){
            $get = "?".http_build_query($get);
            $curr_url .= $get;
        }
        if (!$admin_id) {
            $flag = TRUE;
        }
	
        if ($flag) {
			
			redirect(base_url('login/?ref='.urlencode($curr_url)));
        }
		
	}
	
}


if(!function_exists('breadcrumb')){
	
	function breadcrumb($option=array()){
		$html = '<ol class="breadcrumb">';
       $html .= '<li><a href="'.base_url().'"> Home</a></li>';
		if($option){
			foreach($option as $v){
				if(empty($v['path']) || $v['path'] == '#'){
					$html .= ' <li class="active">'.$v['name'].'</li>';
				}else{
					$html .= ' <li><a href="'.$v['path'].'">'.$v['name'].'</a></li>';
				}
				
			}  
		}
	  
	  $html .= '</ol>';
	  
	  return $html;
	  
	}
	
}


if(!function_exists('get_setting')){
	
	function get_setting($key=''){
		$val = getField('setting_value', 'settings', 'setting_key', $key);
		return $val;
	}
	
}




if(!function_exists('get_lang')){
	
	function get_lang(){
		$lang = get_setting('language');
		$lang_array = explode(',', $lang);
		return $lang_array;
	}
	
}



if(!function_exists('admin_default_lang')){
	
	function admin_default_lang(){
		$lang = get_setting('admin_default_lang');
		return $lang;
	}
	
}


if(!function_exists('get_admin_role')){
	
	function get_admin_role(){
		$admin = get_session('admin_detail');
	
		$role_id = !empty($admin['role_id']) ? $admin['role_id'] : 0;
		
		return $role_id;
	}
	
}

if(!function_exists('is_super_admin')){
	
	function is_super_admin($admin_id=''){
		if(!$admin_id){
			$admin_id = get_session('admin_id');
		}
		
		$super_admin = getField('super_admin', 'admin', 'admin_id', $admin_id);
		if($super_admin == '1'){
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
	
}


if(!function_exists('get_table')){
	
	function get_table($table='', $where=array()){
		$ci = &get_instance();
		$ci->db->select('*')
			->from($table)
			->where($where);
			
		$result = $ci->db->get()->result_array();
		return $result;
	}
	
}


if(!function_exists('print_select_option')){
	
	
	function print_select_option($array=array(), $value='', $name='', $selected=''){
		if(count($array) > 0){
			
			if(!empty($value) && !empty($name)){
				
				foreach($array as $k => $v){
					$select = '';
					
					if(!empty($selected)){
						if($selected == $v[$value]){
							$select = 'selected';
						}
					}
					if($select){
						echo  '<option value="'.$v[$value].'" '.$select.'>'.$v[$name].'</option>';
					}else{
						echo  '<option value="'.$v[$value].'">'.$v[$name].'</option>';
					}
					
				
				}
			
			}
			
		}
		
	}
	
}

if(!function_exists('format_money')){
	
	
	function format_money($amount=0){
		return number_format($amount, 2, '.', '');
	}
	
}

if(!function_exists('format_date_time')){
	
	
	function format_date_time($time=''){
		return date('d M,Y h:i A', strtotime($time));
	}
	
}

if(!function_exists('get_wallet_balance')){
	
	
	function get_wallet_balance($wallet_id=''){
		$balance = getField('balance', 'wallet', 'wallet_id', $wallet_id);
		return $balance;
	}
	
}

if(!function_exists('get_editor')){
	
	
	function get_editor($input_id=''){
		$ckeditor_url = ADMIN_COMPONENT.'/ckeditor/ckeditor.js';
		$script = <<<EOD
	
	<script>
		$(document).ready(function(){
			if(typeof CKEDITOR == 'undefined'){
				var scriptTag = document.createElement('script');
				scriptTag.type = 'text/javascript';
				scriptTag.src = '$ckeditor_url';
				scriptTag.onload = function(){
					CKEDITOR.replace('$input_id');
				};
				document.body.appendChild(scriptTag);
			}else{
				CKEDITOR.replace('$input_id');
			}
			
		});
	</script>
		
EOD;

	return $script;
		
	}
	
}

if(!function_exists('check_wallet')){
	
	// function defination here 
	
	function check_wallet($wallet_id='',  $txn_id='0'){
		
		$ci = &get_instance();
		
		$res = $ci->db->select("(sum(tr.credit) - sum(tr.debit)) as balance")
			->from('wallet_transaction_row tr')
			->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
			->where('tr.wallet_id', $wallet_id)
			->where('t.status', 1)
			->get()->row_array();
		
		$txn_balance = $res['balance'];
		
		$wallet_balance = getField('balance', 'wallet', 'wallet_id', $wallet_id);
		
		if($wallet_balance != $txn_balance){
			
			$notification = 'Wallet Error ! Wallet ID # : '.$wallet_id.' after transaction #' . $txn_id;
			
			error_log($notification);
			
			notify_admin($notification);
			
			
		}
		
		
	}
	
}

if(!function_exists('update_wallet_balance')){
	
	// function defination here 
	
	function update_wallet_balance($wallet_id='', $amount=''){
		
		$ci = &get_instance();
		
		return $ci->db->where('wallet_id', $wallet_id)->update('wallet', array('balance' => $amount));
		
	}
	
}
function SendMail($from='', $to, $template, $data_parse,$type='html',$bcc=array(),$cc=array(),$data_subject=array()) {
 		$CI = get_instance();
 		$mailemailID=get_setting('admin_email');
		$name=get_setting('website_name');
		$site_logo=URL.'themes/'.get_setting('active_theme').'/assets/images/'.LOGO_NAME;
		$default_lang=get_setting('admin_default_lang');
		$mailcontent=$CI->db->select('m.template_id,mt_n.template_content,mt_n.template_subject')->from('mailtemplate as m')->join('mailtemplate_names as mt_n',"m.template_id=mt_n.template_id and mt_n.lang='".$default_lang."'",'left')->where('m.template_type',$template)->get()->row();
 		
       if($mailcontent){
            $subject = $mailcontent->template_subject;
            $contents = $mailcontent->template_content;
	   }else{
	   		 $contents = 'Invalid Template';
            $subject ='Invalid Template';
	   }
        if($data_subject){
			foreach ($data_subject as $key => $val) {
           	 $subject = str_replace('{' . $key . '}', $val, $subject);
        	}
		}
		$preparse=array(
		'WEBSITE_NAME'=>$name,
		'WEBSITE_LOGO'=>"<img src='".$site_logo."' width='100' >",
		'ADMIN_URL'=>ADMIN_URL,
		);
		foreach ($data_parse as $key => $val) {
            $contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
            
        }
        foreach($preparse as $key=>$val){
			$contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
		}
 		if(SET_EMAIL_CRON==1 && $to!=''){
			
 		$pending_emails=array(
 		'to_email'=>$to,
 		'email_subject'=>$subject,
 		'email_content'=>$contents,
 		'request_date'=>date('Y-m-d H:i:s'),
 		'email_unique_id'=>time().'_'.rand(1,10000),
 		);
 		if($data_parse && array_key_exists('PRIORITY',$data_parse)){
			$pending_emails['process_order']=$data_parse['PRIORITY'];
		}
 		$CI->db->insert('pending_emails',$pending_emails);
 		return 1;
 		die;
		}
 		
 		$send='';
 		$CI->load->library("PhpMailerLib");
        $mail = $CI->phpmailerlib->load();
        try {
        	$user=get_setting('smtp_user');
        	$mail->SMTPDebug = 0;
        	$mail->isSMTP();
        	$mail->Host = get_setting('smtp_host'); 
        	$mail->SMTPAuth =true; 
        	$mail->Username = $user;
        	$mail->Password = get_setting('smtp_pass');                           // SMTP password
		    $mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = get_setting('smtp_port');                                    // TCP port to connect to
		    //Recipients
		    $mail->setFrom($user);
		    $mail->addAddress($to);
		    $mail->addReplyTo($user);
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body    = $contents;
		    $send=$mail->send();
        	$mail->ClearAllRecipients(); 
    		$mail->ClearAttachments();   //Remove all attachements
        } catch (Exception $e) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
       	ob_clean();
        return $send;
    }
 function SendMailCI($from='', $to, $template, $data_parse,$type='html',$bcc=array(),$cc=array(),$data_subject=array()) {
 		$CI = get_instance();
 		$config['protocol'] = get_setting('protocol');
		$config['smtp_host'] = get_setting('smtp_host');
		$config['smtp_port'] = get_setting('smtp_port');
		$config['smtp_user'] = get_setting('smtp_user');
		$config['smtp_pass'] = get_setting('smtp_pass');
		$config['mailtype'] = get_setting('mailtype');
		$config['charset'] = get_setting('charset'); 
		
	
 		$mailemailID=get_setting('admin_email');
		$name=get_setting('website_name');
		$site_logo=URL.'themes/'.get_setting('active_theme').'/assets/images/'.LOGO_NAME;
		$default_lang=get_setting('admin_default_lang');
		$mailcontent=$CI->db->select('m.template_id,mt_n.template_content,mt_n.template_subject')->from('mailtemplate as m')->join('mailtemplate_names as mt_n',"m.template_id=mt_n.template_id and mt_n.lang='".$default_lang."'",'left')->where('m.template_type',$template)->get()->row();
 		
       if($mailcontent){
            $subject = $mailcontent->template_subject;
            $contents = $mailcontent->template_content;
	   }else{
	   		 $contents = 'Invalid Template';
            $subject ='Invalid Template';
	   }
        if($data_subject){
			foreach ($data_subject as $key => $val) {
           	 $subject = str_replace('{' . $key . '}', $val, $subject);
        	}
		}
		$preparse=array(
		'WEBSITE_NAME'=>$name,
		'WEBSITE_LOGO'=>"<img src='".$site_logo."' width='100' >",
		'ADMIN_URL'=>ADMIN_URL,
		);
		foreach ($data_parse as $key => $val) {
            $contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
            
        }
        foreach($preparse as $key=>$val){
			$contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
		}
		
		//$to='asish9735@gmail.com';
        $CI->load->library('email');
        $CI->email->initialize($config);
        //$CI->load->library('email', $config);
		$CI->email->from($config['smtp_user'], $name);
		//$CI->email->reply_to($mailemailID, $name);

        $CI->email->to($to);
        //$CI->email->bcc('asish9735@gmail.com');
        $CI->email->subject($subject);
		$CI->email->set_mailtype($type);
		if($bcc){
			$CI->email->bcc($bcc);	
		}
		if($cc){
			$CI->email->bcc($cc);	
		}
        $CI->email->message($contents);
        $send=$CI->email->send();
       // echo $CI->email->print_debugger();
       ob_clean();
        return $send;
    }
function getMemberLogo($member_id,$type='logo'){
	$ci = &get_instance();
	if($type=='logo'){
		$userimage=URL.'themes/'.get_setting('active_theme').'/assets/images/default/empty-image.png';
	}else{
		$userimage=URL.'themes/'.get_setting('active_theme').'/assets/images/default/empty-cover.png';
	}
	$logo=$ci->db->select('m.logo,m.banner')->where('m.member_id',$member_id)->from('member_logo as m')->get()->row();
	if($logo){
		if($type=='logo' && $logo->logo && file_exists(LC_PATH."userupload/member_logo/".$logo->logo)){
			$userimage=USER_UPLOAD.'member_logo/'.$logo->logo;
		}elseif($type!='logo' && $logo->cover && file_exists(LC_PATH."userupload/member_banner/".$logo->banner)){
			$userimage=USER_UPLOAD.'member_banner/'.$logo->banner;
		}
	}
	return $userimage;
}
 function getUserName($member_id){
 	$ci = &get_instance();
	$ci->db->select('a.access_username');
	$ci->db->from('profile_connection as p_c');
	$ci->db->join('access_panel as a','p_c.access_user_id=a.access_user_id','left');
	$ci->db->where(array('p_c.member_id'=>$member_id,'p_c.organization_id'=>NULL));
	$data=$ci->db->get()->row();
	if($data){
		return $data->access_username;
	}else{
		return $member_id;
	}
}
