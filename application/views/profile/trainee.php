
          <?php echo alert(); ?>
              <h3>Profile</h3>
                <?php echo form_open_multipart(cms_current_url(), array('class' => 'tasi-form')); ?>

                  <div class="form-group">

                    <label for="exampleInputEmail1">Firstname *</label> 

                    <input type="text" class="form-control" name="fname" id="" value="<?php echo $users->fname ?>" placeholder="Enter your first name" required/>

                    <span class="error"><?php echo form_error('fname'); ?></span>

                  </div>

                  <div class="form-group">

                    <label for="exampleInputEmail1">Lastname *</label> 

                    <input type="text" class="form-control" name="lname" id="" value="<?php echo $users->lname ?>" placeholder="Enter your last name" required/>

                    <span class="error"><?php echo form_error('lname'); ?></span>

                  </div>

                  <div class="form-group">

                    <label for="exampleInputEmail1">Email *</label> 

                    <input type="text" class="form-control" name="email" id="" value="<?php echo $users->email ?>" placeholder="Enter your email address" required/>

                    <span class="error"><?php echo form_error('email'); ?></span>

                  </div>

                  <div class="form-group">

                    <label for="exampleInputEmail1">Phone *</label> 

                    <input type="text" class="form-control" name="phone" id="" value="<?php echo $users->phone ?>" placeholder="Enter your phone number" required/>

                    <span class="error"><?php echo form_error('phone'); ?></span>

                  </div>

                  <div class="form-group">

                    <label for="exampleInputEmail1">Address *</label> 

                    <input type="text" class="form-control" name="address" id="" value="<?php echo $users->address ?>" placeholder="Enter your address" required/>

                    <span class="error"><?php echo form_error('address'); ?></span>

                  </div>

                  <div class="form-group">

                    <label for="exampleInputEmail1">City *</label> 

                    <input type="text" class="form-control" name="city" id="" value="<?php echo $users->city ?>" placeholder="Enter your city" required/>

                    <span class="error"><?php echo form_error('city'); ?></span>

                  </div>

                  <div class="form-group">

                    <label for="exampleInputEmail1">State *</label> 

                    <input type="text" class="form-control" name="state" id="" value="<?php echo $users->state ?>" placeholder="Enter your state" required/>

                    <span class="error"><?php echo form_error('state'); ?></span>

                  </div>

                  <div class="form-group">

                    <label for="exampleInputEmail1">Zip *</label> 

                    <input type="text" class="form-control" name="zip" id="" value="<?php echo $users->zip ?>" placeholder="Enter your zip" required/>

                    <span class="error"><?php echo form_error('zip'); ?></span>

                  </div>                 

                  <div class="form-group">

                    <label for="exampleInputEmail1">Image</label> 

                    <input type="file" name="userfile"/>
                    <?php if(!empty($users->image)): ?>
                      <br><br>
                      <img style='height:150px; width:150px; border-radius:5px;' src='<?php echo BUCKET_PATH;  ?><?php echo $users->image; ?>'>
                    <?php endif; ?>
                  </div>                 
                  
             

                  <input type="submit" class="btn btn-blue" value="Update">

                </form>