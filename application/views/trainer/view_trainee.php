
                        <!--fullcalendar-->
    <script src="<?php echo base_url() ?>assets/theme/assets/fullcalendar/fullcalendar.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/theme/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>

          <!--morris chart
    <script src="<?php echo base_url() ?>assets/theme/js/jquery.sparkline.js"></script>
    <script src="<?php echo base_url() ?>assets/theme/assets/morris/morris.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/theme/assets/morris/raphael-min.js" type="text/javascript"></script>-->

      <!--flot chart
    <script language="javascript" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.flot.categories.js"></script>
    <script src="<?php echo base_url() ?>assets/theme/js/dash-flot-set.js"></script>-->


         <!--page script
    <script class="include" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/script.js"></script>
    <script src="<?php echo base_url() ?>assets/theme/js/morris-script.js"></script>-->
    <style type="text/css">
      .panel-heading span{
        text-align: center !important;
      }
      span.fc-event-title:hover{
        cursor:pointer !important;
        background-color: rgb(66, 80, 83) !important;
        color: #fff !important;

      }
      .fc-button-next {
          right: 35% !important;
      }
      .fc-button-prev { {
          left: 35% !important;
      }
    </style>
  

<style type="text/css">
/*.overflow_control_class{
height: 860px ! important;
overflow-y: scroll;
}*/

</style>

<script type="text/javascript">
  function overflow_control(){
        $('#workOutDetails').removeClass('overflow_control_class');
        var height =  $('#workOutDetails').height();
        if(height>846){
            $('#workOutDetails').css('overflow-y', 'scroll');
        }
    }

    $(window).load(function(){
   // setTimeout(function(){
     var cal_h = $('#calendarview').height();     
     // alert(cal_h);
     $('#workOutDetails').height(cal_h);
   // },2000);

});

$(window).resize(function(){
   // setTimeout(function(){
     var cal_h = $('#calendarview').height();
     console.log(cal_h);
     // alert(cal_h);
     $('#workOutDetails').height(cal_h);
   // },2000);

});
</script>


             <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/jquery.nearest.js"></script>
        <div class="row" style="margin-bottom:3%" >
          <div class="col-lg-4" id="workOutDetails">
            <?php if($workout){ ?>
            <div class="panel">
              <div class="panel-heading"><?php echo date('m/d/y', strtotime($workout['date'])); ?> - <?php echo $workout['name']; ?> <?php if($workout->status == 1){ ?><img src="<?php echo base_url() ?>assets/theme/img/gl.png"><?php }else{ ?><img src="<?php echo base_url() ?>assets/theme/img/cross.png" width=""><?php } ?> <span style=""><a style="color:#fff;" href="javascript:void(0);" onclick="printDiv('<?php echo $workout["workout_id"] ?>')" class="print_btn" title="Print"><i class="glyphicon glyphicon-print"></i></a></span></div>
              <div class="panel-body">
                <h4>Trainer Notes</h4>
                <p><?php echo $workout['description']; ?></p>
                <h4>Trainee Comments</h4>
                <p><?php echo $workout['notes']; ?></p>
              </div>
            </div>

            <?php if($workout['exercises']){ foreach($workout['exercises'] as $row){ ?>
              <h4><?php echo $row['exercise'] ?></h4>              
              <table class="table table-striped home-table">
                <thead>
                  <tr>
                    <th style="width:50%;">Instructions</th>
                    <th>Results</th>
                  </tr>
                </thead>
                <tbody>                
                <?php if($row['sets']){ $i = 1; foreach($row['sets'] as $val){ ?> 
                  <tr>
                    <td>Set <?php echo $i ?>: <?php echo $val->value.'x'.$val->reps; ?></td>
                    <?php if($val->resultweight!="" || $val->resultreps!=""): ?>
                    <td>
                    Set <?php echo $i ?>: <?php echo $val->resultweight.'x'.$val->resultreps; ?>&nbsp;
                    <?php if($val->resultweight==$val->value && $val->resultreps==$val->reps){ ?> 
                      <i class="fa fa-check" style="color:green"></i>
                    <?php }elseif(($val->resultweight >= $val->value && $val->resultreps > $val->reps) || ($val->resultweight > $val->value && $val->resultreps >= $val->reps) ){ ?>
                      <i class="fa fa-exclamation" style="color:green"></i>
                    <?php }else{ ?> 
                      <i class="fa fa-times" style="color:red"></i> 
                    <?php } ?>
                    <!-- <span style="float:right"><a href="javascript:void(0)" onclick="return change_sets(<?php echo $val->set_id ?>)"><i class="fa fa-edit"></i></a>&nbsp;</span>  -->
                    </td>  
                  <?php else: ?>
                    <!-- <td>Set <?php echo $i ?> : - <span style="float:right"><a href="javascript:void(0)" onclick="return change_sets(<?php echo $val->set_id ?>)"><i class="fa fa-edit"></i></a>&nbsp;</span></td> -->
                  <?php endif; ?>
                  </tr>
                  <?php $i++; } } ?>                      
                </tbody>
              </table>
            <?php } } ?>            
           
            <?php }else{ ?>
              <div class="panel">
              <div class="panel-heading"></div>
              <div class="panel-body">
               <h4>No workout for today</h4>
              </div>
            </div>
            <?php } ?>
          </div>
          <div class="col-lg-8">
            <div id='calendarview'></div>
          </div>
        </div>


        <div class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title">Update values</h4>
            </div>
            <div class="modal-body">
              <form class="form-inline" id="setform" style="text-align:center">
              <input type="hidden" id="setid" value="">
              <input type="hidden" id="setwrkout" value="">
              <div class="form-group">
                <div class="input-group">
                  <label class="sr-only" for="exampleInputEmail2">Result Weight</label>                
                  <input type="text" id="setweight" class="form-control" placeholder="weight">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <label class="sr-only" for="exampleInputEmail2">Result Reps</label>                  
                  <input type="text" id="setreps" class="form-control" placeholder="Reps">
                </div>
              </div>             
                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="closemmodal" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="submit" onclick="return updateset()">Save changes</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->




        <style type="text/css">
        .fc-button-next{
          right: 35%;
        }
        </style>

<script>
function change_sets(id,wrkout){
  $('#setweight').val(""); 
  $('#setreps').val(""); 
  $('#setid').val("");
  $('.modal').modal();
  $('#setid').val(id);
  $('#setwrkout').val(wrkout);
}

function updateset(){
  var w = $('#setweight').val(); 
  var r = $('#setreps').val(); 
  var id= $('#setid').val();
  var wrkout = $('#setwrkout').val();

  if(allnumeric(w) && allnumeric(r)){
    $.ajax({
      type:'POST',
      url:'<?php echo base_url() ?>trainee/ajax_update_sets/'+id+'/'+w+'/'+r,
      success:function(res){
        if(res == 1){
          alert('successfully updated.');          
          $('#closemmodal').trigger('click');
          $('.'+wrkout).trigger('click');
          // window.location = '<?php echo current_url(); ?>';
        }else{
          alert('not updated.');
        }
      }
    });
  }else{
    alert('please enter a number');
  }

}

function allnumeric(inputtxt){  
  var numbers = /^[0-9]+$/;  
  if(inputtxt.match(numbers)){        
    return true;  
  }else {              
    return false;  
  }  
} 


function change_anytime_image(){
$('#calendarview .fc-event-inner .fc-event-title').each(function(){
  var anytime = $(this).html();
  if(anytime=="abcxyzxyzabcxyzabcabcxyz"){
     image = '<img src="<?php echo base_url() ?>assets/theme/img/dumbbell.png">';
     $(this).html(image);
  }
});
}

$(document).on('click','.fc-text-arrow',function(){
  // setTimeout(function(){
    change_anytime_image();
  // },1000);
});

$(document).ready(function(){

  var x = '<?php echo trainee_workout($trainee_id); ?>';  
    if(x){
        var y = JSON.parse(x);
        var eventarr = $.map(y, function(index) {  
            return {
                title: index.title,
                start: index.start,
                className: index.className,
                //end: index.end,
                // color: index.bg,
                // allDay: index.allDay,
                // textColor: '#EFC529',
                draggable:false
            };
        });
    }
    $('#calendarview').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        events: eventarr,
        eventClick: function(calEvent, jsEvent, view) {
          $('.fc-day').css('background-color','');
             var clickedDate = $.nearest({x: jsEvent.pageX, y: jsEvent.pageY}, '.fc-day').css('background-color','#bce8f1');
             // var clickedDate = $.nearest({x: jsEvent.pageX, y: jsEvent.pageY}, '.fc-day').attr('data-date');
            // console.log('Event: ' + calEvent.className);
            // console.log('Event: ' + calEvent.start);
            getWorkoutDetails(calEvent.className, calEvent.start);
        }
    });

       change_anytime_image();

    <?php if($workout){ ?>
      getWorkoutDetails(<?php echo $workout['workout_id']; ?>, new Date());
    <?php } ?>
});

