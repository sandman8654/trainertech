
              <?php echo alert(); ?>   
              <h3>Change Password</h3>
              <?php echo form_open_multipart(cms_current_url(), array('class'=>'tasi-form')); ?>
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
                  <input type="submit" class="btn btn-blue" value="Change Password">
              <?php echo form_close(); ?>