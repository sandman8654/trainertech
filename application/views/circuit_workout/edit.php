<?php
	if(strtotime($workout['date']) < time()){
		$minDate = strtotime($workout['date']);
	}
	else{
		$minDate = $mdate;
	}
?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.ru.js"></script>
<script type="text/javascript">
	$(function () {
		$('#datepicker').datetimepicker({
			pickTime: false,
			minDate: moment("<?php echo date('m/d/Y', $minDate); ?>")
		});
		$('#timepicker').datetimepicker({
			pickDate: false
		});
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
				source: availableTags,
			});
		});
	});
</script>
<?php echo alert(); ?>          
<h3>Edit Circuit Workout</h3>
<?php echo form_open_multipart(cms_current_url(), array('id' => 'contentForm', 'class' => 'tasi-form')); ?>
<div class="form-group">
	<label for="exampleInputEmail1">Create work out for</label> 
	<?php if($trainee) :  ?>
	<div class="main_div">
		<div class="my_check">
		<?php $i=1;  foreach($trainee as $row): ?>
			<div class="checkbox">
				<label>
					<input name="trainee_id[]" <?php if( in_array($row->id, $trainee_workout)) { echo 'checked="checked"'; } ?> value="<?php echo $row->id ?>" type="checkbox" value=""><?php echo $row->fname." ".$row->lname ?>
				</label>
			</div>
			<?php if($i%6==0): ?>
				</div>
				<div class="my_check">
			<?php endif; ?>
		<?php $i++; endforeach;endif; ?>
		</div>
	</div>
	<span class="error"><?php echo form_error('trainee_id'); ?></span>
</div>

<script type="text/javascript">
	$(document).on('click','.parent_gr_div input[type=checkbox]',function(){
		is_checked = $(this).prop('checked');
		if(is_checked){
			$(this).parents('.gr_div').find('.child_gr_div input[type=checkbox]').prop('checked',true);
		}
		else{
			$(this).parents('.gr_div').find('.child_gr_div input[type=checkbox]').prop('checked',false);
		}
	}); 
		
	$(document).on('click','.child_gr_div input[type=checkbox]',function(){
		flag = 0;
		$(this).parents('.gr_div').find('.child_gr_div input[type=checkbox]').each(function(){
			is_check = $(this).prop('checked');
			if(!is_check){
				flag = 1;
			} 
		});
		
		if(flag==1){
			$(this).parents('.gr_div').find('.parent_gr_div input[type=checkbox]').prop('checked',false);
		}
		else{
			$(this).parents('.gr_div').find('.parent_gr_div input[type=checkbox]').prop('checked',true);
		}
	});
		
	$(document).ready(function(){
		$('.gr_div').each(function(){
			flag = 0;
			$(this).find('.child_gr_div input[type=checkbox]').each(function(){
				is_check = $(this).prop('checked');
				if(!is_check){
					flag = 1;
				} 
			});
			
			if(flag==1){
				$(this).find('.parent_gr_div input[type=checkbox]').prop('checked',false);
			}
			else{
				$(this).find('.parent_gr_div input[type=checkbox]').prop('checked',true);
			}
		});
	});
</script>

<?php if ($group): ?>  
<a id="add_group" style="display:none" href="javascript:void(0);" > Add  Group </a>
<div class='input-group'  id="group_box">
	<label for="exampleInputEmail1">Groups</label> 
	<!-- Groups starts -->
	<?php foreach ($group as $group): ?>
	<?php $subgroup = get_result('group',array('parent_id'=>$group->id)) ?>
	<?php if($subgroup): ?>
	<div class="gr_div">
		<div class="checkbox parent_gr_div" >
			<label><input  type="checkbox" value=""><?php echo $group->name ?></label>
		</div>
		<?php foreach ($subgroup as $subgroup): ?>
		<div class="checkbox child_gr_div">
			<label><input <?php if(in_array($subgroup->id,$group_workout)) echo "checked" ?> name="group_id[]" value="<?php echo $subgroup->id ?>" type="checkbox" value=""><?php echo $subgroup->name ?></label>
		</div>
		<?php endforeach ?>
	</div>
	<?php else: ?>     
	<div class="checkbox">
		<label><input <?php if(in_array($group->id,$group_workout)) echo "checked" ?> name="group_id[]" value="<?php echo $group->id ?>" type="checkbox" value=""><?php echo $group->name ?></label>
	</div>
	<?php endif; ?>
	<?php endforeach ?>
	<!-- Groups ends -->
</div>
<a id="remove_group" href="javascript:void(0);"  > Remove Group </a>
<?php endif; ?>

