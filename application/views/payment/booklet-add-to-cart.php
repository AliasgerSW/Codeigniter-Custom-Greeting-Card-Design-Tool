<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Paypal</title>
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
</head>
<body>

<?php
$paypalsandbox = config_item('paypalsandbox');
$paypalurl = '';
if ($paypalsandbox){
	$paypalurl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
} else {
	$paypalurl = 'https://www.paypal.com/cgi-bin/webscr';
}
?>
<form id="paypal-form" action="<?php echo $paypalurl; ?>" method="post">
  <input type="hidden" name="business" value="<?php echo config_item('paypalemail'); ?>">
  
  <!-- Specify a PayPal Shopping Cart Add to Cart button. -->
  <input type="hidden" name="cmd" value="_cart">
  <input type="hidden" name="add" value="1">
  
  <!-- Specify details about the item that buyers will purchase. -->
  <input type="hidden" name="item_number" value="<?php echo $item_order_id; ?>">
  <input type="hidden" name="item_name" value="Booklet Template">
  <input type="hidden" name="item_price" value="<?php echo $item_price; ?>">
  <input type="hidden" name="description" value="<?php echo $item_descr; ?>">
  <input type="hidden" name="amount" value="<?php echo $item_amount; ?>">
  <input type="hidden" name="currency_code" value="<?php echo config_item('currency_code'); ?>">
  
  <!-- Continue shopping on the current webpage of the merchant site. --> 
  <!-- The below value is replaced when buyers click Add to Cart -->
  
  <input type="hidden" value="abc.123" name="custom">
  <input type="hidden" name="shopping_url" value="<?php echo config_item('base_url'); ?>BookletTemplate">
  <input type="hidden" name="return" value="<?php echo config_item('base_url'); ?>BookletTemplate/paymentresult">
  <input type="hidden" name="rm" value="2">
  <input type="hidden" name="cancel_return" value="">
  <input type="hidden" name="notify_url" value="<?php echo config_item('base_url'); ?>BookletTemplate/notifyurl">
  <!-- Display the payment button. --> 
  <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
</form>
<script>
jQuery(document).ready(function(e) {
    jQuery("#paypal-form").submit();
});
</script>
</body>
</html>
