<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function orderComplete($data=array()){
		$order_id=$data['order_id'];
		$orderdetails=$data['order_details'];
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
			return $revenue_id;
		}
	}
	public function getOrders($where=array()){
		$arr=array(
				'select'=>'o.order_id,o.order_date,o.order_time,o.order_active,o.order_status,p.proposal_id,p.proposal_title,p.proposal_image,o.order_duration,o.order_price,o.payment_method,o.transaction_id',
				'table'=>'orders o',
				'join'=>array(array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','position'=>'left'),array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left')),
				'where'=>array('p.proposal_status <>'=>0,'o.order_status <>'=>0),
				'order'=>array(array('o.order_id','desc')),
				);
		if($where && array_key_exists('buyer_id',$where)){
			$arr['where']['o.buyer_id']=$where['buyer_id'];
		}
		if($where && array_key_exists('seller_id',$where)){
			$arr['where']['o.seller_id']=$where['seller_id'];
		}
		if($where && array_key_exists('limit',$where)){
			$arr['limit']=$where['limit'];
		}
		$data=getData($arr);
		return $data;
	}
	public function getPurchases($where=array()){
		$transaction_type=array('order_payment_wallet','order_payment_paypal','order_payment_stripe','order_payment_payza','order_payment_bitcoin','order_payment_mobile_money','order_payment_refund','order_payment_telr','order_payment_ngenius','order_payment_bank');
		$this->db->select('w.*, wr.wallet_id,(sum(wr.credit) - sum(wr.debit)) as Amount , wt.title_tkey as name,ot.order_id')
				->from('wallet_transaction w')
				->join('wallet_transaction_type wt', 'wt.wallet_transaction_type_id = w.wallet_transaction_type_id', 'INNER')
				->join('wallet_transaction_row wr', 'wr.wallet_transaction_id = w.wallet_transaction_id', 'LEFT')
				->join('orders_transaction ot', 'w.wallet_transaction_id = ot.transaction_id', 'LEFT');
		$this->db->where('wr.wallet_id', $where['wallet_id']);		
		$this->db->where('w.status', 1);		
		$this->db->where('wr.description_tkey <>', 'Online_payment_from');		
		$this->db->where_in('wt.title_tkey', $transaction_type);		
		$result = $this->db->group_by('wr.wallet_transaction_id,wr.wallet_id')->order_by('w.transaction_date', 'DESC')->order_by('w.wallet_transaction_id', 'DESC')->get()->result();		
		return $result;
	}
	public function getContacts($where=array()){
		$arr=array(
				'select'=>'o.order_id,m.member_id,m.member_name,max(o.order_date) as last_order_date ,sum(o.order_price) as total_amount,count(o.order_id) as total_order',
				'table'=>'orders o',
				'where'=>array('o.order_status <>'=>ORDER_CANCELLED),
				'order'=>array(array('o.order_id','asc')),
				
				);
		if($where && array_key_exists('buyer_id',$where)){
			$arr['where']['o.buyer_id']=$where['buyer_id'];
			$arr['join'][]=array('table'=>'member as m','on'=>'o.seller_id=m.member_id','position'=>'left');
			$arr['group']='o.seller_id';
		}
		if($where && array_key_exists('seller_id',$where)){
			$arr['where']['o.seller_id']=$where['seller_id'];
			$arr['join'][]=array('table'=>'member as m','on'=>'o.buyer_id=m.member_id','position'=>'left');
			$arr['group']='o.buyer_id';
		}
		
		$data=getData($arr);
		return $data;
	}
	public function orderCancelled($data=array()){
		$order_id=$data['order_id'];
		$orderdetails=$data['order_details'];
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
				
				
			}
		}
	}
}
