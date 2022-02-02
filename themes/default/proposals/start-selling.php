<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="breadcrumbs">
  <div class="container">
    <h1 class="mt-3">
      <?php D(__('create_proposal_page_heading','Become A Freelancer On Our Platform'))?>
    </h1>
    <p>
      <?php D(__('create_proposal_page_heading_info',"You bring the skill. We'll make earnings as easy as 1,2,3"))?>
    </p>
    <?php
  if($is_login==1){
?>
    <a href="<?php D(get_link('postproposalURL'))?>" class="btn btn-site mb-3"><i class="icon-feather-file" aria-hidden="true"></i>
    <?php D(__('create_proposal_page_create_proposal_button','Create A Proposal'))?>
    </a>
    <?php 
}else{
?>
    <button data-toggle="modal" data-target="#register-modal" class="btn btn-site mb-3"><i class="icon-feather-user" aria-hidden="true"></i>
    <?php D(__('create_proposal_page_create_account_button','Create An Account'))?>
    </button>
    <?php } ?>
  </div>
</div>
<section class="section">
  <div class="container">
    <div class="section-headline">
        <h2><?php D(__('create_proposal_page_how_it_works','How Does This Work?'))?></h2>
        <h5>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet</h5>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
          <i class="icon-feather-file mb-3 icon-lg d-block"></i>
            <h4>
              <?php D(__('create_proposal_page_how_it_works_create_proposal','Create a Proposal'))?>
            </h4>
            <p>
              <?php D(__('create_proposal_page_how_it_works_create_proposal_info',"Once you create an account, all you have to do to become a seller is to create a proposal/service. Once the proposal has been approved by the admin, you're automatically a seller and can start earning."))?>
            </p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
      	<div class="card">
		<div class="card-body text-center">
      	<i class="icon-feather-shopping-cart mb-3 icon-lg d-block"></i>
        <h4><?php D(__('create_proposal_page_how_it_works_deliver_work','Deliver Great Work'))?></h4>
        <p>
          <?php D(__('create_proposal_page_how_it_works_deliver_work_info',"Get notified when you get an order and use our system to discuss details with customers. If the customer loves what you've created, he or she will rate you 5 stars."))?>
        </p>
        </div>
        </div>
      </div>
      
      <div class="col-md-4">
      <div class="card">
		<div class="card-body text-center">
      	<i class="icon-material-outline-account-balance-wallet mb-3 icon-lg d-block"></i>
        <h4><?php D(__('create_proposal_page_how_it_works_get_paid','Get Paid. On Time.'))?></h4>
        <p>
          <?php D(__('create_proposal_page_how_it_works_get_paid_info',"Get paid on time, every time. Payment is transferred to you upon order completion. Our system lets you transfer funds from our system to your PayPal account."))?>
        </p>
        </div>
        </div>
      </div>
    </div>
  </div>
</section>