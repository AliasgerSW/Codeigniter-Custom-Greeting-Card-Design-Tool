<?php $this->load->view("bookletpdf/include/header.php"); ?>
<?php $front_image = config_item('bookletU_url') . $front_image; ?>
<?php /*?><div class="front_right_book">
  <div class="front_right_book-body text-center">
    <div class="book_title"><?php echo $tag_line; ?></div>
    <div class="name-text"><?php echo $author; ?></div>
    <div class="date"><?php echo $date_descr; ?></div>
    <img alt="your image" src="<?php echo $front_image; ?>" id="front_image_preview" class="img" style="width: 158px; height: 210px;">
    <div class="description"><?php echo $description; ?></div>
  </div>
</div><?php */?>
<div class="front_right_book">
  <div class="front_right_book-body text-center">
    <div class="book_title_main"><?php echo $tag_line; ?></div>
    <div class="name-text"><?php echo $author; ?></div>
    <div class="date"><?php echo $date_descr; ?></div>
    <img alt="your image" src="<?php echo $front_image; ?>" class="img" style="width: 225px; height: 260px;">
    <div class="description"><?php echo $description; ?></div>
  </div>
</div>
<?php $this->load->view("bookletpdf/include/footer.php"); ?>