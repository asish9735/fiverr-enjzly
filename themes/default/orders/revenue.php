<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$withdrawal_limit=get_option_value('withdrawal_limit');

//$enable_paypal = get_option_value('enable_paypal');

$enable_stripe =get_option_value('enable_stripe');

$enable_payza =get_option_value('enable_payza');

$enable_coinpayments =get_option_value('enable_coinpayments');

$enable_dusupay =get_option_value('enable_dusupay');

$enable_paypal=$enable_bank=$enable_payoneer=1;

//dd($member_details,TRUE);
?>

<div class="breadcrumbs">

  <div class="container-fluid">

	<h1><?php D(__('revenue_page_heading',"Revenue Earned"));?></h1>

    <p class="mb-0"><?php D(__('revenue_page_Available_For_Withdrawal',"Available For Withdrawal:"));?> <span class="font-weight-bold text-success"> <?php D(CURRENCY); ?><?php D(displayamount($member_details['member']->balance)); ?> </span></p>

  </div>

</div>

<section class="section">
<div class="container-fluid">    
	<div class="row">
      <div class="col-xl-3 col-lg-auto col-12">
        <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
      </div>
      <div class="col-xl-9 col-lg col-12">
        <div class="fun-facts-section">
		<div class="fun-facts-container">
			<div class="fun-fact text-white" data-fun-fact-color="#111">

			<div class="fun-fact-text">

				<span><?php D(__('revenue_page_Withdrawals',"Withdrawals"));?></span>

				<h2><?php D(CURRENCY); ?><?php D(displayamount($withdrawn)); ?></h2>

			</div>

			<div class="fun-fact-icon"><i class="icon-feather-credit-card"></i></div>

			</div>



			<div class="fun-fact text-dark" data-fun-fact-color="#fdd007">

				<div class="fun-fact-text">

					<span><?php D(__('revenue_page_Used_To_Order_Proposals_Services',"Used To Order Proposals/Services"));?></span>

					<h2><?php D(CURRENCY); ?><?php D(displayamount($used_purchases)); ?></h2>

				</div>

				<div class="fun-fact-icon"><i class="icon-feather-shopping-cart"></i></div>

			</div>

			

			<div class="fun-fact text-white" data-fun-fact-color="#111">

				<div class="fun-fact-text">

				<span><?php D(__('revenue_page_Pending_Clearance',"Pending Clearance"));?></span>

				<h2><?php D(CURRENCY); ?><?php D(displayamount($pending_clearance)); ?></h2>

				</div>

				<div class="fun-fact-icon"><i class="icon-feather-slash"></i></div>

			</div>

			

			<div class="fun-fact text-dark" data-fun-fact-color="#fdd007">

				<div class="fun-fact-text">

				<span><?php D(__('revenue_page_Available_Income',"Available Income"));?></span>

				<h2><?php D(CURRENCY); ?><?php D(displayamount($member_details['member']->balance)); ?></h2>

				</div>

				<div class="fun-fact-icon"><i class="icon-feather-tag"></i></div>

			</div>

			

		</div>
		</div>
			<?php if($member_details['member']->balance >=$withdrawal_limit){ ?>

			
			<div class="d-md-flex align-items-center mb-3">
			<label class="mr-2 mb-2"><strong><?php D(__('revenue_page_Withdraw_To',"Withdraw To:"));?></strong> </label>

			 <?php if($enable_paypal == 1){ ?>

			<button class="btn btn-primary mr-2 mb-2" data-toggle="modal" data-target="#paypal_withdraw_modal">

				<i class="icon-brand-paypal"></i> <?php D(__('revenue_page_Paypal_Account',"Paypal Account"));?>

			</button>

			<?php }?>

			<button class="btn btn-site mr-2 mb-2" data-toggle="modal" data-target="#bank_withdraw_modal">

				<i class="fa fa-bank"></i> <?php D(__('revenue_page_Bank_Account',"Bank Account"));?>

			</button>

			<?php if($enable_payoneer == 1){ ?>

			<button class="btn btn-dark mr-2 mb-2" data-toggle="modal" data-target="#payoneer_withdraw_modal">

				<i class="fa fa-payoneer"></i> <?php D(__('revenue_page_Payoneer_Account',"Payoneer Account"));?> 

			</button>

			<?php }?>

			<?php if($enable_dusupay == 1){ ?>

			<button class="btn btn-outline-site mr-2 mb-2" data-toggle="modal" data-target="#mobile_money_modal">

				<i class="fa fa-mobile"></i> <?php D(__('revenue_page_Mobile_Money',"Mobile Money"));?> 

			</button>

			<?php }?>

			<?php if($enable_coinpayments == 1){ ?>

			<button class="btn btn-outlin-warning mr-2 mb-2" data-toggle="modal" data-target="#trx_wallet_modal">

				<i class="fa fa-mobile"></i> <?php D(__('revenue_page_Bitcoin_Wallet',"Bitcoin Wallet"));?> 

			</button>

			<?php }?>
			</div>
			<?php }else{ ?>

			<?php if($enable_paypal == 1){ ?>
			
			<button class="btn btn-outline-primary mr-2 mb-2">

			<i class="fa fa-paypal"></i> <?php D(__('revenue_page_Paypal_Account',"Paypal Account"));?>

			</button>

			<?php }?>

			<button class="btn btn-secondary mr-2 mb-2">

				<i class="fa fa-bank"></i> <?php D(__('revenue_page_Bank_Account',"Bank Account"));?>

			</button>

			<?php if($enable_payoneer == 1){ ?>

			<button class="btn btn-secondary mr-2 mb-2">

				<i class="fa fa-payoneer"></i> <?php D(__('revenue_page_Payoneer_Account',"Payoneer Account"));?>

			</button>

			<?php }?>

			<?php if($enable_dusupay == 1){ ?>

			<button class="btn btn-secondary mr-2 mb-2">

				<i class="fa fa-mobile"></i> <?php D(__('revenue_page_Mobile_Money',"Mobile Money"));?>

			</button>

			<?php }?>

			<?php if($enable_coinpayments == 1){ ?>

			<button class="btn btn-secondary mr-2 mb-2">

				<i class="fa fa-mobile"></i> <?php D(__('revenue_page_Bitcoin_Wallet',"Bitcoin Wallet"));?> 

			</button>

			<?php }?>
            

		<p><?php D(__('revenue_page_minimum_withdrawn_balance',"You must have a minimum of at least"));?> <?php D(CURRENCY);?><?php D($withdrawal_limit);?> <?php D(__('revenue_page_to_withdraw',"to withdraw."));?></p>

			<?php } ?>

		<div class="dashboard-box">
			<ul class="nav nav-pills" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Revenue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Withdraw</a>
                </li>  
			</ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <ul class="dashboard-box-list">
                    <?php /*?><th><?php D(__('revenue_page_Date',"Date"));?></th>
                
                    <th><?php D(__('revenue_page_For',"For"));?></th>
                
                    <th><?php D(__('revenue_page_Amount',"Amount"));?></th><?php */?>
                <?php
                
                if($all_revenue){
                
                foreach($all_revenue as $revenue){
                
                ?>
                
                <li>
                
                <div class="job-listing"> 
                
                <!-- Job Listing Details -->
                
                <div class="job-listing-details"> 
                
                  <!-- Details -->
                
                  <div class="job-listing-description">                    
                
                    <!-- Job Listing Footer -->
                
                    <div class="job-listing-footer">
                
                      <ul class="d-md-flex">
                
                        <li><i class="icon-feather-calendar"></i> 
                
                          <?php // D(__('revenue_page_Date',"Date"));?> <span><?php D(dateFormat($revenue->date,'F d, Y')) ; ?></span></li>
                
                        <li><i class="icon-feather-eye"></i> <b>
                
                          <?php D(__('revenue_page_For',"For"));?>:</b> <span><?php if($revenue->status != 1){ ?><?php D(__('revenue_page_Order_Revenue_Pending_Clearance',"Order Revenue Pending Clearance"));?> <?php }else{ ?><?php D(__('revenue_page_Order_Revenue',"Order Revenue"));?><?php } ?></span></li>
                
                        <li><i class="icon-feather-tag"></i> <b>
                
                          <?php D(__('revenue_page_Amount',"Amount"));?>:</b> <span>+<?php D(CURRENCY); ?><?php D(displayamount($revenue->amount)); ?></span></li>
                        
                      </ul>
                
                    </div>
                
                  </div>
                
                </div>
                
                </div>
                <div class="buttons-to-right single-right-button">
                <a href="<?php D(get_link('OrderDetailsURL').$revenue->order_id); ?>" target="blank" class="btn btn-sm btn-outline-dark"> <?php D(__('revenue_page_View_Order',"View Order"));?> </a>              
                </div>
                
                </li>
                
                        
                
                    <?php	}
                
                        }?>
                
                    </ul>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			<ul class="dashboard-box-list">
				<?php
				if($all_widthdraw){
					foreach($all_widthdraw as $transaction){
				?>
				<li>
					<div class="job-listing">
						<div class="job-listing-details">                        
							<div class="job-listing-description">
								<h5 class="job-listing-title"><?php D($transaction['wallet_transaction_id']) ; ?></h5>
								<div class="job-listing-footer">
									<ul>
										<li><i class="icon-feather-calendar"></i> <?php D(dateFormat($transaction['transaction_date'],'F d, Y')) ; ?> <?php D(date('H:i',strtotime($transaction['transaction_date']))) ; ?></li>
										<li><i class="icon-feather-tag"></i> <b><?php D(__('transaction_page_Amount','Amount'));?>:</b> 
											<span class="<?php D($clas)?>"><?php D(CURRENCY.displayamount($transaction['amount'])); ?></span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>  
                    <div class="buttons-to-right single-right-button">
                    	<?php if($transaction['status']==0){ ?>
                        <span class="dashboard-status-button yellow"><?php D(__('transaction_page_Pending','Pending'));?> </span>
                        <?php }elseif($transaction['status']== 1){?>
                        <span class="dashboard-status-button green"><?php D(__('transaction_page_Completed','Completed'));?></span>
                        <?php }else{?>
                        <span class="dashboard-status-button red"><?php D(__('transaction_page_Cancelled','Cancelled'));?></span>
                        <?php } ?>
                    </div>                  
                </li>
				<?php
					}
				}
				?>
			</ul>		
            </div>
		</div>
        </div>

		

