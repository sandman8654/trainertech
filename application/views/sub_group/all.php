        <?php alert(); ?>        
              <h4>
                Manage Sub-Groups
                <span style="float: right;">
                  <a class="btn btn-blue" href="<?php echo base_url() ?>sub_group/add/<?php echo $parent_slug ?>">
                    Add new
                  </a>
                </span>
              </h4>
            <table class="table table-striped home-table">
              <thead>
                <tr>  
                                  
                  <th>Name</th>
                  <th>Created</th>
                  <th>Trainee</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($sub_group): foreach ($sub_group as $row) { ?>
                <tr>
                  
                  <td><?php echo $row->name; ?></td>
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                  <td><a href="<?php echo base_url() ?>sub_group/alltrainee/<?php echo $row->id; ?>"><i class="glyphicon glyphicon-user"></i></a></td>
                  <td>
                    <a href="<?php echo base_url() ?>sub_group/edit/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                         &nbsp;&nbsp;&nbsp;  
                         <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>sub_group/delete/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-remove"></i></a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>
            <?php echo $pagination; ?>