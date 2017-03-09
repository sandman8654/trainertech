<!-- <section class="contant"> -->
<?php echo alert(); ?>   
<h4>Add System Circuit</h4>
<?php echo form_open_multipart(current_url(), array('class'=>'tasi-form', 'id'=>'contentForm')); ?>
<div class="form-group">
	<label for="exampleInputEmail1">Circuit Name</label> 
	<input type="text" class="form-control" name="name" id="circuit_name" value="<?php echo set_value('name'); ?>">
	<span class="error"><?php echo form_error('name'); ?></span>
</div>

<div class="form-group">
	<label for="">Circuit Description</label>
	<textarea class="form-control" name="circuit_description" id="circuit_description" rows="10"><?php echo set_value('description'); ?></textarea>
	<span class="error"><?php echo form_error('circuit_description'); ?></span>
</div>

<div class="form-group">
	<label for="">Circuit Instructions</label>
	<select class="form-control grey-box new_exercise_select_box">
		<option value="0">Choose Exercise</option>
		<?php if($default_exercises){ ?>
			<?php foreach($default_exercises as $row){ ?>
				<option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
			<?php } ?>
		<?php } ?>
	</select>
</div>

<div class="exercise_parent_div">

</div>

<label id="loading_image" style="display:none;">
  <img src="<?php echo base_url() ?>assets/theme/img/preloader-bar.gif">
  <br>
  <br>
</label>

<style>
	.exercise_div{
		background: #dadcdc;
		padding: 20px;
	}

	.exercise_div label.ehead{
		width: 100%;
	}

	.exercise_div label.ehead a{
		float: right;
	}

	.selector_div{
		background: #dadcdc;
		padding: 20px;
	}

	.selector_div label.ehead{
		width: 100%;
	}

	.selector_div label.ehead a{
		float: right;
	}

	select.grey-box{
		-moz-appearance: none;
		-webkit-appearance: none;
		background: url("<?php echo base_url(); ?>assets/theme/img/select.png") no-repeat scroll right 10px center #e5e9ea;
		margin-right: 15px;
		border: 0;
		border-radius: 3px;
		box-shadow: none;
		height: 40px;
		border: 0;
		text-transform: capitalize;
	}
</style>


<a id="new_exercise" href="javascript:void(0);" title="Exercise" style="display:none;"><img src="<?php echo base_url() ?>assets/theme/img/addNew.png">Exercise</a>

<br>
<br>

