    
    <!-- TinyMCE -->

<script type="text/javascript" src="<?php echo base_url() ?>assets/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">



  tinyMCE.init({

    mode : "textareas",
    editor_selector : "mceEditor",

    theme : "advanced",  

    plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks,openmanager",

    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,|,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,code,|,forecolor|,removeformat|,fullscreen",   

    

    file_browser_callback: "openmanager",

    open_manager_upload_path: '../../../../uploads/'

     }); 

</script>

<!-- /TinyMCE -->
<section class="contant">
<h3>Edit Plan</h3>
       
<?php echo form_open_multipart(cms_current_url(), array('class'=>'tasi-form')); ?>
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label> 
    <input type="text" class="form-control" name="name" id="" value="<?php echo $plan->name; ?>" placeholder="Name">
    <span class="error"><?php echo form_error('name'); ?></span>
  </div>  
  
  <div class="form-group">
    <label for="">Description</label>
    <textarea class="mceEditor form-control" name="description" rows="10"><?php echo $plan->description; ?></textarea>
    <span class="error"><?php echo form_error('description'); ?></span>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Price</label> 
    <input type="text" class="form-control" name="price" id="" value="<?php echo $plan->price; ?>" placeholder="Price">
    <span class="error"><?php echo form_error('price'); ?></span>
  </div> 

  <input type="submit" class="btn btn-primary" value="Update">
    </div>
</form>
</section>             