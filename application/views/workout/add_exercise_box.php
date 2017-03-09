<!-- EXERCISE MODULE -->
<div class="form-group counting_box exercise_div">		
	<label class="ehead"> Exercise #<span class="ex_no"></span> <a href="javascript:void(0);" class="remove_exercise">Remove</a> </label>
	<br>
	<label for="exampleInputEmail1">Name</label> 
	<br>
	<input type="text" class="form-control exer_name" name="exercise[]" id="" value="<?php if($exercise){ echo $exercise->name; } ?>">
	<input type="hidden" class="form-control default_exer_id" name="default_exercise_id[]" id="" value="<?php if($exercise){ echo $exercise->id; } ?>">
	<span class="error exer_name_error"></span>
	<br>
	<label for="">Description</label>
	<br>
	<textarea class="form-control exer_desc" name="description[]" rows="5"><?php if($exercise){ echo $exercise->description; } ?></textarea>
	<br>
	<label for="exampleInputEmail1">Image</label> 
	<br>
	<input type="file" class="file_image_exercise" name="image_exercise[]" style="display:inline;">&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="remove_file" title="Remove Exercise Image" style="display:none;">Remove</a>
	<br>
	<br>
	<?php if ($galleryimages): ?>
        <a href="javascript:void(0)" class="showgallery_e">
			or Select Image From Library
			<input type="hidden" value="<?php if($exercise){ echo $exercise->image; } ?>" name="exerciseimg[]" class="exerciseimg">
        </a>              
        <?php if($exercise){ ?>
	        <?php if (!empty($exercise->image)): ?>
	        	<div class="showimage">
					<img src="<?php echo BUCKET_PATH.$exercise->image ?>" style="width:100px;">              
					<a href="javascript:void(0);" class="rem_img">Remove</a>
	        	</div>
	    	<?php else: ?>
				<div class="showimage" style="display:none"></div>
	    	<?php endif; ?>
	    	<?php }else{ ?>
				<div class="showimage" style="display:none"></div>
	    	<?php } ?>
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
	<div class="form-group sets">
        <label for="exampleInputEmail1">Set 1</label> 
        &nbsp;&nbsp; <!-- Time -->
		<select style="width:20%;display: inline-block;" class="form-control timeinput grey-box" name="time_1">
		  <option>N/A</option>
		  <option>Failure</option>
		  <?php for($i=1;$i<=600;$i++):  ?>
		    <option value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>
		  <?php endfor; ?>  
		</select>
		&nbsp;&nbsp; <!-- Weight -->
		<select style="width:20%;display: inline-block;" class="form-control setinput grey-box" name="set_1">
		  <option>N/A</option>
		  <option>Body Weight</option>
		  <?php for($i=1;$i<=1000;$i++):  ?>
		    <option value="<?php echo $i; ?>"><?php echo $i; ?> lbs</option>
		  <?php endfor; ?>  
		</select>
		&nbsp;&nbsp; <!-- Reps -->
		<select style="width:20%;display: inline-block;" class="form-control repsinput grey-box" name="reps_1">
		  <option>N/A</option>
		  <option>Failure</option>
		  <?php for($i=1;$i<=100;$i++):  ?>
		    <option><?php echo $i; ?></option>
		  <?php endfor; ?>  
		</select>
        <a class="remove_set" style="display:none;" href="javascript:void(0);" rel="1"> [-] </a>
        <br>
        <!-- <br> -->
        <a class="add_set" href="javascript:void(0);" rel="2"> Add Set </a>
    </div>
</div>
<!-- EXERCISE MODULE END -->