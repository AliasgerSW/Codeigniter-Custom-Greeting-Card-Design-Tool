<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class product extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('product_model');
		$this->load->model('content_model');
		loadsetting();
	}
	
	public function index(){
		$this->page();
	}
	
	public function allproducts(){
		$this->show_products(-1);
	}
	
	public function category($categoryid){
		if ($categoryid <= 0){
			die('Invalid Category');
		}
		$this->show_products($categoryid);
	}
	
	private function show_products($categoryid){
		$pagevalues = getpagevalues('category', $this);
		$getcategoryinfo = array();
		if ($categoryid > 0){
			$getcategoryinfo = $this->product_model->getcategory($categoryid);
			$pagevalues['title'] = $getcategoryinfo['Name'];
		} else {
			$pagevalues['title'] = 'All Products';
		}
		$pagevalues['category'] = $getcategoryinfo;
		$pagevalues['products'] = $this->product_model->getallproducts($categoryid);
		$this->content_model->showpage($pagevalues);
	}
	
	public function single($productid){
		$product = $this->product_model->getproduct($productid);
		$pagevalues = getpagevalues('single-product', $this);
		$pagevalues['title'] = $product['Name'];
		$pagevalues['product'] = $product;
		$pagevalues['productprices'] = $this->product_model->getproductpricesofproduct($productid);
		$this->content_model->showpage($pagevalues);		
	}
	
	public function customize($productid){
		$this->session->set_userdata('selectedtemplate', $productid);
		Redirect('/BookletTemplate');
	}
}