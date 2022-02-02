<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends MX_Controller {

	function __construct()
	{	
		loadModel('notifications/notification_model');
		parent::__construct();
	}
	public function cron_autocancel_order(){
		//error_log('cron_autocancel_order-'.date('Y-m-d H:i:s').'\n',3,ABS_USERUPLOAD_PATH.'cron/cron-file.txt');
		$wh="(o.order_status <>".ORDER_CANCELLED." and o.order_status <>".ORDER_DELIVERED." and o.order_status <>".ORDER_COMPLETED.")";
		$arr=array(
			'select'=>'o.order_id,o.order_price,o.buyer_id,o.seller_id,o.order_status,o.order_time',
			'table'=>'orders as o',
			'where'=>array('o.order_status >'=>0,$wh=>NULL),
		);
		$arr['where'][' STR_TO_DATE(o.order_time, "%M %d, %Y %H:%i:%s") <']=date('Y-m-d H:i:s');
		$orderDetailsAll=getData($arr);
		if($orderDetailsAll){
			foreach($orderDetailsAll as $k=>$orderdetails){
				$order_id=$orderdetails->order_id;
				updateTable('proposals_referrals',array('status'=>'2'),array('order_id'=>$order_id));
				$currentStatus=getFieldData('order_status','orders','order_id',$order_id);
				$update=updateTable('orders',array('order_status'=>ORDER_CANCELLED,'order_active'=>0),array('order_id'=>$order_id));
				if($update && $currentStatus!=ORDER_CANCELLED){
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
						'sender_id'=>0,
						'receiver_id'=>$orderdetails->seller_id,
						'template'=>'order_cancelled',
						'url'=>'order-details/'.$order_id,
						'content'=>json_encode(array('OID'=>$order_id)),
						);
						$this->notification_model->savenotification($notificationData);
						$notificationData['receiver_id']=$orderdetails->buyer_id;
						$this->notification_model->savenotification($notificationData);
					}
				}	
			}
		}
		header("HTTP/1.1 200 OK");
	}
	public function cron_autocomplete_order(){
		//error_log('cron_autocomplete_order-'.date('Y-m-d H:i:s'),3,ABS_USERUPLOAD_PATH.'cron/cron-file.txt');
		$arr=array(
			'select'=>'o.order_id,o.order_price,o.buyer_id,o.seller_id,o.complete_time',
			'table'=>'orders as o',
			'where'=>array('o.order_status >'=>0,'o.order_status'=>ORDER_DELIVERED,'o.complete_time <>'=>''),
		);
		$arr['where']['o.complete_time <']=date('Y-m-d H:i:s');
		$orderDetailsAll=getData($arr);
		if($orderDetailsAll){
			foreach($orderDetailsAll as $k=>$orderdetails){
				$order_id=$orderdetails->order_id;
				updateTable('orders_conversations',array('status'=>'message'),array('order_id'=>$order_id,'status'=>'delivered'));
				$currentStatus=getFieldData('order_status','orders','order_id',$order_id);
				$update=updateTable('orders',array('order_status'=>ORDER_COMPLETED,'order_active'=>0),array('order_id'=>$order_id,'order_status'=>ORDER_DELIVERED));
				if($update && $currentStatus!=ORDER_COMPLETED){
					updateTable('member',array('recent_delivery_date'=>date('Y-m-d H:i:s')),array('member_id'=>$orderdetails->seller_id));
					$seller_details=getMemberDetails($orderdetails->seller_id,array('main'=>1));
					$total=$orderdetails->order_price;
					$comission_percentage=getComissionPercentage($orderdetails->seller_id);
					//$comission_percentage=get_option_value('comission_percentage');
					$days_before_withdraw=get_option_value('days_before_withdraw');
					$commission=($comission_percentage / 100 ) * $total;
					$seller_price=$total-$commission;
					$seller_wallet_id=$seller_details['member']->wallet_id;
					
					$revenue_date = date("Y-m-d H:i:s");
					$end_date = date("Y-m-d H:i:s", strtotime(" + ".$days_before_withdraw." days"));

					$revenues=array(
					'seller_id'=>$orderdetails->seller_id,
					'order_id'=>$order_id,
					'total'=>$total,
					'amount'=>$seller_price,
					'commission'=>$commission,
					'date'=>$revenue_date,
					'end_date'=>$end_date,
					'status'=>0,
					);
		
					$revenue_id=insertTable('revenues',$revenues,TRUE);
					$month_earnings=0;
					$earning=$this->db->select('SUM(amount) as total')->where('seller_id',$orderdetails->seller_id)->where('YEAR(date)',date('Y'))->where('MONTH(date)',date('m'))->group_by('seller_id')->from('revenues')->get()->row_array();
					if($earning){
						$month_earnings=$earning['total'];
					}
					$this->db->set('pending_clearance','pending_clearance+'.$seller_price,FALSE)->set('month_earnings',$month_earnings,FALSE)->where('wallet_id',$seller_wallet_id)->update('wallet');
					
					$notificationData=array(
					'sender_id'=>$orderdetails->buyer_id,
					'receiver_id'=>$orderdetails->seller_id,
					'template'=>'order_completed',
					'url'=>$this->config->item('OrderDetailsURL').$order_id,
					'content'=>json_encode(array('OID'=>$order_id)),
					);
					$this->notification_model->savenotification($notificationData);
					
				}
			}
		}
		header("HTTP/1.1 200 OK");
	}
	public function cron_release_revenue(){
		//error_log('cron_release_revenue-'.date('Y-m-d H:i:s'),3,ABS_USERUPLOAD_PATH.'cron/cron-file.txt');
		$all_revenues=$this->db->select('revenue_id,order_id,amount,seller_id,commission,total')->where('end_date <= ',date('Y-m-d H:i:s'))->where('status',0)->order_by('revenue_id','asc')->from('revenues')->get()->result();
		if($all_revenues){
			$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
			$sender_wallet_id=$site_details->wallet_id;
			$sender_wallet_balance=$site_details->balance;
			
			$profit_wallet_details=getWallet(get_option_value('SITE_PROFIT_WALLET'));
			$profit_wallet_id=$profit_wallet_details->wallet_id;
			$profit_wallet_balance=$profit_wallet_details->balance;
			
			$recipient_relational_data=get_option_value('website_name');
			//$comission_percentage=get_option_value('comission_percentage');

			foreach($all_revenues as $revenues){
				$seller_details=getMemberDetails($revenues->seller_id,array('main'=>1));
				$seller_wallet_id=$seller_details['member']->wallet_id;
				$seller_wallet_balance=$seller_details['member']->balance;
				
				$wallet_transaction_type_id=get_option_value('ORDER_REVENUE_TO_SELLER');
				$current_datetime=date('Y-m-d H:i:s');
				$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
				if($wallet_transaction_id){
					insertTable('orders_transaction',array('order_id'=>$revenues->order_id,'transaction_id'=>$wallet_transaction_id));
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$sender_wallet_id,'debit'=>$revenues->total,'description_tkey'=>'OrderID','relational_data'=>$revenues->order_id);
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$site_details->title,
								'TW'=>$seller_details['member']->member_name.' wallet',
								'TP'=>'Revenue',
								));
					insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
					
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'credit'=>$revenues->total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$site_details->title,
								'TW'=>$seller_details['member']->member_name.' wallet',
								'TP'=>'Revenue',
								));
					insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
					
					$sender_wallet_balance=displayamount($sender_wallet_balance,2)-displayamount($revenues->total,2);
					updateTable('wallet',array('balance'=>$sender_wallet_balance),array('wallet_id'=>$sender_wallet_id));
					wallet_balance_check($sende_wallet_id,array('transaction_id'=>$wallet_transaction_id));
					
					$seller_new_balance=displayamount($seller_wallet_balance,2)+displayamount($revenues->total,2);
					updateTable('wallet',array('balance'=>$seller_new_balance),array('wallet_id'=>$seller_wallet_id));
					wallet_balance_check($seller_wallet_id,array('transaction_id'=>$wallet_transaction_id));
					$pending_clearance=$revenues->amount;
					$this->db->set('pending_clearance','pending_clearance-'.$pending_clearance,FALSE)->where('wallet_id',$seller_wallet_id)->update('wallet',array('balance'=>$seller_new_balance));
					
					
					//$commission=($comission_percentage / 100 ) * $revenues->amount;
					$commission=$revenues->commission;
					$current_datetime=date('Y-m-d H:i:s');
					$wallet_transaction_type_id=get_option_value('ORDER_SITE_COMMISSION');
					$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
					if($wallet_transaction_id){
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'debit'=>$commission,'description_tkey'=>'Commission','relational_data'=>$revenues->order_id);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$seller_details['member']->member_name.' wallet',
								'TW'=>$profit_wallet_details->title,
								'TP'=>'Commission',
								));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$profit_wallet_id,'credit'=>$commission,'description_tkey'=>'Commission','relational_data'=>$revenues->order_id);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$seller_details['member']->member_name.' wallet',
								'TW'=>$profit_wallet_details->title,
								'TP'=>'Commission',
								));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$profit_wallet_balance=displayamount($profit_wallet_balance,2)+displayamount($commission,2);
						updateTable('wallet',array('balance'=>$profit_wallet_balance),array('wallet_id'=>$profit_wallet_id));
						wallet_balance_check($profit_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$seller_new_balance=displayamount($seller_new_balance,2)-displayamount($commission,2);
						updateTable('wallet',array('balance'=>$seller_new_balance),array('wallet_id'=>$seller_wallet_id));
						wallet_balance_check($seller_wallet_id,array('transaction_id'=>$wallet_transaction_id));
					}
					$commission_referral=0;
					$all_commission_referral=$this->db->select('referral_id,referrer_id,comission,proposal_id')->where('order_id',$revenues->order_id)->where('status',0)->from('proposals_referrals')->get()->row();
					if($all_commission_referral){
						$commission_referral=$all_commission_referral->comission;
						$referrer_id=$all_commission_referral->referrer_id;
						$referral_details=getMemberDetails($referrer_id,array('main'=>1));
						$referral_wallet_id=$referral_details['member']->wallet_id;
						$referral_wallet_balance=$referral_details['member']->balance;
						
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_type_id=get_option_value('REFERRAL_COMMISSION');
						$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'debit'=>$commission_referral,'description_tkey'=>'Referral','relational_data'=>$all_commission_referral->proposal_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$seller_details['member']->member_name.' wallet',
								'TW'=>$referral_details->member_name.' wallet',
								'TP'=>'Referral',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$referral_wallet_id,'credit'=>$commission_referral,'description_tkey'=>'Referral','relational_data'=>$all_commission_referral->proposal_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$seller_details['member']->member_name.' wallet',
								'TW'=>$referral_details->member_name.' wallet',
								'TP'=>'Referral',
								));
							insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$referral_new_balance=displayamount($referral_wallet_balance,2)+displayamount($commission_referral,2);
							updateTable('wallet',array('balance'=>$referral_new_balance),array('wallet_id'=>$referral_wallet_id));
							wallet_balance_check($referral_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
							
							$seller_new_balance=displayamount($seller_new_balance,2)-displayamount($commission_referral,2);
							updateTable('wallet',array('balance'=>$seller_new_balance),array('wallet_id'=>$seller_wallet_id));
							wallet_balance_check($seller_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
						}
						updateTable('proposals_referrals',array('status'=>1),array('referral_id'=>$all_commission_referral->referral_id));
					}
					updateTable('revenues',array('status'=>1),array('revenue_id'=>$revenues->revenue_id));
				}
			}
		}
		header("HTTP/1.1 200 OK");
	}

	public function cron_pending_emails(){
		file_put_contents(ABS_USERUPLOAD_PATH.'cron/pending_emails.log', 'Execute last '.date('Y-m-d H:i:s'));
		SendMailCron();
		header("HTTP/1.1 200 OK");
	}
}
