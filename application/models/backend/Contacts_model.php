<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('backend/contacts_model');
		$this->load->model('frontend/comments_model');
		
	}

	public function get_all_contacts($limit=null, $start=null) {
		$this->db->select("*");
		$this->db->from('contacts');
		$this->db->order_by('id', 'desc');
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}

	// public function get_all_contacts_by_article_id($article_id) {
	// 	$this->db->select("*");
	// 	$this->db->from('contacts');
	// 	$this->db->where('type', 'article');
	// 	$this->db->where('type_id', $article_id);
	// 	$this->db->order_by('id', 'desc');
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }

	public function get_contact_by_id($id) {
		$this->db->select("*");
		$this->db->from('contacts');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	// public function seen($id) {
	// 	$data = array('seen' => 1);
	// 	return $this->update_contact($data, $id);
	// }

	public function save_contact($data) {
		$this->db->insert('contacts', $data);
		return $this->db->insert_id();	
	}

	// public function update_contact($data, $id) {
	// 	$this->db->set($data);
	// 	$this->db->where('id', $id);
	// 	$this->db->update('contacts');
	// 	$rows = $this->db->affected_rows();
	// 	if($rows > 0) {
	// 		return $rows;
	// 	} else {
	// 		return false;
	// 	}
	// }

	public function count_contacts() {
		return $this->db->count_all('contacts');
	}

	// public function close_content($id, $type, $database) {
	// 	if($type == 'article') {
	// 		$data = array('approved' => 'no');
	// 	} else {
	// 		$data = array('comment_approved' => 'no');
	// 	}
	// 	$this->db->set($data);
	// 	$this->db->where('id', $id);
	// 	$this->db->update($database);
	// 	$rows = $this->db->affected_rows();
	// 	if($rows > 0) {
	// 		return $rows;
	// 	} else {
	// 		return false;
	// 	}
	// }

	// public function free_content($id, $type, $database) {
	// 	if($type == 'article') {
	// 		$data = array('approved' => 'yes');
	// 	} else {
	// 		$data = array('comment_approved' => 'yes');
	// 	}
	// 	$this->db->set($data);
	// 	$this->db->where('id', $id);
	// 	$this->db->update($database);
	// 	$rows = $this->db->affected_rows();
	// 	if($rows > 0) {
	// 		return $rows;
	// 	} else {
	// 		return false;
	// 	}
	// }

	public function delete_contact($contact_id) {
		$this->db->where('id', $contact_id);
		$this->db->delete('contacts');
	}


}
