<div class="container-fluid">
	<div class="row">
		<?php $this->load->view('admin/sidebar'); ?>


		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<?php echo alert(); ?>          
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3>Stripe Pay</h3>
				</div>
				<div class="row">              
					<div class="col-sm-11 col-md-10 main">              
						<?php echo form_open_multipart(cms_current_url(), array('id' => 'payment-form', 'onsubmit' => 'return false;')); ?>
							<div class="form-group">
								<label for="exampleInputEmail1">Card Number</label> 
								<input type="text" class="form-control" name="card_number" id="card_number" value="<?php echo set_value('card_number'); ?>">
								<span class="error"><?php echo form_error('card_number'); ?></span>
							</div> 

							<div class="form-group">
								<label for="exampleInputEmail1">Expiry Month</label> 
								<input type="text" class="form-control" name="exp_month" id="exp_month" value="<?php echo set_value('exp_month'); ?>">
								<span class="error"><?php echo form_error('exp_month'); ?></span>
							</div>  

							<div class="form-group">
								<label for="exampleInputEmail1">Expiry Year</label> 
								<input type="text" class="form-control" name="exp_year" id="exp_year" value="<?php echo set_value('exp_year'); ?>">
								<span class="error"><?php echo form_error('exp_year'); ?></span>
							</div>  

							<div class="form-group">
								<label for="exampleInputEmail1">CVC Number</label> 
								<input type="text" class="form-control" name="cvc_number" id="cvc_number" value="<?php echo set_value('cvc_number'); ?>">
								<span class="error"><?php echo form_error('cvc_number'); ?></span>
							</div>  
							
							<br>
							<input type="submit" id="submit_btn" class="btn btn-primary" value="Submit">
						</form>
					
					</div>
				
				</div>
				
			</div>

			
		</div>
	</div>
</div>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
	
	Stripe.setPublishableKey('pk_test_UaxlWdDIEnrj5yVtxmHkaSbS');

	$(document).on('click', '#submit_btn', function(event){
		event.preventDefault();
		var card_number = $('#card_number').val();
		var exp_month   = $('#exp_month').val();    
		var exp_year    = $('#exp_year').val(); 
		var cvc         = $('#cvc_number').val();
		var flag 				= true;
		var msg 				= '';
		if(card_number==''){      
				msg += 'Please enter Credit Card Number.\n';
				flag = false;       
		}

		if(exp_year==''){      
				msg += 'Please enter Card Expiration Year.\n';
				flag = false;       
		}

		if(exp_month==''){       
				msg += 'Please enter Card Expiration Month.\n';
				flag = false;       
		}

	 	if(cvc==''){       
				msg += 'Please enter CVC number.\n';
				flag = false;       
		}

		if(flag){
			Stripe.createToken({
          number:card_number,
          cvc: cvc,
          exp_month:exp_month,
          exp_year: exp_year
      }, stripeResponseHandler);
		}
		else{
			alert(msg);
		}
	});

	function stripeResponseHandler(status, response) {
      if (response.error) {
          alert(response.error.message);
      } else {
          var form = $("#payment-form");
          var token = response['id'];
          form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
          form.removeAttr('onsubmit');
          form.submit();
      }
  }

</script>
