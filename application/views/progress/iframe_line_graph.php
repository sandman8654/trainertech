<?php $color_array = array("#5190AB","#1B1B1B","#B0AE1A","#B284BE","#008080","#E32636","#C46210","#800000","#FF0000","#808000","#FFFF00","#008000","#EFDECD","#E52B50","#AB274F","#5D8AA8","#00308F","#72A0C1","#AF002A","#A4C639","#915C83","#3B444B","#9F8170","#00FF00","#7CB9E8"); ?>

<?php
  $line_through = array();
  foreach($trainees as $trainee):
    $line_through[$trainee->id] = TRUE;
  endforeach;
?>

<?php
if(FALSE){ // $sets

  // echo '<pre>';
  // print_r($sets);
  // print_r($nreps);
  // die();

foreach($nreps as $set){
  if( isset( $dummy_set[ $set['reps'] ] ) ){
    if( $set['value'] > $dummy_set[ $set['reps'] ] ){
      $dummy_set[ $set['reps'] ] = $set['value'];
    }
  }else{
    $dummy_set[$set['reps']] = $set['value'];
  }
}
}
?>


<!DOCTYPE HTML>
<html>
<head>

<script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/canvasjs/canvasjs.js"></script>

<link href="<?php echo base_url(); ?>assets/theme/assets/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/theme/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/theme/css/style.css" rel="stylesheet">

