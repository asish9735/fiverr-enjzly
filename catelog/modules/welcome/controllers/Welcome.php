<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller {

	function __construct()
	{
			parent::__construct();
	}
	public function index()
	{
		$data=array();
		$templateLayout=array('view'=>'welcome_message','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function clean_table(){
		
		/*
		$this->db->truncate('reports');
		$this->db->truncate('cart');
		$this->db->truncate('revenues');
		// Order data
		$this->db->truncate('orders_transaction');
		$this->db->truncate('orders_extras');
		$this->db->truncate('orders_conversations');
		$this->db->truncate('orders');
		
		// Wallet data
		$this->db->truncate('wallet_transaction_row');
		$this->db->truncate('wallet_transaction');
		$this->db->truncate('wallet');
		
		// conversation data
		$this->db->truncate('conversations_messages_offers');
		$this->db->truncate('conversations_message');
		$this->db->truncate('conversations');
		
		$this->db->truncate('send_offers');
		// request data
		$this->db->truncate('request_files');
		$this->db->truncate('request_category');
		$this->db->truncate('buyer_requests');
		
		
		// proposals reviews
		$this->db->truncate('buyer_reviews');
		$this->db->truncate('seller_reviews');
		
		$this->db->truncate('favorites');
		// proposals data
		$this->db->truncate('proposal_tags');
		$this->db->truncate('proposal_stat');
		$this->db->truncate('proposal_settings');
		$this->db->truncate('proposal_package_attributes');
		$this->db->truncate('proposal_packages');
		$this->db->truncate('proposal_files');
		$this->db->truncate('proposal_extras');
		$this->db->truncate('proposal_category');
		$this->db->truncate('proposal_additional');
		$this->db->truncate('proposals_referrals');
		$this->db->truncate('proposals_last_views');
		$this->db->truncate('proposals');
		
		
		$this->db->truncate('files');
		$this->db->truncate('admin_notifications');
		$this->db->truncate('coupons');
		$this->db->truncate('notifications');
		$this->db->truncate('online_transaction_data');
		$this->db->truncate('online_user');


		
		
		
		
		// organization data
		$this->db->truncate('organization_logo');
		$this->db->truncate('organization_address');
		$this->db->truncate('organization_right');
		$this->db->truncate('organization');	
			
		// member data
		$this->db->truncate('member_stats');
		$this->db->truncate('member_skills');
		$this->db->truncate('member_payment_settings');
		$this->db->truncate('member_languages');
		$this->db->truncate('member_address');
		$this->db->truncate('member_logo');
		$this->db->truncate('member_basic');
		$this->db->truncate('member');
		
		// profile connection data
		$this->db->truncate('profile_verify_token');
		$this->db->truncate('profile_connection');
		$this->db->truncate('access_panel');
		
		// global data
		
		// Update Setting
		$wallet=array('user_id'=>0,'title'=>'');
		$wallet['title']='Main wallet';
		$wallet_id=insertTable('wallet',$wallet,TRUE);
		updateTable('settings',array('setting_value'=>$wallet_id,'editable'=>0),array('setting_key'=>'SITE_MAIN_WALLET'));
		
		$wallet['title']='Profit';
		$wallet_id=insertTable('wallet',$wallet,TRUE);
		updateTable('settings',array('setting_value'=>$wallet_id,'editable'=>0),array('setting_key'=>'SITE_PROFIT_WALLET'));
		
		$wallet['title']='Processing Fee Wallet';
		$wallet_id=insertTable('wallet',$wallet,TRUE);
		updateTable('settings',array('setting_value'=>$wallet_id,'editable'=>0),array('setting_key'=>'PROCESSING_FEE_WALLET'));
		
		$wallet['title']='Withdrawal Wallet';
		$wallet_id=insertTable('wallet',$wallet,TRUE);
		updateTable('settings',array('setting_value'=>$wallet_id,'editable'=>0),array('setting_key'=>'WITHDRAW_WALLET'));
		
		$wallet['title']='Paypal Wallet';
		$wallet_id=insertTable('wallet',$wallet,TRUE);
		updateTable('settings',array('setting_value'=>$wallet_id,'editable'=>0),array('setting_key'=>'PAYPAL_WALLET'));
		
		$wallet['title']='Telr Wallet';
		$wallet_id=insertTable('wallet',$wallet,TRUE);
		updateTable('settings',array('setting_value'=>$wallet_id,'editable'=>0),array('setting_key'=>'TELR_WALLET'));
		
		$wallet['title']='Ngenius Wallet';
		$wallet_id=insertTable('wallet',$wallet,TRUE);
		updateTable('settings',array('setting_value'=>$wallet_id,'editable'=>0),array('setting_key'=>'NGENIUS_WALLET'));
		*/
		echo 'DONE';
	}
	public function clean_files(){
		/*$this->load->helper("file");
		$path=ABS_USERUPLOAD_PATH;
		delete_files($path.'ECnote', true);
		delete_files($path.'conversation-files', true);
		delete_files($path.'member_banner', true);
		delete_files($path.'member_logo', true);
		delete_files($path.'message-files', true);
		delete_files($path.'organization_logo', true);
		delete_files($path.'proposal-files', true);
		delete_files($path.'proposal-video', true);
		delete_files($path.'request-files', true);
		delete_files($path.'tempfile', true);*/
		echo 'DONE';
	}
	public function downloadtempfile($file=''){
		header("Content-type: image/gif");
		if($file && file_exists(ABS_USERUPLOAD_PATH."tempfile/".$file)){
			$file_lnk=ABS_USERUPLOAD_PATH."tempfile/".$file;
		}else{
			$file_lnk=APATH."themes/".$theme.'/'.IMAGE."default/noimage.jpg";
		}
		$imageData = file_get_contents($file_lnk);
		echo $imageData;
	}
}