<div class='input-group date'>
	<label for="exampleInputEmail1">Date</label> 
	<input type='text' class="form-control" name="date_of_workout" value="<?php echo $workout['date'] ?>" id='datepicker'/>
	<span class="error"><?php echo form_error('date_of_workout'); ?></span>
</div>

<div class='input-group date'>
	<label for="exampleInputEmail1">Time</label> 
	<select name="anytime" class="form-control" id="choose_time">
			<option value="1">Any-time</option>
			<option value="0" <?php if($workout['anytime'] == '0') { echo 'selected="selected"'; } ?>>Set-time</option>
	</select>
</div>

<div class="form-group" id="set_time" style="display:none;">
	<label for="exampleInputEmail1">Set Time</label> 
	<input type='text' class="form-control" name="time_of_workout" value="<?php echo $workout['time'] ?>" id='timepicker'/>
	<span class="error"><?php echo form_error('time_of_workout'); ?></span>
</div>

<div class="form-group">
	<label for="exampleInputEmail1">Name of Workout</label> 
	<input type="text" class="form-control" name="name_of_workout" id="name_of_workout" value="<?php echo $workout['name'] ?>">
	<span class="error"><?php echo form_error('name_of_workout'); ?></span>
</div>

<div class="form-group">
	<label for="exampleInputEmail1">Description of Workout</label> 
	<textarea class="form-control" rows="5" id="desc_of_workout" name="desc_of_workout"><?php echo $workout['description'] ?></textarea>
	<span class="error"><?php echo form_error('desc_of_workout'); ?></span>
</div>

<div class="form-group">
	<label for="exampleInputEmail1">Image of Workout</label>
	<br> 
	<?php if(!empty($work_image_info->image)): ?>
	<img style="width:100px;height:100px" src="<?php echo BUCKET_PATH ?><?php echo $work_image_info->image ?>">
	<br> 
	<br> 
	<?php endif; ?>
	<input type="file" name="image_workout" value="">(Size 640*300)<br><b>OR</b><br>
	<a href="javascript:void(0)" class="showgallery_w">Select Image <input type="hidden" value="<?php echo $work_image_info->image ?>" name="wrkoutimg" id="wrkoutimg"> </a>
	<span class="error"><?php echo form_error('image_workout'); ?></span>
</div>

<div class="form-group type-div-class" style="display:none;">
	<label for="exampleInputEmail1">Type : 
		Circuit Exercise
	</label>
	<select onchange='change_type();' class="form-control hide" name='type' style='width:15%;'>
		<!-- <option value='1' <?php if($workout['type'] == 1){ echo 'selected'; } ?> >Normal Exercise</option> -->
		<option value='2'>Circuit Exercise</option>
	</select>
	<br>
</div>

<!-- ########################################### -->

