<?php $this->load->view("bookletpdf/include/header.php"); ?>
<?php $front_image1 = config_item('bookletU_url') . $front_image1; ?>
<?php $front_image2 = config_item('bookletU_url') . $front_image2; ?>
<div class="front_right_book-templet-3">
  <div class="front_right_book-body text-center">
	
   
   
   <!---->
    <div  style="max-width: 250px;margin-top:120px; margin-left:80px;height: 80px">
   <div class="front_right_img1_templet_3"><img class="img_temp3" id="temp3_front_image1_preview" src="<?php echo $front_image1; ?>" alt="your image" /></div>
     <div class="front_right_img2_templet_3"><img class="img_temp_second3" id="temp3_front_image_preview" src="<?php echo $front_image2; ?>" alt="your image" /></div>
     </div>
   <!---->
     <!--</div>-->
     
    <div class="book_title booktitle" id="text_line1_preview"><?php echo $text_line1; ?></div>
    <div class="book_title booktitle" id="text_line2_preview"><?php echo $text_line2; ?></div>
    <div class="name-text" id="text_line3_preview"><?php echo $text_line3; ?></div>
    <div class="date" id="text_line4_preview"><?php echo $text_line4; ?></div>
    
    <div class="description" id="text_descr_preview"><?php echo $text_descr; ?></div>
    <div class="description" id="text_line5_preview"><?php echo $text_line5; ?></div>
    <div class="description" id="text_line6_preview"><?php echo $text_line6; ?></div>
  </div>
</div>

<?php $this->load->view("bookletpdf/include/footer.php"); ?>