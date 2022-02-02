<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//dd($filter);

?>

<div class="breadcrumbs" id="how_to">
  <div class="container">
    <h1>
      <?php D(__('how_it_works','How it works?'))?>
    </h1>
    <?php /*?><h5><?php D(__('how_it_works_page_sub_heading','Secure, innovative and user friendly platform for buying and selling online services/proposals.'));?></h5><?php */?>
    <!-- <ul>Home </p> -->
  </div>
</div>
<!-- HOW IT WORKS -->
<?php /*
<section class="section how-it-works">
  <div class="container">
    <div class="section-headline">
      <h2>
        
      </h2>
      
    </div>
    <div class="row">
      <?php
	if($boxes){
	foreach($boxes as $k=>$box){
	?>
      <div class="col-md-4 col-12">
        <div class="icon-box with-line"> 
          <!-- Icon -->
          <div class="icon-box-circle">
            <div class="icon-box-circle-inner"> <img src="<?php D(URL_USERUPLOAD.'box/')?><?php D($box->box_image);?>" class="mb-3" alt="<?php D($box->name);?>" height="96" width="96">
              <div class="icon-box-check">0<?php echo ($k+1);?></div>
            </div>
          </div>
          <h4>
            <?php D($box->name);?>
          </h4>
          <p>
            <?php D($box->description);?>
          </p>
        </div>
      </div>
      <?php	
	}
	}
	?>
    </div>
  </div>
</section>
<?php */  ?>
<section class="section">
  <div class="container text-center">
    <ul class="nav nav-tabs mb-0 justify-content-center toggle-nav" id="myTab" role="tablist">
      <li class="nav-item"><a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">For Buyers</a></li>
      <li class="nav-item"><a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">For Sellers</a></li>
    </ul>
  </div>
