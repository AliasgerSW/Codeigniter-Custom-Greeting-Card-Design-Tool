<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Paypal</title>
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
</head>
<body>

<?php
if( !empty($params) ):
	$business 	= $params['business'];
	$currency 	= $params['currency'];
	$amount 	= $params['amount'];
	$location 	= $params['location'];
	$custom 	= $params['custom'];
	$return_url = $params['return_url'];
	$cancel_url = $params['cancel_url'];
	$notify_url = $params['notify_url'];
	$paypalSandboxMode = $params['paypalMode'];
	$item_name = $params['item_name'];
	
	if( $paypalSandboxMode ){
		$paypalurl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	} else {
		$paypalurl = 'https://www.paypal.com/cgi-bin/webscr';
	}
	?>
	<div style="height:200px; text-align:center; padding-top:90px">
		<span style=" color: #006EB9; font-size:16px; text-align:center"><span class="counter">10</span> Please Be Patience.. While We Redirect You to Payment Gateway !!</span>
	</div>
	<form action="<?php echo $paypalurl; ?>" method="post" id="paypal_checkout" style="display:none;">
		<input name = "cmd" 			value = "_cart" 					type = "hidden" />
		<input name = "upload" 			value = "1" 						type = "hidden" />
		<input name = "no_note" 		value = "0" 						type = "hidden" />
		<input name = "bn" 				value = "PP-BuyNowBF" 				type = "hidden" />
		<input name = "tax" 			value = "0" 						type = "hidden" />
		<input name = "rm" 				value = "2" 						type = "hidden" />
		<input name = "business" 		value = "<?php echo $business ;?>"		type = "hidden" />
		<input name = "handling_cart" 	value = "0" 	type = "hidden" />
		<input name = "currency_code" 	value = "<?php echo $currency ;?>"		type = "hidden" />
		<input name = "lc"				value = "<?php echo $location ;?>"		type = "hidden" />
		<input name = "return"			value = "<?php echo $return_url ;?>"		type = "hidden" />
		<input name = "cbt" 			value = ""		type = "hidden" />
		<input name = "cancel_return" 	value = "<?php echo $cancel_url ;?>" 		type = "hidden" />
		<input name = "notify_url" 		value = "<?php echo $notify_url ;?>" 		type = "hidden" />
		<input name = "custom" 			value = "<?php echo $custom ;?>"		type = "hidden" />
		<div id = "item_1" class = "itemwrap">
			<input name = "item_name_1" 	value = "<?php echo $item_name ;?>" 	type = "hidden">
			<input name = "quantity_1" 		value = "1" type = "hidden">
			<input name = "amount_1" 		value = "<?php echo $amount; ?>"	type = "hidden">
			<input name = "shipping_1" 		value = "0" type = "hidden">
			<input name = "currency_code1" 	value = "<?php echo $currency ;?>"	type = "hidden">
		</div>
		<input id = "ppcheckoutbtn" value = "Checkout" class = "button" type = "submit">
	</form>
	
	<script>
		jQuery(document).ready(function(jQ){
			window.counter = 9;
			var interval = setInterval(function(){	
				jQ('.counter').html(window.counter);
			   
				if(window.counter == 0) { 
					clearInterval(interval); 
					jQ('#paypal_checkout').submit();
				}
				 window.counter --;
			},1000);
		});
	</script>

<?php endif; ?>
</body>
</html>