function getWorkoutDetails (workOutId, date) { //traineeId
  var trainee_id = '<?php echo $trainee_id; ?>';
  if (workOutId[0]) {
    wid = workOutId[0];
  }else{
    wid = workOutId;
  }
  
  $.ajax({
    url: '<?php echo base_url() ?>trainee/ajaxWorkoutDetails',
    type: 'POST',
    data:{
      workOutId : wid,
      date : date,
      trainee_id : trainee_id
    }
  }).done(function(response){
    // alert(response);
    // console.log(response);
    var res = JSON.parse(response);
    console.log (res); 
    // alert(res);   
    var div = $('#workOutDetails');
    if (res.currentWorkout.status == "1") {      
     var statusimg = '<img src="<?php echo base_url() ?>assets/theme/img/gl.png">';      
    }else{
     var statusimg = '<img src="<?php echo base_url() ?>assets/theme/img/cross.png">';            
    }

    var result = '';
    var print = '';

    if(!res.status){
      result += '<div class="panel">';
      result += '<div class="panel-heading"></div>';
      result += '<div class="panel-body">';
      result += '<h4>No Workout details found.</h4>';
      result += '</div>';
      result += '</div>';
      div.html(result);
      $('#ajaxcontent').html('');
    }else{
      if(res.currentWorkout.status == 1){
        var st = 'Completed';
      }else{
        var st = 'Pending';            
      }

      print += '<div id="print_meajax">';
      print +='<style>div#print_meajax { display: none; } @media print{ div#print_meajax { display: block; } /* .print-break {page-break-after:always;} */ }</style>';
      print += '<h3 style="text-align:center;">'+res.currentWorkout.name+'</h3>';
      print += ' <div class="form-group">';
      // print += '<img src="<?php echo BUCKET_PATH ?>'+res.currentWorkout.image+'" alt="Workout Image" style="max-width:80%;">';
      print += '</div>';        
      print += '<div class="form-group"><label for="">Date - '+res.currentWorkout.date+'</label></div>';
          
      if(res.currentWorkout.anytime == '0'){
        print += '<div class="form-group"><label for="">Time - '+res.currentWorkout.time+'</label></div>'
      }

      print += '<div class="form-group"><label for="">Description - '+res.currentWorkout.description+'</label></div>';
      print += '<div class="form-group"><label for="">Status -'+st+'</label></div>';
      print += '<div class="print-break"></div>';          
      // for print workout

      result += '<div class="panel">';
      result += '<div class="panel-heading">';
      if (res.navigateArray) {
        if (res.navigateArray.prev) {
          result += '<a style="margin: 0 4% 0 0; color: rgb(255, 255, 255);" onclick="getWorkoutDetails(' + res.navigateArray.prev + ', \'' + res.navigateArray.date + '\');" href="javascript:void(0);"><span class="fc-text-arrow">‹</span></a>';
        };
        result += '<span>' + res.dateToshow + ' - ' + res.currentWorkout.name + '</span>';               
        result += statusimg;   
        result += '<span style=""><a style="color:#fff;" href="javascript:void(0);" onclick="ajaxprintDiv()" class="print_btn" title="Print"><i class="glyphicon glyphicon-print"></i></a></span>';             
        
        if (res.navigateArray.nex) {
          result += '<a style="margin: 0 0 0 7%; color: rgb(255, 255, 255);" onclick="getWorkoutDetails(' + res.navigateArray.nex + ', \'' + res.navigateArray.date + '\');" href="javascript:void(0);"><span class="fc-text-arrow">›</span></a>';
        };
      }else{
        result += '<span>' + res.dateToshow + ' - ' + res.currentWorkout.name + '</span>'; 
        result += statusimg;
        result += '<span style=""><a style="color:#fff;" href="javascript:void(0);" onclick="ajaxprintDiv()" class="print_btn" title="Print"><i class="glyphicon glyphicon-print"></i></a></span>';
      }
      
      result += '</div>';
      result += '<div class="panel-body">';
      result += '<h4>Trainer Notes</h4>';
      result += '<p>'+ res.currentWorkout.description +'</p>';
      result += '<h4>Trainee Comments</h4>';
      if(res.trainee_workout_notes.notes){
      result += '<p>'+res.trainee_workout_notes.notes+'</p>';
      }else{
        result += '<p>No Comments Found.</p>';
      }
      result += '</div>';
      result += '</div>';

      $.each(res.details, function(index, v1) {  
        // for print exercise
        if(v1.status == 1){
          var e_st = 'Completed';
        }else{
          var e_st = 'Pending';            
        }
                
        print +='<h3 style="text-align:center;">'+v1.exercise+'</h3>';
        
        if(v1.notes != '' && v1.notes != null)
          print += '<p> Notes : '+ v1.notes +'</p>';
        
        print += ' <div class="form-group">';
        // print += '<img src="<?php echo BUCKET_PATH ?>'+v1.image+'" alt="Workout Image" style="max-width:80%;">';
        print += '</div>';        
        print += '<div class="form-group"><label for="">Description - '+v1.description+'</label></div>';
        // print += '<div class="form-group"><label for="">Rest time between sets : '+v1.resttime+' minute(s)</label></div>';
        print += '<div class="form-group"><label for="">Status -'+e_st+'</label></div>';
              
        print += '<div class="form-group"><table class="table table-bordered" style=""><thead><tr><th>Set</th><th>Weight</th><th>Reps</th><th>Status</th><th>Take Weight</th><th>Take Reps</th></tr></thead><tbody>';              

        result += '<h4>'+ v1.exercise +'</h4>';
        
        if(v1.trainee_notes.notes){
        // result += '<h4>Trainee Comments</h4>';
          result += '<p> Notes : '+ v1.trainee_notes.notes +'</p>';
        }
          
        result += '<table class="table table-striped home-table">';
        result += '<thead>';
        result += '<tr>';
        result += '<th style="width:50%;">Instructions</th>';
        result += '<th>Results</th>';
        result += '</tr>';
        result += '</thead>';
        result += '<tbody>';
        var setCount = 1;
        $.each(v1.sets, function(index2, v2) {
          if( v2.resultweight !="" && v2.resultreps!=""){
            var res_d = v2.resultweight +'x'+ v2.resultreps+' ';
          }else{
            var res_d = "- ";
          }

          if (v2.value == v2.resultweight && v2.reps == v2.resultreps ) {      
            var repscheck = '<i class="fa fa-check" style="color:green"></i>';      
          }else if((parseInt(v2.resultweight) >= parseInt(v2.value) && parseInt(v2.resultreps) > parseInt(v2.reps)) || (parseInt(v2.resultweight) > parseInt(v2.value) && parseInt(v2.resultreps) >= parseInt(v2.reps))){
            var repscheck = '<i class="fa fa-exclamation" style="color:green"></i>';      
          }else{
            var repscheck = '<i class="fa fa-times" style="color:red"></i>';            
          }

          if(v2.status == 1){
            var s_st = 'Completed';
          }else{
            var s_st = 'Pending';            
          }

          // for print set
          print += '<tr>';                  
          print += '<td>'+setCount+'</td>';
          print += '<td>'+v2.value+'</td>';
          print += '<td>'+v2.reps+'</td>';
          print += '<td>'+s_st+'</td>';
          print += '<td>'+v2.resultweight+'</td>';
          print += '<td>'+v2.resultreps+'</td>';                    
          print += '</tr>';
          // for print set

          result += '<tr>';                  
          result += '<td>Set '+ setCount +': '+ v2.value +'x'+ v2.reps +'</td>';
          result += '<td>Set '+ setCount +': '+res_d+repscheck+'<span style="float:right">&nbsp;</span></td>';
          // result += '<td>Set '+ setCount +': '+res_d+repscheck+'<span style="float:right"><a href="javascript:void(0)" onclick="return change_sets('+v2.id+','+res.currentWorkout.id+')"><i class="fa fa-edit"></i></a>&nbsp;</span></td>';
          result += '</tr>';
          setCount++;
        });
        print += '</tbody></table></div>';
        result += '</tbody>';
        result += '</table>';
      });
      print += '</div>';
      $('#ajaxcontent').html(print);
      div.html(result);
    overflow_control()
    }
  });
}
</script>