</section>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <section class="section">
      <div class="container">
        <div class="row align-items-center">
          <aside class="col-md-6 col-12 order-md-2"><img src="<?php D(theme_url().IMAGE);?>employer-graphics.png" alt="" class="img-fluid" /> </aside>
          <aside class="col-md-6 col-12 order-md-1">
            <div class="paragraph">
              <h4>Easily</h4>
              <h2 class="title">Find quality freelancers</h2>
              <h5>On Flance you'll find a range of top talent, from programmers to designers, writers, customer support reps, and more. </h5>
              <p>• Start by posting a job. Tell us about your project and the specific skills required. Flance analyses your needs. Our search functionality uses data science to highlight freelancers based on their skills, helping you find talent that's a good match. </p>
              <p>• We send you a shortlist of likely candidates. You can also search our site for talent, and freelancers can view your job and submit proposals too.</p>
              <p><i>We have several measures in place to ensure Flance is a fair and reliable marketplace. We use multiple means to verify that freelancers are who they say they are. Information is also displayed that gives you a sense of each person's skill level. In part, this includes:</i></p>
            </div>
          </aside>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="row align-items-center">
          <aside class="col-md-6 col-12"> <img src="<?php D(theme_url().IMAGE);?>employer-graphics.png" alt="" class="img-fluid" /> </aside>
          <aside class="col-md-6 col-12">
            <div class="paragraph">
              <h4>Hire</h4>
              <h2 class="title">Hire the best freelancer</h2>
              <h5><b>Invite favorite candidates to submit bids, then review and hire your favorite. </b></h5>
              <p>• <b>Browse profiles:</b> View finalists' Flance profiles to see client ratings, portfolios, Job Success scores, and more. </p>
              <p>• <b>Review proposals:</b> Evaluate bids, taking into account each freelancer's qualifications, thought process, timeline, and overall cost.</p>
              <p>• Schedule a chat. Ask specific questions, determine who the best fit is, and contract. </p>
            </div>
          </aside>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="row align-items-center">
          <aside class="col-md-6 col-12 order-md-2"> <img src="<?php D(theme_url().IMAGE);?>employer-graphics.png" alt="" class="img-fluid" /> </aside>
          <aside class="col-md-6 col-12 order-md-1">
            <div class="paragraph">
              <h4>Work</h4>
              <h2 class="title">Work efficiently, effectively.</h2>
              <h5><b>Each project includes an online workspace shared by your team and your freelancer, allowing you to: </b></h5>
              <p>• Send and receive files. Deliver digital assets in a secure environment. </p>
              <p>• Share feedback in real time. Use Flance Messages to communicate via text or chat.</p>
              <h5><b>How do I know my freelancer is accurately billing for my project?</b></h5>
              <p>Built into Flance are several methods of verifying work. On hourly contracts, you can review the Work Diary. It tracks billable time and records completed work. While the freelancer is billing you it also counts keystrokes and takes screenshots of the freelancer's screen (six times per hour), so you can verify billable hours. On fixed-price jobs, you and your freelancer agree on milestones for each project. When your freelancer reaches a milestone, you review the work and release funds when the work is approved.</p>
            </div>
          </aside>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="row align-items-center">
          <aside class="col-md-6 col-12"> <img src="<?php D(theme_url().IMAGE);?>employer-graphics.png" alt="" class="img-fluid" /> </aside>
          <aside class="col-md-6 col-12">
            <div class="paragraph">
              <h4>Pay</h4>
              <h2 class="title">Pay easily, with peace of mind</h2>
              <h5><b>Pay your freelancer by the hour, or a fixed price for the entire project. On fixed-price jobs, use our licensed escrow service to release funds as pre-set milestones are met. </b></h5>
              <p>• <b>Simplified global payments:</b> We deliver payments to freelancers in over 170 countries. </p>
              <p>• <b>Includes Flance Payment Protection:</b> Only pay for work you authorize. </p>
              <p>• <b>Invoicing and reporting:</b> Access your invoices and transaction history on Flance. </p>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <section class="section">
      <div class="container">
        <div class="row align-items-center">
          <aside class="col-md-6 col-12"> <img src="<?php D(theme_url().IMAGE);?>headline-login.png" alt="" class="img-fluid" /> </aside>
          <aside class="col-md-6 col-12">
            <div class="paragraph">
              <h4>Find</h4>
              <h2 class="title">Find rewarding projects</h2>
              <h5><b>Flance is a great place to find more clients, and to run and grow your own freelance business. </b></h5>
              <p>• <b>Freedom to work on ideal projects:?</b> On Flance, you run your own business and choose your own clients and projects. Just complete your profile and we'll highlight ideal jobs. Also search projects, and respond to client invitations. </p>
              <p>• <b>Wide variety and high pay:</b> Clients are now posting jobs in hundreds of skill categories, paying top price for great work. </p>
              <p>• <b>More and more success:</b> The greater the success you have on projects, the more likely you are to get hired by clients that use Flance. </p>
            </div>
          </aside>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="row align-items-center">
          <aside class="col-md-6 col-12 order-md-2"> <img src="<?php D(theme_url().IMAGE);?>headline-login.png" alt="" class="img-fluid" /> </aside>
          <aside class="col-md-6 col-12 order-md-1">
            <div class="paragraph">
              <h4>Get hired quickly</h4>
              <h2 class="title">Get hired quickly</h2>
              <h5><b>Flance makes it easy to connect with clients and begin doing great work. </b></h5>
              <p>• <b>Streamlined hiring:</b> Flance's sophisticated algorithms highlight projects you're a great fit for. </p>
              <p>• <b>Top Rated and Rising Talent programs:</b> Enjoy higher visibility with the added status of prestigious programs. </p>
              <p>• <b>Do substantial work with top clients:</b> Flance pricing encourages freelancers to use Flance for repeat relationships with their clients. </p>
            </div>
          </aside>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="row align-items-center">
          <aside class="col-md-6 col-12"> <img src="<?php D(theme_url().IMAGE);?>headline-login.png" alt="" class="img-fluid" /> </aside>
          <aside class="col-md-6 col-12">
            <div class="paragraph">
              <h4>Work</h4>
              <h2 class="title">Work efficiently, effectively.</h2>
              <h5><b>With Flance, you have the freedom and flexibility to control when, where, and how you work. Each project includes an online workspace shared by you and your client, allowing you to: </b></h5>
              <p>• <b>Send and receive files:</b> Deliver digital assets in a secure environment.</p>
              <p>• <b>Share feedback in real time:</b> Use Flance Messages to communicate via text or chat. </p>
              <h5><b>How does Flance's platform help me manage my work? </b></h5>
              <p>Flance provides a user-friendly platform to help you chat and share files, track your time, and get paid.</p>
            </div>
          </aside>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="row align-items-center">
          <aside class="col-md-6 col-12 order-md-2"> <img src="<?php D(theme_url().IMAGE);?>headline-login.png" alt="" class="img-fluid" /> </aside>
          <aside class="col-md-6 col-12 order-md-1">
            <div class="paragraph">
              <h4>Get paid</h4>
              <h2 class="title">Get paid on time</h2>
              <h5><b>All projects include Flance Payment Protection — helping ensure that you get paid for all work successfully completed through the freelancing website. </b></h5>
              <p><b>•	All invoices and payments happen through Flance:</b> Count on a simple and streamlined process. </p>
              <p><b>•	Hourly and fixed-price projects:</b> For hourly work, submit timesheets through Flance. For fixed-price jobs, set milestones and funds are released via Flance escrow features. </p>
              <p><b>•	Multiple payment options:</b> Choose a payment method that works best for you, from direct deposit or PayPal to wire transfer and more. </p>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </div>
</div>
<style>

ul.iList {

	font-style:italic;

	padding-left:20px;

}

ul.iList li {

	list-style:circle

}

</style>
