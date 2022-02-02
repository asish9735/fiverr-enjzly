 <?php
$this->load->model('admin_notification/admin_notification_model'); 
$admin_detail = get_session('admin_detail');
$menu_list = $this->permission_model->getUserMenu();
$uri_segment = uri_string();
$admin_notification_count = $this->admin_notification_model->getUnreadCount();
?>
 <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo ADMIN_IMAGES;?>avatar5.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $admin_detail['full_name']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form hidden">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
		<li class="<?php echo $uri_segment == 'dashboard' ? 'active' : '';?>">
          <a href="<?php echo base_url('dashboard')?>">
           <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">Hot</small>
            </span>
          </a>
        </li>
		<li class="<?php echo $uri_segment == 'admin_notification/list_record' ? 'active' : '';?>">
          <a href="<?php echo base_url('admin_notification/list_record')?>">
           <i class="fa fa-bell"></i> <span>Notification</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red"><?php echo $admin_notification_count; ?></small>
            </span>
          </a>
        </li>
		
		
		<?php if($menu_list){foreach($menu_list  as $k => $v){ 
		$childs = $v['child'];
		$isactive = '';
		$icon_class = null;
		if(!empty($v['style_class'])){
			if(stripos($v['style_class'], 'fa-') !== FALSE){
				$icon_class = 'fa '.$v['style_class'];
			}else{
				$icon_class = $v['style_class'];
			}
		}else{
			$icon_class = 'fa fa-bars';
		}
		foreach($childs as $c){
			if($uri_segment == $c['url']){
				$isactive = 'active';
				break;
			}
		}
		?>
		<li class="treeview <?php echo $isactive; ?>">
          <a href="#">
            <i class="<?php echo $icon_class; ?>"></i> <span><?php echo $v['name'];?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
			<?php if($childs){foreach($childs as $key => $child){ ?>
            <li class="<?php echo ($uri_segment == $child['url']) ? 'active' : '';?>"><a href="<?php echo base_url($child['url']);?>"><i class="fa fa-circle-o"></i><?php echo $child['name']; ?></a></li>
			<?php } } ?>
          </ul>
        </li>
		<?php } } ?>
        
      </ul>
    </section>
	
	
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->