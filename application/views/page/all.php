<section class="contant">
        <?php alert(); ?>        
        <header>
          <span>Pages</span>
          <form role="form">            
              <a class="btn btn-blue" style="margin:-5px;" href="<?php echo base_url() ?>page/add">
                    Add new
                  </a>
          </form>
        </header>
  <table class="table table-striped home-table">
    
              <thead>
                <tr>                  
                  <th>Title</th>
                  <th>Content</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($page): foreach ($page as $row) { ?>
                <tr>
                  <td><?php echo $row->title ?></td>
                  <td><?php echo word_limiter($row->description,5); ?></td>                                    
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                  <td>
                    <a href="<?php echo base_url() ?>page/edit/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                         &nbsp;&nbsp;&nbsp;  
                         <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>page/delete/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-remove"></i></a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>

    <?php echo $pagination; ?>
</section>


   