<script type="text/javascript">
window.onload = function () {
  var isCanvasSupported = !!document.createElement("canvas").getContext; 
  // You might want to remove this line if you are not planning on using it.

  CanvasJS.addColorSet("customizedColorSet",[
        <?php foreach($color_array as $color){ echo '"'.$color.'",'; } ?>           
    ]);

  var theme = {
      Chart: {
          colorSet: "customizedColorSet"
      },
      Title: {
          fontFamily: isCanvasSupported ? "Calibri, Optima, Candara, Verdana, Geneva, sans-serif" : "calibri",
          fontSize: 18,
          fontColor: "#000000",
          fontWeight: "bold",
          verticalAlign: "top",
          horizontalAlign: "left",
          margin: 10
      },
      Axis: {
          titleFontSize: 16,
          titleFontWeight: "bold",
          titleFontColor: "#000000",
          titleFontFamily: "Roboto",

          labelFontFamily: "Roboto",
          labelFontSize: 12,
          labelFontWeight: "bold",
          labelFontColor: "#000000",
          tickColor: "#ECF0F1",
          tickThickness: 1,
          gridThickness: 1,
          gridColor: "red",
          lineThickness: 2,
          lineColor: "#ddd"
      },
      Legend: {
        fontSize: 16,
          verticalAlign: "top",
          horizontalAlign: "right",
      },
      DataSeries: {
          //bevelEnabled: true,
          indexLabelFontColor: "grey",
          //indexLabelFontFamily: "Trebuchet MS, monospace, Courier New, Courier",
          indexLabelFontFamily: isCanvasSupported ? "Calibri, Optima, Candara, Verdana, Geneva, sans-serif" : "calibri",
          //indexLabelFontWeight: "bold",
          indexLabelFontSize: 18,
          //indexLabelLineColor: "lightgrey",
          indexLabelLineThickness: 1
      }
  };
  CanvasJS.addTheme('customizedTheme',theme); 

  var chart = new CanvasJS.Chart("chartContainer",
  {
    title:{
      text: "Exercise: <?php if($exercise_id){ echo ucwords($exercise_id->name); } ?>"
    },
    axisY:{
        title:"Weight",
          gridThickness: 0,
          titleFontColor: "#5190ab",
          minimum : 0, 
    },
    axisX:{
      // title:"Date",
          gridColor: "#ddd",
          gridThickness: 1,
          minimum: 1, 
          interval: 1,
          labelAngle: 135
    },
    zoomEnabled: true,
      theme: 'customizedTheme',
      backgroundColor: "#ECF0F1",
      data: [

        <?php if($trainees): ?>
        <?php $i = 0; ?>
        <?php foreach($trainees as $trainee): ?>
        {
        markerColor: "#FFFFFF",
        markerBorderColor: "<?php echo $color_array[$i]; ?>",
          click: function(e){
              get_workout_details(<?php echo $trainee->id; ?>,e.dataPoint.y,e.dataPoint.reps,e.dataPoint.workout_id,e.dataPoint.exercise_id,e.dataPoint.label);
          },
          type: "line",

          animationEnabled: true,
                // label : 'Set <?php echo $j; ?>', 
          dataPoints: 
          [

            <?php if($response){ ?>
              <?php $j = 1; ?>

              {
                  x: 0, 
                  y: 0,
                  label: 0,
                  toolTipContent : 'Origin',
                  markerSize: 1, 
                  markerBorderThickness: 1,
                  markerType: "cross",
                  markerColor: "<?php echo $color_array[$i]; ?>"
                },

              <?php foreach($response as $set): if($set[$trainee->id] == '0') { continue; } ?>

              {
                // toolTipContent : 'Set {x} : {y} x {reps} ',
                x: <?php echo $j; ?>, 
                y: <?php echo $set[$trainee->id]; ?>,
                tname: "<?php echo $set[$trainee->id.'_name']; ?>",
                reps: "<?php echo $set[$trainee->id.'_reps']; ?>",
                label: "<?php echo date('m/d/y', strtotime($set['date'])); ?>",
                workout_id: <?php echo $set[$trainee->id.'_wid']; ?>,
                exercise_id: <?php echo $set[$trainee->id.'_eid']; ?>,                
                <?php if($reps == $set[$trainee->id.'_reps']): ?> 
                  <?php $line_through[$trainee->id] = FALSE; ?>
                  toolTipContent : 'Name : {tname}<br>Date : {label}<br>Weight : {y} lbs<br>Reps : {reps}',
                  markerSize: 12, 
                  markerBorderThickness: 3
                <?php elseif( $set[$trainee->id.'_reps'] > 0 && $set[$trainee->id] > 0 ): ?>
                  <?php $line_through[$trainee->id] = FALSE; ?>
                  toolTipContent : 'Name : {tname}<br>Date : {label}<br>Weight : {y} lbs<br>Reps : {reps}',
                  markerSize: 8, 
                  markerBorderThickness: 2
                <?php else: ?>
                  toolTipContent : 'No Details',
                  markerSize: 1, 
                  markerBorderThickness: 1,

                  markerType: "cross",
                  markerColor: "<?php echo $color_array[$i]; ?>"
                
                <?php endif; ?>
              
              },
            <?php $j++; ?>

              <?php endforeach; ?>

            <?php } // $response ?>

            <?php if(FALSE): // $sets ?>
            <?php $j = 1; ?>
            <?php foreach($sets as $set): ?>
              {
                toolTipContent : 'Set {x} : {y} x {reps} ',
                x: <?php echo $j; ?>, 
                y: <?php echo $set[$trainee->id]; ?>,
                reps : <?php echo $nreps[$j-1][$trainee->id]; ?>,
                <?php if($reps == $nreps[$j-1]['reps'] && $dummy_set[$reps] == $nreps[$j-1]['value']): ?> 
                markerSize: 12, 
                markerBorderThickness: 3
              <?php else: ?>
                markerSize: 8, 
                markerBorderThickness: 2
              <?php endif; ?> 
              },
            <?php $j++; ?>
            <?php endforeach; ?>
            <?php endif; ?>
          ]
        },
        <?php $i++; ?>
        <?php endforeach; ?>
        <?php endif; ?>
      ]
  });
  chart.render();
}

$(document).ready(function(){
  setTimeout(function(){
    $('.canvasjs-chart-credit').hide();
  },500);
});
</script>
</head>
<body>
  <div class='col-md-12' style='margin-top:30px;'>
      <div class='col-md-12' id="chartContainer" style="height: 400px;"></div>
  </div>


<div class='col-md-12' style='margin-top:25px;'>
  <?php if($trainees): ?>
  <?php $i = 0; ?>
  <?php foreach($trainees as $trainee): ?>  
  <div class="col-md-4" id='trainee-box-<?php echo $trainee->id; ?>' style='display:none;' >
    <div class="panel">
      <!-- <div class="panel-heading f-p-h"  style="background-color:<?php echo $color_array[$i]; ?>; text-align:center; padding:4px;" >
        <?php echo ucwords($trainee->fname." ".$trainee->lname); ?>
      </div> -->
      <div class="panel-heading s-p-h" >
      </div>
      <div class="panel-body f-p-b">
        <h4>Workout details not found.</h4>
      </div>
    </div>  
    <span class='second-panel'>
    </span>
  </div>
  <?php $i++; ?>
  <?php endforeach; ?>  
  <?php endif; ?>
