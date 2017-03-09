          <?php alert(); ?>
              <h4>
                 <?php echo subgroup_id_to_name($subgroup_id); ?> Trainee
                <span style="float: right;">
                  <a class="btn btn-blue" href="<?php echo base_url() ?>sub_group/addtrainee/<?php echo $subgroup_id ?>">
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
                      <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>sub_group/deletetrainee/<?php echo $row->id ?>/<?php echo $row->subgroup_id ?>"><i class="glyphicon glyphicon-remove"></i></a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>
            <?php echo $pagination; ?>