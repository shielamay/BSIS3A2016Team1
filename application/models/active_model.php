<?php

class active_model extends CI_Model{
	public function construct(){
		
		parent::__construct();
	}
	
	
	
	function getPosts(){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('personal_info', 'users.id_users = personal_info.id_personal_info', 'inner');
		$this->db->where('id_users =', $_SESSION['id_no']);	
		$query = $this->db->get();
				
		return $query->result();
	}
	
	function getPosts2(){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('student_info', 'users.id_users = student_info.id_student_info', 'inner');
		$this->db->where('id_users =', $_SESSION['id_no']);	
		$query = $this->db->get();
				
		return $query->result();
	}
	
	function getPosts3(){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('confession_info', 'users.id_users = confession_info.id_confession_info', 'inner');
		$this->db->where('id_users =', $_SESSION['id_no']);	
		$query = $this->db->get();
				
		return $query->result();
	}
	
	function update($data){		
		$this->db->where('id_personal_info', $data['id_users']);
		unset($data['id_users']);
		$this->db->update('personal_info', $data); 
	}
	
	function update2($data){
		$this->db->where('id_student_info', $data['id_users']);
		unset($data['id_users']);			
		$this->db->update('student_info', $data); 
	}

	function update3($data){
		$this->db->where('id_confession_info', $data['id_users']);
		unset($data['id_users']);			
		$this->db->update('confession_info', $data); 
	}

	function logout($data){
		$this->db->where('id_users',$_SESSION['id_no']);
		$this->db->update('users',$data);
	}
}

?>