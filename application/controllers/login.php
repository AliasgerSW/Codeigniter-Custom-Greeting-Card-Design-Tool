<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
            parent::__construct();
            
			$this->load->database();  
			
			 $this->load->helper(array('url','common_helper'));
			 
			$this->load->library(array('form_validation'));
			
			$this->load->model(array('common_model','login_model'));
			
			loadsetting();
	
    }
     
	public function index()
	{ 
		
		$this->load->view('login/login');
	}
	
	public function auth()
	{   
	    //$remember = $this->input->post('remember_me',TRUE);
		
		$user_name = $this->input->post('login_username',TRUE);
		
		$user_pass = $this->input->post('login_password',TRUE);
		
			
		if($this->login_model->validate_user($user_name,$user_pass)) 
		{
			$id = $this->session->userdata('user_id');
			$is_admin = $this->session->userdata('isadmin');
			//$logintime = array('last_login' => time());
			$this->db->where('user_id', $id);
			//$this->db->update('users', $logintime);
			
			//$this->db->update('users');  
			if ($is_admin == "1"){
				redirect('admin/dashboard');
			} else {
				redirect();
			}
		} else 
		   { 
			    $data['msg'] = "Emails & Password doesn't match"; 
			    $this->load->view('login/login',$data);	   
		   }
	}
	
	public function register()
	{
		if($_POST){
			//echo '<pre/>';print_r($_POST);die;
			$name        = $this->input->post('register_username',TRUE);
			$email       = $this->input->post('register_email',TRUE);
			$password    = $this->input->post('register_password',TRUE);
			$address     = $this->input->post('address',TRUE);
			$contact     = $this->input->post('contact',TRUE);
			$city        = $this->input->post('city',TRUE);
			
			$role_id     = 4;
			
			$status      = 'Inactive';
			$chkEmail    = $this->common_model->check_uniqueEmail($email);
			echo $chkEmail;die;
			$enc_pass    = $this->login_model->encrypt_password($password);
			$ins_data = array('username'=>$name,'email'=>$email,'password'=>$password,
			                  'user_address'=>$address,'user_contact'=>$contact,
							  'user_city'=>$city,'role_id'=>$role_id,
							  'status'=>$status);
			if(!empty($chkEmail))
			{
				$data['result'] = $ins_data;
				$data['msg'] = 'Email Already Registered';
				$this->load->view('login/register',$data);
			}
			else
			{
				$this->common_model->data_insert('users',$ins_data);
				redirect('login','refresh');
			}
			
		}
		else{
		$this->load->view('login/register');
	    }
	}
	
	public function forget_password()
	{
		$this->load->view('login/forget_password');
	}
	
	
	public function logout(){		

		$this->session->sess_destroy();

      	redirect('','refresh');

	}
}