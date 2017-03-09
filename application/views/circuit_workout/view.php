
      <?php echo alert(); ?>
          <h3>View Workout</h3>
              <div class="form-group">
                <label for="">Name - <?php echo ucfirst($workout['name']); ?></label>                                                       
              </div>
              <div class="form-group">
                <label for="">Date - <?php echo $workout['date'] ?></label>                                                       
              </div>
              <div class="form-group">
                <label for="">Time - <?php echo $workout['time'] ?></label>                                                       
              </div>
              <div class="form-group">
                <label for="">Description - <?php echo ucfirst($workout['description']); ?></label>                                                       
              </div>
              
              <?php foreach($workout['exercises'] as $row){ ?>

                <div class="form-group">
                  <label for="">Exercise - <?php echo ucfirst($row['exercise']); ?></label>                                                       
                </div>

                <?php $i=1; foreach($row['sets'] as $var){ ?>

                  <div class="form-group">
                    <label for=""> --> Set <?php echo $i; ?> - <?php echo $var->value ?></label>                                                       
                  </div>

                <?php $i++; } ?>

              <?php } ?>

              <?php echo form_open_multipart(cms_current_url(),array('id'=>'contentForm', 'class' => 'tasi-form')); ?>
                <div class="form-group">
                  <label for="exampleInputEmail1">Status</label> 
                  <select class="form-control" name="status">
                    <option value="0">Pending</option>
                    <option value="1" <?php if($workout['status'] == 1) { echo 'selected="selected"'; } ?> >Complete</option>
                    <option value="2" <?php if($workout['status'] == 2) { echo 'selected="selected"'; } ?> >Not Done</option>
                  </select>
                </div>
              <?php echo form_close(); ?>
</div>
<script type="text/javascript">
  $(document).on('change', 'select[name=status]', function(){
    $("#contentForm").submit();
  });
</script>