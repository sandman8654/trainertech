          <?php echo alert(); ?>          
          <h3>Add Sub-Group</h3>
          <?php echo form_open_multipart(cms_current_url(), array('class' => 'tasi-form')); ?>
            <div class="form-group">
              <label for="exampleInputEmail1">Name</label> 
              <input type="text" class="form-control" name="name" id="" value="<?php echo set_value('name'); ?>" placeholder="Name">
              <span class="error"><?php echo form_error('name'); ?></span>
            </div>  
            
            <br>
            <input type="submit" class="btn btn-blue" value="Submit">
          </form>