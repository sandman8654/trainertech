<?php alert(); ?>
<h4>
	Manage Circuit Workouts
	<span style="float: right;">
		<a class="btn btn-blue" href="<?php echo base_url() ?>circuit_workout/add">
			Add new
		</a>
	</span>
</h4>
<table class="table table-striped home-table">
	<thead>
		<tr>                
			<th>Name</th>
			<th>Circuit Workout Notes</th>
			<!-- <th>Exercise</th> -->
			<th>On</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($workout): foreach ($workout as $row) { ?>
		<tr>
			<td><?php echo $row->name ?></td>
			<td>
				<?php $count = get_count('workout_notes',array('workout_id'=>$row->id)) ?>
				<?php if($count): ?>
					<a  href='<?php echo base_url()._INDEX; ?>circuit_workout/notes/<?php echo $row->id; ?>'  >
						<?php echo $count ?>
						<i class="glyphicon glyphicon-book"></i>
					</a>
				<?php else: ?>
					<a href="javascript:void(0)">
						0 <i class="glyphicon glyphicon-book"></i>
					</a>
				<?php endif; ?>
			</td>
			<!-- <td>
				<?php $count = get_count('exercise',array('workout_id'=>$row->id)) ?>
				<?php if($count): ?>
					<a  href='<?php echo base_url()._INDEX; ?>circuit_workout/exercise/<?php echo $row->id; ?>'  >
						<?php echo $count ?>
						<i class="glyphicon glyphicon-tags"></i>
					</a>
				<?php else: ?>
					<a href="javascript:void(0)">
						0 <i class="glyphicon glyphicon-tags"></i>
					</a>
				<?php endif; ?>
			</td> -->
			<td><?php echo date('m/d/Y', strtotime($row->date)); ?></td>                                    
			<td>
				<a href="<?php echo base_url() ?>circuit_workout/edit/<?php echo $row->id ?>"><i class="glyphicon glyphicon-pencil"></i></a>
				&nbsp;&nbsp;&nbsp;
				<a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>circuit_workout/delete/<?php echo $row->id ?>"><i class="glyphicon glyphicon-remove"></i></a>
			</td>
		</tr>               
		<?php } ?>
		<?php else: ?>
			<tr>
				<td colspan="4" align="center"> No circuit workout found. </td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<?php echo $pagination; ?>