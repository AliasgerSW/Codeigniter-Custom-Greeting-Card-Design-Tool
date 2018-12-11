<?php $front_image1 = config_item('bookletU_url') . $front_image1; ?>
<?php $front_image2 = config_item('bookletU_url') . $front_image2; ?>

<div class="form-group">
  <h2 class="selectedpg_heading">Front Page Selected</h2>
  <div>Title Image</div>
  
   <input type="file" class="imagesuploader" preview-div-id = "front_image1_preview" output-div-id = "front_image1_output" 

  	name="meta_key_front_image1" id="meta_key_front1_image" onChange="readURL('front_image1_preview',this);" />

  <input type="hidden" name="meta_key_front_image1_x" id="meta_key_front_image1_x" value="0" />
  <input type="hidden" name="meta_key_front_image1_y" id="meta_key_front_image1_y" value="0" />
  <input type="hidden" name="meta_key_front_image1_w" id="meta_key_front_image1_w" value="0" />
  <input type="hidden" name="meta_key_front_image1_h" id="meta_key_front_image1_h" value="0" />
  <input type="hidden" name="meta_key_front_image1_jcropw" id="meta_key_front_image1_jcropw" value="0" />
  <input type="hidden" name="meta_key_front_image1_jcroph" id="meta_key_front_image1_jcroph" value="0" />  
	
	<div id="front_image1_output">
    <?php if (!empty($front_image1) && $front_image1 != '#'){ ?>
    <div class="thumbnail-div"> <img class='thumbnail' src='<?php echo $front_image1; ?>' title=''/> <a href='javascript:void(0)' onclick="deletebookimage(this,'front_image1_preview')" class='remove_pict'>x</a> </div>
    <?php } ?>
  </div><br />
  <input type="file" class="imagesuploader" preview-div-id = "front_image2_preview" output-div-id = "front_image2_output" 
  	name="meta_key_front_image2" id="meta_key_front_image2" onChange="readURL('front_image2_preview',this);" />
  <input type="hidden" name="meta_key_front_image2_x" id="meta_key_front_image2_x" value="0" />
  <input type="hidden" name="meta_key_front_image2_y" id="meta_key_front_image2_y" value="0" />
  <input type="hidden" name="meta_key_front_image2_w" id="meta_key_front_image2_w" value="0" />
  <input type="hidden" name="meta_key_front_image2_h" id="meta_key_front_image2_h" value="0" />
  <input type="hidden" name="meta_key_front_image2_jcropw" id="meta_key_front_image2_jcropw" value="0" />
  <input type="hidden" name="meta_key_front_image2_jcroph" id="meta_key_front_image2_jcroph" value="0" />    
  <div id="front_image2_output">
    <?php if (!empty($front_image2) && $front_image2 != '#'){ ?>
    <div class="thumbnail-div"> <img class='thumbnail' src='<?php echo $front_image2; ?>' title=''/> <a href='javascript:void(0)' onclick="deletebookimage(this,'front_image2_preview')" class='remove_pict'>x</a> </div>
    <?php } ?>
  </div>
  <br />
   
  <label for="meta_key_text_line1" class="col-sm-3 control-label title">Text Line1:</label>
  <div>
    <input type="text" id="meta_key_text_line1" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_line1')" name="meta_key_text_line1" 

    	value="<?php echo $text_line1; ?>"/>
  </div>
  <br>
  <label for="meta_key_text_line2" class="col-sm-3 control-label title">Text Line2:</label>
  <div>
    <input type="text" id="meta_key_text_line2" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_line2')" name="meta_key_text_line2" 

  	 value="<?php echo $text_line2; ?>"/>
  </div>
  <br>
  <label for="meta_key_text_line3" class="col-sm-3 control-label title">Text Line3:</label>
  <div>
    <input type="text" id="meta_key_text_line3" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_line3')" name="meta_key_text_line3" 

  	placeholder="Name and Surname" value="<?php echo $text_line3; ?>"/>
  </div>
  <br>
  <label for="meta_key_text_line4" class="col-sm-3 control-label title">Text Line4:</label>
  <div>
    <input type="text" id="meta_key_text_line4" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_line4')" name="meta_key_text_line4" 

  	value="<?php echo $text_line4; ?>"/>
  </div>
  
  <br>
  <label for="meta_key_text_descr" class="col-sm-3 control-label title">Epitaph (change to suit or delete):</label><br />
  <textarea id="meta_key_text_descr" class="description1" onKeyUp="booklet_control_text_keyup('text_descr')" 

  	name="meta_key_text_descr" ><?php echo $text_descr; ?></textarea>
  <p class="note-text">You may also use html tags like &lt;b&gt; for bold, &lt;i&gt; for italic and others here.</p>
</div>
<br>
  <label for="meta_key_text_line5" class="col-sm-3 control-label title">Date and Time:</label>
  <div>
    <input type="text" id="meta_key_text_line5" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_line5')" name="meta_key_text_line5" 

  	value="<?php echo $text_line5; ?>"/>
  </div>
  <br>
  <label for="meta_key_text_line6" class="col-sm-3 control-label title">Location:</label>
  <div>
    <input type="text" id="meta_key_text_line6" class="booktitle1" onKeyUp="booklet_control_text_keyup('text_line6')" name="meta_key_text_line6" 

  	value="<?php echo $text_line6; ?>"/>
  </div>
<input type="hidden" name = "page_number" id="page_number" value="1">
</div>
<br>

