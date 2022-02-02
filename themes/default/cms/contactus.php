<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($filter);
?>

<div class="breadcrumbs">
  <div class="container">
  	<h1><?php D(__('contact_us_page_heading','Contact Us'));?></h1>    
  </div>
</div>
<section class="section">
<div class="container">
  <div class="row">
    <aside class="col-md-8 col-12">      
      <div class="card mb-4" id="contact-form">
      <div class="card-header"><h4><?php D(__('contact_us_page_Send_Us_an_Email','Send Us an Email'));?></h4></div>
      <div class="card-body">
        <p><?php D(__('contact_us_page_Send_Us_an_Email_text_1','Feel free to talk to our online representative at any time you please using our Live Chat system on our website or one of the below instant messaging programs.'));?></p>
        <p><?php D(__('contact_us_page_Send_Us_an_Email_text_2','Please be patient while waiting for response. (24/7 Support!)'));?></p>
     
        <div class="clearfix"></div>
        
      </div>
      </div>
    </aside>
    <aside class="col-md-4 col-12"> 
      <div class="job-overview mb-4">
        <div class="job-overview-headline"><h4><?php D(__('contact_us_page_Head_Office','Head Office'));?></h4></div>
        <div class="job-overview-inner">
            <ul>
                <li>
                    <i class="icon-feather-map-pin"></i>
                    <span><?php D(__('contact_us_page_Address','Address:'));?></span>
                    <h5>Lorem Ipsum 
Lorem Ipsum
Lorem Ipsum, India</h5>
                </li>
                <li>
                    <i class="icon-feather-mail"></i>
                    <span><?php D(__('contact_us_page_Email','Email:'));?></span>
                    <h5><a href="mailto:help@echodeveloper.com"> help@LoremIpsum.com </a></h5>
                </li>
            </ul>
        </div>
        </div>
                         
      <div class="card mb-4">
      <div class="card-header"><h4><?php D(__('contact_us_page_Follow_Us','Follow Us'));?></h4></div>
      <div class="card-body">
        <?php /*?><div class="contact-info widget">
           <ul>
              <li><i class="icon-time"> </i>Monday - Friday 9am to 5pm </li>
              <li><i class="icon-time"> </i>Saturday - 9am to 2pm</li>
              <li><i class="icon-remove-circle"> </i>Sunday - Closed</li>
           </ul>
        </div><?php */?>
        
        <div class="follow">          
          <ul class="social-icons icons-A icon-circle">
            <li><a class="dribbble" href="<?php D(get_option_value('facebook_url'))?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
            <li><a class="twitter" href="<?php D(get_option_value('twitter_url'))?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
            <li><a class="linkedin" href="<?php D(get_option_value('linkedin_url'))?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
            <li><a class="youtube" href="<?php D(get_option_value('youtube_url'))?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
            <!--<li><a class="pinterest" href="http://www.pinterest.com/" target="_blank"><i class="fa fa-pinterest"></i></a></li>-->
          </ul>
        </div>
        </div>
      </div>
    </aside>
  </div>
	<div class="card">
	<div class="card-header"><h4 class="title-sm"><?php D(__('contact_us_page_Our_Location','Our Location'));?></h4></div>
    <div class="card-body">
      <div id="maps" class="google-maps">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d471220.5630409112!2d88.04953608529773!3d22.675752093263746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f882db4908f667%3A0x43e330e68f6c2cbc!2sKolkata%2C%20West%20Bengal!5e0!3m2!1sen!2sin!4v1580562731612!5m2!1sen!2sin" width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        
      </div>
	</div> 
    </div>      
	</div>
</section>