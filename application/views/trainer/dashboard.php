<?php
  // CODE FOR GRAPH
    $pg = array();
    if ($groups){
      foreach($groups as $r){
        $pg[$r->id] = $r->name;
      }
    }
  // CODE FOR GRAPH
?>
<?php $where = array('t_name'=>"",'t_group'=>"","sort_by"=>""); ?>  
<?php if($this->session->userdata('s_trn')) $where = $this->session->userdata('s_trn');  ?>

<script>
function date_validation(from,to){
  from = from.replace(/-/g,'/');
  to = to.replace(/-/g,'/');
  from = Date.parse(from);
  to = Date.parse(to);
  if( to < from ){
    alert('Invalid date range, `From` date must be smaller than or equal to `To` date.');
  }
} 
</script>



<style type="text/css">
.page_header{
  width:100%;
  overflow: hidden
}
.header_heading{
  float: left;
  overflow: hidden;
  width:30%;
}
.header_form{
  float: left;
  overflow: hidden;
  width:70%;
}
.header_form select{
  height: 36px;
  margin-bottom: 4px;
  width: 183px !important;
}
.header_form form{
  float: left !important;
  margin-left: 23% !important;
  overflow: hidden;
}
</style>
        <header >
        <div class="page_header">
          <div class="header_heading">
            <span>Welcome <?php echo get_trainer_name(); ?>!</span>
          </div>
            <div class="header_form">
            <form action="<?php echo base_url()._INDEX ?>trainer/search_dashboard" method="post" role="form" class="form-inline"  id="searchform" >
                <input value="<?php echo $where['t_name'] ?>" name="t_name" type="search" id="search" placeholder="Search">
                <select class="form-control"  name="t_group">
                    <option value="">All Trainees</option>
                      <?php if ($groups): ?>
                          <?php foreach ($groups as $group): ?>
                                 <?php $subgroup = get_result('group',array('parent_id'=>$group->id)) ?>
                                 <?php if($subgroup): ?>
                                      <?php foreach ($subgroup as $subgroup): ?>
                                   <option <?php if($where['t_group']==$subgroup->id) echo "selected" ?> value="<?php echo $subgroup->id ?>"><?php echo $group->name ?> - <?php echo $subgroup->name ?></option>    
                                      <?php endforeach ?>
                                 <?php else: ?> 
                                   <option <?php if($where['t_group']==$group->id) echo "selected" ?> value="<?php echo $group->id ?>"><?php echo $group->name ?></option>    
                                <?php endif; ?>
                              <?php endforeach ?>
                      <?php endif ?>
                </select>
                <select class="form-control"  name="sort_by">
                    <option value="">Sort By</option>
                <option <?php if($where['sort_by']=="created-desc") echo "selected" ?> value="created-desc">Newest</option>
                <option <?php if($where['sort_by']=="created-asc") echo "selected" ?> value="created-asc">Oldest</option>
                    <option <?php if($where['sort_by']=="name-asc") echo "selected" ?> value="name-asc">Name ascending</option>
                    <option <?php if($where['sort_by']=="name-desc") echo "selected" ?> value="name-desc">Name descending</option>
                    <option <?php if($where['sort_by']=="group-asc") echo "selected" ?> value="group-asc">Group ascending</option>
                    <option <?php if($where['sort_by']=="group-desc") echo "selected" ?> value="group-desc">Group descending</option>
                </select>
            </form>
            </div>
        </div>
        </header>

        <table class="table table-striped home-table">
          <thead>
            <tr>
              <th width="25%">Name</th>
              <th width="40%">Group</th>
              <th width="20%">Last Workout</th>
              <th width="15%">Next Workout</th>
            </tr>
          </thead>
          <tbody id="tbody">
            <?php if ($trainee): ?> 
              <?php foreach($trainee as $row): ?>
                <tr>
                    <td><a href="<?php echo base_url() ?>trainer/view_trainee/<?php echo $row->slug; ?>" style="text-transform: capitalize;"><?php echo $row->lname.', '.$row->fname ?></a></td>
                      <td>
                              <?php if ($row->groupid): ?>
                                   <?php if ($row->parent_id==0): ?>
                                        <?php echo $row->groupname ?>
                                   <?php else: ?>
                                       <?php $group = get_row('group',array('id'=>$row->parent_id)) ?>
                                       <?php echo $group->name." - ".$row->groupname ?>
                                   <?php endif ?>
                              <?php else: ?>
                                -
                              <?php endif ?>
                     </td>
                     <td>
                          <?php $lastworkout = get_last_workout($row->trnid) ?>

                              <?php if ($lastworkout): ?>
                                  <?php echo date('m/d/Y',strtotime($lastworkout->date)) ?>
                                  <?php $exist = get_row('workout_notes',array('workout_id'=>$lastworkout->id,'trainee_id'=>$row->trnid)); ?>
                                  <?php  if($exist): ?>
                                      &nbsp;<img src="<?php echo base_url() ?>assets/theme/img/checkgreen.png">
                                  <?php else: ?>
                                      &nbsp; <img src="<?php echo base_url() ?>assets/theme/img/cross.png">
                                  <?php endif ?>
                              <?php else: ?>
                                -
                              <?php endif ?>
                     </td>

                     <td>
                          <?php $nextworkout = get_next_workout($row->trnid) ?>
                              <?php if ($nextworkout): ?>
                                  <?php echo date('m/d/Y',strtotime($nextworkout->date)) ?>
                              <?php else: ?>
                                -
                              <?php endif ?>
                     </td>
                  
                </tr>
            <?php endforeach; ?> 
              
          <?php else: ?>
            <td colspan="4">No Records Found.</td>
          <?php endif; ?>
          </tbody>
        </table>

        <?php echo $pagination ?>

  <script type="text/javascript">

      $('#searchform select').change(function(){
            $('#searchform').submit();
      });
  </script>
  



