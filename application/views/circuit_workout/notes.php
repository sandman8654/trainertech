
        <?php alert(); ?>
              <h4>
                Circuit Workout Notes
              </h4>
            <table class="table table-striped home-table">
              <thead>
                <tr>                
                  <th>Trainee</th>
                  <th style="width:50%">Notes</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($workout_notes): foreach ($workout_notes as $row) { ?>
               
                  <td><?php if($row->fname) echo ucfirst($row->fname." ".$row->lname) ?></td>
                  <td><?php echo $row->notes ?></td>
                                                     
                  <td><?php echo date('m/d/Y', $row->lastupdated); ?></td>                                    
                  <td>
                         <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>circuit_workout/delete_notes/<?php echo $row->id ?>/<?php echo $workout->id ?>"><i class="glyphicon glyphicon-remove"></i></a>
                  </td>
                  
                </tr>               
                <?php  } ?> 
              <?php else: ?>
                    <td colspan="4">No records Found.</td> 
              <?php endif; ?>
              </tbody>
            </table>
            <?php echo $pagination; ?>