<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();  
		$this->load->helper(array('url','common_helper'));
		$this->load->library(array('session' ,'form_validation','grocery_CRUD' ));
		$this->load->model('Grocery_crud_model');
		/* user login check */
		user_check(); 
    }

	public function index() {
		$pendingorders = $this->db_operation->selectSQL(
'SELECT tm.template_name, u.username, t.city, t.address1, t.address2, t.country, torder.* FROM template_order as torder 
	join template_master as tm on(tm.template_id = torder.template_id) 
	join users u on(u.user_id = torder.user_id) 
	join transaction as t on(t.trnsid = torder.trnsid and t.status=2) where torder.order_status_id = 0');
		$data['order_detail'] = $pendingorders;
		$todaysales = 0;
		$dbvalues = $this->db_operation->selectSQL('Select count(*) as todaysales from template_order where date(timestamp) = date(Now())');
		if (!empty($dbvalues) and count($dbvalues) > 0){
			$todaysales = $dbvalues[0]['todaysales'];
		}
		
		$totalsales = 0;
		$dbvalues = $this->db_operation->selectSQL('Select count(*) as totalsales from template_order');
		if (!empty($dbvalues) and count($dbvalues) > 0){
			$totalsales = $dbvalues[0]['totalsales'];
		}
		
		$totalearnings = 0;
		$dbvalues = $this->db_operation->selectSQL('Select sum(price) as totalearnings from template_order');
		if (!empty($dbvalues) and count($dbvalues) > 0){
			$totalearnings = $dbvalues[0]['totalearnings'];
		}
		
		$data['today_sales'] = $todaysales;
		$data['total_sales'] = $totalsales;
		$data['total_earnings'] = $totalearnings;

		
		$this->load->view('admin/dashboard',$data);
	}
	
	public function _example_output($output = null) {
        $this->load->view('admin/setting' , $output);
    }

	public function users() {
		$crud = new grocery_CRUD();
		$crud->unset_print();
		$crud->set_table('users');
		$crud->set_subject('User');
		$page_title = array('page_title' => 'User Details');
		$crud->columns('username','email','user_contact','firstname','lastname','status');
		$crud->display_as('username' , 'Name')
			 ->display_as('firstname' , 'First Name')
			 ->display_as('lastname' , 'Last Name')
			 ->display_as('email' , 'Email')
			 ->display_as('user_contact' , 'Contact')
			 ->display_as('user_address' , 'Address')
			 ->display_as('user_city' , 'City');

		$crud->required_fields('username', 'firstname', 'lastname', 'email', 'user_contact', 'user_address', 'user_city', 'status');
		$crud->callback_after_insert(array($this, 'user_after_insert'));
		$crud->callback_after_update(array($this, 'user_after_update'));
		$crud->unset_jquery();
		$crud->unset_jquery_ui();                    
		$output = $crud->render();
		$outputData = array_merge((array) $output , $page_title);
		$this->_example_output($outputData);
	}

	public function pages() {
		$crud = new grocery_CRUD();
		$crud->unset_print();
		$crud->set_table('pages');
		$crud->set_subject('Pages');
		$page_title = array('page_title' => 'Page Details');
		$crud->columns('page_key','page_title','page_content');
		$crud->display_as('page_key' , 'Key')
			 ->display_as('page_title' , 'Title')
			 ->display_as('page_content' , 'Content');
			 
		$crud->add_action('preview', '', '','read-icon openinnewtab',array($this,'previewpage'));
		$jsscript =  '
		<script>
		jQuery(document).ready(function(e) {
			setTimeout(function(){
				jQuery("a.openinnewtab").attr("target", "_blank");
			}, 2000);
		});
		</script>
		';
		$crud->required_fields('page_key','page_title','page_content');
		$crud->callback_after_insert(array($this, 'page_after_insert'));
		$crud->callback_after_update(array($this, 'page_after_update'));
		$crud->unset_read();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();
		$output = $crud->render();

		$outputData = array_merge((array) $output , $page_title);
		$outputData = array_merge((array) $outputData , array('page_scripts' => $jsscript));
		$this->_example_output($outputData);
	}
	
	function previewpage($primary_key , $row){
	    return site_url('content/page/' . $row->page_key);
	}

	public function pendingorders($osid =- 1) {
		$crud = new grocery_CRUD();
		$crud->unset_print();
		$crud->set_table('template_order');
		$page_title = array('page_title' => 'Orders');
		$crud->columns('user_id','template_id','NoOfCopies','NoOfPages', 'trnsid', 'colortype', 'order_status_id');
		$crud->display_as('user_id' , 'Username')
			->display_as('template_id' , 'Template')
			->display_as('NoOfCopies' , 'Copies')
			->display_as('NoOfPages' , 'Pages')
			->display_as('trnsid' , 'Address')
			->display_as('order_status_id' , 'Status');

		$crud->add_fields('user_id','template_id','NoOfCopies','NoOfPages', 'trnsid','order_status_id');
		$crud->edit_fields('user_id','template_id','NoOfCopies','NoOfPages', 'trnsid','order_status_id');
		$crud->field_type('user_id', 'readonly');
		$crud->field_type('template_id', 'readonly');
		$crud->field_type('NoOfCopies', 'readonly');
		$crud->field_type('NoOfPages', 'readonly');
		$crud->field_type('trnsid', 'readonly');

		if ($osid != -1){
	  	 	$crud->where('template_order.order_status_id',$osid);
		}
		
		$crud->callback_column('colortype',array($this,'_callback_colortype'));
		$crud->callback_column('country',array($this,'_callback_country'));

		$crud->required_fields('user_id','order_status_id');
		$crud->callback_after_insert(array($this, 'pendingorder_after_insert'));
		$crud->callback_after_update(array($this, 'pendingorder_after_update'));                       
		$crud->set_relation('user_id','users','username');
		$crud->set_relation('template_id','template_master','template_name');
		$crud->set_relation('trnsid','transaction','Address : {address1}, {address2}, City : {city}, State : {state}');
		$crud->set_relation('order_status_id','template_order_status','status_name');
		$crud->add_action('ViewPDF', config_item('media_url') . 'images/file_pdf.png', config_item('base_url') . 'BookletTemplate/vieworderpdf/');
		$crud->unset_delete();
		$crud->unset_add();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		$output = $crud->render();
		$outputData = array_merge((array) $output , $page_title);
		$this->_example_output($outputData);
	}
	
	public function _callback_colortype($value, $row){
		if ($value == 2){
			return 'Black & White';
		} else {
			return 'Color';
		}
	}
	
	private $country = array();
	
	public function _callback_country($value, $row){
		if (empty($this->country) || count($this->country) <= 0){
			$dbcountries = getcountries();
			foreach($dbcountries as $dbcountry){
				$this->country[$dbcountry['countryid']] = $dbcountry['countryname'];
			}
		}
		print_r($row);
		die;
		if (array_key_exists($value, $this->country)){
			return $this->country[$value];
		} else {
			return '';
		}
	}

	public function configuration() {
		$crud = new grocery_CRUD();
		$crud->unset_print();
		$crud->set_table('setting');
		$page_title = array('page_title' => 'Configuration');
		$crud->columns('setting_name','setting_value');
		$crud->display_as('setting_value' , 'Value')
			 ->display_as('setting_name' , 'Name');

		$crud->add_fields('setting_name','setting_value');
		$crud->edit_fields('setting_name','setting_value');
		$crud->field_type('setting_name', 'readonly');
		$crud->required_fields('setting_value');
		$crud->callback_after_insert(array($this, 'user_after_insert'));
		$crud->callback_after_update(array($this, 'user_after_update'));                       
		$crud->unset_delete();
		$crud->unset_add();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();

		$output = $crud->render();
		$outputData = array_merge((array) $output , $page_title);
		$this->_example_output($outputData);
	}

	public function menu() {
		$crud = new grocery_CRUD();
		$crud->unset_print();
		$crud->set_table('menu');
		$crud->columns('name','url','parent_id');
		$crud->display_as('name' , 'Name')
			->display_as('url' , 'Path')
			->display_as('parent_id' , 'Parent');

		$crud->required_fields('menu_id','name','url');
		$crud->callback_after_insert(array($this, 'user_after_insert'));
		$crud->callback_after_update(array($this, 'user_after_update'));                       
		$crud->set_relation('parent_id','menu','name',array('parent_id' => '0'));
		$crud->unset_jquery();
		$crud->unset_jquery_ui();
		$output = $crud->render();
		$page_descr = array('page_title' => 'Menu', 'page_descr' => 'Note : <br />
          Following points you have to remember for Menu configuration : <br />
          1) Menu link will contain a path after '. config_item('base_url') . '<br />
          2) If you want to add any of page to menu then you have to add like this "content/page/&lt;your page key&gt;"<br />');
		$outputData = array_merge((array) $output , $page_descr);
		$this->_example_output($outputData);
	}

	public function order() {
		$crud = new grocery_CRUD();
		$crud->unset_print();
		$crud->set_table('template_order_status');
		$page_title = array('page_title' => 'Order Status');
		$crud->columns('order_status_id','status_name','time_stamp');
		$crud->add_fields('setting_name','setting_value');
		$crud->edit_fields('setting_name','setting_value');
		$crud->field_type('setting_name', 'readonly');
		$crud->required_fields('setting_value','setting_name');
		$crud->callback_after_insert(array($this, 'user_after_insert'));
		$crud->callback_after_update(array($this, 'user_after_update'));                       
		$crud->unset_delete();
		$crud->unset_add();
		$crud->unset_jquery();
		$crud->unset_jquery_ui();
		$output = $crud->render();
		$outputData = array_merge((array) $output , $page_title);
		$this->_example_output($outputData);
	}
	
	
	public function transaction() {
		$crud = new grocery_CRUD();
		$crud->unset_print();
		$crud->set_table('transaction');
		$crud->set_subject('Transaction');
		$page_title = array('page_title' => 'Transaction');
		$crud->columns('trnsid','firstname','lastname','address1','address2','postcode','city','country','state','email','phone','token','payerid','status','paymentmethod');
		   
		$crud->required_fields('trnsid','firstname','lastname','address1','address2','postcode','city','country','state','email','phone','token','payerid','status','paymentmethod');
							   
		$crud->callback_after_insert(array($this, 'user_after_insert'));
		$crud->callback_after_update(array($this, 'user_after_update'));                       
		$crud->unset_jquery();
		$crud->unset_jquery_ui();
		$crud->unset_delete();
		$crud->unset_add();
		$output = $crud->render();
		$outputData = array_merge((array) $output , $page_title);
		$this->_example_output($outputData);
	}

}