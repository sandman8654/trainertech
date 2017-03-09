  <section class="contant">

          <?php echo alert(); ?>          
          <h3>Edit Slider</h3>
          <?php echo form_open_multipart(cms_current_url(),array('class'=>'tasi-form')); ?>
            <div class="form-group">
              <label for="exampleInputEmail1">Content</label> 
              <input type="text" class="form-control" name="content" id="" value="<?php echo $app_slider->content; ?>" placeholder="Content">
              <span class="error"><?php echo form_error('content'); ?></span>
            </div>  

            <div class="form-group">
              <label for="exampleInputEmail1">Order</label> 
              <input type="text" class="form-control" name="order" id="" value="<?php echo $app_slider->order; ?>" placeholder="Order">
              <span class="error"><?php echo form_error('order'); ?></span>
            </div>  
            
            <input type="submit" class="btn btn-blue" value="Submit">
          </form>
          </section>