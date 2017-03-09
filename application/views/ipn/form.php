<div class="span12" align="center" style="min-height:480px; padding-top:10%;"> 
<form style="display:none;" id="paypal_form" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
    
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="upload" value="1">
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="cbt" value="Return to SportsApp">
    <input type="hidden" name="invoice" value="<?php echo $invoice; ?>">
    <input type="hidden" name="business" value="merchant@sportsapp.com">
    <input type="hidden" name="return" value="<?php echo base_url(); ?>home/success/<?php echo $invoice ?>" />
    <input type="hidden" name="cancel_return" value="<?php echo base_url(); ?>home/cancel/<?php echo $invoice; ?>" />
    <input type="hidden" name="notify_url" value="<?php echo base_url(); ?>ipn/handler/<?php echo $invoice; ?>">
    <input type="hidden" name="email" value="<?php echo $user_email; ?>">
    <input type="hidden" name="first_name" value="<?php echo $user_name; ?>">
    <input type="hidden" name="item_name_1" value="Plan Name" />
    <input type="hidden" name="amount_1" value="<?php echo round($amount); ?>"/>
    <input type="hidden" name="quantity_1" value="1"/>
    <input type="hidden" name="currency_code" value="USD">

    <button class="btn btn-large" type="submit">Pay $<?php echo round($amount); ?></button>
</form>

</div>
<div class="container"></div>
<script>
$(document).ready(function(){
    $('#paypal_form').submit();
});
</script>