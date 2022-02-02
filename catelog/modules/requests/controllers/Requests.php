<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requests extends MX_Controller {

	function __construct()
	{	
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->member_id=$this->loggedUser['MID'];	
			$this->organization_id=$this->loggedUser['OID'];
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->profile_connection_id=$this->loggedUser['LAST_PCI'];
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
		}elseif($this->router->fetch_method()=='viewoffer'){
			
		}else{
			redirect(get_link('loginURL'));
		}
			parent::__construct();
	}
	
	public function post_request()
	{
		$data=array();
		$data['all_category']=getAllCategory();
		$data['all_delivery_times']=getAllDeliveryTimes();
		$templateLayout=array('view'=>'post-request','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function edit_request($request_id='',$token='')
	{
		$data=array();
		$verify_token=md5('FVRR'.'-'.date("Y-m-d").'-'.$request_id);
		$data['token']=$token;
		if($verify_token==$token){
			$data['request_details']=getRequestDetails($request_id);
			$data['all_category']=getAllCategory();
			$data['all_sub_category']=getAllSubCategory($data['request_details']['request_category']->category_id);
			$data['all_delivery_times']=getAllDeliveryTimes();
			$templateLayout=array('view'=>'edit-request','type'=>'default','buffer'=>FALSE,'theme'=>'');
			load_template($templateLayout,$data);
		}else{
			redirect(get_link('managerequestURL'));
		}
	}
	public function manage_request()
	{
		loadModel('request_model');
		$data=array();
		$data['active_request']=$data['paused_request']=$data['pending_request']=$data['unapproved_request']=array();
		$data['all_request']=$this->request_model->getRequest(array('member_id'=>$this->member_id));
		if($data['all_request']){
			foreach($data['all_request']  as $request){
				$request->offer=0;
				if($request->request_status==REQUEST_PENDING){
					$data['pending_request'][]=$request;
				}elseif($request->request_status==REQUEST_ACTIVE){
					$request->offer=$this->db->where('request_id',$request->request_id)->where('status',1)->from('send_offers')->count_all_results();
					$data['active_request'][]=$request;
				}elseif($request->request_status==REQUEST_PAUSED){
					$request->offer=$this->db->where('request_id',$request->request_id)->where('status',1)->from('send_offers')->count_all_results();
					$data['paused_request'][]=$request;
				}elseif($request->request_status==REQUEST_UNAPPROVED){
					$data['unapproved_request'][]=$request;
				}
			}
		}
		$templateLayout=array('view'=>'manage-request','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function postrequestCheckAjax(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		fromVRules('request_title', 'title', 'required|trim|xss_clean');
		fromVRules('request_description', 'description', 'required|trim|xss_clean|max_length[380]');
		fromVRules('category_id', 'category', 'required|trim|xss_clean');
		if(post('category_id')>0){
			fromVRules('sub_category_id', 'sub category', 'required|trim|xss_clean');
		}
		fromVRules('delivery_time', 'delivery time', 'required|trim|xss_clean');
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
				$buyer_requests=array(
					'seller_id'=>$this->member_id,
					'request_title'=>strip_tags(post('request_title')),
					'request_description'=>strip_tags(post('request_description')),
					'delivery_time'=>post('delivery_time'),
					'request_date'=>date('Y-m-d H:i:s'),
					'request_budget'=>post('request_budget'),
					'request_status'=>REQUEST_PENDING,
					);
				$request_id=insertTable('buyer_requests',$buyer_requests,TRUE);
				if($request_id){
					$request_category=array(
					'request_id'=>$request_id,
					'category_id'=>post('category_id'),
					'category_subchild_id'=>post('sub_category_id'),
					);
					insertTable('request_category',$request_category);
					
					if(post('projectfile')){
						$projectfiles=post('projectfile');
						foreach($projectfiles as $file){
							$file_data=json_decode($file);
							if($file_data){
								if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
									rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."request-files/".$file_data->file_name);
									$ext=explode('.',$file_data->file_name);
									$files=array(
									'original_name'=>$file_data->original_name,
									'server_name'=>$file_data->file_name,
									'upload_time'=>date('Y-m-d H:i:s'),
									'file_ext'=>strtolower(end($ext)),
									);
									$file_id=insertTable('files',$files,TRUE);
									if($file_id){
										$request_files=array(
										'request_id'=>$request_id,
										'file_id'=>$file_id,
										);
										insertTable('request_files',$request_files);
									}
								}
							}
						}
					}
					$BUYER_NAME=getUserName($this->member_id);
					$REQUEST_CATEGORY=getAllCategory(array('category_id'=>$request_category['category_id']));
					$template='new-request-create';
					$data_parse=array(
					'BUYER_NAME'=>$BUYER_NAME,
					'REQUEST_TITLE'=>$buyer_requests['request_title'],
					'REQUEST_CATEGORY'=>$REQUEST_CATEGORY->name,
					'REQUEST_STATUS'=>'Pending',
					'ADMIN_REQUEST_LINK'=>ADMIN_URL.'buyer_request/list_record',
					);
					SendMail('',get_option_value('admin_email'),$template,$data_parse);
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('managerequestURL');
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'request_title';
					$msg['errors'][$i]['message'] = 'Error occur';
				}
			}	
		}		
	}
	unset($_POST);
	echo json_encode($msg);	
		
	}
	public function editrequestCheckAjax(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			$request_id=post('rid');
			$token=post('token');
			$verify_token=md5('FVRR'.'-'.date("Y-m-d").'-'.$request_id);
			if($token==$verify_token){
				$arr=array(
					'select'=>'r.request_id',
					'table'=>'buyer_requests r',
					'where'=>array('r.seller_id'=>$this->member_id,'r.request_id'=>$request_id),
					'single_row'=>true,
				);
				$check_request=getData($arr);
				if($check_request){
					$request_id=$check_request->request_id;
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'request_title';
					$msg['errors'][$i]['message'] = 'Opps! invalid request.';
	   				$i++;
				}
			}else{
				$msg['status'] = 'FAIL';
    			$msg['errors'][$i]['id'] = 'request_title';
				$msg['errors'][$i]['message'] = 'Opps! invalid request.';
   				$i++;
			}	
			if($i>0){
				unset($_POST);
				echo json_encode($msg);
				die;
			}
		fromVRules('request_title', 'title', 'required|trim|xss_clean');
		fromVRules('request_description', 'description', 'required|trim|xss_clean|max_length[380]');
		fromVRules('category_id', 'category', 'required|trim|xss_clean');
		if(post('category_id')>0){
			fromVRules('sub_category_id', 'sub category', 'required|trim|xss_clean');
		}
		fromVRules('delivery_time', 'delivery time', 'required|trim|xss_clean');
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
				$buyer_requests=array(
					'request_title'=>strip_tags(post('request_title')),
					'request_description'=>strip_tags(post('request_description')),
					'delivery_time'=>post('delivery_time'),
					'request_date'=>date('Y-m-d H:i:s'),
					'request_budget'=>post('request_budget'),
					'request_status'=>REQUEST_PENDING,
					);
				updateTable('buyer_requests',$buyer_requests,array('request_id'=>$request_id));
				if($request_id){
					delete('request_category',array('request_id'=>$request_id));
					$request_category=array(
					'request_id'=>$request_id,
					'category_id'=>post('category_id'),
					'category_subchild_id'=>post('sub_category_id'),
					);
					insertTable('request_category',$request_category);
					
					$previous_file=array();
					if(post('projectfileprevious')){
						$projectfileprevious=post('projectfileprevious');
						foreach($projectfileprevious as $file){
							$file_data_p=json_decode($file);
							if($file_data_p){
								$previous_file[]=$file_data_p->file_id;
							}
						}
					}
					if($previous_file){
						$this->db->where_not_in('file_id',$previous_file)->where('request_id',$request_id)->delete('request_files');
					}else{
						$this->db->where('request_id',$request_id)->delete('request_files');
					}
					if(post('projectfile')){
						$projectfiles=post('projectfile');
						foreach($projectfiles as $file){
							$file_data=json_decode($file);
							if($file_data){
								if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
									rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."request-files/".$file_data->file_name);
									$ext=explode('.',$file_data->file_name);
									$files=array(
									'original_name'=>$file_data->original_name,
									'server_name'=>$file_data->file_name,
									'upload_time'=>date('Y-m-d H:i:s'),
									'file_ext'=>strtolower(end($ext)),
									);
									$file_id=insertTable('files',$files,TRUE);
									if($file_id){
										$request_files=array(
										'request_id'=>$request_id,
										'file_id'=>$file_id,
										);
										insertTable('request_files',$request_files);
									}
								}
							}
						}
					}
					
					$BUYER_NAME=getUserName($this->member_id);
					$REQUEST_CATEGORY=getAllCategory(array('category_id'=>$request_category['category_id']));
					$template='new-request-create';
					$data_parse=array(
					'BUYER_NAME'=>$BUYER_NAME,
					'REQUEST_TITLE'=>$buyer_requests['request_title'],
					'REQUEST_CATEGORY'=>$REQUEST_CATEGORY->name,
					'REQUEST_STATUS'=>'Pending',
					'ADMIN_REQUEST_LINK'=>ADMIN_URL.'buyer_request/list_record',
					);
					SendMail('',get_option_value('admin_email'),$template,$data_parse);
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('managerequestURL');
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'request_title';
					$msg['errors'][$i]['message'] = 'Error occur';
				}
			}	
		}		
	}
	unset($_POST);
	echo json_encode($msg);	
		
	}
	public function uploadattachment(){
		if($this->loggedUser){
		$config['upload_path']          = ABS_USERUPLOAD_PATH."tempfile/";
		$allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4', 'zip','rar','mp3','wav','pdf','docx','doc','txt','xls','xlsx');
        $config['allowed_types']        = implode('|',$allowed);
        $config['max_size']             = 1024*50;
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
		echo json_encode($msg);
		}
	}
	public function actionrequestCheckAjax(){
		$msg=array();
		$all_action=array('pause','delete','active');
		checkrequestajax();
		if($this->input->post('rid') && $this->input->post('action')){
			$action=post('action');
			$request_id=post('rid');
			if(in_array($action,$all_action)){
				$arr=array(
					'select'=>'b_r.request_id,b_r.request_status',
					'table'=>'buyer_requests b_r',
					'where'=>array('b_r.request_id'=>$request_id,'b_r.seller_id'=>$this->member_id),
					'single_row'=>true,
				);
				$buyer_requests=getData($arr);
				if($buyer_requests){
					if($action=='pause'){
						updateTable('buyer_requests',array('request_status'=>REQUEST_PAUSED),array('request_id'=>$buyer_requests->request_id,'request_status'=>REQUEST_ACTIVE));
						$msg['message']=__('popup_request_pause_success','One request has been paused.');
					}elseif($action=='delete'){
						updateTable('buyer_requests',array('request_status'=>REQUEST_DELETED),array('request_id'=>$buyer_requests->request_id));
						$msg['message']=__('popup_request_deleted_success','One request has been deleted successfully');
					}elseif($action=='active'){
						updateTable('buyer_requests',array('request_status'=>REQUEST_ACTIVE),array('request_id'=>$buyer_requests->request_id,'request_status'=>REQUEST_PAUSED));
						$msg['message']=__('popup_request_active_success','One request has been activated.');
					}
					$msg['status']='OK';
				}else{
					$msg['status']='FAIL';
					$msg['message']='Invalid request';
				}
				
			}else{
				$msg['status']='FAIL';
				$msg['message']='Invalid request';
			}	
		}else{
			$msg['status']='FAIL';
			$msg['message']='Invalid request';
		}
		echo json_encode($msg);
	}
	public function sendoffermodal(){
		checkrequestajax();
		$data=array();
		$request_id=post('request_id');
		$arr=array(
			'select'=>'r.request_id,r.request_title,r.request_description,r.seller_id,r_c.category_subchild_id',
			'table'=>'buyer_requests r',
			'join'=>array(
				array('table'=>'request_category as r_c','on'=>'r.request_id=r_c.request_id','position'=>'left'),
			),
			'where'=>array('r.request_id'=>$request_id),
			'single_row'=>true,
		);
		$data['request_details']=getData($arr);
		$data['member_details']=getMemberDetails($this->member_id);
		
		$arr=array(
			'select'=>'p.proposal_id,p.proposal_title,p.proposal_image,p.proposal_seller_id',
			'table'=>'proposals p',
			'join'=>array(
				array('table'=>'proposal_category as p_c','on'=>'p.proposal_id=p_c.proposal_id','position'=>'left'),
			),
			'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'p.proposal_seller_id'=>$this->member_id,'p_c.category_subchild_id'=>$data['request_details']->category_subchild_id),
			'group'=>'p.proposal_id'
		);
		$data['my_proposal']=getData($arr);
		$templateLayout=array('view'=>'send-offer-modal','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function sendoffermodaldetails(){
		checkrequestajax();
		$data=array();
		$request_id=post('request_id');
		$proposal_id=post('proposal_id');
		$arr=array(
			'select'=>'r.request_id,r.request_title,r.request_description,r.seller_id',
			'table'=>'buyer_requests r',
			
			'where'=>array('r.request_id'=>$request_id),
			'single_row'=>true,
		);
		$data['request_details']=getData($arr);
		$data['member_details']=getMemberDetails($this->member_id);
		
		$arr=array(
			'select'=>'p.proposal_id,p.proposal_title,p.proposal_image,p.proposal_seller_id',
			'table'=>'proposals p',
			'where'=>array('p.proposal_seller_id'=>$this->member_id,'p.proposal_id'=>$proposal_id),
			'single_row'=>true,
		);
		$data['my_proposal_details']=getData($arr);
		$data['all_delivery_time']=getAllDeliveryTimes();
		$templateLayout=array('view'=>'send-offer-modal-details','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function saveoffer(){
		checkrequestajax();
		$i=0;
		$msg=array();
		$request_id=post('request_id');
		$proposal_id=post('proposal_id');
		if($this->input->post()){
			fromVRules('description', 'description', 'required|trim|xss_clean');
			fromVRules('request_id', 'request_id', 'required|trim|xss_clean');
			fromVRules('proposal_id', 'proposal_id', 'required|trim|xss_clean');
			fromVRules('amount', 'amount', 'required|trim|xss_clean|is_numeric|greater_than_equal_to[5]');
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
				$arr=array(
					'select'=>'p.proposal_id,p.proposal_title,p.proposal_image,p.proposal_seller_id',
					'table'=>'proposals p',
					'where'=>array('p.proposal_seller_id'=>$this->member_id,'p.proposal_id'=>$proposal_id),
					'single_row'=>true,
				);
				$my_proposal_details=getData($arr);
				$arr=array(
					'select'=>'r.request_id,r.request_title,r.request_description,r.seller_id',
					'table'=>'buyer_requests r',
					
					'where'=>array('r.request_id'=>$request_id),
					'single_row'=>true,
				);
				$request_details=getData($arr);
				if(!$my_proposal_details || !$request_details){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'request';
					$msg['errors'][$i]['message'] = 'Invalid request';
	   				$i++;
				}
				if($i==0){
					$send_offers=array(
						'request_id'=>$request_id,
						'sender_id'=>$this->member_id,
						'receiver_id'=>$request_details->seller_id,
						'proposal_id'=>$proposal_id,
						'description'=>post('description'),
						'delivery_time'=>post('delivery_time'),
						'amount'=>post('amount'),
						'status'=>0,
						'reg_date'=>date('Y-m-d H:i:s'),
					);
					$ins=insertTable('send_offers',$send_offers,TRUE);
					if($ins){
						loadModel('notifications/notification_model');
						$SENDER_NAME=getUserName($this->member_id);
						$RECEIVER_NAME=getUserName($request_details->seller_id);
						$RECEIVER_EMAIL=getFieldData('member_email','member','member_id',$request_details->seller_id);
						$url=get_link('viewofferURL').'/'.$request_id;
						$template='new-offer-for-my-request';
						$data_parse=array(
						'SENDER_NAME'=>$SENDER_NAME,
						'RECEIVER_NAME'=>$RECEIVER_NAME,
						'OFFER_URL'=>$url,
						);
						SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
						
						$notificationData=array(
						'sender_id'=>$this->member_id,
						'receiver_id'=>$request_details->seller_id,
						'template'=>'new_offer_for_my_request',
						'url'=>$this->config->item('viewofferURL').'/'.$request_id,
						'content'=>json_encode(array('RID'=>$request_id)),
						);
						$this->notification_model->savenotification($notificationData);
						
						$msg['status'] = 'OK';
						$msg['redirect'] = get_link('buyerRequests');
					}
				}
			}		
		}		
		echo json_encode($msg);
	}
	public function buyer_requests(){
		$data=array();
		$lang=getSetlang();
		$this->db->select('sc.category_subchild_id,sc_n.name');
		$this->db->from('proposal_category as pc');
		$this->db->join('proposals as p','pc.proposal_id=p.proposal_id','left');
		$this->db->join('category_subchild as sc','pc.category_subchild_id=sc.category_subchild_id','left');
		$this->db->join('category_subchild_names as sc_n',"`sc`.`category_subchild_id`=`sc_n`.`category_subchild_id` and `sc_n`.`lang`='".$lang."'",'left');
		$this->db->where('p.proposal_seller_id',$this->member_id);
		$data['seller_category']=$this->db->group_by('sc.category_id')->get()->result();
		$arr=array('select'=>'r.request_id,r.request_title,r.request_description,r.seller_id,r.delivery_time,r.request_budget,r.request_date',
				'table'=>'buyer_requests r',
				'join'=>array(
				array('table'=>'request_category as r_c','on'=>'r.request_id=r_c.request_id','position'=>'left'),
				array('table'=>'proposal_category as p_c','on'=>'r_c.category_subchild_id=p_c.category_subchild_id','position'=>'left'),
				array('table'=>'proposals as p','on'=>'p_c.proposal_id=p.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'r.seller_id=m.member_id','position'=>'left'),
				array('table'=>'send_offers as s_o','on'=>"r.request_id=s_o.request_id and s_o.sender_id='".$this->member_id."'",'position'=>'left'),
				),
				
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'r.request_status'=>REQUEST_ACTIVE,'s_o.offer_id'=>NULL),
				'order'=>array(array('r.request_id','desc')),
				'group'=>'r.request_id',
		);
		if($this->input->get('scat') && $this->input->get('scat')>0){
			$arr['where']['r_c.category_subchild_id']=$this->input->get('scat');
		}
		if($this->input->get('title')){
			$arr['where']['r.request_description']='%'.strip_tags($this->input->get('title')).'%';
		}
		$data['buyer_request']=getData($arr);
		$data['offer_sent']=getData(array('select'=>'r.request_id,r.request_title,r.request_description,r.seller_id,r.delivery_time,r.request_budget,m.member_name,s_o.delivery_time as delivery_time_offer,s_o.amount as amount_offer,s_o.description as description_offer,p.proposal_title',
				'table'=>'send_offers s_o',
				'join'=>array(
				array('table'=>'buyer_requests as r','on'=>"r.request_id=s_o.request_id and s_o.sender_id='".$this->member_id."'",'position'=>'left'),
				array('table'=>'proposals as p','on'=>'s_o.proposal_id=p.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'r.seller_id=m.member_id','position'=>'left'),
				
				),
				/*'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'r.request_status'=>REQUEST_ACTIVE,'r.seller_id <>'=>$this->member_id),*/
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'r.request_status'=>REQUEST_ACTIVE,'s_o.sender_id'=>$this->member_id),
				'order'=>array(array('r.request_id','desc')),
				'group'=>'r.request_id',
				));		
				
				
		$data['login_seller_offers']=$this->db->where('sender_id',$this->member_id)->where('date(reg_date)',date('Y-m-d'))->from('send_offers')->count_all_results();
		$templateLayout=array('view'=>'buyer-request','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function viewoffer($request_id){
		$show_admin=$this->input->get('show_details');
		if($show_admin){
			$is_login=0;
		}else{
			$is_login=1;
			if(!$this->loggedUser){
			redirect(get_link('loginURL'));	
			}
		}
		$arr=array(
				'select'=>'r.request_id,r.request_title,r.request_description,r.delivery_time,r.request_budget,r.request_date,r.request_status',
				'table'=>'buyer_requests as r',
				'where'=>array('r.request_id'=>$request_id,'r.seller_id'=>$this->member_id),
				'single_row'=>true,
			);
		if(!$is_login){
			unset($arr['where']['r.seller_id']);
		}
		$request=getData($arr);
		if($request){
			$data['request_details']=getRequestDetails($request->request_id,array('request_category','request_files'));
			$data['request_details']['request']=$request;
			$art=array('select'=>'s_o.offer_id,m.member_name,s_o.delivery_time as delivery_time_offer,s_o.amount as amount_offer,s_o.description as description_offer,p.proposal_title,p.proposal_image,p.proposal_seller_id,m.seller_level,p.proposal_url,s_o.reg_date',
				'table'=>'send_offers s_o',
				'join'=>array(
				array('table'=>'proposals as p','on'=>'s_o.proposal_id=p.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left'),
				
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'s_o.request_id'=>$request->request_id),
				'order'=>array(array('s_o.offer_id','asc')),
				);
			if(!$is_login){
				unset($art['where']['p.proposal_status']);
			}
			$data['request_offer']=getData($art);
			$data['is_login']=$is_login;
			$templateLayout=array('view'=>'view-offer','type'=>'default','buffer'=>FALSE,'theme'=>'');
			load_template($templateLayout,$data);
		}else{
			redirect(get_link('managerequestURL'));
		}		
	}
	public function acceptofferpayment(){
		checkrequestajax();
		$data=array();
		$request_id=post('request_id');
		$offer_id=post('offer_id');
		$arr=array(
			'select'=>'o.amount,o.proposal_id,o.offer_id,p.proposal_title,o.delivery_time,o.description',
			'table'=>'send_offers o',
			'join'=>array(
			array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','position'=>'left')
			),
			'where'=>array('o.offer_id'=>$offer_id,'o.request_id'=>$request_id),
			'single_row'=>true,
		);
		$data['offer_details']=getData($arr);
		$data['member_details']=getMemberDetails($this->member_id);
		
		$templateLayout=array('view'=>'offer-payment','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}	
}
