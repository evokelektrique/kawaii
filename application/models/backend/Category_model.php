<?php
class Category_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function categories($limit=null, $start=null) {
		$this->db->select("*");
		$this->db->from('categories');
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function create_category($data) {
		$this->db->insert('categories', $data);
		return $this->db->insert_id();
	}

	public function get_category_by_id($cat_id) {
		$this->db->select("*");
		$this->db->from('categories');
		$this->db->where('cat_id', $cat_id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function update_category($cat_id, $data) {
		$this->db->set($data);
		$this->db->where('cat_id', $cat_id);
		$this->db->update('categories');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

	public function count_categories() {
		return $this->db->count_all('categories');
	}

	public function delete_category($category_id) {
		$this->db->where('cat_id', $category_id);
		$this->db->delete('categories');
	}

}