<div class="circuit_parent_div">
	<?php if(count($workout['circuits_data'] > 0)){ ?>
		<?php $circuitt_remove = FALSE; foreach($workout['circuits_data'] as $c_row){ ?>
			<div class="exercise_parent_div">
				
				<div class="exercise_div">
					<div class="form-group circ">
						<label for="exampleInputEmail1">Circuit</label>
						<?php if($circuitt_remove){ ?>
							<a class="aj_remove_circuit" data-cid="<?php echo $c_row['id'] ?>" href="javascript:void(0)">[ remove ]</a>
						<?php } $circuitt_remove = TRUE; ?>

						<input  placeholder="Circuit Name" type="text" class="form-control" name="circuit<?php echo $c_row['id'];  ?>" value="<?php echo $c_row['name'] ?>">
						<!-- <input  type="hidden" class="form-control circuit_e_count" name="circuit_e_count[]" value="<?php echo $c_row['count'] ?>"> -->
						<br>
						<hideshowtag>
							<textarea placeholder="Circuit Description" type="text" class="form-control" name="circuit_description<?php echo $c_row['id'];  ?>"><?php echo $c_row['description'] ?></textarea>
							<br>
							Circuit Image
							<?php if(!empty($c_row['image'])): ?>
							<br> 
							<img style="width:100px;height:100px" src="<?php echo BUCKET_PATH ?><?php echo $c_row['image'] ?>">
							<br> 
							<?php endif; ?>

							<br>
							<input type="file"  name="image_circuit<?php echo $c_row['id'];  ?>" value="">(Size 640*300)
							<br><b>OR</b><br>
							<a href="javascript:void(0)" class="showgallery_e">Select Image <input type="hidden" value="<?php echo $c_row['image'] ?>" name="circuitimg<?php echo $c_row['id'];  ?>" class="circuitimg"></a>
							<div class="showimage" style="display:none"></div>
							<div class="loader" style="display:none"><img src="<?php echo base_url() ?>assets/theme/img/loading.gif" style="width:50px"></div>
							<br>                    
							<br>
						</hideshowtag>
					</div>
					
					<?php $bar = FALSE ; $exercise_remove = FALSE; foreach($c_row['exercises'] as $ex_arr){ ?>
					<?php if($bar){ break; } ?>
						<div class="form-group exer">
							<label for="exampleInputEmail1">Exercise</label> 
							<?php if($exercise_remove){ ?>
								<a class="aj_remove_exercise" data-eid="<?php echo $ex_arr['exercise_id'] ?>" href="javascript:void(0)">[ remove ]</a>
							<?php } $exercise_remove = TRUE; ?>
							<input  placeholder="Excercise" type="text" class="form-control defaul_exercise" name="exercise<?php echo $ex_arr['exercise_id'] ?>" value="<?php echo $ex_arr['exercise'] ?>">
							<span class="error"></span>
						</div>
						
						<?php $set_remove = FALSE; $j = 1; foreach($ex_arr['sets'] as $set_arr){ ?>
						<div class="form-group sets0">
							<label for="exampleInputEmail1">Set <?php echo $j ?></label> 
							&nbsp;&nbsp; Time
							<select style="width:20%;display: inline-block;" class="form-control timeinput" name="time_<?php echo $set_arr->id ?>">
								<option>N/A</option>
								<option <?php if($set_arr->time == 'Failure'){ echo 'selected'; } ?>>Failure</option>
								<?php for($i=1;$i<=600;$i++):  ?>
									<option <?php if($set_arr->time == $i){ echo 'selected'; } ?>  value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>
								<?php endfor; ?>  
							</select>
							&nbsp;&nbsp; Weight
							<select style="width:20%;display: inline-block;" class="form-control setinput" name="set_<?php echo $set_arr->id ?>">
								<option>N/A</option>
								<option <?php if($set_arr->value == 'Body Weight'){ echo 'selected'; } ?>>Body Weight</option>
								<?php for($i=1;$i<=1000;$i++):  ?>
									<option value="<?php echo $i; ?>" <?php if($set_arr->value == $i){ echo 'selected'; } ?>  ><?php echo $i; ?> lbs</option>
								<?php endfor; ?>  
							</select>
							&nbsp;&nbsp; Reps
							<select style="width:20%;display: inline-block;" class="form-control repsinput" name="reps_<?php echo $set_arr->id ?>">
								<option>N/A</option>
								<option <?php if($set_arr->reps == 'Failure'){ echo 'selected'; } ?>>Failure</option>
								<?php for($i=1;$i<=100;$i++):  ?>
									<option <?php if($set_arr->reps == $i){ echo 'selected'; } ?> ><?php echo $i; ?></option>
								<?php endfor; ?>   
							</select>
							<a class="aj_remove_set" data-sid="<?php echo $set_arr->id ?>" <?php if(!$set_remove){ ?> style="display:none;" <?php } $set_remove = TRUE; ?> href="javascript:void(0);" rel="<?php echo $j ?>"> [-] </a>
							<br>
							<a class="add_set01" data-eid="<?php echo $ex_arr['exercise_id']; ?>" <?php if($j != count($ex_arr['sets'])) { echo 'style="display:none;"'; } ?> href="javascript:void(0);" rel="<?php echo $j + 1 ; ?>"> Add Set </a>
						</div>
						<?php $j++; } ?>
					<?php $bar = TRUE; } ?>
				</div>

				<?php if(count($c_row['exercises']) > 1 ){ ?>
					<?php $bar = TRUE ; $exercise_remove = FALSE; foreach($c_row['exercises'] as $ex_arr){ ?>
						<?php if($bar){ $bar = FALSE; continue; } ?>
						<div class="exercise_div">
							<div class="form-group exer">
								<label for="exampleInputEmail1">Exercise</label> 
								<a class="aj_remove_exercise" data-eid="<?php echo $ex_arr['exercise_id'] ?>" href="javascript:void(0)">[ remove ]</a>
								<input  placeholder="Excercise" type="text" class="form-control defaul_exercise" name="exercise<?php echo $ex_arr['exercise_id'] ?>" value="<?php echo $ex_arr['exercise'] ?>">
								<span class="error"></span>
							</div>
							
							<?php $set_remove = FALSE; $j = 1; foreach($ex_arr['sets'] as $set_arr){ ?>
							<div class="form-group sets0">
								<label for="exampleInputEmail1">Set <?php echo $j ?></label> 
								&nbsp;&nbsp; Time
								<select style="width:20%;display: inline-block;" class="form-control timeinput" name="time_<?php echo $set_arr->id ?>">
									<option>N/A</option>
									<option <?php if($set_arr->time == 'Failure'){ echo 'selected'; } ?>>Failure</option>
									<?php for($i=1;$i<=600;$i++):  ?>
										<option <?php if($set_arr->time == $i){ echo 'selected'; } ?>  value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>
									<?php endfor; ?>  
								</select>
								&nbsp;&nbsp; Weight
								<select style="width:20%;display: inline-block;" class="form-control setinput" name="set_<?php echo $set_arr->id ?>">
									<option>N/A</option>
									<option <?php if($set_arr->value == 'Body Weight'){ echo 'selected'; } ?>>Body Weight</option>
									<?php for($i=1;$i<=1000;$i++):  ?>
										<option value="<?php echo $i; ?>" <?php if($set_arr->value == $i){ echo 'selected'; } ?>  ><?php echo $i; ?> lbs</option>
									<?php endfor; ?>  
								</select>
								&nbsp;&nbsp; Reps
								<select style="width:20%;display: inline-block;" class="form-control repsinput" name="reps_<?php echo $set_arr->id ?>">
									<option>N/A</option>
									<option <?php if($set_arr->reps == 'Failure'){ echo 'selected'; } ?>>Failure</option>
									<?php for($i=1;$i<=100;$i++):  ?>
										<option <?php if($set_arr->reps == $i){ echo 'selected'; } ?> ><?php echo $i; ?></option>
									<?php endfor; ?>   
								</select>
								<a class="aj_remove_set" data-sid="<?php echo $set_arr->id ?>" <?php if(!$set_remove){ ?> style="display:none;" <?php } $set_remove = TRUE; ?> href="javascript:void(0);" rel="<?php echo $j ?>"> [-] </a>
								<br>
								<a class="add_set01" data-eid="<?php echo $ex_arr['exercise_id']; ?>" <?php if($j != count($ex_arr['sets'])) { echo 'style="display:none;"'; } ?> href="javascript:void(0);" rel="<?php echo $j + 1 ; ?>"> Add Set </a>
							</div>
							<?php $j++; } ?>
						</div>
					<?php } ?>
				<?php } ?>


				<br>	
				<div class="form-group">
					<!-- <a class="add_exercise01" data-cid="<?php echo $c_row['id'] ?>" rel="1" href="javascript:void(0);"> Add New Exercise </a> -->
				</div>
			</div>
			<br>
		<?php } ?>
	<?php } ?>
