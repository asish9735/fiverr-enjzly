<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="breadcrumbs">
  <div class="container-fluid">
  	<h1><?php D(__('manage_request_page_manage_request','Manage Requests'))?></h1>
      <a href="<?php D(get_link('postrequestURL'))?>" class="btn btn-site headline-link">
            <i class="icon-brand-buysellads"></i> <?php D(__('manage_request_page_post_request_button','Post New Request'))?>
            </a>
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
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="#active" data-toggle="tab" class="nav-link active">
                    <?php D(__('manage_request_page_Active_Requests','Active Requests'))?> <span class="badge badge-dark ml-1"><?php D(count($active_request)); ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#pause" data-toggle="tab" class="nav-link">
                    <?php D(__('manage_request_page_Paused_Requests','Paused Requests'))?> <span class="badge badge-primary ml-1"><?php D(count($paused_request)); ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#pending" data-toggle="tab" class="nav-link">
                    <?php D(__('manage_request_page_Pending_Approval','Pending Approval'))?> <span class="badge badge-warning ml-1"><?php D(count($pending_request)); ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#unapproved" data-toggle="tab" class="nav-link">
                    <?php D(__('manage_request_page_Unapproved','Unapproved'))?> <span class="badge badge-danger ml-1"><?php D(count($unapproved_request)); ?></span>
                </a>
            </li>	
        </ul>
        <div class="clearfix"></div> 
        <div class="tab-content">
            <div id="active" class="tab-pane fade show active">
                <div class="dashboard-box mt-0">
                    <ul class="dashboard-box-list with-button">
                        <?php /*?><thead>
                            <tr>
                                <th><?php D(__('manage_request_page_list_Title','Title'))?></th>
                                <th><?php D(__('manage_request_page_list_Description','Description'))?></th>
                                <th><?php D(__('manage_request_page_list_Date','Date'))?></th>
                                <th><?php D(__('manage_request_page_list_Offers','Offers'))?></th>
                                <th><?php D(__('manage_request_page_list_Budget','Budget'))?></th>
                                <th><?php D(__('manage_request_page_list_Actions','Actions'))?></th>
                            </tr>
                            </thead><?php */?>
                        
                            <?php
                            if($active_request){
                                foreach($active_request as $i=>$request){
                                    $token=md5('FVRR'.'-'.date("Y-m-d").'-'.$request->request_id);
                            ?>
                            <li>
                                <div class="job-listing">
                                    <div class="job-listing-details">                                
                                    <div class="job-listing-description">
                                    <h4 class="job-listing-title"><?php D($request->request_title); ?></h4>
                                    <p><?php D($request->request_description); ?></p>
                                    <div class="job-listing-footer mb-3">
                                        <ul>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Date','Date'))?>:</b> <?php D(dateFormat($request->request_date,'F d, Y')); ?></li>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Offers','Offers'))?>:</b> <?php D($request->offer); ?></li>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Budget','Budget'))?>:</b> <?php D(CURRENCY); ?><?php D($request->request_budget); ?></li>
                                        </ul>
                                    </div>
                                    <a href="<?php D(get_link('viewofferURL'))?>/<?php D($request->request_id); ?>" target="blank" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-eye"></i> <?php D(__('manage_request_page_action_view_offers','View Offers'));?></a>
                                            <a href="<?php D(VZ)?>" onclick="doAction('pause','<?php D($request->request_id); ?>')" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-pause-circle"></i> <?php D(__('manage_request_page_action_Pause','Pause'));?></a>
                                            <a href="<?php D(get_link('editrequestURL'))?>/<?php D($request->request_id); ?>/<?php D($token); ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-edit"></i> <?php D(__('manage_request_page_action_Edit','Edit'));?> </a>
                                            <a href="<?php D(VZ)?>" onclick="doAction('delete','<?php D($request->request_id); ?>')" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i> <?php D(__('manage_request_page_action_Delete','Delete'));?></a>
                                    </div>
                                </div>                                    
                                </div>
                                <?php /*?><div class="buttons-to-right">
                                        <div class="dropdown">
                                        <button class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown"><?php D(__('manage_request_page_list_Actions','Actions'))?></button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            
                                        </div>
                                    </div>
                                </div><?php */?>
                                
                            </li>
                            <?php
                                }
                            }
                            ?>
                    
                    <?php
                    if(count($active_request) == 0){
                    ?>
                    <li>
                    <div class="text-center w-100">
                    	<h2 class="icon-line-awesome-info-circle text-danger"></h2>
						<h5><?php D(__('manage_request_page_list_active_no_record',"You've posted no requests at the moment."));?></h5>
                    </div></li>
                    <?php
                    }
                    ?>
                    </ul>
                </div>
            </div>

            <div id="pause" class="tab-pane fade">
                <div class="dashboard-box mt-0">
                    <ul class="dashboard-box-list">
                            <?php
                            if($paused_request){
                                foreach($paused_request as $i=>$request){
                                    $token=md5('FVRR'.'-'.date("Y-m-d").'-'.$request->request_id);
                            ?>
                            <li>
                                <div class="job-listing">
                                    <div class="job-listing-details">                                
                                    <div class="job-listing-description">
                                    <h4 class="job-listing-title"><?php D($request->request_title); ?></h4>
                                    <p><?php D($request->request_description); ?></p>
                                    <div class="job-listing-footer mb-3">
                                        <ul>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Date','Date'))?>:</b> <?php D(dateFormat($request->request_date,'F d, Y')); ?></li>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Offers','Offers'))?>:</b> <?php D($request->offer); ?></li>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Budget','Budget'))?>:</b> <?php D(CURRENCY); ?><?php D($request->request_budget); ?></li>
                                        </ul>
                                    </div>
                                    <a href="<?php D(get_link('viewofferURL'))?>/<?php D($request->request_id); ?>" target="blank" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-eye"></i> <?php D(__('manage_request_page_action_view_offers','View Offers'));?></a>
                                            <a href="<?php D(VZ)?>" onclick="doAction('pause','<?php D($request->request_id); ?>')" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-pause-circle"></i> <?php D(__('manage_request_page_action_Pause','Pause'));?></a>
                                            <a href="<?php D(get_link('editrequestURL'))?>/<?php D($request->request_id); ?>/<?php D($token); ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-edit"></i> <?php D(__('manage_request_page_action_Edit','Edit'));?> </a>
                                            <a href="<?php D(VZ)?>" onclick="doAction('delete','<?php D($request->request_id); ?>')" class="btn btn-sm btn-outline-danger mr-2"><i class="icon-feather-trash"></i> <?php D(__('manage_request_page_action_Delete','Delete'));?></a>
                                    </div>
                                </div>
                                    
                                </div>
                                <?php /*?><div class="buttons-to-right single-right-button always-visible">
                                        <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown"><?php D(__('manage_request_page_list_Actions','Actions'))?></button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            
                                        </div>
                                    </div>
                                </div><?php */?>
                                
                            </li>
                            <?php
                                }
                            }
                            ?>
                    
                    <?php
                    if(count($paused_request) == 0){
                    ?>
                    <li>
                    <div class="text-center w-100">
                    <h2 class="icon-line-awesome-info-circle text-danger"></h2>
					<h5><?php D(__('manage_request_page_list_paused_no_record',"You currently have no requests paused."));?></h5>
                    </div></li>
                    <?php
                    }
                    ?>
                    </ul>
                </div>

                </div>



            <div id="pending" class="tab-pane fade">
                <div class="dashboard-box mt-0">
                    <ul class="dashboard-box-list">
                        
                            <?php
                            if($pending_request){
                                foreach($pending_request as $i=>$request){
                                    $token=md5('FVRR'.'-'.date("Y-m-d").'-'.$request->request_id);
                            ?>
                            <li>
                                <div class="job-listing">
                                    <div class="job-listing-details">                                
                                    <div class="job-listing-description">
                                    <h4 class="job-listing-title"><?php D($request->request_title); ?></h4>
                                    <p><?php D($request->request_description); ?></p>
                                    <div class="job-listing-footer mb-3">
                                        <ul>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Date','Date'))?>:</b> <?php D(dateFormat($request->request_date,'F d, Y')); ?></li>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Offers','Offers'))?>:</b> <?php D($request->offer); ?></li>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Budget','Budget'))?>:</b> <?php D(CURRENCY); ?><?php D($request->request_budget); ?></li>
                                        </ul>
                                    </div>
                                    <a href="<?php D(get_link('viewofferURL'))?>/<?php D($request->request_id); ?>" target="blank" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-eye"></i> <?php D(__('manage_request_page_action_view_offers','View Offers'));?></a>
                                            <a href="<?php D(VZ)?>" onclick="doAction('pause','<?php D($request->request_id); ?>')" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-pause-circle"></i> <?php D(__('manage_request_page_action_Pause','Pause'));?></a>
                                            <a href="<?php D(get_link('editrequestURL'))?>/<?php D($request->request_id); ?>/<?php D($token); ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-edit"></i> <?php D(__('manage_request_page_action_Edit','Edit'));?> </a>
                                            <a href="<?php D(VZ)?>" onclick="doAction('delete','<?php D($request->request_id); ?>')" class="btn btn-sm btn-outline-danger mr-2"><i class="icon-feather-trash"></i> <?php D(__('manage_request_page_action_Delete','Delete'));?></a>
                                    </div>
                                </div>
                                    
                                </div>
                                <?php /*?><div class="buttons-to-right single-right-button always-visible">
                                        <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown"><?php D(__('manage_request_page_list_Actions','Actions'))?></button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            
                                        </div>
                                    </div>
                                </div><?php */?>
                                
                            </li>
                            <?php
                                }
                            }
                            ?>
                    
                    <?php
                    if(count($pending_request) == 0){
                    ?>
                    <li>
                    <div class="text-center w-100">
                    	<h2 class="icon-line-awesome-info-circle text-danger"></h2>
						<h5><?php D(__('manage_request_page_list_pending_no_record',"You currently have no requests pending."));?></h5>
                    </div>
                    </li>
                    <?php
                    }
                    ?>
                    </ul>
                </div>
                </div>



                <div id="unapproved" class="tab-pane fade">
                <div class="dashboard-box mt-0">
                    <ul class="dashboard-box-list">
                            <?php
                            if($unapproved_request){
                                foreach($unapproved_request as $i=>$request){
                                    $token=md5('FVRR'.'-'.date("Y-m-d").'-'.$request->request_id);
                            ?>
                            <li>
                                <div class="job-listing">
                                    <div class="job-listing-details">                                
                                    <div class="job-listing-description">
                                    <h4 class="job-listing-title"><?php D($request->request_title); ?></h4>
                                    <p><?php D($request->request_description); ?></p>
                                    <div class="job-listing-footer">
                                        <ul>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Date','Date'))?>:</b> <?php D(dateFormat($request->request_date,'F d, Y')); ?></li>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Offers','Offers'))?>:</b> <?php D($request->offer); ?></li>
                                            <li><i class="icon-feather-calendar"></i><b><?php D(__('manage_request_page_list_Budget','Budget'))?>:</b> <?php D(CURRENCY); ?><?php D($request->request_budget); ?></li>
                                        </ul>
                                    </div>
                                    <a href="<?php D(VZ)?>" onclick="doAction('delete','<?php D($request->request_id); ?>')" class="btn btm-sm btn-outline-danger"><i class="icon-feather-trash"></i> <?php D(__('manage_request_page_action_Delete','Delete'));?></a>
                                    </div>
                                </div>
                                    
                                </div>
                                <?php /*?><div class="buttons-to-right single-right-button always-visible">
                                        
                                </div><?php */?>
                                
                            </li>
                            
                            <?php
                                }
                            }
                            ?>
                        
                    
                    <?php
                    if(count($unapproved_request) == 0){
                    ?>
                    <li>
                    <div class="text-center w-100">
						<h2 class="icon-line-awesome-info-circle text-danger"></h2>
                        <h5><?php D(__('manage_request_page_list_unapproved_no_record',"You currently have no unapproved requests."));?></h5>
                    </div>
                    </li>
                    <?php
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


<script type="text/javascript">
function doAction(type,request_id){
	var url="<?php D(get_link('actionrequestURLAJAX'))?>";
	 swal({
          type: 'info',
          text: 'Processing request',
          onOpen: function(){
            swal.showLoading();
            $.ajax({
		        type: "POST",
		        url: url,
		        data:{action:type,rid:request_id},
		        dataType: "json",
		        cache: false,
				success: function(msg) {
					setTimeout(function(){
					if (msg['status'] == 'OK') {
						swal({
			                  type: 'success',
			                  text: msg['message'],
			                  timer: 2000,
			                  onOpen: function(){
			                    swal.showLoading()
			                  }
		                  }).then(function(){
		                  	window.location.reload();
		                })
					}else{
						swal({
			                  type: 'error',
			                   text: msg['message'],
			                  timer: 2000,
			                  onOpen: function(){
			                    swal.showLoading()
			                  }
		                  }).then(function(){
		                  	window.location.reload();
		                })
					}
					},1000);
				}
			})
          }
    })
}
		
</script>