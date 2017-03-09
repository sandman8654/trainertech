          <?php echo alert(); ?>



<?php 

$exist_trainee = array();
if ($group_trainee){
  foreach ($group_trainee as $group_trainee){
    $exist_trainee[] = $group_trainee->trainee_id;
  }
}

 ?>
          <?php $group = get_row('group',array('id'=>$group_id)); ?>

              <h3>Add Trainee to <?php echo $group->name ?> </h3>

          <p>Note : Trainee who are already a member of another group will not appear here. </p>    
              <?php echo form_open_multipart(cms_current_url(), array('class' => 'tasi-form')); ?>
                  
              
                <div class="form-group">
                <label for="exampleInputEmail1">Select Trainees</label> 
                <?php if($trainee) :  ?>
                         <div class="main_div">
                             <div class="my_check">
                      <?php $i=1;  foreach($trainee as $row): ?>
                      <?php if(in_array($row->id,$exist_trainee)){ continue; }?>
                                 <div class="checkbox">
                                     <label >
                                          <input name="trainee[]"  value="<?php echo $row->id ?>" type="checkbox" value=""><?php echo $row->fname." ".$row->lname ?>
                                    </label>
                                 </div>
                                 <?php if($i%6==0): ?>
                                  </div>
                                     <div class="my_check">
                                 <?php endif; ?>
                    <?php $i++; endforeach;endif; ?>
                             </div>
                         </div>
                <span class="error"><?php echo form_error('trainee'); ?></span>
                </div> 

              <?php /* ?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Trainee Name</label> 
                    <select required class="form-control" name="trainee[]" multiple>
                      <?php if ($trainee): foreach($trainee as $row): ?>
                          <?php if(!in_array($row->id,$exist_trainee)): ?>
                            <option value="<?php echo $row->id ?>"><?php echo $row->fname.' '.$row->lname; ?></option>
                          <?php endif; ?>
                      <?php endforeach; endif ?>
                    </select>  
                    <!-- <input type="text" class="form-control" name="name" id="" value="<?php echo set_value('name'); ?>" placeholder="Name"> -->
                    <span class="error"><?php echo form_error('trainee'); ?></span>
                  </div>  */ ?> 
                  
                  <br>
                  <input type="submit" class="btn btn-blue" value="Submit">
              </form>

<style type="text/css">
    .my_check{
         width:30%;
         float:left;

    }
    .main_div{
        overflow: hidden;
    }
</style>