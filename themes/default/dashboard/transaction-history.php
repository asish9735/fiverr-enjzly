<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($transaction_list,TRUE);
?>
<div class="breadcrumbs">
  <div class="container-fluid">
  	<h1><?php D(__('transaction_page_Transaction_History','Transaction History'));?></h1>
  </div>
</div>
<section class="section">
   <div class="container-fluid">
   <div class="row">
      <div class="col-xl-3 col-lg-4 col-12">
      <?php

$templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
load_template($templateLayout,$data);

?>
      </div>
        <div class="col-xl-9 col-lg-8 col-12">
            <div class="dashboard-box mt-0 mb-4">
                <div class="headline black">
                    <h4><?php D(__('transaction_page_Available_Balance','Available Balance:'));?> <b class="text-success"><?php D(CURRENCY); ?><?php D(displayamount($member_details['member']->balance)); ?> </b></h4>
                </div>  
                <ul class="dashboard-box-list">
                    <?php /*?><thead>
                        <tr>
                            <th><?php D(__('transaction_page_Date','Date'));?></th>
                            <th><?php D(__('transaction_page_For','For'));?></th>
                            <th><?php D(__('transaction_page_Status','Status'));?></th>
                            <th><?php D(__('transaction_page_Amount','Amount'));?></th>
                            <th><?php D(__('transaction_page_Processing_Fee','Processing Fee'));?></th>
                        </tr>
                    </thead><?php */?>
                        <?php
                    if($transaction_list){
                        $checktype=array('order_payment_paypal','featured_payment_paypal','featured_payment_telr','order_payment_telr');
                        $processing_fee_wallet=get_option_value('PROCESSING_FEE_WALLET');
                            foreach($transaction_list as $transaction){
                                $orgamount=$transaction->Amount;
                                $commission=0;
                                if(in_array($transaction->title_tkey,$checktype)){
                                    $commission=getFieldData('credit','wallet_transaction_row','','',array('wallet_transaction_id'=>$transaction->wallet_transaction_id,'wallet_id'=>$processing_fee_wallet));
                                }
                                
                                if($orgamount>0){
                                    $clas="text-success";
                                    $amount='+'.CURRENCY.displayamount($orgamount);
                                }else{
                                    $clas="text-danger";
                                    $amount='-'.CURRENCY.displayamount(-$orgamount);
                                }	
                        ?>
                        <li>
                            <div class="job-listing">
                                <div class="job-listing-details">                        
                                <div class="job-listing-description"><h5 class="job-listing-title"><?php D($transaction->name) ; ?></h5>                                <div class="job-listing-footer">
                                    <ul>
                                        <li><i class="icon-feather-calendar"></i> <?php D(dateFormat($transaction->transaction_date,'F d, Y')) ; ?> <?php D(date('H:i',strtotime($transaction->transaction_date))) ; ?></li>
                                        <li><i class="icon-feather-tag"></i> <b><?php D(__('transaction_page_Amount','Amount'));?>:</b> 
                                            <span class="<?php D($clas)?>"><?php D($amount); ?></span>                        
                                        </li>
                                        <li><i class="icon-material-outline-money"></i> <b><?php D(__('transaction_page_Processing_Fee','Processing Fee'));?>:</b> <span class="text-danger"><?php if($commission>0){D('-'.CURRENCY.displayamount($commission));}else{ echo '0';}?></span></li>
                                        
                                    </ul>
                                </div>
                                </div>
                                </div>
                            </div>
                            <!-- Buttons -->
                            <div class="buttons-to-right single-right-button">
                                <?php if($transaction->status==0){ ?>
                                <span class="dashboard-status-button yellow"><?php D(__('transaction_page_Pending','Pending'));?> </span>
                                <?php }elseif($transaction->status== 1){?>
                                <span class="dashboard-status-button green"><?php D(__('transaction_page_Completed','Completed'));?></span>
                                <?php }else{?>
                                <span class="dashboard-status-button red"><?php D(__('transaction_page_Cancelled','Cancelled'));?></span>
                                <?php } ?>
                            </div>
                         </li>
                    <?php	}
                        }?>
                </ul>
            </div>
                <div class="box-footer clearfix row justify-content-center mt-0">
                    <?php echo $links;?>
                </div>
        </div>	
	</div>
   </div>
</section>
