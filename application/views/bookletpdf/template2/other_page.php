<?php $this->load->view("bookletpdf/include/header.php"); ?>
<div class="front_right_book-templet-2">
  <div class="page_header_preview" id="page_header_preview" <?php (empty($isheader) ? 'style="display:none"' : ''); ?>>
    <h4 class="book_title_temp2" id="page_heading_preview"><?php echo $page_heading; ?></h4>
  </div>
  <span class="clear"></span> <br>
  <h3 class="book_title_temp2" id="heading_preview"><?php echo $heading; ?></h3>
  <div class="other_page_description" id="description_preview"><?php echo $description; ?></div>
  <div class="page_footer_preview" id="page_footer_preview" <?php (empty($isfooter) ? 'style="display:none"' : ''); ?>><?php echo $page_footer; ?></div>
</div>
<?php $this->load->view("bookletpdf/include/footer.php"); ?>