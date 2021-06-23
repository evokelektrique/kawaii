<?php

///////////////////////////
// Comment section model //
///////////////////////////
class Comments_model extends CI_Model {

	// Construct
	public function __construct() {
		parent::__construct();
	}

	public function get_latest_comments($limit=null, $start=null) {
		$this->db->select('*');
		$this->db->from("comments");
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}
	public function count_comments() {
		return $this->db->count_all('comments');
	}

	public function get_comment_by_id($id) {
		$this->db->select('*');
		$this->db->from('comments');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_comments_by_post($post_id) {
		$this->db->select('*');
		$this->db->from('comments');
		$this->db->where('comment_post_id', $post_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_comments_by_reply_id($reply_id) {
		$this->db->select('*');
		$this->db->from('comments');
		$this->db->where('comment_reply_id', $reply_id);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_comments_by_user_id($user_id) {
		$this->db->select('*');
		$this->db->from('comments');
		$this->db->where('comment_user_id', $user_id);
		$this->db->where('comment_approved', 'yes');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		return $query->result();
	}


	// Insert comment data to database
	public function save_comment($data) {
		$this->db->insert('comments', $data);
		return $this->db->insert_id();
	}

	// Update comment data from database
	public function update_comment($data, $comment_id) {
		$this->db->set($data);
		$this->db->where('id', $comment_id);
		$this->db->update('comments');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

	// Delete comment from database
	public function delete_comment($comment_id) {
		$this->db->where('id', $comment_id);
		$this->db->delete('comments');
	}

}