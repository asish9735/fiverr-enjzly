<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
		<!-- working area -->
		
		 <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $active_orders_count; ?></h3>

              <p>Active Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?php echo base_url('orders/list_record'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $withdrawn_request_count; ?></h3>

              <p>Withdrawn Request</p>
            </div>
            <div class="icon">
              <i class="ion ion-social-usd"></i>
            </div>
            <a href="<?php echo base_url('wallet/withdrawn_list'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $users_count; ?></h3>

              <p>Registered Users</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo base_url('member/list_record');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $pending_approval_count; ?></h3>

              <p>Pending Approvals</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="<?php echo base_url('proposal/list_record'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
	  <h4>Accounting</h4>
	  <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo format_money($total_sales); ?></h3>
              <p>Total Sales</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-cart"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo format_money($total_profit); ?></h3>
              <p>Total Profit</p>
            </div>
            <div class="icon">
              <i class="ion ion-arrow-graph-up-right"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo format_money($total_commission); ?></h3>
              <p>Total Commission</p>
            </div>
            <div class="icon">
              <i class="fa fa-percent"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
       
        <!-- ./col -->
      </div>
      
      
	  <div class="row">
		<div class="col-sm-8">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Website Statistics</h3>
				</div>
				<div class="box-body table-responsive no-padding">
					<table class="table">
						<tr>
							<td><a href="<?php echo base_url('wallet/withdrawn_list'); ?>">Pending Withdrawn Request</a></td>
							<td><small class="label pull-right bg-green"><?php echo $withdrawn_request_count; ?></small></td>
						</tr>
						<tr>
							<td><a href="<?php echo base_url('proposal/list_record'); ?>">Proposals/Services Awaiting Approval</a></td>
							<td><small class="label pull-right bg-green"><?php echo $pending_approval_count;?></small></td>
						</tr>
						<tr>
							<td><a href="<?php echo base_url('buyer_request/list_record'); ?>">Buyer Requests Awaiting Approval</a></td>
							<td><small class="label pull-right bg-green"><?php echo $pending__request_approval_count;?></small></td>
						</tr>
						<tr>
							<td><a href="<?php echo base_url('admin_notification/list_record'); ?>">Unread Notification</a></td>
							<td><small class="label pull-right bg-green"><?php echo $unread_notification_count;?></small></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	  </div>
	  
		
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  