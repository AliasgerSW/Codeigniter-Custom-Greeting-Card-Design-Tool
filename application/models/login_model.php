<?php

class Login_model extends CI_Model {

    var $details;

    function validate_user($email, $password){		
		
		$this->db->select('*');
        $this->db->from('users');
        $where = array('email'=>$email,'password'=>$password,'status'=>'1');
		$this->db->where($where);
		$result = $this->db->get();
		$records = $result->num_rows();
		$recordsData = $result->result_array();
		if(!empty($recordsData))
		{
			$newdata = array('user_id'=>$recordsData[0]['user_id'],
							 'name'=> $recordsData[0]['username'],
							 'profile_image'=>$recordsData[0]['profile_image'],
							 'email'=>$recordsData[0]['email'],
							 'firstname'=>$recordsData[0]['firstname'],
							 'lastname'=>$recordsData[0]['lastname'],
							 'isadmin'=>$recordsData[0]['isadmin'],
							 'contact'=>$recordsData[0]['user_contact'],
							 'address'=>$recordsData[0]['user_address'],
							 'city'=>$recordsData[0]['user_city'],
							 );
		
			 $this->session->set_userdata($newdata);
			return true;
		}else{ return false;}
		
    }

   
	function encrypt_password($password){
		$this->load->library('encrypt');
		$key = $this->config->item('encryption_key'); //'super-secret-key';
		return $this->encrypt->encode($password,$key);
	}
	 
    function decrypt_password($value){
		$this->load->library('encrypt');	 
		return $this->encrypt->decode($value);		
		
	}
	
	
}