</div>
</div>
</div>
</section>

<div id="mobile_money_modal" class="modal fade"><!-- mobile_money_modal modal fade Starts -->



<div class="modal-dialog"><!-- modal-dialog Starts -->



<div class="modal-content"><!-- modal-content Starts -->



<div class="modal-header"><!-- modal-header Starts -->



<h5 class="modal-title"> Withdraw To Mobile Money Account </h5>



<button type="button" class="close" data-dismiss="modal">



<span>&times;</span>



</button>



</div><!-- modal-header Ends -->



<div class="modal-body text-center"><!-- modal-body Starts -->



<?php if(empty($login_seller_account_number) or empty($login_seller_account_name)){ ?>



<p class="modal-lead">



For Withdraw Payments To Your Mobile Money Account Please Add Your Mobile Money Account Details In <a href="#" id="settings-link">Settings Tab</a>



</p>



<?php }else{ ?>



<p class="modal-lead">



Your Payments Will Be Sent To Follwing Mobile Money Account:



<p class="mb-1"> <strong> Account Number: </strong> <?php echo $login_seller_account_number; ?> </p>



<p> <strong> Account/Owner Name: </strong> <?php echo $login_seller_account_name; ?> </p>



</p>



<form action="withdraw.php" method="post"><!-- withdraw form Starts -->



<div class="form-group row"><!-- form-group Starts -->



<label class="col-md-3 col-form-label"> Amount: </label>



<div class="col-md-8">



<div class="input-group">



<span class="input-group-addon font-weight-bold"> $ </span>



<input type="number" name="amount" class="form-control" min="<?php D($withdrawal_limit); ?>" max="<?php D($member_details['member']->balance); ?>" placeholder="<?php D($withdrawal_limit); ?> Minimum" required>



<input type="hidden" name="withdraw_method" value="mobile_money">



</div>



</div>



</div><!-- form-group Ends -->



<div class="form-group row"><!-- form-group Starts -->



<div class="col-md-8 offset-md-3">



<input type="submit" name="withdraw" value="Withdraw" class="btn btn-primary form-control">



</div>



</div><!-- form-group Ends -->



</form><!-- withdraw form Ends -->



<?php } ?>



