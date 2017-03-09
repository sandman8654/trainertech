
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
  <?php echo alert(); ?>   
  <h3>Edit Trainee</h3>
  <?php echo form_open_multipart(cms_current_url(), array('class'=>'tasi-form')); ?>
  <div class="form-group">
                    <label for="exampleInputEmail1">Trainer</label> 
                    <select class="form-control" name="trainer_id">
                      <option value="">Select trainer</option>
                      <?php foreach($trainer as $row){ ?>
                        <option value="<?php echo $row->id ?>"  <?php if($trainee->trainer_id == $row->id) echo 'selected="selected"'; ?> ><?php echo $row->fname." ".$row->lname ?></option>
                      <?php } ?>
                    </select>
                    <span class="error"><?php echo form_error('trainer_id'); ?></span>
                  </div>

                  <div class="form-group">

                    <label for="exampleInputEmail1">First Name</label> 
                    <input type="text" class="form-control" name="fname" id="" value="<?php echo $trainee->fname;?>" placeholder="First Name">
                    <span class="error"><?php echo form_error('fname'); ?></span>
                  </div>   
                    <div class="form-group">
                    <label for="exampleInputEmail1">Last Name</label> 
                    <input type="text" class="form-control" name="lname" id="" value="<?php echo $trainee->lname; ?>" placeholder="Last Name">
                    <span class="error"><?php echo form_error('lname'); ?></span>
                  </div>                
                <div class="form-group">
                    <label for="exampleInputEmail1">Email Address</label> 
                    <input type="text" class="form-control" name="email" id="" value="<?php echo $trainee->email; ?>" placeholder="Email">
                    <span class="error"><?php echo form_error('email'); ?></span>
                  </div>  
                  
                   <div class="form-group">
                    <label for="exampleInputEmail1">Address</label> 
                    <input type="text" class="form-control" name="address" id="" value="<?php echo $trainee->address; ?>" placeholder="Address">
                    <span class="error"><?php echo form_error('address'); ?></span>
                  
                     <div class="form-group">
                    <label for="exampleInputEmail1">City</label> 
                    <input type="text" class="form-control" name="city" id="" value="<?php echo $trainee->city; ?>" placeholder="City">
                    <span class="error"><?php echo form_error('city'); ?></span>
                  </div>

                  <input type="submit" class="btn btn-primary" value="Update">
                    </div>
                </form>
</section>

   