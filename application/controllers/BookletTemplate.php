<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BookletTemplate extends CI_Controller {
	//Here Remember Page No 1 refers to Front Page and -1 refers to the back page
	private $BookletBaseValue = array();
	private $BookletUserValue = array();
	private $BookletTemplateID = 1;
	private $templatefoldername = '';
	private $TemplateInfo = '';

	public function __construct(){
		parent::__construct();
		$selectedtemplate = $this->session->userdata('selectedtemplate');
		if (empty($selectedtemplate)){
			$selectedtemplate = 1;
			die('No Template Selected');
		}
		$this->BookletTemplateID = $selectedtemplate;
		$this->templatefoldername = 'template' . $selectedtemplate;
		$this->load->model('product_model');
		$this->resetBaseTemplate();
		$this->resetUserTemplate();		
	}
	
	private function resetBaseTemplate(){
		$this->TemplateInfo = $this->product_model->getproduct($this->BookletTemplateID);
		$dbvalues = $this->db_operation->select('template_meta', '*', 'where template_id = ' . $this->BookletTemplateID, 'order by page_no');
		
		if (!empty($dbvalues)){
			foreach($dbvalues as $sv){
				if (empty($sv['page_no']) || empty($sv['meta_key'])){
					continue;
				}
				
				$this->BookletBaseValue[$sv['page_no']][$sv['meta_key']] = $sv['meta_value'];
			}
		}
	}
	
	private function resetUserTemplate(){
		$UMID = $this->getUserCustomisationMID();
		$dbvalues = $this->db_operation->select('template_meta_user', '*', 'where templateCUID = ' . $UMID, 'order by page_no');
		if (!empty($dbvalues)){
			foreach($dbvalues as $sv){
				if (empty($sv['page_no']) || empty($sv['meta_key'])){
					continue;
				}
				
				$this->BookletUserValue[$sv['page_no']][$sv['meta_key']] = $sv['meta_value'];
			}
		}
	}
	
	private function GetCurrentUserID(){
		$userid = get_sessionData('user_id');
		if (empty($userid)){
			return get_client_ip_env();
		}
		return $userid;
	}
	
	public function index(){
		$this->page_template();
	}
	
	public function page_template(){
		$BookletSize = $this->getnoOfpages();
		$pagevalues = getpagevalues('booklettemplate', $this);
		$pagevalues['book_size'] = $BookletSize;
		$pagevalues['title'] = $this->TemplateInfo['Name'];
		$pagevalues['productprices'] = $this->product_model->getproductpricesofproduct($this->BookletTemplateID);
		$this->load->view('BookletTemplate', $pagevalues);
	}
	
	public function front_page_form(){
		$templatevariables = $this->front_page_paramters(true);
		$this->load->view("booklet/$this->templatefoldername/form/front_page", $templatevariables);
	}
	
	public function front_page_preview(){
		$templatevariables = $this->front_page_paramters();
		$this->load->view("booklet/$this->templatefoldername/preview/front_page", $templatevariables);
	}
	
	private function front_page_paramters($formcontrol = false, $bookletordervalue = array()){    
		$templatevariables = array();
		$templatevariables['page_number'] = 1;
		$dbvalues = $this->db_operation->select('template_parameters','*',
			' where template_id=' . $this->BookletTemplateID . " and page_type = 'front'");
		if (!empty($dbvalues) && count($dbvalues) > 0){
			foreach($dbvalues as $dbvalue){
				$templatevariables[$dbvalue['meta_key']] = $this->get_met_value(1, $dbvalue['meta_key'], $formcontrol, $bookletordervalue);
			}
		}
		return $templatevariables;
	}
	
	public function back_page_form(){
		$templatevariables = $this->back_page_paramters(true);
		$this->load->view("booklet/$this->templatefoldername/form/back_page", $templatevariables);
	}
	
	public function back_page_preview(){
		$templatevariables = $this->back_page_paramters();
		$this->load->view("booklet/$this->templatefoldername/preview/back_page", $templatevariables);
	}
	
	private function back_page_paramters($formcontrol = false, $bookletordervalue = array()){
	 $templatevariables = array();
		$templatevariables['page_number'] = -1;
		$dbvalues = $this->db_operation->select('template_parameters','*',
			' where template_id=' . $this->BookletTemplateID . " and page_type = 'back'");
		if (!empty($dbvalues) && count($dbvalues) > 0){
			foreach($dbvalues as $dbvalue){
				$templatevariables[$dbvalue['meta_key']] = 
					$this->get_met_value(-1, $dbvalue['meta_key'], $formcontrol, $bookletordervalue);
			}
		}
		return $templatevariables;
	}
	
	public function other_page_form($pageno){
		$templatevariables = $this->other_page_paramters($pageno, true);
		$this->load->view("booklet/$this->templatefoldername/form/other_page", $templatevariables);
	}
	
	public function other_page_preview($pageno){
		$templatevariables = $this->other_page_paramters($pageno);
		$this->load->view("booklet/$this->templatefoldername/preview/other_page", $templatevariables);
	}
	
	private function other_page_paramters($pageno, $formcontrol = false, $bookletordervalue = array()){
		$templatevariables = array();
		$templatevariables['page_number'] = $pageno;
		$dbvalues = $this->db_operation->select('template_parameters','*',
			' where template_id=' . $this->BookletTemplateID . " and page_type = 'other'");
		if (!empty($dbvalues) && count($dbvalues) > 0){
			foreach($dbvalues as $dbvalue){
				$templatevariables[$dbvalue['meta_key']] = 
					$this->get_met_value($pageno, $dbvalue['meta_key'], $formcontrol, $bookletordervalue);
			}
		}
	
		return $templatevariables;
	}
	
	public function get_met_value($pageno, $meta_key, $formcontrol = false, $bookletordervalue = array()){
		if (empty($pageno) || empty($meta_key)){
			continue;
		}
		
		$uservaluearray = $this->BookletUserValue;
		if (!empty($bookletordervalue) && count($bookletordervalue) <= 0){
			$uservaluearray = $bookletordervalue;
		}
		
		if (array_key_exists($pageno, $uservaluearray)){
			if (array_key_exists($meta_key, $uservaluearray[$pageno])){
				return str_replace('{pageno}', $pageno, $uservaluearray[$pageno][$meta_key]);
			}
		}
		
		if (array_key_exists($pageno, $this->BookletBaseValue)){
			if (array_key_exists($meta_key, $this->BookletBaseValue[$pageno])){
				return str_replace('{pageno}', $pageno, $this->BookletBaseValue[$pageno][$meta_key]);
			}
		}
		
		return '';
	}
	
	public function update_template(){
		if (!isset($_POST['page_number']) || empty($_POST['page_number']) || !is_numeric($_POST['page_number'])){
			return;
		}
		$pageno = $_POST['page_number'];
		
		if (!(isset($_POST['update']) || isset($_POST['add_to_cart']) || 
			isset($_POST['navprev']) || isset($_POST['navnext']))){
			return;
		}
		
		$meta_values = array();
	
		$filearray = array();
		
		$UserMasterID = $this->getUserCustomisationMID();
		
		$whereexp = ' where 1 = 1 ';
		$whereexp .= ' and template_id = ' . $this->BookletTemplateID;
		$pagetype = 'other';
		if ($pageno == 1){
			$pagetype = 'front';
		} else if ($pageno == -1){
			$pagetype = 'back';
		}
		$whereexp .= " and page_type = '$pagetype'";
		$whereexp .= ' and isfile = 1';
		$dbvalues = $this->db_operation->select('template_parameters', '*', $whereexp);
		if (!empty($dbvalues) && count($dbvalues) > 0){
			foreach($dbvalues as $dbvalue){
				$filearray[] = $dbvalue['meta_key'];
			}
		}

		$metakey = '';
		foreach($_POST as $key => $val){
			if (startsWith($key, 'meta_key_') && strlen($key) > strlen('meta_key_')){
				$metakey = substr($key, 9);
				if (in_array($metakey, $filearray)) continue;
				$meta_values[$metakey] = $val;
			}
		}
		
		$isfile = 0;

		foreach($_FILES as $key => $val){
			if (startsWith($key, 'meta_key_') && strlen($key) > strlen('meta_key_') && !empty($_FILES[$key]['name'])){
				$meta_values[substr($key, 9)] = $this->SaveImage($key);
				$isfile = 1;
			}
		}
		
		$deleteexp = array();
		$deleteexp['templateCUID'] = $UserMasterID;
		$deleteexp['page_no'] = $pageno;
		if (!$isfile){
			$deleteexp['isfile'] = 0;
		} else {
			$dbvalues = $this->db_operation->select('template_meta_user', '*',
				array('templateCUID' => $UserMasterID, 'page_no' => $pageno, 'isfile' => 1));
			foreach($dbvalues as $dbvalue){
				if (!empty($dbvalue['meta_value'])){
					$this->_removebookletimage($dbvalue['meta_value']);
				}
			}
		}
		
		$this->db_operation->data_delete('template_meta_user', $deleteexp);

		$insertstring = array(
			'templateCUID' => $UserMasterID,
			'page_no' => $pageno,
			'meta_key' => '',
			'meta_value' => '',
			'isfile' => 0
		);
		
		foreach($meta_values as $key => $val){
			$insertstring['meta_key'] = $key;
			$insertstring['meta_value'] = $val;
			if (in_array($key, $filearray)){
				$insertstring['isfile'] = 1;
			} else {
				$insertstring['isfile'] = 0;
			}
			$this->db_operation->data_insert('template_meta_user', $insertstring);
		}
		$extraexpr = '';
		if (isset($_POST['navprev']) || isset($_POST['update'])){
			if (isset($_POST['navprev'])){
				if ($pageno != 1) {
					if ($pageno == -1){
						$totalpages = $this->getnoOfpages(0);
						$pageno = $totalpages - 1;
					} else {
						$pageno = $pageno - 1;
					}
				}
			} else {
				$totalpages = $this->getnoOfpages(0);
				if ($pageno != -1){
					if ($pageno == ($totalpages - 1)){
						$pageno = -1;
					} else {
						$pageno = $pageno + 1;
					}
				}
			}
		}
		switch($pageno){
			case 1:
				$extraexpr = '&pagetype=front';
				break;
			case -1:
				$extraexpr = '&pagetype=back';			
				break;
			default:
				$extraexpr = '&pagetype=other&pageno=' . $pageno;
				break;
		}
		
		if (isset($_POST['add_to_cart'])){
			Redirect('/cart/add_to_cart/' . $UserMasterID . '/' . $_REQUEST['price_options'] . 
				'/' . $_REQUEST['color_options']);
		} else {
			Redirect('/BookletTemplate?msg=Saved Successfully' . $extraexpr);
		}
	}
	
	private function getUserCustomisationMID(){
		$userid = $this->GetCurrentUserID();
		$whereexp = 'where template_id = ' . $this->BookletTemplateID;
		if (is_numeric($userid)){
			$whereexp .= ' and user_id = ' . $userid;
		} else {
			$whereexp .= " and ipaddress = '" . $userid . "'";
		}
		$result = $this->db_operation->select('template_user', '*', $whereexp);
		if (!empty($result) && count($result) > 0){
			return $result[0]['templateCUID'];
		} else {
			if (is_numeric($userid)){
				$insertstring = array(
					'template_id' => $this->BookletTemplateID, 
					'user_id' => $userid,
					'ipaddress' => ''
				);
			} else {
				$insertstring = array(
					'template_id' => $this->BookletTemplateID, 
					'ipaddress' => $userid,
					'user_id' => 0
				);
			}
			return $this->db_operation->data_insert('template_user', $insertstring);
		}
	}
	
	private function SaveImage($inputname){
		$file_name = $_FILES[$inputname]['name'];
		if(!empty($file_name)){
			$temp_name = $_FILES[$inputname]['tmp_name'];    
			// Check if image file is a actual image or fake image    
			$check = getimagesize($temp_name);
			
			if($check !== false) {
				// Check file size 
				if($_FILES[$inputname]['size']< 1500000) {
					$ext = @pathinfo($file_name, PATHINFO_EXTENSION);    
					$file_name = time().rand(1000,99999).'.'.$ext;    
					$file_path = config_item('bookletU_path').$file_name;     
					@move_uploaded_file($temp_name, $file_path); 
					$this->cropimage($inputname, $file_path);
					//insert query  en_property_image
					return $file_name;
				} else {return ''; }
			} else {return ''; }
		} else {return ''; }
	}
	
	private function cropimage($metakey, $file_path){
		// echo '<pre>';
		// print_r($_REQUEST);
		
		$cordx = 0;
		$cordy = 0;
		$cordh = 0;
		$cordw = 0;
		if (array_key_exists($metakey . '_x', $_REQUEST) && !empty($_REQUEST[$metakey . '_x'])){
			$cordx = $_REQUEST[$metakey . '_x'];
		} else {
			return;
		}
		
		if (array_key_exists($metakey . '_y', $_REQUEST) && !empty($_REQUEST[$metakey . '_y'])){
			$cordy = $_REQUEST[$metakey . '_y'];
		} else {
			return;
		}
		
		if (array_key_exists($metakey . '_h', $_REQUEST) && !empty($_REQUEST[$metakey . '_h'])){
			$cordh = $_REQUEST[$metakey . '_h'];
		} else {
			return;
		}
				
		if (array_key_exists($metakey . '_w', $_REQUEST) && !empty($_REQUEST[$metakey . '_w'])){
			$cordw = $_REQUEST[$metakey . '_w'];
		} else {
			return;
		}		
		
		$jcrop_resized_w = 439;
		if (array_key_exists($metakey . '_jcropw', $_REQUEST) && !empty($_REQUEST[$metakey . '_jcropw'])){
			$jcrop_resized_w = $_REQUEST[$metakey . '_jcropw'];
		} else {
			return;
		}		
		
		$jcrop_resized_h = 332;
		if (array_key_exists($metakey . '_jcroph', $_REQUEST) && !empty($_REQUEST[$metakey . '_jcroph'])){
			$jcrop_resized_h = $_REQUEST[$metakey . '_jcroph'];
		} else {
			return;
		}
		
		$targ_h = 258;
		$targ_w = 222;
		//$targ_w = 151; $targ_h = 176;
		$jpeg_quality = 90;
		
		$src = $file_path;
		$img_r = createImageFromFile($src);
		//echo '<img src="'. $src . '" />';
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		
		list($width, $height, $type, $attr) = getimagesize($src);

		$cordw = $cordw * ($width/$jcrop_resized_w); 
		$cordh = $cordh * ($height/$jcrop_resized_h);
		
		$cordx = $cordx * ($width/$jcrop_resized_w); 
		$cordy = $cordy * ($height/$jcrop_resized_h);
		
		imagecopyresampled($dst_r,$img_r,0,0,$cordx,$cordy,
		$targ_w,$targ_h,$cordw,$cordh);
		
		try{
			imagepng($dst_r,$file_path);
		} catch(Exception $e){
			
		}
	}
	
	private function changenoofpages($totalpages){
		try {
			if ($totalpages < 4 || $totalpages % 4 != 0) {
				$Noofpages = $this->GetNoOfPages();
				echo $Noofpages . '|Number of pages must be greater than 4 and it will be in 4\'s format';
				return;
			}
			$UserMasterID = $this->getUserCustomisationMID();
			$whereexp = array();
			$whereexp['templateCUID'] = $UserMasterID;
			
			$updatestring = array('NoOfPages' => $totalpages);
			$this->db_operation->data_update('template_user', $updatestring, $whereexp);
			echo $totalpages . '|Saved Successfully';
		} catch(Exception $e) {
			$Noofpages = $this->GetNoOfPages();
			$errormsg = $e->getMessage();
			echo $totalpages . '|' . $errormsg;
		}
	}

	public function setnoOfpages($totalpages){
		$this->changenoofpages($totalpages);
	}
	
	public function getnoOfpages($isecho = 0){
		$pages = 4;
		try {
			$UserMasterID = $this->getUserCustomisationMID();
			$result = $this->db_operation->select('template_user', 'NoOfPages', ' where templateCUID = ' . $UserMasterID);
			if ((isset($result) && count($result) > 0)){
				$pages = $result[0]['NoOfPages'];
			} else {
				$pages = $this->TemplateInfo['NoOfPages'];
			}
		} catch(Exception $e) {
			$pages = $this->TemplateInfo['NoOfPages'];
		}
		if ($isecho){
			echo $pages;
		} else {
			return $pages;
		}
	}
	
	public function previewpdf(){
		$this->getbookletpdf();
	}
	
	private function getbookletpdf(){
		$totalpages = $this->getnoOfpages(0);
		include( config_item('site_url') . 'assets/mpdf60/mpdf.php');
		$mpdf = new mPDF('utf-8', 'A5', '8', '', 10, 10, 22, 22, 10, 20);
		for($count = 1;$count <= $totalpages; $count++){
			$templatevariables = array();
			$tview = '';
			if ($count == 1){
				$templatevariables = $this->front_page_paramters();
				$tview = "bookletpdf/$this->templatefoldername/front_page";
			} else if ($count == $totalpages){
				$templatevariables = $this->back_page_paramters();
				$tview = "bookletpdf/$this->templatefoldername/back_page";
			} else {
				$templatevariables = $this->other_page_paramters($count);
				$tview = "bookletpdf/$this->templatefoldername/other_page";
			}
			ob_start();
			$this->load->view($tview, $templatevariables);
			$content = ob_get_contents();
			ob_end_clean();
			$mpdf->WriteHTML($content);
			if ($count != $totalpages){
				$mpdf->AddPage();
			}
		}
		$mpdf->Output();
	}
	
	public function removebookletimage(){
		$this->_removebookletimage($_REQUEST['filename']);
	}
	
	public function _removebookletimage($pfilename){
		if (empty($pfilename)) continue;
		$file_name = $pfilename;
		$file_name_withpath = config_item('bookletU_path') . $file_name;
		//$file_name_withpathbw = config_item('bookletU_path') . 'blackwhite/' . $file_name;
		try {
			if (is_file($file_name_withpath)){
				unlink($file_name_withpath);
			}
			/*if (is_file($file_name_withpathbw)){
				unlink($file_name_withpathbw);
			}*/
			$deleteexp = array();
			$deleteexp['meta_value'] = $file_name;
			$this->db_operation->data_delete('template_meta_user', $deleteexp);
			echo 'success';
		} catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getproductprices(){
		$UMID = $this->getUserCustomisationMID();
		$productprices = $this->product_model->getproductpricesofcustom($UMID);
		die(json_encode($productprices));
	}
	
	private function savecurrentorder(){
		$UserMasterID = $this->getUserCustomisationMID();
		$resultMvals = $this->db_operation->select('template_user', '*', ' where templateCUID = ' . $UserMasterID);

		$datainsert = array();
		$datainsert['template_id'] = $resultMvals[0]['template_id'];
		$datainsert['user_id'] = $resultMvals[0]['user_id'];
		$datainsert['NoOfPages'] = $resultMvals[0]['NoOfPages'];
		$datainsert['NoOfCopies'] = $_REQUEST['price_options'];
		$order_id = $this->db_operation->data_insert('template_order', $datainsert);
		
		$resultDvals = $this->db_operation->select('template_meta_user', '*', 'where templateCUID = ' . $UserMasterID, 'order by page_no');
		foreach($resultDvals as $resultDval){
			$datainsert = array();
			$datainsert['meta_key'] = $resultDval['meta_key'];
			$datainsert['meta_value'] = $resultDval['meta_value'];
			$datainsert['isfile'] = $resultDval['isfile'];
			$datainsert['page_no'] = $resultDval['page_no'];
			$datainsert['templateOrderID'] = $order_id;
			
			$this->db_operation->data_insert('template_meta_order', $datainsert);
		}
		return $order_id;
	}

	public function vieworderpdf($order_id){
		$totalpages = 4;
		$colortype = 'color';
		$this->BookletTemplateID = 1;
		$dbvalues = $this->db_operation->select('template_order', '*', ' where templateOrderID = ' . $order_id);
		if (!empty($dbvalues) && count($dbvalues) > 0){
			$totalpages = $dbvalues[0]['NoOfPages'];
			$this->BookletTemplateID = $dbvalues[0]['template_id'];
			if ($dbvalues[0]['colortype'] == 1){
				$colortype = 'color';
			} else {
				$colortype = 'blackwhite';
			}
		}
		$colortype = 'color';
		
		
		if ($colortype == 'blackwhite'){
			$dbvalues = $this->db_operation->select('template_meta_order', '*', 'where isfile = 1 and templateOrderID = ' . $order_id);
			$imagename = '';
			$convfrom = '';
			$confto = '';
			foreach($dbvalues as $sv){
				$imagename = $sv['meta_value'];
				$convfrom = config_item('bookletU_path') . $imagename;
				$convto = config_item('bookletU_path') . 'blackwhite/' . $imagename;
				if (is_file($convfrom)){
					if (!is_file($convto)){
						convertimagetograyscale($convfrom, $convto);
					}
				}
			}
		}
		
		$dbvalues = $this->db_operation->select('template_meta_order', '*', 'where templateOrderID = ' . $order_id, 'order by page_no');
		$bookletordervalue = array();
		if (!empty($dbvalues)){
			$bookletmetavalue = '';
			foreach($dbvalues as $sv){
				if (empty($sv['page_no']) || empty($sv['meta_key'])){
					continue;
				}
				
				$bookletmetavalue = $sv['meta_value'];
				if ($colortype == 'blackwhite' && $sv['isfile'] == '1'){
					$bookletmetavalue = 'blackwhite/' . $bookletmetavalue;
				}
				$bookletordervalue[$sv['page_no']][$sv['meta_key']] = $bookletmetavalue;
			}
		}
		
		include( config_item('site_url') . 'assets/mpdf60/mpdf.php');
		$mpdf = new mPDF('utf-8', 'A5', '8', '', 10, 10, 22, 22, 10, 20);
		for($count = 1;$count <= $totalpages; $count++){
			$templatevariables = array();
			$tview = '';
			if ($count == 1){
				$templatevariables = $this->front_page_paramters(false, $bookletordervalue);
				$tview = "bookletpdf/template$this->BookletTemplateID/front_page";
			} else if ($count == $totalpages){
				$templatevariables = $this->back_page_paramters(false, $bookletordervalue);
				$tview = "bookletpdf/template$this->BookletTemplateID/back_page";
			} else {
				$templatevariables = $this->other_page_paramters($count, false, $bookletordervalue);
				$tview = "bookletpdf/template$this->BookletTemplateID/other_page";
			}
			ob_start();
			$this->load->view($tview, $templatevariables);
			$content = ob_get_contents();
			ob_end_clean();
			$mpdf->WriteHTML($content);
			if ($count != $totalpages){
				$mpdf->AddPage();
			}
		}
		$mpdf->Output();
	}
}