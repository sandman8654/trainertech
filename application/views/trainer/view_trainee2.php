
                        <!--fullcalendar-->
    <script src="<?php echo base_url() ?>assets/theme/assets/fullcalendar/fullcalendar.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/theme/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>

          <!--morris chart-->
    <script src="<?php echo base_url() ?>assets/theme/js/jquery.sparkline.js"></script>
    <script src="<?php echo base_url() ?>assets/theme/assets/morris/morris.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/theme/assets/morris/raphael-min.js" type="text/javascript"></script>

      <!--flot chart-->
    <script language="javascript" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.flot.categories.js"></script>
    <script src="<?php echo base_url() ?>assets/theme/js/dash-flot-set.js"></script>


         <!--page script-->
    <script class="include" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/script.js"></script>
    <script src="<?php echo base_url() ?>assets/theme/js/morris-script.js"></script>
  
  


        <header>
          <span><img src="<?php echo base_url() ?>assets/theme/img/user.png">Welcome To Admin Panel</span>
        </header>      
        <div class="row" style="">
          <div class="col-lg-4" id="workOutDetails">
            <div class="panel">
              <div class="panel-heading">9/16/14 - Chest <img src="<?php echo base_url() ?>assets/theme/img/gl.png"></div>
              <div class="panel-body">
                <h4>Trainer Notes</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <h4>Trainee Comments</h4>
                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
              </div>
            </div>
            <h4>Flat Barbell Bench Press</h4>
            <table class="table table-striped home-table">
              <thead>
                <tr>
                  <th style="width:70%;">Instructions</th>
                  <th>Results</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Set 1: 15x135</td>
                  <td>Set 1: 15x135 <input type="checkbox" class="ok"></td>
                </tr>    
                <tr>
                  <td>Set 1: 15x135</td>
                  <td>Set 1: 15x135 <input type="checkbox" class="ok"></td>
                </tr>   
                <tr>
                  <td>Set 1: 15x135</td>
                  <td>Set 1: 15x135 <input type="checkbox" class="ok"></td>
                </tr>   
                <tr>
                  <td>Set 1: 15x135</td>
                  <td>Set 1: 15x135 <input type="checkbox" class="ok"></td>
                </tr>               
              </tbody>
            </table>
            <h4>Incline Dumbell Bench Press</h4>
            <table class="table table-striped home-table">
              <thead>
                <tr>
                  <th style="width:70%;">Instructions</th>
                  <th>Results</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Set 1: 15x135</td>
                  <td>Set 1: 15x135 <input type="checkbox" class="ok"></td>
                </tr>    
                <tr>
                  <td>Set 1: 15x135</td>
                  <td>Set 1: 15x135 <input type="checkbox" class="ok"></td>
                </tr>   
                <tr>
                  <td>Set 1: 15x135</td>
                  <td>Set 1: 15x135 <input type="checkbox" class="ok"></td>
                </tr>   
                <tr>
                  <td>Set 1: 15x135</td>
                  <td>Set 1: 15x135 <input type="checkbox" class="ok"></td>
                </tr>               
              </tbody>
            </table>
          </div>
          <div class="col-lg-8">
            <div id='calendarview'></div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-10"><div id="hero-bar" class="graph"></div></div>
          <div class="col-md-2">
            <p>Reps</p>
            <select class="st-select">
              <option>1</option>
            </select>
            <p>Timeline</p>
            <select class="st-select">
              <option>Current</option>
            </select>
          </div>
        </div>

        <div class="row flot-chart">
          <div class="col-md-10">
            <div class="demo-container">
              <div id="placeholder" class="demo-placeholder"></div>
            </div>
          </div>
          <div class="col-md-2">
            <p>Barbell Bench</p>
            <select class="st-select">
              <option>Current</option>
            </select>
            <p>Reps</p>
            <select class="st-select">
              <option>1</option>
            </select>
            <p>Timeline</p>
            <div class="input-group date form_datetime-component">
              <input type="text" class="form-control" readonly="" size="16">
              <span class="input-group-btn">
                <button type="button" class="btn date-set"><i class="fa fa-calendar"></i></button>
              </span>
            </div>
            <div class="input-group date form_datetime-component">
              <input type="text" class="form-control" readonly="" size="16">
              <span class="input-group-btn">
                <button type="button" class="btn date-set"><i class="fa fa-calendar"></i></button>
              </span>
            </div>
          </div>
        </div>
<script>
$(document).ready(function(){
var x = '<?php echo trainee_workout($trainee_id); ?>';
  // alert(x);
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
            console.log('Event: ' + calEvent.className);
            $.ajax({
              url: '<?php echo base_url() ?>trainer/ajaxWorkoutDetails/'+calEvent.className,
              type: 'POST'
            }).done(function( response ) {
              console.log(response);
            });
        }
    }); 
});
</script>