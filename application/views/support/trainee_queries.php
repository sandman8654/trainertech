
        <?php alert(); ?>
              <h4>
                Trainee Queries
                <span style="float: right;">
                  <a class="btn btn-blue"href="<?php echo base_url() ?>support/add">
                    Add new
                  </a>
                </span>
              </h4>
            <table class="table table-striped home-table">
              <thead>
                <tr>  
                  <th>Trainee</th>
                  <th>Subject</th>
                  <!-- <th>Status</th> -->
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($support): foreach ($support as $row) { ?>
                <tr>                  
                  <td><?php echo ucfirst($row->trfname." ".$row->trlname) ?></td>
                  <td><?php echo $row->subject; ?></td>
                  <!-- <td><?php if($row->status == 1) echo 'Open'; elseif($row->status == 2) echo "Closed"; ?></td>                                  -->
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                  <td>
                     <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>support/delete_queries/<?php echo $row->token2 ?>"><i class="glyphicon glyphicon-remove"></i></a>
                         &nbsp;&nbsp;&nbsp;  
                    <a href="<?php echo base_url() ?>support/reply/<?php echo $row->token2 ?>">
                      <i class="glyphicon glyphicon-comment"></i>
                      <?php $replies = count_replies($row->token2,'trainer_read'); ?>
                      <?php if ($replies): ?>
                        <span style="color:#425053">
                          (<?php
                            if($replies == '1'){
                              echo $replies.' Unread Message';
                            }
                            else{
                              echo $replies.' Unread Messages';
                            }
                          ?>)
                        </span>
                      <?php endif ?>
                  </a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>
            <div>
              <?php echo $pagination; ?>
            </div>