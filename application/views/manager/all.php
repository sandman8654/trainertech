<section class="contant">
        <?php alert(); ?>        
        <header>
          <span>Manage Managers</span>
          <form role="form">            
              <a class="btn btn-blue" style="margin:-5px;" href="<?php echo base_url() ?>manager/add">
                Add new
              </a>
          </form>
        </header>
  <table class="table table-striped home-table">
   <thead>
                <tr>  
                                  
                  <th>Name</th>
                  <th>Email</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($manager): foreach ($manager as $row) { ?>
                <tr>
                  
                  <td><?php echo $row->fname.' '.$row->lname ?></td>
                    <td><a href="mailto:<?php echo $row->email ?>"><?php echo $row->email ?></a></td>
                                                     
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                  <td>
                    <a href="<?php echo base_url() ?>manager/edit/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                         &nbsp;&nbsp;&nbsp;  
                         <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>manager/delete/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-remove"></i></a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
  </table> 
    <?php echo $pagination; ?>
</section>

    