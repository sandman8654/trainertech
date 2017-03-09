  <section class="contant">

              <?php alert(); ?>
              <h4>
                Manage Slider
                <span style="float: right;">
                  <a class="btn btn-blue" href="<?php echo base_url() ?>app_slider/add">
                    Add new
                  </a>
                  <br>
                  <br>
                </span>
              </h4>
              <table class="table table-striped  home-table">
                <thead>
                  <tr>  
                                    
                    <th>Content</th>
                    <th>Order</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($app_slider): ?>


                    <?php foreach ($app_slider as $row) { ?>
                  <tr>
                    
                    <td><?php echo $row->content; ?></td>
                    <td><?php echo $row->order; ?></td>
                    <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                    <td>
                      <a href="<?php echo base_url() ?>app_slider/edit/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                           &nbsp;&nbsp;&nbsp;  
                           <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>app_slider/delete/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-remove"></i></a>
                    </td>
                    
                  </tr>               
                  <?php  } ?> 

                <?php else: ?>
                      No records Found.
                <?php endif; ?>

                  

                </tbody>
              </table>
              <div>
                <?php echo $pagination; ?>
              </div>

              </section>