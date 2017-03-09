 <?php $where = array("sort_by"=>""); ?>  
<?php if($this->session->userdata('sort_trainee')) $where = $this->session->userdata('sort_trainee');  ?>


            <h4>
              Manage Trainees
              <span style="float: right;">
                <a class="btn btn-blue" style="margin:-5px;" href="<?php echo base_url() ?>trainer/add_trainee">
                  Add new
                </a>
              </span>


              <style type="text/css">
              #searchform select{
          -moz-appearance: none;
          -webkit-appearance: none;
          background: url("<?php echo base_url() ?>assets/theme/img/select.png") no-repeat scroll right 10px center #e5e9ea;
          margin-right: 15px;
        border: 0 none;
        border-radius: 3px;
        box-shadow: none;
        width: 180px;
        font-weight: lighter;
        height: 41px;
                  }
              </style>

              <span style="float: right;">
          <form action="<?php echo base_url()._INDEX ?>trainer/sort_trainee" method="post" role="form"  id="searchform" >
            <select class="form-control"  name="sort_by">
              <option value="">Sort By</option>
              <option <?php if($where['sort_by']=="created-desc") echo "selected" ?> value="created-desc">Newest</option>
              <option <?php if($where['sort_by']=="created-asc") echo "selected" ?> value="created-asc">Oldest</option>
              <option <?php if($where['sort_by']=="name-asc") echo "selected" ?> value="name-asc">Name ascending</option>
              <option <?php if($where['sort_by']=="name-desc") echo "selected" ?> value="name-desc">Name descending</option>
            </select>
          </form>
              </span>
            </h4>
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
                <?php if ($trainee): foreach ($trainee as $row) { ?>
               
                  <td><a href="<?php echo base_url() ?>trainer/view_trainee/<?php echo $row->slug; ?>" style="text-transform: capitalize;"><?php echo $row->lname.', '.$row->fname ?></a></td>
                  <td><a href="mailto:<?php echo $row->email ?>"><?php echo $row->email ?></a></td>
                                                     
                  <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                  <td>
                    <a href="<?php echo base_url() ?>trainer/edit_trainee/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                         &nbsp;&nbsp;&nbsp;  
                         <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>trainer/delete_trainee/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-remove"></i></a>
                  </td>
                  
                </tr>               
                <?php  } endif ?>
              </tbody>
            </table>
            <div>
              <?php echo $pagination; ?>
            </div>


    <script type="text/javascript">
        $('#searchform select').change(function(){
              $('#searchform').submit();
        });
    </script>