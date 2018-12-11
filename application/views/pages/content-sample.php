<!-----(slider-2)----->

<div class="slider-banner">
  <h2 class="content"><?php echo $title; ?></h2>
</div>
<div class="clearfix"></div>
<!-----(title)----->
<div class="container"> 
  <!-- <div class="default-heading-content">-->
  
  <div class="sample">
    <div class="row">
      <h3 class="title-sample">Popular Templates</h3>
      <!---------->
      <div class="col-md-5"> 
        <!-- About Compnay Item -->
        <div class="about-company-item"> 
          <!-- About Company Image -->
          <ul class="list-inline btn">
            <li><a href="#">Previous</a></li>
            |
            <li><a href="#">Next</a></li>
          </ul>
          <?php $baseurl = config_item('base_url'); ?>
          <a class="frnt-img" href="<?php echo $baseurl . 'product/single/1'; ?>"><img alt="" src="<?php echo config_item('media_url'); ?>images/frnt.png" class="img-responsive img-thumbnail">
          <p class="front-title">Front Page</p>
          </a> </div>
      </div>
      <!------------->
      <div class="padtop-25"></div>
      <div class="col-md-7"> 
        <!---------->
        <div class="col-md-4">
          <div class="sample-img"><a class="frnt-img" href="<?php echo $baseurl . 'product/single/1'; ?>"> <img alt="" src="<?php echo config_item('media_url'); ?>images/fram-2.png" class="img-responsive img-thumbnail"></a> </div>
        </div>
        <!---------->
        <div class="col-md-4">
          <div class="sample-img"><a class="frnt-img" href="<?php echo $baseurl . 'product/single/1'; ?>"> <img alt="" src="<?php echo config_item('media_url'); ?>images/fram3.png" class="img-responsive img-thumbnail"></a> </div>
        </div>
        <!---------->
        <div class="col-md-4">
          <div class="sample-img"><a class="frnt-img" href="<?php echo $baseurl . 'product/single/1'; ?>"> <img alt="" src="<?php echo config_item('media_url'); ?>images/fram-4.png" class="img-responsive img-thumbnail"></a> </div>
        </div>
        <!---------->
        <div class="col-md-12">
          <div class="sample-discription">
            <h3 class="title-sample">Description</h3>
            <p class="content-text">On the reverse you are able to upload your own picture. In the Images section below, select the left hand icon</p>
          </div>
        </div>
      </div>
      <!----------> 
    </div>
  </div>
</div>
<!--sample apge code--> 

<!--end sample page code--> 
<?php echo $content; ?>
</div>
</div>
