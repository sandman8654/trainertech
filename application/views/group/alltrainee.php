          <?php alert(); ?>
          <?php $group = get_row('group',array('id'=>$group_id)); ?>
              <h4>
                <?php echo ucwords($group->name)  ?>  Trainee
                  
                <span style="float: right;">
                  <a class="btn btn-blue" href="<?php echo base_url() ?>group/addtrainee/<?php echo $group_id ?>">
                    Add new
                  </a>
                </span>
              </h4>
            <table class="table table-striped home-table">
              <thead>
                <tr>  
                                  
                  <th>Trainee Name</th>                  
                  <th>Email</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($member): foreach ($member as $row) { ?>
                <tr>
                  
                  <td><?php echo $row->fname.' '.$row->lname; ?></td>
                  <td><?php echo $row->email ?></td>                                    
                  <td>                    
                      <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>group/removetrainee/<?php echo $row->id ?>/<?php echo $row->group_id ?>"><i class="glyphicon glyphicon-remove"></i></a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>
            <?php echo $pagination; ?>