<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cart extends CI_Controller {
	private $paypalsetting = array();
    public function __construct() {
        parent::__construct();
		$this->load->model('product_model');
		$this->load->model('content_model');
		loadsetting();
	}

	public function index(){
		$this->showcart();
	}
	
	public function showcart(){
		$pagevalues = getpagevalues('cart', $this);
		$getcategoryinfo = array();
		$this->content_model->showpage($pagevalues);
	}
	
	private function deletefromcart($orderid){
		$deleteexp = array('templateOrderID' => $orderid);
		$this->db_operation->data_delete('template_meta_order', $deleteexp);
		
		$deleteexp = array('templateOrderID' => $orderid);
		$this->db_operation->data_delete('template_order', $deleteexp);
	}
	
	public function add_to_cart($templateUMID, $noofcopies, $colortype = 1){
		$resultMvals = $this->db_operation->select('template_user', '*', ' where templateCUID = ' . $templateUMID);

		$datainsert = array();
		$datainsert['template_id'] = $resultMvals[0]['template_id'];
		$datainsert['user_id'] = $resultMvals[0]['user_id'];
		$datainsert['NoOfPages'] = $resultMvals[0]['NoOfPages'];
		$datainsert['NoOfCopies'] = $noofcopies;
		$datainsert['colortype'] = $colortype;
		$order_id = $this->db_operation->data_insert('template_order', $datainsert);
		
		$resultDvals = $this->db_operation->select('template_meta_user', '*', 'where templateCUID = ' . $templateUMID, 'order by page_no');
		foreach($resultDvals as $resultDval){
			$datainsert = array();
			$datainsert['meta_key'] = $resultDval['meta_key'];
			$datainsert['meta_value'] = $resultDval['meta_value'];
			$datainsert['isfile'] = $resultDval['isfile'];
			$datainsert['page_no'] = $resultDval['page_no'];
			$datainsert['templateOrderID'] = $order_id;
			
			
			$this->db_operation->data_insert('template_meta_order', $datainsert);
		}
		
		if ($colortype == 2){
			$colortype = 'Black & White';
		} else {
			$colortype = 'Color';
		}
		
		$productinfo = $this->product_model->getproduct($resultMvals[0]['template_id']);
		$productprice = $this->product_model->getproductpriceoforder($order_id);

		$productname = $noofcopies . ' ' . $colortype . ' Copies of ' . $productinfo['Name'] . ' having ' . 
			$resultMvals[0]['NoOfPages'] . ' Pages.';

		$data = array(
			   'id'      => 'order_' . $order_id,
			   'orderid' => $order_id,
			   'qty'     => 1,
			   'price'   => $productprice,
			   'name'    => $productname
			);

		$this->cart->insert($data);
		
		$whereexp = array();
		$whereexp['templateOrderID'] = $order_id;
		$updatestring = array('price' => $productprice);
		$this->db_operation->data_update('template_order', $updatestring, $whereexp);

		
		Redirect('cart');
	}
	
	public function update_cart(){
		$cartarray = $_REQUEST;
		foreach($cartarray as $val){
			if (!(is_array($val) && !empty($val) && count($val) > 0 && array_key_exists('qty', $val))) continue;
			if ($val['qty'] <= 0){
				if (!empty($val['orderid']) && $val['orderid'] > 0){
					$this->deletefromcart($val['orderid']);
				}
			}
		}
		$this->cart->update($_REQUEST);
		Redirect('cart');
	}
	
	
	public function payWithSagePay($trns_id){
		require_once (APPPATH.'/libraries/sagepay/SagePay.php');
		
		$countrycode = '';
		$dbvalues = $this->db_operation->select('country', 'countrycode', ' where countryid = ' . $_REQUEST['country']);
		if (!empty($dbvalues) && count($dbvalues) > 0){
			$countrycode = $dbvalues[0]['countrycode'];
		}
		
		$carttotal = $this->cart->total();
		
		$currencycode = get_setting('currencycode');
		$shippingcost = get_setting('shippingpackingcost');
		
		$totalamount = $carttotal + ((float)$shippingcost);

		$sagePay = new SagePay();
		$sagePay->setCurrency($currencycode);
		$sagePay->setAmount($totalamount);
		$sagePay->setDescription('Booklet Orders');
		$sagePay->setBillingSurname($_REQUEST['lastname']);
		$sagePay->setBillingFirstnames($_REQUEST['firstname']);
		$sagePay->setBillingCity($_REQUEST['city']);
		$sagePay->setBillingPostCode($_REQUEST['postcode']);
		$sagePay->setBillingAddress1($_REQUEST['address1']);
		$sagePay->setBillingAddress2($_REQUEST['address2']);
		$sagePay->setBillingCountry($countrycode);
		$sagePay->setDeliverySameAsBilling(); 

		/* Example of using BasketXML */
		$xml = new DOMDocument();
		$basketNode = $xml->createElement("basket");
		$itemNode = $xml->createElement("item");

		$descriptionNode =  $xml->createElement( 'description' );
		$descriptionNode->nodeValue = 'Booklet Order';
		$itemNode -> appendChild($descriptionNode);

		$quantityNode =  $xml->createElement('quantity');
		$quantityNode->nodeValue = '1';
		$itemNode -> appendChild($quantityNode);

		$unitNetAmountNode =  $xml->createElement('unitNetAmount');
		$unitNetAmountNode->nodeValue = $totalamount;
		$itemNode -> appendChild($unitNetAmountNode);

		$unitTaxAmountNode =  $xml->createElement('unitTaxAmount');
		$unitTaxAmountNode->nodeValue = '0';
		$itemNode -> appendChild($unitTaxAmountNode);

		$unitGrossAmountNode =  $xml->createElement('unitGrossAmount');
		$unitGrossAmountNode->nodeValue = $totalamount;
		$itemNode -> appendChild($unitGrossAmountNode);

		$totalGrossAmountNode =  $xml->createElement('totalGrossAmount');
		$totalGrossAmountNode->nodeValue = $totalamount;
		$itemNode -> appendChild($totalGrossAmountNode);

		$basketNode->appendChild( $itemNode );
		$xml->appendChild( $basketNode );

		$sagePay->setBasketXML($xml->saveHTML());
		$success = config_item('base_url') . 'cart/sagesuccess/' . $trns_id;
		$fail = config_item('base_url') . 'cart/sagefailure/' . $trns_id;
		$sagePay->setSuccessURL($success);
		$sagePay->setFailureURL($fail);

		$vendorid = get_setting('sagevendor'); //"thecoffincompan";
		$sagetest =  get_setting('sagetestmode');
		if ($sagetest == 'true'){
			$sagepayurl = 'https://test.sagepay.com/gateway/service/vspform-register.vsp';
		} else {
			$sagepayurl = 'https://live.sagepay.com/gateway/service/vspform-register.vsp';
		}
		?>
		<form method="POST" id="SagePayForm" action="<?php echo $sagepayurl; ?>">
			<input type="hidden" name="VPSProtocol" value= "3.00">
			<input type="hidden" name="TxType" value= "PAYMENT">
			<input type="hidden" name="Vendor" value= "<?php echo $vendorid; ?>">
			<input type="hidden" name="Crypt" value= "<?php echo $sagePay->getCrypt(); ?>">
			<input type="submit" value="Continue to SagePay" style="visibility:hidden">
		</form>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script>
		jQuery(document).ready(function(jQ){
			jQ('#SagePayForm').submit();
		});
		</script>
		<?php
	}

	public function sagesuccess($trnsid){
		require_once (APPPATH.'/libraries/sagepay/SagePay.php');
		$sagePay = new SagePay();
		$response = serialize($_REQUEST);
		$responsedecode = $sagePay->decode($_REQUEST['crypt']);
		
		$datainsert = array();
		$datainsert['VendorTxCode'] = $responsedecode['VendorTxCode'];
		$datainsert['VPSTxId'] = $responsedecode['VPSTxId'];
		$datainsert['Status'] = $responsedecode['Status'];
		$datainsert['StatusDetail'] = $responsedecode['StatusDetail'];
		$datainsert['TxAuthNo'] = $responsedecode['TxAuthNo'];
		$datainsert['AVSCV2'] = $responsedecode['AVSCV2'];
		$datainsert['AddressResult'] = $responsedecode['AddressResult'];
		$datainsert['PostCodeResult'] = $responsedecode['PostCodeResult'];
		$datainsert['CV2Result'] = $responsedecode['CV2Result'];
		$datainsert['GiftAid'] = $responsedecode['GiftAid'];
		$datainsert['3DSecureStatus'] = $responsedecode['3DSecureStatus'];
		$datainsert['CardType'] = $responsedecode['CardType'];
		$datainsert['Last4Digits'] = $responsedecode['Last4Digits'];
		$datainsert['DeclineCode'] = $responsedecode['DeclineCode'];
		$datainsert['ExpiryDate'] = $responsedecode['ExpiryDate'];
		$datainsert['Amount'] = $responsedecode['Amount'];
		$datainsert['BankAuthCode'] = $responsedecode['BankAuthCode'];
		
		$sagepayid = $this->db_operation->data_insert('sagepayment', $datainsert);
		
		$whereexp = array();
		$whereexp['trnsid'] = $trnsid;
		
		$updatestring = array('sagepayid' => $sagepayid);
		$updatestring['paytype'] = 2;
		$updatestring['status'] = 2;
		$this->db_operation->data_update('transaction', $updatestring, $whereexp);
		
		$this->cart->destroy();
		$pagevalues = getpagevalues('pay-success', $this);
		$this->content_model->showpage($pagevalues);
	}
	
	public function sagefailure($trnsid){
		require_once (APPPATH.'/libraries/sagepay/SagePay.php');
		$sagePay = new SagePay();
		$response = serialize($_REQUEST);
		$responsedecode = $sagePay->decode($_REQUEST['crypt']);

		$datainsert = array();
		$datainsert['VendorTxCode'] = $responsedecode['VendorTxCode'];
		$datainsert['VPSTxId'] = $responsedecode['VPSTxId'];
		$datainsert['Status'] = $responsedecode['Status'];
		$datainsert['StatusDetail'] = $responsedecode['StatusDetail'];
		$datainsert['Amount'] = $responsedecode['Amount'];
		$sagepayid = $this->db_operation->data_insert('sagepayment', $datainsert);
		
		$whereexp = array();
		$whereexp['trnsid'] = $trnsid;
		
		$updatestring = array('sagepayid' => $sagepayid);
		$updatestring['paytype'] = 2;
		$updatestring['status'] = 3;
		$this->db_operation->data_update('transaction', $updatestring, $whereexp);
		
		$pagevalues = getpagevalues('pay-success', $this);
		$pagevalues['content'] = 'Sorry your order could not be submitted. Your previous payment has been failed.';
		$pagevalues['title'] = 'Payment failed';
		$this->content_model->showpage($pagevalues);
	}
	
	public function sendpaymentrequest(){
		$orderids = '';
		foreach ($this->cart->contents() as $items) {
			$orderids .= (!empty($orderids) ? ',' : '') . $items['orderid'];
		}
		if (empty($orderids)){ Redirect(); return; }
		
		$datainsert = array(
			'firstname' => $_REQUEST['firstname'],
			'lastname' => $_REQUEST['lastname'],
			'address1' => $_REQUEST['address1'],
			'address2' => $_REQUEST['address2'],
			'postcode' => $_REQUEST['postcode'],
			'city' => $_REQUEST['city'],
			'country' => $_REQUEST['country'],
			'state' => $_REQUEST['state'],
			'email' => $_REQUEST['email'],
			'phone' => $_REQUEST['phone'],
			'paymentmethod' => $_REQUEST['paymentmethod']
		);
		$trns_id = $this->db_operation->data_insert('transaction', $datainsert);
		
		$whereexp = array();
		foreach ($this->cart->contents() as $items) {
			$whereexp['templateOrderID'] = $items['orderid'];
			$updatestring = array('trnsid' => $trns_id);
			$this->db_operation->data_update('template_order', $updatestring, $whereexp);
		}
		
		if ($_REQUEST['paymentmethod'] == 'paypal'){
			self::payWithPaypal($trns_id);
		} else {
			$this->payWithSagePay($trns_id);
		}
	}

	public static function advanceRandomString($length = 16, $UC = true, $LC = true, $N = true, $SC = false){
		$randomString = '';
		//$source = 'abcdefghijklmnopqrstuvwxyz';
		$source = '';
		if ($UC)
			$source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if ($LC)
			$source .= 'abcdefghijklmnopqrstuvwxyz';
		if ($N)
			$source .= '1234567890';
		if ($SC)
			$source .= '|@#~$%()=^*+[]{}-_';
		if ($length > 0) {
			$randomString = "";
			$length1 = $length - 1;
			$input = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');  
			$rand = array_rand($input, 1);
			$source = str_split($source, 1);
			for ($i = 1; $i <= $length1; $i++) {
				$num = mt_rand(1, count($source));
				$randomString1 .= $source[$num - 1];
				$randomString = "{$rand}{$randomString1}";
			}
		}
		return $randomString;
	}
 
	public function getPaymentStatus( $ID = 0 ){
		//echo $ID;die('000');
		$data = $this->db_operation->select('paypalPayment','*'," where pay_id = ".(int)$ID);
		//print_r($data); die;
		return $data[0]['order_status'];
	}
 
 	public function payWithPaypal( $TXNID = 0 ){
		require_once( APPPATH.'/libraries/joomla/joomla.php' );
		$ORDER_DETAIL 		= '';
		$paypalMode 		= get_setting('paypaltestmode');
		$item_name			= 'Booklet Order';
		$carttotal 			= $this->cart->total();
		$order_currency		= get_setting('currencycode');
		$order_payee_email 	= get_setting('paypaluname'); 
		$order_payer_email 	= '';
		$order_status 		= 'INPROGRESS';
		$order_modified 	= date("Y-m-d H:i:s");
		$order_created 		= date("Y-m-d H:i:s");
		$order_unique   	= self::advanceRandomString($length = 48, $UC = true, $LC = true, $N = true, $SC = false);
		$data = array(
						'transactionId'		=> $TXNID,
						'order_amount' 		=> $carttotal,
						'order_currency' 	=> $order_currency,
						'order_payee_email' => $order_payee_email,
						'order_payer_email' => $order_payer_email,
						'order_status' 		=> $order_status,
						'order_modified' 	=> $order_modified,
						'order_created' 	=> $order_created,
						'order_unique' 		=> $order_unique
		);
		$orderID = $this->db_operation->data_insert('paypalPayment', $data);
		if( $orderID ):
			$ORDER_DETAIL = array(
				'order_id' 				=> (int)$orderID,
				'order_unique' 			=> $order_unique,
				'order_transactionId' 	=> $TXNID
			);
			$this->session->set_userdata('ORDER_DETAIL', $ORDER_DETAIL);	
			
			$paypalData['params'] = array(
									'business' 	=> $order_payee_email,
									'currency' 	=> $order_currency, 
									'amount'   	=> $carttotal,
									'location'  => 'IN',
									'custom'   	=> rawurlencode(json_encode($ORDER_DETAIL)),
									'item_name'	=> $item_name,
									'paypalMode'=> $paypalMode,
									'return_url'=> config_item('base_url'). 'cart/returnurl/1',
									'cancel_url'=> config_item('base_url'). 'cart/returnurl/cancel',
									'notify_url'=> config_item('base_url'). 'cart/processIPN'
			);
			$this->load->view('payment/paypal', $paypalData);
		endif;
	}

	public function returnurl( $payment = 0 ){
		
		//$payment_done = $this->input->post("payment_done", TRUE);
		if( $payment == 1 ):
			$this->cart->destroy();
			//print_r($this->session->userdata('ORDER_DETAIL', '')); die;
			$ORDER_DETAIL 		= $this->session->userdata('ORDER_DETAIL');
			//print_r($ORDER_DETAIL);
			//die;
			$this->session->set_userdata('LAST_ORDER_DETAIL', $ORDER_DETAIL);
			$LAST_ORDER_DETAIL 	= $this->session->userdata('LAST_ORDER_DETAIL');
			//print_r($LAST_ORDER_DETAIL);
			$this->session->unset_userdata('ORDER_DETAIL');

			$pagevalues 		= getpagevalues('pay-success', $this);
			//echo $LAST_ORDER_DETAIL['order_id']; die;
			$orderStatus 		= $this->getPaymentStatus( $LAST_ORDER_DETAIL['order_id'] );
			$pagevalues['content'] = 'Thank You. Your Current Booking Payment Status is '.$orderStatus;
			$this->content_model->showpage($pagevalues);
			
		elseif( $payment == 'cancel'):
		
		else:
			// redirect
		endif;
	}
 
	//////////////////////////////////////////////////////////////////////////////////////	
	/////////////          ///        ///          ///        ///    //////  /////////////	
	/////////////  //////  ///  /////////  //////////////  //////  /  /////  /////////////
	/////////////  //////  ///  /////////  //////////////  //////  //  ////  /////////////
	/////////////          ///      /////  ////    //////  //////  ///  ///  /////////////
	/////////////  //////  ///  /////////  //////  //////  //////  ////  //  /////////////
	/////////////  //////  ///  /////////  //////  //////  //////  /////  /  /////////////
	/////////////          ///        ///          ///        ///  //////    /////////////
	//////////////////////////////////////////////////////////////////////////////////////
	public function processIPN(){
		require_once( APPPATH.'/libraries/joomla/joomla.php' );
		//return;
		try { 
			self::addIPNTempEntry();
		} catch ( Exception $e) {
			//// JLog::add('Caught exception(IPN TEMP): ' . $e->getMessage(), // JLog::ALL, 'com_jsystem');
		}
		//////////////////////////////////////////
		$payPalMode = get_setting('paypaltestmode');
		//////////////////////////////////////////
		
		// STEP 1: read POST data
		// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
		// Instead, read raw POST data from the input stream. 
		$raw_post_data 	= file_get_contents('php://input');		
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		  $keyval = explode ('=', $keyval);
		  if (count($keyval) == 2)
			 $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
				$value = urlencode(stripslashes($value)); 
		   } else {
				$value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}
		
		// STEP 2: POST IPN data back to PayPal to validate
		if ( $payPalMode == 1 ) :
			$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
		else :
			$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
		endif;
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		// In wamp-like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set 
		// the directory path of the certificate as shown below:
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		if( !($res = curl_exec($ch)) ) {
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			exit;
		}
		curl_close($ch);
		 
		// STEP 3: Inspect IPN validation result and act accordingly
		if ( strcmp ($res, "VERIFIED") == 0 ) {
			// The IPN is verified, process it:
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process the notification
			// assign posted variables to local variables
			$item_name 			= $_POST['item_name'];
			$item_number 		= $_POST['item_number'];
			$payment_status 	= $_POST['payment_status'];
			$payment_amount 	= $_POST['mc_gross'];
			$payment_currency 	= $_POST['mc_currency'];
			$TXN 				= $_POST['txn_id'];
			$receiver_email 	= $_POST['receiver_email'];
			$payer_email 		= $_POST['payer_email'];
			
			
			/////////////////////////////////////////////////////////////
			self::doIPNOperations();
			/////////////////////////////////////////////////////////////
			
			//if( $payment_status == 'Completed' ):
				//PSB::updatePaymentStatus( $TXN, $receiver_email, $payer_email );
			//endif;
			// IPN message values depend upon the type of notification sent.
			// To loop through the &_POST array and print the NV pairs to the screen:
			foreach($_POST as $key => $value) {
			  echo $key." = ". $value."<br>";
			}
		} else if (strcmp ($res, "INVALID") == 0) {
			// IPN invalid, log for manual investigation
			// echo "The response from IPN was: <b>" .$res ."</b>";
			// JLog::add('Caught INAVLID IPN: The response from IPN was - ' . $res, // JLog::ALL, 'com_jsystem');
			
			$VOID_REASON = 'Invalid IPN - ( ' . $res . ' ) ( Line Number - ' . __LINE__ . ' )';
			self::doIPNVoidEntry($VOID_REASON);
		}		
	}
	
	private static function addIPNTempEntry(){
		$db = JFactory::getDbo();
		$object = new stdClass();
		$object->rawdata = serialize($_REQUEST);
		return $result = $db->insertObject('jorders_ipn_track_temp', $object);
	}
	
	private static function doIPNVoidEntry( $VOID_REASON = 'Void Reason Unknown - Check Raw Data or Hit Raw Data for more Help.'){
		$jInput = new JInput();
		/*
		(`payment_record_id`, `payment_record_type`, `order_id`, `order_unique`, `amount`, `currency`, `tax`, `payer_status`, `payer_email`, `receiver_email`, `payer_id`, `first_name`, `last_name`, `address_status`, `address_name`, `address_street`, `address_country_code`, `address_zip`, `address_city`, `address_state`, `address_country`, `residence_country`, `mc_gross`, `mc_shipping`, `mc_handling`, `mc_currency`, `notify_version`, `custom`, `num_cart_items`, `verify_sign`, `txn_id`, `txn_type`, `transaction_subject`, `payment_gross`, `pending_reason`, `payment_type`, `payment_date`, `payment_status`, ` 	merchant_return_link`, `charset`, `test_ipn`, `auth`, `reason_remark`, `rawdata`, `modified`, `created`, `state`)
		*/

		// Create an object for the record we are going to update.
		$object = new stdClass();

		$customObject = json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		 
		// Must be a valid primary key value.
		
		//$object->payment_record_id		= $jInput->get('payment_record_id', '', 'filter');
		$object->payment_record_type	= $jInput->get('payment_record_type', 'PAYMENT_PAYPAL_STANDARD', 'filter');
		
		$object->payment_order_id		= (int)$customObject->order_id;
		$object->payment_order_unique	= $customObject->order_unique;
		$object->amount					= $jInput->get('mc_gross', 0.00, 'filter');
		$object->currency				= $jInput->get('mc_currency', '', 'filter');
		
		$object->tax 					= $jInput->get('tax', 0.00, 'filter');
		//$object->tax1 				= $jInput->get('tax1', '', 'filter');

		$object->payer_status 			= $jInput->get('payer_status', '', 'filter');
		$object->payer_email 			= $jInput->get('payer_email', '', 'filter');
		$object->receiver_email			= $jInput->get('receiver_email', '', 'filter');

		$object->payer_id 				= $jInput->get('payer_id', '', 'filter');
		$object->first_name 			= $jInput->get('first_name', '', 'filter');
		$object->last_name 				= $jInput->get('last_name', '', 'filter');

		$object->address_status 		= $jInput->get('address_status', '', 'filter');
		$object->address_name 			= $jInput->get('address_name', '', 'filter');
		$object->address_street			= $jInput->get('address_street', '', 'filter');
		$object->address_country_code	= $jInput->get('address_country_code', '', 'filter');
		$object->address_country		= $jInput->get('address_country', '', 'filter');
		$object->address_zip 			= $jInput->get('address_zip', '', 'filter');
		$object->address_city 			= $jInput->get('address_city', '', 'filter');
		$object->address_state 			= $jInput->get('address_state', '', 'filter');
		$object->residence_country 		= $jInput->get('residence_country', '', 'filter');

		$object->mc_gross				= $jInput->get('mc_gross', 0.00, 'filter');
		$object->mc_shipping 			= $jInput->get('mc_shipping', 0.00, 'filter');
		$object->mc_handling 			= $jInput->get('mc_handling', 0.00, 'filter');
		//$object->mc_handling1 		= $jInput->get('mc_handling1', '', 'filter');
		//$object->mc_shipping1 		= $jInput->get('mc_shipping1', '', 'filter');
		//$object->mc_gross_1 			= $jInput->get('mc_gross_1', '', 'filter');
		$object->mc_currency 			= $jInput->get('mc_currency', '', 'filter');

		$object->notify_version 		= $jInput->get('notify_version', 0.0, 'filter');
		$object->custom	 				= $jInput->get('custom', '', 'filter');
		$object->num_cart_items 		= $jInput->get('num_cart_items', 0, 'filter');
		//$object->item_number1 		= $jInput->get('item_number1', '', 'filter');
		//$object->item_name1 			= $jInput->get('item_name1', '', 'filter');
		//$object->quantity1 				= $jInput->get('quantity1', '', 'filter');
		$object->verify_sign 			= $jInput->get('verify_sign', '', 'filter');
		$object->protection_eligibility = $jInput->get('protection_eligibility', '', 'filter');

		$object->txn_id 				= $jInput->get('txn_id', '', 'filter');
		$object->txn_type 				= $jInput->get('txn_type', '', 'filter');
		$object->transaction_subject 	= $jInput->get('transaction_subject', '', 'filter');

		$object->payment_gross 			= $jInput->get('payment_gross', 0.00, 'filter');
		$object->pending_reason			= $jInput->get('pending_reason', '', 'filter');
		$object->payment_type 			= $jInput->get('payment_type', '', 'filter');
		$object->payment_date 			= $jInput->get('payment_date', '', 'filter');
		$object->payment_status			= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');

		$object->merchant_return_link 	= $jInput->get('merchant_return_link', '', 'filter');

		$object->charset 				= $jInput->get('charset', '', 'filter');

		$object->test_ipn 				= $jInput->get('test_ipn', '', 'filter');

		$object->auth					= $jInput->get('auth', '', 'filter');

		$object->reason_remark			= '';
		$object->rawdata				= serialize($_REQUEST);
		$object->modified				= date("Y-m-d H:i:s");
		$object->created				= date("Y-m-d H:i:s");
		$object->hits					= 1;
		$object->hit					= date("Y-m-d H:i:s");
		$object->hitrawdata				= serialize($_REQUEST);
		$object->void_reason			= $VOID_REASON;
		$object->mode					= (int)self::getPayPalMode();
		$object->state					= 1;
		return $result = JFactory::getDbo()->insertObject('jorders_ipn_track_void', $object);
	}
	private static function getPayPalMode(){
		//$plugin   		= JPluginHelper::getPlugin('psbpayment', 'psbpaypal');
		//$pluginParams  	= new JRegistry();
		//$pluginParams->loadString($plugin->params);
		//return $payPalMode 	= (int)$pluginParams->get('psb_paypal_mode', 1);	
		if( get_setting('paypaltestmode') == 'true' ){
			return 1;
		}else{
			return 0;
		}
	}
	private static function debugMode(){
		$plugin   		= JPluginHelper::getPlugin('psbpayment', 'psbpaypal');
		$pluginParams  	= new JRegistry();
		$pluginParams->loadString($plugin->params);
		return $debugMode 	= (int)$pluginParams->get('psb_debug_mode', 1);	
	}
	private static function doIPNOperations(){
		//$debugMode = (int)self::debugMode();
		$debugMode = 1;
		if ( $debugMode == 1 ) : 
			// JLog::add('Notice (IPN): ------- IPN OPERRATIONS BEGIN -------- ', // JLog::ALL, 'com_jsystem');
		endif; 
		$jInput = new JInput();
		//$jInput 			= new JInput();
		//echo '<pre>';
		//print_r($jInput);
		//echo '</pre>';
		//echo $jInput->get('custom', '[]', 'filter');
		$customObject 		= json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		
		//echo '<pre>';
		//print_r($customObject);
		//echo '</pre>';
		
		$IPNPaymentStatus	= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');
		$orderID			= $customObject->order_id;
		$orderUnique		= $customObject->order_unique;
		$orderTransactionId	= $customObject->order_transactionId;
		$order = self::getOrder( $orderID, $orderUnique, $orderTransactionId );
		//print_r($order);
		//die('X');
		if ( $orderID != '' && $orderUnique != '' ) :
			$order = self::getOrder( $orderID, $orderUnique, $orderTransactionId );
			//echo '<pre>';
			//print_r($order);
			//echo '</pre>';
			//die("00000000000");
			if ( $order ) :
				$validateOrderVoid = self::doValidateOrderVoid($order);
				if( $validateOrderVoid === true ) :
					if ( $order->order_status == 'INPROGRESS' && $order->order_status != '' ) :
						if ( self::doCreatePaymentRecord() ) :
							if ( self::doUpdateOrder() ) :
								
								if ( self::doUpdateUserTransaction() ) :
									// USER ACCOUNT UPDATED SUCCESSFULLY
									if ( $debugMode == 1 ) : 
										// JLog::add('Caught exception(IPN): User Account Updated Successfully', // JLog::ALL, 'com_jsystem');
									endif; 
								else :
									if ( $debugMode == 1 ) : 
										// JLog::add('Caught exception(IPN): Error while updating user account', // JLog::ALL, 'com_jsystem');
									endif; 
									// CREATING VOID TRANSACTION ENTRY
									$VOID_REASON = 'Error while updating user account ( Line Number - ' . __LINE__ . ' )';
									self::doIPNVoidEntry($VOID_REASON);
								endif;
								
							else :
								if ( $debugMode == 1 ) : 
									// JLog::add('Caught exception(IPN): Error while updating order', // JLog::ALL, 'com_jsystem');
								endif; 
								// CREATING VOID TRANSACTION ENTRY
								$VOID_REASON = 'Error while updating order ( Line Number - ' . __LINE__ . ' )';
								self::doIPNVoidEntry($VOID_REASON);
							endif;
						else :
							if ( $debugMode == 1 ) : 
								// JLog::add('Caught exception(IPN): Error while create Payment Record', // JLog::ALL, 'com_jsystem');
							endif; 
							// CREATING VOID TRANSACTION ENTRY
							$VOID_REASON = 'Error while create Payment Record ( Line Number - ' . __LINE__ . ' )';
							self::doIPNVoidEntry($VOID_REASON);
						endif;
					else :
						// TO DO - UPDATE  
						if ( $order->order_status == '' ) :
							if ( $debugMode == 1 ) : 
								// JLog::add('Caught exception(IPN): Order Status Missing', // JLog::ALL, 'com_jsystem');
							endif; 
							// CREATING VOID TRANSACTION ENTRY
							$VOID_REASON = 'Order Status Missing ( Line Number - ' . __LINE__ . ' )';
							self::doIPNVoidEntry($VOID_REASON);
						else :
							if ( $order->order_status != $IPNPaymentStatus ) :
								// DIFFERENT PAYMENT STATUS FOUND - UPDATING PAYMENT RECORD
								$paymentRecord = self::getPaymentRecord( $orderID, $orderUnique );
								if ( $paymentRecord ) :
									// PAYMENT RECORD FOUND FOR CURRENT ORDER - UPDATING
									///////////////////////////////////////////////
									// TO UPDATE HITS, HIT TIME, PAYMENT STATUS
									///////////////////////////////////////////////
									if ( self::doUpdatePaymentRecord( $paymentRecordID = (int)$paymentRecord->payment_record_id, $paymentRecordType = 'PAYMENT_PAYPAL_STANDARD' ) ) :
										// PAYMENT RECORD UPDATED SUCCESSFULLY
										if ( $debugMode == 1 ) : 
											// JLog::add('Caught exception(IPN): Payment Record Updated Successfully', // JLog::ALL, 'com_jsystem');
										endif; 
										// UPDATATING ORDER ...
										if ( self::doUpdateOrder() ) :
											// GROUP UPDATED SUCCESSFULLY
											if ( $debugMode == 1 ) : 
												// JLog::add('Caught exception(IPN): Order Updated Successfully', // JLog::ALL, 'com_jsystem');
											endif; 
											// UPDATATING USER ACCOUNT - PREMIUM ACCOUNT STATUS ...
											if ( self::doUpdateUserTransaction() ) :
												// USER ACCOUNT UPDATED SUCCESSFULLY
												if ( $debugMode == 1 ) : 
													// JLog::add('Caught exception(IPN): User Account Updated Successfully', // JLog::ALL, 'com_jsystem');
												endif; 
											else :
												if ( $debugMode == 1 ) : 
													// JLog::add('Caught exception(IPN): Error while updating user account', // JLog::ALL, 'com_jsystem');
												endif; 
												// CREATING VOID TRANSACTION ENTRY
												$VOID_REASON = 'Error while updating user account ( Line Number - ' . __LINE__ . ' )';
												self::doIPNVoidEntry($VOID_REASON);
											endif;
										else :
											if ( $debugMode == 1 ) : 
												// JLog::add('Caught exception(IPN): Error while updating order', // JLog::ALL, 'com_jsystem');
											endif; 
											// CREATING VOID TRANSACTION ENTRY
											$VOID_REASON = 'Error while updating order ( Line Number - ' . __LINE__ . ' )';
											self::doIPNVoidEntry($VOID_REASON);
										endif;
									else :
										if ( $debugMode == 1 ) : 
											// JLog::add('Caught exception(IPN): Error while updating Payment Record', // JLog::ALL, 'com_jsystem');
										endif; 
										// CREATING VOID TRANSACTION ENTRY
										$VOID_REASON = 'Error while updating Payment Record ( Line Number - ' . __LINE__ . ' )';
										self::doIPNVoidEntry($VOID_REASON);
									endif;
								else :
									if ( $debugMode == 1 ) : 
										// JLog::add('Caught exception(IPN): No Previous Payment record found with current Order', // JLog::ALL, 'com_jsystem');
									endif; 
									// NO PAYMENT RECORD FOUND FOR CURRENT ORDER - CREATING
									if ( self::doCreatePaymentRecord() ) :
										// PAYMENT RECORD CREATED SUCCESSFULLY
										// UPDATATING ORDER ...
										if ( $debugMode == 1 ) : 
											// JLog::add('Caught exception(IPN): Payment Record Created Successfully', // JLog::ALL, 'com_jsystem');
										endif; 
										if ( self::doUpdateOrder() ) :
											// GROUP UPDATED SUCCESSFULLY
											if ( $debugMode == 1 ) : 
												// JLog::add('Caught exception(IPN): Order Updated Successfully', // JLog::ALL, 'com_jsystem');
											endif; 
											// UPDATATING USER ACCOUNT - PREMIUM ACCOUNT STATUS ...
											if ( self::doUpdateUserTransaction() ) :
												// USER ACCOUNT UPDATED SUCCESSFULLY
												if ( $debugMode == 1 ) : 
													// JLog::add('Caught exception(IPN): User Account Updated Successfully', // JLog::ALL, 'com_jsystem');
												endif; 
											else :
												if ( $debugMode == 1 ) : 
													// JLog::add('Caught exception(IPN): Error while updating user account', // JLog::ALL, 'com_jsystem');
												endif; 
												// CREATING VOID TRANSACTION ENTRY
												$VOID_REASON = 'Error while updating user account ( Line Number - ' . __LINE__ . ' )';
												self::doIPNVoidEntry($VOID_REASON);
											endif;
										else :
											if ( $debugMode == 1 ) : 
												// JLog::add('Caught exception(IPN): Error while updating order', // JLog::ALL, 'com_jsystem');
											endif; 
											// CREATING VOID TRANSACTION ENTRY
											$VOID_REASON = 'Error while updating order ( Line Number - ' . __LINE__ . ' )';
											self::doIPNVoidEntry($VOID_REASON);
										endif;
									else :
										if ( $debugMode == 1 ) : 
											// JLog::add('Caught exception(IPN): Error while create Payment Record', // JLog::ALL, 'com_jsystem');
										endif; 
										// CREATING VOID TRANSACTION ENTRY
										$VOID_REASON = 'Error while create Payment Record ( Line Number - ' . __LINE__ . ' )';
										self::doIPNVoidEntry($VOID_REASON);
									endif;
								endif;
							else :
								// Order Status Allready Updated
								if ( $debugMode == 1 ) : 
									// JLog::add('Caught exception(IPN): Order Status Allready Up to Date', // JLog::ALL, 'com_jsystem');
								endif; 
								
								// CREATING VOID TRANSACTION ENTRY
								$VOID_REASON = 'Order Status Allready Up to Date ( Line Number - ' . __LINE__ . ' )';
								self::doIPNVoidEntry($VOID_REASON);
	
								//////////////////////////////////////////////////////////////////////////
								// TO DO - UPDATE HITS, HIT TIME AND HIT RAW DATA ONLY - AT PAYMENT RECORD
								//////////////////////////////////////////////////////////////////////////
							endif;
						endif;
					endif;
				else :
					if ( $debugMode == 1 ) : 
						// JLog::add('Caught exception(IPN): Order - Void Validation - Failed( ' . $validateOrderVoid . ' )', // JLog::ALL, 'com_jsystem');
					endif; 
					$VOID_REASON = 'Order - Void Validation - Failed( ' . $validateOrderVoid . ' ) ( Line Number - ' . __LINE__ . ' )';
					self::doIPNVoidEntry($VOID_REASON);
				endif;
			else :
				if ( $debugMode == 1 ) : 
					// JLog::add('Caught exception(IPN): Order Detail Invalid ', // JLog::ALL, 'com_jsystem');
				endif; 
				// CREATING VOID TRANSACTION ENTRY
				$VOID_REASON = 'Order Detail Invalid ( Line Number - ' . __LINE__ . ' )';
				self::doIPNVoidEntry($VOID_REASON);
			endif;
		else :
			if ( $debugMode == 1 ) : 
				// JLog::add('Caught exception(IPN): Order Detail Missing ', // JLog::ALL, 'com_jsystem');
			endif; 
			
			// CREATING VOID TRANSACTION ENTRY
			if (  $orderID == '' && $orderUnique == '' ) :
				$VOID_REASON = 'Order Detail(Order ID and Order Unique) Missing ( Line Number - ' . __LINE__ . ' )';
			elseif ( $orderID == '' ) :
				$VOID_REASON = 'Order Detail(Order ID) Missing ( Line Number - ' . __LINE__ . ' )';
			elseif( $orderUnique == '' ) :
				$VOID_REASON = 'Order Detail(Order Unique) Missing ( Line Number - ' . __LINE__ . ' )';
			else :
				$VOID_REASON = 'Order Detail Missing ( Line Number - ' . __LINE__ . ' )';
			endif;
			self::doIPNVoidEntry($VOID_REASON);
		endif;
		if ( $debugMode == 1 ) : 
			// JLog::add('Notice (IPN): ------- IPN OPERRATIONS END -------- ', // JLog::ALL, 'com_jsystem');
		endif; 
		return true;
	}
	
	private static function doValidateOrderVoid($order) {
		$jInput = new JInput();
		//$jInput 			= new JInput();
		$IPNAmount			= $jInput->get('mc_gross', 0.00, 'filter');
		$IPNCurrency		= $jInput->get('mc_currency', '', 'filter');
		$IPNReceiverEmail	= $jInput->get('receiver_email', '', 'filter');
		
		$voidStatus = false;
		$voidMessages = array();
		///////////////////////////////////////////////////////
		if ( $IPNAmount == '' || $IPNAmount == 0 ) : 
			$voidStatus 	= true;
			$voidMessages[] = 'IPN Amount Missing/Zero ( ' . $IPNAmount . ' )';
		elseif ( $IPNAmount == $order->order_amount || number_format($IPNAmount,2) == number_format($order->order_amount,2) ) : 
			// IPN AMOUNT VALID 			
		else :
			$voidStatus 	= true;
			$voidMessages[] = 'IPN & Order Amount Mismatch ( Order Amount - ' . $order->order_amount . ' and IPN Amount - ' . $IPNAmount . ' )';
		endif;
		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////
		if ( $IPNCurrency == '' ) : 
			$voidStatus 	= true;
			$voidMessages[] = 'IPN Currenncy Missing ( ' . $IPNCurrency . ' )';
		elseif ( $IPNCurrency == $order->order_currency ) : 
			// IPN CURRENCY VALID			
		else :
			$voidStatus 	= true;
			$voidMessages[] = 'IPN & Order Currency Mismatch ( Order Currency - ' . $order->order_currency . ' and IPN Currency - ' . $IPNCurrency . ' )';
		endif;
		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////
		if ( $IPNReceiverEmail == '' ) : 
			$voidStatus 	= true;
			$voidMessages[] = 'IPN Receiver Email/Payee Email Missing ( ' . $IPNReceiverEmail . ' )';
		elseif ( $IPNReceiverEmail == $order->order_payee_email ) : 
			// IPN RECEIVER/PAYEE EMAIL VALID			
		else :
			$voidStatus 	= true;
			$voidMessages[] = 'IPN & Order Receiver Email/Payee Email Mismatch ( Order Payee Email - ' . $order->order_payee_email . ' and IPN Receiver Email - ' . $IPNReceiverEmail . ' )';
		endif;
		///////////////////////////////////////////////////////
		
		if ( $voidStatus ) :
			return implode(', ', $voidMessages);
		else :
			return true;		
		endif;
		
	}
	private static function doCreatePaymentRecord(){
		return self::doSavePayPalTXN();
	}
	private static function doUpdatePaymentRecord( $paymentRecordID = 0, $paymentRecordType = 'PAYMENT_PAYPAL_STANDARD'){
		return self::doUpdatePayPalTXN( $paymentRecordID, $paymentRecordType );
	}
	private static function doUpdatePayPalTXN(  $paymentRecordID = 0, $paymentRecordType = 'PAYMENT_PAYPAL_STANDARD'){
		//$jInput = new JInput();
		 $jInput = new JInput();		
		/*
		(`payment_record_id`, `payment_record_type`, `order_id`, `order_unique`, `amount`, `currency`, `tax`, `payer_status`, `payer_email`, `receiver_email`, `payer_id`, `first_name`, `last_name`, `address_status`, `address_name`, `address_street`, `address_country_code`, `address_zip`, `address_city`, `address_state`, `address_country`, `residence_country`, `mc_gross`, `mc_shipping`, `mc_handling`, `mc_currency`, `notify_version`, `custom`, `num_cart_items`, `verify_sign`, `txn_id`, `txn_type`, `transaction_subject`, `payment_gross`, `pending_reason`, `payment_type`, `payment_date`, `payment_status`, ` 	merchant_return_link`, `charset`, `test_ipn`, `auth`, `reason_remark`, `rawdata`, `modified`, `created`, `state`)
		*/

		// Create an object for the record we are going to update.
		$object = new stdClass();

		$customObject = json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		 
		// Must be a valid primary key value.
		
		$object->payment_record_id		= (int)$paymentRecordID;
		$object->payment_record_type	= $paymentRecordType;
		
		$object->payment_order_id		= (int)$customObject->order_id;
		$object->payment_order_unique	= $customObject->order_unique;
		$object->amount					= $jInput->get('mc_gross', 0.00, 'filter');
		$object->currency				= $jInput->get('mc_currency', '', 'filter');
		
		$object->tax 					= $jInput->get('tax', 0.00, 'filter');
		//$object->tax1 				= $jInput->get('tax1', '', 'filter');

		$object->payer_status 			= $jInput->get('payer_status', '', 'filter');
		$object->payer_email 			= $jInput->get('payer_email', '', 'filter');
		$object->receiver_email			= $jInput->get('receiver_email', '', 'filter');

		$object->payer_id 				= $jInput->get('payer_id', '', 'filter');
		$object->first_name 			= $jInput->get('first_name', '', 'filter');
		$object->last_name 				= $jInput->get('last_name', '', 'filter');

		$object->address_status 		= $jInput->get('address_status', '', 'filter');
		$object->address_name 			= $jInput->get('address_name', '', 'filter');
		$object->address_street			= $jInput->get('address_street', '', 'filter');
		$object->address_country_code	= $jInput->get('address_country_code', '', 'filter');
		$object->address_country		= $jInput->get('address_country', '', 'filter');
		$object->address_zip 			= $jInput->get('address_zip', '', 'filter');
		$object->address_city 			= $jInput->get('address_city', '', 'filter');
		$object->address_state 			= $jInput->get('address_state', '', 'filter');
		$object->residence_country 		= $jInput->get('residence_country', '', 'filter');

		$object->mc_gross				= $jInput->get('mc_gross', 0.00, 'filter');
		$object->mc_shipping 			= $jInput->get('mc_shipping', 0.00, 'filter');
		$object->mc_handling 			= $jInput->get('mc_handling', 0.00, 'filter');
		//$object->mc_handling1 		= $jInput->get('mc_handling1', '', 'filter');
		//$object->mc_shipping1 		= $jInput->get('mc_shipping1', '', 'filter');
		//$object->mc_gross_1 			= $jInput->get('mc_gross_1', '', 'filter');
		$object->mc_currency 			= $jInput->get('mc_currency', '', 'filter');

		$object->notify_version 		= $jInput->get('notify_version', 0.0, 'filter');
		$object->custom	 				= $jInput->get('custom', '', 'filter');
		$object->num_cart_items 		= $jInput->get('num_cart_items', 0, 'filter');
		//$object->item_number1 		= $jInput->get('item_number1', '', 'filter');
		//$object->item_name1 			= $jInput->get('item_name1', '', 'filter');
		//$object->quantity1 				= $jInput->get('quantity1', '', 'filter');
		$object->verify_sign 			= $jInput->get('verify_sign', '', 'filter');
		$object->protection_eligibility = $jInput->get('protection_eligibility', '', 'filter');

		$object->txn_id 				= $jInput->get('txn_id', '', 'filter');
		$object->txn_type 				= $jInput->get('txn_type', '', 'filter');
		$object->transaction_subject 	= $jInput->get('transaction_subject', '', 'filter');

		$object->payment_gross 			= $jInput->get('payment_gross', 0.00, 'filter');
		$object->pending_reason			= $jInput->get('pending_reason', '', 'filter');
		$object->payment_type 			= $jInput->get('payment_type', '', 'filter');
		$object->payment_date 			= $jInput->get('payment_date', '', 'filter');
		$object->payment_status			= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');

		$object->merchant_return_link 	= $jInput->get('merchant_return_link', '', 'filter');

		$object->charset 				= $jInput->get('charset', '', 'filter');

		$object->test_ipn 				= $jInput->get('test_ipn', '', 'filter');

		$object->auth					= $jInput->get('auth', '', 'filter');

		$object->reason_remark			= '';
		$object->rawdata				= serialize($_REQUEST);
		$object->modified				= date("Y-m-d H:i:s");
		//$object->created				= date("Y-m-d H:i:s");
		$object->hits					= 'hits + 1';
		$object->hit					= date("Y-m-d H:i:s");
		$object->hitrawdata				= serialize($_REQUEST);
		//$object->state					= 1;
		return $result = JFactory::getDbo()->updateObject('jorders_payments_provider', $object, 'payment_record_id');
		//return $result = JFactory::getDbo()->insertObject('jorders_payments_provider', $object);
	}
	private static function doUpdateOrder(){
		//$jInput 			= new JInput();
		$jInput = new JInput();
		$customObject 		= json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		$IPNPaymentStatus	= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');
		$IPNPayerEmail		= $jInput->get('payer_email', '', 'filter');

		if ( $customObject->order_id == '' || $customObject->order_unique == '' ) return false;
		
		$db = JFactory::getDbo();		 
		$query = $db->getQuery(true);
		 
		// Fields to update.
		$fields = array(
			$db->quoteName('order_status') . ' = ' . $db->quote($IPNPaymentStatus),
			$db->quoteName('order_payer_email') . ' = ' . $db->quote($IPNPayerEmail),
			$db->quoteName('order_modified') . ' = NOW()'
		);
		 
		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('pay_id') . ' = ' .  (int)$customObject->order_id, 
			$db->quoteName('order_unique') . ' = ' . $db->quote($customObject->order_unique)
		);
		 
		$query->update($db->quoteName('paypalPayment'))->set($fields)->where($conditions);
		 
		$db->setQuery($query);
		 
		return $result = $db->execute();
	}
	private static function getUserTransaction( $trnsId = 0 ){ 
		$db = JFactory::getDbo();		 
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from( $db->quoteName( 'transaction' ));
		$query->where($db->quoteName( 'trnsid' ) . ' = ' . (int)$trnsId );
		$db->setQuery($query);
		return $db->loadObject();	
	}
	private static function userHasTransaction( $trnsId = 0 ){ 
		if ( $trnsId == 0 || $trnsId == '' ) return false;
		
		$userTransaction			= self::getUserTransaction( (int)$trnsId );
		$userTransactionCompleted 	= (int)$userTransaction->status;
		
		if ( $userTransactionCompleted === 2 ) :
			return true;
		else :
			return false;
		endif; 
	}
	private static function getPremiumAccountValidity( $order = '' ){ 
		if ( $order == '' ) return false;
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userOrderVilidityDuration		= (int)abs($order->order_duration);
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userOrderVilidityDurationMDY	= strtolower( $order->order_duration_mdy );
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$nowDatetime					= date('Y-m-d H:i:s');
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$futureDate=date('Y-m-d', strtotime('+1 year', strtotime($startDate)) );
		$increaseDatetimeBy				= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
		return $futureDatetime 			= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*******************************************************************************************************
		if (  $userOrderVilidityDurationMDY == 'year' ) :
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		elseif( $userOrderVilidityDurationMDY  == 'month'):
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		elseif( $userOrderVilidityDurationMDY  == 'day'):
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		endif;					
		********************************************************************************************************/
		////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	private static function XuserHasTransaction(){ 
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userHasPlanActivated 			= (int)$userProfile->sp_user_plan;
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userPlanVilidity 				= $userProfile->sp_user_plan_validity;
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userOrderVilidityDuration		= (int)abs($order->order_duration);
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userOrderVilidityDurationMDY	= strtolower( $order->order_duration_mdy );
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$nowDatetime					= date('Y-m-d H:i:s');
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$futureDate=date('Y-m-d', strtotime('+1 year', strtotime($startDate)) );
		$increaseDatetimeBy				= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
		$futureDatetime 				= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*******************************************************************************************************
		if (  $userOrderVilidityDurationMDY == 'year' ) :
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		elseif( $userOrderVilidityDurationMDY  == 'month'):
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		elseif( $userOrderVilidityDurationMDY  == 'day'):
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		endif;					
		********************************************************************************************************/
		////////////////////////////////////////////////////////////////////////////////////////////////////////

		//strtotime($datetimeStr);
		$userHasPlanVilidity = 0;
		if ( $userPlanVilidity > $userOrderVilidity ) :
			$userHasPlanVilidity = 1;
		endif;
		
		if ( $userHasPlanActivated === 1 && $userHasPlanVilidity === 1) :
			return true;
		endif;
	}
	private static function doUpdateUserTransaction(){
		//$jInput 			= new JInput();
		$jInput = new JInput();
		$customObject 		= json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		$IPNPaymentStatus	= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');
		$IPNPayerEmail		= $jInput->get('payer_email', '', 'filter');

		$orderID			= $customObject->order_id;
		$orderUnique		= $customObject->order_unique;
		$orderTransactionId	= $customObject->order_transactionId;
		 
		if ( $orderID == '' || $orderUnique == '' ) return false;
		$order = self::getOrder( $orderID, $orderUnique, $orderTransactionId );
		
		if( $order ) :		
			switch ( $IPNPaymentStatus ) :
				case 'Pending' : 
						$activateTransaction = self::activateTransaction ( $orderTransactionId, 1 );
						if( $activateTransaction ) :
							return true;
							// UPDATE SUCCESSFULL
						else :
							return false;
							// ERROR WHILE UPDATE 						
						endif;
				 	break;
					
				case 'Completed' : 
					if ( self::userHasTransaction( $orderTransactionId )) :
						return true;
						// ALLREADY UP TO DATE	
					else :
						$activateTransaction = self::activateTransaction ( $orderTransactionId, 2 );
						if( $activateTransaction ) :
							return true;
							// UPDATE SUCCESSFULL
						else :
							return false;
							// ERROR WHILE UPDATE 						
						endif;
					endif;
				 	break;
				
				case 'Refunded' : 
					if ( self::userHasTransaction( $orderTransactionId )) :
						$activateTransaction = self::activateTransaction ( $orderTransactionId, 3 );
						if( $activateTransaction ) :
							return true;
							// UPDATE SUCCESSFULL
						else :
							return false;
							// ERROR WHILE UPDATE 						
						endif;
					else :
						return true;
						// ALLREADY UP TO DATE	
					endif;
				 	break;
				case '':
					return false;
					// MISSING
					break;	
				default:
					return true;
					break;
			endswitch;
		else:
			return false;
			// INVALID ORDER
		endif;
	}
	
	private static function activateTransaction ( $trnsID = 0, $activate = 1 ) {
		if ( (int)$trnsID == 0 ) return false;
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$fields = array(
			$db->quoteName('status') . ' = ' . (int)$activate,
			$db->quoteName('paytype') . ' = 1'
		);
		$conditions = array(
			$db->quoteName('trnsid') . ' = ' .  (int)$trnsID
		);
		 
		$query->update($db->quoteName('transaction'))->set($fields)->where($conditions);
		$db->setQuery($query);
		return $result = $db->execute();
	}
	
	private static function getOrder( $orderID = 0, $orderUnique = '', $orderTransactionId = 0 ){
		if( $orderID == '' || $orderID == 0 || $orderUnique == '' ) return false;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('op.*');
		$query->from($db->quoteName( 'paypalPayment', 'op'));
		$query->where($db->quoteName('op.pay_id') . ' = ' . (int)$orderID );
		$query->where($db->quoteName('op.order_unique') . ' = ' . $db->quote($orderUnique) );
		$query->where($db->quoteName('op.transactionId') . ' = ' . $db->quote($orderTransactionId) );
		$db->setQuery($query);
		return $result = $db->loadObject();
	}	
	public function testLog() {
		//print_r(// JLog);
		echo // JLog::add('Caught exception(TEST LOG): ', // JLog::ALL, 'com_jsystem');
		die('DIE');
	}
	private static function getPaymentRecord( $orderID = 0, $orderUnique = '' ){
		if( $orderID == '' || $orderID == 0 || $orderUnique == '' ) return false;

		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('opp.*');
		$query->from($db->quoteName( 'jorders_payments_provider', 'opp') );
		$query->where($db->quoteName('opp.payment_order_id') . ' = ' . (int)$orderID );
		$query->where($db->quoteName('opp.payment_order_unique') . ' = ' . $db->quote($orderUnique) );
		$query->where($db->quoteName('opp.state') . ' = 1 ' );
		
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		return $result = $db->loadObject();
	}
	
	private static function doSavePayPalTXN(){ 
		//$jInput = new JInput();
		$jInput = new JInput();
		$object = new stdClass();
		$customObject = json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		$object->payment_record_type	= $jInput->get('payment_record_type', 'PAYMENT_PAYPAL_STANDARD', 'filter');
		$object->payment_order_id		= (int)$customObject->order_id;
		$object->payment_order_unique	= $customObject->order_unique;
		$object->amount					= $jInput->get('mc_gross', 0.00, 'filter');
		$object->currency				= $jInput->get('mc_currency', '', 'filter');
		$object->tax 					= $jInput->get('tax', 0.00, 'filter');
		$object->payer_status 			= $jInput->get('payer_status', '', 'filter');
		$object->payer_email 			= $jInput->get('payer_email', '', 'filter');
		$object->receiver_email			= $jInput->get('receiver_email', '', 'filter');
		$object->payer_id 				= $jInput->get('payer_id', '', 'filter');
		$object->first_name 			= $jInput->get('first_name', '', 'filter');
		$object->last_name 				= $jInput->get('last_name', '', 'filter');
		$object->address_status 		= $jInput->get('address_status', '', 'filter');
		$object->address_name 			= $jInput->get('address_name', '', 'filter');
		$object->address_street			= $jInput->get('address_street', '', 'filter');
		$object->address_country_code	= $jInput->get('address_country_code', '', 'filter');
		$object->address_country		= $jInput->get('address_country', '', 'filter');
		$object->address_zip 			= $jInput->get('address_zip', '', 'filter');
		$object->address_city 			= $jInput->get('address_city', '', 'filter');
		$object->address_state 			= $jInput->get('address_state', '', 'filter');
		$object->residence_country 		= $jInput->get('residence_country', '', 'filter');
		$object->mc_gross				= $jInput->get('mc_gross', 0.00, 'filter');
		$object->mc_shipping 			= $jInput->get('mc_shipping', 0.00, 'filter');
		$object->mc_handling 			= $jInput->get('mc_handling', 0.00, 'filter');
		$object->mc_currency 			= $jInput->get('mc_currency', '', 'filter');
		$object->notify_version 		= $jInput->get('notify_version', 0.0, 'filter');
		$object->custom	 				= $jInput->get('custom', '', 'filter');
		$object->num_cart_items 		= $jInput->get('num_cart_items', 0, 'filter');
		$object->verify_sign 			= $jInput->get('verify_sign', '', 'filter');
		$object->protection_eligibility = $jInput->get('protection_eligibility', '', 'filter');
		$object->txn_id 				= $jInput->get('txn_id', '', 'filter');
		$object->txn_type 				= $jInput->get('txn_type', '', 'filter');
		$object->transaction_subject 	= $jInput->get('transaction_subject', '', 'filter');
		$object->payment_gross 			= $jInput->get('payment_gross', 0.00, 'filter');
		$object->pending_reason			= $jInput->get('pending_reason', '', 'filter');
		$object->payment_type 			= $jInput->get('payment_type', '', 'filter');
		$object->payment_date 			= $jInput->get('payment_date', '', 'filter');
		$object->payment_status			= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK','filter');
		$object->merchant_return_link 	= $jInput->get('merchant_return_link', '', 'filter');
		$object->charset 				= $jInput->get('charset', '', 'filter');
		$object->test_ipn 				= $jInput->get('test_ipn', '', 'filter');
		$object->auth					= $jInput->get('auth', '', 'filter');
		$object->reason_remark			= '';
		$object->rawdata				= serialize($_REQUEST);
		$object->modified				= date("Y-m-d H:i:s");
		$object->created				= date("Y-m-d H:i:s");
		$object->hits					= 1;
		$object->hit					= date("Y-m-d H:i:s");
		$object->hitrawdata				= serialize($_REQUEST);
		$object->mode					= (int)self::getPayPalMode();
		$object->state					= 1;
		return $result = JFactory::getDbo()->insertObject('jorders_payments_provider', $object);
	}
	//////////////////////////////////////////////////////////////////////////////////////	
	////////////////////////          ///    //////  ///          ////////////////////////
	////////////////////////  ///////////  /  /////  ///  //////  ////////////////////////
	////////////////////////  ///////////  //  ////  ///  //////  ////////////////////////
	////////////////////////       //////  ///  ///  ///  //////  ////////////////////////
	////////////////////////  ///////////  ////  //  ///  //////  ////////////////////////
	////////////////////////  ///////////  /////  /  ///  //////  ////////////////////////
	////////////////////////          ///  //////    ///          ////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////	

}