</div>
<script type="text/javascript">
	$(document).on('click', '.add_exercise01', function(){

		// alert(1);
		
		var div = $(this).parent().parent();

		var cid = $(this).data('cid');
		var rel = $(this).attr('rel');
		type = $('[name=type]').val();
		if(type == 1){
			var css = '';
		}else{
			var css = 'hide';
		}

		
		var html = '';
		html += '<div class="exercise_div">';
		html += '<div class="form-group exer">';
		html += '<label for="exampleInputEmail1">Exercise</label><a class="remove_exercise" href="javascript:void(0)">[ remove]</a>';
		html += '<input placeholder="Excercise" type="text" class="form-control defaul_exercise" name="exercise'+cid+'[]" value="">';
		html += '<hideshowtag class="'+css+'">';
		html += '<span class="error"></span><br><textarea placeholder="Description" type="text" class="form-control" name="description'+cid+'[]" value=""></textarea>';
		html += '<br>Exercise Image<br><input type="file" name="image_exercise'+cid+'[]" value="">(Size 640*300) <br><br>';
		html += '<b>OR</b><br><a href="javascript:void(0)" class="showgallery_e">Select Image <input type="hidden" value="" name="exerciseimg'+cid+'[]" class="exerciseimg"></a><br>';
		html += '<div class="showimage" style="display:none"></div><div class="loader" style="display:none"><img src="<?php echo base_url() ?>assets/theme/img/loading.gif" style="width:50px"></div>';
		html += '</hideshowtag>';
		html += '</div>';
		html += '<div class="form-group sets">';
		html += '<label for="exampleInputEmail1">Set 1</label> ';
		
		html += '&nbsp;&nbsp; Time ';
		html += '<select style="width:20%;display: inline-block;" class="form-control timeinput" name="time_'+cid+'[]">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=600;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp; Weight ';
		html += '<select style="width:20%;display: inline-block;" class="form-control setinput" name="set_'+cid+'[]">';
		html += ' <option>N/A</option>';
		html += ' <option>Body Weight</option>';
		html += ' <?php for($i=1;$i<=1000;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>"><?php echo $i; ?> lbs</option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp; Reps ';
		html += '<select style="width:20%;display: inline-block;" class="form-control repsinput" name="reps_'+cid+'[]">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=100;$i++):  ?>';
		html += '   <option><?php echo $i; ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';

		html += '<a class="remove_set" style="display:none;" href="javascript:void(0);" rel="1"> [-] </a>';
		html += '<br>';
		html += '<a class="add_set" href="javascript:void(0);" rel="2"> Add Set </a>';
		html += '</div> ';
		html += '</div>';
		
		// $('.exercise_parent_div')
		// div.append(html);

		var ele = $(this).parent();

		$(html).insertBefore(ele);

		var inp = $(this).parent().parent().find('.exercise_div').find('.circ').find('input.circuit_e_count');
		inp.val( parseInt( parseInt( inp.val() ) + 1 ) );
	});
	
	$(document).on('click', '.add_set01', function(){
		var rel = $(this).attr('rel');
		var eid = $(this).data('eid');
		
		var html = '';
		
		html += '<div class="form-group sets0">';
		html += '<label for="exampleInputEmail1">Set '+rel+'</label> ';
		 
	
		html += '&nbsp;&nbsp; Time ';
		html += '<select style="width:20%;display: inline-block;" class="form-control timeinput" name="time_'+eid+'[]">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=600;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp;&nbsp; Weight ';
		html += '<select style="width:20%;display: inline-block;" class="form-control setinput" name="set_'+eid+'[]">';
		html += ' <option>N/A</option>';
		html += ' <option>Body Weight</option>';
		html += ' <?php for($i=1;$i<=1000;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>"><?php echo $i; ?> lbs</option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp;&nbsp; Reps ';
		html += '<select style="width:20%;display: inline-block;" class="form-control repsinput" name="reps_'+eid+'[]">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=100;$i++):  ?>';
		html += '   <option><?php echo $i; ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';


		html += '<a class="remove_set0" href="javascript:void(0);" rel="'+rel+'"> [-] </a>';
		html += '<br>';
		html += '<a class="add_set01" data-eid="'+eid+'" href="javascript:void(0);" rel="'+parseInt(parseInt(rel)+1)+'"> Add Set </a>';
		html += '</div>';
		
		$(this).parent().parent().append(html);
		// $(this).parent().append(html);
		
		$(this).hide();
	});


	$(document).on('click', '.remove_set0', function(){
		var parentDiv = $(this).parent().parent();
		$(this).parent().remove();
		var obj = [];
		var i = 1;
		
		parentDiv.find('.sets0').each(function(){
			$(this).find('label').html('Set '+i);
			$(this).find('a.remove_set0').attr('rel', i);
			$(this).find('a.add_set01').attr('rel', parseInt(i+1));
		
			if(i == parentDiv.find('.sets0').length){
				$(this).find('a.add_set01').show();
			}
			i++;
		});
	});


	$(document).on('click', '.aj_remove_set', function(){
		var parentDiv = $(this).parent().parent();
		$(this).parent().remove();
		var obj = [];
		var i = 1;

		var sid = $(this).data('sid');
		
		parentDiv.find('.sets0').each(function(){
			$(this).find('label').html('Set '+i);
			$(this).find('a.remove_set0').attr('rel', i);
			$(this).find('a.add_set01').attr('rel', parseInt(i+1));
		
			if(i == parentDiv.find('.sets0').length){
				$(this).find('a.add_set01').show();
			}
			i++;
		});

		$.ajax({
			url:'<?php echo base_url() ?>circuit_workout/ajax_remove_set/'+sid,
		});
	});

