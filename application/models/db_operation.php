<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class db_operation extends CI_Model {
	
/* insert the register form in datebase 	
	example:- $this->common->data_insert("table name","Array");	
*/	
	public function __construct(){
        parent::__construct();
    }

	public function data_insert($table,$data) {
		$query = $this->db->insert($table, $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function data_update($table, $update_array, $where_array){
		if(is_array($where_array))
		{
			foreach($where_array as $key => $value)
			{
				$this->db->where($key, $value);
			}	
		}
		$this->db->update($table,$update_array);
		return true;
	}
	
/*	
example:- $this->common->data_update($table,"name","value");	
*/	
	public function data_delete($table, $whereconditions) {
		if (!empty($whereconditions) && is_array($whereconditions) && count($whereconditions) > 0){
			foreach($whereconditions as $key => $val){
				$this->db->where($key, $val);
			}
		} else {
			/*For security purpose this checking has been made otherwise all the record has been deleted from the table*/
			return;
		}
		$this->db->delete($table);
		return true;
		 
	}
	
	
/* 
select query 
$column = " field1,field2 ";
$where = "WHERE id = value";
$order = "ORDER BY column_name {ASC|DESC}";
example:- $this->common->select($table, $where);
*/
    public function select($table, $column = " * ",$where ="",$order ="") {	
		//$sql = "SELECT * FROM ".$table;		
		$sql = "SELECT ".$column." FROM ".$table;		
		if($where!='')
		{
		 $sql .= " ".$where;
		}
		if($order!='')
		{
		 $sql .= " ".$order;
		}

		$query = $this->db->query($sql);
		if($query->num_rows()>0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

/* 
select query 
$column = " field1,field2 ";
$where = "WHERE id = value";
$order = "ORDER BY column_name {ASC|DESC}";
example:- $this->common->select($table, $where);
*/
    public function selectSQL($sqlexpr) {	
		$query = $this->db->query($sqlexpr);
		if($query->num_rows()>0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	
}