<br>

<?php /**
        START : GROUP & MEMBERS SECTION
*/ ?>

<?php if ($select_groups || $group_members): ?>
<?php $limit = 18; ?>
<div class="row">
  <div class="col-md-12">
    <h3>Compare Trainees</h3>    
  </div>  
  <div class="col-md-12">
    
    <?php if($select_groups){ ?>  
    <div class="col-md-6 mainpg" style='background-color:#E5E9EA; border-right:10px solid #ECF0F1;'>
      <div class="col-md-12"><h4>Select Group(s)</h4></div>
      <?php $i=1; ?>
      <span class='g-pg-page' value='1' >
      <?php foreach ($select_groups as $group): ?>
      <div class='col-md-4'>
          <div class="checkbox">
              <label>
                <input class="select_groups sgrp_<?php echo $group->id ?>" name="selected_group_id[]" value="<?php echo $group->id ?>" type="checkbox">
                <?php
                    if($group->parent_id == '0'){
                      echo $group->name;
                    }else{
                        if(isset($pg[$group->parent_id])){
                          echo $pg[$group->parent_id].' - '.$group->name;
                        }else{
                          echo $group->name;
                        }
                      }
                  ?>
              </label>
          </div>
      </div>
      <?php if($i%$limit == 0): ?>
        </span>
        <span class='g-pg-page' value='<?php echo $i; ?>' style="display:none;" >
      <?php endif; ?> 
      <?php $i++; ?>
      <?php endforeach ?>
      </span>
      <?php if($total_groups > $limit): ?>
      <div class='col-md-12'>
        <ul class="pagination g-pg-link">
          <?php $links = ceil($total_groups/$limit); ?>
          <?php for($j=1; $j<=$links; $j++): ?>
            <li <?php if($j == 1){ echo 'class="active"'; } ?>  >
              <a value='<?php echo $j; ?>' ><?php echo $j; ?></a>
            </li>
          <?php endfor; ?>  
        </ul>
      </div>
      <?php endif; ?>
    </div>
      <?php } else { $select_groups = array(); } ?>

      <?php if($group_members){ ?>
      <div class="col-md-6 mainpg" style='background-color:#E5E9EA; border-left:10px solid #ECF0F1;'>
        <div class="col-md-12"><h4>Select Trainees</h4></div>
        <?php $i=1; ?>
      <span class='m-pg-page' value='1'>
        <?php $vv = TRUE; foreach ($group_members as $group_member): ?>
      <div class='col-md-4'>
            <div class="checkbox">
                <label>
                  <?php
                      $check_id = '';
                      if($vv){
                        $vv = FALSE;
                        $check_id = 'firstcheckbox';
                      }
                  ?>
                  <input id="<?php echo $check_id ?>" name="grp_member_id[]" class="grp_members grp_<?php echo $group_member->group_id ?>" grpidd="<?php echo $group_member->group_id ?>" value="<?php echo $group_member->id ?>" type="checkbox">
                  <?php echo $group_member->lname.", ".$group_member->fname; ?>
                </label>
        </div>
      </div>
      <?php if($i%$limit == 0): ?>
        </span>
        <span class='m-pg-page' value='<?php echo $i; ?>' style="display:none;" >
      <?php endif; ?> 
      <?php $i++; ?>
        <?php endforeach ?>
      </span>
      <?php if($total_members > $limit): ?>
      <div class='col-md-12'>
        <ul class="pagination m-pg-link">
          <?php $links = ceil($total_members/$limit); ?>
          <?php for($j=1; $j<=$links; $j++): ?>
            <li <?php if($j == 1){ echo 'class="active"'; } ?>  >
              <a value='<?php echo $j; ?>' ><?php echo $j; ?></a>
            </li>
          <?php endfor; ?>  
        </ul>
      </div>
      <?php endif; ?>
    </div>
    <?php } else { $group_members = array(); } ?>
    </div>
