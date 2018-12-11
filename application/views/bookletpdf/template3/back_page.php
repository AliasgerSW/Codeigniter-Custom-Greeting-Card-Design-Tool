<?php $this->load->view("bookletpdf/include/header.php"); ?>
<?php $back_image = config_item('bookletU_url') . $back_image; ?>
<div class="front_right_book-templet-3">
  <div class="front_right_book-body text-center">
  <div class="front_right_img_templet_3"><img class="img_back"  id="back_image_preview" src="<?php echo $back_image; ?>" alt="your image" /></div>
    <div class="description-back" id="back_description_preview"><?php echo $back_description; ?></div>
  </div>
</div>
<?php $this->load->view("bookletpdf/include/footer.php"); ?>