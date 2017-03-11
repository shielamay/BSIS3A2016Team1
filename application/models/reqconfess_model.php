<?php

class reqconfess_model extends CI_Model{
	public function construct(){
		
		parent::__construct();
	}

	
	public function req($data) {
	
		$this->db->insert('request', $data);
	}
		
	function getPosts(){
		$this->db->select("*");
		$this->db->from('confession');
		$this->db->join('agree', 'confession.id_confession=agree.id_confess_agr', 'left');
		$this->db->order_by('date', 'DESC');
		$this->db->order_by('time', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}


	function getPosts2(){
		$this->db->select("*");
		$this->db->from('request');
		$this->db->order_by('id_request', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getPosts3(){
		$this->db->select("*");
		$this->db->from('request');
		$this->db->where('approved', '0');
		$this->db->where('declined', '0');
		$this->db->order_by('id_request', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getApproved(){
		$this->db->select("*");
		$this->db->from('request');
		$this->db->where('approved', '1');
		$this->db->where('declined', '0');
		$this->db->order_by('id_request', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function getDeclined(){
		$this->db->select("*");
		$this->db->from('request');
		$this->db->where('approved', '0');
		$this->db->where('declined', '1');
		$this->db->order_by('id_request', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function addComment($data){
	$this->db->insert('comment', $data);	
	}

	function getComments(){
		$this->db->select("*");
		$this->db->from('comment');
		$this->db->join('personal_info', 'comment.id_user_com=personal_info.id_personal_info', 'left');
		$this->db->order_by('comm_date', 'ASC');
		$this->db->order_by('comm_time', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}



}

?>