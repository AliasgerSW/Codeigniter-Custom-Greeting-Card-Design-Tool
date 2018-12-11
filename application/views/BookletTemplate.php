<?php $this->load->view("pages/header.php"); ?>
<div class="color-divider"></div>
<div class="clearfix"></div>
<!-----(slider-2)-----> 
<!--<div class="slider"> <img src="<?php //echo config_item('media_url'); ?>images/title-banner.jpg" class="img-responsive" /> </div>-->
<div class="slider-banner">
  <h2 class="content"><?php echo $title; ?> - Customization</h2>
</div>
<div class="clearfix"></div>
<div class="padtop-15"></div>
<!--<div class="middle">-->
<div class="container">
  <?php if (isset($_REQUEST['msg'])) { ?>
  <div class="message" style="display:none;"><?php echo $_REQUEST['msg']; ?></div>
  <?php } ?>
  <div class="Form_Header_ExtraSpaceAbove form_holder_tbs">
    <div class="row">
      <div class="col-md-12">
        <p class="col-md-4 select_font text-center">Select Page
          <?php
		  $pagetype = 'front';
		  $pageno = 2;
		  if (isset($_REQUEST['pagetype']) && !empty($_REQUEST['pagetype'])){
			  $pagetype = $_REQUEST['pagetype'];
		  }
		  if (!in_array($pagetype, array('front','back','other'))){
			  $pagetype = 'front';
		  }
		  if (isset($_REQUEST['pageno']) && !empty($_REQUEST['pageno'])){
			  $pageno = $_REQUEST['pageno'];
		  } 

		  switch($pagetype){
			  case 'front':
				$pageno = 1;
				break;
			  case 'back':
				$pageno = -1;
				break;
			  default:
			  	  if ($pageno > $book_size){
					  $pageno = 2;
				  }
				  break;
		  }
		  ?>
          <select id="select-page"  name="selection" onchange="selectionchange(this.value);" >
            <option  value="front" <?php if ($pagetype == 'front') { ?> selected="selected" <?php } ?>>Front Page</option>
            <option value="back" <?php if ($pagetype == 'back') { ?> selected="selected" <?php } ?>>Back Page</option>
            <option value="other" <?php if ($pagetype == 'other') { ?> selected="selected" <?php } ?>>Other Page</option>
          </select>
          <input class="page-no" name="other" type="text" id="select-page-no" placeholder="Page No." value="<?php echo $pageno; ?>"  >
          <button type="submit" class="btn btn-primary show-btn" id="show-option" name="show" value="Show">Show</button>
        </p>
        <p class="col-md-3 Form_Header_ExtraSpaceAbove select_font text-center">Change Total No of Pages
          <input class="page-no" name="other" type="text" id="pages-input" placeholder="Pages" value="<?php echo $book_size; ?>">
          <button type="submit" class="btn btn-primary show-btn" id="pages-submit" name="show" value="Show">Update</button>
          <input type="hidden" name="current-book-size" type="text" id="current-book-size" value="<?php echo $book_size; ?>"  >
        </p>
        <p class="col-md-5 Form_Header_ExtraSpaceAbove select_font text-center">
        	<?php $currsign = get_setting('currencysign'); //config_item('currencysign'); ?>
            <select id="color_options_sel">
              <option value="1">Color</option>
              <option value="2">Black</option>
            </select>
            <select id="price_options_sel">
              <?php $isfirst = true; $defaultcopies = 0; ?>
			  <?php foreach($productprices as $key => $productprice){ ?>
                <option value="<?php echo $productprice['rangeto']; ?>">
                	Order <?php echo $productprice['rangeto']; ?> Copies for <?php echo $currsign . $productprice['pricecolor']; ?> only
                    <?php 
					if ($isfirst){
						$defaultcopies = $productprice['rangeto'];
						$isfirst = false;
					}
					?>
                </option>
              <?php } ?>
            </select>
          <input type="submit" class="btn btn-primary" id="add_to_cart_sel" value="Add to Cart">
        </p>
        <div class="clearfix"></div>
        <div class="col-md-6 no-padding">
          <form action="<?php echo config_item('base_url'); ?>BookletTemplate/update_template" id="customize-form" method="post" 
          	enctype="multipart/form-data">
            <div id="customize-form-div"> </div>
            <div id="button">
              <input type="submit" class="btn btn-primary" id="nav-prev" name="navprev" value="« Previous" <?php if ($pageno == 1) echo 'disabled'; ?> >
              <?php if ($pageno == -1) { ?>
              	<input type="submit" class="btn btn-primary" id="save-and-finish" name="update" value="Finish">
              <?php } else { ?>
              	<input type="submit" class="btn btn-primary" id="save-and-finish" name="update" value="Save & Continue »">
              <?php } ?>
              <input type="submit" class="btn btn-primary" name="preview_pdf" id="showpdfpreview" value="Preview Pdf">
              <input type="hidden" id="price_options" name="price_options" value="<?php echo $defaultcopies; ?>" />
              <input type="hidden" id="color_options" name="color_options" value="1" />
              <input type="submit" class="hide" name="add_to_cart" id="add_to_cart" value="Add to Cart">
              <p class="note-text">Note : Please save your changes before watching pdf</p>
            </div>
          </form>
          <!-- FRONT PAGE DIV--> 
          
        </div>
        <div class="col-md-6 no-padding"> 
          
          <!--front_right_book-->
          
          <div id="preview-form-div"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
jQuery('.message').show().delay(3000).fadeOut();
function deletebookimage(control, previewimgid){
	deletethumbnailandpreview('<?php echo config_item('base_url'); ?>', control, previewimgid);
}

function reloadpriceoption(colortype){
	var currsign = '<?php echo $currsign; ?>'
	showpriceof = 'pricecolor';
	if (colortype == '2'){
		showpriceof = 'pricebw';
	}
	jQuery.ajax({
		url : '<?php echo config_item('base_url'); ?>BookletTemplate/getproductprices',
		success: function(data){
			if (data != ""){
				jQuery("#price_options_sel").find("option").remove();
				var jsarray = jQuery.parseJSON(data);
				jQuery.each(jsarray, function(i,v){
					jQuery('#price_options_sel').append(jQuery('<option>', {
						value: v['rangeto'],
						text: 'Order ' + v['rangeto'] + ' Copies for ' + currsign + v[showpriceof] + ' only'
					}));
				});
			}
		}
	});
}
jQuery(document).ready(function(e){
	var baseurl = '<?php echo config_item('base_url'); ?>';
	load_both(baseurl);
	selectionchange(jQuery('#select-page').val());
	jQuery("#show-option").click(function(e) {
    	load_both(baseurl);
	})
	jQuery("#pages-submit").click(function(e) {
		var totpage = jQuery("#pages-input").val();
    	change_numberofpages('<?php echo config_item('base_url'); ?>', totpage);
	});
	jQuery("#price_options_sel").on('click keyup blur', function(e){
		jQuery("#price_options").val(jQuery(this).val());
	});
	jQuery("#color_options_sel").on('change keyup', function(e){
		jQuery("#color_options").val(jQuery(this).val());
		reloadpriceoption(jQuery(this).val());
	});
	jQuery("#add_to_cart_sel").click(function(e) {
        jQuery("#add_to_cart").click();
    });
	jQuery("#showpdfpreview").click(function(e) {
		e.preventDefault();
		window.open('<?php echo config_item('base_url'); ?>BookletTemplate/previewpdf', '_blank');
	});
});
</script>
<div class="clearfix"></div>
<?php $this->load->view("pages/footer.php");?>