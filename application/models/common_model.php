<?php

class Common_model extends CI_Model {

    // check unique email
	public function check_uniqueEmail($email)
	{
		$this->db->select('*')
				 ->from('users')
				 ->where('email',$email);
		$result = $this->db->get()->num_rows();
		if(!empty($result)){return $result;}else{return false;}		 
	}
	
	
	public function data_insert($table,$data,$where= '')
	{
		$query = $this->db->insert($table, $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	
}

