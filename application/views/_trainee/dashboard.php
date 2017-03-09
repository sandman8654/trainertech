
    <div class="container-fluid">
      <div class="row">
        

        <div class="col-sm-12 main">
          
          <div class="panel panel-default">
            <div class="panel-heading">Dashboard</div>

            <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>                
                  <th>Name</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Status</th>
                  <th>View</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($workout): foreach ($workout as $row) { ?>
               
                  <td><?php echo $row->name ?></td>
                                                     
                  <td><?php echo date('m/d/Y', strtotime($row->date)); ?></td>                                    
                  <td><?php echo date('h:i A', strtotime($row->time)); ?></td>                                    
                  <td><?php if($row->status == 0){ echo 'Pending'; } elseif($row->status == 2) { echo 'Not Done'; } else { echo 'Completed'; }  ?></td>
                  <td>
                    <a href="<?php echo base_url() ?>workout/view/<?php echo $row->id ?>"><i class="glyphicon glyphicon-eye-open"></i></a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>
            <div>
              <?php echo $pagination; ?>
          </div>

          
        </div>
      </div>
    </div>

    