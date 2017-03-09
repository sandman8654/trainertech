<header class="contact">
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<h1>GET IN TOUCH WITH <br>TURN KEY INVESTMENTS</h1>
			</div>
			<div class="col-sm-2 text-center">
				<i class="icon-envelope"></i>
				<h5>REQUEST MORE INFORMATION</h5>
				<div class="border-horizontal"></div>
				<a href="mailto:info@turnkey.com">info@turnkey.com</a>
			</div>
			<div class="col-sm-2 text-center">
				<i class="icon-phone"></i>
				<h5>CALL US</h5>
				<div class="border-horizontal"></div>
				<a href="callto:123.456.789">123.456.789</a><br>
				<a href="callto:123.456.789">123.456.789</a>
			</div>
		</div>
	</div>
</header>

<section class="cont-form">
	<div class="container">
		<?php if(validation_errors()): ?>
		<div class="alert alert-danger">
			<p>Please correct the following.</p>
			<?php if(form_error('fname')) echo "<p>&#9679; First Name</p>"; ?>
			<?php if(form_error('lname')) echo "<p>&#9679; Last Name</p>"; ?>
			<?php if(form_error('email')) echo "<p>&#9679; Email Address</p>"; ?>
			<?php if(form_error('city')) echo "<p>&#9679; City</p>"; ?>
			<?php if(form_error('state')) echo "<p>&#9679; State</p>"; ?>                    
			<?php if(form_error('address')) echo "<p>&#9679; Address</p>"; ?>                    
			<?php if(form_error('information')) echo "<p>&#9679; What would you like more information on?</p>"; ?>                    
		</div>
		<?php endif; ?><br>
		<?php if($this->session->flashdata('success_msg')): ?>
			<div class="alert alert-success">
				<p><?php echo $this->session->flashdata('success_msg'); ?></p>
			</div>
		<?php endif; ?>
		<?php echo form_open(base_url().'contactus/index'); ?>
			<div class="form-group row">
				<div class="col-sm-6">
					<label for="exampleInputEmail1">What would you like more information on?</label>
					<select type="email" class="form-control" name="information" required>
						<option value="">-Select-</option>
						<?php if ($services): ?>
							<?php foreach ($services as $row): ?>
								<option <?php if($service_slug == $row->slug){ echo 'selected="selected"'; } ?> value="<?php echo $row->title ?>"><?php echo $row->title ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>

				<div class="col-sm-6">
					<label for="exampleInputEmail1">Address</label>
					<input type="text" name="address" class="form-control" value='<?php echo set_value("address") ?>' required>
				</div>
				
			</div>
			
			<div class="form-group row">
				<div class="col-sm-6">
					<label for="exampleInputEmail1">First Name</label>
					<input type="text" name="fname" class="form-control" value='<?php echo set_value("fname") ?>' required>
				</div>

				<div class="col-sm-2">
					<label for="exampleInputEmail1">City</label>
					<input name="city" type="text" class="form-control" value='<?php echo set_value("city") ?>' required>
				</div>
				<div class="col-sm-2">
					<label for="exampleInputEmail1">State</label>
					<input name="state" type="text" class="form-control" value='<?php echo set_value("state") ?>' required>
				</div>
				<div class="col-sm-2">
					<label for="exampleInputEmail1">Zip</label>
					<input type="text" name="zip" class="form-control" value='<?php echo set_value("zip") ?>' required>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-sm-6">
					<label for="exampleInputEmail1">Last Name</label>
					<input type="text" name="lname" class="form-control" value='<?php echo set_value("lname") ?>' required>
				</div>

				<div class="col-sm-6">
					<label for="exampleInputEmail1">Email Address</label>
					<input type="email" name="email" class="form-control" value='<?php echo set_value("email") ?>' required>
				</div>
				
			</div>

			<div class="form-group row">
				<div class="col-sm-6">
					<label for="exampleInputEmail1">Phone</label>
					<input name="phone" id="phone" type="text" class="form-control" value='<?php echo set_value("phone") ?>' maxlength="20">
				</div>
				<div class="col-sm-6">
					<button class="btn lern-more pull-right">SUBMIT</button>
				</div>
			</div>
		</form>
	</div>
</section>

<script>
	$(document).on( 'change' , '#phone', function(){
	  var input = $('#phone').val();
	  input = input.replace('-', '');
	  input = input.replace(' ', '');
	  input = input.replace('(', '');
	  input = input.replace(')', '');
	  var areaCode = input.substring(0, 3);
	  var exchange = input.substring(3, 6);
	  var tail = input.substring(6);
	  var length = input.length;
	  if(length <= 3)
		$('#phone').val('( '+areaCode+' )');
	  else if(length <= 6)
		$('#phone').val('( '+areaCode+' )' + " " + exchange);
	  else
		$('#phone').val('( '+areaCode+' )' + " " + exchange + " - " + tail);
	});
</script>