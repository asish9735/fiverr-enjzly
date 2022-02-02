<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function processOrder($data=array()){
		$order_id=insertTable('orders',$data,TRUE);
		return $order_id;
	}
}
