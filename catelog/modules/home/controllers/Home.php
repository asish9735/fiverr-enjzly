<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller {

	function __construct()
	{	$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->member_id=$this->loggedUser['MID'];	
			$this->organization_id=$this->loggedUser['OID'];	
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->profile_connection_id=$this->loggedUser['LAST_PCI'];
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
		}
		parent::__construct();
	}
	public function index()
	{
		$data=array();
		if($this->loggedUser){
			$data['profile_data']=getMemberDetails($this->member_id);
			$data['recent_buy']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_set.proposal_featured,p_set.featured_end_date,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'orders o',
				'join'=>array(
				array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','position'=>'left'),
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'o.buyer_id'=>$this->member_id,'o.order_status'=>ORDER_COMPLETED),
				'order'=>array(array('o.order_id','desc')),
				'limit'=>'5'
				));
			$data['recent_proposals']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_set.proposal_featured,p_set.featured_end_date,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
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
			
			
			
			
			$data['slider']=getData(array(
					'select'=>'s_n.name,s_n.description,s.slide_image,s.slide_id,s.slide_image,s.slide_url',
					'table'=>'slider as s',
					'join'=>array(array('table'=>'slider_names as s_n','on'=>"s.slide_id=s_n.slide_id and s_n.lang='".getSetlang()."'",'position'=>'left')),
					'where'=>array('s.status'=>1),
					'order'=>array(array('s.display_order','asc'))
				));
			$data['loggedUser']=$this->loggedUser;
			$data['featured_proposal']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_s.proposal_views,p_set.proposal_featured,p_set.featured_end_date,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'proposals p',
				'join'=>array(
				array('table'=>'proposal_stat as p_s','on'=>'p.proposal_id=p_s.proposal_id','position'=>'left'),
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'p_set.proposal_featured'=>1,'p_set.featured_end_date >='=>date('Y-m-d H:i:s')),
				'order'=>array(array('rand()',NULL)),
				'limit'=>'6'
				));
			/*$data['top_proposal']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_s.proposal_views,p_set.proposal_featured,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'proposals p',
				'join'=>array(
				array('table'=>'proposal_stat as p_s','on'=>'p.proposal_id=p_s.proposal_id','position'=>'left'),
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'m.seller_level'=>4),
				'order'=>array(array('rand()',NULL)),
				'limit'=>'6'
				));*/
			
			$data['random_proposal']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_s.proposal_views,p_set.proposal_featured,p_set.featured_end_date,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'proposals p',
				'join'=>array(
				array('table'=>'proposal_stat as p_s','on'=>'p.proposal_id=p_s.proposal_id','position'=>'left'),
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE),
				'order'=>array(array('rand()',NULL)),
				'limit'=>'6'
				));
			$data['recent_request']=getData(array('select'=>'r.request_id,r.request_title,r.request_description,r.seller_id,r.delivery_time,r.request_budget',
				'table'=>'buyer_requests r',
				'join'=>array(
				array('table'=>'request_category as r_c','on'=>'r.request_id=r_c.request_id','position'=>'left'),
				array('table'=>'proposal_category as p_c','on'=>'r_c.category_subchild_id=p_c.category_subchild_id','position'=>'left'),
				array('table'=>'proposals as p','on'=>'p_c.proposal_id=p.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'r.seller_id=m.member_id','position'=>'left'),
				array('table'=>'send_offers as s_o','on'=>"r.request_id=s_o.request_id and s_o.sender_id='".$this->member_id."'",'position'=>'left'),
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'r.request_status'=>REQUEST_ACTIVE,'r.seller_id <>'=>$this->member_id),
				/*'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'r.request_status'=>REQUEST_ACTIVE,'s_o.offer_id'=>NULL),*/
				'order'=>array(array('r.request_id','desc')),
				'group'=>'r.request_id',
				'limit'=>'6'
				));;	
				$data['login_seller_offers']=$this->db->where('sender_id',$this->member_id)->where('date(reg_date)',date('Y-m-d'))->from('send_offers')->count_all_results();
				$data['seller_proposal']=$this->db->where('proposal_seller_id',$this->member_id)->where('proposal_status',PROPOSAL_ACTIVE)->from('proposals')->count_all_results();
				$data['load_js']=load_js(array('mycustom.js'));
			$templateLayout=array('view'=>'home-user','type'=>'default','buffer'=>FALSE,'theme'=>'');
		}else{
		
					$data['load_js']=load_js(array('owl.carousel.min.js'));
					$data['load_css']=load_css(array('owl.carousel.css','owl.theme.default.css'));
					$data['slider']=getData(array(
					'select'=>'s.slide_name,s.slide_image',
					'table'=>'home_section_slider as s',
					'where'=>array('s.status'=>1),
				));
		$data['cards']=getData(array(
					'select'=>'h.card_title,h.card_desc,h.card_link,h.card_image',
					'table'=>'home_cards as h',
					'where'=>array('h.status'=>1,'h.lang'=>getSetlang()),
				));
		$data['featured_category']=getData(array(
					'select'=>'c.category_id,c.category_key,c.category_image,c_n.name,c_n.info',
					'table'=>'category as c',
					'join'=>array(array('table'=>'category_names as c_n','on'=>"c.category_id=c_n.category_id and c_n.lang='".getSetlang()."'",'position'=>'left')),
					'where'=>array('c.status'=>1,'c.is_featured'=>1),
					'limit'=>'8',
					'order'=>array(array('c.display_order','asc'))
				));
		$data['boxes']=getData(array(
					'select'=>'b.box_id,b.box_image,b_n.name,b_n.description',
					'table'=>'section_boxes as b',
					'join'=>array(array('table'=>'section_boxes_names as b_n','on'=>"b.box_id=b_n.box_id and b_n.lang='".getSetlang()."'",'position'=>'left')),
					'where'=>array('b.status'=>1),
					'limit'=>'8',
					'order'=>array(array('b.display_order','asc'))
				));
		$data['featured_proposal']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_s.proposal_views,p_set.proposal_featured,p_set.featured_end_date,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'proposals p',
				'join'=>array(
				array('table'=>'proposal_stat as p_s','on'=>'p.proposal_id=p_s.proposal_id','position'=>'left'),
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'p_set.proposal_featured'=>1,'p_set.featured_end_date >='=>date('Y-m-d H:i:s')),
				'order'=>array(array('rand()',NULL)),
				'limit'=>'15'
				));
				
			$popular_member=getData(array(
					'select'=>'m.member_id,m.member_name,m_a.member_country,m.seller_rating,m_b.member_heading',
					'table'=>'member as m',
					'join'=>array(
						array('table'=>'member_address as m_a','on'=>"m.member_id=m_a.member_id",'position'=>'left'),
						array('table'=>'member_basic as m_b','on'=>"m.member_id=m_b.member_id",'position'=>'left'),
					),
					'where'=>array('m.is_inactive <>'=>1,'m.is_admin_verified'=>1),
					'limit'=>'4',
					'order'=>array(array('m.seller_rating','desc'))
				));	
			if($popular_member){
				foreach($popular_member as $k=>$row){
					$rating=$this->db->select('AVG(buyer_rating) as avg_rating,count(review_id) as total_review')->where('review_seller_id',$row->member_id)->from('buyer_reviews')->get()->row();
					$avg_rating=0;
					if($rating){
					 	$avg_rating=$rating->avg_rating;
					}
					$row->avg_rating=$avg_rating;

					$row->skills=getData(array(
						'select'=>'s.skill_id,s_n.skill_title,m_s.skill_level',
						'table'=>'member_skills as m_s',
						'join'=>array(
							array('table'=>'skills as s','on'=>'m_s.skill_id=s.skill_id','position'=>'left'),
							array('table'=>'skills_names as s_n','on'=>'s.skill_id=s_n.skill_id','position'=>'left'),
							
						),
						'where'=>array('m_s.member_id'=>$row->member_id,'s_n.lang'=>getSetlang()),
					));	
					$popular_member[$k]=$row;
				}
			}
			$data['popular_member']=$popular_member;
			
		$templateLayout=array('view'=>'home','type'=>'default','buffer'=>FALSE,'theme'=>'');
			
		}
		load_template($templateLayout,$data);
	}
	public function getdata(){
		checkrequestajax();
		$data=array();
		$form_type=get('formtype');
		if($form_type=='getsubcat'){
			$dataid=get('Okey');
			$all_category_subchild=array();
			if($dataid){
				$all_category_subchild=getAllSubCategory($dataid);
			}
			$data['all_category_subchild']=$all_category_subchild;
			echo json_encode($data['all_category_subchild']);
		}
	}
	public function profileview($username=''){
		$memberL=getData(array(
				'select'=>'p_c.member_id',
				'table'=>'profile_connection as p_c',
				'join'=>array(array('table'=>'access_panel as a','on'=>'p_c.access_user_id=a.access_user_id','position'=>'left')),
				'where'=>array('a.access_username'=>$username,'p_c.organization_id'=>NULL),
				'single_row'=>true,
			)
		);
		if($memberL){
			$data['username']=$username;
			$member_id=$memberL->member_id;
			$is_editable=0;
			$is_login=0;
			$data['member_details']=getMemberDetails($member_id);
			if($this->loggedUser){
				$is_login=1;
			}
			if($this->loggedUser && $member_id==$this->member_id){
				$is_editable=1;
			}
			$data['is_login']=$is_login;
			$data['is_editable']=$is_editable;
			$rating=$this->db->select('AVG(buyer_rating) as rating,count(review_id) as total_review')->where('review_seller_id',$member_id)->from('buyer_reviews')->get()->row();
			if($rating){
				$data['member_details']['member']->avg_rating=$rating->rating;
				$data['member_details']['member']->total_review=$rating->total_review;
			}else{
				$data['member_details']['member']->avg_rating=0;
				$data['member_details']['member']->total_review=0;
			}
			$data['member_details']['member_address']->member_country_name='';
			$data['member_details']['member_address']->country_flag='';
			if($data['member_details']['member_address'] && $data['member_details']['member_address']->member_country){
				$country=getAllCountry(array('country_code'=>$data['member_details']['member_address']->member_country));
				if($country){
					$data['member_details']['member_address']->member_country_name=$country->country_name;
					$data['member_details']['member_address']->country_flag=strtolower($country->country_code_short);
				}
			}
			$data['proposals']=getData(array('select'=>'p.proposal_id,p.proposal_seller_id,p.proposal_title,p.proposal_price,p.display_price,p.proposal_url,p.proposal_image,p_s.proposal_views,p_set.proposal_featured,p_set.featured_end_date,m.member_name,m.seller_level,p_set.proposal_enable_referrals',
				'table'=>'proposals p',
				'join'=>array(
				array('table'=>'proposal_stat as p_s','on'=>'p.proposal_id=p_s.proposal_id','position'=>'left'),
				array('table'=>'proposal_settings as p_set','on'=>'p.proposal_id=p_set.proposal_id','position'=>'left'),
				array('table'=>'member as m','on'=>'p.proposal_seller_id=m.member_id','position'=>'left')
				),
				'where'=>array('p.proposal_status'=>PROPOSAL_ACTIVE,'p.proposal_seller_id'=>$member_id),
				'order'=>array(array('p.proposal_id','desc')),
				));
			$data['all_languages']=getAlllanguages();
			$data['all_skills']=getAllSkills();
			$data['languages_level']=getLanguageLevelName();
			$data['skills_level']=getSkillsLevelName();
			$arr=array(
					'select'=>'b.buyer_rating,b.buyer_review,b.order_id,b.review_date,m.member_name as buyer_name',
					'table'=>'buyer_reviews b',
					'join'=>array(array('table'=>'member as m','on'=>'b.review_buyer_id=m.member_id','position'=>'left')),
					'where'=>array('b.review_seller_id'=>$member_id),
					'order'=>array(array('b.review_date','asc')),
				);
		$buyer_reviews=getData($arr);
		$data['buyer_reviews']=$buyer_reviews;
		}else{
			redirect(get_link('homeURL'));
		}
		$templateLayout=array('view'=>'profile-details','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function setlang(){
		$langS=post('lang');
		$alllanguage=explode(',',get_option_value('language'));
		if(in_array($langS,$alllanguage)){
		$this->session->set_userdata('current_lang',$langS);
		}
		
	}
}