</div>
<script>
$('.g-pg-link a').click(function(){
  $('.g-pg-link li').removeClass('active');
  $(this).parent().addClass('active');
  $('.g-pg-page').hide();
  var offset = $(this).attr('value');
  offset = (offset - 1) * <?php echo $limit ?>;
  if(offset == 0){ offset = 1; }
  $('.g-pg-page[value='+offset+']').show();
}); 
$('.m-pg-link a').click(function(){
  $('.m-pg-link li').removeClass('active');
  $(this).parent().addClass('active');
  $('.m-pg-page').hide();
  var offset = $(this).attr('value');
  offset = (offset - 1) * <?php echo $limit ?>;
  if(offset == 0){ offset = 1; }
  $('.m-pg-page[value='+offset+']').show();
}); 
$(document).ready(function(){
  var x = $('.g-pg-page').parent('.mainpg').height(); 
  var y = $('.m-pg-page').parent('.mainpg').height(); 
  if(x > y){
    $('.mainpg').height(x); 
  }else{
    $('.mainpg').height(y); 
  }
});
</script>
<style>
.pagination a:hover{
  cursor: pointer;
}
.my-select-css{
  -moz-appearance: none;
  -webkit-appearance: none;
  background: url("<?php echo base_url(); ?>assets/theme/img/select.png") no-repeat scroll right 10px center #e5e9ea;
  margin-right: 15px;
  border: 0;
  border-radius: 3px;
  box-shadow: none;
  height: 40px;
}
.my-input-css:hover{
  cursor: pointer;
}
.my-input-css{
  background: #e5e9ea;
  margin-right: 15px;
  border: 0;
  border-radius: 3px;
  box-shadow: none;
  height: 40px;
}
.g-pg-page,.m-pg-page{
  min-height: 300px !important;
  display: block;
}
.my_check{
  width:30%;
  float:left;
}
.main_div{
  overflow: hidden;
}
#graph_iframe{
  width: 100%;
  border: none;
  height: 1000px;
  overflow: hidden;
}
</style>

    <div class="form-group" id="trainee_list" style="display:none;">
    </div> 

    <div class="form-group" id="workout_list" style="display:none;">
    </div>

    <div class="form-group" id="exec_list" style="display:none; margin-top:25px;">
    <div class="row">
      <div class='div4filter'></div>
      <div class="col-md-5">
          <label style="display:block;">Select Timeline</label>
          <input onchange="create_compare_graph(0);"  name="comparefrom" class="form-control my-input-css"  style="display:inline; width:43%;"  readonly="readonly" >
          <span  style="display:inline; font-weight:bold; margin-right:4%;"  >To</span>
          <input onchange="create_compare_graph(0);"  name="compareto" class="form-control my-input-css"  style="margin-right:0px; display:inline; width:43%;"  readonly="readonly" >
      </div>
      </div>
    </div>

    <div class="form-group" id="graph_div" style="display:none;overflow: hidden;">
        <iframe id="graph_iframe" src="<?php echo base_url() ?>trainer/create_graph"></iframe>
    </div>

