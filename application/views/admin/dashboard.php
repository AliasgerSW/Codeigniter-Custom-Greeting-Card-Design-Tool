<?php $this->load->view('admin/include/head'); ?>
<!-- Page Container -->
<!--
            Available Classes:

            'sidebar-l'                  Left Sidebar and right Side Overlay
            'sidebar-r'                  Right Sidebar and left Side Overlay
            'sidebar-mini'               Mini hoverable Sidebar (> 991px)
            'sidebar-o'                  Visible Sidebar by default (> 991px)
            'sidebar-o-xs'               Visible Sidebar by default (< 992px)

            'side-overlay-hover'         Hoverable Side Overlay (> 991px)
            'side-overlay-o'             Visible Side Overlay by default (> 991px)

            'side-scroll'                Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (> 991px)

            'header-navbar-fixed'        Enables fixed header
        -->

<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed">
<!-- Side Overlay-->
<aside id="side-overlay"> 
  <!-- Side Overlay Scroll Container -->
  <div id="side-overlay-scroll"> 
    <!-- Side Header -->
    <div class="side-header side-content"> 
      <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
      <button class="btn btn-default pull-right" type="button" data-toggle="layout" data-action="side_overlay_close"> <i class="fa fa-times"></i> </button>
      <span>
      <?php /*?><img class="img-avatar img-avatar32" src="<?php echo config_item('asset_url');?>img/avatars/avatar10.jpg" alt=""><?php */?>
      <span class="font-w600 push-10-l">Roger Hart</span> </span> </div>
    <!-- END Side Header --> 
    
    <!-- Side Content -->
    <div class="side-content remove-padding-t"> 
      <!-- END Notifications --> 
      <!-- Online Friends --> 
      <!-- Quick Settings -->
      <div class="block pull-r-l">
        <div class="block-header bg-gray-lighter">
          <ul class="block-options">
            <li>
              <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
            </li>
          </ul>
          <h3 class="block-title">Quick Settings</h3>
        </div>
        <div class="block-content"> 
          <!-- Quick Settings Form -->
          <form class="form-bordered" action="index.html" method="post" onsubmit="return false;">
            <div class="form-group">
              <div class="row">
                <div class="col-xs-8">
                  <div class="font-s13 font-w600">Online Status</div>
                  <div class="font-s13 font-w400 text-muted">Show your status to all</div>
                </div>
                <div class="col-xs-4 text-right">
                  <label class="css-input switch switch-sm switch-primary push-10-t">
                    <input type="checkbox">
                    <span></span> </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-xs-8">
                  <div class="font-s13 font-w600">Auto Updates</div>
                  <div class="font-s13 font-w400 text-muted">Keep up to date</div>
                </div>
                <div class="col-xs-4 text-right">
                  <label class="css-input switch switch-sm switch-primary push-10-t">
                    <input type="checkbox">
                    <span></span> </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-xs-8">
                  <div class="font-s13 font-w600">Notifications</div>
                  <div class="font-s13 font-w400 text-muted">Do you need them?</div>
                </div>
                <div class="col-xs-4 text-right">
                  <label class="css-input switch switch-sm switch-primary push-10-t">
                    <input type="checkbox" checked>
                    <span></span> </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-xs-8">
                  <div class="font-s13 font-w600">API Access</div>
                  <div class="font-s13 font-w400 text-muted">Enable/Disable access</div>
                </div>
                <div class="col-xs-4 text-right">
                  <label class="css-input switch switch-sm switch-primary push-10-t">
                    <input type="checkbox" checked>
                    <span></span> </label>
                </div>
              </div>
            </div>
          </form>
          <!-- END Quick Settings Form --> 
        </div>
      </div>
      <!-- END Quick Settings --> 
    </div>
    <!-- END Side Content --> 
  </div>
  <!-- END Side Overlay Scroll Container --> 
</aside>
<!-- END Side Overlay -->

<?php $this->load->view('admin/include/sidebar'); ?>
<?php $this->load->view('admin/include/header'); ?>
<!-- Main Container -->
<main id="main-container">
<!-- Page Header -->
<div class="content bg-image overflow-hidden" <?php /*?>style="background-image: url(../../../media/images/banner.jpg);"<?php */?>>
  <div class="push-50-t push-15" >
    <h1 class="h2 text-white animated zoomIn">Dashboard</h1>
    <h2 class="h5 text-white-op animated zoomIn">Welcome <?php echo $this->session->userdata('name'); ?></h2>
    
  </div>