</script>
<div class="form-group">
	<!-- <a class="add_circuit" href="javascript:void(0);"> Add New Circuit </a> -->
</div>

<input type="hidden" value="1" id="count_of_exercise" name="count_of_exercise">
<input type="submit" class="btn btn-primary" value="Submit">
<br>
<br>
<?php echo form_close(); ?>

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
								<!-- <label > -->
								<img src="<?php echo BUCKET_PATH.$row->image ?>" alt="" style="max-width:100%">
								<input name="galleryimage"  value="<?php echo $row->image ?>" type="radio" value="" style="display:inline">
								<!-- </label> -->
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
		
	$(document).on('click','.showgallery_e',function(){
		$('.modal').modal();                
		$(this).find('.circuitimg').addClass('hasimage');
	});
		
	function selectgallery(){
		var val = $('input[name=galleryimage]:checked').val();

		if(val !== undefined){
			$(document).find('.hasimage').val(val);
			var alink = $(document).find('.hasimage').parent();
			alink.parent().find('.showimage').show();
			// var el = '<img src="<?php echo BUCKET_PATH ?>'+val+'" style="max-width:100px;">';
			// alink.parent().find('.showimage').html(el);
			alink.parent().find('.showimage').html(val);
		}
		
		$('#closemmodal').trigger('click');
	}
		
	function remove_hasclass(){
		$(document).find('.hasimage').removeClass('hasimage');
	}	
