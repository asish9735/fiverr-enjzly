<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proposals extends MX_Controller {

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
			$this->username=$this->loggedUser['UNAME'];	
		}elseif($this->router->fetch_method()=='start_selling' || $this->router->fetch_method()=='view'){
			
		}else{
			redirect(get_link('loginURL'));
		}
			parent::__construct();
	}
	public function start_selling()
	{
		$data=array();
		$data['is_login']=0;
		if($this->loggedUser){
			$data['is_login']=1;
		}
		$data['seo_tags']=array(
		'meta_title'=>'Start selling on '.get_option_value('website_name'),
		'meta_key'=>'',
		'meta_description'=>'Start selling on '.get_option_value('website_name'),
		'seo_images'=>array(),
		);
		
		$templateLayout=array('view'=>'start-selling','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function post_proposal()
	{
		$data=array();
		$data['load_js']=load_js(array('croppie.js','summernote.js','tagsinput.js','upload-drag-file.js'));
		$data['load_css']=load_css(array('tagsinput.css','croppie.css'));
		$data['all_category']=getAllCategory();
		$data['all_delivery_times']=getAllDeliveryTimes();
		$templateLayout=array('view'=>'post-proposal','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function edit_proposal($proposal_id='',$token='')
	{
		$data=array();
		$verify_token=md5('FVRR'.'-'.date("Y-m-d").'-'.$proposal_id);
		$data['tab']=get('tab');
		$data['token']=$token;
		if($verify_token==$token){
			$data['load_js']=load_js(array('croppie.js','summernote.js','tagsinput.js','upload-drag-file.js'));
			$data['load_css']=load_css(array('tagsinput.css','croppie.css'));
			$data['proposal_details']=getProposalDetails($proposal_id);
			$data['all_category']=getAllCategory();
			$data['all_sub_category']=getAllSubCategory($data['proposal_details']['proposal_category']->category_id);
			$data['all_delivery_times']=getAllDeliveryTimes();
			$data['edit_proposal_tab_details']=load_view('proposals/edit-proposal-tab-details',$data,TRUE);
			$data['edit_proposal_tab_price']=load_view('proposals/edit-proposal-tab-price',$data,TRUE);
			$data['edit_proposal_tab_extra']=load_view('proposals/edit-proposal-tab-extra',$data,TRUE);
			$templateLayout=array('view'=>'edit-proposal','type'=>'default','buffer'=>FALSE,'theme'=>'');
			load_template($templateLayout,$data);
		}else{
			redirect(get_link('manageproposalURL'));
		}
	}
	public function pay_featured_listing(){
		checkrequestajax();
		$data=array();
		$proposal_id=post('proposal_id');
		$arr=array(
			'select'=>'p.proposal_title,p.proposal_id',
			'table'=>'proposals p',
			'where'=>array('p.proposal_id'=>$proposal_id,'p.proposal_seller_id'=>$this->member_id),
			'single_row'=>true,
		);
		$data['proposal_details']=getData($arr);
		$data['member_details']=getMemberDetails($this->member_id);
		
		$templateLayout=array('view'=>'pay-featured','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function makefeature($id){
		checkrequestajax();
		$i=0;
		$msg=array();
		$method=post('method');
		$arr=array(
					'select'=>'p.proposal_id',
					'table'=>'proposals p',
					'where'=>array('p.proposal_seller_id'=>$this->member_id,'p.proposal_id'=>$id),
					'single_row'=>true,
				);
		$check_proposal=getData($arr);
		if($check_proposal){
			$featured_fee=get_option_value('featured_fee');
			$processing_fee=get_option_value('processing_fee');
			$featured_duration=get_option_value('featured_duration');
			$proposal_id=$check_proposal->proposal_id;
			if($method=='wallet'){
				$processing_fee=0;
				$total=$featured_fee+$processing_fee;
				$seller_details=getMemberDetails($this->member_id,array('main'=>1));
				$seller_wallet_id=$seller_details['member']->wallet_id;
				$seller_wallet_balance=$seller_details['member']->balance;
				$site_details=getWallet(get_option_value('SITE_PROFIT_WALLET'));
				$reciver_wallet_id=$site_details->wallet_id;
				$reciver_wallet_balance=$site_details->balance;
				//$issuer_relational_data=get_option_value('website_name');
				$recipient_relational_data=$seller_details['member']->member_name;
				if($seller_details && $seller_details['member']->balance>$total){
					$wallet_transaction_type_id=get_option_value('FEATURED_PAYMENT_WALLET');
					$current_datetime=date('Y-m-d H:i:s');
					$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
					if($wallet_transaction_id){
						
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'debit'=>$total,'description_tkey'=>'PID','relational_data'=>$proposal_id);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$seller_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Featured_Payment',
								));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$seller_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Featured_Payment',
								));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$seller_new_balance=displayamount($seller_wallet_balance,2)-displayamount($total,2);
						updateTable('wallet',array('balance'=>$seller_new_balance),array('wallet_id'=>$seller_wallet_id));
						wallet_balance_check($seller_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
						wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$featured_end_date=date('Y-m-d H:i:s',strtotime('+'.$featured_duration.' days'));
						updateTable('proposal_settings',array('proposal_featured'=>1,'featured_end_date'=>$featured_end_date),array('proposal_id'=>$proposal_id));
						
						
						$RECEIVER_EMAIL=$seller_details['member']->member_email;
						$url=get_link('manageproposalURL');
						$template='featured';
						$data_parse=array(
						'SELLER_NAME'=>$seller_details['member']->member_name,
						'PROPOSAL_URL'=>$url,
						);
						SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
						loadModel('notifications/notification_model');
						$notificationData=array(
						'sender_id'=>0,
						'receiver_id'=>$seller_details['member']->member_id,
						'template'=>'featured',
						'url'=>$this->config->item('manageproposalURL'),
						'content'=>json_encode(array('PID'=>$proposal_id)),
						);
						$this->notification_model->savenotification($notificationData);
						
							
						$msg['status'] = 'OK';
						$msg['redirect'] =get_link('manageproposalURL').'?ref=paymentsuccess';
					}else{
						$msg['status'] = 'FAIL';
						$msg['message'] = 'transaction error';
					}
				}else{
					$msg['status'] = 'FAIL';
					$msg['message'] = 'Insufficient fund';
				}					
					
					
				
			}
			elseif($method=='paypal'){
				$msg['status'] = 'OK';
				$msg['redirect'] =get_link('PaypalCheckOut').'featured/'.$proposal_id;
			}
			elseif($method=='telr'){
				$featured_fee=get_option_value('featured_fee');
				$feeCalculation=generateProcessingFee('telr',$featured_fee);
				$processing_fee=$feeCalculation['processing_fee'];
				$amount=$featured_fee+$processing_fee;
				$cart_desc='Feature payment';
				$cart_id=$proposal_id.'-'.time();
				$post_data = Array(
					'ivp_method'		=> 'create',
					'ivp_authkey'		=> get_option_value('telr_authentication_code'),
					'ivp_store'		=> get_option_value('telr_store_id'),
					'ivp_lang'		=> 'en',
					'ivp_cart'		=> $cart_id,
					'ivp_amount'		=> $amount,
					'ivp_currency'		=> trim(CurrencyCode()),
					'ivp_test'		=> get_option_value('telr_is_sandbox'),
					'ivp_desc'		=> trim($cart_desc),
					'return_auth'		=> 	get_link('TelrNotify').'featured/'.$proposal_id,
					'return_can'		=>  get_link('homeURL'),
					'return_decl'		=>  get_link('homeURL'),
					/*'ivp_update_url'	=>  get_link('TelrNotify').'featured/'.$proposal_id,*/
				);
			$curl_telr=curl_telr($post_data);
			if($curl_telr){
				if(isset($curl_telr['order'])) {
					$transansaction_data=array('payment_type'=>'TELR','content_key'=>$cart_id);
					$transansaction_data['request_value']=json_encode($post_data);
					insertTable('online_transaction_data',$transansaction_data);
		
					$jobj = $curl_telr['order'];
					$ref=$jobj['ref'];
					$this->session->set_userdata('Tref',$ref);
					$redirurl=$jobj['url'];
					$msg['status'] = 'OK';
					$msg['redirect'] =$redirurl;
				}else{
					$jobj = $returnData['error'];
					$msg['status'] = 'FAIL';
					$msg['error'] = $jobj['message'].' :: '.$jobj['note'];
				}
			}
			//print_r($post_data);
			//dd($curl_telr);	
				
			}
		}else{
			$msg['status'] = 'FAIL';
		}
		unset($_POST);
		echo json_encode($msg);	
	}
	public function offercheckout($id){
		loadModel('cart/cart_model');
		checkrequestajax();
		$i=0;
		$msg=array();
		$method=post('method');
		$arr=array(
			'select'=>'o.amount,o.proposal_id,o.offer_id,p.proposal_title,o.delivery_time,o.description,p.proposal_id,o.sender_id,o.receiver_id,pa.buyer_instruction,p.proposal_seller_id',
			'table'=>'conversations_messages_offers o',
			'join'=>array(
			array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','position'=>'left'),
			array('table'=>'proposal_additional as pa','on'=>'p.proposal_id=pa.proposal_id','position'=>'left'),
			),
			'where'=>array('o.offer_id'=>$id,'o.receiver_id'=>$this->member_id),
			'single_row'=>true,
		);
		$check_proposal=getData($arr);
		if($check_proposal){
			$order_status=ORDER_PROCESSING;
			$order_number=time();
			$buyer_details=getMemberDetails($this->member_id,array('main'=>1));
			$seller_details=getMemberDetails($check_proposal->proposal_seller_id,array('main'=>1));
			
			$offer_fee=$check_proposal->amount;
			$processing_fee=get_option_value('processing_fee');
			$offer_id=$check_proposal->offer_id;
			if($method=='wallet'){
				if(!empty($check_proposal->proposal_additional->buyer_instruction)){
					$order_status=ORDER_PENDING;
				}
				$processing_fee=0;
				$total=$offer_fee+$processing_fee;
				
				if($buyer_details && $buyer_details['member']->balance>$total){
					$order_time = date("M d, Y H:i:s", strtotime(" + ".$check_proposal->delivery_time." days"));
					$OrderData=array(
					'order_number'=>$order_number,
					'order_duration'=>$check_proposal->delivery_time,
					'order_date'=>date('Y-m-d H:i:s'),
					'order_time'=>$order_time,
					/*'order_description'=>$order_description,*/
					'seller_id'=>$check_proposal->sender_id,
					'buyer_id'=>$this->member_id,
					'proposal_id'=>$check_proposal->proposal_id,
					'order_price'=>$offer_fee,
					'order_qty'=>1,
					'order_fee'=>$processing_fee,
					'order_active'=>'1',
					'order_status'=>0,
					'payment_method'=>$method,
					);
					$order_id=$this->cart_model->processOrder($OrderData);
					if($order_id){
						$buyer_wallet_id=$buyer_details['member']->wallet_id;
						$buyer_wallet_balance=$buyer_details['member']->balance;
						$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						
						$recipient_relational_data=$buyer_details['member']->member_name;
						$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_WALLET');
						$current_datetime=date('Y-m-d H:i:s');
						
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$buyer_new_balance=displayamount($buyer_wallet_balance,2)-displayamount($total,2);
							$this->db->set('used_purchases','used_purchases+'.$total,FALSE)->where('wallet_id',$buyer_wallet_id)->update('wallet',array('balance'=>$buyer_new_balance));
							wallet_balance_check($buyer_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
							wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
							
							updateTable('orders',array('order_status'=>$order_status,'transaction_id'=>$wallet_transaction_id),array('order_id'=>$order_id));
							
							updateTable('conversations_messages_offers',array('order_id'=>$order_id,'status'=>1),array('offer_id'=>$check_proposal->offer_id));
							
							
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('OrderDetailsURL').$order_id;
							$template='new-order';
							$data_parse=array(
							'BUYER_NAME'=>$buyer_details['member']->member_name,
							'SELLER_NAME'=>$seller_details['member']->member_name,
							'PROPOSAL_TITLE'=>$check_proposal->proposal_title,
							'QTY'=>1,
							'DELIVERY_TIME'=>$check_proposal->delivery_time,
							'ORDER_PRICE'=>$offer_fee,
							'ORDER_DETAILS_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							
							loadModel('notifications/notification_model');
							$notificationData=array(
							'sender_id'=>$this->member_id,
							'receiver_id'=>$seller_details['member']->member_id,
							'template'=>'order',
							'url'=>$this->config->item('OrderDetailsURL').$order_id,
							'content'=>json_encode(array('OID'=>$order_id)),
							);
							$this->notification_model->savenotification($notificationData);
						
							$msg['status'] = 'OK';
							$msg['method'] = $method;
							$msg['redirect'] = get_link('OrderDetailsURL').$order_id.'?ref=paymentsuccess';
							
						}else{
							$msg['status'] = 'FAIL';
							$msg['message'] = 'transaction error';
						}
					}
				}else{
					$msg['status'] = 'FAIL';
					$msg['message'] = 'Insufficient fund';
				}
			}
			elseif($method=='paypal'){
				$feeCalculation=generateProcessingFee('paypal',$offer_fee);
				$processing_fee=$feeCalculation['processing_fee'];
				$total=$offer_fee+$processing_fee;
				$order_time = date("M d, Y H:i:s", strtotime(" + ".$check_proposal->delivery_time." days"));
				$OrderData=array(
				'order_number'=>$order_number,
				'order_duration'=>$check_proposal->delivery_time,
				'order_date'=>date('Y-m-d H:i:s'),
				'order_time'=>$order_time,
				/*'order_description'=>$order_description,*/
				'seller_id'=>$check_proposal->sender_id,
				'buyer_id'=>$this->member_id,
				'proposal_id'=>$check_proposal->proposal_id,
				'order_price'=>$offer_fee,
				'order_qty'=>1,
				'order_fee'=>$processing_fee,
				'order_active'=>'0',
				'order_status'=>0,
				'payment_method'=>$method,
				);
				$order_id=$this->cart_model->processOrder($OrderData);
				if($order_id){
					updateTable('conversations_messages_offers',array('order_id'=>$order_id),array('offer_id'=>$check_proposal->offer_id));
					$msg['status'] = 'OK';
					$msg['redirect'] = get_link('PaypalCheckOut').'checkout/'.$order_id;
				}
			}
			elseif($method=='telr'){
				$feeCalculation=generateProcessingFee('telr',$offer_fee);
				$processing_fee=$feeCalculation['processing_fee'];
				$total=$offer_fee+$processing_fee;
				$order_time = date("M d, Y H:i:s", strtotime(" + ".$check_proposal->delivery_time." days"));
				$OrderData=array(
				'order_number'=>$order_number,
				'order_duration'=>$check_proposal->delivery_time,
				'order_date'=>date('Y-m-d H:i:s'),
				'order_time'=>$order_time,
				/*'order_description'=>$order_description,*/
				'seller_id'=>$check_proposal->sender_id,
				'buyer_id'=>$this->member_id,
				'proposal_id'=>$check_proposal->proposal_id,
				'order_price'=>$offer_fee,
				'order_qty'=>1,
				'order_fee'=>$processing_fee,
				'order_active'=>'0',
				'order_status'=>0,
				'payment_method'=>$method,
				);
				$order_id=$this->cart_model->processOrder($OrderData);
				if($order_id){
					updateTable('conversations_messages_offers',array('order_id'=>$order_id),array('offer_id'=>$check_proposal->offer_id));
					$msg['status'] = 'OK';
					$post_data = Array(
						'ivp_method'		=> 'create',
						'ivp_authkey'		=> get_option_value('telr_authentication_code'),
						'ivp_store'		=> get_option_value('telr_store_id'),
						'ivp_lang'		=> 'en',
						'ivp_cart'		=> $order_id,
						'ivp_amount'		=> $total,
						'ivp_currency'		=> trim(CurrencyCode()),
						'ivp_test'		=> get_option_value('telr_is_sandbox'),
						'ivp_desc'		=> trim('New Order Process'),
						'return_auth'		=> 	get_link('TelrNotify').'checkout/'.$order_id,
						'return_can'		=>  get_link('homeURL'),
						'return_decl'		=>  get_link('homeURL'),
						/*'ivp_update_url'	=>  get_link('TelrNotify').'featured/'.$proposal_id,*/
					);
					$curl_telr=curl_telr($post_data);
					if($curl_telr){
						if(isset($curl_telr['order'])) {
							$transansaction_data=array('payment_type'=>'TELR','content_key'=>$order_id);
							$transansaction_data['request_value']=json_encode($post_data);
							insertTable('online_transaction_data',$transansaction_data);
				
							$jobj = $curl_telr['order'];
							$ref=$jobj['ref'];
							$this->session->set_userdata('Tref',$ref);
							$redirurl=$jobj['url'];
							$msg['status'] = 'OK';
							$msg['redirect'] =$redirurl;
						}else{
							$jobj = $returnData['error'];
							$msg['status'] = 'FAIL';
							$msg['error'] = $jobj['message'].' :: '.$jobj['note'];
						}
					}
					//$msg['redirect'] = get_link('PaypalCheckOut').'checkout/'.$order_id;
				}
			}
			
		}else{
			$msg['status'] = 'FAIL';
		}
		unset($_POST);
		echo json_encode($msg);	
	}
	public function requestoffercheckout($id){
		loadModel('cart/cart_model');
		checkrequestajax();
		$i=0;
		$msg=array();
		$method=post('method');
		$arr=array(
			'select'=>'o.amount,o.proposal_id,o.offer_id,p.proposal_title,o.delivery_time,o.description,p.proposal_id,o.sender_id,o.receiver_id,pa.buyer_instruction,p.proposal_seller_id',
			'table'=>'send_offers o',
			'join'=>array(
			array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','position'=>'left'),
			array('table'=>'proposal_additional as pa','on'=>'p.proposal_id=pa.proposal_id','position'=>'left'),
			),
			'where'=>array('o.offer_id'=>$id,'o.receiver_id'=>$this->member_id),
			'single_row'=>true,
		);
		$check_proposal=getData($arr);
		if($check_proposal){
			$order_status=ORDER_PROCESSING;
			$order_number=time();
			$buyer_details=getMemberDetails($this->member_id,array('main'=>1));
			$seller_details=getMemberDetails($check_proposal->proposal_seller_id,array('main'=>1));
			
			$offer_fee=$check_proposal->amount;
			$processing_fee=get_option_value('processing_fee');
			$offer_id=$check_proposal->offer_id;
			if($method=='wallet'){
				if(!empty($check_proposal->proposal_additional->buyer_instruction)){
					$order_status=ORDER_PENDING;
				}
				$processing_fee=0;
				$total=$offer_fee+$processing_fee;
				
				if($buyer_details && $buyer_details['member']->balance>$total){
					$order_time = date("M d, Y H:i:s", strtotime(" + ".$check_proposal->delivery_time." days"));
					$OrderData=array(
					'order_number'=>$order_number,
					'order_duration'=>$check_proposal->delivery_time,
					'order_date'=>date('Y-m-d H:i:s'),
					'order_time'=>$order_time,
					/*'order_description'=>$order_description,*/
					'seller_id'=>$check_proposal->sender_id,
					'buyer_id'=>$this->member_id,
					'proposal_id'=>$check_proposal->proposal_id,
					'order_price'=>$offer_fee,
					'order_qty'=>1,
					'order_fee'=>$processing_fee,
					'order_active'=>'1',
					'order_status'=>0,
					'payment_method'=>$method,
					);
					$order_id=$this->cart_model->processOrder($OrderData);
					if($order_id){
						$buyer_wallet_id=$buyer_details['member']->wallet_id;
						$buyer_wallet_balance=$buyer_details['member']->balance;
						$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						
						$recipient_relational_data=$buyer_details['member']->member_name;
						$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_WALLET');
						$current_datetime=date('Y-m-d H:i:s');
						
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$buyer_new_balance=displayamount($buyer_wallet_balance,2)-displayamount($total,2);
							$this->db->set('used_purchases','used_purchases+'.$total,FALSE)->where('wallet_id',$buyer_wallet_id)->update('wallet',array('balance'=>$buyer_new_balance));
							wallet_balance_check($buyer_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
							wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
							
							updateTable('orders',array('order_status'=>$order_status,'transaction_id'=>$wallet_transaction_id),array('order_id'=>$order_id));
							
							updateTable('send_offers',array('order_id'=>$order_id,'status'=>1),array('offer_id'=>$check_proposal->offer_id));
							
							
							
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('OrderDetailsURL').$order_id;
							$template='new-order';
							$data_parse=array(
							'BUYER_NAME'=>$buyer_details['member']->member_name,
							'SELLER_NAME'=>$seller_details['member']->member_name,
							'PROPOSAL_TITLE'=>$check_proposal->proposal_title,
							'QTY'=>1,
							'DELIVERY_TIME'=>$check_proposal->delivery_time,
							'ORDER_PRICE'=>$offer_fee,
							'ORDER_DETAILS_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							
							loadModel('notifications/notification_model');
							$notificationData=array(
							'sender_id'=>$this->member_id,
							'receiver_id'=>$seller_details['member']->member_id,
							'template'=>'order',
							'url'=>$this->config->item('OrderDetailsURL').$order_id,
							'content'=>json_encode(array('OID'=>$order_id)),
							);
							$this->notification_model->savenotification($notificationData);
							
							
							$msg['status'] = 'OK';
							$msg['method'] = $method;
							$msg['redirect'] = get_link('OrderDetailsURL').$order_id.'?ref=paymentsuccess';
							
						}else{
							$msg['status'] = 'FAIL';
							$msg['message'] = 'transaction error';
						}
					}
				}else{
					$msg['status'] = 'FAIL';
					$msg['message'] = 'Insufficient fund';
				}
			}
			elseif($method=='paypal'){
				$feeCalculation=generateProcessingFee('paypal',$offer_fee);
				$processing_fee=$feeCalculation['processing_fee'];
				$total=$offer_fee+$processing_fee;
				$order_time = date("M d, Y H:i:s", strtotime(" + ".$check_proposal->delivery_time." days"));
				$OrderData=array(
				'order_number'=>$order_number,
				'order_duration'=>$check_proposal->delivery_time,
				'order_date'=>date('Y-m-d H:i:s'),
				'order_time'=>$order_time,
				/*'order_description'=>$order_description,*/
				'seller_id'=>$check_proposal->sender_id,
				'buyer_id'=>$this->member_id,
				'proposal_id'=>$check_proposal->proposal_id,
				'order_price'=>$offer_fee,
				'order_qty'=>1,
				'order_fee'=>$processing_fee,
				'order_active'=>'0',
				'order_status'=>0,
				'payment_method'=>$method,
				);
				$order_id=$this->cart_model->processOrder($OrderData);
				if($order_id){
					updateTable('send_offers',array('order_id'=>$order_id),array('offer_id'=>$check_proposal->offer_id));
					$msg['status'] = 'OK';
					$msg['redirect'] = get_link('PaypalCheckOut').'checkout/'.$order_id;
				}
			}
			elseif($method=='telr'){
				$feeCalculation=generateProcessingFee('telr',$offer_fee);
				$processing_fee=$feeCalculation['processing_fee'];
				$total=$offer_fee+$processing_fee;
				$order_time = date("M d, Y H:i:s", strtotime(" + ".$check_proposal->delivery_time." days"));
				$OrderData=array(
				'order_number'=>$order_number,
				'order_duration'=>$check_proposal->delivery_time,
				'order_date'=>date('Y-m-d H:i:s'),
				'order_time'=>$order_time,
				/*'order_description'=>$order_description,*/
				'seller_id'=>$check_proposal->sender_id,
				'buyer_id'=>$this->member_id,
				'proposal_id'=>$check_proposal->proposal_id,
				'order_price'=>$offer_fee,
				'order_qty'=>1,
				'order_fee'=>$processing_fee,
				'order_active'=>'0',
				'order_status'=>0,
				'payment_method'=>$method,
				);
				$order_id=$this->cart_model->processOrder($OrderData);
				if($order_id){
					updateTable('send_offers',array('order_id'=>$order_id),array('offer_id'=>$check_proposal->offer_id));
					$msg['status'] = 'OK';
					$post_data = Array(
						'ivp_method'		=> 'create',
						'ivp_authkey'		=> get_option_value('telr_authentication_code'),
						'ivp_store'		=> get_option_value('telr_store_id'),
						'ivp_lang'		=> 'en',
						'ivp_cart'		=> $order_id,
						'ivp_amount'		=> $total,
						'ivp_currency'		=> trim(CurrencyCode()),
						'ivp_test'		=> get_option_value('telr_is_sandbox'),
						'ivp_desc'		=> trim('New Order Process'),
						'return_auth'		=> 	get_link('TelrNotify').'checkout/'.$order_id,
						'return_can'		=>  get_link('homeURL'),
						'return_decl'		=>  get_link('homeURL'),
						/*'ivp_update_url'	=>  get_link('TelrNotify').'featured/'.$proposal_id,*/
					);
					$curl_telr=curl_telr($post_data);
					if($curl_telr){
						if(isset($curl_telr['order'])) {
							$transansaction_data=array('payment_type'=>'TELR','content_key'=>$order_id);
							$transansaction_data['request_value']=json_encode($post_data);
							insertTable('online_transaction_data',$transansaction_data);
				
							$jobj = $curl_telr['order'];
							$ref=$jobj['ref'];
							$this->session->set_userdata('Tref',$ref);
							$redirurl=$jobj['url'];
							$msg['status'] = 'OK';
							$msg['redirect'] =$redirurl;
						}else{
							$jobj = $returnData['error'];
							$msg['status'] = 'FAIL';
							$msg['error'] = $jobj['message'].' :: '.$jobj['note'];
						}
					}
					//$msg['redirect'] = get_link('PaypalCheckOut').'checkout/'.$order_id;
				}
			}
		}else{
			$msg['status'] = 'FAIL';
		}
		unset($_POST);
		echo json_encode($msg);	
	}
	public function manage_proposal()
	{
		loadModel('proposal_model');
		$data=array();
		$data['active_proposals']=$data['paused_proposals']=$data['pending_proposals']=$data['modification_proposals']=$data['declined_proposals']=array();
		$data['member_details']=getMemberDetails($this->member_id);
		$data['member_details']['member']->username=$this->username;
		$data['all_proposal']=$this->proposal_model->getProposal(array('member_id'=>$this->member_id));
		if($data['all_proposal']){
			foreach($data['all_proposal']  as $proposal){
				$proposal->order=0;
				if($proposal->proposal_status==PROPOSAL_PENDING){
					$data['pending_proposals'][]=$proposal;
				}elseif($proposal->proposal_status==PROPOSAL_ACTIVE){
					$proposal->order=$this->db->where('proposal_id',$proposal->proposal_id)->where('order_status <>',0)->from('orders')->count_all_results();
					$data['active_proposals'][]=$proposal;
				}elseif($proposal->proposal_status==PROPOSAL_PAUSED){
					$proposal->order=$this->db->where('proposal_id',$proposal->proposal_id)->where('order_status <>',0)->from('orders')->count_all_results();
					$data['paused_proposals'][]=$proposal;
				}elseif($proposal->proposal_status==PROPOSAL_MODIFICATION){
					$data['modification_proposals'][]=$proposal;
				}elseif($proposal->proposal_status==PROPOSAL_DECLINED){
					$data['declined_proposals'][]=$proposal;
				}
			}
		}
		$templateLayout=array('view'=>'manage-proposal','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function vacation(){
		checkrequestajax();
		$i=0;
		$msg=array();
		$mode=post('mode');
		if($mode=='on'){
			fromVRules('seller_vacation_reason', 'reason', 'trim|xss_clean|required');
			fromVRules('seller_vacation_message', 'message', 'trim|xss_clean');
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
					updateTable('member',array('is_vacation'=>1),array('member_id'=>$this->member_id));
					updateTable('member_basic',array('seller_vacation_reason'=>post('seller_vacation_reason'),'seller_vacation_message'=>post('seller_vacation_message')),array('member_id'=>$this->member_id));
					$msg['status'] = 'OK';
				}
			}
		}elseif($mode=='off'){
			updateTable('member',array('is_vacation'=>0),array('member_id'=>$this->member_id));
			updateTable('member_basic',array('seller_vacation_reason'=>NULL,'seller_vacation_message'=>NULL),array('member_id'=>$this->member_id));
			$msg['status'] = 'OK';
		}
		unset($_POST);
		echo json_encode($msg);	
	}
	public function postproposalCheckAjax(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		fromVRules('proposal_title', 'title', 'required|trim|xss_clean|min_length[15]|max_length[80]');
		fromVRules('proposal_description', 'description', 'required|trim|xss_clean|min_length[150]|max_length[1200]');
		fromVRules('category_id', 'category', 'required|trim|xss_clean');
		if(post('category_id')>0){
			fromVRules('sub_category_id', 'sub category', 'required|trim|xss_clean');
		}
		fromVRules('delivery_id', 'delivery time', 'required|trim|xss_clean');
		if($this->input->post('proposal_enable_referrals') && $this->input->post('proposal_enable_referrals')=='1'){
			fromVRules('proposal_referral_money', 'commision', 'required|trim|xss_clean');
		}
		fromVRules('proposal_tags', 'tag', 'required|trim|xss_clean');
		fromVRules('mainimage', 'proposal image', 'required|trim|xss_clean');
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
			$proposal_url=getSlug(trim(strip_tags(post('proposal_title'))));
			$arr=array(
					'select'=>'p.proposal_id',
					'table'=>'proposals p',
					'where'=>array('p.proposal_status <>'=>PROPOSAL_DELETED,'p.proposal_seller_id'=>$this->member_id,'proposal_url'=>$proposal_url),
					'single_row'=>true,
				);
			$check_proposal=getData($arr);
			if($check_proposal){
				$msg['status'] = 'FAIL';
    			$msg['errors'][$i]['id'] = 'proposal_title';
				$msg['errors'][$i]['message'] = 'Opps! Your Already Made A Proposal With Same Title Try Another.';
   				$i++;
			}

			if($i==0){
				
				$proposals=array(
					'proposal_seller_id'=>$this->member_id,
					'proposal_title'=>strip_tags(post('proposal_title')),
					'proposal_url'=>$proposal_url,
					'delivery_time'=>post('delivery_id'),
					'proposal_date'=>date('Y-m-d H:i:s'),
					'proposal_status'=>PROPOSAL_PENDING,
					);
				$file_data=json_decode(post('mainimage'));
				if($file_data){
					if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
						rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."proposal-files/".$file_data->file_name);
						$proposals['proposal_image']=$file_data->file_name;
					}
				}
				
				$proposal_id=insertTable('proposals',$proposals,TRUE);
				if($proposal_id){
					$proposal_category=array(
					'proposal_id'=>$proposal_id,
					'category_id'=>post('category_id'),
					'category_subchild_id'=>post('sub_category_id'),
					);
					insertTable('proposal_category',$proposal_category);
					
					$proposal_additional=array(
					'proposal_id'=>$proposal_id,
					'proposal_description'=>NULL,
					'buyer_instruction'=>'',
					'proposal_video'=>NULL,
					);
					if($this->input->post('proposal_description')){
						$proposal_additional['proposal_description']=htmlentities(post('proposal_description'));
					}
					if($this->input->post('buyer_instruction')){
						$proposal_additional['buyer_instruction']=trim(strip_tags(post('buyer_instruction')));
					}
					if($this->input->post('projectvideo')){
						$file_data=json_decode(post('projectvideo'));
						if($file_data){
							if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
								rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."proposal-video/".$file_data->file_name);
								$proposal_additional['proposal_video']=$file_data->file_name;
							}
						}
					}
					insertTable('proposal_additional',$proposal_additional);
					if($this->input->post('proposal_tags')){
						$proposal_tag=explode(',',post('proposal_tags'));
						foreach($proposal_tag as $tag){
							$proposal_tags=array(
							'proposal_id'=>$proposal_id,
							'tag_name'=>strip_tags($tag),
							'add_date'=>date('Y-m-d H:i:s'),
							);
							insertTable('proposal_tags',$proposal_tags);
						}
					}
					$proposal_settings=array(
						'proposal_id'=>$proposal_id,
						/*'level_id'=>0,
						'language_id'=>0,*/
						'proposal_referral_code'=>0,
						'proposal_enable_referrals'=>0,
						'proposal_referral_money'=>0,
						'proposal_featured'=>0,
					);
					if($this->input->post('proposal_enable_referrals') && $this->input->post('proposal_enable_referrals')=='1'){
						$proposal_settings['proposal_enable_referrals']=1;
						$proposal_settings['proposal_referral_code']=mt_rand();
						$proposal_settings['proposal_referral_money']=post('proposal_referral_money');
					}
					insertTable('proposal_settings',$proposal_settings);
					
					$proposal_stat=array(
					'proposal_id'=>$proposal_id,
					'proposal_rating'=>0,
					'proposal_views'=>0,
					);
					insertTable('proposal_stat',$proposal_stat);
					if(post('projectfile')){
						$projectfiles=post('projectfile');
						foreach($projectfiles as $file){
							$file_data=json_decode($file);
							if($file_data){
								if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
									rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."proposal-files/".$file_data->file_name);
									$ext=explode('.',$file_data->file_name);
									$files=array(
									'original_name'=>$file_data->original_name,
									'server_name'=>$file_data->file_name,
									'upload_time'=>date('Y-m-d H:i:s'),
									'file_ext'=>strtolower(end($ext)),
									);
									$file_id=insertTable('files',$files,TRUE);
									if($file_id){
										$proposal_files=array(
										'proposal_id'=>$proposal_id,
										'file_id'=>$file_id,
										);
										insertTable('proposal_files',$proposal_files);
									}
								}
							}
						}
					}
					$proposal_packages=array(
					'proposal_id'=>$proposal_id,
					'package_name'=>'Basic',
					'price'=>150,
					'delivery_time'=>1,
					'description'=>'Package Info',
					);
					insertTable('proposal_packages',$proposal_packages);
					$proposal_packages=array(
					'proposal_id'=>$proposal_id,
					'package_name'=>'Standard',
					'price'=>250,
					'delivery_time'=>2,
					'description'=>'Package Info',
					);
					insertTable('proposal_packages',$proposal_packages);
					$proposal_packages=array(
					'proposal_id'=>$proposal_id,
					'package_name'=>'Advance',
					'price'=>450,
					'delivery_time'=>3,
					'description'=>'Package Info',
					);
					insertTable('proposal_packages',$proposal_packages);
					updateTable('proposals',array('display_price'=>250),array('proposal_id'=>$proposal_id));
					
					$SELLER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
					$PROPOSAL_CATEGORY=getAllCategory(array('category_id'=>$proposal_category['category_id']));
					$template='new-proposal-create';
					$data_parse=array(
					'SELLER_NAME'=>$SELLER_NAME,
					'PROPOSAL_TITLE'=>$proposals['proposal_title'],
					'PROPOSAL_CATEGORY'=>$PROPOSAL_CATEGORY->name,
					'PROPOSAL_STATUS'=>'Pending',
					'ADMIN_PROPOSAL_LINK'=>ADMIN_URL.'proposal/list_record',
					);
					SendMail('',get_option_value('admin_email'),$template,$data_parse);
					
					
					
					$token=md5('FVRR'.'-'.date("Y-m-d").'-'.$proposal_id);
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=price';
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
	public function editproposalCheckAjax(){
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			$tab=post('tab');
			$proposal_id=post('pid');
			$token=post('token');
			$verify_token=md5('FVRR'.'-'.date("Y-m-d").'-'.$proposal_id);
			if($token==$verify_token){
				$arr=array(
					'select'=>'p.proposal_id',
					'table'=>'proposals p',
					'where'=>array('p.proposal_seller_id'=>$this->member_id,'p.proposal_id'=>$proposal_id),
					'single_row'=>true,
				);
				$check_proposal=getData($arr);
				if($check_proposal){
					$proposal_id=$check_proposal->proposal_id;
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'proposal_title';
					$msg['errors'][$i]['message'] = 'Opps! invalid request.';
	   				$i++;
				}
			}else{
				$msg['status'] = 'FAIL';
    			$msg['errors'][$i]['id'] = 'proposal_title';
				$msg['errors'][$i]['message'] = 'Opps! invalid request.';
   				$i++;
			}	
			if($i>0){
				unset($_POST);
				echo json_encode($msg);
				die;
			}
		$token=md5('FVRR'.'-'.date("Y-m-d").'-'.$proposal_id);
		if($tab=='pricefixed'){
		fromVRules('proposal_price', 'price', 'required|trim|xss_clean|numeric|greater_than[0]');
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
					updateTable('proposals',array('proposal_price'=>post('proposal_price'),'display_price'=>post('proposal_price')),array('proposal_id'=>$proposal_id));
					$msg['status'] = 'OK';
					//$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=price';
					$msg['redirect'] =get_link('manageproposalURL');
				}
			}
		}elseif($tab=='savepackageall'){
		fromVRules('package_1', 'package', 'required|trim|xss_clean|numeric');
		fromVRules('package_2', 'package', 'required|trim|xss_clean|numeric');
		fromVRules('package_3', 'package', 'required|trim|xss_clean|numeric');
		fromVRules('package_time_1', 'time', 'required|trim|xss_clean|numeric|greater_than[0]');
		fromVRules('package_time_2', 'time', 'required|trim|xss_clean|numeric|greater_than[0]');
		fromVRules('package_time_3', 'time', 'required|trim|xss_clean|numeric|greater_than[0]');
		fromVRules('package_desc_1', 'description', 'required|trim|xss_clean|min_length[70]|max_length[1200]');
		fromVRules('package_desc_2', 'description', 'required|trim|xss_clean|min_length[70]|max_length[1200]');
		fromVRules('package_desc_3', 'description', 'required|trim|xss_clean|min_length[70]|max_length[1200]');
		fromVRules('package_price_1', 'price', 'required|trim|xss_clean|numeric|greater_than[0]');
		fromVRules('package_price_2', 'price', 'required|trim|xss_clean|numeric|greater_than[0]');
		fromVRules('package_price_3', 'price', 'required|trim|xss_clean|numeric|greater_than[0]');
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
					$package_id=post('package_1');
					updateTable('proposal_packages',array('description'=>post('package_desc_1'),'delivery_time'=>post('package_time_1'),'price'=>post('package_price_1')),array('proposal_id'=>$proposal_id,'package_id'=>$package_id));
					
					$package_id=post('package_2');
					updateTable('proposal_packages',array('description'=>post('package_desc_2'),'delivery_time'=>post('package_time_2'),'price'=>post('package_price_2')),array('proposal_id'=>$proposal_id,'package_id'=>$package_id));
					
					$package_id=post('package_3');
					updateTable('proposal_packages',array('description'=>post('package_desc_3'),'delivery_time'=>post('package_time_3'),'price'=>post('package_price_3')),array('proposal_id'=>$proposal_id,'package_id'=>$package_id));
					
					$display_price=0;
					$arr=array(
						'select'=>'p.price',
						'table'=>'proposal_packages p',
						'where'=>array('p.proposal_id'=>$proposal_id,'p.price >'=>0),
						'single_row'=>true,
						'order'=>array(array('price','asc'))
					);
					$check_proposal_price=getData($arr);
					if($check_proposal_price){
						$display_price=$check_proposal_price->price;
					}
					updateTable('proposals',array('display_price'=>$display_price),array('proposal_id'=>$proposal_id));
					$msg['status'] = 'OK';
					//$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=price';
					$msg['redirect'] =get_link('manageproposalURL');
				}
			}	
			
			
		}elseif($tab=='savepackage'){
		fromVRules('price', 'price', 'required|trim|xss_clean|numeric');
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
					$package_id=post('package_id');
					updateTable('proposal_packages',array('description'=>post('description'),'delivery_time'=>post('delivery_time'),'price'=>post('price')),array('proposal_id'=>$proposal_id,'package_id'=>$package_id));
					
					$display_price=0;
					$arr=array(
						'select'=>'p.price',
						'table'=>'proposal_packages p',
						'where'=>array('p.proposal_id'=>$proposal_id,'p.price >'=>0),
						'single_row'=>true,
						'order'=>array(array('price','asc'))
					);
					$check_proposal_price=getData($arr);
					if($check_proposal_price){
						$display_price=$check_proposal_price->price;
					}
					updateTable('proposals',array('display_price'=>$display_price),array('proposal_id'=>$proposal_id));
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=price';
				}
			}
		}elseif($tab=='addattribute'){
		fromVRules('attribute_name', 'name', 'required|trim|xss_clean');
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
				if($msg['errors'][$i]['attribute_name']){
					$msg['message'] = $val;
				}
			}else{
				if($i==0){
					$package_id=post('package_id');
					$package=getProposalDetails($proposal_id,array('proposal_packages'));
					if($package && $package['proposal_packages']){
						foreach($package['proposal_packages'] as $packagedata){
							insertTable('proposal_package_attributes',array('proposal_id'=>$proposal_id,'package_id'=>$packagedata->package_id,'attribute_name'=>post('attribute_name')));
						}
					}
					
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=price';
				}
			}
		}elseif($tab=='saveattribute'){
		fromVRules('attribute_value', 'value', 'required|trim|xss_clean');
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
				if($msg['errors'][$i]['attribute_value']){
					$msg['message'] = $val;
				}
			}else{
				if($i==0){
					$attribute_id=post('attribute_id');
					updateTable('proposal_package_attributes',array('attribute_value'=>post('attribute_value')),array('proposal_id'=>$proposal_id,'attribute_id'=>$attribute_id));
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=price';
				}
			}
		}elseif($tab=='deleteattribute'){
			fromVRules('attribute_name', 'name', 'required|trim|xss_clean');
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
				if($msg['errors'][$i]['attribute_name']){
					$msg['message'] = $val;
				}
			}else{
				if($i==0){
					$attribute_name=post('attribute_name');
					delete('proposal_package_attributes',array('attribute_name'=>post('attribute_name'),'proposal_id'=>$proposal_id));
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=price';
				}
			}
		}elseif($tab=='extraadd'){
			fromVRules('extraname', 'name', 'required|trim|xss_clean');
			fromVRules('extraprice', 'price', 'required|trim|xss_clean|numeric|greater_than[0]');
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
					$extraid=insertTable('proposal_extras',array('proposal_id'=>$proposal_id,'name'=>post('extraname'),'price'=>post('extraprice')),TRUE);
					if($extraid){
						$msg['status'] = 'OK';
						$msg['message'] = 'Your proposal extra has been inserted successfully';
						$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=extra';
					}else{
						$msg['status'] = 'FAIL';
						$msg['errors'][$i]['id'] = 'extraname';
						$msg['errors'][$i]['message'] = 'error';
		   				$i++;
					}
					
				}
			}	
		}elseif($tab=='extraupdate'){	
			fromVRules('extraname', 'name', 'required|trim|xss_clean');
			fromVRules('extraprice', 'price', 'required|trim|xss_clean|numeric|greater_than[0]');
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
					$id=post('id');
					$extraid=updateTable('proposal_extras',array('name'=>post('extraname'),'price'=>post('extraprice')),array('proposal_id'=>$proposal_id,'id'=>$id));
					if($extraid){
						$msg['status'] = 'OK';
						$msg['message'] = 'Your proposal extra has been updated successfully';
						$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=extra';
					}else{
						$msg['status'] = 'FAIL';
						$msg['errors'][$i]['id'] = 'extraname';
						$msg['errors'][$i]['message'] = 'error';
		   				$i++;
					}
					
				}
			}
		}elseif($tab=='extradelete'){	
			fromVRules('id', 'id', 'required|trim|xss_clean|numeric|greater_than[0]');
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
				if($msg['errors'][$i]['id']){
					$msg['id'] = $val;
				}
			}else{
				if($i==0){
					$id=post('id');
					$delete=delete('proposal_extras',array('id'=>$id,'proposal_id'=>$proposal_id));
					if($delete){
						$msg['status'] = 'OK';
						$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=extra';
					}else{
						$msg['status'] = 'FAIL';
					}
					
				}
			}
		
		
		}elseif($tab=='main'){	
		fromVRules('proposal_title', 'title', 'required|trim|xss_clean|min_length[15]|max_length[80]');
		fromVRules('proposal_description', 'description', 'required|trim|xss_clean|min_length[150]|max_length[1200]');
		fromVRules('category_id', 'category', 'required|trim|xss_clean');
		if(post('category_id')>0){
			fromVRules('sub_category_id', 'sub category', 'required|trim|xss_clean');
		}
		fromVRules('delivery_id', 'delivery time', 'required|trim|xss_clean');
		if($this->input->post('proposal_enable_referrals') && $this->input->post('proposal_enable_referrals')=='1'){
			fromVRules('proposal_referral_money', 'commision', 'required|trim|xss_clean');
		}
		fromVRules('proposal_tags', 'tag', 'required|trim|xss_clean');
		if($this->input->post('mainimageprevious')){
			
		}else{
			fromVRules('mainimage', 'image', 'required|trim|xss_clean');
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
		}else{
			if($i==0){
				
				$proposals=array(
					'proposal_title'=>strip_tags(post('proposal_title')),
					'delivery_time'=>post('delivery_id'),
					'proposal_date'=>date('Y-m-d H:i:s'),
					'proposal_status'=>PROPOSAL_PENDING,
					);
				
				$file_data=json_decode(post('mainimage'));
				if($file_data){
					if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
						rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."proposal-files/".$file_data->file_name);
						$proposals['proposal_image']=$file_data->file_name;
					}
				}else{
					$previousfile_data=json_decode(post('mainimageprevious'));
					if($previousfile_data){
						$proposals['proposal_image']=$previousfile_data->file_name;
					}
				}
				
				updateTable('proposals',$proposals,array('proposal_id'=>$proposal_id));
				if($proposal_id){
					delete('proposal_category',array('proposal_id'=>$proposal_id));
					$proposal_category=array(
					'proposal_id'=>$proposal_id,
					'category_id'=>post('category_id'),
					'category_subchild_id'=>post('sub_category_id'),
					);
					insertTable('proposal_category',$proposal_category);
					
					delete('proposal_additional',array('proposal_id'=>$proposal_id));
					$proposal_additional=array(
					'proposal_id'=>$proposal_id,
					'proposal_description'=>NULL,
					'buyer_instruction'=>'',
					'proposal_video'=>NULL,
					);
					if($this->input->post('proposal_description')){
						$proposal_additional['proposal_description']=htmlentities(post('proposal_description'));
					}
					if($this->input->post('buyer_instruction')){
						$proposal_additional['buyer_instruction']=trim(strip_tags(post('buyer_instruction')));
					}
					if($this->input->post('projectvideo')){
						$file_data=json_decode(post('projectvideo'));
						if($file_data){
							if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
								rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."proposal-video/".$file_data->file_name);
								$proposal_additional['proposal_video']=$file_data->file_name;
							}
						}
					}elseif($this->input->post('videoprevious')){
						$file_data=json_decode(post('videoprevious'));
						if($file_data){
							if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."proposal-video/".$file_data->file_name)){
								$proposal_additional['proposal_video']=$file_data->file_name;
							}
						}
					}
					insertTable('proposal_additional',$proposal_additional);
					delete('proposal_tags',array('proposal_id'=>$proposal_id));
					if($this->input->post('proposal_tags')){
						$proposal_tag=explode(',',post('proposal_tags'));
						foreach($proposal_tag as $tag){
							$proposal_tags=array(
							'proposal_id'=>$proposal_id,
							'tag_name'=>strip_tags($tag),
							'add_date'=>date('Y-m-d H:i:s'),
							);
							insertTable('proposal_tags',$proposal_tags);
						}
					}
					delete('proposal_settings',array('proposal_id'=>$proposal_id));
					$proposal_settings=array(
						'proposal_id'=>$proposal_id,
						/*'level_id'=>0,
						'language_id'=>0,*/
						'proposal_referral_code'=>0,
						'proposal_enable_referrals'=>0,
						'proposal_referral_money'=>0,
						'proposal_featured'=>0,
					);
					if($this->input->post('proposal_enable_referrals') && $this->input->post('proposal_enable_referrals')=='1'){
						$proposal_settings['proposal_enable_referrals']=1;
						$proposal_settings['proposal_referral_code']=mt_rand();
						$proposal_settings['proposal_referral_money']=post('proposal_referral_money');
					}
					insertTable('proposal_settings',$proposal_settings);
					
