<div class="form-group">
  <h2 class="selectedpg_heading"> BACK Page selected </h2>
  <!--<h3 class="title-text">Front Page Selected</h3>-->
  
  <label for="meta_key_back_description" class="col-sm-3 control-label title-decription">Description</label><br/>
  <textarea id="meta_key_back_description" onKeyUp="booklet_control_text_keyup('back_description')" class="description2" name="meta_key_back_description" ><?php echo $back_description; ?></textarea>
  <p class="note-text">You may also use html tags like &lt;b&gt; for bold, &lt;i&gt; for italic and others here.</p>
</div>
<br>
<input type="hidden" name = "page_number" id="page_number" value="-1">
