<?php  $this->load->view('admin/include/head'); ?>
<?php foreach($css_files as $file): ?>
<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>

<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed"> 
  <!-- Side Overlay-->
  <aside id="side-overlay"> 
    <!-- Side Overlay Scroll Container -->
    <div id="side-overlay-scroll"> 
      <!-- Side Header -->
      <div class="side-header side-content"> 
        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
        <button class="btn btn-default pull-right" type="button" data-toggle="layout" data-action="side_overlay_close"> <i class="fa fa-times"></i> </button>
        <!--<span>
                            <img class="img-avatar img-avatar32" src="assets/img/avatars/avatar10.jpg" alt="">
                            <span class="font-w600 push-10-l">Roger Hart</span>
                        </span>--> 
      </div>
      <!-- END Side Header --> 
      
      <!-- Side Content -->
      <div class="side-content remove-padding-t"> 
        <!-- Notifications -->
        <div class="block pull-r-l">
          <div class="block-header bg-gray-lighter"> </div>
        </div>
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
    <!-- demo_page_start -->
    <div class="block block-bordered">
      <div class="block-header bg-gray-lighter">
        <h3 class="block-title"><?php echo $page_title;?></h3>
      </div>
      <div class="block-content">
        <div class="row">
        <?php
			if (isset($page_descr) && !empty($page_descr)){
				echo '<div class="col-lg-12">' . $page_descr . '</div>';
			}
		?>
          <div class="col-lg-12"> <?php echo $output; ?> </div>
        </div>
      </div>
    </div>
    <!-- demo_page_end --> 
  </main>
  <!-- END Main Container -->
  
  <?php $this->load->view('admin/include/footer'); ?>
</div>
<!-- END Page Container --> 

<!-- Apps Modal --> 
<?php $this->load->view('admin/include/app-modal'); ?>
<!-- END Apps Modal -->

<?php  $this->load->view('admin/include/footer_script'); ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>		

<!-- Page Plugins --> 
<script src="<?php echo config_item('asset_url');?>js/plugins/slick/slick.min.js"></script> 
<?php
	if (isset($page_scripts) && !empty($page_scripts)){
		echo $page_scripts;
	}
?>

<!-- Page JS Code --> 
<script>
$(function () {
	// Init page helpers (Slick Slider plugin)
	App.initHelpers('slick');
});
</script>
</body></html>