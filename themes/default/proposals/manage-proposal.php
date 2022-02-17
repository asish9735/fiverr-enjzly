<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$loggedUser=$this->session->userdata('loggedUser');

$username=$loggedUser['UNAME'];

?>

<div class="breadcrumbs">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-4 col-12">
        <div class="view-proposals d-iphone6-flex align-items-center">
          <div>
            <?php D(__('manage_proposal_page_Vacation_Mode','Vacation Mode:'))?>
            <label id="vacation_section" class="mb-0" style="display:inline-block">
              <?php if($member_details['member']->is_vacation!= 1){ ?>
              <button id="turn_on_seller_vaction" data-toggle="button" class="btn btn-toggle">
              <div class="toggle-handle"></div>
              </button>
              <?php }else{ ?>
              <button id="turn_off_seller_vaction" data-toggle="button" class="btn btn-toggle active">
              <div class="toggle-handle"></div>
              </button>
              <?php } ?>
            </label>
          </div>
          
          <!--<div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="customSwitch1">
          <label class="custom-control-label" for="customSwitch1">Test</label>
        </div>--> 
        </div>
      </div>
      <div class="col-sm-4 col-12 text-center">
        <h1>
          <?php D(__('manage_proposal_page_heading','View My Gigs'))?>
        </h1>
      </div>
      <div class="col-sm-4 col-12 text-sm-right">
      <a href="<?php D(get_link('postproposalURL'))?>" class="btn btn-dark mr-2"> <i class="icon-brand-buysellads"></i> <?php D(__('manage_proposal_page_post_proposal_button','Add New Gigs'))?></a>
      </div>
    </div>
  </div>
