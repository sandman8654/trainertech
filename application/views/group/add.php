  <style type="text/css">
  .form-control{
    width:50%;
  }

  </style>    
          <?php echo alert(); ?>
              <h3>Add Group</h3>
              <?php echo form_open_multipart(cms_current_url(), array('class'=>'tasi-form')); ?>
                 
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label> 
                    <input type="text" class="form-control" name="name" id="" value="<?php echo set_value('name'); ?>" placeholder="Name">
                    <span class="error"><?php echo form_error('name'); ?></span>
                  </div>  

                  <div class="form-group">
                    <label for="exampleInputEmail1">Parent Group</label> 
                     <select name="group_id" class="form-control">
                       <option value="0">None</option>
                        <?php if ($groups): ?>
                             <?php foreach ($groups as $group): ?>
                                 <option value="<?php echo $group->id ?>"><?php echo ucfirst($group->name) ?></option>
                             <?php endforeach ?>
                        <?php endif ?>
                     </select>
                    <span class="error"><?php echo form_error('group_id'); ?></span>
                  </div>  
                  
                  <br>
                  <input type="submit" class="btn btn-blue" value="Submit">
              </form>