</div><!-- modal-body Ends -->



</div><!-- modal-content Ends -->



</div><!-- modal-dialog Ends -->



</div><!-- mobile_money_modal modal fade Ends -->







<div id="paypal_withdraw_modal" class="modal fade">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title"> <?php D(__('modal_paypal_withdraw_headng',"Withdraw/Transfer Funds To PayPal"));?> </h5>

				<button class="close" data-dismiss="modal">

				<span> &times; </span>

				</button>

			</div>

			<div class="modal-body"><!-- modal-body Starts -->

				

                <?php if($member_details['member_payment_settings'] && $member_details['member_payment_settings']->paypal_email){ ?>

					<p>

						<?php D(__('modal_paypal_withdraw_amount_transferred_to',"Your revenue funds will be transferred to:"));?>

						<strong> <?php D($member_details['member_payment_settings']->paypal_email); ?> </strong>

					</p>

					<form action="" method="post" id="paypalTranferForm" onsubmit="return performAction(this);return false;">

						<input type="hidden" name="action" value="paypal"/>

						<div class="form-group">

							<label class="form-label"><?php D(__('modal_paypal_withdraw_Amount',"Amount"));?></label>

							

								<div class="input-group">

									<div class="input-group-prepend"><span class="input-group-text"> <?php D(CURRENCY)?> </span></div>

									<input type="number" name="amount" id="amount" class="form-control"  placeholder="<?php D($withdrawal_limit); ?> <?php D(__('modal_paypal_withdraw_Minimum',"Minimum"));?>" >

								</div>

								<span id="amountError" class="rerror pull-left text-left"></span>

						    

						</div>

						<button type="submit" class="btn btn-site saveBTN"><?php D(__('modal_paypal_withdraw_Transfer',"Transfer"));?></button>

					</form>

                    <?php }else{ ?>

					<p>

						<?php D(__('modal_paypal_withdraw_Transfer_paypal_no_details',"In order to transfer funds to your PayPal account, you will need to add your PayPal email in your"));?>

						<a href="<?php D(get_link('settingsURL'))?>?tab=account" class="text-success">

						<?php D(__('modal_paypal_withdraw_account_settings',"account settings"));?> 

						</a>

						<?php D(__('modal_paypal_withdraw_tab',"tab."));?> 

					</p>

                    <?php } ?>

				



			</div>			



		</div>



	</div>