</div>
<section class="section gray">
  <div class="container-fluid"><!-- container-fluid view-proposals Starts -->
    <div class="row">
      <div class="col-xl-3 col-lg-auto col-12">
        <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
      </div>
      <div class="col-xl-9 col-lg col-12">
        <ul class="nav nav-pills">
          <li class="nav-item"> <a href="#active-proposals" data-toggle="tab" class="nav-link active">
            <?php D(__('manage_proposal_page_Active_Proposals','Active Proposals'))?>
            <span class="badge badge-site ml-1">
            <?php D(count($active_proposals)); ?>
            </span> </a> </li>
          <li class="nav-item"> <a href="#pause-proposals" data-toggle="tab" class="nav-link">
            <?php D(__('manage_proposal_page_Paused_Proposals','Paused Proposals'))?>
            <span class="badge badge-site ml-1">
            <?php D(count($paused_proposals)); ?>
            </span> </a> </li>
          <li class="nav-item"> <a href="#pending-proposals" data-toggle="tab" class="nav-link">
            <?php D(__('manage_proposal_page_Pending_Proposals','Pending Proposals'))?>
            <span class="badge badge-site ml-1">
            <?php D(count($pending_proposals)); ?>
            </span> </a> </li>
          <li class="nav-item"> <a href="#modification-proposals" data-toggle="tab" class="nav-link">
            <?php D(__('manage_proposal_page_Requires_Modification','Requires Modification'))?>
            <span class="badge badge-site ml-1">
            <?php D(count($modification_proposals)); ?>
            </span> </a> </li>
          <li class="nav-item"> <a href="#declined-proposals" data-toggle="tab" class="nav-link">
            <?php D(__('manage_proposal_page_Declined','Declined'))?>
            <span class="badge badge-danger ml-1">
            <?php D(count($declined_proposals)); ?>
            </span> </a> </li>
        </ul>
        <div class="tab-content">
          <div id="active-proposals" class="tab-pane fade show active">
            <div class="dashboard-box mt-0">
              <ul class="dashboard-box-list">
                <?php /*?><tr>

			<th><?php D(__('manage_proposal_page_list_Title',"Proposal's Title"))?></th>

			<th><?php D(__('manage_proposal_page_list_Price',"Proposal's Price"))?></th>

			<th><?php D(__('manage_proposal_page_list_Views','Views'))?></th>

			<th><?php D(__('manage_proposal_page_list_Orders','Orders'))?></th>

			<th><?php D(__('manage_proposal_page_list_Actions','Actions'))?></th>

			</tr><?php */?>
                <?php

				if($active_proposals){

					foreach($active_proposals as $i=>$proposal){

					$token=md5('FVRR'.'-'.date("Y-m-d").'-'.$proposal->proposal_id);

				?>
                <li> 
                  
                  <!-- Job Listing -->
                  
                  <div class="job-listing"> 
                    
                    <!-- Job Listing Details -->
                    
                    <div class="job-listing-details"> 
                      
                      <!-- Details -->
                      
                      <div class="job-listing-description">
                        <h4 class="job-listing-title">
                          <?php D($proposal->proposal_title); ?>
                          <?php $class='';

                        if($order->order_status==1){

                          $class='yellow';

                        }elseif($order->order_status==2){

                          $class='blue';

                        }elseif($order->order_status==3){

                          $class='';

                        }elseif($order->order_status==4){

                          $class='';

                        }elseif($order->order_status==5){

                          $class='red';

                        }elseif($order->order_status==6){

                          $class='green';

                        }elseif($order->order_status==7){

                          $class='green';

                        }

                      ?>
                          <?php if($proposal->proposal_featured == 1 && $proposal->featured_end_date>=date('Y-m-d H:i:s')){ ?>
                          <span class="badge badge-primary">
                          <?php D(__('manage_proposal_page_action_Featured','Featured'));?>
                          </span> <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="" data-content="<?php D(__('manage_proposal_page_action_Featured','Featured'));?> till: <?php D(dateFormat($proposal->featured_end_date,'F d, Y')); ?>"><i class="icon-feather-info"></i></a>
                          <?php }else{ ?>
                          <?php } ?>
                        </h4>
                        
                        <!-- Job Listing Footer -->
                        <div class="job-listing-footer mb-3">
                          <ul>
                            <li><i class="icon-feather-tag"></i> <b>
                              <?php D(__('manage_proposal_page_list_Price',"Proposal's Price"))?>
                              :</b> <span>
                              <?php D(CURRENCY); ?>
                              <?php D($proposal->display_price); ?>
                              </span></li>
                            <li><i class="icon-feather-eye"></i> <b>
                              <?php D(__('manage_proposal_page_list_Views','Views'))?>
                              :</b> <span>
                              <?php D($proposal->proposal_views); ?>
                              </span></li>
                            <li><i class="icon-feather-shopping-cart"></i> <b>
                              <?php D(__('manage_proposal_page_list_Orders','Orders'))?>
                              :</b>
                              <?php D($s_currency); ?>
                              <span>
                              <?php D($proposal->order); ?>
                              </span></li>
                            <?php /* if($proposal->proposal_featured == 1 && $proposal->featured_end_date>=date('Y-m-d H:i:s')){ ?>
                          <li><i class="icon-feather-award"></i> <b>Featured till:</b> <?php D(dateFormat($proposal->featured_end_date,'F d, Y').' '.date('H:i',strtotime($proposal->featured_end_date))); ?></li>
                          <?php }else{ ?>
                          <?php } */?>
                          </ul>
                        </div>
                        <div class="job-listing-button">
                        <a href="<?php D(get_link('ProposalDetailsURL'))?>/<?php D('p-'.$member_details['member']->username); ?>/<?php echo $proposal->proposal_url; ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-eye"></i>
                        <?php D(__('manage_proposal_page_action_Preview','Preview'));?>
                        </a>
                        <?php if($proposal->proposal_featured == 1 && $proposal->featured_end_date>=date('Y-m-d H:i:s')){ ?>
                        
                        <?php /*?><a href="<?php D(VZ)?>" class="dropdown-item text-success"><?php D(__('manage_proposal_page_action_Already_Featured','Already Featured'));?> till <?php D(dateFormat($proposal->featured_end_date,'F d, Y').' '.date('H:i',strtotime($proposal->featured_end_date))); ?></a><?php */?>
                        
                        <?php }else{ ?>
                        <a href="<?php D(VZ)?>" class="btn btn-sm btn-outline-dark mr-2" onclick="doAction('makefeature','<?php D($proposal->proposal_id); ?>')" id="featured-button-<?php D($proposal->proposal_id); ?>"><i class="icon-feather-award"></i> Make Proposal Featured</a>
                        <?php } ?>
                        <a href="<?php D(VZ)?>" onclick="doAction('pause','<?php D($proposal->proposal_id); ?>')" class="btn btn-sm btn-outline-dark mr-2"> <i class="icon-feather-pause-circle"></i>
                        <?php D(__('manage_proposal_page_action_Pause','Pause'));?>
                        </a> 
                        
                        <?php /*?><a href="<?php D(get_link('ProposalReferralURL'))?>/<?php D($proposal->proposal_id); ?>" class="dropdown-item"> <?php D(__('manage_proposal_page_action_View_Referrals','View Referrals'));?></a><?php */?> 
                        
                        <a href="<?php D(get_link('editproposalURL'))?>/<?php D($proposal->proposal_id); ?>/<?php D($token); ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-edit"></i>
                        <?php D(__('manage_proposal_page_action_Edit','Edit'));?>
                        </a> <a href="<?php D(VZ)?>" onclick="doAction('delete','<?php D($proposal->proposal_id); ?>')" class="btn btn-sm btn-outline-danger mr-2"><i class="icon-feather-trash"></i>
                        <?php D(__('manage_proposal_page_action_Delete','Delete'));?>
                        </a>
                        </div>
                        </div>
                    </div>
                  </div>
                  
                  <!-- Buttons -->
                  <?php /*?><div class="button-with-dropdown">
                    <div class="dropdown">
                      <button class="btn btn-sm btn-outline-dark dropdown-toggle" data-toggle="dropdown">
                      <?php D(__('manage_proposal_page_list_Actions','Actions'))?>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">
                      </div>
                    </div>
                  </div><?php */?>
                </li>
                <?php

					}
				}

				?>
              </ul>
              <?php

            if(count($active_proposals) == 0){

          ?>
              <div class='p-3 text-center'>
              	<h2 class="icon-line-awesome-info-circle text-danger"></h2>
                <h5><?php D(__('manage_proposal_page_list_active_no_record',"You currently have no proposals/services to sell."));?></h5>
              </div>
              <?php

            }

          ?>
            </div>
          </div>
          <div id="pause-proposals" class="tab-pane fade">
            <div class="dashboard-box mt-0">
              <ul class="dashboard-box-list">
                <?php

					if($paused_proposals){

					foreach($paused_proposals as $i=>$proposal){

					$token=md5('FVRR'.'-'.date("Y-m-d").'-'.$proposal->proposal_id);

				?>
                <li>
                  <div class="job-listing">                     
                    <!-- Job Listing Details -->                    
                    <div class="job-listing-details">                       
                      <!-- Details -->                     
                      <div class="job-listing-description">
                        <h3 class="job-listing-title">
                          <?php D($proposal->proposal_title); ?>
                          <?php if($proposal->admin_reason){?>
                          <a href="#" data-toggle="tooltip" data-html="true"  title="<?php D(nl2br($proposal->admin_reason));?>"><i class="fa fa-question-circle"></i></a>
                          <?php }?>
                        </h3>
                        
                        <!-- Job Listing Footer -->
                        
                        <div class="job-listing-footer mb-3">
                          <ul>
                            <li><i class="icon-feather-tag"></i> <b>
                              <?php D(__('manage_proposal_page_list_Price',"Proposal's Price"))?>
                              :</b> <span>
                              <?php D(CURRENCY); ?>
                              <?php D($proposal->display_price); ?>
                              </span></li>
                            <li><i class="icon-feather-eye"></i> <b>
                              <?php D(__('manage_proposal_page_list_Views','Views'))?>
                              :</b> <span>
                              <?php D($proposal->proposal_views); ?>
                              </span></li>
                            <li><i class="icon-feather-shopping-cart"></i> <b>
                              <?php D(__('manage_proposal_page_list_Orders','Orders'))?>
                              :</b> <span>
                              <?php D($proposal->order); ?>
                              </span></li>
                          </ul>
                        </div>
                        <a href="<?php D(VZ)?>" onclick="doAction('active','<?php D($proposal->proposal_id); ?>')" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-"></i> <?php D(__('manage_proposal_page_action_Activate_Proposal','Activate Proposal'));?></a> 
                        
                        <?php /*?><a href="<?php D(get_link('ProposalReferralURL'))?>/<?php echo $proposal_id; ?>" class="dropdown-item"> <?php D(__('manage_proposal_page_action_View_Referrals','View Referrals'));?></a><?php */?> 
                        
                        <a href="<?php D(get_link('editproposalURL'))?>/<?php D($proposal->proposal_id); ?>/<?php D($token); ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-edit"></i> <?php D(__('manage_proposal_page_action_Edit','Edit'));?></a>
                        <a href="<?php D(VZ)?>" onclick="doAction('delete','<?php D($proposal->proposal_id); ?>')" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i> <?php D(__('manage_proposal_page_action_Delete','Delete'));?></a>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Buttons -->
                  
                  <?php /*?><div class="buttons-to-right single-right-button always-visible">
                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                      <?php D(__('manage_proposal_page_list_Actions','Actions'))?>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">
                       </div>
                    </div>
                  </div><?php */?>
                </li>
                <?php

                                }

                            }

                            ?>
              </ul>
              <?php

                          if(count($paused_proposals) == 0){

                        ?>
              <div class='p-3 text-center'>
                	<h2 class="icon-line-awesome-info-circle text-danger"></h2>
                    <h5><?php D(__('manage_proposal_page_list_paused_no_record',"You currently have no paused proposals/services."));?></h5>
              </div>
              <?php

                          }

                        ?>
            </div>
          </div>
          <div id="pending-proposals" class="tab-pane fade">
            <div class="dashboard-box mt-0">
              <ul class="dashboard-box-list">
                <?php

			if($pending_proposals){

				foreach($pending_proposals as $i=>$proposal){

					$token=md5('FVRR'.'-'.date("Y-m-d").'-'.$proposal->proposal_id);

			?>
                <li>
                  <div class="job-listing"> 
                    
                    <!-- Job Listing Details -->
                    
                    <div class="job-listing-details"> 
                      
                      <!-- Details -->
                      
                      <div class="job-listing-description">
                        <h3 class="job-listing-title">
                          <?php D($proposal->proposal_title); ?>
                        </h3>
                        
                        <!-- Job Listing Footer -->
                        
                        <div class="job-listing-footer mb-3">
                          <ul>
                            <li><i class="icon-feather-tag"></i> <b>
                              <?php D(__('manage_proposal_page_list_Price',"Proposal's Price"))?>
                              :</b> <span>
                              <?php D(CURRENCY); ?>
                              <?php D($proposal->display_price); ?>
                              </span></li>
                            <li><i class="icon-feather-eye"></i> <b>
                              <?php D(__('manage_proposal_page_list_Views','Views'))?>
                              :</b> <span>
                              <?php D($proposal->proposal_views); ?>
                              </span></li>
                            <li><i class="icon-feather-shopping-cart"></i> <b>
                              <?php D(__('manage_proposal_page_list_Orders','Orders'))?>
                              :</b> <span>
                              <?php D($proposal->order); ?>
                              </span></li>
                          </ul>
                        </div>
                        <a href="<?php D(get_link('ProposalDetailsURL'))?>/<?php D('p-'.$member_details['member']->username); ?>/<?php echo $proposal->proposal_url; ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-eye"></i> <?php D(__('manage_proposal_page_action_Preview','Preview'));?>
                        </a> <a href="<?php D(get_link('editproposalURL'))?>/<?php D($proposal->proposal_id); ?>/<?php D($token); ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-edit"></i> <?php D(__('manage_proposal_page_action_Edit','Edit'));?>
                        </a> <a href="<?php D(VZ)?>" onclick="doAction('delete','<?php D($proposal->proposal_id); ?>')" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i> <?php D(__('manage_proposal_page_action_Delete','Delete'));?>
                        </a>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Buttons -->                  
                  <?php /*?><div class="buttons-to-right single-right-button always-visible">
                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                      <?php D(__('manage_proposal_page_list_Actions','Actions'))?>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">  </div>
                    </div>
                  </div><?php */?>
                </li>
                <?php

				}

			}

			?>
              </ul>
              <?php

			  if(count($pending_proposals) == 0){

		  ?>
              <div class='p-3 text-center'>
              		<h2 class="icon-line-awesome-info-circle text-danger"></h2>
                    <h5><?php D(__('manage_proposal_page_list_pending_no_record',"You currently have no proposals/services pending."));?></h5>
                </div>
              <?php

                          }

                        ?>
            </div>
          </div>
          <div id="modification-proposals" class="tab-pane fade">
            <div class="dashboard-box mt-0">
              <ul class="dashboard-box-list">
                <?php

				if($modification_proposals){

					foreach($modification_proposals as $i=>$proposal){

						$token=md5('FVRR'.'-'.date("Y-m-d").'-'.$proposal->proposal_id);

				?>
                <li>
                  <div class="job-listing"> 
                    
                    <!-- Job Listing Details -->
                    
                    <div class="job-listing-details"> 
                      
                      <!-- Details -->
                      
                      <div class="job-listing-description">
                        <h3 class="job-listing-title">
                          <?php D($proposal->proposal_title); ?>
                          <?php if($proposal->admin_reason){?>
                          <a href="#" data-toggle="tooltip" data-html="true"  title="<?php D(nl2br($proposal->admin_reason));?>"><i class="fa fa-question-circle"></i></a>
                          <?php }?>
                        </h3>
                        
                        <!-- Job Listing Footer -->
                        
                        <div class="job-listing-footer mb-3">
                          <ul>
                            <li><i class="icon-feather-tag"></i> <b>
                              <?php D(__('manage_proposal_page_list_Price',"Proposal's Price"))?>
                              :</b> <span>
                              <?php D(CURRENCY); ?>
                              <?php D($proposal->display_price); ?>
                              </span></li>
                            <li><i class="icon-feather-eye"></i> <b>
                              <?php D(__('manage_proposal_page_list_Views','Views'))?>
                              :</b> <span>
                              <?php D($proposal->proposal_views); ?>
                              </span></li>
                            <li><i class="icon-feather-shopping-cart"></i> <b>
                              <?php D(__('manage_proposal_page_list_Orders','Orders'))?>
                              :</b> <span>
                              <?php D($proposal->order); ?>
                              </span></li>
                          </ul>
                        </div>
                        <a href="<?php D(get_link('ProposalDetailsURL'))?>/<?php D('p-'.$member_details['member']->username); ?>/<?php echo $proposal->proposal_url; ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-eye"></i> <?php D(__('manage_proposal_page_action_Preview','Preview'));?></a>
                         <a href="<?php D(get_link('editproposalURL'))?>/<?php D($proposal->proposal_id); ?>/<?php D($token); ?>" class="btn btn-sm btn-outline-dark mr-2"><i class="icon-feather-edit"></i> <?php D(__('manage_proposal_page_action_Edit','Edit'));?>
                        </a> <a href="<?php D(VZ)?>" onclick="doAction('delete','<?php D($proposal->proposal_id); ?>')" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i> <?php D(__('manage_proposal_page_action_Delete','Delete'));?>
                        </a>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Buttons -->                  
                  <?php /*?><div class="buttons-to-right single-right-button always-visible">
                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                      <?php D(__('manage_proposal_page_list_Actions','Actions'))?>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">  </div>
                    </div>
                  </div><?php */?>
                </li>
                <?php

					}

				}

				?>
              </ul>
              <?php
                 if(count($modification_proposals) == 0){
              ?>
              <div class='p-3 text-center'>
              	<h2 class="icon-line-awesome-info-circle text-danger"></h2>
              	<h5><?php D(__('manage_proposal_page_list_pending_no_record',"You currently have no proposals/services modification."));?></h5>
              </div>
              <?php

                          }

                        ?>
            </div>
          </div>
          <div id="declined-proposals" class="tab-pane fade">
            <div class="dashboard-box mt-0">
              <ul class="dashboard-box-list">
                <?php

				if($declined_proposals){

					foreach($declined_proposals as $i=>$proposal){

				?>
                <li>
                  <div class="job-listing"> 
                    
                    <!-- Job Listing Details -->
                    
                    <div class="job-listing-details"> 
                      
                      <!-- Details -->
                      
                      <div class="job-listing-description">
                        <h3 class="job-listing-title">
                          <?php D($proposal->proposal_title); ?>
                        </h3>
                        
                        <!-- Job Listing Footer -->
                        
                        <div class="job-listing-footer mb-3">
                          <ul>
                            <li><i class="icon-feather-tag"></i> <b>
                              <?php D(__('manage_proposal_page_list_Price',"Proposal's Price"))?>
                              :</b> <span>
                              <?php D(CURRENCY); ?>
                              <?php D($proposal->display_price); ?>
                              </span></li>
                            <li><i class="icon-feather-eye"></i> <b>
                              <?php D(__('manage_proposal_page_list_Views','Views'))?>
                              :</b> <span>
                              <?php D($proposal->proposal_views); ?>
                              </span></li>
                            <li><i class="icon-feather-shopping-cart"></i> <b>
                              <?php D(__('manage_proposal_page_list_Orders','Orders'))?>
                              :</b> <span>
                              <?php D($proposal->order); ?>
                              </span></li>
                          </ul>
                        </div>
                        <a href="<?php D(VZ)?>" onclick="doAction('delete','<?php D($proposal->proposal_id); ?>')" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i> <?php D(__('manage_proposal_page_action_Delete','Delete'));?>
                    	</a>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Buttons -->
                  
                  <?php /*?><div class="buttons-to-right single-right-button always-visible">  </div><?php */?>
                </li>
                <?php

					}

				}

				?>
              </ul>
              <?php

			  if(count($declined_proposals) == 0){

			?>
              <div class='p-3'>
                <div class="text-center">
                	<h2 class="icon-line-awesome-info-circle text-danger"></h2>
                    <h5><?php D(__('manage_proposal_page_list_declined_no_record',"You currently have no proposals/services declined."));?></h5>
                </div>
              </div>
              <?php

                          }

                        ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div id="vacation-modal" class="modal fade"><!-- vacation-modal modal fade Starts -->
  
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content mycustom-modal"><!-- modal-content Starts -->
      
      <form action="" method="post" accept-charset="utf-8" id="vacationform" class="form-horizontal" role="form" name="vacationform" onsubmit="saveVacationActive(this);return false;">
        <div class="modal-header"><!-- modal-header Starts -->
          
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">
          <?php D(__('global_Close',"Close"));?>
          </button>
          <h4 class="modal-title">
            <?php D(__('modal_vacation_heading',"Vacation Mode"));?>
          </h4>
          <button id="activate" class="btn btn-site saveBTN pull-right" type="submit">
          <?php D(__('modal_vacation_Submit_Activate',"Activate"));?>
          </button>
        </div>
        
        <!-- modal-header Ends -->
        
        <div class="modal-body"><!-- modal-body p-0 Starts -->
          
          <input name="mode" value="on" type="hidden"/>
          <div class="form-group mb-3"><!--- form-group Starts --->
            
            <label>
              <?php D(__('modal_vacation_Why',"Why?"));?>
              <small class="text-muted"> (
              <?php D(__('modal_vacation_Optional',"Optional"));?>
              ) </small></label>
            <select class="form-control float-right" name="seller_vacation_reason" id="seller_vacation_reason">
              <option value="">
              <?php D(__('modal_vacation_Select',"Select"));?>
              </option>
              <option>
              <?php D(__('modal_vacation_option_1',"I\'m going on vacation"));?>
              </option>
              <option>
              <?php D(__('modal_vacation_option_2',"I\'m overbooked"));?>
              </option>
              <option>
              <?php D(__('modal_vacation_option_3',"Other"));?>
              </option>
            </select>
          </div>
          
          <!--- form-group Ends ---> 
          
          <br>
          <div class="form-group mt-3 mb-0"><!--- form-group Starts --->
            
            <label>
              <?php D(__('modal_vacation_Additional_Information',"Additional Information"));?>
              <small class="text-muted"> (
              <?php D(__('modal_vacation_Optional',"Optional"));?>
              ) </small> </label>
            <textarea name="seller_vacation_message" id="seller_vacation_message" rows="4" class="form-control"></textarea>
          </div>
          
          <!--- form-group Ends ---> 
          
        </div>
        
        <!-- modal-body p-0 Ends -->
        
      </form>
    </div>
    
    <!-- modal-content Ends --> 
    
  </div>
  
  <!-- modal-dialog Ends --> 
  
