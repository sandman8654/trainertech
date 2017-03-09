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

<script type="text/javascript">
$(document).ready(function(){
  $('#searchform select').change(function(){
  $('#searchform').submit();
});
});
</script>

 <?php $where = array("sort_by"=>""); ?>  
<?php if($this->session->userdata('sort_group')) $where = $this->session->userdata('sort_group');  ?>



              <?php alert(); ?>
              <h4>
                Manage Groups
                <span style="float: right;">
                  <a class="btn btn-blue" href="<?php echo base_url() ?>group/add">
                    Add new
                  </a>
                </span>
                <span style="float: right;">
            <form action="<?php echo base_url()._INDEX ?>group/sort_group" method="post" role="form"  id="searchform" >
              <select class="form-control"  name="sort_by">
                <option value="">Sort By</option>
                <option <?php if($where['sort_by']=="created-desc") echo "selected" ?> value="created-desc">Newest</option>
                <option <?php if($where['sort_by']=="created-asc") echo "selected" ?> value="created-asc">Oldest</option>
                <option <?php if($where['sort_by']=="group-asc") echo "selected" ?> value="group-asc">Group ascending</option>
                <option <?php if($where['sort_by']=="group-desc") echo "selected" ?> value="group-desc">Group descending</option>
              </select>
            </form>
                </span>
              </h4>
              <table class="table table-striped  home-table">
                <thead>
                  <tr>  
                                    
                    <th>Name</th>
                    <th>Parent Group</th>
                    <th>Members</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($group): foreach ($group as $row) { ?>
                  <tr>
                    
                    <td><?php echo $row->name; ?></td>
                    <td>
                        <?php if($row->parent_id!="0"):  ?>
                                <?php $parent = get_row('group',array('id'=>$row->parent_id)) ?>
                                <?php echo ucfirst($parent->name); ?>
                        <?php else:  ?>
                                -
                        <?php endif;  ?>
                    </td>
                   <td><a href="<?php echo base_url() ?>group/alltrainee/<?php echo $row->id; ?>"><i class="glyphicon glyphicon-user"></i></a></td>
                    
                    <td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>                                    
                    <td>
                      <a href="<?php echo base_url() ?>group/edit/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                           &nbsp;&nbsp;&nbsp;  
                           <a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>group/delete/<?php echo $row->slug ?>"><i class="glyphicon glyphicon-remove"></i></a>
                    </td>
                    
                  </tr>               
                  <?php  } endif ?>
                </tbody>
              </table>
              <div>
                <?php echo $pagination; ?>
              </div>