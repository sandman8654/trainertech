  <script type="text/javascript" src="https://www.google.com/jsapi"></script>

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
<div class="row" style="margin-bottom:-6%">
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
                        <span style="float:right"><a href="javascript:void(0)" onclick="return change_sets(<?php echo $val->set_id ?>)"><i class="fa fa-edit"></i></a>&nbsp;</span> 
                    </td>
                    <?php else: ?>
                    <td>Set <?php echo $i ?> : - <span style="float:right"><a href="javascript:void(0)" onclick="return change_sets(<?php echo $val->set_id ?>)"><i class="fa fa-edit"></i></a>&nbsp;</span></td>
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
    <div  style="margin-top:10%"class="row">
      <div class="col-lg-12">
      <h4>
        Overall Progress

        <select id="graph_sort_by" onchange="create_bar_graph();" class="form-control" style="width:15%; float:right;">
          <option value="0">Current</option>
          <option value="1">Average</option>
          <option value="2">Highest</option>
        </select>

      </h4>

      <h5> Bar Graph </h5>
        <div style="height: 500px;margin-top:2%; padding:20px; background:#fff;" id='barchart'></div>
      </div>
    </div>

    <div style="margin-top:8%" class="row">
      <div class="col-lg-12">
        <h5> Line Graph </h5>
          <div style="height: 500px;margin-top:2%; padding:20px; background:#fff;" id='corechart'></div>
      </div>
    </div>



<div class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Update values</h4>
            </div>
            <div class="modal-body" id='dynamic-set-update-view'>
              <!--  -->
              <!--  -->
              <!--  -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="closemmodal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submit" onclick="return updateset()">Save changes</button>
            </div>
        </div>
    </div>
</div>


<style type="text/css">
    .fc-button-next{
    right: 35%;
    }
</style>
<script>
function change_sets(setid,workoutid){
  $.ajax({
    url : '<?php echo base_url(); ?>trainee/ajax_update_set_view/'+setid+'/'+workoutid,
    success : function(res){
      $('#dynamic-set-update-view').html(res);
      $('.modal').modal();
    }
  });
}
    
function updateset(){
  var workoutid = $('[name=workoutid]').val();
  $.ajax({
    type:'POST',
    data : $('#setform').serialize(),
    url:'<?php echo base_url() ?>trainee/ajax_update_sets',
    success:function(res){
      if(res == 1){
          alert('successfully updated.');          
          $('#closemmodal').trigger('click');
          $('.'+workoutid).trigger('click');
      }else{
          alert('not updated.');
      }
    }
  });
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
      // console.log('workOutId ' + workOutId);
      // console.log('date ' + date);
      // console.log('trainee_id ' + trainee_id);
      if (workOutId[0]) {
        wid = workOutId[0];
      }else{
        wid = workOutId;
      }

      $("#workout_id_for_graph").val(wid);
      create_bar_graph();

      $.ajax({
        url: '<?php echo base_url() ?>trainee/ajaxWorkoutDetails',
        type: 'POST',
        data:{workOutId:wid, date:date, trainee_id:trainee_id}
      }).done(function( response ) {
        var res = JSON.parse(response);
        // console.log (res)    
        var div = $('#workOutDetails');
        if (res.currentWorkout.status == "1") {      
         var statusimg = '<img src="<?php echo base_url() ?>assets/theme/img/gl.png">';      
        }else{
         var statusimg = '<img src="<?php echo base_url() ?>assets/theme/img/cross.png">';            
        }
    
        var result = '';
        var print = '';
    
        // if(array_key_exists('error', res)){
        if(!res.status){
    
          result += '<div class="panel">';
            result += '<div class="panel-heading"></div>';
            result += '<div class="panel-body">';
              result += '<h4>No Workout details found.</h4>';
            result += '</div>';
          result += '</div>';
    
          div.html(result);
          $('ajaxcontent').html('');
    
        }else{
    
            // for print workout
    
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
    

    
              chart_array = new Array();

              $.each(res.details, function(index, v1) {  
                  if(v1.status == 1){
                    var e_st = 'Completed';
                  }else{
                    var e_st = 'Pending';            
                  }
                 
            /*for exercise chart*/
               chart_array.push(v1.sets);
            /*for exercise chart*/

              // console.log(exercise_set_array);
                
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
    
                      // alert(v2.resultweight);
    
                      if (v2.value == v2.resultweight && v2.reps == v2.resultreps ) {      
                       var repscheck = '<i class="fa fa-check" style="color:green"></i>';      
                      }else if((parseInt(v2.resultweight) >= parseInt(v2.value) && parseInt(v2.resultreps) > parseInt(v2.reps)) || (parseInt(v2.resultweight) > parseInt(v2.value) && parseInt(v2.resultreps) >= parseInt(v2.reps))){
                       var repscheck = '<i class="fa fa-exclamation" style="color:green"></i>';      
                      }else{
                       var repscheck = '<i class="fa fa-times" style="color:red"></i>';            
                      }
    
    
                      // console.log((v2.resultweight >= v2.value && v2.resultreps > v2.reps) || (v2.resultweight > v2.value && v2.resultreps >= v2.reps));
    
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
                        result += '<td>Set '+ setCount +': '+res_d+repscheck+'<span style="float:right"><a href="javascript:void(0)" onclick="return change_sets('+v2.id+','+res.currentWorkout.id+')"><i class="fa fa-edit"></i></a>&nbsp;</span></td>';
                      result += '</tr>';
                      setCount++;
                    });
                   print += '</tbody></table></div>';
                    result += '</tbody>';
                  result += '</table>';

              });

            $('#ajaxcontent').html(print);
            div.html(result);
            overflow_control();

      var chartuniversalarray = [];
      chartuniversalarray.push( ['Exercise','Sets'] );

       $.each(chart_array,function(key10,value10){
           $.each(value10,function(key20,value20){
            var e_name = value20.name;
            var set_resultweight = value20.resultweight;
            // var set_resultreps = value20.name;
            chartuniversalarray.push( [ (e_name) , parseInt(set_resultweight) ] );

           });

       });

      // barchart(chartuniversalarray);
      
      // corechart(chartuniversalarray);
          /*chart work starts*/

        }
      });
    }
