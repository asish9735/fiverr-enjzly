<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MX_Controller {

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
		}elseif($this->router->fetch_method()=='paypalnotify' || $this->router->fetch_method()=='ngeniusnotify'){
		
		}else{
			redirect(get_link('loginURL'));
		}
		//loadModel('cart_model');
		loadModel('notifications/notification_model');
			parent::__construct();
	}
	public function check_payment_telr($order_ref,$type,$id='') {
		$post_data = Array(
		'ivp_method'	=> 'check',
		'ivp_authkey'	=> get_option_value('telr_authentication_code'),
		'ivp_store'	=>  get_option_value('telr_store_id'),
		'order_ref'	=> $order_ref,
		);
		$returnData = curl_telr($post_data);
		$msg = json_encode($returnData);
		//file_put_contents(ABS_USERUPLOAD_PATH.'telr.log', $msg);
		//dd($returnData);
		$objOrder='';
		$objError='';
		if (isset($returnData['order'])) { $objOrder = $returnData['order']; }
		if (isset($returnData['error'])) { $objError = $returnData['error']; }
		if (is_array($objError)) { // Failed
			return false;
		}
		if (!isset(
			$objOrder['cartid'],
			$objOrder['status']['code'],
			$objOrder['transaction']['status'],
			$objOrder['transaction']['ref'])) {
			// Missing fields
			return false;
		}
		$new_tx=$objOrder['transaction']['Tref'];
		$ordStatus=$objOrder['status']['code'];
		$txStatus=$objOrder['transaction']['status'];
		if (($ordStatus==-1) || ($ordStatus==-2)) {
			// Order status EXPIRED (-1) or CANCELLED (-2)
			//$this->payment_cancelled($order_id,$new_tx);
			return false;
		}
		if (($ordStatus==2) || ($ordStatus==4)) {
			// Order status AUTH (2) or PAYMENT_REQUESTED (4)
			//$this->payment_pending($order_id,$new_tx);
			//return true;
			return false;
		}
		if ($ordStatus==3) {
			// Order status PAID (3)
			if (($txStatus=='P') || ($txStatus=='H')) {
				// Transaction status of pending or held
				//$this->payment_pending($order_id,$new_tx);
				//return true;
				return false;
			}
			if ($txStatus=='A') {
				$verify_token=$objOrder['cartid'];
				updateTable('online_transaction_data',array('response_value'=>$msg),array('content_key'=>$verify_token));
				// Transaction status = authorised
				$this->payment_authorised_telr($verify_token,$type,$id);
				return true;
			}
		}
		return false;	
	}
	public function payment_authorised_telr($verify_token,$type,$id=''){
		$is_valid=0;
		$transaction_data=getData(array(
		'select'=>'request_value',
		'table'=>'online_transaction_data',
		'where'=>array('payment_type'=>'TELR','content_key'=>$verify_token),
		'single_row'=>TRUE
		));
		if($transaction_data){
			if($type=='checkout'){
				$order_status=ORDER_PROCESSING;
				$arr=array(
					'select'=>'o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction',
					'table'=>'orders as o',
					'join'=>array(
					array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','posiotion'=>'left'),
					array('table'=>'proposal_additional as p_a','on'=>'o.proposal_id=p_a.proposal_id','posiotion'=>'left'),
					),
					'where'=>array('o.order_id'=>$id,'o.order_status'=>0),
					'single_row'=>TRUE
				);
				$orderDetails=getData($arr);
				if($orderDetails){
					$total=$orderDetails->order_price;
					$order_id=$orderDetails->order_id;
					if(!empty($orderDetails->buyer_instruction)){
						$order_status=ORDER_PENDING;
					}
						$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
						$buyer_wallet_id=$buyer_details['member']->wallet_id;
						$buyer_wallet_balance=$buyer_details['member']->balance;
						$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						$recipient_relational_data=$buyer_details['member']->member_name;
						$telr_details=getWallet(get_option_value('TELR_WALLET'));
						$telr_wallet_id=$telr_details->wallet_id;
						$telr_wallet_balance=$telr_details->balance;
						$fee_wallet_details=getWallet(get_option_value('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;
						$order_fee=$orderDetails->order_fee;
						
						$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_TELR');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
							insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
							$telr_payment=$total+$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$telr_wallet_id,'debit'=>$telr_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Telr_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);*/	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_Topup',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Telr_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,	
								'TP'=>'Order_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
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
							updateTable('conversations_messages_offers',array('status'=>1),array('order_id'=>$order_id));
							updateTable('send_offers',array('status'=>1),array('order_id'=>$order_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_telr=displayamount($telr_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance_telr),array('wallet_id'=>$telr_wallet_id));
							wallet_balance_check($telr_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('OrderDetailsURL').$order_id;
							$template='new-order';
							$data_parse=array(
							'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
							'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
							'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
							'QTY'=>$orderDetails->order_qty,
							'DELIVERY_TIME'=>$orderDetails->order_duration,
							'ORDER_PRICE'=>$orderDetails->order_price,
							'ORDER_DETAILS_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							SendMail('',get_option_value('admin_email'),$template,$data_parse);
							
							$notificationData=array(
							'sender_id'=>$orderDetails->buyer_id,
							'receiver_id'=>$orderDetails->seller_id,
							'template'=>'order',
							'url'=>$this->config->item('OrderDetailsURL').$order_id,
							'content'=>json_encode(array('OID'=>$order_id)),
							);
							$this->notification_model->savenotification($notificationData);
						}
					}
			}
			elseif($type=='featured'){
				$featured_fee=get_option_value('featured_fee');
				$feeCalculation=generateProcessingFee('telr',$featured_fee);
				$order_fee=$feeCalculation['processing_fee'];
				$featured_duration=get_option_value('featured_duration');
				$total=$featured_fee;
				$arr=array(
					'select'=>'p.proposal_id,p.proposal_seller_id',
					'table'=>'proposals p',
					'where'=>array('p.proposal_id'=>$id),
					'single_row'=>true,
				);
				$check_proposal=getData($arr);
				IF($check_proposal){
					$proposal_id=$check_proposal->proposal_id;
					$seller_details=getMemberDetails($check_proposal->proposal_seller_id,array('main'=>1));
					$seller_wallet_id=$seller_details['member']->wallet_id;
					$seller_wallet_balance=$seller_details['member']->balance;
					$site_details=getWallet(get_option_value('SITE_PROFIT_WALLET'));
					$reciver_wallet_id=$site_details->wallet_id;
					$reciver_wallet_balance=$site_details->balance;
					$recipient_relational_data=$seller_details['member']->member_name;
					$telr_details=getWallet(get_option_value('TELR_WALLET'));
					$telr_wallet_id=$telr_details->wallet_id;
					$telr_wallet_balance=$telr_details->balance;
					$fee_wallet_details=getWallet(get_option_value('PROCESSING_FEE_WALLET'));
					$fee_wallet_id=$fee_wallet_details->wallet_id;
					$fee_wallet_balance=$fee_wallet_details->balance;
					
					
					$wallet_transaction_type_id=get_option_value('FEATURED_PAYMENT_TELR');
					$current_datetime=date('Y-m-d H:i:s');
					$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
					if($wallet_transaction_id){
						$featured_end_date=date('Y-m-d H:i:s',strtotime('+'.$featured_duration.' days'));
						updateTable('proposal_settings',array('proposal_featured'=>1,'featured_end_date'=>$featured_end_date),array('proposal_id'=>$proposal_id));
						$telr_payment=$total+$order_fee;
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$telr_wallet_id,'debit'=>$telr_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$telr_details->title,
							'TW'=>$seller_details['member']->member_name.' wallet',	
							'TP'=>'Telr_Payment',
							));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$paypal_details->title,
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);*/	
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$telr_details->title,
							'TW'=>$seller_details['member']->member_name.' wallet',	
							'TP'=>'Wallet_Topup',
							));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Telr_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$telr_details->title,
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
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
						
						$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
						wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
						updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
						wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance_telr=displayamount($telr_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance_telr),array('wallet_id'=>$telr_wallet_id));
						wallet_balance_check($telr_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$RECEIVER_EMAIL=$seller_details['member']->member_email;
						$url=get_link('manageproposalURL');
						$template='featured';
						$data_parse=array(
						'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
						'PROPOSAL_URL'=>$url,
						);
						SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
						
						$notificationData=array(
						'sender_id'=>0,
						'receiver_id'=>$seller_details['member']->member_id,
						'template'=>'featured',
						'url'=>$this->config->item('manageproposalURL'),
						'content'=>json_encode(array('PID'=>$proposal_id)),
						);
						$this->notification_model->savenotification($notificationData);
					}
				}	
			}
			elseif($type=='cart'){
					$allids=explode('-',$id);
					$allorder=$this->db->select('o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction')
					->from('orders as o')
					->join('proposals as p','o.proposal_id=p.proposal_id','left')
					->join('proposal_additional as p_a','o.proposal_id=p_a.proposal_id','left')
					->where_in('order_id',$allids)
					->where('o.order_status',0);
					$allorder=$this->db->get()->result();
					if($allorder){
						foreach($allorder as $orderDetails){
							$total=$orderDetails->order_price;
							$order_id=$orderDetails->order_id;
							if(!empty($orderDetails->buyer_instruction)){
								$order_status=ORDER_PENDING;
							}
							$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
							$buyer_wallet_id=$buyer_details['member']->wallet_id;
							$buyer_wallet_balance=$buyer_details['member']->balance;
							$telr_details=getWallet(get_option_value('TELR_WALLET'));
							$telr_wallet_id=$telr_details->wallet_id;
							$telr_wallet_balance=$telr_details->balance;
							$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
							$reciver_wallet_id=$site_details->wallet_id;
							$reciver_wallet_balance=$site_details->balance;
							$recipient_relational_data=$buyer_details['member']->member_name;
							$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_TELR');
							$current_datetime=date('Y-m-d H:i:s');
							$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
							if($wallet_transaction_id){
								updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
								insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$telr_wallet_id,'debit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Telr_Payment',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_topup',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
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
								$new_balance=displayamount($telr_wallet_balance,2)-displayamount($total,2);
								updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$telr_wallet_id));
								wallet_balance_check($telr_wallet_id,array('transaction_id'=>$wallet_transaction_id));
								
								$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
								$RECEIVER_EMAIL=$seller_details['member']->member_email;
								$url=get_link('OrderDetailsURL').$order_id;
								$template='new-order';
								$data_parse=array(
								'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
								'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
								'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
								'QTY'=>$orderDetails->order_qty,
								'DELIVERY_TIME'=>$orderDetails->order_duration,
								'ORDER_PRICE'=>$orderDetails->order_price,
								'ORDER_DETAILS_URL'=>$url,
								);
								SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
								SendMail('',get_option_value('admin_email'),$template,$data_parse);
								
								$notificationData=array(
								'sender_id'=>$orderDetails->buyer_id,
								'receiver_id'=>$orderDetails->seller_id,
								'template'=>'order',
								'url'=>$this->config->item('OrderDetailsURL').$order_id,
								'content'=>json_encode(array('OID'=>$order_id)),
								);
								$this->notification_model->savenotification($notificationData);
							}
						}
					}
				}
			
			
			
		}
	}
	public function telrnotify($type,$id=''){
		$data=array();
		$order_ref=$this->session->userdata('Tref');
		if($order_ref){
		$checkpyment=$this->check_payment_telr($order_ref,$type,$id);
		if($type=='checkout'){
			if($checkpyment){
				$data['redirect']=get_link('OrderDetailsURL').$id.'?ref=paymentsuccess';
			}else{
				$data['redirect']=get_link('buyingOrderURL');
			}
			
		}elseif($type=='featured'){
			if($checkpyment){
				$data['redirect']=get_link('manageproposalURL').'?ref=paymentsuccess';
			}else{
				$data['redirect']=get_link('manageproposalURL').'?ref=paymentfailed';
			}
		}
		$this->session->set_userdata('Tref','');
		$templateLayout=array('view'=>'telr-form','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
		}else{
			redirect(get_link('homeURL'));
		}
	}
	public function paypal($type,$id=''){
		$data=array();
		
		if($type=='checkout' && $id){
			$arr=array(
				'select'=>'o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction',
				'table'=>'orders as o',
				'join'=>array(
				array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','posiotion'=>'left'),
				array('table'=>'proposal_additional as p_a','on'=>'o.proposal_id=p_a.proposal_id','posiotion'=>'left'),
				),
				'where'=>array('o.order_id'=>$id,'o.buyer_id'=>$this->member_id,'o.order_status'=>0),
				'single_row'=>TRUE
			);
			$orderDetails=getData($arr);
			if($orderDetails){
				$order_id=$orderDetails->order_id;
				$data['formdata']=array(
				'amount'=>$orderDetails->order_price+$orderDetails->order_fee,
				'return_url'=>get_link('OrderDetailsURL').$orderDetails->order_id.'?ref=paymentsuccess',
				'notify_url'=>get_link('PaypalNotify').$type.'/'.$orderDetails->order_id,
				'custom'=>md5('PPAY-'.$orderDetails->order_id),
				);
				
			}else{
				redirect(get_link('homeURL'));
			}
		}elseif($type=='featured'){
			$proposal_id=$id;
			$featured_fee=get_option_value('featured_fee');
			$feeCalculation=generateProcessingFee('paypal',$featured_fee);
			$processing_fee=$feeCalculation['processing_fee'];
			$data['formdata']=array(
			'amount'=>$featured_fee+$processing_fee,
			'return_url'=>get_link('manageproposalURL').'?ref=paymentsuccess',
			'notify_url'=>get_link('PaypalNotify').$type.'/'.$proposal_id,
			'custom'=>md5('PPAY-'.$proposal_id),
			);
			
		}elseif($type=='cart' && $id){
			$allids=explode('-',$id);
			$allorder=$this->db->select('sum(order_price) as total_price')->where_in('order_id',$allids)->from('orders')->get()->row();
			if($allorder){
				$total_order_price=$allorder->total_price;
				$feeCalculation=generateProcessingFee('paypal',$total_order_price);
				$processing_fee=$feeCalculation['processing_fee'];
				$data['formdata']=array(
				'amount'=>$total_order_price+$processing_fee,
				'return_url'=>get_link('buyingOrderURL').'?ref=paymentsuccess',
				'notify_url'=>get_link('PaypalNotify').$type.'/'.$id,
				'custom'=>md5('PPAY-'.$id),
				);
				
			}
		}else{
			redirect(get_link('homeURL'));
		}
		
		$transansaction_data=array('payment_type'=>'PAYPAL','content_key'=>$data['formdata']['custom']);
		
		$amount=$data['formdata']['amount'];
		$site_currency_code=CurrencyCode();
		if($site_currency_code=='AED'){
			$conversion=get_option_value('AED_TO_USD');
			$site_currency_code='USD';
			$amount=displayamount($data['formdata']['amount']*$conversion);
		}
		$data['formdata']['amount_converted']=$amount;
		
		$transansaction_data['request_value']=json_encode($data['formdata']);
		
		
		insertTable('online_transaction_data',$transansaction_data);
		$data['formdata']['currency_code']=$site_currency_code;
		$data['formdata']['url']='https://www.paypal.com/cgi-bin/webscr';
		$is_sandbox=get_option_value('is_sandbox');
		if($is_sandbox){
			$data['formdata']['url']='https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		$templateLayout=array('view'=>'paypal-form','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);

	}
	public function paypalnotify($type,$id){
		
		$msg = json_encode($this->input->post());
		//file_put_contents(ABS_USERUPLOAD_PATH.'paypal.log', $msg);
		require(APPPATH.'third_party/PaypalIPN.php');

		$ipn = new PaypalIPN();
		// Use the sandbox endpoint during testing.
		$is_sandbox=get_option_value('is_sandbox');
		if($is_sandbox){
			$ipn->useSandbox();
		}
		$verified = $ipn->verifyIPN();
		if ($verified) {
			$verify_token=post('custom');
			updateTable('online_transaction_data',array('response_value'=>$msg),array('content_key'=>$verify_token));
			$token=md5('PPAY-'.$id);
			$is_valid=0;
			$transaction_data=getData(array(
			'select'=>'request_value',
			'table'=>'online_transaction_data',
			'where'=>array('payment_type'=>'PAYPAL','content_key'=>$verify_token),
			'single_row'=>TRUE
			));
			if($transaction_data){
				$payment_gross=post('payment_gross');
				$payment_request=json_decode($transaction_data->request_value);
				if($payment_request && $payment_gross>=$payment_request->amount_converted){
					$is_valid=1;
				}
				
			}
			if($verify_token==$token && $is_valid==1){
				
				if($type=='checkout'){
					$order_status=ORDER_PROCESSING;
					
					$arr=array(
						'select'=>'o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction',
						'table'=>'orders as o',
						'join'=>array(
						array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','posiotion'=>'left'),
						array('table'=>'proposal_additional as p_a','on'=>'o.proposal_id=p_a.proposal_id','posiotion'=>'left'),
						),
						'where'=>array('o.order_id'=>$id,'o.order_status'=>0),
						'single_row'=>TRUE
					);
					$orderDetails=getData($arr);
					if($orderDetails){
						$total=$orderDetails->order_price;
						$order_id=$orderDetails->order_id;
						if(!empty($orderDetails->buyer_instruction)){
							$order_status=ORDER_PENDING;
						}
						$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
						$buyer_wallet_id=$buyer_details['member']->wallet_id;
						$buyer_wallet_balance=$buyer_details['member']->balance;
						$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						$recipient_relational_data=$buyer_details['member']->member_name;
						$paypal_details=getWallet(get_option_value('PAYPAL_WALLET'));
						$paypal_wallet_id=$paypal_details->wallet_id;
						$paypal_wallet_balance=$paypal_details->balance;
						$fee_wallet_details=getWallet(get_option_value('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;
						$order_fee=$orderDetails->order_fee;
						
						$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_PAYPAL');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
							insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
							$paypal_payment=$total+$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$paypal_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Paypal_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);*/	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_Topup',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,	
								'TP'=>'Order_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
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
							updateTable('conversations_messages_offers',array('status'=>1),array('order_id'=>$order_id));
							updateTable('send_offers',array('status'=>1),array('order_id'=>$order_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_paypal=displayamount($paypal_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance_paypal),array('wallet_id'=>$paypal_wallet_id));
							wallet_balance_check($paypal_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('OrderDetailsURL').$order_id;
							$template='new-order';
							$data_parse=array(
							'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
							'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
							'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
							'QTY'=>$orderDetails->order_qty,
							'DELIVERY_TIME'=>$orderDetails->order_duration,
							'ORDER_PRICE'=>$orderDetails->order_price,
							'ORDER_DETAILS_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							SendMail('',get_option_value('admin_email'),$template,$data_parse);
							
							$notificationData=array(
							'sender_id'=>$orderDetails->buyer_id,
							'receiver_id'=>$orderDetails->seller_id,
							'template'=>'order',
							'url'=>$this->config->item('OrderDetailsURL').$order_id,
							'content'=>json_encode(array('OID'=>$order_id)),
							);
							$this->notification_model->savenotification($notificationData);
						}
					}
				}elseif($type=='featured'){
					$featured_fee=get_option_value('featured_fee');
					$feeCalculation=generateProcessingFee('paypal',$featured_fee);
					$order_fee=$feeCalculation['processing_fee'];
					$featured_duration=get_option_value('featured_duration');
					$total=$featured_fee;
					$arr=array(
						'select'=>'p.proposal_id,p.proposal_seller_id',
						'table'=>'proposals p',
						'where'=>array('p.proposal_id'=>$id),
						'single_row'=>true,
					);
					$check_proposal=getData($arr);
					IF($check_proposal){
						$proposal_id=$check_proposal->proposal_id;
						$seller_details=getMemberDetails($check_proposal->proposal_seller_id,array('main'=>1));
						$seller_wallet_id=$seller_details['member']->wallet_id;
						$seller_wallet_balance=$seller_details['member']->balance;
						$site_details=getWallet(get_option_value('SITE_PROFIT_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						$recipient_relational_data=$seller_details['member']->member_name;
						$paypal_details=getWallet(get_option_value('PAYPAL_WALLET'));
						$paypal_wallet_id=$paypal_details->wallet_id;
						$paypal_wallet_balance=$paypal_details->balance;
						$fee_wallet_details=getWallet(get_option_value('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;
						
						
						$wallet_transaction_type_id=get_option_value('FEATURED_PAYMENT_PAYPAL');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$featured_end_date=date('Y-m-d H:i:s',strtotime('+'.$featured_duration.' days'));
							updateTable('proposal_settings',array('proposal_featured'=>1,'featured_end_date'=>$featured_end_date),array('proposal_id'=>$proposal_id));
							$paypal_payment=$total+$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$paypal_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$seller_details['member']->member_name.' wallet',	
								'TP'=>'Payment_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);*/	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$seller_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_Topup',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
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
							
							$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
							wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_paypal=displayamount($paypal_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance_paypal),array('wallet_id'=>$paypal_wallet_id));
							wallet_balance_check($paypal_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('manageproposalURL');
							$template='featured';
							$data_parse=array(
							'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
							'PROPOSAL_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							
							$notificationData=array(
							'sender_id'=>0,
							'receiver_id'=>$seller_details['member']->member_id,
							'template'=>'featured',
							'url'=>$this->config->item('manageproposalURL'),
							'content'=>json_encode(array('PID'=>$proposal_id)),
							);
							$this->notification_model->savenotification($notificationData);
						
							
						}
					}	
				}elseif($type=='cart'){
					$allids=explode('-',$id);
					$allorder=$this->db->select('o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction')
					->from('orders as o')
					->join('proposals as p','o.proposal_id=p.proposal_id','left')
					->join('proposal_additional as p_a','o.proposal_id=p_a.proposal_id','left')
					->where_in('order_id',$allids)
					->where('o.order_status',0);
					$allorder=$this->db->get()->result();
					if($allorder){
						foreach($allorder as $orderDetails){
							$total=$orderDetails->order_price;
							$order_id=$orderDetails->order_id;
							if(!empty($orderDetails->buyer_instruction)){
								$order_status=ORDER_PENDING;
							}
							$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
							$buyer_wallet_id=$buyer_details['member']->wallet_id;
							$buyer_wallet_balance=$buyer_details['member']->balance;
							$paypal_details=getWallet(get_option_value('PAYPAL_WALLET'));
							$paypal_wallet_id=$paypal_details->wallet_id;
							$paypal_wallet_balance=$paypal_details->balance;
							$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
							$reciver_wallet_id=$site_details->wallet_id;
							$reciver_wallet_balance=$site_details->balance;
							$recipient_relational_data=$buyer_details['member']->member_name;
							$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_PAYPAL');
							$current_datetime=date('Y-m-d H:i:s');
							$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
							if($wallet_transaction_id){
								updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
								insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Paypal_Payment',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_topup',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
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
								$new_balance=displayamount($paypal_wallet_balance,2)-displayamount($total,2);
								updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$paypal_wallet_id));
								wallet_balance_check($paypal_wallet_id,array('transaction_id'=>$wallet_transaction_id));
								
								$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
								$RECEIVER_EMAIL=$seller_details['member']->member_email;
								$url=get_link('OrderDetailsURL').$order_id;
								$template='new-order';
								$data_parse=array(
								'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
								'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
								'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
								'QTY'=>$orderDetails->order_qty,
								'DELIVERY_TIME'=>$orderDetails->order_duration,
								'ORDER_PRICE'=>$orderDetails->order_price,
								'ORDER_DETAILS_URL'=>$url,
								);
								SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
								SendMail('',get_option_value('admin_email'),$template,$data_parse);
								
								$notificationData=array(
								'sender_id'=>$orderDetails->buyer_id,
								'receiver_id'=>$orderDetails->seller_id,
								'template'=>'order',
								'url'=>$this->config->item('OrderDetailsURL').$order_id,
								'content'=>json_encode(array('OID'=>$order_id)),
								);
								$this->notification_model->savenotification($notificationData);
							}
						}
					}
				}
			}
		}
		// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
		header("HTTP/1.1 200 OK");
	}
	public function ngeniusnotify(){
	    $json = file_get_contents("php://input");
	    //file_put_contents(ABS_USERUPLOAD_PATH.'ngenius.log', $json);
	    $order = json_decode($json);
	    if($order){
	    	$verify_token=$ref=$order->order->reference;
	    	$eventName=$order->eventName;
	    	$transaction_data=getData(array(
			'select'=>'request_value',
			'table'=>'online_transaction_data',
			'where'=>array('payment_type'=>'NGENIUS','content_key'=>$verify_token),
			'single_row'=>TRUE
			));
			if($transaction_data && $eventName=='CAPTURED'){
				updateTable('online_transaction_data',array('response_value'=>$json),array('content_key'=>$verify_token));
				$payment_request=json_decode($transaction_data->request_value);
				$type=$payment_request->payment_type;
				$cart_id=$payment_request->cart_id;
				if($type=='checkout'){
					$id=$cart_id;
					$order_status=ORDER_PROCESSING;
					
					$arr=array(
						'select'=>'o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction',
						'table'=>'orders as o',
						'join'=>array(
						array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','posiotion'=>'left'),
						array('table'=>'proposal_additional as p_a','on'=>'o.proposal_id=p_a.proposal_id','posiotion'=>'left'),
						),
						'where'=>array('o.order_id'=>$id,'o.order_status'=>0),
						'single_row'=>TRUE
					);
					$orderDetails=getData($arr);
					if($orderDetails){
						$total=$orderDetails->order_price;
						$order_id=$orderDetails->order_id;
						if(!empty($orderDetails->buyer_instruction)){
							$order_status=ORDER_PENDING;
						}
						$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
						$buyer_wallet_id=$buyer_details['member']->wallet_id;
						$buyer_wallet_balance=$buyer_details['member']->balance;
						$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						$recipient_relational_data=$buyer_details['member']->member_name;
						$ngenius_details=getWallet(get_option_value('NGENIUS_WALLET'));
						$ngenius_wallet_id=$ngenius_details->wallet_id;
						$ngenius_wallet_balance=$ngenius_details->balance;
						$fee_wallet_details=getWallet(get_option_value('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;
						$order_fee=$orderDetails->order_fee;
						
						$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_NGENIUS');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
							insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
							$ngenius_payment=$total+$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$ngenius_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Ngenius_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Ngenius_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);*/	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_Topup',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Ngenius_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,	
								'TP'=>'Order_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
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
							updateTable('conversations_messages_offers',array('status'=>1),array('order_id'=>$order_id));
							updateTable('send_offers',array('status'=>1),array('order_id'=>$order_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_ngenius=displayamount($ngenius_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance_ngenius),array('wallet_id'=>$ngenius_wallet_id));
							wallet_balance_check($ngenius_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('OrderDetailsURL').$order_id;
							$template='new-order';
							$data_parse=array(
							'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
							'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
							'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
							'QTY'=>$orderDetails->order_qty,
							'DELIVERY_TIME'=>$orderDetails->order_duration,
							'ORDER_PRICE'=>$orderDetails->order_price,
							'ORDER_DETAILS_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							SendMail('',get_option_value('admin_email'),$template,$data_parse);
							
							$notificationData=array(
							'sender_id'=>$orderDetails->buyer_id,
							'receiver_id'=>$orderDetails->seller_id,
							'template'=>'order',
							'url'=>$this->config->item('OrderDetailsURL').$order_id,
							'content'=>json_encode(array('OID'=>$order_id)),
							);
							$this->notification_model->savenotification($notificationData);
						}
					}
				}elseif($type=='featured'){
					$ids=explode('-',$cart_id);
					$id=$ids[0];
					$featured_fee=get_option_value('featured_fee');
					$feeCalculation=generateProcessingFee('ngenius',$featured_fee);
					$order_fee=$feeCalculation['processing_fee'];
					$featured_duration=get_option_value('featured_duration');
					$total=$featured_fee;
					$arr=array(
						'select'=>'p.proposal_id,p.proposal_seller_id',
						'table'=>'proposals p',
						'where'=>array('p.proposal_id'=>$id),
						'single_row'=>true,
					);
					$check_proposal=getData($arr);
					IF($check_proposal){
						$proposal_id=$check_proposal->proposal_id;
						$seller_details=getMemberDetails($check_proposal->proposal_seller_id,array('main'=>1));
						$seller_wallet_id=$seller_details['member']->wallet_id;
						$seller_wallet_balance=$seller_details['member']->balance;
						$site_details=getWallet(get_option_value('SITE_PROFIT_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						$recipient_relational_data=$seller_details['member']->member_name;
						$ngenius_details=getWallet(get_option_value('NGENIUS_WALLET'));
						$ngenius_wallet_id=$ngenius_details->wallet_id;
						$ngenius_wallet_balance=$ngenius_details->balance;
						$fee_wallet_details=getWallet(get_option_value('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;
						
						
						$wallet_transaction_type_id=get_option_value('FEATURED_PAYMENT_NGENIUS');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$featured_end_date=date('Y-m-d H:i:s',strtotime('+'.$featured_duration.' days'));
							updateTable('proposal_settings',array('proposal_featured'=>1,'featured_end_date'=>$featured_end_date),array('proposal_id'=>$proposal_id));
							$ngenius_payment=$total+$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$ngenius_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$seller_details['member']->member_name.' wallet',	
								'TP'=>'Payment_Payment',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Ngenius_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);*/	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$seller_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_Topup',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Ngenius_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
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
							
							$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
							wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_ngenius=displayamount($ngenius_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance_ngenius),array('wallet_id'=>$ngenius_wallet_id));
							wallet_balance_check($ngenius_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('manageproposalURL');
							$template='featured';
							$data_parse=array(
							'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
							'PROPOSAL_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							
							$notificationData=array(
							'sender_id'=>0,
							'receiver_id'=>$seller_details['member']->member_id,
							'template'=>'featured',
							'url'=>$this->config->item('manageproposalURL'),
							'content'=>json_encode(array('PID'=>$proposal_id)),
							);
							$this->notification_model->savenotification($notificationData);
						
							
						}
					}	
				}elseif($type=='cart'){
					$allids=explode('-',$id);
					$allorder=$this->db->select('o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction')
					->from('orders as o')
					->join('proposals as p','o.proposal_id=p.proposal_id','left')
					->join('proposal_additional as p_a','o.proposal_id=p_a.proposal_id','left')
					->where_in('order_id',$allids)
					->where('o.order_status',0);
					$allorder=$this->db->get()->result();
					if($allorder){
						foreach($allorder as $orderDetails){
							$total=$orderDetails->order_price;
							$order_id=$orderDetails->order_id;
							if(!empty($orderDetails->buyer_instruction)){
								$order_status=ORDER_PENDING;
							}
							$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
							$buyer_wallet_id=$buyer_details['member']->wallet_id;
							$buyer_wallet_balance=$buyer_details['member']->balance;
							$ngenius_details=getWallet(get_option_value('NGENIUS_WALLET'));
							$ngenius_wallet_id=$ngenius_details->wallet_id;
							$ngenius_wallet_balance=$ngenius_details->balance;
							$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
							$reciver_wallet_id=$site_details->wallet_id;
							$reciver_wallet_balance=$site_details->balance;
							$recipient_relational_data=$buyer_details['member']->member_name;
							$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_NGENIUS');
							$current_datetime=date('Y-m-d H:i:s');
							$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
							if($wallet_transaction_id){
								updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
								insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Ngenius_Payment',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_topup',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
								insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
								
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
								$new_balance=displayamount($ngenius_wallet_balance,2)-displayamount($total,2);
								updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$ngenius_wallet_id));
								wallet_balance_check($ngenius_wallet_id,array('transaction_id'=>$wallet_transaction_id));
								
								$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
								$RECEIVER_EMAIL=$seller_details['member']->member_email;
								$url=get_link('OrderDetailsURL').$order_id;
								$template='new-order';
								$data_parse=array(
								'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
								'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
								'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
								'QTY'=>$orderDetails->order_qty,
								'DELIVERY_TIME'=>$orderDetails->order_duration,
								'ORDER_PRICE'=>$orderDetails->order_price,
								'ORDER_DETAILS_URL'=>$url,
								);
								SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
								SendMail('',get_option_value('admin_email'),$template,$data_parse);
								
								$notificationData=array(
								'sender_id'=>$orderDetails->buyer_id,
								'receiver_id'=>$orderDetails->seller_id,
								'template'=>'order',
								'url'=>$this->config->item('OrderDetailsURL').$order_id,
								'content'=>json_encode(array('OID'=>$order_id)),
								);
								$this->notification_model->savenotification($notificationData);
							}
						}
					}
				}
				
				
				
			}
		}
 		echo '{"success": true}';
	}
}
