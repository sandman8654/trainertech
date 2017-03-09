    <div class="container-fluid">
      <div class="row">
        

        <div class="col-sm-12 main">
          <?php echo alert(); ?>          
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3>Change Password</h3>
            </div>
            <div class="row">              
              <div class="col-sm-11 col-md-10 main">              
                <?php echo form_open_multipart(cms_current_url()); ?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Old Password</label> 
                    <input type="password" class="form-control" name="old" id="" value="<?php echo set_value('old'); ?>" placeholder="Enter Old Password">
                    <span class="error"><?php echo form_error('old'); ?></span>
                  </div>                 

                  <div class="form-group">
                    <label for="exampleInputEmail1">New Password</label> 
                    <input type="password" class="form-control" name="new" id="" value="<?php echo set_value('new'); ?>" placeholder="Enter New Password">
                    <span class="error"><?php echo form_error('new'); ?></span>
                  </div>                 

                  <div class="form-group">
                    <label for="exampleInputEmail1">Confirm Password</label> 
                    <input type="password" class="form-control" name="con" id="" value="<?php echo set_value('con'); ?>" placeholder="Re-enter new password to confirmation" >
                    <span class="error"><?php echo form_error('con'); ?></span>
                  </div>                 
                  
                  <br>
                  <input type="submit" class="btn btn-primary" value="Change Password">
                </form>
              
              </div>
            
            </div>
            
          </div>

          
        </div>
      </div>
    </div>

    