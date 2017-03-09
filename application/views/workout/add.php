<style>
  select.grey-box{
    -moz-appearance: none;
    -webkit-appearance: none;
    background: url("<?php echo base_url(); ?>assets/theme/img/select.png") no-repeat scroll right 10px center #e5e9ea;
    margin-right: 15px;
    border: 0;
    border-radius: 3px;
    box-shadow: none;
    height: 40px;
    border: 0;
    text-transform: capitalize;
  }
  .arr_class{
    -moz-appearance: none;
    -webkit-appearance: none;
    background: url("<?php echo base_url(); ?>assets/theme/img/select.png") no-repeat scroll right 10px center #e5e9ea !important;
  }
  .my_check{
    width:30%;
    float:left;
  }
  .main_div{
    overflow: hidden;
  }
  .child_gr_div{
    margin-left: 15%;
  }
  .hide{
    display: none;
  }
  .exercise_div{
    background: #dadcdc;
    padding: 20px;
  }
  .exercise_div label.ehead{
    width: 100%;
  }
  .exercise_div label.ehead a{
    float: right;
  }
  .circuit_div{
    background: #dadcdc;
    padding: 20px;
  }
  .circuit_div label.ehead{
    width: 100%;
  }
  .circuit_div label.ehead a{
    float: right;
  }
  .selector_div{
    background: #dadcdc;
    padding: 20px;
  }
  .selector_div label.ehead{
    width: 100%;
  }
  .selector_div label.ehead a{
    float: right;
  }
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.ru.js"></script>
<script type="text/javascript">
  $(function () {
    $('#datepicker').datetimepicker({
      pickTime: false,
      minDate: moment("<?php echo $mdate ?>")
    });
    
    /*
    $('#timepicker').datetimepicker({
      pickDate: false
    });
    */
  });
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>
  $(function() {
    arr = JSON.parse('<?php echo $default ?>');
    var availableTags = [];
    
    $.each(arr, function(index,va){     
      var a = { label:va.name , value:va.name, id: va.id};
      availableTags.push(a);
    });
    
    $(document).on('focus','.defaul_exercise', function(){
      $( ".defaul_exercise" ).autocomplete({
        source: availableTags
      });
    });
  });
</script>

<?php echo alert(); ?>
<h3>Add New Workout</h3>

<?php echo form_open_multipart(current_url(), array('class'=>'tasi-form', 'id'=>'contentForm')); ?>

