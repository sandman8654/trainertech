
<?php echo alert(); ?>
		<h3>Workout Details</h3>
				<div class="form-group">
					<a href="javascript:void(0);" onclick="printDiv('print_me');" class="btn btn-primary pull-right print_btn"><i class="glyphicon glyphicon-print"></i> Print</a>
				</div>

				<div class="form-group">
					<img src="<?php echo BUCKET_PATH.$workout['image'] ?>" alt="Workout Image" style="max-width:80%;">
				</div>

				<div class="form-group">
					<label for="">Name - <?php echo ucfirst($workout['name']); ?></label>                                                       
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
				
				<?php foreach($workout['exercises'] as $row){ ?>

					<div class="form-group">
						<label for="">Exercise - <?php echo ucfirst($row['exercise']); ?></label>                                                       
					</div>

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

				<?php } ?>
</div>

<style>
	.print_btn{
		display: none;
	}
</style>

<script>
	$(window).load(function(){
		$(".print_btn").show();
	});

	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;

		window.print();

		document.body.innerHTML = originalContents;
	}
</script>



<div id="print_me">

<style>
div#print_me { display: none; }
@media print{
	div#print_me { display: block; }
	.print-break {page-break-after:always;}
}
</style>

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