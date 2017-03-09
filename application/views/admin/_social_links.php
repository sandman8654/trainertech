<div class="container-fluid">

      <div class="row">

        <?php $this->load->view('admin/sidebar'); ?>





        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

          <?php echo alert(); ?>          

          <div class="panel panel-default">

            <div class="panel-heading">

              <h3>Social Links</h3>

            </div>

            <div class="row">              

              <div class="col-sm-11 col-md-10 main">              

                <?php echo form_open_multipart(cms_current_url()); ?>                  

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
                  <label class="control-label" for="">youtube</label>                  
                    <input  class="form-control" type="text" name="youtube" value="<?php if(!empty($link->youtube)) echo  $link->youtube; ?>">                  
                  <span style="color:red"><?php echo form_error('youtube') ?></span>
                  </div>

                  <div class="form-group">
                  <label class="control-label" for="">Twitter Username</label>                  
                    <input  class="form-control" type="text" name="twitter_username" value="<?php if(!empty($link->twitter_username)) echo  $link->twitter_username; ?>">                  
                  <span style="color:red"><?php echo form_error('twitter_username') ?></span>
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

