<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($cart,TRUE);
$p=0;
?>
<div class="container mt-5 mb-3">
	<div class="row">
		<div class="col-md-12">
			<div class="card mb-3">
				<div class="card-body">
					<h5 class="float-left mt-2"> <?php D(__('cart_page_your_cart',"Your Cart"));?> (<?php D(count($cart)); ?>) </h5>
					<h5 class="float-right">
						<a class="btn btn-success" href="<?php D(get_link('homeURL'))?>"> <?php D(__('cart_page_Continue_Shopping',"Continue Shopping"));?></a>
					</h5>
				</div>
             </div>
		</div>
	</div>
	<div class="row cart-add-sect" id="cart-show">
		<div class="col-md-7">
			<div class="card mb-3">
				<div class="card-body">
                <?php
                    $total = 0;
					if($cart){
						foreach($cart as $c=>$cartdata){
							$username=getUserName($cartdata->proposal_seller_id);
							if($cartdata->package_id){
								$proposal_price=$cartdata->price;
							}else{
								$proposal_price=$cartdata->proposal_price;
							}
							if($cartdata->extra){
								foreach($cartdata->extra as $extra){
									$allextra[]=ucfirst($extra->name).' ('.CURRENCY.$extra->price.')';
									$proposal_price+=$extra->price;
								}	
							}
							$sub_total = $proposal_price * $cartdata->qty;
							$total += $sub_total;
				?>
					<div class="cart-proposal">
						<div class="row">
							<div class="col-lg-3 mb-2">
								<a href="<?php D(get_link('ProposalDetailsURL'))?>/<?php D($username); ?>/<?php D($cartdata->proposal_url); ?>">
									<img src="<?php D(URL_USERUPLOAD) ?>proposal-files/<?php D($cartdata->proposal_image); ?>" class="img-fluid">
								</a>
							</div>
							<div class="col-lg-9">
								<a href="<?php D(get_link('ProposalDetailsURL'))?>/<?php D($username); ?>/<?php D($cartdata->proposal_url); ?>">
									<h6 class="text-success make-black"><?php D(ucfirst($cartdata->proposal_title)); ?> </h6>
								</a>
								<a href="<?php D(VZ)?>" onclick="deleteCart('<?php D($cartdata->proposal_id); ?>')" class=" text-muted remove-link">
									<i class="fa fa-times"></i> <?php D(__('cart_page_Remove_Proposal',"Remove Proposal"));?>
								</a>
								<?php
								if($cartdata->package_id){
								?>
								<div><?php D($cartdata->package_name)?> <?php D(__('cart_page_package',"package"));?></div>
								<?php	
								}
								?>
								<?php
								if($cartdata->extra){
									?>
									<small><?php D(implode(', ',$allextra));?></small>
									<?php
								}
								?>
							</div>
						</div>
						<hr>
						<h6 class="clearfix">
							<?php D(__('cart_page_Proposal_or_Service_Quantity',"Proposal/Service Quantity"));?>
							<strong class="float-right price ml-2 mt-2"> <?php D(CURRENCY); ?><?php D($sub_total); ?> </strong>
							<input type="text" name="quantity" class="float-right form-control quantity" min="1" data-proposal_id="<?php D($cartdata->proposal_id); ?>" value="<?php D($cartdata->qty); ?>">
						</h6>
						<hr>
					</div>
				<?php			
						}
					}		
                ?>
				<h3 class="float-right"><?php D(__('cart_page_Total',"Total"));?> <?php D(CURRENCY); ?><?php D($total); ?> </h3>
				</div>
			</div>
            <?php
            if(count($cart)== 0){
            ?>
            <center><h3 class='pt-5'><i class='fa fa-meh-o'></i> <?php D(__('cart_page_empty_cart',"Your cart is empty"));?></h3></center>
            <?php
            }?>
		</div>
		<div class="col-md-5">
			<div class="card">
				<div class="card-body cart-order-details">
					<p><?php D(__('cart_page_Cart_Subtotal',"Cart Subtotal"));?> <span class="float-right"><?php echo $s_currency; ?><?php D($total); ?></span></p>
					<!--<hr>
					<p>Apply Coupon Code</p>
					<form class="input-group" method="post">
						<input type="text" name="code" class="form-control apply-disabled" placeholder="Enter Coupon Code">
						<button type="submit" name="coupon_submit" class="input-group-addon btn btn-success"> 
							Apply
						</button>
					</form>-->
					<!--<?php if($cartdata->coupon){?>
					<p class="coupon-response mt-2 p-2 bg-success text-white">Your coupon code has been applied successfully.</p>
					<?php }?>
					<hr>-->
					<hr>

					<p><?php D(__('cart_page_Total',"Total"));?> <span class="font-weight-bold float-right"><?php D(CURRENCY); ?><?php D($total);?> </span></p>
					<hr>
					<a href="<?php D(get_link('CartURL'))?>?option=payment_options" class="btn btn-lg btn-success btn-block">
						<?php D(__('cart_page_Proceed_To_Payment',"Proceed To Payment"));?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function deleteCart(proposal_id){
		$.ajax({
			url: "<?php D(get_link('actionCartCheckAJAXURL'))?>",
			method: "POST",
			data: {proposal_id: proposal_id, action:'delete'},
			success: function(data){
				window.location.reload();
			}	
		});
	}
$(document).on('keyup','.quantity', function(){	
var value = parseInt($(this).val(), 10);
var min = parseInt($(this).attr("min"), 10);
if(value < min){
value = min;
$(this).val(value);
}
if (/\D/g.test($(this).value)){ $(this).val(this.value.replace(/\D/g,'1')); }
var seller_id = "<?php echo $login_seller_id; ?>";
var proposal_id = $(this).data("proposal_id");
var quantity = $(this).val();
if(quantity != ""){
$.ajax({
	url: "<?php D(get_link('actionCartCheckAJAXURL'))?>",
	method: "POST",
	data: {proposal_id: proposal_id, proposal_qty: quantity,action:'cartupdate'},
	success: function(data){
		window.location.reload();
	}	
});	
}
});	
</script>