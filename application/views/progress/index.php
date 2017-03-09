<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.ru.js"></script>

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

<style>
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
.my-input-css{
	background: #e5e9ea;
	margin-right: 15px;
	border: 0;
	border-radius: 3px;
	box-shadow: none;
	height: 40px;
}
.my-input-css:hover{
	cursor: pointer;
}
#iframe-line-graph{
	width: 100%;
	border: none;
	height: 1300px;
	margin-top: 30px;
}
#iframe-bar-graph{
	width: 100%;
	border: none;
	height: 420px;
	margin-top: 30px;
}
</style>


<h4>Overall Progress</h4>
<div class="row">
  	<div class="col-md-3">
	    <label>Select Number of Reps</label>
	    <select onchange='create_bar_graph();' name="barreps" class="form-control my-select-css">
	        <option value='0'>Select Reps</option>
	        <?php $selected_first = TRUE; if($reps): ?>
		        <?php foreach( $reps as $rep  ): ?>
		            <option <?php if($selected_first){ echo 'selected="selected"'; $selected_first = FALSE; } ?>><?php echo $rep; ?></option>
		        <?php endforeach; ?>
	        <?php endif; ?>
	    </select>
  	</div>
	<div class="col-md-2">
	    <label>Select Timeline</label>
	    <select onchange='create_bar_graph();' name='bartimeline' class="form-control my-select-css">
	      	<option value="0">Current</option>
          	<option value="1">Average</option>
          	<option value="2">Highest</option>
	    </select>
  	</div>
	<div class="col-md-2">
	    <label>From</label>
	    <input onchange='create_bar_graph();' name='barfrom' value="<?php echo date('m/d/Y', strtotime('- 1 month') );  ?>" class="form-control my-input-css" readonly='readonly' >
  	</div>
	<div class="col-md-2">
	    <label>To</label>
	    <input onchange='create_bar_graph();' name='barto' value="<?php echo date('m/d/Y', strtotime('+ 1 month') );  ?>" class="form-control my-input-css" readonly='readonly' >
  	</div>
</div>


<iframe id="iframe-bar-graph" src=""></iframe>
<script>
onload = create_bar_graph();
function create_bar_graph(){
	var timeline = $('[name=bartimeline]').val();
	var reps = $('[name=barreps]').val();
	var from = $('[name=barfrom]').val().replace('/','-').replace('/','-');
	var to = $('[name=barto]').val().replace('/','-').replace('/','-');
  	date_validation(from,to);
  	$("#iframe-bar-graph").attr('src', '<?php echo base_url() ?>progress/create_bar_graph/'+timeline+'/'+reps+'/'+from+'/'+to);
}
</script>

<?php /**
				LINE GRAPH
*/ ?>
<h4>Exercise Progress</h4>
<div class="row">
  	<div class="col-md-4">
    	<label>Select Exercise</label>
	    <select onchange='create_line_graph(1);' name="lineexercise" class="form-control my-select-css">
	      	<option value=''>Select Exercise</option>
	   		<?php if($exercises): ?>
		   		<?php foreach($exercises as $exercise): ?>
			      	<option value='<?php echo $exercise->id; ?>'><?php echo $exercise->name; ?></option>
		   		<?php endforeach; ?>
	   		<?php endif; ?>	
	    </select>
  	</div>
  	<div class="col-md-3">
	    <label>Select Number of Reps</label>
	    <select onchange='create_line_graph(0);' name="linereps" class="form-control my-select-css">
	        <option value="">Select Reps</option>
	    </select>
  	</div>
	<div class="col-md-2">
	    <label>From</label>
	    <input onchange='create_line_graph(0);' name='linefrom' class="form-control my-input-css" readonly='readonly' >
  	</div>
	<div class="col-md-2">
	    <label>To</label>
	    <input onchange='create_line_graph(0);' name='lineto' class="form-control my-input-css" readonly='readonly' >
  	</div>


</div>
<iframe id="iframe-line-graph" src=""></iframe>
<script>
function create_line_graph(flag){
	var exercise = $('[name=lineexercise]').val();
	if(exercise == ''){
		return;
	}

	var reps = $('[name=linereps]').val();
	if(!reps){
		reps = 99999;
	}

	var from = $('[name=linefrom]').val().replace('/','-').replace('/','-');
	if(!from){
		from = '01-01-2000';
	}

	var to = $('[name=lineto]').val().replace('/','-').replace('/','-');
	if(!to){
		to = '01-01-2030';
	}

	date_validation(from,to);
	
	if(flag == 1){
		$("#iframe-line-graph").attr('src', '');
		$.ajax({
			url : '<?php echo base_url(); ?>progress/get_reps_by_exercise/'+exercise, 
			success : function(res){
			  	$('[name=linereps]').html(res);
			}
		});
	}
	else if(flag == 0 && reps != 99999){
		$("#iframe-line-graph").attr('src', '<?php echo base_url() ?>progress/create_line_graph/'+exercise+'/'+reps+'/'+from+'/'+to);
		$("#iframe-line-graph").show();
	}
}
</script>



<script type="text/javascript">
$(function () {
    $('[name=barfrom]').datetimepicker({
		pickTime: false,
    });
    $('[name=barto]').datetimepicker({
		pickTime: false,
    });
    $('[name=linefrom]').datetimepicker({
		pickTime: false,
    });
    $('[name=lineto]').datetimepicker({
		pickTime: false,
    });
});
</script>