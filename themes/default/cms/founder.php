<style>
.founder h2 {
	border-left: 3px solid #85364d;
    padding-left: 10px;
    line-height: 24px;
}
.founder h2 span {
	color: #85364d;
    display: block;
    margin-top: 10px;
}
.founder-body {
	background-image:url(<?php D(theme_url().IMAGE)?>users-icon.png);
	background-repeat:no-repeat;
	background-position: 10px 10px;
    background-size: 28px;
	background-color: #85364d;
	color: #fff;
	padding:30px 40px;
	padding-right: 15px;
	position: relative;
	margin-bottom: 50px;
}
@media (min-width: 768px) {
.founder .img-fluid {
	position: absolute;
	right: 0;
	top: -55px;
}
}
@media (max-width: 767px) {
.founder-body {
	padding: 15px;
	padding-top:40px;
}
}
</style>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($filter);
?>
<div class="container-fluid mt-5 mb-5">
  <div class="mb-4 text-center">
      <h1>
        <?php D($cms->title); ?>
      </h1>
      <p class="lead pb-4"> </p>
  </div>
  <div class="row terms-page">
    <div class="col-md-3 mb-3">
      <div class="card">
        <div class="card-body">
          <ul class="nav nav-pills flex-column mt-2">
          <?php if($founder){
          	foreach($founder as $k=>$member){
          	?>
            <li class="nav-item"> <a class="nav-link <?php if($k==0){D('active');}?>" data-toggle="pill" href="#founder_<?php D($k);?>"><?php D(ucwords($member->founder_name));?></a> </li>
            <?php }
            }
        	?>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <div class="tab-content">
           <?php if($founder){
          	foreach($founder as $k=>$member){
          	?>
          
            <div id="founder_<?php D($k);?>" class="tab-pane fade show <?php if($k==0){D('active');}?>">              
              <div class="founder">
                <h2 class="mb-4"><?php D(ucwords($member->founder_name));?> <span>Founder</span></h2>
                <div class="founder-body">
                <div class="row">
                  <aside class="col-xl-10 col-lg-9 col-md-8">
                  	<?php D(html_entity_decode($member->content));?>
                  </aside>
                  <aside class="col-xl-2 col-lg-3 col-md-4"> 
                   <?php if(!empty($member->founder_image) && file_exists(ABS_USERUPLOAD_PATH.'founder/'.$member->founder_image)){ ?>
                  <img src="<?php D(URL_USERUPLOAD)?>founder/<?php D($member->founder_image);?>" alt="" class="img-fluid" />
                  <?php }?>
                   </aside>
                </div>
                </div>
              </div>
            </div>
			 <?php }
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
