<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function encrypt_data($data){
		
  $CI = & get_instance();
  $CI->load->library('encrypt');	 
  $key = 'super-secret-key';
  return  $CI->encrypt->encode($data, $key);
		
}
	 
function decrypt_data($value){
	$CI = & get_instance();
	$CI->load->library('encrypt');	 
	$key = 'super-secret-key';
	return  $CI->encrypt->decode($value, $key);
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function getpagevalues($pagekey, $ciobj){
	$whereexp = " where page_key = '" . $pagekey . "'";
	$dbvalues = $ciobj->db_operation->select( 'pages', '*', $whereexp);
	
	if (empty($dbvalues) || count($dbvalues) <= 0){
		return array();
	}
	
	$pagevalues = array();
	$pagevalues['id'] = $dbvalues[0]['page_id'];
	$pagevalues['key'] = $dbvalues[0]['page_key'];
	$pagevalues['title'] = $dbvalues[0]['page_title'];
	$pagevalues['content'] = $dbvalues[0]['page_content'];
	
	$dbmenus = $ciobj->db_operation->selectSQL('
Select m.*, p.name as parentname from menu m left outer join menu p on m.parent_id = p.menu_id');
	
	$menuvalues = array();
	foreach($dbmenus as $dbmenu){
		if (!empty($dbmenu['parent_id'])){
			$menuvalues[$dbmenu['parent_id']][$dbmenu['menu_id']] = array('url' => $dbmenu['url'], 'name' => $dbmenu['name']);
		} else {
			$menuvalues[$dbmenu['menu_id']]['0'] = array('url' => $dbmenu['url'], 'name' => $dbmenu['name']);
		}
	}
	$pagevalues['menulist'] = $menuvalues;
	return $pagevalues;
}

function get_sessionData($fieldname){
	$CI = & get_instance();
	$CI->load->database();
	$CI->load->library('session');
	$result = $CI->session->userdata($fieldname);
	if(!empty($result)){
		return $result;
	}else{
		return false;
	}
}

function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

/* user type check */
function user_check() {
	$CI = & get_instance();
	$CI->load->database();
	$CI->load->library('session');
	if(!$CI->session->userdata('user_id')) {
		$CI->session->sess_destroy(); $CI->session->set_userdata('warning', 'Your session has expired');
		redirect('login');  exit();
	}
}


function convertimagetograyscale($getimagefrom, $copyimageto){
	$im = createImageFromFile($getimagefrom);
	$success = false;
	if($im && imagefilter($im, IMG_FILTER_GRAYSCALE)) {
		imagepng($im, $copyimageto);
		$success = true;
	}
	imagedestroy($im);
	return $success;
}

function createImageFromFile($filename, $use_include_path = false, $context = null, &$info = null) {
  // try to detect image informations -> info is false if image was not readable or is no php supported image format (a  check for "is_readable" or fileextension is no longer needed)
  $info = array("image"=>getimagesize($filename));
  $info["image"] = getimagesize($filename);
  if($info["image"] === false) throw new InvalidArgumentException("\"".$filename."\" is not readable or no php supported format");
  else
  {
    // fetches fileconten from url and creates an image ressource by string data
    // if file is not readable or not supportet by imagecreate FALSE will be returnes as $imageRes
    $imageRes = imagecreatefromstring(file_get_contents($filename, $use_include_path, $context));
    // export $http_response_header to have this info outside of this function
    if(isset($http_response_header)) $info["http"] = $http_response_header;
    return $imageRes;
  }
}

function loadsetting() {
	$ciobj = & get_instance();
	$ciobj->load->database();
	$ciobj->load->library('session');
	
	$dbvalues = $ciobj->db_operation->select( 'setting', '*', '');
	$settingarray = array();
	foreach($dbvalues as $sv){
		$settingarray[$sv['setting_key']] = $sv['setting_value'] ;
	}
	
	$ciobj->session->set_userdata($settingarray);
}

function get_setting($settingname){
	$CI = & get_instance();
	$result = $CI->session->userdata('setting_' . $settingname);
	if(!empty($result)){
		return $result;
	}else{
		return '';
	}
}

function getorderstatus(){
	$ciobj = & get_instance();
	$dbvalues = $ciobj->db_operation->select( 'template_order_status', '*');
	
	if (empty($dbvalues) || count($dbvalues) <= 0){
		return array();
	}
	
	return $dbvalues;
}

function getcountries(){
	$ciobj = & get_instance();
	$dbvalues = $ciobj->db_operation->select( 'country', '*');
	
	if (empty($dbvalues) || count($dbvalues) <= 0){
		return array();
	}
	
	return $dbvalues;
}
