
<?php $front_image = config_item('bookletU_url') . $front_image; ?>
<div class="front_right_book-templet-2">
  <div class="front_right_book-body text-center">
  
     
    <div class="book_title booktitle" id="text_line1_preview"><?php echo $text_line1; ?></div>
    <div class="book_title booktitle temp_2" id="text_line2_preview"><?php echo $text_line2; ?></div>
    <div class="name-text" id="text_name_preview"><?php echo $text_name; ?></div>
    
	
	<div class="front_right-preview-pane">
		<div class="front_right_img_templet_2x front_right_img">
			<img  class="img jcrop-preview img_temp2x" id="front_image_preview" src="<?php  echo $front_image; ?>" alt="your image" />
		</div>
	</div>
    <div class="book_title booktitle_temp2" id="text_line3_preview"><?php echo $text_line3; ?></div>
    
 	<div class="description_temp2" id="text_line4_preview"><?php echo $text_line4; ?></div>
    <div class="description_temp2" id="text_address_preview"><?php echo $text_address; ?></div>
     
    
  </div>
</div>
