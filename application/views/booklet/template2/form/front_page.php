<?php $front_image = config_item('bookletU_url') . $front_image; ?>
<div class="form-group">
  <h2 class="selectedpg_heading">Front Page Selected</h2>
  <div>Title Image</div>
 
	<input type="file" class="imagesuploader" preview-div-id = "front_image_preview" output-div-id = "front_image_output" 
		name="meta_key_front_image" id="meta_key_front_image" onChange="readURL('front_image_preview',this);" 
	/>
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
  <br />
  <label for="meta_key_text_line1" class="col-sm-3 control-label title">Text Box 1:</label>
  <div>
  <input type="text" id="meta_key_text_line1" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_line1')" name="meta_key_text_line1" 
value="<?php echo $text_line1; ?>"/>
  </div>
 <br>
  <label for="meta_key_text_line2" class="col-sm-3 control-label title">Text Box 2</label><br />
  <textarea id="meta_key_text_line2" class="description1" onKeyUp="booklet_control_text_keyup('text_line2')" 

  	name="meta_key_text_line2" ><?php echo $text_line2; ?></textarea>
  <p class="note-text">You may also use html tags like &lt;b&gt; for bold, &lt;i&gt; for italic and others here.</p>
</div>
  <br>
  <label for="meta_key_text_name" class="col-sm-3 control-label title">Name:</label>
  <div>
    <input type="text" id="meta_key_text_name" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_name')" 
    name="meta_key_text_name"  value="<?php echo $text_name; ?>"/>
  </div>
  <br />
  <label for="meta_key_text_line3" class="col-sm-3 control-label title">Text Box 3:</label>
  <div>
  <input type="text" id="meta_key_text_line3" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_line3')" name="meta_key_text_line3" 
value="<?php echo $text_line3; ?>"/>
  </div>
 <br>
  <label for="meta_key_text_line4" class="col-sm-3 control-label title">line4:</label>
  <div>
    <input type="text" id="meta_key_text_line4" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_line4')" name="meta_key_text_line4" 

  	value="<?php echo $text_line4; ?>"/>
  </div>
  

  <br>
  <label for="meta_key_text_address" class="col-sm-3 control-label title">Address:</label>
  <div>
    <input type="text" id="meta_key_text_address" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_address')" name="meta_key_text_address" 

  	value="<?php echo $text_address; ?>"/>
  </div>
<input type="hidden" name = "page_number" id="page_number" value="1">
</div>
<br>

