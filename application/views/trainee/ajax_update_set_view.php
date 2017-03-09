<?php  
	$time_result = 0;
	$weight_result = 0;
	$reps_result = 0;
	if($result){
		$time_result = $result->resulttime;
		$weight_result = $result->resultweight;
		$reps_result = $result->resultreps;
	}
?>

<form class="form-inline" id="setform" style="text-align:center">
    <input type="hidden" name="setid" value="<?php echo $set_id ?>">
    <input type="hidden" name="workoutid" value="<?php echo $workout_id ?>">

	<div class="row">
		Time<br>
		<select name='time' class="form-control" style='width:200px;'>
			<?php if($set->time == 'N/A'): ?>
				<option>N/A</option>
			<?php elseif($set->time == 'Failure'): ?>	
				<option>Failure</option>
			<?php else: ?>
				<?php for($i=1;$i<=600;$i++):  ?>
				<option value="<?php echo $i; ?>" <?php if($i == $time_result){ echo 'selected'; } ?> ><?php echo seconds_to_string($i); ?></option>
					<?php // if($i == $set->time){ break; } ?>
				<?php endfor; ?> 
			<?php endif; ?>	
		</select><br><br>
	</div>

	<div class="row">
		Weight<br> 
		<select name='weight' class="form-control" style='width:200px;'>
			<?php if($set->value == 'N/A'): ?>
				<option>N/A</option>
			<?php elseif($set->value == 'Body Weight'): ?>
				<option>Body Weight</option>
			<?php else: ?>
				<?php for($i=1;$i<=1000;$i++):  ?>
				<option value="<?php echo $i; ?>"  <?php if($i == $weight_result){ echo 'selected'; } ?> ><?php echo $i; ?> lbs</option>
					<?php // if($i == $set->value){ break; } ?>
				<?php endfor; ?>  
			<?php endif; ?>	
		</select><br><br>
	</div>

	<div class="row">
		Reps<br>
		<select name='reps' class="form-control" style='width:200px;'>
			<?php if($set->reps == 'N/A'): ?>
				<option>N/A</option>
			<?php elseif($set->reps == 'Failure'): ?>
				<option>Failure</option>
			<?php else: ?>
				<?php for($i=1;$i<=100;$i++):  ?>
				<option <?php if($i == $reps_result){ echo 'selected'; } ?> ><?php echo $i; ?></option>
					<?php // if($i == $set->reps){ break; } ?>
				<?php endfor; ?>  
			<?php endif; ?>	
		</select>
	</div>

</form>