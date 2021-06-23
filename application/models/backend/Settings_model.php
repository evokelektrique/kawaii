<?php
class Settings_model extends CI_Model {
	public function __construct() {
		parent::__construct();

	}



	public function settings() {
		$this->db->select('*');
		$this->db->from('settings');
		$query = $this->db->get();
		return $query->result();
	}


	public function update($data) {
		$this->db->set($data);
		$this->db->where('id', 1);
		$this->db->update('settings');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

}