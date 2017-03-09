<section class="contant">
  <?php echo alert(); ?>   
  <h3>Social Links</h3>
  <?php echo form_open_multipart(cms_current_url(), array('class'=>'tasi-form')); ?>
    <div class="form-group">
                  <label class="" for="">Facebook</label>                  
                    <input  class="form-control" type="text" name="facebook" value="<?php if(!empty($link->facebook)) echo  $link->facebook; ?>">                  
                  <span style="color:red"><?php echo form_error('facebook') ?></span>
                  </div>

                  <div class="form-group">
                  <label class="control-label" for="">Twitter</label>                  
                    <input  class="form-control" type="text" name="twitter" value="<?php if(!empty($link->twitter)) echo  $link->twitter; ?>">                  
                  <span style="color:red"><?php echo form_error('twitter') ?></span>
                  </div>

                  <div class="form-group">
                  <label class="control-label" for="">instagram</label>                  
                    <input  class="form-control" type="text" name="instagram" value="<?php if(!empty($link->instagram)) echo  $link->instagram; ?>">                  
                  <span style="color:red"><?php echo form_error('instagram') ?></span>
                  </div>

                  <div class="form-group">
                  <label class="control-label" for="">Twitter Username</label>                  
                    <input  class="form-control" type="text" name="twitter_username" value="<?php if(!empty($link->twitter_username)) echo  $link->twitter_username; ?>">                  
                  <span style="color:red"><?php echo form_error('twitter_username') ?></span>
                  </div>  

                  <br>
                  <input type="submit" class="btn btn-primary" value="Update">

                </form>
</section>