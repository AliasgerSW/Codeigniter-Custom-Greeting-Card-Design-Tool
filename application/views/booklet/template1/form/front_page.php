<?php $front_image = config_item('bookletU_url') . $front_image; ?>

<div class="form-group">
  <h2 class="selectedpg_heading">Front Page Selected</h2>
  <div>Title Image</div>
  <input type="file" class="imagesuploader" preview-div-id = "front_image_preview" output-div-id = "front_image_output" 
  	name="meta_key_front_image" id="meta_key_front_image" onChange="readURL('front_image_preview',this);" />
  <input type="hidden" name="meta_key_front_image_x" id="meta_key_front_image_x" value="0" />
  <input type="hidden" name="meta_key_front_image_y" id="meta_key_front_image_y" value="0" />
  <input type="hidden" name="meta_key_front_image_w" id="meta_key_front_image_w" value="0" />
  <input type="hidden" name="meta_key_front_image_h" id="meta_key_front_image_h" value="0" />
  <input type="hidden" name="meta_key_front_image_jcropw" id="meta_key_front_image_jcropw" value="0" />
  <input type="hidden" name="meta_key_front_image_jcroph" id="meta_key_front_image_jcroph" value="0" />
  <div id="front_image_output">
    <?php if (!empty($front_image) && $front_image != '#'){ ?>
    <div class="thumbnail-div"> <img class='thumbnail' src='<?php echo $front_image; ?>' title=''/> <a href='javascript:void(0)' onclick="deletebookimage(this,'front_image_preview')" class='remove_pict'>x</a> </div>
    <?php } ?>
  </div>
  <label for="meta_key_tag_line" class="col-sm-3 control-label title">Line 1</label>
  <div>
    <input type="text" id="meta_key_tag_line" class="booktitle1" onKeyUp="booklet_control_text_keyup('tag_line')" name="meta_key_tag_line" 
    	value="<?php echo $tag_line; ?>"/>
  </div>
  <br>
  <label for="meta_key_author" class="col-sm-3 control-label title">Line 2</label>
  <div>
    <input type="text" id="meta_key_author" class="booktitle1" onKeyUp="booklet_control_text_keyup('author')" name="meta_key_author" 

  	placeholder="Name and Surname" value="<?php echo $author; ?>"/>
  </div>
  <br>
  <label for="meta_key_date_descr" class="col-sm-3 control-label title">Line 3</label>
  <div>
    <input type="text" id="meta_key_date_descr" class="booktitle1" onKeyUp="booklet_control_text_keyup('date_descr')" name="meta_key_date_descr" 

  	value="<?php echo $date_descr; ?>"/>
  </div>
  <br>
  <label for="meta_key_description" class="col-sm-3 control-label title">Description</label><br />
  <textarea id="meta_key_description" class="description1" onKeyUp="booklet_control_text_keyup('description')" 

  	name="meta_key_description" ><?php echo $description; ?></textarea>
  <p class="note-text">You may also use html tags like &lt;b&gt; for bold, &lt;i&gt; for italic and others here.</p>
</div>
<input type="hidden" name = "page_number" id="page_number" value="1">
</div>
<br>

