<div class="container-fluid">
      <div class="row">
        <div class="col-sm-12  col-md-12 main">
          <?php echo alert(); ?>          
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3><?php echo $support->subject ?></h3>
            </div>
            <div class="row">              
              <div class="col-sm-12 col-md-12 main">                                          
                  <div class="col-sm-12">                
                    <h3>Message :</h3>
                    <?php echo $support->message; ?>
                  </div>
                  <hr>
                  <?php $trainee_id = get_trainee_id(); ?>
                  <div class="col-sm-12" style="max-height:500px; overflow-y:scroll">
                  <?php if ($conversation): foreach($conversation as $row): ?>
                      <div class="col-sm-12">                
                        <br>
                        <h4><?php if($row->send_by == '3' && $row->sender_id == $trainee_id) echo "Me"; elseif($row->send_by == '2') echo $row->fname; ?> 
                        <span style="font-size:10px"><?php $time=explode(',',timespan(strtotime($row->created), time())); echo $time[0];  ?> ago</span></h4>
                        <?php echo $row->message; ?>
                      </div>
                  <?php endforeach; endif; ?>                  
                  </div>

                  <div class="col-sm-12"> 
                 <?php echo form_open_multipart(cms_current_url()); ?>
                 <?php if($support->status != 2): ?>
                  <div style="margin-top:40px" class="form-group">                  
                    <label for="exampleInputEmail1">Reply</label> 
                    <textarea row="5" class="form-control" name="reply" id="" ><?php echo set_value('reply'); ?></textarea>
                    <span class="error"><?php echo form_error('reply'); ?></span>
                  </div>  
                  <div style="" class="form-group">                  
                    <label for="exampleInputEmail1"></label> 
                    <input class="btn btn-primary" type="submit" value="Send">                    
                  </div>  
                  <?php else: ?>
                    <div style="margin-top:40px" class="form-group">
                    <label>This query has been Closed</label> 
                    </div>
                  <?php endif; ?>



                  <div style="margin-top:40px" class="form-group">                  
                    <label for="exampleInputEmail1">Status</label> 
                    <select id="status" class="form-control">
                      <option value="1" <?php if($support->status == '1') echo "selected='selected'"; ?> >Open</option>
                      <option value="2" <?php if($support->status == '2') echo "selected='selected'"; ?> >Closed</option>
                    </select>                    
                  </div>  
                  <?php form_close(); ?>                  
                  </div>
              </div>
                </div>
            
            </div>
            
          </div>

          
        </div>
      </div>
    </div>   

    <script type="text/javascript">
      $('#status').change(function(event){
        var val = $(this).val();
        if(val != "" && confirm('Are you sure')){
          window.location.href='<?php echo base_url() ?>trainee/status/<?php echo $support->id; ?>/'+val;
        }else{
          $(this).val();
        }

      })
    </script>