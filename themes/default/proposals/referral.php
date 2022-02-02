<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($all_referrals,TRUE);
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-10 col-md-10 mt-5 mb-5">
			<div class="card rounded-0">
				<div class="card-body">
					<h1> My Proposal Referrals </h1>
					<p>
					Here, you can track all the proposals you've successfully promoted and the commissions you've awarded for promoting them.
					</p>
					<p class="lead text-danger">
					Note: If we decide that a proposal referral is incorrect or fraudulent, it will be declined and you will not receive any funds for it.
					</p>
					<div class="row">
						<div class="col-md-4 mb-3">
							<div class="card text-white border-success">
								<div class="card-header text-center bg-success">
									<div class="display-4"> 
										<?php D(CURRENCY); ?>
										<?php $total=0;
										if($approved_referrals && $approved_referrals->total){$total=$approved_referrals->total;}
										D($total);
										?>
									</div>
									<div class="font-weight-bold">
										Approved <small> Earnings </small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="card text-white border-secondary">
								<div class="card-header text-center bg-secondary">
									<div class="display-4"> 
									<?php D(CURRENCY); ?>
									<?php $total=0;
									if($pending_referrals && $pending_referrals->total){$total=$pending_referrals->total;}
									D($total);
									?>
									</div>
									<div class="font-weight-bold">
										Pending
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="card text-white border-danger">
								<div class="card-header text-center bg-danger">
									<div class="display-4">
									<?php D(CURRENCY); ?>
									<?php $total=0;
									if($declined_referrals && $declined_referrals->total){$total=$declined_referrals->total;}
									D($total);
									?>
									</div>
									<div class="font-weight-bold">
										Declined
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive border border-secondary rounded" style="overflow-x:hidden; overflow-y:hidden;">
						<table class="table table-bordered">
							<thead>
								<tr class="card-header">
									<th>Owner</th>
									<th>Buyer</th>
									<th>Proposal</th>
									<th>Purchase Date</th>
									<th>Your Commision</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
					<?php
					if($all_referrals){
						foreach($all_referrals as $r=>$referral){
							$cls="";
							$status="";
							if($referral->status == "1"){
								$cls="text-success";
								$status='Approved';
							}elseif($referral->status == "0"){
								$cls="text-secondary";
								$status='Pending';
							}elseif($referral->status == "2"){
								$cls="text-danger";
								$status='Declined';
							}
					?>
								<tr>
									<td><?php D($referral->seller_name); ?></td>
									<td><?php D($referral->buyer_name); ?></td>
									<td><?php D($referral->proposal_title); ?></td>
									<td><?php D(dateFormat($referral->date,'F d, Y')); ?></td>
									<td><?php D(CURRENCY); ?><?php D($referral->comission); ?></td>
									<td class="font-weight-bold <?php D($cls);?>"> <?php D($status); ?> 
									</td>
								</tr>
					<?php
						}	
					}else{
					?>
								<tr>
									<td class='text-center' colspan='6'>
										<h3 class='pb-2 pt-2'><i class='fa fa-meh-o'></i> Nothing to display at the moment.</h3>
									</td>
								</tr>
					<?php	
					}
					?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>