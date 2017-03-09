<h4>Workouts</h4>
<?php alert(); ?>
<table class="table table-striped home-table">
	<thead>
		<tr>                
			<th>Date</th>
			<th>Workout Title</th>
			<th>Status</th>
			<th>View</th>
			<th>Print</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($workouts): foreach ($workouts as $row) { ?>
	 
			<td><?php echo date('m/d/Y', strtotime($row->date)); ?></td>                                    
			<td><?php echo $row->name ?></td>
																				 
			<td>
				<?php
					if($row->status == 0){
						echo 'Pending';
					}
					elseif($row->status == 2){
						echo 'Not Done';
					}
					else{
						echo 'Completed';
					}
				?>
			</td>
			<td>
				<a href="<?php echo base_url() ?>trainee/view_workout/<?php echo $row->id ?>"><i class="glyphicon glyphicon-eye-open"></i></a>
			</td>
			<td>
				<a href="javascript:void(0);" class="print_btn_temp" title="Print Button Loading"><i class="glyphicon glyphicon-print"></i></a>
				<a href="javascript:void(0);" onclick="printDiv('<?php echo $row->id ?>')" class="print_btn" title="Print"><i class="glyphicon glyphicon-print"></i></a>
			</td>
			
		</tr>               
		<?php  } endif ?>
	</tbody>
</table>
<?php echo $pagination; ?>

<style>
	.print_btn{
		display: none;
	}

	.print_btn_temp{
		opacity: 0.3;
	}
</style>

<script>
	$(window).load(function(){
		$(".print_btn_temp").remove();
		$(".print_btn").show();
	});

	function printDiv(wId) {
		var divName = 'print_me'+wId;
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;

		window.print();

		document.body.innerHTML = originalContents;
	}
</script>

<?php if ($workouts): foreach ($workouts as $row) { ?>

<div id="print_me<?php echo $row->id ?>">
	<style>
		div#print_me<?php echo $row->id ?> { display: none; }
		@media print{
			div#print_me<?php echo $row->id ?> { display: block; }
			.print-break {page-break-after:always;}
		}
	</style>

	<?php $workout = get_workout_full_detail($row->id); ?>

	<h3 style="text-align:center;"><?php echo ucfirst($workout['name']); ?></h3>

				<div class="form-group">
					<img src="<?php echo BUCKET_PATH.$workout['image'] ?>" alt="Workout Image" style="max-width:80%;">
				</div>

				
				<div class="form-group">
					<label for="">Date - <?php echo $workout['date'] ?></label>                                                       
				</div>
				<div class="form-group">
					<label for="">Time - <?php echo $workout['time'] ?></label>                                                       
				</div>
				<div class="form-group">
					<label for="">Description - <?php echo ucfirst($workout['description']); ?></label>                                                       
				</div>
				<div class="form-group">
					<label for="">Status - 
						<?php
              if($workout['status'] == 0){
                echo 'Pending';
              }
              elseif($workout['status'] == 2){
                echo 'Not Done';
              }
              else{
                echo 'Completed';
              }
            ?>
					</label>                                                       
				</div>

				
				<div class="print-break"></div>
				
				<?php foreach($workout['exercises'] as $row){ ?>
				

					<h3 style="text-align:center;"><?php echo ucfirst($row['exercise']); ?></h3>


					<div class="form-group">
						<img src="<?php echo BUCKET_PATH.$row['image'] ?>" alt="Exercise Image" style="max-width:80%;">
					</div>

					<div class="form-group">
						<label for="">Description - <?php echo ucfirst($row['description']); ?></label>                                                       
					</div>

					<div class="form-group">
						<label for="">Rest time between sets : <?php echo $row['resttime']; ?> minute(s)</label>
					</div>

					<div class="form-group">
						<label for="">Exercise status : 
							<?php
                if($row['exercise_status'] == 0){
                  echo 'Pending';
                }
                else{
                  echo 'Completed';
                }
              ?>
						</label>
					</div>

					<div class="form-group">
						<table class="table table-bordered" style="/*width:60%*/">
							<thead>
								<tr>
									<th>Set</th>
									<th>Weight</th>
									<th>Reps</th>
									<th>Status</th>
									<th>Take Weight</th>
									<th>Take Reps</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; foreach($row['sets'] as $var){ ?>
									<tr>
										<td><?php echo $i ?> of <?php echo count($row['sets']); ?> </td>
										<td><?php echo $var->value; ?> </td>
										<td><?php echo $var->reps; ?> </td>
										<td>
											<?php
				                if($var->set_status == 0){
				                  echo 'Pending';
				                }
				                else{
				                  echo 'Completed';
				                }
				              ?>
										</td>
										<td><?php echo $var->resultweight; ?> </td>
										<td><?php echo $var->resultreps; ?> </td>
									</tr>
								<?php $i++; } ?>
							</tbody>
						</table>
					</div>

					<div class="print-break"></div>

				<?php } ?>

</div>

<?php } endif; ?>