<h2 class="selectedpg_heading"> Other Page selected </h2>
<div class="form-group">
  <label for="meta_key_heading" class="col-sm-3 control-label title padding-left_no">Content Heading</label>
  <div>
    <input class="booktitle1" type="text" id="meta_key_heading" onKeyUp="booklet_control_text_keyup('heading')" 

    	name="meta_key_heading" value="<?php echo $heading; ?>"/>
  </div>
  <br>
  <label for="meta_key_description" class="col-sm-3 control-label title padding-left_no">Content</label><br />
  <textarea class="description3" id="meta_key_description" onKeyUp="booklet_control_text_keyup('description')" 
  	name="meta_key_description"><?php echo $description; ?></textarea>
  <p class="note-text">You may also use html tags like &lt;b&gt; for bold, &lt;i&gt; for italic and others here.</p>
</div>
<div>
  <input type="checkbox" name="meta_key_isheader" id="meta_key_isheader" <?php echo ($isheader == 'isheader' ? 'checked="checked"' : ''); ?> value="isheader" 

  	onclick="booklet_checkbox_controlvisiblity(this,['page_header_div','page_header_preview'])">
  Apply Header</div>
<br>
<div id="page_header_div" <?php echo ($isheader != 'isheader' ? 'style="display:none;"' : '') ?>>
  <label for="meta_key_page_heading" class="col-sm-3 control-label title">Page Heading</label>
  <input class="booktitle1" type="text" onKeyUp="booklet_control_text_keyup('page_heading')" id="meta_key_page_heading" 

  	name="meta_key_page_heading" value="<?php echo $page_heading; ?>"/>
</div>
<br>
<div>
  <input type="checkbox" name="meta_key_isfooter" onclick="booklet_checkbox_controlvisiblity(this, ['page_footer_div','page_footer_preview'])" 

  	id="meta_key_isfooter" <?php echo ($isfooter == 'isfooter' ? 'checked="checked"' : ''); ?> value="isfooter">
  Apply Footer</div>
<br>
<div id="page_footer_div" <?php echo ($isfooter != 'isfooter' ? 'style="display:none;"' : '') ?>>
  <label for="meta_key_page_footer" class="col-sm-3 control-label title">Page Footer</label>
  <input type="text" id="meta_key_page_footer" onKeyUp="booklet_control_text_keyup('page_footer')" name="meta_key_page_footer" 

  	value="<?php echo $page_footer; ?>">
  <input type="hidden" name = "page_number" id="page_number" value="<?php echo $page_number; ?>">
</div>
<br>
