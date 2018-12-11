<!-----(slider-2)----->

<div class="slider-banner">
  <h2 class="content"><?php echo $title; ?></h2>
</div>
<div class="clearfix"></div>
<!-----(title)----->
<div class="container">
  <div class="default-heading-content">
    <div class="row"> 
      <!-- set document page code-->
      
      <div class="col-xs-12 col-sm-8 col-md-8">
        <h3 class="document-title">Select a Template</h3>
        <hr>
        <!----------->
        <?php 
		$baseurl = config_item('base_url'); 
		
		foreach($products as $product){ ?>
          <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="doc-img"> 
            <a href="<?php echo $baseurl . 'product/single/' . $product['id']; ?>">
            	<img src="<?php echo config_item('media_url') . 'images/product/'. $product['product_image']; ?>" class="img-responsive" /></a>
              <p class="image-title"><a href="<?php echo $baseurl . 'product/single/' . $product['id']; ?>"><?php echo $product['Name']; ?></a> </p>
            </div>
          </div>
        <?php } ?>
      </div>
      
      <!------Side Bar----->
      <div class="col-xs-12 col-sm-4 col-md-4">
        <?php $this->load->view('pages/sidebar.php'); ?>
      </div>
      <!--------------------End--> 
    </div>
    <!--end set document page code--> 
    
    <?php echo $content; ?> </div>
</div>