</div>

<script>
function get_workout_details(trainee_id, weight, reps, workout_id, exercise_id, label){
  // alert( 'Trainer - ID = ' + trainee_id + '\nWeight = ' + weight + '\nReps = ' + reps + '\nWID = ' + workout_id + '\nEID = ' + exercise_id );
  if( trainee_id == '0' || weight == '0' || reps == '0' || workout_id == '0' || exercise_id == '0' ){
    alert('No workout details.');
    return 0;
  }

  var x = '1'; // NO USE
  var y = '10'; // NO USE
  var workOutId = workout_id;
  var ex_id = exercise_id;
  var date = label;

  $.ajax({
      type: 'POST',
      url: '<?php echo base_url() ?>progress/ajax_workout_details_for_dashboard_graph',
      data: {
        workOutId : workOutId,
        date : date,
        trainee_id : trainee_id
      },
      success: function(response){
            var res = JSON.parse(response);
            if(res.status){
  
              // show panel subheader
              if (res.currentWorkout.status == "1") {      
                var right = '<img class="pull-right" src="<?php echo base_url() ?>assets/theme/img/gl.png">';      
              }else{
                var right = '<img class="pull-right" src="<?php echo base_url() ?>assets/theme/img/cross.png">';            
              }
              $('#trainee-box-'+trainee_id+' .s-p-h').html(res.dateToshow+right);
          
              // show panel body
        result = '<h4>Trainer Notes</h4>';
        result += '<p>'+ res.currentWorkout.description +'</p>';
        result += '<h4>Trainee Comments</h4>';
        if(res.trainee_workout_notes.notes){
          result += '<p>'+res.trainee_workout_notes.notes+'</p>';
        }else{
          result += '<p>No Comments Found.</p>';
        }
              $('#trainee-box-'+trainee_id+' .f-p-b').html(result);

              // show second full panel
        $.each(res.details, function(index, v1) {
          if(ex_id == v1.exercise_id ){
            result = '';
            result += '<h4>'+v1.exercise+' Results</h4>';
            if(v1.trainee_notes.notes){
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
                var flag = false;
                
                if( v2.resulttime != "0" && v2.resultweight != "0" && v2.resultreps!= "0"){
                  
                  var rTime = '';
                  if(v2.resulttime != 'N/A' && v2.resulttime != 'Failure'){
                      rTime = sec_to_min(v2.resulttime)+'x';
                  }
                  else{
                      rTime = v2.resulttime+'x';
                  }

                  var res_d = rTime + v2.resultweight +'x'+ v2.resultreps+' ';

                  if(v2.resultweight == weight && v2.resultreps == reps){
                    flag = true;
                  }

                }else{
                  var res_d = "- ";
                }

                var cOTime = '0';
                var cRTime = '0';

                if(v2.time != 'N/A' && v2.time != 'Failure'){
                    cOTime = v2.time;
                }

                if(v2.resulttime != 'N/A' && v2.resulttime != 'Failure'){
                    cRTime = v2.resulttime;
                }

                if (cOTime == cRTime && v2.value == v2.resultweight && v2.reps == v2.resultreps ) {      
                    var repscheck = '<i class="fa fa-check" style="color:green"></i>';      
                }else if(( parseInt(v2.resultreps) >= parseInt(v2.reps) && parseInt(v2.resultweight) >= parseInt(v2.value) && parseInt(cRTime) >= parseInt(cOTime) )){
                    var repscheck = '<i class="fa fa-exclamation" style="color:green"></i>';      
                }else{
                    var repscheck = '<i class="fa fa-times" style="color:red"></i>';            
                }

                var oTime = '';
                if(v2.time != 'N/A' && v2.time != 'Failure'){
                    oTime = sec_to_min(v2.time)+'x';
                }
                else{
                    oTime = v2.time+'x';
                }

                result += '<tr>';
                  if(flag){ // setCount == x
                    result += '<td style="color:#51A1DB;" >Set '+ setCount +': '+ oTime + v2.value +'x'+ v2.reps +'</td>';
                    result += '<td style="color:#51A1DB;" >Set '+ setCount +': '+res_d+repscheck+'<span style="float:right">&nbsp;</span></td>';
                  }else{
                    result += '<td>Set '+ setCount +': '+ oTime + v2.value +'x'+ v2.reps +'</td>';
                    result += '<td>Set '+ setCount +': '+res_d+repscheck+'<span style="float:right">&nbsp;</span></td>';
                  }                
                result += '</tr>';
                setCount++;
              });
              result += '</tbody>';
            result += '</table>';
            }
        });
          $('#trainee-box-'+trainee_id+' .second-panel').html(result);
      }   
        $('#trainee-box-'+trainee_id).show();
      } 
    });
}