</div>

<div id="payoneer_withdraw_modal" class="modal fade">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title"> <?php D(__('modal_payoneer_withdraw_headng',"Withdraw/Transfer Funds To Payoneer"));?> </h5>

				<button class="close" data-dismiss="modal">

				<span> &times; </span>

				</button>

			</div>

			<div class="modal-body"><!-- modal-body Starts -->

                <?php if($member_details['member_payment_settings'] && $member_details['member_payment_settings']->payoneer_email){ ?>

					<p>

						<?php D(__('modal_payoneer_withdraw_transferred_to',"Your revenue funds will be transferred to:"));?>

						<strong> <?php D($member_details['member_payment_settings']->payoneer_email); ?> </strong>

					</p>

					<form action="" method="post" id="payoneerTranferForm" onsubmit="return performAction(this);return false;">

						<input type="hidden" name="action" value="payoneer"/>

						<div class="form-group">

							<label class="form-label">

							<?php D(__('modal_payoneer_withdraw_Amount',"Amount"));?>

							</label>

								<div class="input-group">

									<div class="input-group-prepend"><span class="input-group-text"> <?php D(CURRENCY)?> </span></div>

									<input type="number" name="amount" id="amount" class="form-control"  placeholder="<?php D($withdrawal_limit); ?> <?php D(__('modal_payoneer_withdraw_Minimum',"Minimum"));?>" >

								</div>

								<span id="amountError" class="rerror pull-left text-left"></span>

						</div>

						<button type="submit" class="btn btn-site saveBTN"><?php D(__('modal_payoneer_withdraw_Transfer',"Transfer"));?></button>

					</form>

                    <?php }else{ ?>

					<p>

						<?php D(__('modal_payoneer_withdraw_Transfer_payoneer_no_details',"In order to transfer funds to your Payoneer account, you will need to add your Payoneer email in your"));?>

						<a href="<?php D(get_link('settingsURL'))?>?tab=account" class="text-success">

						<?php D(__('modal_paypal_withdraw_account_settings',"account settings"));?> 

						</a>

						<?php D(__('modal_paypal_withdraw_tab',"tab."));?>

					</p>

                    <?php } ?>

				</center>



			</div>

		</div>



	</div>

</div>

