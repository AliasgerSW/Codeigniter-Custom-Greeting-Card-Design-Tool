<?php $this->load->view("bookletpdf/include/header.php"); ?>
<?php $front_image = config_item('bookletU_url') . $front_image; ?>
<div class="front_right_book-templet-2">
  <div class="front_right_book-body text-center">
    <div class="booktitle_temp_2" id="text_line1_preview"><?php echo $text_line1; ?></div>
    <div class="booktitle_temp_2" id="text_line2_preview"><?php echo $text_line2; ?></div>
    <div class="name-text" id="text_name_preview"><?php echo $text_name; ?></div>
    <div class="front_right_img_templet_2"><img class="img_temp2" id="front_image_preview" src="<?php  echo $front_image; ?>" alt="your image" /></div>
    <div class="book_title_temp2" id="text_line3_preview"><?php echo $text_line3; ?></div>
 	<div class="description_temp2" id="text_line4_preview"><?php echo $text_line4; ?></div>
    <div class="description_temp2" id="text_address_preview"><?php echo $text_address; ?></div>
    
  </div>
</div>
<?php $this->load->view("bookletpdf/include/footer.php"); ?>