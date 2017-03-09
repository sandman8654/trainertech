<!-- CIRCUIT -->
<div class="form-group counting_box circuit_div">		
	<label class="ehead"> Circuit #<span class="cr_no">1</span> <a class="remove_circuit" href="javascript:void(0);">Remove</a> </label>
	<br>
	<label for="exampleInputEmail1">Name</label> 
	<br>
	<input type="text" value="<?php echo $circuit['name']; ?>" id="" name="circuit[]" class="form-control circ_name">
	<span class="error circ_name_error"></span>
	<br>
	<label for="">Description</label>
	<br>
	<textarea rows="5" name="circuit_description[]" class="form-control circ_desc"><?php echo $circuit['description']; ?></textarea>
	<br>
	<?php if($circuit['exercises']){ ?>
    	<?php $exer_count = 1; foreach($circuit['exercises'] as $row){ ?>
			<div class="form-group circuit_exercise_div">
				<br>
				<label class="ehead"> <?php echo $row['exercise'] ?> <input type="hidden" class="circ_exe_name" name="circ_exe_name" value="<?php echo $row['exercise'] ?>"> <input type="hidden" class="circ_exe_def_id" name="circ_exe_def_id" value="<?php echo $row['default_exercise_id'] ?>"> <a class="remove_circuit_exercise" href="javascript:void(0);">Remove</a> </label>
				<br>
				<label for="">Description</label>
				<br>
				<textarea rows="5" name="circuit_exer_description[]" class="form-control circ_exe_desc"><?php echo $row['description'] ?></textarea>
				<br>
				<label for="exampleInputEmail1">Image</label> 
				<br>
				<input type="file" style="display:inline;" name="circ_image_exercise[]" class="file_image_exercise">&nbsp;&nbsp;&nbsp;<a style="display:none;" title="Remove Exercise Image" class="remove_file" href="javascript:void(0);">Remove</a>
				<br>
				<br>
				<?php if ($galleryimages): ?>
					<a class="showgallery_c" href="javascript:void(0)">
						or Select Image From Library
						<input type="hidden" class="circexerciseimg" name="circexerciseimg[]" value="<?php echo $row['image'] ?>">
					</a>             
					<?php if (!empty($row['image'])): ?>
		                <div class="showimage">
		                	<img src="<?php echo BUCKET_PATH.$row['image'] ?>" style="width:100px;">              
		                	<a href="javascript:void(0);" class="rem_img">Remove</a>
		                </div>
		            <?php else: ?>
		              <div class="showimage" style="display:none"></div>
		            <?php endif; ?>
				<?php endif; ?>
				<br>
				<br>
				<label>Sets</label>

				<div class="form-group">
					<label for="exampleInputEmail1" style="opacity:0;">Set 1</label> 
					&nbsp;&nbsp; <!-- Time -->
					<span style="width:20%;display: inline-block;">
					Time
					</span>
					&nbsp;&nbsp; <!-- Weight -->
					<span style="width:20%;display: inline-block; margin-left:0.5%;">
					&nbsp;&nbsp;  Weight  
					</span>
					&nbsp;&nbsp; <!-- Reps -->
					<span style="width:20%;display: inline-block; margin-left:1.5%;">
					&nbsp;&nbsp;  Reps 
					</span>
				</div>

				<?php $j = 1; if($row['sets']): foreach($row['sets'] as $var){ ?>
					<div class="form-group sets">
						<label for="exampleInputEmail1">Set <?php echo $j ?></label> 
						&nbsp;&nbsp; <!-- Time -->
						<select style="width:20%;display: inline-block;" class="form-control timeinput grey-box" name="time_1">
							<option>N/A</option>
							<option <?php if($var->time == 'Failure'){ echo 'selected'; } ?>>Failure</option>
							<?php for($i=1;$i<=600;$i++):  ?>
								<option <?php if($var->time == $i){ echo 'selected'; } ?>  value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>
							<?php endfor; ?>  
						</select>
						&nbsp;&nbsp; <!-- Weight -->
						<select style="width:20%;display: inline-block;" class="form-control setinput grey-box" name="set_1">
							<option>N/A</option>
							<option <?php if($var->value == 'Body Weight'){ echo 'selected'; } ?>>Body Weight</option>
							<?php for($i=1;$i<=1000;$i++):  ?>
								<option value="<?php echo $i; ?>" <?php if($var->value == $i){ echo 'selected'; } ?>  ><?php echo $i; ?> lbs</option>
							<?php endfor; ?>  
						</select>
						&nbsp;&nbsp; <!-- Reps -->
						<select style="width:20%;display: inline-block;" class="form-control repsinput grey-box" name="reps_1">
							<option>N/A</option>
							<option <?php if($var->reps == 'Failure'){ echo 'selected'; } ?>>Failure</option>
							<?php for($i=1;$i<=100;$i++):  ?>
								<option <?php if($var->reps == $i){ echo 'selected'; } ?> ><?php echo $i; ?></option>
							<?php endfor; ?>  
						</select>
						<a class="remove_set" <?php if($j==1) { echo 'style="display:none;"'; } ?> href="javascript:void(0);" rel="<?php echo $j ?>">Remove</a>
						<br>
						<a class="add_set" <?php if($j!=count($row['sets'])) { echo 'style="display:none;"'; } ?> href="javascript:void(0);" rel="<?php echo $j + 1 ; ?>"> Add Set </a>
					</div>
				<?php $j++; } endif; ?>	
			</div>
		<?php } ?>
	<?php } ?>
</div>
<!-- / CIRCUIT -->