</script>


  <script type="text/javascript">
      google.load("visualization", "1.1", {packages:["bar"]});
      function barchart(chartuniversalarray) {

     var data = google.visualization.arrayToDataTable(chartuniversalarray);
        var options = {
          // chart: {
          //   title: 'Exercise Sets chart'
          // },
          hAxis: {
            title: 'Exercises'
          },
          vAxis: {
            title: 'Weights'
          },
          bars: 'vertical' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('barchart'));

        chart.draw(data, options);
      }
      
      google.load("visualization", "1.1", {packages:["corechart"]});
      function corechart(chartuniversalarray) {
  
     var data = google.visualization.arrayToDataTable(chartuniversalarray);

        var options = {
          // curveType: 'function',
          pointSize: 3,
          hAxis: {
            title: 'Exercises'
          },
          vAxis: {
            title: 'Weights'
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('corechart'));
        chart.draw(data, options);
    }


    </script>


    <?php  if ($workout): ?>
<div id="print_me<?php echo $workout['workout_id'] ?>">
    <style>
        div#print_me<?php echo $workout['workout_id'] ?> { display: none; }
        @media print{
        div#print_me<?php echo $workout['workout_id'] ?> { display: block; }
        .print-break {page-break-after:always;}
        }
    </style>
    <?php //$workout = get_workout_full_detail($workout['workout_id']); ?>
    <h3 style="text-align:center;"><?php echo ucfirst($workout['name']); ?></h3>
    <div class="form-group">
        <img src="<?php echo BUCKET_PATH.$workout['image'] ?>" alt="Workout Image" style="max-width:80%;">
    </div>
    <div class="form-group">
        <label for="">Date - <?php echo $workout['date'] ?></label>                                                       
    </div>
    <div class="form-group">
        <label for="">Time - <?php echo $workout['time'] ?></label>                                                       
    </div>
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
    <div class="form-group">
        <img src="<?php echo BUCKET_PATH.$row['image'] ?>" alt="Exercise Image" style="max-width:80%;">
    </div>
    <div class="form-group">
        <label for="">Description - <?php echo ucfirst($row['description']); ?></label>                                                       
    </div>
    <div class="form-group">
        <label for="">Rest time between sets : <?php echo $row['resttime']; ?> minute(s)</label>
    </div>
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
    .fc-event-time, .fc-event-title:hover{
    cursor: pointer;
    background-color: rgb(66, 80, 83) !important;
    }
    .fc-today{
    background-color: #5190ab;
    }
    .fc-today .fc-day-number, .fc-today .fc-day-content{
    color: #fff;
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

<script>
  function create_bar_graph(){
    var wid = $("#workout_id_for_graph").val();
    var trainee_id = '<?php echo $trainee_id; ?>';
    var graph_sort_by = $("#graph_sort_by").val();

    $("#barchart").html('');
    $("#corechart").html('');

    if(wid == '0'){
      return 0;
    }
    $.ajax({
      url: '<?php echo base_url() ?>trainee/create_bar_graph',
      type: 'POST',
      data: {
        'workout_id' : wid,
        'trainee_id' : trainee_id,
        'graph_sort_by' : graph_sort_by
      }
    }).done(function( response ) {
      console.log(response);

      if(response == '0'){
        return 0;
      }

      var res = JSON.parse(response);
      var bar_chart_data = [];
      bar_chart_data.push( ['Exercise','Result-Weight', 'Given-Weight'] );

      $.each(res,function(key20,value20){
        var ename = value20.ename;
        var rval = value20.rval;
        var oval = value20.oval;

        bar_chart_data.push( [ (ename) , parseInt(rval) , parseInt(oval) ] );
      });
      barchart(bar_chart_data);
      corechart(bar_chart_data);
    });
  }
</script>
<input type="hidden" id="workout_id_for_graph" value="0">