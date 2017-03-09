<?php alert(); ?>       
<h4>
	System Images
	<span style="float: right;">
		<a class="btn btn-blue" href="<?php echo base_url() ?>trainer_gallery/add">
			Add new
		</a>
	</span>
</h4>

<table class="table table-striped home-table">
	<thead>
		<tr>
			<th>Image</th>
			<th>Created</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($gallery): foreach ($gallery as $row) { ?>
		<tr>
			<td><img src="<?php echo BUCKET_PATH.$row->image ?>" alt="" width="100px"></td>
			<td><?php echo date('m/d/Y', strtotime($row->created)); ?></td>
			<td>
				&nbsp;&nbsp;&nbsp;
				<a onclick="return confirm('Are you sure?')" href="<?php echo base_url() ?>trainer_gallery/delete/<?php echo $row->id ?>"><i class="glyphicon glyphicon-remove"></i></a>
			</td>
		</tr>
		<?php  } endif ?>
	</tbody>
</table>
<?php echo $pagination; ?>