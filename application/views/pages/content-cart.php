<!-----(slider-2)----->

<div class="slider-banner">
  <h2 class="content"><?php echo $title; ?></h2>
</div>
<div class="clearfix"></div>
<!-----(title)----->
<div class="container">
  <div class="default-heading-content">
    <div class="row no-margin"> 
      <!-- set document page code-->
      
      <div class="col-xs-12 col-sm-8 col-md-8"> 
        <?php $currencysign = get_setting('currencysign'); $shipping = get_setting('shippingpackingcost'); ?>
        <div class="padtop-56"></div>
      	<?php if ($this->cart->total_items() > 0) { ?>
	  	<?php echo form_open(base_url(). 'cart/update_cart', array('id'=>'cart-form')); ?>
        <div class="cart_table">
        <table cellpadding="6" cellspacing="1" style="width:100%; margin:5px 0px 0;" border="0">
          <tr>
            <th></th>
            <th class="cart_page">Item Description</h3></th>
            <th style="text-align:right; font-size: 16px; font-weight: 600px;">Item Price</th>
            <th style="text-align:right; font-size: 16px; font-weight: 600px;">Sub-Total</th>
            <th style="text-align:right; font-size: 16px; font-weight: 600px;">Remove</th>
          </tr>
          <?php $i = 1; ?>
          <?php foreach ($this->cart->contents() as $items): ?>
          <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
          <?php echo form_hidden($i.'[orderid]', $items['orderid']); ?>
          <tr class="product">
            <td><?php echo form_input(array('name' => $i.'[qty]', 'type' => 'hidden', 'value' => $items['qty'], 'class' => 'cart-qty')); ?></td>
            <td><?php echo $items['name']; ?>
              <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
              <p>
                <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
                <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
                <?php endforeach; ?>
              </p>
              <?php endif; ?></td>
            <td style="text-align:right"><?php echo $this->cart->format_number($items['price']); ?></td>
            <td style="text-align:right"><?php echo $currencysign . $this->cart->format_number($items['subtotal']); ?></td>
            <td style="text-align:right"><input type="submit" class="cancel btn btn-default" value="X" /></td>
          </tr>
          <?php $i++; ?>
          <?php endforeach; ?>
          <tr style="border-top:1px solid #cccccc;">
            <td colspan="2"></td>
            <td class="right"><strong>Cart Total</strong></td>
            <td class="right"><?php echo $currencysign . $this->cart->format_number($this->cart->total()); ?></td>
          </tr>
          <?php if (!empty($shipping)){ ?>
          <?php
		  $totalamt = $this->cart->total() + ((float)$shipping);
		  ?>
		  <tr style="border-top:1px solid #cccccc;">
            <td colspan="2"></td>
            <td class="right"><strong>With Shipping Cost : </strong></td>
            <td class="right"><?php echo $currencysign . $this->cart->format_number($shipping); ?></td>
          </tr>
		  <tr style="border-top:1px solid #cccccc;">
            <td colspan="2"></td>
            <td class="right"><strong>Total Amount : </strong></td>
            <td class="right"><?php echo $currencysign . $this->cart->format_number($totalamt); ?></td>
          </tr>          
          <?php } ?>
        </table>
        </div>
        </form>
        <div class="padtop-15"></div>
        <a class="btn btn-default" href="<?php echo config_item('base_url'); ?>product/allproducts">Continue shopping</a>
        <div class="shipping-address">
          <h3 class="cart_title">Enter Shipping Address</h3>
          <form action="<?php echo config_item('base_url'); ?>cart/sendpaymentrequest" method="post">
            <table>
              <tbody>
                <tr>
                  <td>Company<br>
                    <input type="text" value="" name="company"></td>
                </tr>
                <tr>
                  <td>First Name <span class="required">*</span><br>
                    <input type="text" value="" name="firstname" required></td>
                  <td>Last Name <span class="required">*</span><br>
                    <input type="text" value="" name="lastname" required></td>
                </tr>
                <tr>
                  <td>Address 1 <span class="required">*</span><br>
                    <input type="text" name="address1" value="" required></td>
                  <td>Address 2<br>
                    <input type="text" value="" name="address2"></td>
                </tr>
                <tr>
                  <td>Postcode <span class="required">*</span><br>
                    <input type="text" value="" name="postcode" required></td>
                  <td>City <span class="required">*</span><br>
                    <input type="text" value="Indore" name="city" required></td>
                </tr>
                <tr>
                  <td>Country <span class="required">*</span><br>
                    <select name="country" required>
                    <option value="">Select</option>
                    <?php
					$countries = getcountries();
					foreach($countries as $country){
						echo '<option value=' . $country['countryid'] . '>' . $country['countryname'] . '</option>';
					}
					?>
                    </select>
                    </td>
                  <td>Zone/State/Province <span class="required">*</span><br>
                    <input type="text" name="state" value="" required></td>
                </tr>
                <tr>
                  <td width="50%">Email <span class="required">*</span><br>
                    <input type="email" value="" name="email" required></td>
                  <td>Phone <span class="required">*</span><br>
                    <input type="tel" value="" name="phone" required></td>
                </tr>
                <tr>
                  <td width="50%">Payment from <span class="required">*</span><br>
                    <select name="paymentmethod" required>
                    <option value="paypal" selected="selected">Paypal</option>
                    <option value="sage">Sage</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td colspan="2"><input type="submit" name="payment" class="btn btn-default" value="Proceed to Payment"></td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
        <?php } else { ?>
        <h3>Oops!! No item present in your cart.</h3> 
        <p>Click <a href="<?php echo config_item('base_url'); ?>product/allproducts">here</a> for continue shopping.</p>
        <?php } ?>
      </div>
      
      <!------Side Bar----->
      <div class="col-xs-12 col-sm-4 col-md-4">
        <?php $this->load->view('pages/sidebar.php'); ?>
      </div>
      <!--------------------End--> 
    </div>
    <!--end set document page code--> 
    
    <?php echo $content; ?> </div>
</div>
<script>
jQuery(document).ready(function(){
jQuery(".cancel").click(function(e){
	jQuery(this).parent('td').parent('tr').find('.cart-qty').first().val(0);
  });
}); 
</script> 
