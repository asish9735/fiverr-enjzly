<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller {

	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$is_ajax=$this->input->is_ajax_request();
		$this->access_member_type='';
		if($this->loggedUser){
			$this->member_id=$this->loggedUser['MID'];	
			$this->organization_id=$this->loggedUser['OID'];
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->profile_connection_id=$this->loggedUser['LAST_PCI'];
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			if ($is_ajax) {}else{
			$this->Autorun_model->updateSellerLevels();
			}
		}else{
			redirect(get_link('loginURL'));
		}
		parent::__construct();
	}
	public function index()
	{
		
		$data=array();
		if($this->loggedUser){
			$data['loggedUser']=$this->loggedUser;
			$arr=array(
				'select'=>'m.member_name,m.member_register_date,m.seller_rating,c.country_name,m.recent_delivery_date,w.balance,w.month_earnings',
				'table'=>'member as m',
				'join'=>array(
					array('table'=>'member_address as a','on'=>'m.member_id=a.member_id','position'=>'left'),
					array('table'=>'country_names as c','on'=>"`a`.`member_country`=`c`.`country_code` and `c`.`country_lang`='".getSetlang()."'",'position'=>'left'),
					array('table'=>'wallet as w','on'=>"m.member_id=w.user_id",'position'=>'left'),
				),
				'where'=>array('m.member_id'=>$this->member_id),
				'single_row'=>true,
			);
			$data['member_details']=getData($arr);
			
			$arr=array(
				'select'=>'n.notification_id,n.sender_id,n.notification_date,n.notification_url,n.is_read,m.member_name,nt_n.template_content',
				'table'=>'notifications as n',
				'join'=>array(
					array('table'=>'member as m','on'=>'n.sender_id=m.member_id','position'=>'left'),
					array('table'=>'notifications_template as nt','on'=>"n.notification_template=nt.template_key",'position'=>'left'),
					array('table'=>'notifications_template_names as nt_n','on'=>"`nt`.`notification_template_id`=`nt_n`.`notification_template_id` and `nt_n`.`lang`='".getSetlang()."'",'position'=>'left'),
				),
				'where'=>array('n.receiver_id'=>$this->member_id,'n.is_deleted <>'=>1),
				'limit'=>'5',
				'order'=>array(array('notification_id','desc'))
			);
			$data['notifications']=getData($arr);
			$data['notification_count']=$this->db->where('receiver_id',$this->member_id)->where('is_deleted <>',1)->from('notifications')->count_all_results();
			$data['unread_notification']=$this->db->where('receiver_id',$this->member_id)->where('is_read',0)->from('notifications')->count_all_results();
			
		
		$wh1=" (c.sender_id='".$this->member_id."' or c.receiver_id='".$this->member_id."')";
		$this->db->select('c.conversations_id,c.sender_id,c.receiver_id,c.last_message_id,c_m.message,c_m.sending_date,c_m.is_read,c_m.offer_id,c_m.sender_id as message_sender,m.member_name');
		$this->db->from('conversations as c');
		$this->db->join('conversations_message as c_m','c.last_message_id=c_m.message_id','left');
		$this->db->join('member as m','c_m.sender_id=m.member_id','left');
		$this->db->where($wh1);
		$this->db->where('c.status',1);
		$data['messages']=$this->db->limit(4)->order_by('c.last_message_id','desc')->order_by('c.conversations_id','desc')->group_by('c.conversations_id')->get()->result();
		
		$data['message_count']=$this->db->where($wh1)->where('c.status',1)->from('conversations as c')->count_all_results();
			
			
			$data['load_js']=load_js(array('chart.min.js'));
			
			$data['order_complete']=$this->db->where('seller_id',$this->member_id)->where('order_status',ORDER_COMPLETED)->from('orders')->count_all_results();
			$data['order_delivered']=$this->db->where('seller_id',$this->member_id)->where('order_status',ORDER_DELIVERED)->from('orders')->count_all_results();
			$data['order_cancelled']=$this->db->where('seller_id',$this->member_id)->where('order_status',ORDER_CANCELLED)->from('orders')->count_all_results();
			$data['order_queue']=$this->db->where('seller_id',$this->member_id)->where('order_active',1)->from('orders')->count_all_results();
			$data['open_purchase']=$this->db->where('buyer_id',$this->member_id)->where('order_active',1)->from('orders')->count_all_results();
			
			$data['active_gigs']=$this->db->where('proposal_seller_id',$this->member_id)->where('proposal_status',PROPOSAL_ACTIVE)->from('proposals')->count_all_results();
			$data['active_request']=$this->db->where('seller_id',$this->member_id)->where('request_status',REQUEST_ACTIVE)->from('buyer_requests')->count_all_results();


			loadModel('orders/order_model');
			loadModel('dashboard_model');
			//$data['all_buyer']=$this->order_model->getContacts(array('seller_id'=>$this->member_id));
			//$data['all_seller']=$this->order_model->getContacts(array('buyer_id'=>$this->member_id));
			
			$data['orders_as_seller']=$this->order_model->getOrders(array('seller_id'=>$this->member_id,'limit'=>5));
			$data['orders_as_buyer']=$this->order_model->getOrders(array('buyer_id'=>$this->member_id,'limit'=>5));
			
			$data['orderStatusChart']=$this->dashboard_model->getOdderChartSattus($this->member_id);
			$data['orderGraph']['seller']=array();
			$data['orderGraph']['buyer']=array();
			for($i=5;$i>=0;$i--){
				$date=date('Y-m',strtotime('- '.$i.'month'));
				$year=date('Y',strtotime($date));
				$month=date('m',strtotime($date));
				$data['orderGraph']['month'][]=date('F',strtotime($date));
				$data['orderGraph']['seller'][]=$this->db->where('YEAR(order_date)',$year)->where('MONTH(order_date)',$month)->where('seller_id',$this->member_id)->where('order_status <>',0)->from('orders')->count_all_results();
				$data['orderGraph']['buyer'][]=$this->db->where('YEAR(order_date)',$year)->where('MONTH(order_date)',$month)->where('buyer_id',$this->member_id)->where('order_status <>',0)->from('orders')->count_all_results();
			
			}

			$templateLayout=array('view'=>'dashboard','type'=>'default','buffer'=>FALSE,'theme'=>'');
			load_template($templateLayout,$data);
		}
	}
	public function transaction_history(){
		$data['member_details']=getMemberDetails($this->member_id,array('main'=>1));
		loadModel('dashboard_model');
		$this->load->library('pagination');
		$config['base_url'] = VPATH.'dashboard/transaction_history/';
		$config['total_rows'] =$this->dashboard_model->getTransaction(array('wallet_id'=>$data['member_details']['member']->wallet_id),'','',TRUE);	;
		$config['per_page'] = 10; 
		$config['page_query_string'] = TRUE;
		$config['use_page_numbers'] = TRUE;  
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='page-item active'><a href='javascript:void(0)' class='page-link'>";
        $config['cur_tag_close'] = '</a></li>';
        $config['last_tag_open'] = "<li class='last'>";
        $config['last_tag_close'] = '</li>';
        /*$config['next_link'] = '<i class="zmdi zmdi-chevron-right"></i>';
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = '</li>';*/
       /* $config['prev_link'] = '<i class="zmdi zmdi-chevron-left"></i>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';*/                 
        $config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config); 
		$limit_from=$this->input->get('per_page');
		$page = ($limit_from) ? $limit_from : 0;
        $per_page = $config["per_page"];
        $start = 0;
        if ($page > 0) {
            for ($i = 1; $i < $page; $i++) {
                $start = $start + $per_page;
            }
        }
		$data['transaction_list']=$this->dashboard_model->getTransaction(array('wallet_id'=>$data['member_details']['member']->wallet_id),$config['per_page'],$start);	
        $data['links']=$this->pagination->create_links();
		$templateLayout=array('view'=>'transaction-history','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
}
