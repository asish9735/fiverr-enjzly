<?php

defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="breadcrumbs">

  <div class="container">

  <h1><?php D(__('notification_page_Heading',"Notifications"));?></h1>

  </div>

</div>

<section class="section gray">

<div class="container-fluid">			
<div class="row">
      <div class="col-xl-3 col-lg-4 col-12">
        <?php
          $templateLayout=array('view'=>'inc/user-nav','type'=>'ajax','buffer'=>FALSE,'theme'=>'');
          load_template($templateLayout,$data);
        ?>
      </div>
      <div class="col-xl-9 col-lg-8 col-12">
    <div class="dashboard-box mt-0">

            <!-- Headline -->

            <div class="headline">

                <h4><i class="icon-feather-bell text-site"></i> <?php D(__('notification_page_All_Notifications',"All Notifications"));?></h4>

            </div>

			<div style="max-height:460px" data-simplebar>

            <ul class="dashboard-box-list">

            <?php /*?><tr>

                <th><?php D(__('notification_page_Sender',"Sender"));?></th>

                <th><?php D(__('notification_page_Message',"Message"));?></th>

                <th><?php D(__('notification_page_Date',"Date"));?></th>

                <th><?php D(__('notification_page_Delete',"Delete"));?> </th>

            </tr><?php */?>



        <?php

        if($notifications){

            foreach($notifications as $notification){

                if($notification->sender_id){

                    ///$member_name=$notification->member_name;

                    $sender_user_name=getUserName($notification->sender_id);

                }else{

                    $member_name=$sender_user_name=__('notification_page_Admin','Admin');

                }

            ?>

            <li class="<?php if($notification->is_read!=1){D('active');}?>">

                    <!-- Job Listing -->

                    <div class="job-listing">

                        <!-- Job Listing Details -->

                        <div class="job-listing-details">

                            <!-- Logo -->

                            <a href="<?php D(get_link('NotificationDetailsLink'))?><?php D($notification->notification_id); ?>" class="job-listing-company-logo" style="max-width: 64px">

                                <img src="<?php D(getMemberLogo($notification->sender_id))?>" alt="" class="img-fluid" height="64" width="64">

                            </a>

                            <!-- Details -->

                            <div class="job-listing-description">

                                <h3 class="job-listing-title"><a href="<?php D(get_link('NotificationDetailsLink'))?><?php D($notification->notification_id); ?>"><?php /*D($member_name);*/ D($sender_user_name); ?></a></h3>

                                <span><?php D($notification->template_content);?></span>

                                <!-- Job Listing Footer -->

                                <div class="job-listing-footer">

                                    <ul>

                                        <li><i class="icon-feather-calendar"></i> <?php D(date('H:i',strtotime($notification->notification_date)).' '.dateFormat($notification->notification_date,'F d,Y')); ?></li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Buttons -->

                    <div class="buttons-to-right">

                        <a href="<?php D(VZ); ?>" class="btn btn-sm btn-outline-danger ico" onclick="deleteNotification('<?php D($notification->notification_id); ?>')" title="Remove" data-tippy-placement="left"><i class="icon-feather-trash"></i></a>

                    </div>

                </li>

            

            <?php

            }

        }

         ?>
<?php 

if(!$notifications){

?>

<li>

<div class="alert alert-danger mb-0 w-100">

  <?php D(__('notification_page_no_notification',"You have no notifications at the moment."));?>

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

<script>

	function deleteNotification(nid){

		$.ajax({

        type: "POST",

        url: "<?php D(get_link('deleteActionURLNotificationAJAX'))?>/"+nid,

        dataType: "json",

        cache: false,

		success: function(msg) {

			if (msg['status'] == 'OK') {

				var message='<?php D(__('popup_notification_page_delete_success',"Notification deleted successfully!"));?>';

				 swal({

                  type: 'success',

                  text: message,

                  timer: 2000,

                  onOpen: function(){

                    swal.showLoading()

                  }

                  }).then(function(){

                  	window.location.reload();

                })	

			} else if (msg['status'] == 'FAIL') {

				 swal({

                  type: 'error',

                  text: message,

                  timer: 2000,

                  })

			}

		}

	})

	}

</script>