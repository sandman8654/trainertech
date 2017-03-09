<?php echo alert(); ?>   
<h4>Add System Image</h4>
<?php echo form_open_multipart(current_url(), array('class'=>'tasi-form')); ?>            
	<div class="form-group">
		<label for="exampleInputEmail1">Image *</label> 
		<input type="file" required name="gallery_image">              
	</div>
	
	<input name="submit" type="submit" class="btn btn-primary" value="Upload">
</form>