<?php if ($select_groups || $group_members): ?>
<?php $limit = 18; ?>
<div class="row">
  <div class="col-md-12">
    <?php if($select_groups){ ?>  
    <div class="col-md-6 mainpg" style='background-color:#E5E9EA; border-right:10px solid #ECF0F1;'>
      <div class="col-md-12"><h5>Select Group(s)</h5></div>
      <?php $i=1; ?>
      <span class='g-pg-page' value='1' >
      <?php foreach ($select_groups as $group): ?>
      <div class='col-md-4'>
          <div class="checkbox">
              <label style="text-transform:capitalize;">
                <input class="select_groups sgrp_<?php echo $group->id ?>" name="group_id[]" value="<?php echo $group->id ?>" type="checkbox">
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
        <div class="col-md-12"><h5>Select Trainee(s)</h5></div>
        <?php $i=1; ?>
      <span class='m-pg-page' value='1'>
        <?php $vv = TRUE; foreach ($group_members as $group_member): ?>
      <div class='col-md-4'>
            <div class="checkbox">
                <label  style="text-transform:capitalize;">
                  <?php
                    $check_id = '';
                    if($vv){
                      $vv = FALSE;
                      $check_id = 'firstcheckbox';
                    }
                  ?>
                  <input id="<?php echo $check_id ?>" name="trainee_id[]" class="grp_members grp_<?php echo $group_member->group_id ?>" grpidd="<?php echo $group_member->group_id ?>" value="<?php echo $group_member->id ?>" type="checkbox">
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
  .g-pg-page,.m-pg-page{
    min-height: 300px !important;
    display: block;
  }
</style>
<?php endif; ?>

<br>
<div class="row">
  <div class="col-md-3">
    <div class='input-group date'>
      <label for="exampleInputEmail1">Workout Date</label> 
      <input type='text' class="form-control arr_class" name="date_of_workout" value="<?php echo set_value('date_of_workout') ?>" id='datepicker'/>
      <span class="error"><?php echo form_error('date_of_workout'); ?></span>
    </div>
  </div>
  <div class="col-md-9">
    <div class="form-group">
      <label for="exampleInputEmail1">Workout Name</label> 
      <input type="text" class="form-control" name="name_of_workout" id="name_of_workout" value="<?php echo set_value('name_of_workout'); ?>">
      <span class="error"><?php echo form_error('name_of_workout'); ?></span>
    </div>
  </div>
</div>

<br>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="exampleInputEmail1">Workout Description</label> 
      <textarea class="form-control" rows="5" id="desc_of_workout" name="desc_of_workout"><?php echo set_value('desc_of_workout'); ?></textarea>
      <span class="error"><?php echo form_error('desc_of_workout'); ?></span>
    </div>
  </div>
</div>

<br>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="exampleInputEmail1">Workout Image</label> 
      <input type="file" name="image_workout"><br>
      <?php if ($galleryimages): ?>
        <a href="javascript:void(0)" class="showgallery_w">or Select Image From Library <input type="hidden" value="" name="wrkoutimg" id="wrkoutimg"> </a>              
        <div class="showimage" style="display:none"></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<br>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="exampleInputEmail1">Workout Instructions</label> 
    </div>
  </div>
</div>

<div class="exercise_parent_div">

</div>

<label id="loading_image" style="display:none;">
  <img src="<?php echo base_url() ?>assets/theme/img/preloader-bar.gif">
  <br>
  <br>
</label>

<div class="row">
  <div class="col-md-12">
    <a id="new_exercise" href="javascript:void(0);" title="Add Exercise"><img src="<?php echo base_url() ?>assets/theme/img/addNew.png">Exercise</a>
    &nbsp;&nbsp;&nbsp;
    
    <?php if($default_circuit){ ?>
      <a id="new_circuit" href="javascript:void(0);" title="Add Circuit"><img src="<?php echo base_url() ?>assets/theme/img/addNew.png">Circuit</a>
    <?php } ?>

    <br>
    <br>
    <input type="submit" class="btn btn-primary" value="Submit">
    <br>
    <br>
  </div>
</div>
<input type="hidden" id="exercise_or_circuit_name" name="exercise_or_circuit_name" value="0">
</form>

<div class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="remove_hasclass()" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Select Image</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <?php if ($galleryimages): $i =1; foreach($galleryimages as $row): ?>  
            <div class="col-sm-4" style="text-align:center; margin-bottom:10px">
              <div class="checkbox">
                <img src="<?php echo BUCKET_PATH.$row->image ?>" alt="" style="max-width:100%">
                <input name="galleryimage"  value="<?php echo $row->image ?>" type="radio" value="" style="display:inline">
              </div>
            </div>
    
            <?php if($i%3 == 0){ ?>              
              </div>
              <div class="row">
            <?php } ?>
          <?php $i++; endforeach; endif; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="closemmodal" onclick="remove_hasclass()" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit" onclick="return selectgallery()">Select</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
  $(document).on('click','.showgallery_w',function(){
    $('.modal').modal();                
    $(this).find('#wrkoutimg').addClass('hasimage');
  });
    
  function selectgallery(){
      var val = $('input[name=galleryimage]:checked').val();
      $(document).find('.hasimage').val(val);
      var alink = $(document).find('.hasimage').parent();
      alink.parent().find('.showimage').show();
      var el = '<img src="<?php echo BUCKET_PATH ?>'+val+'" style="width:100px">';
      el += '<a href="javascript:void(0);" class="rem_img">Remove</a>';
      alink.parent().find('.showimage').html(el);
      $('#closemmodal').trigger('click');
  }
    
  function remove_hasclass(){
    $(document).find('.hasimage').removeClass('hasimage');
  }

  $(document).on("click", ".rem_img", function(){
    $(this).hide();
    
    if($(this).parent().parent().find('a.showgallery_w').find('#wrkoutimg').val() != undefined)
      $(this).parent().parent().find('a.showgallery_w').find('#wrkoutimg').val('');
    else if($(this).parent().parent().find('.showgallery_e').find('input.exerciseimg').val() != undefined)
      $(this).parent().parent().find('.showgallery_e').find('input.exerciseimg').val('');
    else
      $(this).parent().parent().find('.showgallery_c').find('input.circexerciseimg').val('');

    $(this).parent().html('');
    $(this).parent().hide();
  });

  function exercise_numbering(){
    $("span.ex_no").each(function (index){
      $(this).html( parseInt(index) + 1 );
    });
  }

  function circuit_numbering(){
    $("span.cr_no").each(function (index){
      $(this).html( parseInt(index) + 1 );
    });
  }

  $(document).on("click", "#new_exercise", function(){

    $(".selector_div").each(function(){
      $(this).remove();
    });

    var select_box = '';

    select_box += '<div class="form-group selector_div">';
    select_box += '<label class="ehead"> Exercise #<span class="ex_no"></span> <a href="javascript:void(0);" class="remove_selector">Remove</a> </label>';

    select_box += '<select class="form-control grey-box new_exercise_select_box">';
    select_box += '<option value="0">Choose Exercise</option>';
    // select_box += '<option value="NEW">  -- Add New Exercise  --  </option>';
    <?php if($default_exercises){ ?>
      <?php foreach($default_exercises as $row){ ?>
        select_box += '<option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>';
      <?php } ?>
    <?php } ?>
    select_box += '</select>';

    select_box += '</div>';

    $(select_box).insertAfter(".exercise_parent_div");

    exercise_numbering();

    // $(this).hide();
  });

  $(document).on("click", "#new_circuit", function(){

    $(".selector_div").each(function(){
      $(this).remove();
    });

    var select_box = '';

    select_box += '<div class="form-group selector_div">';
    select_box += '<label class="ehead"> Circuit #<span class="cr_no"></span> <a href="javascript:void(0);" class="remove_selector">Remove</a> </label>';

    select_box += '<select class="form-control grey-box new_circuit_select_box">';
    select_box += '<option value="0">Choose Circuit</option>';
    <?php if($default_circuit){ ?>
      <?php foreach($default_circuit as $row){ ?>
        select_box += '<option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>';
      <?php } ?>
    <?php } ?>
    select_box += '</select>';

    select_box += '</div>';

    $(select_box).insertAfter(".exercise_parent_div");

    circuit_numbering();
    // $(this).hide();
  });

  setInterval(function(){
    $(".file_image_exercise").each(function(){
      if($(this).val() != ''){
        $(this).parent().find('a.remove_file').show();
      }
      else{
        $(this).parent().find('a.remove_file').hide();
      }
    });
  }, 100);

  $(document).on('click', 'a.remove_file', function(){
    $(this).parent().find('.file_image_exercise').val('');
  });

  $(document).on("change", ".new_exercise_select_box", function(){
    var val = $(this).val();
    if(val != '0'){
      $(".selector_div").each(function(){
        $(this).remove();
      });
      $(this).remove();
      $.ajax({
        url : '<?php echo base_url()._INDEX ?>workout/add_exercise_box',
        data : {
          id : val
        },
        type : 'POST',
        beforeSend : function(){
          $("#loading_image").show();
        },
        success : function(exercise_div){
          if(exercise_div != '0'){
            $(".exercise_parent_div").append(exercise_div);
            exercise_numbering();
          }
          $("#loading_image").hide();
        }
      });
    }
  });

  $(document).on("change", ".new_circuit_select_box", function(){
    var val = $(this).val();
    if(val != '0'){
      $(".selector_div").each(function(){
        $(this).remove();
      });
      $(this).remove();
      $.ajax({
        url : '<?php echo base_url()._INDEX ?>workout/add_circuit_box',
        data : {
          id : val
        },
        type : 'POST',
        beforeSend : function(){
          $("#loading_image").show();
        },
        success : function(circuit_div){
          if(circuit_div != '0'){
            $(".exercise_parent_div").append(circuit_div);
            circuit_numbering();
          }
          $("#loading_image").hide();
        }
      });
    }
  });

  $(document).on('click', '.add_set', function(){
  var rel = $(this).attr('rel');
  
  var html = '';
  
  html += '<div class="form-group sets">';
  html += '<label for="exampleInputEmail1">Set '+rel+'</label> ';
   
  
  html += '&nbsp;&nbsp; <!-- Time --> ';
  html += '<select style="width:20%;display: inline-block;" class="form-control timeinput grey-box" name="time_'+rel+'">';
  html += ' <option>N/A</option>';
  html += ' <option>Failure</option>';
  html += ' <?php for($i=1;$i<=600;$i++):  ?>';
  html += '   <option value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>';
  html += ' <?php endfor; ?>  ';
  html += '</select>';
  html += '&nbsp;&nbsp;&nbsp; <!-- Weight --> ';
  html += '<select style="width:20%;display: inline-block;" class="form-control setinput grey-box" name="set_'+rel+'">';
  html += ' <option>N/A</option>';
  html += ' <option>Body Weight</option>';
  html += ' <?php for($i=1;$i<=1000;$i++):  ?>';
  html += '   <option value="<?php echo $i; ?>"><?php echo $i; ?> lbs</option>';
  html += ' <?php endfor; ?>  ';
  html += '</select>';
  html += '&nbsp;&nbsp;&nbsp; <!-- Reps --> ';
  html += '<select style="width:20%;display: inline-block;" class="form-control repsinput grey-box" name="reps_'+rel+'">';
  html += ' <option>N/A</option>';
  html += ' <option>Failure</option>';
  html += ' <?php for($i=1;$i<=100;$i++):  ?>';
  html += '   <option><?php echo $i; ?></option>';
  html += ' <?php endfor; ?>  ';
  html += '</select>';


  html += '<a class="remove_set" href="javascript:void(0);" rel="'+rel+'"> Remove </a>';
  html += '<br>';
  html += '<a class="add_set" href="javascript:void(0);" rel="'+parseInt(parseInt(rel)+1)+'"> Add Set </a>';
  html += '</div>';
  
  $(this).parent().parent().append(html);
  
  $(this).hide();
  });
  
  $(document).on('click', '.remove_set', function(){
  var parentDiv = $(this).parent().parent();
  $(this).parent().remove();
  var obj = [];
  var i = 1;
  
  parentDiv.find('.sets').each(function(){
    $(this).find('label').html('Set '+i);
    $(this).find('a.remove_set').attr('rel', i);
    $(this).find('a.add_set').attr('rel', parseInt(i+1));
  
    if(i == parentDiv.find('.sets').length){
    $(this).find('a.add_set').show();
    }
  
    i++;
  });
  
  });

  $(document).on('click','.showgallery_e',function(){
    if($(this).find('.exerciseimg').val() == ''){
      $('input[name=galleryimage]:checked').prop('checked', false);
    }

    $('.modal').modal();                
    $(this).find('.exerciseimg').addClass('hasimage');
  });

  $(document).on('click','.showgallery_c',function(){
    if($(this).find('.circexerciseimg').val() == ''){
      $('input[name=galleryimage]:checked').prop('checked', false);
    }

    $('.modal').modal();                
    $(this).find('.circexerciseimg').addClass('hasimage');
  });

  $(document).on("click", ".remove_exercise", function(){
    if(confirm('Are you sure?')){
      $(this).parent().parent().remove();
      exercise_numbering();
    }
  });

  $(document).on("click", ".remove_circuit", function(){
    if(confirm('Are you sure?')){
      $(this).parent().parent().remove();
      circuit_numbering();
    }
  });

  $(document).on("click", ".remove_circuit_exercise", function(){
    if(confirm('Are you sure?')){
      var circuit_exercises = 0;
      $(this).parent().parent().parent().find('.circuit_exercise_div').each(function(){
        circuit_exercises++;
      });

      if(circuit_exercises == 1)
        $(this).parent().parent().parent().remove();
      else  
        $(this).parent().parent().remove();
    }
  });

  $(document).on("click", ".remove_selector", function(){
    $(".selector_div").each(function(){
      $(this).remove();
    });
  });

  $(document).on('submit', '#contentForm', function(event){
    var flag = 1;
    var msg = [];

    var checkbox_error = true;
    $('.select_groups').each(function(){
      if($(this).prop('checked') == true)
        checkbox_error = false;     
    });
    $('.grp_members').each(function(){
      if($(this).prop('checked') == true)
        checkbox_error = false;
    });

    if(checkbox_error){
      flag = 2;
      msg.push('# Please select atleast one trainee or group.');
    }

    if($("input[name=date_of_workout]").val() == ''){
      flag = 2;
      msg.push('# Please enter workout date.');
    }

    if($("#name_of_workout").val() == ''){
      flag = 2;
      msg.push('# Please enter workout name.');
    }

    if($("#desc_of_workout").val() == ''){
      flag = 2;
      msg.push('# Please enter workout description.');
    }

    var exer_empty_error = true;
    $(".exercise_parent_div .counting_box").each(function(){
      exer_empty_error = false;
    });

    if(exer_empty_error){
      flag = 2;
      msg.push('# Please add atleast one exercise or circuit.');
    }else{
      $(".exercise_parent_div .counting_box").each(function(){
        if($(this).hasClass('exercise_div')){
          var ele = $(this).find('input.exer_name');
          var ele_length = ele.val().length;
          if(ele.val() == ''){
            ele.css('border', '1px red solid');
            flag = 2;
          }
          else if(ele_length < 3 || ele_length > 25){
            ele.css('border', '1px red solid');
            $(this).find('span.error.exer_name_error').html('Exercise name length should be between 3 to 25 characters');
            flag = 2;
          }
          else{
            ele.css('border', '');
            $(this).find('span.error.exer_name_error').html('');
          }
        }
        else if($(this).hasClass('circuit_div')){
          var ele = $(this).find('input.circ_name');
          var ele_length = ele.val().length;
          if(ele.val() == ''){
            ele.css('border', '1px red solid');
            flag = 2;
          }
          else if(ele_length < 3 || ele_length > 25){
            ele.css('border', '1px red solid');
            $(this).find('span.error.circ_name_error').html('Circuit name length should be between 3 to 25 characters');
            flag = 2;
          }
          else{
            ele.css('border', '');
            $(this).find('span.error.circ_name_error').html('');
          }
        }
      });
    }

    if(flag == 2 && msg.length == 0){
      alert('# Please check exercise or circuit name.');
      return false;
    }
    else if(flag == 2){
      alert(msg.join('\n'));
      return false;
    }
    else{
      var ind = 1;
      var exercise_or_circuit_name = [];
      $(".exercise_parent_div .counting_box").each(function(){
        if($(this).hasClass('exercise_div')){
          var main_name = 'exercise_ex'+ind;
          var var_name = 'ex'+ind;
          $(this).find('input.exer_name').attr('name', var_name+'_name');
          $(this).find('input.default_exer_id').attr('name', var_name+'_default_id');
          $(this).find('textarea.exer_desc').attr('name', var_name+'_desc');
          $(this).find('input.file_image_exercise').attr('name', var_name+'_file');
          if($(this).find('input.exerciseimg')){
            $(this).find('input.exerciseimg').attr('name', var_name+'_galleryname');
          }
          $(this).find('.sets').each(function(){
            $(this).find('select.timeinput').attr('name', var_name+'_time[]');
            $(this).find('select.setinput').attr('name', var_name+'_weight[]');
            $(this).find('select.repsinput').attr('name', var_name+'_reps[]');
          });
        }
        else if($(this).hasClass('circuit_div')){
          var main_name = 'circuit_cr'+ind;         
          var var_name = 'cr'+ind;
          $(this).find('input.circ_name').attr('name', var_name+'_name');
          $(this).find('textarea.circ_desc').attr('name', var_name+'_desc');
          var ex_number = 1;
          var ex_name = var_name+'_exercise';
          $(this).find('.circuit_exercise_div').each(function(){
            $(this).find("input.circ_exe_name").attr('name' , ex_name+'_name[]');
            $(this).find("input.circ_exe_def_id").attr('name' , ex_name+'_def_id[]');
            $(this).find("textarea.circ_exe_desc").attr('name' , ex_name+'_desc[]');
            $(this).find("input.file_image_exercise").attr('name' , ex_name+'_file[]');
            if($(this).find('input.circexerciseimg')){
              $(this).find('input.circexerciseimg').attr('name', ex_name+'_galleryname[]');
            }           
            $(this).find('.sets').each(function(){
              $(this).find('select.timeinput').attr('name', ex_name+'_time'+ex_number+'[]');
              $(this).find('select.setinput').attr('name', ex_name+'_weight'+ex_number+'[]');
              $(this).find('select.repsinput').attr('name', ex_name+'_reps'+ex_number+'[]');
            });
            ex_number++;
          });
        }
        exercise_or_circuit_name.push(main_name);
        ind++;
      });
      $("#exercise_or_circuit_name").val(exercise_or_circuit_name.join(","));
      return true;
    }
  });
</script>