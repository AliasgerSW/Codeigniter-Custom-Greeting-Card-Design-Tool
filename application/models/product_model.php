<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class product_model extends CI_Model {
	
/* insert the register form in datebase 	
	example:- $this->common->data_insert("table name","Array");	
*/	
	public function __construct(){
        parent::__construct();
    }
	
	public function getcategory($category_id){
		$dbvalues = $this->db_operation->select('template_category', '*', ' where category_id = ' . $category_id);
		$dbvalue = array();
		if (!empty($dbvalues) && count($dbvalues) > 0){
			$dbvalue = $dbvalues[0];
		} else {
			return $dbvalue;
		}
		$category = array(
				'id' => $dbvalue['category_id'],
				'Name' => $dbvalue['category_name']
			);

		return $category;
	}

	
	public function getallproducts($category_id = -1){
		$dbvalues = $this->db_operation->select('template_master', '*', 
			($category_id != -1 ? ' where category_id = ' . $category_id : ''));
		$products = array();
		foreach($dbvalues as $dbvalue){
			$products[$dbvalue['template_id']] = $this->getproductarray($dbvalue);
		}
		return $products;
	}

	public function getproduct($template_id){
		$dbvalues = $this->db_operation->select('template_master', '*', ' where template_id = ' . $template_id);
		$dbvalue = array();
		if (!empty($dbvalues) && count($dbvalues) > 0){
			$dbvalue = $dbvalues[0];
		} else {
			return $dbvalue;
		}
		$product = $this->getproductarray($dbvalue);

		return $product;
	}
	
	private function getproductarray($dbvalue){
		$product = array(
				'id' => $dbvalue['template_id'],
				'Name' => $dbvalue['template_name'],
				'Descr' => $dbvalue['template_descr'],
				'Descr2' => $dbvalue['template_descr2'],
				'product_image' => $dbvalue['product_image'],
				'NoOfPages' => $dbvalue['NoOfPages'],
				'color' => array('price' => $dbvalue['price_color'], 'priceperpage' => $dbvalue['priceperpage_color']),
				'blackwhite' => array('price' => $dbvalue['price_bw'], 'priceperpage' => $dbvalue['priceperpage_bw'])
			);
		return $product;
	}
	
	
	public function getproductpricesofproduct($template_id){
		$templateinfo = $this->getproduct($template_id);
		if (empty($templateinfo) || count($templateinfo) <= 0){
			return array();
		}

		$dbvalues = $this->db_operation->select('discount');
		$pricelist = array();
		$pricecol = $templateinfo['color']['price'];
		$pricebw = $templateinfo['blackwhite']['price'];
		$rangeto = 0;
		foreach($dbvalues as $dbvalue){
			$rangeto = $dbvalue['rangeto']; 
			if (empty($dbvalue['rangeto'])){
				$rangeto = $dbvalue['rangefrom'] + 19;
			}
			$pricelist[$dbvalue['discountid']] = array(
				'name' => $dbvalue['name'],
				'rangefrom' => $dbvalue['rangefrom'],
				'rangeto' => $rangeto,
				'discount' => $dbvalue['discount'],
				'pricecolor' => $pricecol * $rangeto * (100 - $dbvalue['discount'])/100,
				'pricebw' => $pricebw * $rangeto * (100 - $dbvalue['discount'])/100
			);
			if (empty($dbvalue['rangeto'])){
				$pricelist[$dbvalue['discountid']]['isfinal'] = 1;
			} else {
				$pricelist[$dbvalue['discountid']]['isfinal'] = 0;
			}
		}
		return $pricelist;
	}
	
	public function getproductpricesofcustom($templateuserid){
		$dbvalues = $this->db_operation->select('template_user', '*', ' where templateCUID = ' . $templateuserid);
		if (empty($dbvalues) || count($dbvalues) <= 0){
			return array();
		}
		
		$template_id = $dbvalues[0]['template_id'];
		$NoofPages = $dbvalues[0]['NoOfPages'];
		$templateinfo = $this->getproduct($template_id);
		if (empty($templateinfo) || count($templateinfo) <= 0){
			return array();
		}

		$dbvalues = $this->db_operation->select('discount');
		$pricelist = array();
		$pricecol = $templateinfo['color']['price'];
		$pricebw = $templateinfo['blackwhite']['price'];
		$excesspage = $NoofPages - $templateinfo['NoOfPages'];
		$pricecol = $pricecol + ($excesspage * $templateinfo['color']['priceperpage']);
		$pricebw = $pricebw + ($excesspage * $templateinfo['blackwhite']['priceperpage']);
		$rangeto = 0; $isfinal = 0;

		foreach($dbvalues as $dbvalue){
			$rangeto = $dbvalue['rangeto']; 
			if (empty($dbvalue['rangeto'])){
				$rangeto = $dbvalue['rangefrom'] + 20;
			}
			if (empty($dbvalue['rangeto'])){
				$isfinal = 1;
			} else {
				$isfinal = 0;
			}

			$pricelist[] = array(
				'name' => $dbvalue['name'],
				'rangefrom' => $dbvalue['rangefrom'],
				'rangeto' => $dbvalue['rangeto'],
				'discount' => $dbvalue['discount'],
				'pricecolor' => $pricecol * $rangeto * (100 - $dbvalue['discount'])/100,
				'pricebw' => $pricebw * $rangeto * (100 - $dbvalue['discount'])/100,
				'isfinal' => $isfinal
			);

		}
		return $pricelist;
	}

	public function getproductpriceoforder($templateorderid){
		$dbvalues = $this->db_operation->select('template_order', '*', ' where templateOrderID = ' . $templateorderid);
		if (empty($dbvalues) || count($dbvalues) <= 0){
			return array();
		}
		
		$template_id = $dbvalues[0]['template_id'];
		$NoofPages = $dbvalues[0]['NoOfPages'];
		$NoofCopies = $dbvalues[0]['NoOfCopies'];
		$colortype = $dbvalues[0]['colortype'];
		if ($colortype == 2){
			$colortype = 'blackwhite';
		} else {
			$colortype = 'color';
		}
		if ($NoofCopies <= 0){
			return array();
		}
		
		$templateinfo = $this->getproduct($template_id);
		if (empty($templateinfo) || count($templateinfo) <= 0){
			return array();
		}
		
		$orderprice = $templateinfo[$colortype]['price'];
		$excesspage = $NoofPages - $templateinfo['NoOfPages'];
		$orderprice = $orderprice + ($excesspage * $templateinfo[$colortype]['priceperpage']);
		
		$dbvalues = $this->db_operation->select('discount', '*', 
			" where rangefrom <= $NoofCopies and (rangeto >= $NoofCopies or rangeto = 0)");
		$returnvalue = $orderprice;
		if (!empty($dbvalues) && count($dbvalues) > 0){
			$returnvalue = $orderprice * $NoofCopies * (100 - $dbvalues[0]['discount'])/100;
		}
		return $returnvalue;
	}
}