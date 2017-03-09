
        <?php alert(); ?>
              <h4>
                Exercise
              </h4>
            <table class="table table-striped home-table">
              <thead>
                <tr>                
                  <th>Name</th>
                  <th>Notes</th>
                  <th>Created</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($exercise_notes): foreach ($exercise_notes as $row) { ?>
               
                  <td><?php if($row->name) echo ucfirst($row->name) ?></td>
                 
                  <td>
		                <?php $count = get_count('exercise_notes',array('exercise_id'=>$row->id)) ?>
		                <?php if($count): ?>
		                <a  href='<?php echo base_url()._INDEX; ?>workout/exercise_notes/<?php echo $row->id; ?>'  >
                           <?php echo $count ?>
                           	<i class="glyphicon glyphicon-book"></i>
		                </a>
		                <?php else: ?>
		                
					<a href="javascript:void(0)">
		                0 <i class="glyphicon glyphicon-book"></i>
					</a>
		                <?php endif; ?>
						
                  </td>
                                                     
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                  
                </tr>               
                <?php  } ?> 
            	<?php else: ?>
                    <td colspan="4">No records Found.</td> 
            	<?php endif; ?>
              </tbody>
            </table>
            <?php echo $pagination; ?>