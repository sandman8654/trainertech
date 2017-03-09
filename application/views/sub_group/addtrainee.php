          <?php echo alert(); ?>
              <h3>Add Trainee to <?php echo subgroup_id_to_name($sub_group_id); ?> </h3>
              <?php echo form_open_multipart(cms_current_url(), array('class' => 'tasi-form')); ?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Trainee Name</label> 
                    <select class="form-control" name="trainee[]" multiple>
                      <?php if ($trainee): foreach($trainee as $row): ?>
                        <option value="<?php echo $row->id ?>"><?php echo $row->fname.' '.$row->lname; ?></option>
                      <?php endforeach; endif ?>
                    </select>  
                    <!-- <input type="text" class="form-control" name="name" id="" value="<?php echo set_value('name'); ?>" placeholder="Name"> -->
                    <span class="error"><?php echo form_error('trainee'); ?></span>
                  </div>  
                  
                  <br>
                  <input type="submit" class="btn btn-blue" value="Submit">
              </form>