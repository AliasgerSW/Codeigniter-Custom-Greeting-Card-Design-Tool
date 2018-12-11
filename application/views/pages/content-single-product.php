<!-----(slider-2)----->

<div class="slider-banner">
  <h2 class="content"><?php echo $title; ?></h2>
</div>
<div class="clearfix"></div>
<!-----(title)----->
<div class="container">
  <div class="default-heading-content">
    <div class="row"> 
      <!---------->
      <div class="col-xs-12 col-sm-12 col-md-12">
        <br />
        <!----------->
        <div class="col-xs-12 col-sm-5 col-md-5">
          <div class="templet-img"><img src="<?php echo config_item('media_url') . 'images/product/'. $product['product_image']; ?>" class="img-responsive" /></div>
          <div class="padbottom-15"></div>
        </div>
        <!--------->
        <div class="col-xs-12 col-sm-7 col-md-7">
          <div class="customize-btn">
            <a class="btn btn-default" href="<?php echo config_item('base_url') . 'product/customize/' . $product['id']; ?>" role="button">Customize this Template</a>
          </div>
          <div class="sidebar-document">
            <div class="side-box">
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#overview">Overview</a></li>
                <li class=""><a data-toggle="tab" href="#pricing">Pricing</a></li>
                <li class=""><a data-toggle="tab" href="#size">Size</a></li>
                <!--<li class=""><a data-toggle="tab" href="#delivery">Delivery Options</a></li>-->
              </ul>
              <!---------->
              <div class="tab-content">
                <div id="overview" class="tab-pane fade active in"> <?php echo $product['Descr'] ?> </div>
                <?php $currsign = get_setting('currencysign'); //config_item('currencysign'); ?>
                <div id="pricing" class="tab-pane fade">
                  <p class="pricing-title">Below booklet prices are having <?php echo $product['NoOfPages'] ?> pages in it. Prices of Extra pages will be <b><?php echo $currsign . $product['color']['priceperpage']; ?></b> and <b><?php echo $currsign . $product['blackwhite']['priceperpage']; ?></b> for Color and Black & White respectively. </p>
                  <hr>
                  <div class="row">
                    <div class="col-sm-6">
                      <p class="pricing-title">Prices of Color Copies</p>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th colspan="2">QUANTITY</th>
                              <th colspan="3">PRICE</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach($productprices as $key => $productprice){ ?>
                            <tr>
                              <td colspan="2"><?php echo $productprice['rangeto']; ?></td>
                              <td><?php echo $currsign . $productprice['pricecolor']; ?></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <p class="pricing-title">Prices of Black & White Copies</p>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th colspan="2">QUANTITY</th>
                              <th colspan="3">PRICE</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach($productprices as $key => $productprice){ ?>
                            <tr>
                              <td colspan="2"><?php echo $productprice['rangeto']; ?></td>
                              <td><?php echo $currsign . $productprice['pricebw']; ?></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="size" class="tab-pane fade"> <?php echo $product['Descr2'] ?> </div>
              </div>
            </div>
          </div>
          <div class="padbottom-15"></div>
        </div>
      </div>
    </div>
  </div>
</div>
