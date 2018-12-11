<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class content_model extends CI_Model {
	
/* insert the register form in datebase 	
	example:- $this->common->data_insert("table name","Array");	
*/	
	public function __construct(){
        parent::__construct();
    }
	
	public function showpage($pagevalues){
		$this->headerpart($pagevalues);
		$this->content($pagevalues);
		$this->footerpart($pagevalues);
	}

	
	private function headerpart($pageparam){
		$postfix = $pageparam['key'];
		if ($postfix == ''){
			$this->load->view('pages/header.php', $pageparam);
		} else if (file_exists(APPPATH . "views/pages/header-" . $postfix . ".php")){
			$this->load->view('pages/header-' . $postfix . '.php', $pageparam);
		} else {
			$this->load->view('pages/header.php', $pageparam);
		}
	}
	
	private function footerpart($pageparam){
		$postfix = $pageparam['key'];
		if ($postfix == ''){
			$this->load->view('pages/footer.php', $pageparam);
		} else if (file_exists(APPPATH . "views/pages/footer-" . $postfix . ".php")){
			$this->load->view('pages/footer-' . $postfix . '.php', $pageparam);
		} else {
			$this->load->view('pages/footer.php', $pageparam);
		}
	}
	
	private function content($pageparam){
		$postfix = $pageparam['key'];
		if (file_exists(APPPATH . "views/pages/content-" . $postfix . ".php")){
			$this->load->view('pages/content-' . $postfix . '.php', $pageparam);
		} else {
			$this->load->view('pages/content.php', $pageparam);
		}
	}
}