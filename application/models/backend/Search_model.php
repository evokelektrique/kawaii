<?php
class Search_model extends CI_Model {
	public function __construct() {
		parent::__construct();

	}



	public function search($query, $limit, $start, $sort='DESC', $like='name') {

		$this->db->select('*');
		$this->db->from("posts");
		$this->db->like("$like", $query);
		$this->db->order_by('id', "$sort");
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$result = $this->db->get();
		return $result->result();
	}

	public function search_rows($query, $sort='DESC', $type='articles') {

		$this->db->select('*');
		$this->db->from("posts");
		$this->db->like('name', $query);
		$this->db->order_by('id', "$sort");
		$result = $this->db->get();
		return $result->num_rows();
	}
}