</div>
<!-- END Page Header --> 

<!-- Stats --> 

<!-- END Stats --> 
<div class="content bg-white border-b">
                    <div class="row items-push text-uppercase">
                        <div class="col-xs-12 col-sm-4">
                            <div class="font-w700 text-gray-darker animated fadeIn">Product Sales</div>
                            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Today</small></div>
                            <a class="h2 font-w300 text-primary animated flipInX" href="base_comp_charts.html"><?php echo number_format($today_sales, 2); ?></a>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="font-w700 text-gray-darker animated fadeIn">Product Sales</div>
                            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> This Month</small></div>
                            <a class="h2 font-w300 text-primary animated flipInX" href="base_comp_charts.html"><?php echo number_format($total_sales, 2); ?></a>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="font-w700 text-gray-darker animated fadeIn">Total Earnings</div>
                            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> All Time</small></div>
                            <a class="h2 font-w300 text-primary animated flipInX" href="base_comp_charts.html"><?php echo get_setting('currencysign') . ' ' . number_format($total_earnings, 2); ?></a>
                        </div>
                    </div>
                </div>

<!-- Page Content -->
<div class="content">
<div class="row">
<div class="col-lg-12"> 
<div class="content content-boxed">
                    <div class="row">
                        <div class=" col-lg-12">
                            <!-- Timeline -->
                            <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-shopping-cart"></i>Pending Order</h3>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <td class="text-right">User Name</td>
          <td>Template</td>
          <td>Copies</td>
          <td>Pages</td>
          <td class="text-right">Address</td>
          <td class="text-right">Status</td>
        </tr>
      </thead>
      
       <?php 
	//echo '<pre/>'; print_r($order_detail); 
	if (!empty ($order_detail)){
		foreach($order_detail as $or){
		?>
        
        
        
      <tbody>
                        <tr>
          <td class="text-right"><?php echo ucfirst($or['username'])?></td>
          <td><?php echo $or['template_name'];?></td>
          <td><?php echo $or['NoOfCopies'];?></td>
          <td><?php echo $or['NoOfPages'];?></td>
          <td class="text-right"><?php echo "Address:". $or['address1'];?><?php echo ",".$or['address2'];?>, &nbsp;<?php echo "City:". $or['city'];?>, &nbsp;<?php echo "Country:". $or['country'];?></td>
           <td><?php echo $or['order_status_id'];?></td>
          <!--<td class="text-right"><a class="btn btn-info" title="" data-toggle="tooltip" href="http://demo.opencart.com/admin/index.php?route=sale/order/info&amp;token=1f576f8cd45c2e1145eecc70ff6245e8&amp;order_id=1913" data-original-title="View"><i class="fa fa-eye"></i></a></td>-->
        </tr>
                
        <?php } }	 ?>
                      </tbody>
    </table>
  </div>
</div>
  <!-- Main Dashboard Chart -->
  <?php /*?><div class="block">
    <div class="block-header">
      <ul class="block-options">
        <li>
          <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
        </li>
      </ul>
      <h3 class="block-title"></h3>
    </div>
    <div class="block-content block-content-full bg-gray-lighter text-center"> 
      <!-- Chart.js Charts (initialized in js/pages/base_pages_dashboard.js), for more examples you can check out http://www.chartjs.org/docs/ -->
      <div style="height: 374px;">
        <canvas class="js-dash-chartjs-lines"></canvas>
      </div>
    </div>
    <div class="block-content text-center">
      <div class="row items-push text-center"> </div>
    </div>
  </div><?php */?>
  <!-- END Main Dashboard Chart --> 
</div>
<div class="row">
<div class="col-lg-8"> 


  <!-- News -->
  
  <?php $this->load->view('admin/include/footer'); ?>
</div>
<!-- END Page Container --> 

<!-- Apps Modal -->
<?php $this->load->view('admin/include/app-modal'); ?>
<!-- END Apps Modal -->

<?php  $this->load->view('admin/include/footer_script'); ?>

<!-- Page Plugins --> 
<script src="<?php echo config_item('asset_url');?>js/plugins/slick/slick.min.js"></script> 
<script src="<?php echo config_item('asset_url');?>js/plugins/chartjs/Chart.min.js"></script> 

<!-- Page JS Code --> 
<script src="<?php echo config_item('asset_url');?>js/pages/base_pages_dashboard.js"></script> 
<script>
$(function () {
	// Init page helpers (Slick Slider plugin)
	App.initHelpers('slick');
});
</script>
</body>
</html>