<br><br><br><br><br>    
  
  <script>
    $(document).on('click', 'input.select_groups', function(){

      if($(this).prop('checked')){
        var selected = [];
        $("input.select_groups").each(function(){
          if($(this).prop('checked'))
            selected.push($(this).val());
        });

        if(selected.length > 0){
          $.each(selected, function (ii,vv){
            $(".grp_"+vv).each(function(){
              $(this).prop('checked', true);
            })
          });
        }
      }
      else{
        var vv = $(this).val();
        $(".grp_"+vv).each(function(){
          $(this).prop('checked', false);
        })
      }

      $("#firstcheckbox").trigger('change');

      return 0;
      

      var selected = [];
      $("input.select_groups").each(function(){
        if($(this).prop('checked'))
          selected.push($(this).val());
      });

      if(selected.length > 0){
        $.ajax({
          url : "<?php echo base_url() ?>trainer/get_group_members",
          type : 'POST',
          data : {
            'selected_groups' : selected
          },
          success : function(response){
            if(response){
              var content = '';
              content += '<br>';
              content += '<label for="exampleInputEmail1">Group Members</label>';
              
              $.each(response, function(ii, res){
                content += '<div class="main_div">';
                content += '<h5>'+res.group_name+'</h5>';
                content += '<div class="my_check">';
                var c = 1;
                $.each(res.members, function(i,v){
                  content += '<div class="checkbox">';
                  content += '<label >'
                  content += '<input name="grp_member_id[]" class="grp_members" value="'+v.trainee_idd+'" type="checkbox">'+v.fname+' '+v.lname;
                  content += '</label>';
                  content += '</div>';

                  if(c%5 == 0){
                    content += '</div>';
                    content += '<div class="my_check">';
                  }
                  c++;
                });
                content += '</div>';
                content += '</div>';
              });

              $("#trainee_list").html(content);
              $("#trainee_list").show();
            }
          }
        });
      }
      else{
        $("#trainee_list").hide();
        $("#workout_list").hide();
        $("#exec_list").hide();
        $("#graph_div").hide();
      }   
    });

    $(document).on('change', '.grp_members', function(){

      if($(this).prop('checked') == false){
        var grpidd = $(this).attr("grpidd");
        $(".sgrp_"+grpidd).prop('checked', false);
      }
      $("#workout_list").hide();
      $("#exec_list").hide();
      $("#graph_div").hide();

      var selected_members = [];
      $('.grp_members').each(function(i,v){
        if($(this).prop('checked')){
          selected_members.push($(this).val());
        }
      });
      if(selected_members.length > 0){
        $.ajax({
          url : "<?php echo base_url() ?>trainer/get_group_members_exercise",
          type : 'POST',
          data : {
            'selected_members' : selected_members
          },
          success : function(res){
            if(res){
    

var content = '';
content += '<div class="col-md-4">';
    content += '<label>Select Common Exercises</label>';
    content += '<select onchange="create_compare_graph(1);"  class="form-control my-select-css" id="select_exercise">';
      content += '<option value="">Select Exercise</option>';
      $.each(res, function(i,v){
        // content += '<option data-wid="'+v.workout_id+'" value="'+v.id+'"> '+v.wname+' - '+v.name+'</option>';
        content += '<option data-wid="'+v.workout_id+'" value="'+v.id+'" style="text-transform:capitalize"> '+v.name+'</option>';
      });
    content += '</select>';
content += '</div>';
content += '<div class="col-md-3">';
    content += '<label>Select Number of Reps</label>';
    content += '<select onchange="create_compare_graph(0);"  id="select_reps" class="form-control my-select-css">';
        content += '<option value="">Select Reps</option>';
    content += '</select>';
content += '</div>';
              
       
                $('[name=comparefrom]').val(null);
                $('[name=compareto]').val(null);
                $("#exec_list .div4filter").html(content);
                $("#exec_list").show();
            }
            else{
                $("#exec_list").hide();
                $("#exec_list .div4filter").html('');
            }
          }
        });
      }
    });
    
    
function create_compare_graph(flag){
  var selected_members = [];
  $('.grp_members').each(function(i,v){
    if($(this).prop('checked')){
      selected_members.push($(this).val());
    }
  });
  
  var select_exercise = $("#select_exercise").val();
  var select_workout = $("#select_exercise option[value="+select_exercise+"]").data('wid');

  var select_reps = $("#select_reps").val();
  if(!select_reps){
    select_reps = 99999;
  }

  var from = $('[name=comparefrom]').val().replace('/','-').replace('/','-');
  if(!from){
    from = '01-01-2000';
  }

  var to = $('[name=compareto]').val().replace('/','-').replace('/','-');
  if(!to){
    to = '01-01-2030';
  }

  date_validation(from,to);

  if(flag == 1){
    $("#graph_div").hide();
    $.ajax({
      url : '<?php echo base_url(); ?>trainer/get_reps_by_exercise/'+select_exercise+'/'+selected_members.join('-'), 
      success : function(res){
        $('#select_reps').html(res);
      }
    });
  }
  else if(flag == 0){
    $("#graph_iframe").attr('src', '<?php echo base_url() ?>trainer/create_graph/'+selected_members.join('-')+'/'+select_workout+'/'+select_exercise+'/'+select_reps+'/'+from+'/'+to);
    $("#graph_div").show();
  }
}

</script>

<?php endif; ?>
              

<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.ru.js"></script>
<script type="text/javascript">
$(window).load(function(){
    $('[name=comparefrom]').datetimepicker({
    pickTime: false,
    });
    $('[name=compareto]').datetimepicker({
    pickTime: false,
    });
});
</script>