<?php alert(); ?>       
<h4>
  System Exercises
  <span style="float: right;">
    <a class="btn btn-blue" href="<?php echo base_url() ?>exercise/add">
      Add new
    </a>
  </span>
</h4>
  <table class="table table-striped home-table">
    
              <thead>
                <tr>                  
                  <th>Name</th>
                  <th>Description</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($exercise): foreach ($exercise as $row) { ?>
                <tr>
                  <td><?php echo $row->name ?></td>
                  <td><?php echo word_limiter($row->description,5); ?></td>                                    
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                  <td>
                    <a href="<?php echo base_url() ?>exercise/edit/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                         &nbsp;&nbsp;&nbsp;  
                         <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>exercise/delete/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-remove"></i></a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>

    <?php echo $pagination; ?>
</section>


   