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
  	var theme = {
      Axis: {
          titleFontSize: 14,
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
  };
  CanvasJS.addTheme('customizedTheme',theme); 



  	var chart = new CanvasJS.Chart("chartContainer",{
		axisY:{
			title:"Weight",
		  	gridThickness: 0,
		  	minimum : 0,
		  	titleFontColor: "#5190ab",
		  	fontFamily: "Roboto"
		},
		axisX:{
			interval : 1,
			labelAngle: 135,
			minimum : 0,
		},


		zoomEnabled: true,
		theme: 'customizedTheme',
		backgroundColor: "#ECF0F1",
		data: [{
          	click: function(e){
				//alert(e.dataPoint.x,e.dataPoint.y);
          	},
			dataPoints: 
			[
				<?php if($result): ?>
					<?php $i = 1; ?>
					<?php foreach($result as $row): ?>
						<?php if($row['rval'] > 0): ?>
							{
								indexLabel: "<?php echo $row['ename']; ?>",
					    	    indexLabelOrientation: "vertical",
					    	    indexLabelFontColor: '#000',
					    	    indexLabelFontWeight: 'bold',

					   			ename: "<?php echo $row['ename']; ?>",

					    	    label : "<?php echo date('m/d/y', strtotime($row['date'])); ?>",

					    	    toolTipContent : 'Date : {label}<br>Exercise Name : {ename}<br>Weight : {y} lbs',
								
								x: <?php echo $i; ?>, 
								y: <?php echo $row['rval']; ?>
							},
							<?php $i++; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			]
		}]
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
	<div id="chartContainer" style="height:400px; width:100%;" ></div>
</body>
</html>