<?php $back_image = config_item('bookletU_url') . $back_image; ?>
 
<div class="form-group">
  <h2 class="selectedpg_heading"> Back Page Selected </h2>
  <!--<h3 class="title-text">Front Page Selected</h3>-->
  <input type="file" class="imagesuploader" preview-div-id = "back_image_preview" output-div-id = "back_image_output" 
  	name="meta_key_back_image" id="meta_key_back_image" onChange="readURL('back_image_preview',this);" />
  <input type="hidden" name="meta_key_back_image_x" id="meta_key_back_image_x" value="0" />
  <input type="hidden" name="meta_key_back_image_y" id="meta_key_back_image_y" value="0" />
  <input type="hidden" name="meta_key_back_image_w" id="meta_key_back_image_w" value="0" />
  <input type="hidden" name="meta_key_back_image_h" id="meta_key_back_image_h" value="0" />
  <input type="hidden" name="meta_key_back_image_jcropw" id="meta_key_back_image_jcropw" value="0" />
  <input type="hidden" name="meta_key_back_image_jcroph" id="meta_key_back_image_jcroph" value="0" />
    
  <div id="back_image_output">
    <?php if (!empty($back_image) && $back_image != '#'){ ?>
    <div class="thumbnail-div"> <img class='thumbnail' src='<?php echo $back_image; ?>' title=''/> <a href='javascript:void(0)' onclick="deletebookimage(this,'back_image_preview')" class='remove_pict'>x</a> </div>
    <?php } ?>
  </div>
  <label for="meta_key_back_description" class="col-sm-3 control-label title-decription">Poem /Quote</label><br/>
  <textarea id="meta_key_back_description" onKeyUp="booklet_control_text_keyup('back_description')" class="description2" name="meta_key_back_description" ><?php echo $back_description; ?></textarea>
  <p class="note-text">You may also use html tags like &lt;b&gt; for bold, &lt;i&gt; for italic and others here.</p>
</div>
<br>
<input type="hidden" name = "page_number" id="page_number" value="-1">