</div>

<!-- vacation-modal modal fade Ends -->

<div class="append-modal"></div>
<div id="featured-proposal-modal"></div>
<script type="text/javascript">
$(function () {
  $('[data-toggle="popover"]').popover()
});
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

function doAction(type,proposal_id){

	if(type=='makefeature'){

		$.ajax({

		  	method: "POST",

		 	url: "<?php D(get_link('actionproposalPayfeatureURLAJAX'))?>",

		 	data: {proposal_id: proposal_id }

		}).done(function(data){

			$("#featured-proposal-modal").html(data);		

		});

		return false;

	}

	var url="<?php D(get_link('actionproposalURLAJAX'))?>";

	 swal({

          type: 'info',

          text: '<?php D(__('popup_manageproposal_Processing request',"Processing request"));?>',

          onOpen: function(){

            swal.showLoading();

            $.ajax({

		        type: "POST",

		        url: url,

		        data:{action:type,rid:proposal_id},

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

function saveVacationActive(ev){

	var formID=$(ev).attr('id');

	var buttonsection=$('#'+formID).find('.saveBTN');

	var buttonval = buttonsection.html();

	buttonsection.html(SPINNER).attr('disabled','disabled');

	

	$.ajax({

        type: "POST",

        url: "<?php D(get_link('actionvacationsetURLAJAX'))?>/",

        data:$('#'+formID).serialize(),

        dataType: "json",

        cache: false,

		success: function(msg) {

			buttonsection.html(buttonval).removeAttr('disabled');

			clearErrors();

			if (msg['status'] == 'OK') {

				$("#vacation-modal").modal('hide');

				 swal({

                  type: 'success',

                   text: '<?php D(__('popup_manageproposal_Vacation_switched_On',"Vacation switched On."));?>',

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

					$("#turn_on_seller_vaction").attr('id','turn_off_seller_vaction').addClass('active');

                })	

			} else if (msg['status'] == 'FAIL') {

				registerFormPostResponse(formID,msg['errors']);

			}

		}

	})	

}

$('#vacation_section').on('click','#turn_on_seller_vaction', function(e){

        $("#vacation-modal").modal('show');

});

$('#vacation_section').on('click','#turn_off_seller_vaction', function(e){

        $.ajax({

	        method:"POST",

	        url: "<?php D(get_link('actionvacationsetURLAJAX'))?>",

	        data: { mode: 'off' }

        }).done(function(){

	    	

	        swal({

		          type: 'success',

		          text: '<?php D(__('popup_manageproposal_Vacation_switched_OFF',"Vacation switched OFF."));?>',

		          padding: 40,

	        }).then(function(){

              $("#turn_off_seller_vaction").attr('id','turn_on_seller_vaction');

              

	        })	

            

        });

    });

$(document).ready(function(){

   

    $('#vacation-modal').on('hide.bs.modal', function () {

    	$("#turn_on_seller_vaction").removeClass('active');	

    });

    

});		

</script>
<?php if(($this->input->get('ref') && $this->input->get('ref')=='paymentsuccess') || ($this->input->get('ref_p') && $this->input->get('ref_p')=='paymentsuccess')){?>
<script>

	swal({

          type: 'success',

          text: '<?php D(__('popup_manageproposal_Payment_Success',"Payment Success"));?>',

          padding: 40,

    }).then(function(){

			window.location.href="<?php D(get_link('manageproposalURL'));?>";

    })
 
</script>
<?php }?>
