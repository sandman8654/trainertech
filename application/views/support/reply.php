    <div class="container-fluid">
      <div class="row">
          <?php echo alert(); ?>          
          <h3><?php echo $support->subject ?></h3>
              <div class="col-sm-12">                
                <h3>Message :</h3>
                <?php echo $support->message; ?>
              </div>
              <hr>
              <div class="col-sm-12" style="max-height:500px; overflow-y:scroll">
              <?php if ($conversation): foreach($conversation as $row): ?>
                  <div class="col-sm-12">                
                    <br>
                    <h4><?php if($row->send_by == '1') echo "Admin"; elseif($row->send_by == '2') echo 'Me';elseif($row->send_by == '3') echo 'User'; ?> 
                    <span style="font-size:10px"><?php $time=explode(',',timespan(strtotime($row->created), time())); echo $time[0];  ?> ago</span></h4>
                    <?php echo $row->message; ?>
                  </div>
              <?php endforeach; endif; ?>                  
              </div>
              <div class="col-sm-12"> 
                 <?php echo form_open_multipart(cms_current_url(), array('class'=>'tasi-form')); ?>
                     <?php if($support->status != 2): ?>
                      <div style="margin-top:40px" class="form-group">
                        <label for="exampleInputEmail1">Reply</label>
                        <textarea row="5" class="form-control" name="reply" id="" ><?php echo set_value('reply'); ?></textarea>
                        <span class="error"><?php echo form_error('reply'); ?></span>
                      </div>
                      <div style="" class="form-group">
                        <label for="exampleInputEmail1"></label>
                        <input class="btn btn-blue" type="submit" value="Send">
                      </div>
                      <?php else: ?>
                        <div style="margin-top:40px" class="form-group">
                        <label>This query has been Closed</label> 
                        </div>
                      <?php endif; ?>
                      <div style="margin-top:40px; display:none;" class="form-group">                  
                        <label for="exampleInputEmail1">Status</label> 
                        <select id="status" class="form-control">
                          <option value="1" <?php if($support->status == '1') echo "selected='selected'"; ?> >Open</option>
                          <option value="2" <?php if($support->status == '2') echo "selected='selected'"; ?> >Closed</option>
                        </select>
                      </div>
                  <?php echo form_close(); ?>                  
                  </div>
              </div>
        </div>
    </div>

    <script type="text/javascript">
      $('#statuss').change(function(event){
        var val = $(this).val();
        if(val != "" && confirm('Are you sure')){
          window.location.href='<?php echo base_url() ?>support/status/<?php echo $support->token2; ?>/'+val;
        }else{
          $(this).val();
        }

      })
    </script>