<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Setting extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->database();

        $this->load->helper(array('url','common_helper'));

        $this->load->library(array('session' ,'form_validation','grocery_CRUD' ));
        
        $this->load->model('Grocery_crud_model');
		
		user_check();
    }

    public function _example_output($output = null) {
        $this->load->view('admin/setting' , $output);
    }

    public function index() {
        $this->_example_output((object) array('output' => '' , 'js_files' => array() , 'css_files' => array()));
    }
   
}

