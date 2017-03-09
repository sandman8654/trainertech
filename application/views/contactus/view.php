    <div class="container-fluid">
      <div class="row">
        <?php $this->load->view('admin/sidebar'); ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

          <?php echo alert(); ?>          

          <div class="panel panel-default">

            <div class="panel-heading">

              <h3>View Contact</h3>

            </div>

            <div class="row">              

              <div class="col-sm-11 col-md-10 main">                             

                  <div class="form-group">
                    <label for="">Name - <?php echo ucfirst($detail->name); ?></label>                                                       
                  </div>
                  <div class="form-group">
                    <label for="">Email - <?php echo ucfirst($detail->email); ?></label>                                                       
                  </div>
                  <div class="form-group">
                    <label for="">Phone - <?php echo ucfirst($detail->phone); ?></label>                                                       
                  </div>
                  <div class="form-group">
                    <label for="">Address - <?php echo ucfirst($detail->address); ?></label>                                                       
                  </div>
                  <div class="form-group">
                    <label for="">Information On - <?php echo ucfirst($detail->information); ?></label>                                                       
                  </div>
                  <div class="form-group">
                    <label for="">City - <?php echo ucfirst($detail->city); ?></label>                                                       
                  </div>
                  <div class="form-group">
                    <label for="">State - <?php echo ucfirst($detail->state); ?></label>                                                       
                  </div>
                  <div class="form-group">
                    <label for="">Zip - <?php echo ucfirst($detail->zip); ?></label>                                                       
                  </div>

              </div>

            

            </div>

            

          </div>



          

        </div>

      </div>

    </div>



    