<div id="bank_withdraw_modal" class="modal fade">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title"> <?php D(__('modal_bank_withdraw_headng',"Withdraw/Transfer Funds To Bank"));?> </h5>

				<button class="close" data-dismiss="modal">

				<span> &times; </span>

				</button>

			</div>

			<div class="modal-body"><!-- modal-body Starts -->

                <?php if($member_details['member_payment_settings'] && $member_details['member_payment_settings']->bank_account_number){ ?>

					<p>

						<?php D(__('modal_bank_withdraw_transferred_to',"Your revenue funds will be transferred to:"));?>

						 <strong> <?php D($member_details['member_payment_settings']->bank_account_number); ?> </strong>

					</p>

					<form action="" method="post" id="bankTranferForm" onsubmit="return performAction(this);return false;">

						<input type="hidden" name="action" value="bank"/>

						<div class="form-group">

							<label class="form-label"><?php D(__('modal_bank_withdraw_Amount',"Amount"));?></label>

							

								<div class="input-group">

									<div class="input-group-prepend"><span class="input-group-text"> <?php D(CURRENCY)?> </span></div>

									<input type="number" name="amount" id="amount" class="form-control"  placeholder="<?php D($withdrawal_limit); ?> <?php D(__('modal_bank_withdraw_Minimum',"Minimum"));?>" >

								</div>

								<span id="amountError" class="rerror pull-left text-left"></span>

						    

						</div>

						<button type="submit" class="btn btn-site saveBTN"><?php D(__('modal_bank_withdraw_Transfer',"Transfer"));?></button>

					</form>

                    <?php }else{ ?>

					<p class="lead">

						<?php D(__('modal_bank_withdraw_Transfer_bank_no_details',"In order to transfer funds to your bank account, you will need to add your Bank detaila in your"));?>

						<a href="<?php D(get_link('settingsURL'))?>?tab=account" class="text-success">

						<?php D(__('modal_bank_withdraw_account_settings',"account settings"));?> 

						</a>

						<?php D(__('modal_bank_withdraw_tab',"tab."));?>

					</p>

                    <?php } ?>



			</div>

		</div>



	</div>

</div>		

<div id="trx_wallet_modal" class="modal fade">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

			<h5 class="modal-title"> Withdraw/Transfer Funds To Bitcoin Wallet </h5>

			<button class="close" data-dismiss="modal"><span>&times;</span></button>

			</div>

			<div class="modal-body"><!-- modal-body Starts -->

			<center><!-- center Starts -->

				<?php if(empty($login_seller_wallet)){ ?>

				<p class="lead">

				In order to transfer funds to your bitcoin wallet, you will need to add your wallet address in your

				<a href="<?php D(get_link('settingsURL'))?>?tab=account" class="text-success">

				account settings 

				</a>

				tab.

				</p>

				<?php }else{ ?>

				<p class="lead">

				Your revenue funds will be transferred to:

				<br> <strong> <?php echo $login_seller_wallet; ?> </strong>

				</p>

				<form action="withdraw_wallet" method="post">

					<div class="form-group row">

					<label class="col-md-3 col-form-label font-weight-bold">Amount</label>

						<div class="col-md-8">

							<div class="input-group">

							<span class="input-group-addon font-weight-bold"> $ </span>

							<input type="number" name="amount" class="form-control input-lg" min="<?php D($withdrawal_limit); ?>" max="<?php D($member_details['member']->balance); ?>" placeholder="<?php D($withdrawal_limit); ?> Minimum" required >

							</div>

					    </div>

					</div>

					<div class="form-group row">

						<div class="col-md-8 offset-md-3">

						 <input type="submit" name="withdraw" value="Transfer" class="btn btn-success form-control">

						</div>

					</div>

			 	</form>

      <?php } ?>

			</center>

			</div>

			<div class="modal-footer">

			<button class="btn btn-secondary" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div>

<script>

var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

	function performAction(ev){

	var formID=$(ev).attr('id');

	var modal=$(ev).closest('.modal');

	var buttonsection=$('#'+formID).find('.saveBTN');

	var forminput=$('#'+formID).serialize();

	

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('transferActionURLAJAX'))?>",

        data:forminput,

        dataType: "json",

        cache: false,

		success: function(msg) {

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				$(modal).modal('hide');

				var message='<?php D(__('popup_withdrawn_request_success_message',"Your request has been submitted successfully!"));?>';

				if(msg['message']){

					message=msg['message'];

				}

				 swal({

                  type: 'success',

                  text: message,

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	if(msg['redirect']){

						window.location.href=msg['redirect'];

					}

                  	

                })	

			} else if (msg['status'] == 'FAIL') {

				registerFormPostResponse(formID,msg['errors']);

			}

		}

	})

	

	return false;

}

</script>
<!--
<script>

// Fun Facts

	function funFacts() {

		/*jslint bitwise: true */

		function hexToRgbA(hex){

		    var c;

		    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){

		        c= hex.substring(1).split('');

		        if(c.length== 3){

		            c= [c[0], c[0], c[1], c[1], c[2], c[2]];

		        }

		        c= '0x'+c.join('');

		        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',0.07)';

		    }

		}



		$(".fun-fact").each(function() {

			var factColor = $(this).attr('data-fun-fact-color');



	        if(factColor !== undefined) {

	        	$(this).find(".fun-fact-icon").css('background-color', hexToRgbA(factColor));

	            $(this).find("i").css('color', factColor);

	        }

		});



	} funFacts();

</script>
-->