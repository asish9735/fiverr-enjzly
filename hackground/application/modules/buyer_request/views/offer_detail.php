  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
        <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
     <?php echo $breadcrumb ? $breadcrumb : '';?>
    </section>

	 <!-- Content Filter -->
	<?php $this->layout->load_filter(); ?>
	
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $title ? $title : '';?></h3>

          <div class="box-tools pull-right">
			<?php if(ALLOW_TRASH_VIEW){ ?>
			<?php if(get('show') && get('show') == 'trash'){ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method);?>" type="button" class="btn btn-box-tool"><i class="fa fa-check-circle-o <?php echo ICON_SIZE;?>"></i> Show Main</a>&nbsp;&nbsp;
			<?php }else{ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method.'?show=trash');?>" type="button" class="btn btn-box-tool"><i class="fa fa-trash <?php echo ICON_SIZE;?>"></i> Show Trash</a>&nbsp;&nbsp;
			<?php } ?>
			<?php } ?>
		   
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding table_visible" id="main_table">
              <table class="table table-hover" style="margin-bottom: 50px">
                <tbody>
				<tr>
				  <th style="width:10%">ID</th>
                  <th style="width:20%">Proposal</th>
                  <th style="width:20%">Username</th>
                  <th style="width:10%">Offer Budget</th>
                  <th style="width:10%">Offer Duration</th>
                  <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php $currency = get_setting('site_currency');
				if(count($request_offer) > 0){foreach($request_offer as $k => $v){ 
				$seller_user_name=getUserName($v['proposal_seller_id']);
				$url=URL.'proposals/view/'.$seller_user_name.'/'.$v['proposal_url'];
				$profileurl=URL.'p-'.$seller_user_name;
				?>
				<tr>
					
                  <td><?php echo $v['offer_id']; ?></td>
                  <td>
                  <p><b>Title:</b> <?php echo $v['proposal_title']; ?></p>
                  <p><b>Description:</b> <?php echo $v['description_offer']; ?></p>
                  </td>
                  <td>
                  <?php echo $seller_user_name; ?>
                  </td>
                  <td><?php echo $currency; ?> <?php echo $v['amount_offer']; ?></td>
                  <td><?php echo $v['delivery_time_offer']; ?>days</td>
                  <td class="text-right" style="padding-right:20px;">
					<div class="btn-group">
					  <button type="button" class="btn btn-default"><?php echo 'action'; ?></button>
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
					  	 <li><a href="<?php echo $url; ?>" target="_blank">View proposal</a></li>
					  	<li><a href="<?php echo $profileurl; ?>" target="_blank">View member</a></li>
					  	
						
					  </ul>
					</div>
				  </td>
                </tr>
				<?php } }else{  ?>
				<tr>
                  <td colspan="10"><?php echo NO_RECORD; ?></td>
                 </tr>
				<?php } ?>
                
               </tbody>
			  </table>
        </div>
		 <!-- /.box-body -->
		<div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
              
              </ul>
            </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<div class="modal fade" id="ajaxModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		 
		</div>
	  </div>
</div>


