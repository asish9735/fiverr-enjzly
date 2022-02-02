<?php
$loggedUser=$this->session->userdata('loggedUser');	
?>
<!-- Footer
================================================== -->
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-md-3 col-6">
        
      <h3>CATEGORIES</h3>

      <ul class="list-unstyled">
      
                <li class="list-unstyled-item"><a href="/category.php?cat_id=1">Graphics &amp; Design</a></li>
      
                  <li class="list-unstyled-item"><a href="/category.php?cat_id=2">Digital Marketing</a></li>
      
                  <li class="list-unstyled-item"><a href="/category.php?cat_id=3">Writing &amp; Translation
</a></li>
      
                  <li class="list-unstyled-item"><a href="/category.php?cat_id=4">Video &amp; Animation
</a></li>
      
              
      </ul>
      
      </div>

      <div class="col-md-2 col-6">
        <h3>ABOUT</h3>
        <ul class="list-unstyled">
          
                 <li class="list-unstyled-item"><a href="/terms_and_conditions.php"><i class="fa fa-file-text-o"></i> Terms &amp; Conditions</a></li>
                 <li class="list-unstyled-item"><a href="/customer_support.php"><i class="fa fa-comments"></i> Customer Support</a></li>
                 <li class="list-unstyled-item"><a href="/how-it-works.php"><i class="fa fa-question-circle"></i> How It Works</a></li>
                 <li class="list-unstyled-item"><a href="/knowledge_bank.php"><i class="fa fa-book"></i> Knowledge Bank</a></li>
                 
        </ul>
      </div>

      <div class="col-md-3 col-6">
        <h3>CATEGORIES</h3>
        <ul class="list-unstyled">

          <li class="list-unstyled-item"><a href="/category.php?cat_id=5">Music &amp; Audio
</a></li>
          <li class="list-unstyled-item"><a href="/category.php?cat_id=6">Programming &amp; Tech
</a></li>
          <li class="list-unstyled-item"><a href="/category.php?cat_id=7">Business
</a></li>
          <li class="list-unstyled-item"><a href="/category.php?cat_id=8">Fun &amp; Lifestyle
</a></li>
  
        </ul>
      </div>

      <div class="col-md-4 col-6">
        <h3>FIND US ON</h3>
        <ul class="list-inline social_icon">
  
    
          <li class="list-inline-item"><a href="#"><i class="fa fa-google-plus-official"></i></a></li>
      
    
          <li class="list-inline-item"><a href="#"><i class="fa fa-twitter"></i></a></li>
      
    
          <li class="list-inline-item"><a href="#"><i class="fa fa-facebook"></i></a></li>
      
    
          <li class="list-inline-item"><a href="#"><i class="fa fa-linkedin"></i></a></li>
      
    
          <li class="list-inline-item"><a href="#"><i class="fa fa-pinterest"></i></a></li>
      
    
  </ul>

  <div class="form-group mt-1">
  
  <div class="ddOutOfVision" id="languageSelect_msddHolder" style="height: 0px; overflow: hidden; position: absolute;"><select id="languageSelect" class="form-control" tabindex="-1">

    
  <option data-image="http://localhost:7777/gigtodo/languages/images/english.png" data-url="http://localhost:7777/gigtodo/change_language?id=1" selected="">

  English  
  </option>
  
    
  </select></div><div class="dd ddcommon borderRadius" id="languageSelect_msdd" tabindex="0" style="width: 370px;"><div class="ddTitle borderRadiusTp"><span class="divider"></span><span class="ddArrow arrowoff"></span><span class="ddTitleText " id="languageSelect_title"><img src="http://localhost:7777/gigtodo/languages/images/english.png" class="fnone"><span class="ddlabel">English</span><span class="description" style="display: none;"></span></span></div><input id="languageSelect_titleText" type="text" autocomplete="off" class="text shadow borderRadius" style="display: none;"><div class="ddChild ddchild_ border shadow" id="languageSelect_child" style="z-index: 9999; display: none; position: absolute; visibility: visible; height: 37px;"><ul><li class="enabled _msddli_ selected"><img src="http://localhost:7777/gigtodo/languages/images/english.png" class="fnone"><span class="ddlabel">English</span><div class="clear"></div></li></ul></div></div>
  
  </div>

        <h5>Mobile Apps (Coming soon!)</h5>
        <img src="http://localhost:7777/gigtodo/images/google.png" class="pic">
  
        <img src="http://localhost:7777/gigtodo/images/app.png" class="pic1">
  
      </div>

    </div>
  </div>
<br>
</footer>
<section class="post_footer">

 
</section>
<section class="messagePopup animated slideInRight">
</section>
<!-- Wrapper / End -->

<?php
if(!$loggedUser){
	$data=array(
	'referral'=>(($this->input->get('referral')) ? $this->input->get('referral'):''),
	'enable_social_login'=>get_option_value('enable_social_login'),
	);
	$templateLayout=array('view'=>'accesspanel/access-modal','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
	load_template($templateLayout,$data);
}
?>
<!-- Scripts
================================================== -->
<?php
$this->minify->add_css('msdropdown.css');
echo $this->minify->deploy_css(FALSE, 'footer.min.css');

$this->minify->js(array('msdropdown.js', 'jquery.sticky.js','customjs.js','popper.min.js','owl.carousel.min.js','bootstrap.js','summernote.js'));
echo $this->minify->deploy_js(FALSE, 'footer.min.js');

	?>
<!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
<script>
// Snackbar for user status switcher
$('#snackbar-user-status label').click(function() { 
	Snackbar.show({
		text: 'Your status has been changed!',
		pos: 'bottom-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 3000,
		textColor: '#fff',
		backgroundColor: '#383838'
	}); 
}); 
</script>


</body>
</html>