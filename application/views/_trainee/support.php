
    <div class="container-fluid">
      <div class="row">        
        <div class="col-sm-12  col-md-12 main">  
        <?php alert(); ?>        
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4>
                Supports
                <span style="float: right;">
                  <a class="btn btn-primary" style="margin:-5px;" href="<?php echo base_url() ?>trainee/add_query">
                    Add new
                  </a>
                </span>
              </h4>
            </div>
            <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>  
                                  
                  
                  <th>Subject</th>
                  <th>Status</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($support): foreach ($support as $row) { ?>
                <tr>                  
                  <td><?php echo $row->subject; ?></td>
                  <td><?php if($row->status == 1) echo 'Open'; elseif($row->status == 2) echo "Closed"; ?></td>                                 
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                  <td>
                    <a href="<?php echo base_url() ?>trainee/reply/<?php echo $row->id ?>"><i class="glyphicon glyphicon-envelope"></i></a>
                         &nbsp;&nbsp;&nbsp;  
                         <!--<a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>trainee/delete/<?php echo $row->id ?>"><i class="glyphicon glyphicon-remove"></i></a>-->
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
      </div>
    </div>