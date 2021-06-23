<?php
class link_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function get_latest_links($limit=null, $start=null) {
		$this->db->select("*");
		$this->db->from('links');
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function create_link($data) {
		$this->db->insert('links', $data);
		return $this->db->insert_id();
	}

	public function get_link_by_id($id) {
		$this->db->select("*");
		$this->db->from('links');
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function update_link($id, $data) {
		$this->db->set($data);
		$this->db->where('id', $id);
		$this->db->update('links');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

	public function count_links() {
		return $this->db->count_all('links');
	}

	public function delete_link($link_id) {
		$this->db->where('id', $link_id);
		$this->db->delete('links');
	}


	public function get_parent_links() {
		$this->db->select("*");
		$this->db->from('links');
		$this->db->where('parent_id', '0');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_links_by_parent_id($parent_id) {
		$this->db->select("*");
		$this->db->from('links');
		$this->db->where('parent_id', $parent_id);
		$query = $this->db->get();
		return $query->result();
	}

}