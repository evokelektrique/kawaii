<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alerts_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('backend/alerts_model');
		$this->load->model('frontend/comments_model');
		
	}

	public function get_all_alerts($limit=null, $start=null) {
		$this->db->select("*");
		$this->db->from('alerts');
		$this->db->order_by('id', 'desc');
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_alerts_by_article_id($article_id) {
		$this->db->select("*");
		$this->db->from('alerts');
		$this->db->where('type', 'article');
		$this->db->where('type_id', $article_id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_alert_by_id($id) {
		$this->db->select("*");
		$this->db->from('alerts');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function seen($id) {
		$data = array('seen' => 1);
		return $this->update_alert($data, $id);
	}

	public function save_alert($data) {
		$this->db->insert('alerts', $data);
		return $this->db->insert_id();	
	}

	public function update_alert($data, $id) {
		$this->db->set($data);
		$this->db->where('id', $id);
		$this->db->update('alerts');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}
	public function count_alerts() {
		return $this->db->count_all('alerts');
	}





	public function close_content($id, $type, $database) {
		if($type == 'article') {
			$data = array('approved' => 'no');
		} else {
			$data = array('comment_approved' => 'no');
		}
		$this->db->set($data);
		$this->db->where('id', $id);
		$this->db->update($database);
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

	public function free_content($id, $type, $database) {
		if($type == 'article') {
			$data = array('approved' => 'yes');
		} else {
			$data = array('comment_approved' => 'yes');
		}
		$this->db->set($data);
		$this->db->where('id', $id);
		$this->db->update($database);
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

	public function delete_alert($alert_id) {
		$this->db->where('id', $alert_id);
		$this->db->delete('alerts');
	}


}
