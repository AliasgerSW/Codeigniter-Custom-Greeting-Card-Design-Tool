<?php $front_image = config_item('bookletU_url') . $front_image; ?>
<div class="front_right_book">
  <div class="front_right_book-body text-center">
    <div class="book_title booktitle" id="tag_line_preview"><?php echo $tag_line; ?></div>
    <div class="name-text" id="author_preview"><?php echo $author; ?></div>
    <div class="date" id="date_descr_preview"><?php echo $date_descr; ?></div>
    <div class="front_right-preview-pane" id="front_right-preview-pane">
      <div class="front_right_img">
        <img class="img jcrop-preview" id="front_image_preview" style="height:258px; width:222px;" src="<?php echo $front_image; ?>" alt="your image" />
      </div>
	  </div>
    <div class="description" id="description_preview"><?php echo $description; ?></div>
  </div>
</div>
