<?php



class Stats_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function watched_chapters($user_id) {
		$this->db->select('*');
		$this->db->from('chapter_stats');
		$this->db->where('user_id', $user_id);
		$this->db->limit(10);
		$query = $this->db->get();
		return $query->result();
	}

	public function add_chapter_watch($data) {

		$this->db->insert('chapter_stats', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_total_watched_by_article_id($article_id) {
		$this->db->select('*');
		$this->db->from('chapter_stats');
		$this->db->where('article_id', $article_id);
		$query = $this->db->get();
		return $query->result();
	}

	function get_chartdata() {
		$from = date("Y-m-d",strtotime('1 months ago'));
		$to = date("Y-m-d",strtotime('+1 day'));
		// SELECT * FROM tokens WHERE 
		$where = "created_at >= '".$from."' AND created_at <= '".$to."'";
		$this->db->select("*");
		$this->db->from("users");
		$this->db->where($where);
		$this->db->order_by('id', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

}


