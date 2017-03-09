<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.ru.js"></script>
<script type="text/javascript">
	$(function () {
		$('#datepicker').datetimepicker({
			pickTime: false,
			minDate: moment("<?php echo $mdate ?>")
			// minDate: moment("<?php echo date('m/d/Y'); ?>")
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
<h3>Add Circuit Workout</h3>
<?php echo form_open_multipart(cms_current_url(), array('id' => 'contentForm', 'class'=>'tasi-form')); ?>
<div class="form-group">
	<label for="exampleInputEmail1">Create work out for</label> 
		<?php if($trainee) :  ?>
		<div class="main_div">
			<div class="my_check">
				<?php $i=1;  foreach($trainee as $row): ?>
				<div class="checkbox">
						<label >
						<input name="trainee_id[]"  value="<?php echo $row->id ?>" type="checkbox" value=""><?php echo $row->fname." ".$row->lname ?>
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
						<label><input name="group_id[]" value="<?php echo $subgroup->id ?>" type="checkbox" value=""><?php echo $subgroup->name ?></label>
				</div>
				<?php endforeach ?>
			</div>
		<?php else: ?>     
		<div class="checkbox">
				<label><input name="group_id[]" value="<?php echo $group->id ?>" type="checkbox" value=""><?php echo $group->name ?></label>
		</div>
		<?php endif; ?>
		<?php endforeach ?>
		<!-- Groups ends -->
	</div>
<a id="remove_group" href="javascript:void(0);"  > Remove Group </a>
<?php endif; ?>

<div class='input-group date'>
	<label for="exampleInputEmail1">Date</label> 
	<input type='text' class="form-control" name="date_of_workout" value="<?php echo set_value('date_of_workout') ?>" id='datepicker'/>
	<span class="error"><?php echo form_error('date_of_workout'); ?></span>
</div>

<div class='input-group date'>
	<label for="exampleInputEmail1">Time</label> 
	<select name="anytime" class="form-control" id="choose_time">
			<option value="1">Any-time</option>
			<option value="0">Set-time</option>
	</select>
</div>

<div class="form-group" id="set_time" style="display:none;">
	<label for="exampleInputEmail1">Set Time</label> 
	<input type='text' class="form-control" name="time_of_workout" value="<?php echo set_value('time_of_workout') ?>" id='timepicker'/>
	<span class="error"><?php echo form_error('time_of_workout'); ?></span>
</div>

<div class="form-group">
	<label for="exampleInputEmail1">Name of Workout</label> 
	<input type="text" class="form-control" name="name_of_workout" id="name_of_workout" value="<?php echo set_value('name_of_workout'); ?>">
	<span class="error"><?php echo form_error('name_of_workout'); ?></span>
</div>

<div class="form-group">
	<label for="exampleInputEmail1">Description of Workout</label> 
	<textarea class="form-control" rows="5" id="desc_of_workout" name="desc_of_workout"><?php echo set_value('desc_of_workout'); ?></textarea>
	<span class="error"><?php echo form_error('desc_of_workout'); ?></span>
</div>

<div class="form-group">
	<label for="exampleInputEmail1">Image of Workout</label> 
	<input  type="file"  name="image_workout" value="">(Size 640*300) <br><b>OR</b><br>
	<a href="javascript:void(0)" class="showgallery_w">Select Image <input type="hidden" value="" name="wrkoutimg" id="wrkoutimg"> </a>
	<span class="error"><?php echo form_error('image_workout'); ?></span>
</div>


<div class="form-group type-div-class" style="display:none;">
	<label for="exampleInputEmail1">Type</label> 
	<select onchange='change_type();' class="form-control" name='type' style='width:15%;'>
		<!-- <option value='1'>Normal Exercise</option> -->
		<option value='2'>Circuit Exercise</option>
	</select>
	<br>
</div>

<div class="circuit_parent_div">
	<div class="exercise_parent_div">
		<div class="exercise_div">
			<div class="form-group circ">
				<label for="exampleInputEmail1">Circuit</label> 
				<input  placeholder="Circuit Name" type="text" class="form-control" name="circuit[]" value="">
				<input  type="hidden" class="form-control circuit_e_count" name="circuit_e_count[]" value="1">
				<br>
				<hideshowtag>
					<textarea placeholder="Circuit Description" type="text" class="form-control" name="circuit_description[]" value=""></textarea>
					<br>
					Circuit Image
					<br>
					<input type="file"  name="image_circuit[]" value="">(Size 640*300)
					<br><b>OR</b><br>
					<a href="javascript:void(0)" class="showgallery_e">Select Image <input type="hidden" value="" name="circuitimg[]" class="circuitimg"></a>
					<div class="showimage" style="display:none"></div>
					<div class="loader" style="display:none"><img src="<?php echo base_url() ?>assets/theme/img/loading.gif" style="width:50px"></div>
					<br>                    
					<br>
				</hideshowtag>
			</div>
			<div class="form-group exer">
				<label for="exampleInputEmail1">Exercise</label> 
				<input  placeholder="Excercise" type="text" class="form-control defaul_exercise" name="exercise[]" value="">
				<span class="error"></span>
				<?php if(FALSE){ ?>
				<br>
				<hideshowtag>
					<textarea placeholder="Description" type="text" class="form-control" name="description[]" value=""></textarea>
					<br>
					Exercise Image
					<br>
					<input type="file"  name="image_exercise[]" value="">(Size 640*300)
					<br><b>OR</b><br>
					<a href="javascript:void(0)" class="showgallery_e">Select Image <input type="hidden" value="" name="exerciseimg[]" class="exerciseimg"> </a>
					<div class="showimage" style="display:none"></div>
					<div class="loader" style="display:none"><img src="<?php echo base_url() ?>assets/theme/img/loading.gif" style="width:50px"></div>
					<br>                    
					<br>
				</hideshowtag>
				<?php } ?>
			</div>
			
			<div class="form-group sets">
				<label for="exampleInputEmail1">Set 1</label> 
				&nbsp;&nbsp; Time
				<select style="width:20%;display: inline-block;" class="form-control timeinput" name="time_1">
					<option>N/A</option>
					<option>Failure</option>
					<?php for($i=1;$i<=600;$i++):  ?>
						<option value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>
					<?php endfor; ?>  
				</select>
				&nbsp;&nbsp; Weight
				<select style="width:20%;display: inline-block;" class="form-control setinput" name="set_1">
					<option>N/A</option>
					<option>Body Weight</option>
					<?php for($i=1;$i<=1000;$i++):  ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?> lbs</option>
					<?php endfor; ?>  
				</select>
				&nbsp;&nbsp; Reps
				<select style="width:20%;display: inline-block;" class="form-control repsinput" name="reps_1">
					<option>N/A</option>
					<option>Failure</option>
					<?php for($i=1;$i<=100;$i++):  ?>
						<option><?php echo $i; ?></option>
					<?php endfor; ?>  
				</select>
				<a class="remove_set" style="display:none;" href="javascript:void(0);" rel="1"> [-] </a>
				<br>
				<a class="add_set" href="javascript:void(0);" rel="2"> Add Set </a>
			</div>
		</div>
		<br>	
		<div class="form-group">
			<a class="add_exercise" href="javascript:void(0);"> Add New Exercise </a>
		</div>
	</div>
	<br>
</div>

<div class="form-group">
	<a class="add_circuit" href="javascript:void(0);"> Add New Circuit </a>
</div>

<input type="hidden" value="1" id="count_of_exercise" name="count_of_exercise">
<input type="submit" class="btn btn-primary" value="Submit">
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
		$(".exercise_parent_div .exercise_div").each(function(){
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