<style type="text/css" media="print">
    * { display: none; }
</style>

<?php  if ($workout): ?>

<div id="print_me<?php echo $workout['workout_id'] ?>">
  <style>
    div#print_me<?php echo $workout['workout_id'] ?> { display: none; }
    @media print{
      div#print_me<?php echo $workout['workout_id'] ?> { display: block; }
      /*.print-break {page-break-after:always;}*/
    }
  </style>

  <?php //$workout = get_workout_full_detail($workout['workout_id']); ?>

  <h3 style="text-align:center;"><?php echo ucfirst($workout['name']); ?></h3>

        <!-- <div class="form-group">
          <img src="<?php echo BUCKET_PATH.$workout['image'] ?>" alt="Workout Image" style="max-width:80%;">
        </div> -->

        
        <div class="form-group">
          <label for="">Date - <?php echo $workout['date'] ?></label>                                                       
        </div>
        
        <?php if($workout['anytime'] == '0' ){ ?>
          <div class="form-group">
            <label for="">Time - <?php echo $workout['time'] ?></label>                                                       
          </div>
        <?php } ?>
        
        <div class="form-group">
          <label for="">Description - <?php echo ucfirst($workout['description']); ?></label>                                                       
        </div>
        <div class="form-group">
          <label for="">Status - 
            <?php
              if($workout['status'] == 0){
                echo 'Pending';
              }
              elseif($workout['status'] == 2){
                echo 'Not Done';
              }
              else{
                echo 'Completed';
              }
            ?>
          </label>                                                       
        </div>

        
        <div class="print-break"></div>
        
        <?php foreach($workout['exercises'] as $row){ ?>
        

          <h3 style="text-align:center;"><?php echo ucfirst($row['exercise']); ?></h3>


          <!-- <div class="form-group">
            <img src="<?php echo BUCKET_PATH.$row['image'] ?>" alt="Exercise Image" style="max-width:80%;">
          </div> -->

          <div class="form-group">
            <label for="">Description - <?php echo ucfirst($row['description']); ?></label>                                                       
          </div>

          <!-- <div class="form-group">
            <label for="">Rest time between sets : <?php echo $row['resttime']; ?> minute(s)</label>
          </div> -->

          <div class="form-group">
            <label for="">Exercise status : 
              <?php
                if($row['status'] == 0){
                  echo 'Pending';
                }
                else{
                  echo 'Completed';
                }
              ?>
            </label>
          </div>

          <div class="form-group">
            <table class="table table-bordered" style="">
              <thead>
                <tr>
                  <th>Set</th>
                  <th>Weight</th>
                  <th>Reps</th>
                  <th>Status</th>
                  <th>Take Weight</th>
                  <th>Take Reps</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; foreach($row['sets'] as $var){ ?>
                  <tr>
                    <td><?php echo $i ?> of <?php echo count($row['sets']); ?> </td>
                    <td><?php echo $var->value; ?> </td>
                    <td><?php echo $var->reps; ?> </td>
                    <td>
                      <?php
                        if($var->set_status == 0){
                          echo 'Pending';
                        }
                        else{
                          echo 'Completed';
                        }
                      ?>
                    </td>
                    <td><?php echo $var->resultweight; ?> </td>
                    <td><?php echo $var->resultreps; ?> </td>
                  </tr>
                <?php $i++; } ?>
              </tbody>
            </table>
          </div>

          <div class="print-break"></div>

        <?php } ?>

</div>

<?php  endif; /* */ ?>

<div id="ajaxcontent">
  
</div>



<script>
  $(window).load(function(){
    $(".print_btn_temp").remove();
    $(".print_btn").show();
  });

  function printDiv(wId) {
    var divName = 'print_me'+wId;
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;

    window.location ='<?php echo current_url() ?>';
  }

   function ajaxprintDiv() {
    var divName = 'print_meajax';
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();
    document.body.innerHTML = originalContents;
    window.location ='<?php echo current_url() ?>';
  }
</script>
<style type="text/css">
  .fc-event-time, .fc-event-title{
    cursor: pointer;
  }

   .fc-today span.fc-event-title{
      color: #fff !important;
   }

  .fc-today{
    background-color: #5190ab;
  }

  .fc-today .fc-day-number, .fc-today .fc-day-content{
    color: #fff !important;
  }


   #calendarview td{
    height: 130px;
  }

  #calendarview .fc-header td{
    height: auto;
  }

  .fc-event.fc-event-hori.fc-event-start.fc-event-end{
    text-align: center;
  }
  
</style>