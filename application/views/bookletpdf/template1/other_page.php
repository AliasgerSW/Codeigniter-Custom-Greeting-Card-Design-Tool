<?php $this->load->view("bookletpdf/include/header.php"); ?>
<?php /*?><div class="front_right_book other-frm">
  <?php if (!empty($isheader)){ ?>
  <div class="page_header_preview">
    <h4 class="book_title"><?php echo $page_heading; ?></h4>
  </div>
  <?php } ?>
  <span class="clear"></span> <br>
  <h3 class="book_title"><?php echo $heading; ?></h3>
  <div class="other_page_description"><?php echo $description; ?></div>
  <?php if (!empty($isfooter)){ ?>
  <div class="page_footer_preview"><?php echo $page_footer; ?></div>
  <?php } ?>
</div><?php */?>
<div class="Other_book">
  <?php if (!empty($isheader)){ ?>
    <div class="page_header_preview">
      <h4 class="book_title_second"><?php echo $page_heading; ?></h4>
    </div>
  <?php } ?>  
  <span class="clear"></span> <br>
  <h3 class="other_page_title"><?php echo $heading; ?></h3>
  <div class="other_page_description"><?php echo $description; ?></div>
  <?php if (!empty($isfooter)){ ?>
    <div class="page_footer_preview"><?php echo $page_footer; ?></div>
  <?php } ?>  
</div>
<?php $this->load->view("bookletpdf/include/footer.php"); ?>