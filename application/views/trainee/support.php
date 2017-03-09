
        <?php alert(); ?>
              <h4>
                Supports
                <span style="float: right;">
                  <a class="btn btn-blue" href="<?php echo base_url() ?>trainee/add_query">
                    Add new
                  </a>
                </span>
              </h4>
            <table class="table table-striped home-table">
              <thead>
                <tr>
                  <th>Subject</th>
                  <!-- <th>Status</th> -->
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($support): foreach ($support as $row) { ?>
                <tr>                  
                  <td><?php echo $row->subject; ?></td>
                  <!-- <td><?php if($row->status == 1) echo 'Open'; elseif($row->status == 2) echo "Closed"; ?></td>                                  -->
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                  <td>
                    <a href="<?php echo base_url() ?>trainee/reply/<?php echo $row->token2 ?>">
                      <i class="glyphicon glyphicon-comment"></i>
                      <?php $replies = count_replies($row->token2,'trainee_read'); ?>
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
                         &nbsp;&nbsp;&nbsp;  
                         <!--<a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>trainee/delete/<?php echo $row->id ?>"><i class="glyphicon glyphicon-remove"></i></a>-->
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>
            <?php echo $pagination;  ?>