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
          <?php echo alert(); ?>
              <h3>Add Trainer</h3>
                <?php echo form_open_multipart(cms_current_url(), array('class'=>'tasi-form')); ?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">First Name</label> 
                    <input type="text" class="form-control" name="fname" id="" value="<?php echo set_value('fname'); ?>" placeholder="First Name">
                    <span class="error"><?php echo form_error('fname'); ?></span>
                  </div>  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Last Name</label> 
                    <input type="text" class="form-control" name="lname" id="" value="<?php echo set_value('lname'); ?>" placeholder="Last Name">
                    <span class="error"><?php echo form_error('lname'); ?></span>
                  </div>
                    <div class="form-group">
                    <label for="exampleInputEmail1">Email Address</label> 
                    <input type="text" class="form-control" name="email" id="" value="<?php echo set_value('email'); ?>" placeholder="Email">
                    <span class="error"><?php echo form_error('email'); ?></span>
                  </div>  
                    <div class="form-group">
                    <label for="exampleInputEmail1">Password</label> 
                    <input type="password" class="form-control" name="password" id="" value="<?php echo set_value('password'); ?>" placeholder="Password">
                    <span class="error"><?php echo form_error('password'); ?></span>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" id="exampleInputConfim" value="<?php echo set_value('confirm_password'); ?>" placeholder="Confirm Password">
                    <span class="error"><?php echo form_error('confirm_password'); ?></span>
                  </div>
                   <div class="form-group">
                    <label for="exampleInputEmail1">Address</label> 
                    <input type="text" class="form-control" name="address" id="" value="<?php echo set_value('address'); ?>" placeholder="Address">
                    <span class="error"><?php echo form_error('address'); ?></span>
                  </div>
                     <div class="form-group">
                    <label for="exampleInputEmail1">City</label> 
                    <input type="text" class="form-control" name="city" id="" value="<?php echo set_value('city'); ?>" placeholder="City">
                    <span class="error"><?php echo form_error('city'); ?></span>
                  </div>

                  <br>
                  <input type="submit" class="btn btn-blue" value="Submit">
                </form>