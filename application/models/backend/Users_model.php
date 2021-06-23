<?php
class Users_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_all_users($limit=null, $start=null) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->order_by('id', 'desc');
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_users_by_id($user_id) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $user_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_user_by_username($username) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('username', $username);
		$query = $this->db->get();
		return $query->result();
	}

	public function update_profile_cover_url($data, $user_id) {
		$this->db->set($data);
		$this->db->where('id', $user_id);
		$this->db->update('users');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

	public function update_profile_picture_url($data, $user_id) {
		$this->db->set($data);
		$this->db->where('id', $user_id);
		$this->db->update('users');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

	public function users_count() {
		return $this->db->count_all('users');
	}

	public function update_user($data, $user_id) {
		$this->db->set($data);
		$this->db->where('id', $user_id);
		$this->db->update('users');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

}