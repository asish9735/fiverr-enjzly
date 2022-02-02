<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autorun_model extends MX_Controller {

	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$is_ajax=$this->input->is_ajax_request();
		if($this->loggedUser){
			$this->member_id=$this->loggedUser['MID'];	
			$this->organization_id=$this->loggedUser['OID'];
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->profile_connection_id=$this->loggedUser['LAST_PCI'];
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			//$this->updateSellerLevels();
			if ($is_ajax) {}else{
				$this->updateRevenue();
			}
			
		}
		if ($is_ajax) {}else{
		$this->updateProposalFeature();
		}
		if($this->session->userdata('current_lang')){
			$deflang=$this->session->userdata('current_lang');
			if($deflang=='ar'){
				define('LOGO_NAME',			  	"logo-ar.png");
			}
		}
		define('CURRENCY',CurrencySymbol());
			parent::__construct();
	}
	public function updateSellerLevels(){
		if($this->loggedUser){
		$memdetails=getMemberDetails($this->member_id);
		if($memdetails){
			$seller_level=$memdetails['member']->seller_level;
			$seller_rating=$memdetails['member']->seller_rating;
			$level_one_rating = get_option_value('level_one_rating');
			$level_one_orders = get_option_value('level_one_orders');
			$level_two_rating = get_option_value('level_two_rating');
			$level_two_orders = get_option_value('level_two_orders');
			$level_top_rating = get_option_value('level_top_rating');
			$level_top_orders = get_option_value('level_top_orders');
			
			//setFMessage('level_up',3);
			$count_orders =$this->db->where('seller_id',$this->member_id)->where('order_status',ORDER_CANCELLED)->from('orders')->count_all_results();
			if($seller_level == 1 ){
				if($seller_rating >= $level_one_rating && $count_orders >= $level_one_orders){
					updateTable('member',array('seller_level'=>2),array('member_id'=>$this->member_id));
					setFMessage('level_up',1);
				}
			}elseif($seller_level == 2){
				if($seller_rating >= $level_two_rating && $count_orders >= $level_two_orders){	
					updateTable('member',array('seller_level'=>3),array('member_id'=>$this->member_id));
					setFMessage('level_up',2);
				}
			}elseif($seller_level == 3){
				 if($seller_rating >= $level_top_rating && $count_orders >= $level_top_orders){	
					updateTable('member',array('seller_level'=>4),array('member_id'=>$this->member_id));
					setFMessage('level_up',3);
				}
			}
		}
		}
	}
	public function updateRevenue(){
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
	}
	public function updateProposalFeature(){
		updateTable('proposal_settings',array('proposal_featured'=>0,'featured_end_date'=>NULL),array('proposal_featured'=>1,'featured_end_date <'=>date('Y-m-d H:i:s')));
	}
}
