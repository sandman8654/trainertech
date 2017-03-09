    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 col-md-12  main">
          <?php echo alert(); ?>          
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3>Add Query</h3>
            </div>
            <div class="row">              
              <div class="col-sm-12 col-md-12 main">              
                <?php echo form_open_multipart(cms_current_url()); ?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Subject</label> 
                    <input type="text" class="form-control" name="subject" id="" value="<?php echo set_value('subject'); ?>" placeholder="subject">
                    <span class="error"><?php echo form_error('subject'); ?></span>
                  </div>  

                  <div class="form-group">
                    <label for="exampleInputEmail1">Message</label> 
                    <textarea row="5" class="form-control" name="message" id="" ><?php echo set_value('message'); ?></textarea>
                    <span class="error"><?php echo form_error('message'); ?></span>
                  </div>  
                  
                  <br>
                  <input type="submit" class="btn btn-primary" value="Submit">
                </form>
              
              </div>
            
            </div>
            
          </div>

          
        </div>
      </div>
    </div>

    