<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class content extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('content_model');
		loadsetting();
	}
	
	public function index(){
		$this->page();
	}
	
	public function page($page_key = 'front'){
		$pagevalues = array();
		if ($page_key == 'front'){
			$pagevalues = getpagevalues($page_key, $this);
		} else {
			$pagevalues = getpagevalues($page_key, $this);
			if (is_null($pagevalues) || empty($pagevalues) || count($pagevalues) <= 0){
				$pagevalues = getpagevalues('not-found', $this);
			}
		}
		$this->content_model->showpage($pagevalues);
	}
}