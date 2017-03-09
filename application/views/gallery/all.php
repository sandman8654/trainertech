<section class="contant">
        <?php alert(); ?>        
        <header>
          <span>Gallery Images</span>
          <form role="form">            
              <a class="btn btn-blue" style="margin:-5px;" href="<?php echo base_url() ?>gallery/add">
                    Add new
                  </a>
          </form>
        </header>
  <table class="table table-striped home-table">
    
              <thead>
                <tr>                  
                  <th>Image</th>                  
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($gallery): foreach ($gallery as $row) { ?>
                <tr>
                  <td><img src="<?php echo BUCKET_PATH.$row->image ?>" alt="" width="100px"></td>                  
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                <td>                    
                    &nbsp;&nbsp;&nbsp;  
                    <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>gallery/delete/<?php echo $row->id ?>"><i class="glyphicon glyphicon-remove"></i></a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>

    <?php echo $pagination; ?>
</section>


   