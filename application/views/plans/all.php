<section class="contant">
        <?php alert(); ?>        
        <header>
          <span>Manage Plans</span>
          <form role="form">            
              <a class="btn btn-blue" style="margin:-5px;" href="<?php echo base_url() ?>plans/add">
                    Add new
                  </a>
          </form>
        </header>
  <table class="table table-striped home-table">
    <thead>
      <tr>  
                        
        <th>Name</th>
        <th>Price</th>
        <th>Created</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($plans): foreach ($plans as $row) { ?>
      <tr>
        
        <td><?php echo $row->name; ?></td>
        <td>$<?php echo $row->price; ?></td>
                                           
        <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
        <td>
          <a href="<?php echo base_url() ?>plans/edit/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-pencil"></i></a>
               &nbsp;&nbsp;&nbsp;  
               <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>plans/delete/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-remove"></i></a>
        </td>
        
      </tr>               
      <?php  } endif ?>
    </tbody>
  </table> 
    <?php echo $pagination; ?>
</section>
           