<input type="submit" class="btn btn-primary" value="Submit">
</form>
<br>  
<!-- </section> -->
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
	$(document).on('click','.showgallery_e',function(){
		if($(this).find('.exerciseimg').val() == ''){
			$('input[name=galleryimage]:checked').prop('checked', false);
		}

		$('.modal').modal();                
		$(this).find('.exerciseimg').addClass('hasimage');
	});
		
	function selectgallery(){
		var val = $('input[name=galleryimage]:checked').val();
		$(document).find('.hasimage').val(val);
		var alink = $(document).find('.hasimage').parent();
		alink.parent().find('.showimage').show();

		var el = '<img src="<?php echo BUCKET_PATH ?>'+val+'" style="width:100px">';

		el += '<a href="javascript:void(0);" class="rem_img">Remove</a>';

		alink.parent().find('.showimage').html(el);
		$('#closemmodal').trigger('click');
	}
		
	function remove_hasclass(){
		$(document).find('.hasimage').removeClass('hasimage');
	}

	$(document).on("click", ".rem_img", function(){
		$(this).parent().parent().find('.showgallery_e').find('input.exerciseimg').val('');
		$(this).parent().hide();
		$(this).parent().html('');
	});

	$(document).on('click', '.add_set', function(){
	var rel = $(this).attr('rel');
	
	var html = '';
	
	html += '<div class="form-group sets">';
	html += '<label for="exampleInputEmail1">Set '+rel+'</label> ';
	 
  
	html += '&nbsp;&nbsp; <!-- Time --> ';
	html += '<select style="width:20%;display: inline-block;" class="form-control timeinput grey-box" name="time_'+rel+'">';
	html += ' <option>N/A</option>';
	html += ' <option>Failure</option>';
	html += ' <?php for($i=1;$i<=600;$i++):  ?>';
	html += '   <option value="<?php echo $i; ?>" ><?php echo seconds_to_string($i); ?></option>';
	html += ' <?php endfor; ?>  ';
	html += '</select>';
	html += '&nbsp;&nbsp;&nbsp; <!-- Weight --> ';
	html += '<select style="width:20%;display: inline-block;" class="form-control setinput grey-box" name="set_'+rel+'">';
	html += ' <option>N/A</option>';
	html += ' <option>Body Weight</option>';
	html += ' <?php for($i=1;$i<=1000;$i++):  ?>';
	html += '   <option value="<?php echo $i; ?>"><?php echo $i; ?> lbs</option>';
	html += ' <?php endfor; ?>  ';
	html += '</select>';
	html += '&nbsp;&nbsp;&nbsp; <!-- Reps --> ';
	html += '<select style="width:20%;display: inline-block;" class="form-control repsinput grey-box" name="reps_'+rel+'">';
	html += ' <option>N/A</option>';
	html += ' <option>Failure</option>';
	html += ' <?php for($i=1;$i<=100;$i++):  ?>';
	html += '   <option><?php echo $i; ?></option>';
	html += ' <?php endfor; ?>  ';
	html += '</select>';


	html += '<a class="remove_set" href="javascript:void(0);" rel="'+rel+'"> Remove </a>';
	html += '<br>';
	html += '<a class="add_set" href="javascript:void(0);" rel="'+parseInt(parseInt(rel)+1)+'"> Add Set </a>';
	html += '</div>';
	
	$(this).parent().parent().append(html);
	
	$(this).hide();
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


	$(document).on("click", ".remove_exercise", function(){
		if(confirm('Are you sure?')){
			$(this).parent().parent().remove();
			exercise_numbering();
		}
	});

	$(document).on("click", ".remove_selector", function(){
		$(".selector_div").each(function(){
			$(this).remove();
		});

		$("#new_exercise").show();
	});

	$(document).on("change", ".new_exercise_select_box", function(){
		var val = $(this).val();
		if(val != '0'){
			$(".selector_div").each(function(){
				$(this).remove();
			});
			$(this).remove();
			$.ajax({
				url : '<?php echo base_url()._INDEX ?>circuit/add_exercise_box',
				data : {
					id : val
				},
				type : 'POST',
				beforeSend : function(){
		          $("#loading_image").show();
		        },
		        success : function(exercise_div){
					if(exercise_div != '0'){
						$(".exercise_parent_div").append(exercise_div);
						exercise_numbering();
						$("#new_exercise").show();
					}
					$("#loading_image").hide();
				}
			});
		}
	});

	function exercise_numbering(){
		$("span.ex_no").each(function (index){
			$(this).html( parseInt(index) + 1 );
		});
	}

	$(document).on("click", "#new_exercise", function(){
		var select_box = '';

		select_box += '<div class="form-group selector_div">';
		select_box += '<label class="ehead"> Exercise #<span class="ex_no"></span> <a href="javascript:void(0);" class="remove_selector">Remove</a> </label>';

		select_box += '<select class="form-control grey-box new_exercise_select_box">';
		select_box += '<option value="0">Choose Exercise</option>';
		<?php if($default_exercises){ ?>
			<?php foreach($default_exercises as $row){ ?>
				select_box += '<option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>';
			<?php } ?>
		<?php } ?>
		select_box += '</select>';

		select_box += '</div>';

		$(select_box).insertAfter(".exercise_parent_div");

		exercise_numbering();

		$(this).hide();
	});

	setInterval(function(){
		$(".file_image_exercise").each(function(){
			if($(this).val() != ''){
				$(this).parent().find('a.remove_file').show();
			}
			else{
				$(this).parent().find('a.remove_file').hide();
			}
		});
	}, 100);

	$(document).on('click', 'a.remove_file', function(){
		$(this).parent().find('.file_image_exercise').val('');
	});

	$(document).on('submit', '#contentForm', function(event){
		var flag = 1;
		var c_name_length = $("#circuit_name").val().length;
		if($("#circuit_name").val() == ''){
			$("#circuit_name").css('border', '1px red solid');
			flag = 2;
		}
		else if(c_name_length<3 || c_name_length>25){
			$("#circuit_name").siblings('span').html('Circuit name length should be between 3 to 25 characters');
			$("#circuit_name").css('border', '1px red solid');
			flag = 2;
		}
		else{
			$("#circuit_name").css('border', '');
			$("#circuit_name").siblings('span').html('');
		}
	
		if($("#circuit_description").val() == ''){
			$("#circuit_description").css('border', '1px red solid');
			flag = 2;
		}
		else{
			$("#circuit_description").css('border', '');
		}
	
		var t = 1;
		$(".exercise_div").each(function(){
			e_length = $(this).find('input.exer_name').val().length;
			
			if($(this).find('input.exer_name').val() == ''){
				$(this).find('input.exer_name').css('border', '1px solid red');
				flag = 2;
			}else if(e_length<3 || e_length>25){
				$(this).find('input.exer_name').css('border', '1px red solid');
				$(this).find('span.exer_name_error').html('Exercise name length should be between 3 to 25 characters');
				flag = 2;
			}else{
				$(this).find('input.exer_name').css('border', '');
				$(this).find('span.exer_name_error').html('');
			}	
	
			$(this).find('.sets').each(function(){
				$(this).find('.timeinput').attr('name', 'time'+t+'[]');
				$(this).find('.setinput').attr('name', 'sets'+t+'[]');
				$(this).find('.repsinput').attr('name', 'reps'+t+'[]');
			});
			t++;
		});

		if(t == 1){
			alert("Please save atleast one exercise in system circuit.");
			return false;
		}

		if(flag == 1){
			return true;
		}
		else{
			return false;
		}
	});
</script>