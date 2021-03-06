<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MX_Controller {

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
			
		}else{
			redirect(get_link('loginURL'));
		}
		loadModel('cart_model');
		loadModel('notifications/notification_model');
			parent::__construct();
	}
	public function index(){
		$data=array();
		$data['load_js']=load_js(array('mycustom.js'));
		
		$arr=array(
			'select'=>'c.proposal_id,c.qty,p.proposal_title,p.proposal_image,p.proposal_price,pp.price,pp.package_name,c.extra,c.package_id,p.proposal_url,p.proposal_seller_id',
			'table'=>'cart as c',
			'join'=>array(
				array('table'=>'proposals as p','on'=>'c.proposal_id=p.proposal_id','position'=>'left'),
				array('table'=>'proposal_packages as pp','on'=>'c.package_id=pp.package_id','position'=>'left'),
			),
			'where'=>array('c.member_id'=>$this->member_id),
		);
		$data['cart']=getData($arr);
		if($data['cart']){
			foreach($data['cart'] as $k=>$carti){
				if($carti->extra){
					$extra=json_decode($carti->extra);
					$data['cart'][$k]->extra=$this->db->where_in('id',$extra)->where('proposal_id',$carti->proposal_id)->from('proposal_extras')->get()->result();
				}
			}
		}
		if($this->input->get('option')){
			$data['member_details']=getMemberDetails($this->member_id,array('main'=>1));
			$templateLayout=array('view'=>'cart-payment-option','type'=>'default','buffer'=>FALSE,'theme'=>'');
		}else{
			$templateLayout=array('view'=>'cart','type'=>'default','buffer'=>FALSE,'theme'=>'');
		}		
		
		load_template($templateLayout,$data);
	}
	public function payment_option(){
		
	}
	public function checkout()
	{
		$data=array();
		
		
		$sub_total=$proposal_price=$extra_price=0;
		$CheckOutData=$this->session->userdata('CheckOutData');
		$data['CheckOutData']=$CheckOutData;
		$data['loggedUser']=$this->loggedUser;
		if($CheckOutData && $CheckOutData['proposal_id']){
			if($this->session->userdata('referred-'.$CheckOutData['proposal_id'])){
				$data['referred']=$this->session->userdata('referred-'.$CheckOutData['proposal_id']);
				$data['referred']['username']=getUserName($data['referred']['refer_id']);
			}
			$data['proposal_details']=getProposalDetails($CheckOutData['proposal_id'],array('proposal'));
			if($CheckOutData['package_id']){
				$arr=array(
					'select'=>'p.package_id,p.package_name,p.price,p.description,p.revisions,p.delivery_time',
					'table'=>'proposal_packages as p',
					'where'=>array('p.proposal_id'=>$CheckOutData['proposal_id'],'p.package_id'=>$CheckOutData['package_id']),
					'single_row'=>TRUE
				);
				$proposal_packages=getData($arr);
				if($proposal_packages){
					$proposal_price=$proposal_packages->price;
				}else{
					$proposal_price=$data['proposal_details']['proposal']->display_price;
				}
				
			}else{
				$proposal_price=$data['proposal_details']['proposal']->proposal_price;
			}
			if($CheckOutData['coupon']){
				$arr=array(
							'select'=>'c.coupon_id,c.coupon_type,c.coupon_price,c.coupon_limit,c.coupon_used',
							'table'=>'coupons as c',
							'where'=>array('c.proposal_id'=>$CheckOutData['proposal_id'],'c.coupon_code'=>$CheckOutData['coupon']),
							'single_row'=>TRUE
						);
				$coupons=getData($arr);
				if($coupons){
					if($coupons->coupon_type==''){
						$proposal_price = $coupons->coupon_price;
					}else{
						$numberToAdd = ($proposal_price / 100) * $coupons->coupon_price;
						$proposal_price = $proposal_price - $numberToAdd;
					}
				}
			}
			$sub_total+= $proposal_price*$CheckOutData['qty'];
			if($CheckOutData['extra']){
				foreach($CheckOutData['extra'] as $e=>$extra){
					$arr=array(
							'select'=>'p.id,p.name,p.price',
							'table'=>'proposal_extras as p',
							'where'=>array('p.proposal_id'=>$CheckOutData['proposal_id'],'p.id'=>$extra),
							'single_row'=>TRUE
						);
					$proposal_extras=getData($arr);
					if($proposal_extras){
						$extra_price+=$proposal_extras->price;
					}
				}
			}
			$sub_total+=$extra_price;
			
		}else{
			redirect(get_link('homeURL'));
		}
		$data['CheckOutData']['extra_price']=$extra_price;
		$data['CheckOutData']['proposal_price']=$proposal_price;
		$data['CheckOutData']['sub_total']=$sub_total;
		$this->session->set_userdata('CheckOutData',$data['CheckOutData']);
		$data['member_details']=getMemberDetails($this->member_id,array('main'=>1));
		$data['load_js']=load_js(array('mycustom.js'));
		$templateLayout=array('view'=>'checkout','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
	public function savecheckoutCheckAjax(){
		checkrequestajax();
		$i=0;
		$msg=array();
		$CheckOutData=array();
		$proposal_id=post('proposal_id');
		$qty=post('proposal_qty');
		$ptype=post('ptype');
		
		if($proposal_id && $qty){
			$proposal_status=getFieldData('proposal_status','proposals','proposal_id',$proposal_id);
			if($proposal_status==PROPOSAL_ACTIVE){
				
			
			$CheckOutData['proposal_id']=$proposal_id;
			$CheckOutData['qty']=$qty;
			if($this->input->post('package_id')){
				$CheckOutData['package_id']=$this->input->post('package_id');
			}
			if($this->input->post('proposal_extras')){
				$CheckOutData['extra']=$this->input->post('proposal_extras');
			}
			if($ptype && $ptype=='cart'){
				$extra='';
				if($this->input->post('proposal_extras')){
					$extra=json_encode($this->input->post('proposal_extras'));
				}
				$cart=array('member_id'=>$this->member_id,'proposal_id'=>$proposal_id,'qty'=>$qty,'extra'=>$extra);
				if($this->input->post('package_id')){
					$cart['package_id']=$this->input->post('package_id');
				}
				delete('cart',array('member_id'=>$this->member_id,'proposal_id'=>$proposal_id));
				insertTable('cart',$cart);	
			}else{
				$this->session->set_userdata('CheckOutData',$CheckOutData);	
				$msg['redirect'] =get_link('checkoutURL');
			}
			
			$msg['status'] = 'OK';
		}else{
			$msg['status'] = 'FAIL';	
			$msg['notverified'] = 'Proposal not verified yet';	
			
			}	
		}
	unset($_POST);
	echo json_encode($msg);
	}
	public function cartprocessCheckAjax(){
		$all_method=array('wallet','paypal','telr');
		checkrequestajax();
		$i=0;
		$msg=array();
		$method=post('method');
		$is_cart=post('payfor');
		$processing_fee=get_option_value('processing_fee');
		$order_status=ORDER_PROCESSING;
		$buyer_details=getMemberDetails($this->member_id,array('main'=>1));
		if($is_cart=='cart'){
			if($method && in_array($method,$all_method)){
				if($method=='wallet'){
					$processing_fee=0;
					$arr=array(
						'select'=>'c.proposal_id,c.qty,p.proposal_title,p.proposal_image,p.proposal_price,pp.price,pp.package_name,c.extra,c.package_id,p.proposal_url,p.proposal_seller_id,p.delivery_time,pa.buyer_instruction',
						'table'=>'cart as c',
						'join'=>array(
							array('table'=>'proposals as p','on'=>'c.proposal_id=p.proposal_id','position'=>'left'),
							array('table'=>'proposal_packages as pp','on'=>'c.package_id=pp.package_id','position'=>'left'),
							array('table'=>'proposal_additional as pa','on'=>'c.proposal_id=pa.proposal_id','position'=>'left'),
						),
						'where'=>array('c.member_id'=>$this->member_id),
					);
					$cart=getData($arr);
					if($cart){
						$orderdata=array();
						$sub_total=0;
						$buyer_wallet_id=$buyer_details['member']->wallet_id;
						$buyer_wallet_balance=$buyer_details['member']->balance;
						$site_details=getWallet(get_option_value('SITE_MAIN_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						$recipient_relational_data=$buyer_details['member']->member_name;
						$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_WALLET');
						
						foreach($cart as $k=>$cartdata){
							$order_number=time();
							$proposal_price=0;
							if($cartdata->package_id){
								$proposal_price=$cartdata->price;
							}else{
								$proposal_price=$cartdata->proposal_price;
							}
							$extraCart=array();
							if($cartdata->extra){
								$extra=json_decode($cartdata->extra);
								$extraData=$this->db->where_in('id',$extra)->where('proposal_id',$cartdata->proposal_id)->from('proposal_extras')->get()->result();
								foreach($extraDataas as $extra){
									$extraCart[]=array(
									'name'=>$extra->name,
									'price'=>$extra->price,
									);
									$proposal_price+=$extra->price;
								}
							}
							$price_total = $proposal_price * $cartdata->qty;
							$sub_total += $price_total;
							$order_time = date("M d, Y H:i:s", strtotime(" + ".$cartdata->delivery_time." days"));
							$orderdata[]=array(
							'order_number'=>$order_number,
							'order_duration'=>$cartdata->delivery_time,
							'order_date'=>date('Y-m-d H:i:s'),
							'order_time'=>$order_time,
							/*'order_description'=>$order_description,*/
							'seller_id'=>$cartdata->proposal_seller_id,
							'buyer_id'=>$this->member_id,
							'proposal_id'=>$cartdata->proposal_id,
							'order_price'=>$price_total,
							'order_qty'=>$cartdata->qty,
							'order_fee'=>$processing_fee,
							'order_active'=>'1',
							'order_status'=>0,
							'payment_method'=>$method,
							'buyer_instruction'=>$cartdata->buyer_instruction,
							'order_extra'=>$extraCart,
							'proposal_title'=>$cartdata->proposal_title,
							);
						}
						$totalAll=$sub_total+$processing_fee;
						if($orderdata && $buyer_details && $buyer_details['member']->balance>$totalAll){
							foreach($orderdata as $order){
								$order_extra=array();
								if(!empty($order['buyer_instruction'])){
									$order_status=ORDER_PENDING;
								}
								if($order['order_extra']){
									$order_extra=$order['order_extra'];
								}
								$proposal_title=$order['proposal_title'];
								unset($order['order_extra']);
								unset($order['buyer_instruction']);
								unset($order['proposal_title']);
								$total=$order['order_price'];
								$order_id=$this->cart_model->processOrder($order);
								if($order_id){
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
										
										$buyer_wallet_balance=displayamount($buyer_wallet_balance,2)-displayamount($total,2);
										$this->db->set('used_purchases','used_purchases+'.$total,FALSE)->where('wallet_id',$buyer_wallet_id)->update('wallet',array('balance'=>$buyer_wallet_balance));
										wallet_balance_check($buyer_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
										
										$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
										$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
											'FW'=>$buyer_details['member']->member_name.' wallet',
											'TW'=>$site_details->title,
											'TP'=>'Order_Payment',
											));
										insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
										
										$reciver_wallet_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
										updateTable('wallet',array('balance'=>$reciver_wallet_balance),array('wallet_id'=>$reciver_wallet_id));
										wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
										
										updateTable('orders',array('order_status'=>$order_status,'transaction_id'=>$wallet_transaction_id),array('order_id'=>$order_id));
									}else{
										$msg['status'] = 'FAIL';
										$msg['message'] = 'Insufficient fund';
									}
									if($order_extra){
										foreach($order_extra as $oextra){
											insertTable('orders_extras',array('order_id'=>$order_id,'name'=>$oextra['name'],'price'=>$oextra['price']));
										}
									}
									
									$seller_details=getMemberDetails($order['seller_id'],array('main'=>1));
									$RECEIVER_EMAIL=$seller_details['member']->member_email;
									$url=get_link('OrderDetailsURL').$order_id;
									$template='new-order';
									$data_parse=array(
									'BUYER_NAME'=>getUserName($this->member_id),
									'SELLER_NAME'=>getUserName($order['seller_id']),
									'PROPOSAL_TITLE'=>$proposal_title,
									'QTY'=>$order['order_qty'],
									'DELIVERY_TIME'=>$order['order_duration'],
									'ORDER_PRICE'=>$order['order_price'],
									'ORDER_DETAILS_URL'=>$url,
									);
									SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
									SendMail('',get_option_value('admin_email'),$template,$data_parse);
									
									$notificationData=array(
									'sender_id'=>$this->member_id,
									'receiver_id'=>$order['seller_id'],
									'template'=>'order',
									'url'=>$this->config->item('OrderDetailsURL').$order_id,
									'content'=>json_encode(array('OID'=>$order_id)),
									);
									$this->notification_model->savenotification($notificationData);		
								}
							}
							$msg['status'] = 'OK';
							$msg['method'] = $method;
							$msg['redirect'] = get_link('buyingOrderURL').'?ref=paymentsuccess';
							
						}else{
							$msg['status'] = 'FAIL';
							$msg['message'] = 'Insufficient fund';
						}
					}
				}
				elseif($method=='paypal'){
					$processing_fee=0;
					$arr=array(
						'select'=>'c.proposal_id,c.qty,p.proposal_title,p.proposal_image,p.proposal_price,pp.price,pp.package_name,c.extra,c.package_id,p.proposal_url,p.proposal_seller_id,p.delivery_time,pa.buyer_instruction',
						'table'=>'cart as c',
						'join'=>array(
							array('table'=>'proposals as p','on'=>'c.proposal_id=p.proposal_id','position'=>'left'),
							array('table'=>'proposal_packages as pp','on'=>'c.package_id=pp.package_id','position'=>'left'),
							array('table'=>'proposal_additional as pa','on'=>'c.proposal_id=pa.proposal_id','position'=>'left'),
						),
						'where'=>array('c.member_id'=>$this->member_id),
					);
					$cart=getData($arr);
					if($cart){
						$orderdata=array();
						$sub_total=0;
						foreach($cart as $k=>$cartdata){
							$order_number=time();
							$proposal_price=0;
							if($cartdata->package_id){
								$proposal_price=$cartdata->price;
							}else{
								$proposal_price=$cartdata->proposal_price;
							}
							$extraCart=array();
							if($cartdata->extra){
								$extra=json_decode($cartdata->extra);
								$extraData=$this->db->where_in('id',$extra)->where('proposal_id',$cartdata->proposal_id)->from('proposal_extras')->get()->result();
								foreach($extraDataas as $extra){
									$extraCart[]=array(
									'name'=>$extra->name,
									'price'=>$extra->price,
									);
									$proposal_price+=$extra->price;
								}
							}
							$price_total = $proposal_price * $cartdata->qty;
							$sub_total += $price_total;
							$order_time = date("M d, Y H:i:s", strtotime(" + ".$cartdata->delivery_time." days"));
							$orderdata[]=array(
							'order_number'=>$order_number,
							'order_duration'=>$cartdata->delivery_time,
							'order_date'=>date('Y-m-d H:i:s'),
							'order_time'=>$order_time,
							/*'order_description'=>$order_description,*/
							'seller_id'=>$cartdata->proposal_seller_id,
							'buyer_id'=>$this->member_id,
							'proposal_id'=>$cartdata->proposal_id,
							'order_price'=>$price_total,
							'order_qty'=>$cartdata->qty,
							'order_fee'=>$processing_fee,
							'order_active'=>'0',
							'order_status'=>0,
							'payment_method'=>$method,
							'order_extra'=>$extraCart,
							);
							$totalAll=$sub_total+$processing_fee;
						}
						if($orderdata){
							$all_order_ids=array();
							foreach($orderdata as $order){
								$order_extra=array();
								if($order['order_extra']){
									$order_extra=$order['order_extra'];
								}
								unset($order['order_extra']);
								$total=$order['order_price'];
								$order_id=$this->cart_model->processOrder($order);
								if($order_id){
									$all_order_ids[]=$order_id;
									if($order_extra){
										foreach($order_extra as $oextra){
											insertTable('orders_extras',array('order_id'=>$order_id,'name'=>$oextra['name'],'price'=>$oextra['price']));
										}
									}
								}
							}
							if($all_order_ids){
								$msg['status'] = 'OK';
								$msg['redirect'] = get_link('PaypalCheckOut').'cart/'.implode('-',$all_order_ids);
							}else{
								$msg['status'] = 'FAIL';
								$msg['message'] = 'Error in process';
							}
							
						}
					}	
				}
				elseif($method=='telr'){
					$processing_fee=0;
					$arr=array(
						'select'=>'c.proposal_id,c.qty,p.proposal_title,p.proposal_image,p.proposal_price,pp.price,pp.package_name,c.extra,c.package_id,p.proposal_url,p.proposal_seller_id,p.delivery_time,pa.buyer_instruction',
						'table'=>'cart as c',
						'join'=>array(
							array('table'=>'proposals as p','on'=>'c.proposal_id=p.proposal_id','position'=>'left'),
							array('table'=>'proposal_packages as pp','on'=>'c.package_id=pp.package_id','position'=>'left'),
							array('table'=>'proposal_additional as pa','on'=>'c.proposal_id=pa.proposal_id','position'=>'left'),
						),
						'where'=>array('c.member_id'=>$this->member_id),
					);
					$cart=getData($arr);
					if($cart){
						$orderdata=array();
						$sub_total=0;
						foreach($cart as $k=>$cartdata){
							$order_number=time();
							$proposal_price=0;
							if($cartdata->package_id){
								$proposal_price=$cartdata->price;
							}else{
								$proposal_price=$cartdata->proposal_price;
							}
							$extraCart=array();
							if($cartdata->extra){
								$extra=json_decode($cartdata->extra);
								$extraData=$this->db->where_in('id',$extra)->where('proposal_id',$cartdata->proposal_id)->from('proposal_extras')->get()->result();
								foreach($extraDataas as $extra){
									$extraCart[]=array(
									'name'=>$extra->name,
									'price'=>$extra->price,
									);
									$proposal_price+=$extra->price;
								}
							}
							$price_total = $proposal_price * $cartdata->qty;
							$sub_total += $price_total;
							$order_time = date("M d, Y H:i:s", strtotime(" + ".$cartdata->delivery_time." days"));
							$orderdata[]=array(
							'order_number'=>$order_number,
							'order_duration'=>$cartdata->delivery_time,
							'order_date'=>date('Y-m-d H:i:s'),
							'order_time'=>$order_time,
							/*'order_description'=>$order_description,*/
							'seller_id'=>$cartdata->proposal_seller_id,
							'buyer_id'=>$this->member_id,
							'proposal_id'=>$cartdata->proposal_id,
							'order_price'=>$price_total,
							'order_qty'=>$cartdata->qty,
							'order_fee'=>$processing_fee,
							'order_active'=>'0',
							'order_status'=>0,
							'payment_method'=>$method,
							'order_extra'=>$extraCart,
							);
							$totalAll=$sub_total+$processing_fee;
						}
						if($orderdata){
							$all_order_ids=array();
							foreach($orderdata as $order){
								$order_extra=array();
								if($order['order_extra']){
									$order_extra=$order['order_extra'];
								}
								unset($order['order_extra']);
								$total=$order['order_price'];
								$order_id=$this->cart_model->processOrder($order);
								if($order_id){
									$all_order_ids[]=$order_id;
									if($order_extra){
										foreach($order_extra as $oextra){
											insertTable('orders_extras',array('order_id'=>$order_id,'name'=>$oextra['name'],'price'=>$oextra['price']));
										}
									}
								}
							}
							if($all_order_ids){
								$msg['status'] = 'OK';
								$order_id=implode('-',$all_order_ids);
								$post_data = Array(
									'ivp_method'		=> 'create',
									'ivp_authkey'		=> get_option_value('telr_authentication_code'),
									'ivp_store'		=> get_option_value('telr_store_id'),
									'ivp_lang'		=> 'en',
									'ivp_cart'		=> 'CART-'.$order_id,
									'ivp_amount'		=> $totalAll,
									'ivp_currency'		=> trim(CurrencyCode()),
									'ivp_test'		=> get_option_value('telr_is_sandbox'),
									'ivp_desc'		=> trim('Cart Order Process'),
									'return_auth'		=> 	get_link('TelrNotify').'cart/'.$order_id,
									'return_can'		=>  get_link('homeURL'),
									'return_decl'		=>  get_link('homeURL'),
									/*'ivp_update_url'	=>  get_link('TelrNotify').'featured/'.$proposal_id,*/
								);
								$curl_telr=curl_telr($post_data);
								if($curl_telr){
									if(isset($curl_telr['order'])) {
										$transansaction_data=array('payment_type'=>'TELR','content_key'=>'CART-'.$order_id);
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
											
								
								
								
								//$msg['redirect'] = get_link('PaypalCheckOut').'cart/'.implode('-',$all_order_ids);
							}else{
								$msg['status'] = 'FAIL';
								$msg['message'] = 'Error in process';
							}
							
						}
					}	
				}
				elseif($method=='ngenius'){
					$processing_fee=0;
					$arr=array(
						'select'=>'c.proposal_id,c.qty,p.proposal_title,p.proposal_image,p.proposal_price,pp.price,pp.package_name,c.extra,c.package_id,p.proposal_url,p.proposal_seller_id,p.delivery_time,pa.buyer_instruction',
						'table'=>'cart as c',
						'join'=>array(
							array('table'=>'proposals as p','on'=>'c.proposal_id=p.proposal_id','position'=>'left'),
							array('table'=>'proposal_packages as pp','on'=>'c.package_id=pp.package_id','position'=>'left'),
							array('table'=>'proposal_additional as pa','on'=>'c.proposal_id=pa.proposal_id','position'=>'left'),
						),
						'where'=>array('c.member_id'=>$this->member_id),
					);
					$cart=getData($arr);
					if($cart){
						$orderdata=array();
						$sub_total=0;
						foreach($cart as $k=>$cartdata){
							$order_number=time();
							$proposal_price=0;
							if($cartdata->package_id){
								$proposal_price=$cartdata->price;
							}else{
								$proposal_price=$cartdata->proposal_price;
							}
							$extraCart=array();
							if($cartdata->extra){
								$extra=json_decode($cartdata->extra);
								$extraData=$this->db->where_in('id',$extra)->where('proposal_id',$cartdata->proposal_id)->from('proposal_extras')->get()->result();
								foreach($extraDataas as $extra){
									$extraCart[]=array(
									'name'=>$extra->name,
									'price'=>$extra->price,
									);
									$proposal_price+=$extra->price;
								}
							}
							$price_total = $proposal_price * $cartdata->qty;
							$sub_total += $price_total;
							$order_time = date("M d, Y H:i:s", strtotime(" + ".$cartdata->delivery_time." days"));
							$orderdata[]=array(
							'order_number'=>$order_number,
							'order_duration'=>$cartdata->delivery_time,
							'order_date'=>date('Y-m-d H:i:s'),
							'order_time'=>$order_time,
							/*'order_description'=>$order_description,*/
							'seller_id'=>$cartdata->proposal_seller_id,
							'buyer_id'=>$this->member_id,
							'proposal_id'=>$cartdata->proposal_id,
							'order_price'=>$price_total,
							'order_qty'=>$cartdata->qty,
							'order_fee'=>$processing_fee,
							'order_active'=>'0',
							'order_status'=>0,
							'payment_method'=>$method,
							'order_extra'=>$extraCart,
							);
							$totalAll=$sub_total+$processing_fee;
						}
						if($orderdata){
							$all_order_ids=array();
							foreach($orderdata as $order){
								$order_extra=array();
								if($order['order_extra']){
									$order_extra=$order['order_extra'];
								}
								unset($order['order_extra']);
								$total=$order['order_price'];
								$order_id=$this->cart_model->processOrder($order);
								if($order_id){
									$all_order_ids[]=$order_id;
									if($order_extra){
										foreach($order_extra as $oextra){
											insertTable('orders_extras',array('order_id'=>$order_id,'name'=>$oextra['name'],'price'=>$oextra['price']));
										}
									}
								}
							}
							if($all_order_ids){
								$msg['status'] = 'OK';
								$order_id=implode('-',$all_order_ids);
								
								$post_data = array(
									'grant_type'		=> 'client_credentials',
								);
								$curl_ngenius=curl_ngenius($post_data,'token',$this->member_id);
								if($curl_ngenius){
									$access_token = $curl_ngenius['access_token'];
									if($access_token){
										$postData = array(); 
										$postData['action'] = 'SALE'; 
										$postData['amount'] =array();
										$postData['merchantAttributes '] =array();
										$postData['merchantAttributes']['redirectUrl'] =get_link('buyingOrderURL').'?ref_p=paymentsuccess';
										$postData['merchantAttributes']['cancelUrl'] = get_link('homeURL'); 
										$postData['amount']['currencyCode'] = trim(CurrencyCode());  
										$postData['amount']['value'] = round($totalAll*100); 
										$postData['token'] = $access_token; 
										$curl_ngenius_order=curl_ngenius($postData,'order',$this->member_id);
										if($curl_ngenius_order){
										//print_r($curl_ngenius_order);
											if($curl_ngenius_order['_links']['payment']['href']){
												$ref = $curl_ngenius_order['reference'];
												$transansaction_data=array('payment_type'=>'NGENIUS','content_key'=>$ref);
												unset($postData['token']);
												$postData['cart_id']=$order_id;
												$postData['payment_type']='cart';
												$transansaction_data['request_value']=json_encode($postData);
												insertTable('online_transaction_data',$transansaction_data);
												
												
												//$this->session->set_userdata('Nref',$ref);
												$redirurl=$curl_ngenius_order['_links']['payment']['href'];
												$msg['status'] = 'OK';
												$msg['method'] = $method;
												$msg['redirect'] =$redirurl;
											}else{
												$jobj = $returnData['error'];
												$msg['status'] = 'FAIL';
												$msg['error'] = $jobj['message'].' :: '.$jobj['note'];
											}
										}	
									}
								}

								//$msg['redirect'] = get_link('PaypalCheckOut').'cart/'.implode('-',$all_order_ids);
							}else{
								$msg['status'] = 'FAIL';
								$msg['message'] = 'Error in process';
							}
							
						}
					}	
				}
				if($msg['status']=='OK'){
					delete('cart',array('member_id'=>$this->member_id));	
				}
				
			}
		}
	unset($_POST);
	echo json_encode($msg);	
	}
	public function checkoutprocessCheckAjax(){
		$all_method=array('wallet','paypal','telr','ngenius','bank');
		checkrequestajax();
		$i=0;
		$msg=array();
		$method=post('method');
		$is_cart=post('payfor');
		$processing_fee=get_option_value('processing_fee');
		$order_status=ORDER_PROCESSING;
		$order_number=time();
		$buyer_details=getMemberDetails($this->member_id,array('main'=>1));
		
		if($is_cart=='cart'){
			
			
			
		}else{
			
			$CheckOutData=$this->session->userdata('CheckOutData');
			
			//dd($CheckOutData,TRUE);
			if($CheckOutData && in_array($method,$all_method)){
			$proposal_details=getProposalDetails($CheckOutData['proposal_id'],array('proposal','proposal_additional','proposal_settings'));
			$seller_details=getMemberDetails($proposal_details['proposal']->proposal_seller_id,array('main'=>1));
			if(!empty($proposal_details['proposal_additional']->buyer_instruction)){
				$order_status=ORDER_PENDING;
			}
			$delivery_time=$proposal_details['proposal']->delivery_time;
			if($CheckOutData['package_id']){
				$delivery_time_package=getFieldData('delivery_time','proposal_packages','package_id',$CheckOutData['package_id']);
				if($delivery_time_package){
					$delivery_time=$delivery_time_package;
				}
			}	
			if($method=='wallet'){
				
				
				$processing_fee=0;
				$total=$CheckOutData['sub_total']+$processing_fee;
				
				if($buyer_details && $buyer_details['member']->balance>$total){
				//$delivery= getAllDeliveryTimes($proposal_details['proposal']->delivery_time)
				$order_time = date("M d, Y H:i:s", strtotime(" + ".$delivery_time." days"));
				$OrderData=array(
				'order_number'=>$order_number,
				'order_duration'=>$delivery_time,
				'order_date'=>date('Y-m-d H:i:s'),
				'order_time'=>$order_time,
				/*'order_description'=>$order_description,*/
				'seller_id'=>$proposal_details['proposal']->proposal_seller_id,
				'buyer_id'=>$this->member_id,
				'proposal_id'=>$CheckOutData['proposal_id'],
				'order_price'=>$CheckOutData['sub_total'],
				'order_qty'=>$CheckOutData['qty'],
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
					
					//$seller_wallet_id=$seller_details['member']->wallet_id;
					//$seller_wallet_balance=$seller_details['member']->balance;
					$reciver_wallet_id=$site_details->wallet_id;
					$reciver_wallet_balance=$site_details->balance;
					//$issuer_relational_data=get_option_value('website_name');
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
						
						
						$RECEIVER_EMAIL=$seller_details['member']->member_email;
						$url=get_link('OrderDetailsURL').$order_id;
						$template='new-order';
						$data_parse=array(
						'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
						'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
						'PROPOSAL_TITLE'=>$proposal_details['proposal']->proposal_title,
						'QTY'=>$OrderData['order_qty'],
						'DELIVERY_TIME'=>$proposal_details['proposal']->delivery_time,
						'ORDER_PRICE'=>$OrderData['order_price'],
						'ORDER_DETAILS_URL'=>$url,
						);
						SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
						SendMail('',get_option_value('admin_email'),$template,$data_parse);
						
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
						$msg['message'] = 'Insufficient fund';
					}
				}
				}else{
					$msg['status'] = 'FAIL';
					$msg['message'] = 'transaction error';
				}
				
			}
			elseif($method=='bank'){
				$processing_fee=0;
				$total=$CheckOutData['sub_total']+$processing_fee;
				
				if($buyer_details && $buyer_details['member']->bank_transfer_allowed==1){
				//$delivery= getAllDeliveryTimes($proposal_details['proposal']->delivery_time)
				$order_time = date("M d, Y H:i:s", strtotime(" + ".$delivery_time." days"));
				$OrderData=array(
				'order_number'=>$order_number,
				'order_duration'=>$delivery_time,
				'order_date'=>date('Y-m-d H:i:s'),
				'order_time'=>$order_time,
				/*'order_description'=>$order_description,*/
				'seller_id'=>$proposal_details['proposal']->proposal_seller_id,
				'buyer_id'=>$this->member_id,
				'proposal_id'=>$CheckOutData['proposal_id'],
				'order_price'=>$CheckOutData['sub_total'],
				'order_qty'=>$CheckOutData['qty'],
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
					$bank_details=getWallet(get_option_value('BANK_WALLET'));
					$bank_wallet_id=$bank_details->wallet_id;
					$bank_wallet_balance=$bank_details->balance;

					$wallet_transaction_type_id=get_option_value('ORDER_PAYMENT_BANK');
					$current_datetime=date('Y-m-d H:i:s');
					$wallet_transaction_id=insertTable('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
					if($wallet_transaction_id){
						insertTable('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$bank_wallet_id,'debit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Bank');
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$bank_details->title,
						'TW'=>$buyer_details['member']->member_name.' wallet',	
						'TP'=>'Bank_Transfer',
						));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Bank');
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$bank_details->title,
							'TW'=>$buyer_details['member']->member_name.' wallet',	
							'TP'=>'Wallet_Topup',
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
						$new_balance_bank=displayamount($bank_wallet_balance,2)-displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance_bank),array('wallet_id'=>$bank_wallet_id));
						wallet_balance_check($bank_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						updateTable('orders',array('order_status'=>$order_status,'transaction_id'=>$wallet_transaction_id),array('order_id'=>$order_id));
						
						
						$RECEIVER_EMAIL=$seller_details['member']->member_email;
						$url=get_link('OrderDetailsURL').$order_id;
						$template='new-order';
						$data_parse=array(
						'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
						'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
						'PROPOSAL_TITLE'=>$proposal_details['proposal']->proposal_title,
						'QTY'=>$OrderData['order_qty'],
						'DELIVERY_TIME'=>$proposal_details['proposal']->delivery_time,
						'ORDER_PRICE'=>$OrderData['order_price'],
						'ORDER_DETAILS_URL'=>$url,
						);
						SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
						SendMail('',get_option_value('admin_email'),$template,$data_parse);
						
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
						$msg['message'] = 'Insufficient fund';
					}
				}
				}else{
					$msg['status'] = 'FAIL';
					$msg['message'] = 'transaction error';
				}
				
			}
			elseif($method=='paypal'){
				$feeCalculation=generateProcessingFee('paypal',$CheckOutData['sub_total']);
				$processing_fee=$feeCalculation['processing_fee'];
				$total=$CheckOutData['sub_total']+$processing_fee;
				$order_time = date("M d, Y H:i:s", strtotime(" + ".$delivery_time." days"));
				$OrderData=array(
				'order_number'=>$order_number,
				'order_duration'=>$delivery_time,
				'order_date'=>date('Y-m-d H:i:s'),
				'order_time'=>$order_time,
				/*'order_description'=>$order_description,*/
				'seller_id'=>$proposal_details['proposal']->proposal_seller_id,
				'buyer_id'=>$this->member_id,
				'proposal_id'=>$CheckOutData['proposal_id'],
				'order_price'=>$CheckOutData['sub_total'],
				'order_qty'=>$CheckOutData['qty'],
				'order_fee'=>$processing_fee,
				'order_active'=>'0',
				'order_status'=>0,
				'payment_method'=>$method,
				);
				$order_id=$this->cart_model->processOrder($OrderData);
				if($order_id){
					$this->session->unset_userdata('referred-'.$CheckOutData['proposal_id']);
					$this->session->unset_userdata('CheckOutData');
					$msg['status'] = 'OK';
					$msg['redirect'] = get_link('PaypalCheckOut').'checkout/'.$order_id;
				}
			}
			elseif($method=='telr'){
				$feeCalculation=generateProcessingFee('telr',$CheckOutData['sub_total']);
				$processing_fee=$feeCalculation['processing_fee'];
				$total=$CheckOutData['sub_total']+$processing_fee;
				$order_time = date("M d, Y H:i:s", strtotime(" + ".$delivery_time." days"));
				$OrderData=array(
				'order_number'=>$order_number,
				'order_duration'=>$delivery_time,
				'order_date'=>date('Y-m-d H:i:s'),
				'order_time'=>$order_time,
				/*'order_description'=>$order_description,*/
				'seller_id'=>$proposal_details['proposal']->proposal_seller_id,
				'buyer_id'=>$this->member_id,
				'proposal_id'=>$CheckOutData['proposal_id'],
				'order_price'=>$CheckOutData['sub_total'],
				'order_qty'=>$CheckOutData['qty'],
				'order_fee'=>$processing_fee,
				'order_active'=>'0',
				'order_status'=>0,
				'payment_method'=>$method,
				);
				$order_id=$this->cart_model->processOrder($OrderData);
				if($order_id){
					$this->session->unset_userdata('referred-'.$CheckOutData['proposal_id']);
					$this->session->unset_userdata('CheckOutData');
					$msg['status'] = 'OK';
					//$msg['redirect'] = get_link('PaypalCheckOut').'checkout/'.$order_id;
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
				}
			}
			elseif($method=='ngenius'){
				$feeCalculation=generateProcessingFee('ngenius',$CheckOutData['sub_total']);
				$processing_fee=$feeCalculation['processing_fee'];
				$total=$CheckOutData['sub_total']+$processing_fee;
				$order_time = date("M d, Y H:i:s", strtotime(" + ".$delivery_time." days"));
				$OrderData=array(
				'order_number'=>$order_number,
				'order_duration'=>$delivery_time,
				'order_date'=>date('Y-m-d H:i:s'),
				'order_time'=>$order_time,
				/*'order_description'=>$order_description,*/
				'seller_id'=>$proposal_details['proposal']->proposal_seller_id,
				'buyer_id'=>$this->member_id,
				'proposal_id'=>$CheckOutData['proposal_id'],
				'order_price'=>$CheckOutData['sub_total'],
				'order_qty'=>$CheckOutData['qty'],
				'order_fee'=>$processing_fee,
				'order_active'=>'0',
				'order_status'=>0,
				'payment_method'=>$method,
				);
				$order_id=$this->cart_model->processOrder($OrderData);
				if($order_id){
					$this->session->unset_userdata('referred-'.$CheckOutData['proposal_id']);
					$this->session->unset_userdata('CheckOutData');
					$msg['status'] = 'OK';
					
					$post_data = array(
						'grant_type'		=> 'client_credentials',
					);
					$curl_ngenius=curl_ngenius($post_data,'token',$this->member_id);
					if($curl_ngenius){
						$access_token = $curl_ngenius['access_token'];
						if($access_token){
							$postData = array(); 
							$postData['action'] = 'SALE'; 
							$postData['amount'] =array();
							$postData['merchantAttributes '] =array();
							$postData['merchantAttributes']['redirectUrl'] = get_link('OrderDetailsURL').$order_id.'?ref_p=paymentsuccess'; 
							$postData['merchantAttributes']['cancelUrl'] = get_link('homeURL'); 
							$postData['amount']['currencyCode'] = trim(CurrencyCode());  
							$postData['amount']['value'] = round($total*100); 
							$postData['token'] = $access_token; 
							$curl_ngenius_order=curl_ngenius($postData,'order',$this->member_id);
							if($curl_ngenius_order){
							//print_r($curl_ngenius_order);
								if($curl_ngenius_order['_links']['payment']['href']){
									$ref = $curl_ngenius_order['reference'];
									$transansaction_data=array('payment_type'=>'NGENIUS','content_key'=>$ref);
									unset($postData['token']);
									$postData['cart_id']=$order_id;
									$postData['payment_type']='checkout';
									$transansaction_data['request_value']=json_encode($postData);
									insertTable('online_transaction_data',$transansaction_data);
									
									
									//$this->session->set_userdata('Nref',$ref);
									$redirurl=$curl_ngenius_order['_links']['payment']['href'];
									$msg['status'] = 'OK';
									$msg['method'] = $method;
									$msg['redirect'] =$redirurl;
								}else{
									$jobj = $returnData['error'];
									$msg['status'] = 'FAIL';
									$msg['error'] = $jobj['message'].' :: '.$jobj['note'];
								}
							}	
						
						}
					}
				}
			}
			if($order_id){
				if($CheckOutData['extra']){
					foreach($CheckOutData['extra'] as $extra){
						$arr=array(
							'select'=>'p.id,p.name,p.price',
							'table'=>'proposal_extras as p',
							'where'=>array('p.proposal_id'=>$CheckOutData['proposal_id'],'p.id'=>$extra),
							'single_row'=>TRUE
						);
						$proposal_extras=getData($arr);
						if($proposal_extras){
							insertTable('orders_extras',array('order_id'=>$order_id,'name'=>$proposal_extras->name,'price'=>$proposal_extras->price));
						}
					}
				}
				if($proposal_details['proposal_settings']->proposal_enable_referrals == 1){
					if($this->session->userdata('referred-'.$CheckOutData['proposal_id'])){
						$referred=$this->session->userdata('referred-'.$CheckOutData['proposal_id']);
						if($referred['refer_id']!=$this->member_id){
							$proposal_referral_money=$proposal_details['proposal_settings']->proposal_referral_money;
							$r_o_comission = ($CheckOutData['sub_total']*$proposal_referral_money)/100;
							$comission = round($r_o_comission,1);
							$proposals_referrals=array(
								'proposal_id'=>$CheckOutData['proposal_id'],
								'order_id'=>$order_id,
								'seller_id'=>$seller_details['member']->member_id,
								'referrer_id'=>$referred['refer_id'],
								'buyer_id'=>$buyer_details['member']->member_id,
								'comission'=>$comission,
								'date'=>date('Y-m-d H:i:s'),
								'ip'=>$this->input->ip_address(),
								'status'=>0,
							);
							insertTable('proposals_referrals',$proposals_referrals);
						}	
					}	
				}
				if($method=='wallet'){
					$notificationData=array(
					'sender_id'=>$this->member_id,
					'receiver_id'=>$seller_details['member']->member_id,
					'template'=>'order',
					'url'=>$this->config->item('OrderDetailsURL').$order_id,
					'content'=>json_encode(array('OID'=>$order_id)),
					);
					$this->notification_model->savenotification($notificationData);
				}
				$this->session->unset_userdata('referred-'.$CheckOutData['proposal_id']);
				$this->session->unset_userdata('CheckOutData');
				
			}
			
			}else{
				$msg['status'] = 'FAIL';
			}
		}
		
		
	unset($_POST);
	echo json_encode($msg);
	}
	
	public function cartaction(){
		checkrequestajax();
		$action=post('action');
		$proposal_id=post('proposal_id');
		if($action=='cartupdate' && $proposal_id){
			updateTable('cart',array('qty'=>post('proposal_qty')),array('member_id'=>$this->member_id,'proposal_id'=>$proposal_id));
			return 1;
		}elseif($action=='delete' && $proposal_id){
			delete('cart',array('member_id'=>$this->member_id,'proposal_id'=>$proposal_id));
			return 1;
			
		}
	}
}
