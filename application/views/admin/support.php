<section class="contant">
        <?php alert(); ?>        
        <header>
          <span>Supports</span>
         
        </header>
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
                    <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>admin/delete_support/<?php echo $row->token2 ?>"><i class="glyphicon glyphicon-remove"></i></a>
                         &nbsp;&nbsp;&nbsp;  
                    <a href="<?php echo base_url() ?>admin/reply/<?php echo $row->token2 ?>">
                     <i class="glyphicon glyphicon-comment"></i>
                      <?php $replies = count_replies($row->token2,'admin_read'); ?>
                      <?php if ($replies): ?>
                        <span style="color:#425053">
                          (<?php echo $replies ?>)
                        </span>
                      <?php endif ?>
                    </a>
                  </td>
                  
                </tr>               
                <?php  } endif; ?>
              </tbody>
        </table> 
    <?php echo $pagination; ?>
</section>
    