/*					$proposal_stat=array(
					'proposal_id'=>$proposal_id,
					'proposal_rating'=>0,
					'proposal_views'=>0,
					);
					insertTable('proposal_stat',$proposal_stat);*/
					
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
						$this->db->where_not_in('file_id',$previous_file)->where('proposal_id',$proposal_id)->delete('proposal_files');
					}else{
						$this->db->where('proposal_id',$proposal_id)->delete('proposal_files');
					}
					if(post('projectfile')){
						$projectfiles=post('projectfile');
						foreach($projectfiles as $file){
							$file_data=json_decode($file);
							if($file_data){
								if($file_data->file_name && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name)){
									rename(ABS_USERUPLOAD_PATH."tempfile/".$file_data->file_name, ABS_USERUPLOAD_PATH."proposal-files/".$file_data->file_name);
									$ext=explode('.',$file_data->file_name);
									$files=array(
									'original_name'=>$file_data->original_name,
									'server_name'=>$file_data->file_name,
									'upload_time'=>date('Y-m-d H:i:s'),
									'file_ext'=>strtolower(end($ext)),
									);
									$file_id=insertTable('files',$files,TRUE);
									if($file_id){
										$proposal_files=array(
										'proposal_id'=>$proposal_id,
										'file_id'=>$file_id,
										);
										insertTable('proposal_files',$proposal_files);
									}
								}
							}
						}
					}
					$SELLER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
					$PROPOSAL_CATEGORY=getAllCategory(array('category_id'=>$proposal_category['category_id']));
					$template='new-proposal-create';
					$data_parse=array(
					'SELLER_NAME'=>$SELLER_NAME,
					'PROPOSAL_TITLE'=>$proposals['proposal_title'],
					'PROPOSAL_CATEGORY'=>$PROPOSAL_CATEGORY->name,
					'PROPOSAL_STATUS'=>'Pending',
					'ADMIN_PROPOSAL_LINK'=>ADMIN_URL.'proposal/list_record',
					);
					SendMail('',get_option_value('admin_email'),$template,$data_parse);
					$msg['status'] = 'OK';
					$msg['redirect'] =get_link('editproposalURL').'/'.$proposal_id.'/'.$token.'?tab=price';
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'request_title';
					$msg['errors'][$i]['message'] = 'Error occur';
				}
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
			}elseif($this->input->get('type') && $this->input->get('type')=='video'){
				
				$allowed = array('mp4','mov','avi','flv','wmv');
				 $config['max_size']             = 1024*50;
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
	public function actionproposalCheckAjax(){
		$msg=array();
		$all_action=array('pause','delete','active');
		checkrequestajax();
		if($this->input->post('rid') && $this->input->post('action')){
			$action=post('action');
			$proposal_id=post('rid');
			if(in_array($action,$all_action)){
				$arr=array(
					'select'=>'p.proposal_id,p.proposal_status',
					'table'=>'proposals p',
					'where'=>array('p.proposal_id'=>$proposal_id,'p.proposal_seller_id'=>$this->member_id),
					'single_row'=>true,
				);
				$proposals=getData($arr);
				if($proposals){
					if($action=='pause'){
						updateTable('proposals',array('proposal_status'=>PROPOSAL_PAUSED),array('proposal_id'=>$proposals->proposal_id,'proposal_status'=>PROPOSAL_ACTIVE));
						$msg['message']=__('popup_proposal_pause_success','One proposal has been paused.');
					}elseif($action=='delete'){
						updateTable('proposals',array('proposal_status'=>PROPOSAL_DELETED),array('proposal_id'=>$proposals->proposal_id));
						$msg['message']=__('popup_proposal_deleted_success','One proposal has been deleted successfully');
					}elseif($action=='active'){
						updateTable('proposals',array('proposal_status'=>PROPOSAL_ACTIVE),array('proposal_id'=>$proposals->proposal_id,'proposal_status'=>PROPOSAL_PAUSED));
						$msg['message']=__('popup_proposal_active_success','One proposal has been activated.');
					}
					$msg['status']='OK';
				}else{
					$msg['status']='FAIL';
					$msg['message']='Invalid proposal';
				}
				
			}else{
				$msg['status']='FAIL';
				$msg['message']='Invalid proposal';
			}	
		}else{
			$msg['status']='FAIL';
			$msg['message']='Invalid proposal';
		}
		echo json_encode($msg);
	}
	public function view($username,$project_url){
		loadModel('proposal_model');
		$data=array();
		$data['is_login']=0;
		$data['is_report']=0;
		$data['is_owner']=0;
		
		$arr=array(
					'select'=>'p.proposal_id',
					'table'=>'proposals p',
					'where'=>array('p.proposal_status <>'=>PROPOSAL_DELETED,'p.proposal_url'=>$project_url),
					'single_row'=>true,
				);
		$check_proposal=getData($arr);
		if($check_proposal){
			
		}else{
			setFMessage('invalidURL','Invalid url');
			redirect(get_link('homeURL'));
		}
		$data['proposal_details']=getProposalDetails($check_proposal->proposal_id);
		if($this->loggedUser){
			$data['loggedUser']=$this->loggedUser;
			$data['is_login']=1;
			$data['is_report']=$this->db->where('reporter_id',$this->member_id)->where('content_id',$check_proposal->proposal_id)->where('content_type','proposal')->from('reports')->count_all_results();
			if($this->member_id==$data['proposal_details']['proposal']->proposal_seller_id){
				$data['is_owner']=1;
			}else{
				delete('proposals_last_views',array('seller_id'=>$this->member_id,'proposal_id'=>$check_proposal->proposal_id));
				insertTable('proposals_last_views',array('seller_id'=>$this->member_id,'proposal_id'=>$check_proposal->proposal_id));
			}
		}
		if($data['is_owner']==0){
			if($this->session->userdata('no_of_views-'.$check_proposal->proposal_id)){
			}else{
				$this->db->where('proposal_id',$check_proposal->proposal_id)->set('proposal_views','`proposal_views`+1',false)->update('proposal_stat');
				$this->session->set_userdata('no_of_views-'.$check_proposal->proposal_id,TRUE);
			}
		}
		
		$arr=array(
					'select'=>'b.buyer_rating,b.buyer_review,b.order_id,b.review_date,m.member_name as buyer_name,s.seller_rating,s.seller_review,s.review_date,s.review_id as seller_review_id',
					'table'=>'buyer_reviews b',
					'join'=>array(array('table'=>'seller_reviews as s','on'=>'b.order_id=s.order_id','position'=>'left'),array('table'=>'member as m','on'=>'b.review_buyer_id=m.member_id','position'=>'left')),
					'where'=>array('b.proposal_id'=>$check_proposal->proposal_id),
					'order'=>array(array('b.review_date','asc')),
				);
		$buyer_reviews=getData($arr);
		$data['buyer_reviews']=$buyer_reviews;
		$data['owner_details']=getMemberDetails($data['proposal_details']['proposal']->proposal_seller_id,array('main'=>1,'member_address'=>1,'member_languages'=>1));
		
		$data['other_proposal']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_set.proposal_featured,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'proposals p',
				'join'=>array(
				
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'p.proposal_id <>'=>$check_proposal->proposal_id,'p.proposal_seller_id'=>$data['proposal_details']['proposal']->proposal_seller_id),
				'order'=>array(array('rand()',NULL)),
				'limit'=>'6'
				));
		if($this->loggedUser){
		$data['recent_proposals']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_set.proposal_featured,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'proposals_last_views r_p',
				'join'=>array(
				array('table'=>'proposals as p','on'=>'r_p.proposal_id=p.proposal_id','position'=>'left'),
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'r_p.seller_id'=>$this->member_id),
				'order'=>array(array('rand()',NULL)),
				'limit'=>'4'
				));	
		}	
		$data['proposal_order_queue']=$this->db->where('proposal_id',$check_proposal->proposal_id)->where('order_active',1)->from('orders')->count_all_results();
		$data['seo_tags']=array(
		'meta_title'=>$data['proposal_details']['proposal']->proposal_title,
		'meta_key'=>'',
		'meta_description'=>strip_tags(html_entity_decode($data['proposal_details']['proposal_additional']->proposal_description)),
		'seo_images'=>array(URL_USERUPLOAD.'proposal-files/'.$data['proposal_details']['proposal']->proposal_image),
		);
		$templateLayout=array('view'=>'proposal-details','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function updatefavorite(){
		$msg=array();
		checkrequestajax();
		$proposal_id=post('proposal_id');
		if($proposal_id){
			$check=$this->db->where('member_id',$this->member_id)->where('proposal_id',$proposal_id)->from('favorites')->count_all_results();
			if($check){
				delete('favorites',array('member_id'=>$this->member_id,'proposal_id'=>$proposal_id));
			}else{
				insertTable('favorites',array('member_id'=>$this->member_id,'proposal_id'=>$proposal_id));
			}
			$msg['status']='OK';
		}
		echo json_encode($msg);
	}
	public function referralmodal(){
		checkrequestajax();
		$proposal_id=post('proposal_id');
		if($proposal_id){
			$proposal_details=getProposalDetails($proposal_id,array('proposal_settings'));
			if($proposal_details){
				$affiliatedata['link']=get_link('referralShareLink')."/".$proposal_id.'/'.$proposal_details['proposal_settings']->proposal_referral_code.'/'.$this->member_id;
				$affiliatedata['proposal_referral_money']=$proposal_details['proposal_settings']->proposal_referral_money;
				$templateLayout=array('view'=>'referralmodal','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
				load_template($templateLayout,$affiliatedata);
			}
			
		}
		
	}
	public function sharereferral($proposal_id,$proposal_code,$refer_id){
		if($proposal_id){
			$proposal_details=getProposalDetails($proposal_id,array('proposal','proposal_settings'));
			if($proposal_details){
				if($proposal_details['proposal_settings']->proposal_enable_referrals==1 && $proposal_details['proposal_settings']->proposal_referral_code==$proposal_code){
					$this->session->set_userdata('referred-'.$proposal_id,array('proposal_id'=>$proposal_id,'refer_id'=>$refer_id));
				}
				$url=get_link('ProposalDetailsURL').'/'.getUserName($proposal_details['proposal']->proposal_seller_id).'/'.$proposal_details['proposal']->proposal_url;
				redirect($url);
				die;
			}
		}
		redirect(get_link('homeURL'));
	}
	public function favorites(){
		$data['loggedUser']=$this->loggedUser;
		$data['member_details']=getMemberDetails($this->member_id,array('main'=>1));
		$data['all_favorites']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_set.proposal_featured,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'favorites f',
				'join'=>array(
				array('table'=>'proposals as p','on'=>'f.proposal_id=p.proposal_id','position'=>'left'),
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'f.member_id'=>$this->member_id),
				));	
		$templateLayout=array('view'=>'favorites','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function referral_proposal(){
		$data['loggedUser']=$this->loggedUser;
		$data['member_details']=getMemberDetails($this->member_id,array('main'=>1));
		$data['all_referrals']=getData(array('select'=>'p_r.referral_id,p_r.comission,p_r.date,p_r.status,ms.member_name as seller_name,mb.member_name as buyer_name,p.proposal_title',
				'table'=>'proposals_referrals p_r',
				'join'=>array(
					array('table'=>'proposals as p','on'=>'p_r.proposal_id=p.proposal_id','position'=>'left'),
					array('table'=>'member as ms','on'=>'p_r.seller_id=ms.member_id','position'=>'left'),
					array('table'=>'member as mb','on'=>'p_r.buyer_id=mb.member_id','position'=>'left')
					),
				'where'=>array('p_r.referrer_id'=>$this->member_id),
				'order'=>array(array('p_r.referral_id','desc'))
				));	
		$data['approved_referrals']=$this->db->select('sum(comission) as total')->where('referrer_id',$this->member_id)->where('status',1)->from('proposals_referrals')->get()->row();
		$data['pending_referrals']=$this->db->select('sum(comission) as total')->where('referrer_id',$this->member_id)->where('status',0)->from('proposals_referrals')->get()->row();
		$data['declined_referrals']=$this->db->select('sum(comission) as total')->where('referrer_id',$this->member_id)->where('status',2)->from('proposals_referrals')->get()->row();
		$templateLayout=array('view'=>'referral','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function report($proposal_id){
		checkrequestajax();
		$i=0;
		$msg=array();
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
					'content_id'=>$proposal_id,
					'content_type'=>'proposal',
					'reason'=>post('reason'),
					'additional_information'=>post('additional_information'),
				);
				$reports_id=insertTable('reports',$reports,TRUE);
				if($reports_id){
					$msg['message'] = 'Your Report Has Been Successfully Submited';
				}
				$msg['status'] = 'OK';
			}
		}
		unset($_POST);
		echo json_encode($msg);
	}
}