</script>

<script type="text/javascript">
	$(document).on('click', '.add_set', function(){
		var rel = $(this).attr('rel');
		
		var html = '';
		
		html += '<div class="form-group sets">';
		html += '<label for="exampleInputEmail1">Set '+rel+'</label> ';
		 
	
		html += '&nbsp;&nbsp; Time ';
		html += '<select style="width:20%;display: inline-block;" class="form-control timeinput" name="time_'+rel+'">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=600;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp;&nbsp; Weight ';
		html += '<select style="width:20%;display: inline-block;" class="form-control setinput" name="set_'+rel+'">';
		html += ' <option>N/A</option>';
		html += ' <option>Body Weight</option>';
		html += ' <?php for($i=1;$i<=1000;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>"><?php echo $i; ?> lbs</option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp;&nbsp; Reps ';
		html += '<select style="width:20%;display: inline-block;" class="form-control repsinput" name="reps_'+rel+'">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=100;$i++):  ?>';
		html += '   <option><?php echo $i; ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';


		html += '<a class="remove_set" href="javascript:void(0);" rel="'+rel+'"> [-] </a>';
		html += '<br>';
		html += '<a class="add_set" href="javascript:void(0);" rel="'+parseInt(parseInt(rel)+1)+'"> Add Set </a>';
		html += '</div>';
		
		$(this).parent().parent().append(html);
		// $(this).parent().append(html);
		
		$(this).hide();
	});

	$(document).on('click', '.add_exercise', function(){

		// alert(1);
		
		var div = $(this).parent().parent();

		var rel = $(this).attr('rel');
		type = $('[name=type]').val();
		if(type == 1){
			var css = '';
		}else{
			var css = 'hide';
		}

		
		var html = '';
		html += '<div class="exercise_div">';
		html += '<div class="form-group exer">';
		html += '<label for="exampleInputEmail1">Exercise</label><a class="remove_exercise" href="javascript:void(0)">[ remove]</a>';
		html += '<input placeholder="Excercise" type="text" class="form-control defaul_exercise" name="exercise[]" value="">';
		html += '<hideshowtag class="'+css+'">';
		html += '<span class="error"></span><br><textarea placeholder="Description" type="text" class="form-control" name="description[]" value=""></textarea>';
		html += '<br>Exercise Image<br><input type="file" name="image_exercise[]" value="">(Size 640*300) <br><br>';
		html += '<b>OR</b><br><a href="javascript:void(0)" class="showgallery_e">Select Image <input type="hidden" value="" name="exerciseimg[]" class="exerciseimg"></a><br>';
		html += '<div class="showimage" style="display:none"></div><div class="loader" style="display:none"><img src="<?php echo base_url() ?>assets/theme/img/loading.gif" style="width:50px"></div>';
		html += '</hideshowtag>';
		html += '</div>';
		html += '<div class="form-group sets">';
		html += '<label for="exampleInputEmail1">Set 1</label> ';
		
		html += '&nbsp;&nbsp; Time ';
		html += '<select style="width:20%;display: inline-block;" class="form-control timeinput" name="time_'+rel+'">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=600;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp; Weight ';
		html += '<select style="width:20%;display: inline-block;" class="form-control setinput" name="set_'+rel+'">';
		html += ' <option>N/A</option>';
		html += ' <option>Body Weight</option>';
		html += ' <?php for($i=1;$i<=1000;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>"><?php echo $i; ?> lbs</option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp; Reps ';
		html += '<select style="width:20%;display: inline-block;" class="form-control repsinput" name="reps_'+rel+'">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=100;$i++):  ?>';
		html += '   <option><?php echo $i; ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';

		html += '<a class="remove_set" style="display:none;" href="javascript:void(0);" rel="1"> [-] </a>';
		html += '<br>';
		html += '<a class="add_set" href="javascript:void(0);" rel="2"> Add Set </a>';
		html += '</div> ';
		html += '</div>';
		
		// $('.exercise_parent_div')
		// div.append(html);

		var ele = $(this).parent();

		$(html).insertBefore(ele);

		var inp = $(this).parent().parent().find('.exercise_div').find('.circ').find('input.circuit_e_count');
		inp.val( parseInt( parseInt( inp.val() ) + 1 ) );
	});

	$(document).on('click', '.add_circuit', function(){
		
		var div = $('.circuit_parent_div');

		var css = 'hide';

		var rel = 1;
		
		var html = '';
		
		html += '<div class="exercise_parent_div">';
		html += '<div class="exercise_div">';

		html += '<div class="form-group circ">';
		html += '<label for="exampleInputEmail1">Circuit</label><a class="remove_circuit" href="javascript:void(0)">[ remove ]</a>';
		html += '<input  placeholder="Circuit Name" type="text" class="form-control" name="circuit[]" value="">';
		html += '<input  type="hidden" class="form-control circuit_e_count" name="circuit_e_count[]" value="1">';
		html += '<br>';
		html += '<hideshowtag>';
		html += '<textarea placeholder="Circuit Description" type="text" class="form-control" name="circuit_description[]" value=""></textarea>';
		html += '<br>';
		html += 'Circuit Image';
		html += '<br>';
		html += '<input type="file"  name="image_circuit[]" value="">(Size 640*300)';
		html += '<br><b>OR</b><br>';
		html += '<a href="javascript:void(0)" class="showgallery_e">Select Image <input type="hidden" value="" name="circuitimg[]" class="circuitimg"></a>';
		html += '<div class="showimage" style="display:none"></div>';
		html += '<div class="loader" style="display:none"><img src="<?php echo base_url() ?>assets/theme/img/loading.gif" style="width:50px"></div>';
		html += '<br>';
		html += '<br>';
		html += '</hideshowtag>';
		html += '</div>';

		html += '<div class="form-group exer">';
		html += '<label for="exampleInputEmail1">Exercise</label>';
		html += '<input placeholder="Excercise" type="text" class="form-control defaul_exercise" name="exercise[]" value="">';
		html += '<hideshowtag class="'+css+'">';
		html += '<span class="error"></span><br><textarea placeholder="Description" type="text" class="form-control" name="description[]" value=""></textarea>';
		html += '<br>Exercise Image<br><input type="file" name="image_exercise[]" value="">(Size 640*300) <br><br>';
		html += '<b>OR</b><br><a href="javascript:void(0)" class="showgallery_e">Select Image <input type="hidden" value="" name="exerciseimg[]" class="exerciseimg"></a><br>';
		html += '<div class="showimage" style="display:none"></div><div class="loader" style="display:none"><img src="<?php echo base_url() ?>assets/theme/img/loading.gif" style="width:50px"></div>';
		html += '</hideshowtag>';
		html += '</div>';
		html += '<div class="form-group sets">';
		html += '<label for="exampleInputEmail1">Set 1</label> ';
		
		html += '&nbsp;&nbsp; Time ';
		html += '<select style="width:20%;display: inline-block;" class="form-control timeinput" name="time_'+rel+'">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=600;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp; Weight ';
		html += '<select style="width:20%;display: inline-block;" class="form-control setinput" name="set_'+rel+'">';
		html += ' <option>N/A</option>';
		html += ' <option>Body Weight</option>';
		html += ' <?php for($i=1;$i<=1000;$i++):  ?>';
		html += '   <option value="<?php echo $i; ?>"><?php echo $i; ?> lbs</option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';
		html += '&nbsp;&nbsp; Reps ';
		html += '<select style="width:20%;display: inline-block;" class="form-control repsinput" name="reps_'+rel+'">';
		html += ' <option>N/A</option>';
		html += ' <option>Failure</option>';
		html += ' <?php for($i=1;$i<=100;$i++):  ?>';
		html += '   <option><?php echo $i; ?></option>';
		html += ' <?php endfor; ?>  ';
		html += '</select>';

		html += '<a class="remove_set" style="display:none;" href="javascript:void(0);" rel="1"> [-] </a>';
		html += '<br>';
		html += '<a class="add_set" href="javascript:void(0);" rel="2"> Add Set </a>';
		html += '</div> ';
		html += '</div>';
		html += '<br>';
		html += '<div class="form-group">';
		html += '<a class="add_exercise" href="javascript:void(0);"> Add New Exercise </a>';
		html += '</div>';
		html += '</div>';
		// html += '<br>';

		// alert(html);
		
		div.append(html);
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
		
	$(document).on('click', '.remove_exercise', function(){
		var inp = $(this).parent().parent().parent().find('.exercise_div').find('.circ').find('input.circuit_e_count');
		inp.val( parseInt( parseInt( inp.val() ) - 1 ) );
		$(this).parent().parent().remove();
	});

	$(document).on('click', '.aj_remove_exercise', function(){
		var inp = $(this).parent().parent().parent().find('.exercise_div').find('.circ').find('input.circuit_e_count');
		inp.val( parseInt( parseInt( inp.val() ) - 1 ) );
		$(this).parent().parent().remove();
		var eid = $(this).data('eid');

		$.ajax({
			url:'<?php echo base_url() ?>circuit_workout/ajax_remove_exercise/'+eid,
		});

	});

	$(document).on('click', '.aj_remove_circuit', function(){
		$(this).parent().parent().parent().remove();
		var cid = $(this).data('cid');

		$.ajax({
			url:'<?php echo base_url() ?>circuit_workout/ajax_remove_circuit/'+cid,
		});
	});

	$(document).on('click', '.remove_circuit', function(){
		$(this).parent().parent().parent().remove();
	});
		
	$(document).on('submit', '#contentForm', function(event){
		var flag = 1;
		if($("#datepicker").val() == ''){
			$("#datepicker").css('border', '1px red solid');
			flag = 2;
		}
		
		var w_length = $("#name_of_workout").val().length;
		if($("#name_of_workout").val() == ''){
			$("#name_of_workout").css('border', '1px red solid');
			flag = 2;
		}
		else if(w_length<3 || w_length>25){
			$("#name_of_workout").siblings('span').html('Workout name length should be between 3 to 25 characters');
			$("#name_of_workout").css('border', '1px red solid');
			flag = 2;
		}
		else{
			$("#name_of_workout").css('border', '');
			$("#name_of_workout").siblings('span').html('');
		}
		
		if($("#desc_of_workout").val() == ''){
			$("#desc_of_workout").css('border', '1px red solid');
			flag = 2;
		}
		
		var t = 1;
		$(".exercise_div").each(function(){
			// console.log($(this));

			e_length = $(this).find('.exer').find('input').val().length;
			if($(this).find('.exer').find('input').val() == ''){
				$(this).find('.exer').find('input').css('border', '1px solid red');
				flag = 2;
			}else if(e_length<3 || e_length>25){
				$(this).find('.exer').find('span').html('Exercise name length should be between 3 to 25 characters');
				$(this).find('.exer').find('input').css('border', '1px red solid');
				flag = 2;
			}else{
				$(this).find('.exer').find('span').html('');
				$(this).find('.exer').find('input').css('border', '');
			}

			$(this).find('.sets').each(function(){
				$(this).find('.timeinput').attr('name', 'time'+t+'[]');
				$(this).find('.setinput').attr('name', 'sets'+t+'[]');
				$(this).find('.repsinput').attr('name', 'reps'+t+'[]');
				if($(this).find('input').val() == ''){
					$(this).find('input').css('border', '1px solid red');
					flag = 2;
				}
			});
			// alert(t);
			t++;
		});

		// return false;
		
		if(flag == 1){
			return true;
		}
		else{
			return false;
		}
	});
		
	$(document).on('click', 'select', function(){
		$(this).css('border', '');
	});
		
	$(document).on('focus', 'input, textarea', function(){
		$(this).css('border', '');
	});
		
	$(document).on('click','#add_group', function(){  
		$('#add_group').hide();
		$('#group_box').show();
		$('#remove_group').show();
	});
		
	$(document).on('click','#remove_group', function(){  
		$('#add_group').show();
		$('#group_box').hide();
		$('#remove_group').hide();
	});
		
	$(document).on('change','#choose_time', function(){  
		if($(this).val() == '1'){
			$("#set_time").hide();
			$("#timepicker").val('');
		}
		else{
			$("#set_time").show();
			$("#timepicker").focus();
		}
	});
</script>

<script type="text/javascript">
	$(document).on('click','.parent_gr_div input[type=checkbox]',function(){
		is_checked = $(this).prop('checked');
		if(is_checked){
			$(this).parents('.gr_div').find('.child_gr_div input[type=checkbox]').prop('checked',true);
		}
		else{
			$(this).parents('.gr_div').find('.child_gr_div input[type=checkbox]').prop('checked',false);
		}
	}); 

	$(document).on('click','.child_gr_div input[type=checkbox]',function(){
		flag = 0;
		$(this).parents('.gr_div').find('.child_gr_div input[type=checkbox]').each(function(){
			is_check = $(this).prop('checked');
			if(!is_check){
				flag = 1;
			} 
		});
		
		if(flag==1){
			$(this).parents('.gr_div').find('.parent_gr_div input[type=checkbox]').prop('checked',false);
		}
		else{
			$(this).parents('.gr_div').find('.parent_gr_div input[type=checkbox]').prop('checked',true);
		}
	}); 
</script>

<style type="text/css">
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
</style>
<script>
onload = change_type();
function change_type(){
	return 0;
}
</script>
<style>
.hide{
	display: none;
}
</style>