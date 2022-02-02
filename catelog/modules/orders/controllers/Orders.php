<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MX_Controller {

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
		loadModel('order_model');
		loadModel('notifications/notification_model');
			parent::__construct();
	}
	public function revenue(){
		$data['loggedUser']=$this->loggedUser;
		$data['member_details']=$member_details= getMemberDetails($this->member_id,array('main'=>1,'member_payment_settings'=>1));
		
		$tota_pending=0;
		$used_purchases=0;
		$earning=$this->db->select('SUM(amount) as total')->where('seller_id',$this->member_id)->where('status',0)->group_by('seller_id')->from('revenues')->get()->row_array();
		if($earning){
			$tota_pending=$earning['total'];
		}
		$transaction_type=array('order_payment_wallet','order_payment_paypal','order_payment_stripe','order_payment_payza','order_payment_bitcoin','order_payment_mobile_money','order_payment_refund');
		$this->db->select('SUM(wr.debit)-sum(wr.credit) as Amount')
				->from('wallet_transaction w')
				->join('wallet_transaction_type wt', 'wt.wallet_transaction_type_id = w.wallet_transaction_type_id', 'INNER')
				->join('wallet_transaction_row wr', 'wr.wallet_transaction_id = w.wallet_transaction_id', 'LEFT');
		$this->db->where('wr.wallet_id', $member_details['member']->wallet_id);		
		$this->db->where('w.status', 1);		
		$this->db->where_in('wt.title_tkey', $transaction_type);		
		$result_buy = $this->db->group_by('wr.wallet_id')->order_by('w.transaction_date', 'DESC')->order_by('w.wallet_transaction_id', 'DESC')->get()->row();
		
		if($result_buy){
			$used_purchases=$result_buy->Amount;
		}
		$data['withdrawn']=getFieldData('withdrawn','wallet','','',array('user_id'=>$this->member_id));
		$data['pending_clearance']	=$tota_pending;
		$data['used_purchases']	=$used_purchases;	
		
		$arr=array(
			'select'=>'r.order_id,r.status,r.date,r.amount',
			'table'=>'revenues as r',
			'where'=>array('r.seller_id'=>$this->member_id),
			'order'=>array(array('revenue_id','desc'))
		);
		$data['all_revenue']=getData($arr);

		$wallet_transaction_type_id=get_option_value('WITHDRAW');
		$this->db->select('t.*,sum(r.debit) as amount,sum(r.credit) as processing_fee,r.description_tkey,r.relational_data')
			->from('wallet_transaction t')
			->join('wallet_transaction_row r', 'r.wallet_transaction_id=t.wallet_transaction_id','left');

		$this->db->where('t.wallet_transaction_type_id',$wallet_transaction_type_id);
		$this->db->where('r.description_tkey <>','Transfer_from');
		$this->db->where('r.wallet_id', $member_details['member']->wallet_id);		
		$this->db->group_by('t.wallet_transaction_id');
		$data['all_widthdraw']=$this->db->order_by('t.wallet_transaction_id', 'DESC')->get()->result_array();


		$templateLayout=array('view'=>'revenue','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function purchases(){
		$data['loggedUser']=$this->loggedUser;
		$member_details= getMemberDetails($this->member_id,array('main'=>1));
		$data['all_orders']=$this->order_model->getPurchases(array('wallet_id'=>$member_details['member']->wallet_id));
		$templateLayout=array('view'=>'purchases','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function buyer(){
		$data['loggedUser']=$this->loggedUser;
		$data['active_orders']=$data['delivered_orders']=$data['complete_orders']=$data['cancelled_orders']=array();
		$data['all_orders']=$this->order_model->getOrders(array('buyer_id'=>$this->member_id));
		if($data['all_orders']){
			foreach($data['all_orders']  as $order){
				if($order->order_active==1){
					$data['active_orders'][]=$order;
					if($order->order_status==ORDER_DELIVERED){
						$data['delivered_orders'][]=$order;
					}
				}else{
					if($order->order_status==ORDER_COMPLETED){
						$data['complete_orders'][]=$order;
					}elseif($order->order_status==ORDER_CANCELLED){
						$data['cancelled_orders'][]=$order;
					}
				}
			}
		}
		$templateLayout=array('view'=>'buyer-order','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function seller(){
		$data['loggedUser']=$this->loggedUser;
		$data['tab']='';
		if($this->input->get('tab')){
			$data['tab']=get('tab');
		}
		$data['active_orders']=$data['delivered_orders']=$data['complete_orders']=$data['cancelled_orders']=array();
		$data['all_orders']=$this->order_model->getOrders(array('seller_id'=>$this->member_id));
		if($data['all_orders']){
			foreach($data['all_orders']  as $order){
				if($order->order_active==1){
					$data['active_orders'][]=$order;
					if($order->order_status==ORDER_DELIVERED){
						$data['delivered_orders'][]=$order;
					}
				}else{
					if($order->order_status==ORDER_COMPLETED){
						$data['complete_orders'][]=$order;
					}elseif($order->order_status==ORDER_CANCELLED){
						$data['cancelled_orders'][]=$order;
					}
				}
			}
		}
		$templateLayout=array('view'=>'seller-order','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function order_details($order_id){
		$data['load_js']=load_js(array('mycustom.js','jquery.barrating.min.js'));
		$data['load_css']=load_css(array('fontawesome-stars.css'));
		$data['loggedUser']=$this->loggedUser;
		$arr=array(
			'select'=>'o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction',
			'table'=>'orders as o',
			'join'=>array(
			array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','posiotion'=>'left'),
			array('table'=>'proposal_additional as p_a','on'=>'o.proposal_id=p_a.proposal_id','posiotion'=>'left'),
			),
			'where'=>array('o.order_id'=>$order_id),
			'single_row'=>TRUE
		);
		$data['orderDetails']=getData($arr);
		if($data['orderDetails'] && ($data['orderDetails']->seller_id==$this->member_id || $data['orderDetails']->buyer_id==$this->member_id)){
			$data['orderDetails']->extra=getData(array(
				'select'=>'o.name,o.price',
				'table'=>'orders_extras as o',
				'where'=>array('o.order_id'=>$order_id),
			));
			$data['orderDetails']->buyer=getMemberDetails($data['orderDetails']->buyer_id,array('main'=>1));
			$data['orderDetails']->seller=getMemberDetails($data['orderDetails']->seller_id,array('main'=>1));
			$data['orderDetails']->buyer_user_name=getUserName($data['orderDetails']->buyer_id);
			$data['orderDetails']->seller_user_name=getUserName($data['orderDetails']->seller_id);
			
			if($data['orderDetails']->order_status == ORDER_COMPLETED){
				$data['orderDetails']->revenues=getData(array(
					'select'=>'r.date,r.end_date,r.status,r.amount,r.commission',
					'table'=>'revenues as r',
					'where'=>array('r.order_id'=>$order_id),
					'single_row'=>TRUE
				));
				$data['orderDetails']->seller_review=getData(array(
					'select'=>'r.seller_rating,r.seller_review,r.review_date',
					'table'=>'seller_reviews as r',
					'where'=>array('r.order_id'=>$order_id),
					'single_row'=>TRUE
				));
				$data['orderDetails']->buyer_review=getData(array(
					'select'=>'r.buyer_rating,r.buyer_review,r.review_date',
					'table'=>'buyer_reviews as r',
					'where'=>array('r.order_id'=>$order_id),
					'single_row'=>TRUE
				));
			}elseif($data['orderDetails']->order_status == ORDER_DELIVERED){
				//$currentDate = new DateTime("now");
				$currentDate = date('Y-m-d H:i:s');
				if(!empty($data['orderDetails']->complete_time)){
					//$endDate = new DateTime($data['orderDetails']->order_time);
					$endDate =$data['orderDetails']->complete_time;
					if($currentDate >= $endDate){
						$completedata=array(
						'order_id'=>$order_id,
						'order_details'=>$data['orderDetails'],
						);
						$this->order_model->orderComplete($completedata);
						$notificationData=array(
						'sender_id'=>$data['orderDetails']->buyer_id,
						'receiver_id'=>$data['orderDetails']->seller_id,
						'template'=>'order_completed',
						'url'=>$this->config->item('OrderDetailsURL').$order_id,
						'content'=>json_encode(array('OID'=>$order_id)),
						);
						$this->notification_model->savenotification($notificationData);
						redirect(get_link('OrderDetailsURL').$order_id);
					}
				}
			}elseif($data['orderDetails']->order_status != ORDER_CANCELLED){
				/*$currentDate = new DateTime("now");
				$endDate = new DateTime($data['orderDetails']->order_time);
				if($currentDate >= $endDate){
					$completedata=array(
					'order_id'=>$order_id,
					'order_details'=>$data['orderDetails'],
					);
					$this->order_model->orderCancelled($completedata);
					
					$notificationData=array(
					'sender_id'=>0,
					'receiver_id'=>$data['orderDetails']->seller_id,
					'template'=>'order_cancelled',
					'url'=>'order-details/'.$order_id,
					'content'=>json_encode(array('OID'=>$order_id)),
					);
					$this->notification_model->savenotification($notificationData);
					$notificationData['receiver_id']=$data['orderDetails']->buyer_id;
					$this->notification_model->savenotification($notificationData);
					redirect(get_link('OrderDetailsURL').$order_id);
				}*/
			}
			
		}else{
			redirect(get_link('homeURL'));
		}
		$templateLayout=array('view'=>'order-details','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function load_conversation(){
		checkrequestajax();
		$orderid=post('order_id');
		$data['orderDetails']=$orderdetails=getData(array(
				'select'=>'o.order_id,o.seller_id,o.buyer_id,o.order_status,o.complete_time',
				'table'=>'orders as o',
				'where'=>array('o.order_id'=>$orderid),
				'single_row'=>TRUE
			));
		if($orderdetails){
			$data['loggedUser']=$this->loggedUser;
			$order_id=$orderdetails->order_id;
			if(in_array($this->member_id,array($orderdetails->seller_id,$orderdetails->buyer_id))){
				$data['orderDetails']->buyer=getMemberDetails($data['orderDetails']->buyer_id,array('main'=>1));
				$data['orderDetails']->seller=getMemberDetails($data['orderDetails']->seller_id,array('main'=>1));
				$data['orderconversations']=getData(array(
					'select'=>'o.sender_id,o.message,o.file,o.date,o.reason,o.status,m.member_name',
					'table'=>'orders_conversations as o',
					'join'=>array(
						array('table'=>'member as m','on'=>'o.sender_id=m.member_id','posiotion'=>'left'),
					),
					'where'=>array('o.order_id'=>$order_id),
				));
				$templateLayout=array('view'=>'order-conversations','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
				load_template($templateLayout,$data);
			}
		}
	}
	public function sendmessage(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			fromVRules('orderid', 'orderid', 'required|trim|xss_clean|numeric');
			fromVRules('messagebox', 'message', 'required|trim|xss_clean');
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
				$orderid=post('orderid');
				$orderdetails=getData(array(
						'select'=>'o.order_id,o.seller_id,o.buyer_id,o.order_status',
						'table'=>'orders as o',
						'where'=>array('o.order_id'=>$orderid),
						'single_row'=>TRUE
					));
				if($orderdetails){
					$order_id=$orderdetails->order_id;
					if(in_array($this->member_id,array($orderdetails->seller_id,$orderdetails->buyer_id))){
						
					}else{
						$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = 'orderid';
						$msg['errors'][$i]['message'] = 'Error in request';
		   				$i++;
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'orderid';
					$msg['errors'][$i]['message'] = 'invalid in request';
	   				$i++;
				}
				if($i==0){
					if($this->member_id==$orderdetails->buyer_id){
						if($orderdetails->order_status==ORDER_PENDING){
							updateTable('orders',array('order_status'=>ORDER_PROCESSING),array('order_id'=>$order_id));
							$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
						}
						$receiver_id = $orderdetails->seller_id;
					}else{
						$receiver_id = $orderdetails->buyer_id;
					}
					$conversations=array(
						'order_id'=>$order_id,
						'sender_id'=>$this->member_id,
						'message'=>post('messagebox'),
	/*					'file'=>post('message'),*/
						'date'=>date('Y-m-d H:i:s'),
						'reason'=>'',
						'status'=>'message',
					);
					if($this->input->post('attachment')){
						$attachment=post('attachment');
						$file_data=json_decode($attachment);
						if($file_data){
							if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
								rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."conversation-files/".$file_data->file_name);	
								$conversations['file']=$file_data->file_name;
							}
						}
					}
					$conversations_id=insertTable('orders_conversations',$conversations,TRUE);
					if($conversations_id){
						$notificationData=array(
						'sender_id'=>$this->member_id,
						'receiver_id'=>$receiver_id,
						'template'=>'order_message',
						'url'=>$this->config->item('OrderDetailsURL').$order_id,
						'content'=>json_encode(array('OID'=>$order_id)),
						);
						$this->notification_model->savenotification($notificationData);
					}
					$msg['status'] = 'OK';
				}
			}
			
		}
	unset($_POST);
	echo json_encode($msg);
	}
	public function saveAction($orderid){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			$action=post('action');
			$orderdetails=getData(array(
						'select'=>'o.order_id,o.seller_id,o.buyer_id,o.order_status,o.order_price,o.proposal_id',
						'table'=>'orders as o',
						'where'=>array('o.order_id'=>$orderid),
						'single_row'=>TRUE
					));
			if($orderdetails){
				$order_id=$orderdetails->order_id;
				if(in_array($this->member_id,array($orderdetails->seller_id,$orderdetails->buyer_id))){
					if($action=='submit_delivered'){
						fromVRules('delivered_message', 'message', 'required|trim|xss_clean');
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
								$conversations=array(
									'order_id'=>$order_id,
									'sender_id'=>$this->member_id,
									'message'=>post('delivered_message'),
									'date'=>date('Y-m-d H:i:s'),
									'reason'=>'',
									'status'=>'delivered',
								);
								if($this->input->post('attachment')){
									$attachment=post('attachment');
									$file_data=json_decode($attachment);
									if($file_data){
										if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
											rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."conversation-files/".$file_data->file_name);	
											$conversations['file']=$file_data->file_name;
										}
									}
								}
								$conversations_id=insertTable('orders_conversations',$conversations,TRUE);
								if($conversations_id){
									updateTable('orders_conversations',array('status'=>'message'),array('order_id'=>$order_id,'status'=>'delivered','c_id <>'=>$conversations_id));
									$order_auto_complete =get_option_value('order_auto_complete');
          							$complete_time = date("Y-m-d H:i:s",strtotime(" + $order_auto_complete days"));
									updateTable('orders',array('order_status'=>ORDER_DELIVERED,'complete_time'=>$complete_time),array('order_id'=>$order_id,'order_status <>'=>ORDER_CANCELLED,'order_status <>'=>ORDER_COMPLETED));
									
									$seller_details=getMemberDetails($orderdetails->seller_id,array('main'=>1));
									$buyer_details=getMemberDetails($orderdetails->buyer_id,array('main'=>1));
									$RECEIVER_EMAIL=$buyer_details['member']->member_email;
									$url=get_link('OrderDetailsURL').$order_id;
									$template='order-delivered';
									$data_parse=array(
									'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
									'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
									'MESSAGE'=>$conversations['message'],
									'ORDER_DETAILS_URL'=>$url,
									);
									SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
									
									
									$notificationData=array(
									'sender_id'=>$this->member_id,
									'receiver_id'=>$orderdetails->buyer_id,
									'template'=>'order_delivered',
									'url'=>$this->config->item('OrderDetailsURL').$order_id,
									'content'=>json_encode(array('OID'=>$order_id)),
									);
									$this->notification_model->savenotification($notificationData);
								}
								$msg['status'] = 'OK';
								$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
							}
						}
					}
					elseif($action=='submit_revison'){
						fromVRules('revison_message', 'message', 'required|trim|xss_clean');
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
								$conversations=array(
									'order_id'=>$order_id,
									'sender_id'=>$this->member_id,
									'message'=>post('revison_message'),
									'date'=>date('Y-m-d H:i:s'),
									'reason'=>'',
									'status'=>'revision',
								);
								if($this->input->post('attachment')){
									$attachment=post('attachment');
									$file_data=json_decode($attachment);
									if($file_data){
										if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
											rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."conversation-files/".$file_data->file_name);	
											$conversations['file']=$file_data->file_name;
										}
									}
								}
								$conversations_id=insertTable('orders_conversations',$conversations,TRUE);
								if($conversations_id){
									updateTable('orders_conversations',array('status'=>'message'),array('order_id'=>$order_id,'status'=>'delivered','c_id <>'=>$conversations_id));
									updateTable('orders',array('order_status'=>ORDER_REVISION),array('order_id'=>$order_id,'order_status <>'=>ORDER_CANCELLED,'order_status <>'=>ORDER_COMPLETED));
									
									$seller_details=getMemberDetails($orderdetails->seller_id,array('main'=>1));
									$buyer_details=getMemberDetails($orderdetails->buyer_id,array('main'=>1));
									$RECEIVER_EMAIL=$seller_details['member']->member_email;
									$url=get_link('OrderDetailsURL').$order_id;
									$template='revision-requested';
									$data_parse=array(
									'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
									'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
									'MESSAGE'=>$conversations['message'],
									'ORDER_DETAILS_URL'=>$url,
									);
									SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
									
									$notificationData=array(
									'sender_id'=>$this->member_id,
									'receiver_id'=>$orderdetails->seller_id,
									'template'=>'order_revision',
									'url'=>$this->config->item('OrderDetailsURL').$order_id,
									'content'=>json_encode(array('OID'=>$order_id)),
									);
									$this->notification_model->savenotification($notificationData);
								}
								$msg['status'] = 'OK';
								$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
							}
						}
					}	
					elseif($action=='submit_report'){
						fromVRules('additional_information', 'message', 'required|trim|xss_clean');
						fromVRules('reason', 'reason', 'required|trim|xss_clean');
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
								$reports=array(
									'reporter_id'=>$this->member_id,
									'content_id'=>$order_id,
									'content_type'=>'order',
									'reason'=>post('reason'),
									'additional_information'=>post('additional_information'),
								);
								$reports_id=insertTable('reports',$reports,TRUE);
								if($reports_id){
									$msg['message'] = 'Your Report Has Been Successfully Submited';
								}
								$msg['status'] = 'OK';
								$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
							}
						}
					}	
					elseif($action=='submit_cancellation_request'){
						fromVRules('cancellation_message', 'message', 'required|trim|xss_clean');
						fromVRules('cancellation_reason', 'message', 'trim|xss_clean');
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
								$conversations=array(
									'order_id'=>$order_id,
									'sender_id'=>$this->member_id,
									'message'=>post('cancellation_message'),
									'date'=>date('Y-m-d H:i:s'),
									'reason'=>post('cancellation_reason'),
									'status'=>'cancellation_request',
								);
								$conversations_id=insertTable('orders_conversations',$conversations,TRUE);
								if($conversations_id){
									updateTable('orders',array('order_status'=>ORDER_CANCELLATION),array('order_id'=>$order_id,'order_status <>'=>ORDER_CANCELLED,'order_status <>'=>ORDER_COMPLETED));
									
									if($this->member_id==$orderdetails->seller_id){
										$receiver_id=$orderdetails->buyer_id;
									}else{
										$receiver_id=$orderdetails->seller_id;
									}
									$notificationData=array(
									'sender_id'=>$this->member_id,
									'receiver_id'=>$receiver_id,
									'template'=>'cancellation_request',
									'url'=>$this->config->item('OrderDetailsURL').$order_id,
									'content'=>json_encode(array('OID'=>$order_id)),
									);
									$this->notification_model->savenotification($notificationData);
								}
								$msg['status'] = 'OK';
								$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
							}
						}
					}
					elseif($action=='decline_request'){
						updateTable('orders_conversations',array('status'=>'decline_cancellation_request'),array('order_id'=>$order_id,'status'=>'cancellation_request'));
						updateTable('orders',array('order_status'=>ORDER_PROCESSING),array('order_id'=>$order_id,'order_status <>'=>ORDER_CANCELLED,'order_status <>'=>ORDER_COMPLETED));
						
						if($this->member_id==$orderdetails->seller_id){
							$receiver_id=$orderdetails->buyer_id;
						}else{
							$receiver_id=$orderdetails->seller_id;
						}
						$notificationData=array(
						'sender_id'=>$this->member_id,
						'receiver_id'=>$receiver_id,
						'template'=>'decline_cancellation_request',
						'url'=>$this->config->item('OrderDetailsURL').$order_id,
						'content'=>json_encode(array('OID'=>$order_id)),
						);
						$this->notification_model->savenotification($notificationData);
						
						$msg['status'] = 'OK';
						$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
					}
					elseif($action=='accept_request'){
						if($orderdetails->order_status==ORDER_CANCELLATION){
							
						updateTable('orders_conversations',array('status'=>'accept_cancellation_request'),array('order_id'=>$order_id,'status'=>'cancellation_request'));
						updateTable('proposals_referrals',array('status'=>'2'),array('order_id'=>$order_id));
						$update=updateTable('orders',array('order_status'=>ORDER_CANCELLED,'order_active'=>0),array('order_id'=>$order_id,'order_status'=>ORDER_CANCELLATION));
						if($update && $orderdetails->order_status!=ORDER_CANCELLED){
							$total=$orderdetails->order_price;
							$buyer_details=getMemberDetails($orderdetails->buyer_id,array('main'=>1));
							$buyer_wallet_id=$buyer_details['member']->wallet_id;
							$buyer_wallet_balance=$buyer_details['member']->balance;
							$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
							$sender_wallet_id=$site_details->wallet_id;
							$sender_wallet_balance=$site_details->balance;
							
							$reciver_wallet_id=$buyer_wallet_id;
							$reciver_wallet_balance=$buyer_wallet_balance;
							$issuer_relational_data=$buyer_details['member']->member_name;
							$recipient_relational_data=get_option_value('website_name');
							
							$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_REFUND');
							$current_datetime=date('Y-m-d H:i:s');
							$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
							if($wallet_transaction_id){
								
								insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$sender_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$site_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',
								'TP'=>'Order_Payment_Refund',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$sender_new_balance=displayamount($sender_wallet_balance,2)-displayamount($total,2);
								updateTable('wallet',array('balance'=>$sender_new_balance),array('wallet_id'=>$sender_wallet_id));
								wallet_balance_check($sender_wallet_id,array('transaction_id'=>$wallet_transaction_id));
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$site_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',
								'TP'=>'Order_Payment_Refund',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);	
								
								
								$reciver_new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
								$this->db->set('used_purchases','used_purchases-'.$total,FALSE)->where('wallet_id',$reciver_wallet_id)->update('wallet',array('balance'=>$reciver_new_balance));
								wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
								
								if($this->member_id==$orderdetails->seller_id){
									$receiver_id=$orderdetails->buyer_id;
								}else{
									$receiver_id=$orderdetails->seller_id;
								}
								
								
								$seller_details=getMemberDetails($orderdetails->seller_id,array('main'=>1));
								$RECEIVER_EMAIL=$seller_details['member']->member_email;
								$url=get_link('OrderDetailsURL').$order_id;
								$template='order-cancelled-to-seller';
								$data_parse=array(
								'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
								'ORDER_DETAILS_URL'=>$url,
								);
								SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
								
								$RECEIVER_EMAIL=$buyer_details['member']->member_email;
								$template='order-cancelled-to-buyer';
								$data_parse=array(
								'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
								'ORDER_DETAILS_URL'=>$url,
								);
								SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
								
								
								
								$notificationData=array(
								'sender_id'=>$this->member_id,
								'receiver_id'=>$receiver_id,
								'template'=>'accept_cancellation_request',
								'url'=>$this->config->item('OrderDetailsURL').$order_id,
								'content'=>json_encode(array('OID'=>$order_id)),
								);
								$this->notification_model->savenotification($notificationData);
						
								$msg['status'] = 'OK';
								$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
							}
						}else{
							$msg['status'] = 'OK';
							$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
						}
						}
					}
					elseif($action=='complete'){
						if($orderdetails->buyer_id==$this->member_id){
							$completedata=array(
							'order_id'=>$order_id,
							'order_details'=>$orderdetails,
							);
							$revenue_id=$this->order_model->orderComplete($completedata);
							if($revenue_id){
								$notificationData=array(
								'sender_id'=>$orderdetails->buyer_id,
								'receiver_id'=>$orderdetails->seller_id,
								'template'=>'order_completed',
								'url'=>$this->config->item('OrderDetailsURL').$order_id,
								'content'=>json_encode(array('OID'=>$order_id)),
								);
								$this->notification_model->savenotification($notificationData);
						
								$msg['status'] = 'OK';
								$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
							}
							/*updateTable('orders_conversations',array('status'=>'message'),array('order_id'=>$order_id,'status'=>'delivered'));
							$update=updateTable('orders',array('order_status'=>ORDER_COMPLETED,'order_active'=>0),array('order_id'=>$order_id,'order_status'=>ORDER_DELIVERED));
							if($update){
								updateTable('member',array('recent_delivery_date'=>date('Y-m-d H:i:s')),array('member_id'=>$orderdetails->seller_id));
								$seller_details=getMemberDetails($orderdetails->seller_id,array('main'=>1));
								$total=$orderdetails->order_price;
								$comission_percentage=get_option_value('comission_percentage');
								$days_before_withdraw=get_option_value('days_before_withdraw');
								$commission=($comission_percentage / 100 ) * $total;
								$seller_price=$total-$commission;
								$seller_wallet_id=$seller_details['member']->wallet_id;
								
								$this->db->set('pending_clearance','pending_clearance+'.$seller_price,FALSE)->set('month_earnings','month_earnings+'.$seller_price,FALSE)->where('wallet_id',$seller_wallet_id)->update('wallet');
								
								$revenue_date = date("Y-m-d H:i:s");
								$end_date = date("Y-m-d H:i:s", strtotime(" + $days_before_withdraw days"));

								$revenues=array(
								'seller_id'=>$orderdetails->seller_id,
								'order_id'=>$order_id,
								'amount'=>$seller_price,
								'date'=>$revenue_date,
								'end_date'=>$end_date,
								'status'=>0,
								);
								$revenue_id=insertTable('revenues',$revenues,TRUE);
								if($revenue_id){
									$msg['status'] = 'OK';
									$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
								}
							}*/
						}
					}
					elseif($action=='review_submit'){
						fromVRules('review', 'review', 'trim|xss_clean');
						fromVRules('rating', 'rating', 'required|trim|xss_clean|numeric|less_than_equal_to[5]');
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
								
							if($orderdetails->seller_id==$this->member_id){
								$review=array(
								'order_id'=>$order_id,
								'review_seller_id'=>$this->member_id,
								'seller_rating'=>post('rating'),
								'seller_review'=>post('review'),
								'review_date'=>date('Y-m-d H:i:s'),
								);
								$createupdate=create_update('seller_reviews',$review,array('order_id'=>$order_id));
								
								$notificationData=array(
								'sender_id'=>$this->member_id,
								'receiver_id'=>$orderdetails->buyer_id,
								'template'=>'seller_order_review',
								'url'=>$this->config->item('OrderDetailsURL').$order_id,
								'content'=>json_encode(array('OID'=>$order_id)),
								);
								if($createupdate==2){
									$notificationData['template']='seller_order_review_update';
								}
								$this->notification_model->savenotification($notificationData);
						
							}elseif($orderdetails->buyer_id==$this->member_id){
								$review=array(
								'order_id'=>$order_id,
								'proposal_id'=>$orderdetails->proposal_id,
								'review_buyer_id'=>$this->member_id,
								'buyer_rating'=>post('rating'),
								'buyer_review'=>post('review'),
								'review_seller_id'=>$orderdetails->seller_id,
								'review_date'=>date('Y-m-d H:i:s'),
								);
								$createupdate=create_update('buyer_reviews',$review,array('order_id'=>$order_id));
								updateMemberReview($orderdetails->seller_id);
								
								$notificationData=array(
								'sender_id'=>$this->member_id,
								'receiver_id'=>$orderdetails->seller_id,
								'template'=>'buyer_order_review',
								'url'=>$this->config->item('OrderDetailsURL').$order_id,
								'content'=>json_encode(array('OID'=>$order_id)),
								);
								if($createupdate==2){
									$notificationData['template']='buyer_order_review_update';
								}
								$this->notification_model->savenotification($notificationData);
							}
							
								if($createupdate==1){
									$msg['message'] ='Review submitted successfully!';
								}elseif($createupdate==2){
									$msg['message'] ='Review updated successfully!';
								}
								$msg['status'] = 'OK';
								$msg['redirect'] = get_link('OrderDetailsURL').$order_id;
							}
						}
					}	
				}	
			}
		}
	unset($_POST);
	echo json_encode($msg);
	}
	public function contacts(){
		$data['loggedUser']=$this->loggedUser;
		$data['tab']=get('tab');
		$member_details= getMemberDetails($this->member_id,array('main'=>1));
		$data['all_buyer']=$this->order_model->getContacts(array('seller_id'=>$this->member_id));
		$data['all_seller']=$this->order_model->getContacts(array('buyer_id'=>$this->member_id));
		$templateLayout=array('view'=>'contacts','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function transferAction(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			$action=post('action');
			$member_details= getMemberDetails($this->member_id,array('main'=>1,'member_payment_settings'=>1));
			$current_balance=$member_details['member']->balance;
			$wallet_id=$member_details['member']->wallet_id;
			$site_details=getWallet(get_option_value('WITHDRAW_WALLET'));
			$receiver_wallet_id=$site_details->wallet_id;
			$receiver_wallet_balance=$site_details->balance;
			$fee_wallet_details=getWallet(get_option_value('PROCESSING_FEE_WALLET'));
			$fee_wallet_id=$fee_wallet_details->wallet_id;
			$fee_wallet_balance=$fee_wallet_details->balance;				
			if($action=='paypal'){
				fromVRules('amount', 'amount', 'required|trim|numeric|xss_clean|greater_than[4]|less_than['.$current_balance.']');
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
					$total=post('amount');
					$feeCalculation=generateProcessingFee('withdrawal_paypal',$total);
					$order_fee=$feeCalculation['processing_fee'];
					if($i==0){
						$relational_data=json_encode(array('method'=>'Paypal','to'=>$member_details['member_payment_settings']->paypal_email));
						$wallet_transaction_type_id=get_option_value('WITHDRAW');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$wallet_id,'debit'=>$total,'description_tkey'=>'Paypal_Transfer','relational_data'=>$relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$member_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							$w_payment=$total-$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$receiver_wallet_id,'credit'=>$w_payment,'description_tkey'=>'Transfer_from','relational_data'=>$member_details['member']->member_name);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$member_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Paypel_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$member_details['member']->member_name.' wallet',
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
							$new_balance=displayamount($current_balance,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$wallet_id));
							wallet_balance_check($wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance=displayamount($receiver_wallet_balance,2)+displayamount($w_payment,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$receiver_wallet_id));
							wallet_balance_check($receiver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
							$template='withdrawn-request';
							$data_parse=array(
							'WITHDRAWN_URL'=>ADMIN_URL.'wallet/withdrawn_list',
							);
							SendMail('',get_option_value('admin_email'),$template,$data_parse);
							
						}
						$msg['status'] = 'OK';
						$msg['redirect'] = get_link('revenueURL');
					}
				}
			}
			elseif($action=='payoneer'){
				fromVRules('amount', 'amount', 'required|trim|numeric|xss_clean|greater_than[4]|less_than['.$current_balance.']');
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
					$total=post('amount');
					$feeCalculation=generateProcessingFee('withdrawal_payoneer',$total);
					$order_fee=$feeCalculation['processing_fee'];
					if($i==0){
						$relational_data=json_encode(array('method'=>'Payoneer','to'=>$member_details['member_payment_settings']->payoneer_email));
						$wallet_transaction_type_id=get_option_value('WITHDRAW');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$wallet_id,'debit'=>$total,'description_tkey'=>'Payoneer_Transfer','relational_data'=>$relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$member_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							$w_payment=$total-$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$receiver_wallet_id,'credit'=>$w_payment,'description_tkey'=>'Transfer_from','relational_data'=>$member_details['member']->member_name);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$member_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Payoneer_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$member_details['member']->member_name.' wallet',
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
							$new_balance=displayamount($current_balance,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$wallet_id));
							wallet_balance_check($wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance=displayamount($receiver_wallet_balance,2)+displayamount($w_payment,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$receiver_wallet_id));
							wallet_balance_check($receiver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
							$template='withdrawn-request';
							$data_parse=array(
							'WITHDRAWN_URL'=>ADMIN_URL.'wallet/withdrawn_list',
							);
							SendMail('',get_option_value('admin_email'),$template,$data_parse);
							
						}
						$msg['status'] = 'OK';
						$msg['redirect'] = get_link('revenueURL');
					}
				}
			}
			elseif($action=='bank'){
				fromVRules('amount', 'amount', 'required|trim|numeric|xss_clean|greater_than[4]|less_than['.$current_balance.']');
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
					$total=post('amount');
					$feeCalculation=generateProcessingFee('withdrawal_bank',$total);
					$order_fee=$feeCalculation['processing_fee'];
					if($i==0){
						$relational_data=json_encode(array('method'=>'Bank','to'=>$member_details['member_payment_settings']->bank_account_number,/*'ac_name'=>$member_details['member_payment_settings']->bank_account_name,*/'bcode'=>$member_details['member_payment_settings']->bank_code,'bname'=>$member_details['member_payment_settings']->bank_name));
						$wallet_transaction_type_id=get_option_value('WITHDRAW');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$wallet_id,'debit'=>$total,'description_tkey'=>'Bank_Transfer','relational_data'=>$relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$member_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							$w_payment=$total-$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$receiver_wallet_id,'credit'=>$w_payment,'description_tkey'=>'Transfer_from','relational_data'=>$member_details['member']->member_name);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$member_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Bank_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$member_details['member']->member_name.' wallet',
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
							$new_balance=displayamount($current_balance,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$wallet_id));
							wallet_balance_check($wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance=displayamount($receiver_wallet_balance,2)+displayamount($w_payment,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$receiver_wallet_id));
							wallet_balance_check($receiver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$template='withdrawn-request';
							$data_parse=array(
							'WITHDRAWN_URL'=>ADMIN_URL.'wallet/withdrawn_list',
							);
							SendMail('',get_option_value('admin_email'),$template,$data_parse);
							
						}
						$msg['status'] = 'OK';
						$msg['redirect'] = get_link('revenueURL');
					}
				}
			}
			if($msg['status']=='OK'){
				$message='A fiverrer Request for Withdrawal';
				$this->db->insert('admin_notifications',array('message'=>$message,'created_date'=>date('Y-m-d H:i:s')));
			}
		}
		unset($_POST);
		echo json_encode($msg);
	}
	public function sellinghistory($seller_id){
		$data['member_details']=getMemberDetails($seller_id,array('main'=>1));
		$data['all_orders']=$this->order_model->getOrders(array('buyer_id'=>$this->member_id,'seller_id'=>$seller_id));
		$templateLayout=array('view'=>'selling-history','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function buyinghistory($buyer_id){
		$data['member_details']=getMemberDetails($buyer_id,array('main'=>1));
		$data['all_orders']=$this->order_model->getOrders(array('seller_id'=>$this->member_id,'buyer_id'=>$buyer_id));
		$templateLayout=array('view'=>'buying-history','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
}
?>