function sec_to_min(seconds){
  seconds = parseInt(seconds);
  
  if(seconds == NaN)
      seconds = 0;

  if(seconds <= 59){
      return seconds + 's';
  }
  else if( seconds > 59 ){
      var min = parseInt(seconds / 60);
      var sec = seconds % 60;

      var res = '';
      res += min;
      res += 'm';
      
      if(sec > 0){
          res += sec;
          res += 's';
      }
      return res;
  }
  else{
      return seconds;
  }
}

function handlePointClick(trainee_id,x,y){
  /*
  var workOutId = <?php echo $workout->id; ?>;
  var ex_id = <?php echo $exercise_id->id; ?>;
  var date = new Date();
    $.ajax({
      type: 'POST',
      url: '<?php echo base_url() ?>trainee/ajaxWorkoutDetails',
      data: {
        workOutId : workOutId,
          date : date,
          trainee_id : trainee_id
      },
      success: function(response){
            var res = JSON.parse(response);
            if(res.status){
  
              // show panel subheader
              if (res.currentWorkout.status == "1") {      
                var right = '<img class="pull-right" src="<?php echo base_url() ?>assets/theme/img/gl.png">';      
              }else{
                var right = '<img class="pull-right" src="<?php echo base_url() ?>assets/theme/img/cross.png">';            
              }
              $('#trainee-box-'+trainee_id+' .s-p-h').html(res.dateToshow+right);
          
              // show panel body
        result = '<h4>Trainer Notes</h4>';
        result += '<p>'+ res.currentWorkout.description +'</p>';
        result += '<h4>Trainee Comments</h4>';
        if(res.trainee_workout_notes.notes){
          result += '<p>'+res.trainee_workout_notes.notes+'</p>';
        }else{
          result += '<p>No Comments Found.</p>';
        }
              $('#trainee-box-'+trainee_id+' .f-p-b').html(result);

              // show second full panel
        $.each(res.details, function(index, v1) {
          if(ex_id == v1.exercise_id ){
            result = '';
            result += '<h5>'+v1.exercise+' Results</h5>';
            if(v1.trainee_notes.notes){
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

                result += '<tr>';
                  if(setCount == x){
                    result += '<td style="color:#51A1DB;" >Set '+ setCount +': '+ v2.value +'x'+ v2.reps +'</td>';
                    result += '<td style="color:#51A1DB;" >Set '+ setCount +': '+res_d+repscheck+'<span style="float:right">&nbsp;</span></td>';
                  }else{
                    result += '<td>Set '+ setCount +': '+ v2.value +'x'+ v2.reps +'</td>';
                    result += '<td>Set '+ setCount +': '+res_d+repscheck+'<span style="float:right">&nbsp;</span></td>';
                  }                
                result += '</tr>';
                setCount++;
              });
              result += '</tbody>';
            result += '</table>';
            }
        });
          $('#trainee-box-'+trainee_id+' .second-panel').html(result);
      }   
        $('#trainee-box-'+trainee_id).show();
      } 
    });
  */
}
</script>

</body>
</html>

<?php //var_dump($trainees); ?>
<?php //var_dump($wid); ?>
<?php //var_dump($workout); ?>
<?php //var_dump($eid); ?>
<?php //var_dump($exercise_id); ?>
<?php //var_dump($sets); ?>