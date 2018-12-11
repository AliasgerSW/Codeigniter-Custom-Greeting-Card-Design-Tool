<!-- Sidebar -->

<nav id="sidebar"> 
  <!-- Sidebar Scroll Container -->
  <div id="sidebar-scroll"> 
    <!-- Sidebar Content --> 
    <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
    <div class="sidebar-content"> 
      <!-- Side Header -->
      <div class="side-header side-content bg-white-op"> 
        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
        <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close"> <i class="fa fa-times"></i> </button>
        <!-- Themes functionality initialized in App() -> uiHandleTheme() --> 
        <a class="h5 text-white" href="<?php echo base_url(); ?>"> <i ></i> <span class="h4 font-w600 sidebar-mini-hide">BOOKLET</span> </a> </div>
      <!-- END Side Header --> 
      
      <!-- Side Content -->
      <div class="side-content">
        <ul class="nav-main">
          <?php // $baseurl = config_item('base_url'); ?>
          <li> <a class="active" href="<?php echo base_url('admin/dashboard'); ?>"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a> </li>
          <li class="nav-main-heading"><span class="sidebar-mini-hide">Interface</span></li>
          <li> <a class="nav-menu" data-toggle="nav-submenu" href="<?php echo base_url('admin/dashboard/pages'); ?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Pages</span></a> </li>
          <li> <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-settings"></i><span class="sidebar-mini-hide">Order</span></a>
            <ul>
              <?php
          $dbvalues = getorderstatus();
          if (!empty($dbvalues) && count($dbvalues) > 0){ 
            foreach($dbvalues as $dbvalue){ ?>
              <li> <a class="nav-menu" href="<?php echo base_url('admin/dashboard/pendingorders/'. $dbvalue['order_status_id']); ?>"><i class="si si-badge"></i><span class="sidebar-mini-show"><?php echo ucwords($dbvalue['status_name']); ?> Orders</span>
              	</a></li>
            <?php } 
			  }
			?>
              <li> <a class="nav-menu" data-toggle="nav-submenu" href="<?php echo base_url('admin/dashboard/order'); ?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Order Status</span></a> </li>
            </ul>
          </li>
          <li> <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-settings"></i><span class="sidebar-mini-hide">Users</span></a>
            <ul>
              <li> <a href="<?php  echo base_url('admin/dashboard/users/add'); ?>">Add User</a> </li>
              <li> <a href="<?php echo base_url('admin/dashboard/users'); ?>">View User</a> </li>
            </ul>
          </li>
          <li> <a class="nav-menu" data-toggle="nav-submenu" href="<?php echo base_url('admin/dashboard/configuration'); ?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Configuration</span></a></li>
          <li> <a class="nav-menu" data-toggle="nav-submenu" href="<?php echo base_url('admin/dashboard/transaction'); ?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Transaction</span></a></li>
          <li> <a class="nav-menu" data-toggle="nav-submenu" href="<?php echo base_url('admin/dashboard/menu'); ?>"><i class="si si-badge"></i><span class="sidebar-mini-hide">Menu Configuration</span></a></li>
        </ul>
      </div>
      <!-- END Side Content --> 
    </div>
    <!-- Sidebar Content --> 
  </div>
  <!-- END Sidebar Scroll Container --> 
</nav>
<!-- END Sidebar -->