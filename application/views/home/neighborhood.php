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



    <div class="container-fluid">

      <div class="row">

        <?php $this->load->view('admin/sidebar'); ?>





        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

          <?php echo alert(); ?>          

          <div class="panel panel-default">

            <div class="panel-heading">

              <h3>Neighborhood</h3>

            </div>

            <div class="row">              

              <div class="col-sm-11 col-md-10 main">              

                <?php echo form_open_multipart(cms_current_url()); ?>

                  <div class="form-group">

                    <label for="exampleInputEmail1">Title</label> 

                    <input type="text" class="form-control" name="title" id="" value="<?php if($neighbor) echo $neighbor->title; else echo set_value('title'); ?>" placeholder="Enter Title">

                    <span class="error"><?php echo form_error('title'); ?></span>

                  </div>

                  <div class="form-group">

                    <label for="">Excerpt</label>

                    <input type="text" class="form-control" name="excerpt" value="<?php if($neighbor) echo $neighbor->excerpt; else echo set_value('excerpt'); ?>" id="" placeholder="Enter Excerpt">

                   <span class="error"><?php echo form_error('excerpt'); ?></span>

                  </div>



                  <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="mceEditor form-control" name="description" rows="10"><?php if($neighbor) echo $neighbor->description; ?></textarea>
                    <span class="error"><?php echo form_error('description'); ?></span>
                  </div>


                  <div class="form-group">

                    <label for="exampleInputFile">Image</label>

                    <input type="file" name="userfile" id="exampleInputFile">

                    <!-- <p class="help-block">Example block-level help text here.</p> -->

                    <?php if (!empty($neighbor->image)): ?><br>

                        <img src="<?php echo base_url() ?>assets/uploads/home/<?php echo $neighbor->image; ?>" width='100'>

                    <?php endif ?>

                  </div>

                  <br>

                  <input type="submit" class="btn btn-primary" value="Update">

                </form>             

              </div>            

            </div>            

          </div>          

        </div>

      </div>

    </div>



    