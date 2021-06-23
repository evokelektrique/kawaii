<?php
class Post_model extends CI_Model {
	public function __construct() {
		parent::__construct();

	}

	public function savedata($data) {
		$this->db->insert('posts', $data);
		return $this->db->insert_id();
	}

	public function get_latest_posts($limit=null, $start=null) {
		$this->db->select("*");
		$this->db->from('posts');
		$this->db->order_by('id', 'desc');
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_lates_posts_sort_by($sort, $limit=null, $start=null) {
		$this->db->select("*");
		$this->db->from('posts');
		$this->db->order_by("{$sort}", 'DESC');
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}



	public function count_posts() {
		return $this->db->count_all('posts');
	}

	public function get_article_by_id($id) {
		$this->db->select('*');
		$this->db->from('posts');
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_article_by_slug($slug) {
		$this->db->select('*');
		$this->db->from('posts');
		$this->db->where('url_slug', $slug);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}
	public function update_article_by_id($id, $data) {
		$this->db->set($data);
		$this->db->where('id', $id);
		$this->db->update('posts');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

	public function delete_article($article_id) {
		$this->db->where('id', $article_id);
		$this->db->delete('posts');
	}

	public function add_chapter($data) {
		$this->db->insert('chapters', $data);
		return $this->db->insert_id();
	}


	public function get_chapters_by_id($id,$limit=null, $start=null) {
		$this->db->select('*');
		$this->db->from('chapters');
		$this->db->where('article_id', $id);
		// $this->db->order_by('id', 'aes');
		if($limit != null) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_chapter_by_id($id) {
		$this->db->select('*');
		$this->db->from('chapters');
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function update_chapter($id, $data) {
		$this->db->set($data);
		$this->db->where('id', $id);
		$this->db->update('chapters');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}

	public function delete_chapter_by_article_id($article_id) {
		$this->db->where('article_id', $article_id);
		$this->db->delete('chapters');
	}

	public function delete_episode_by_episode_id($episode_id) {
		$this->db->where('id', $episode_id);
		$this->db->delete('episodes');
	}

	public function count_chapters() {
		return $this->db->count_all('chapters');
	}




	public function get_latest_episodes($article_id, $chapter_id, $limit=null) {
		$this->db->select('*');
		$this->db->from('episodes');
		$this->db->where('article_id', $article_id);
		$this->db->where('chapter_id', $chapter_id);
		if($limit != null) {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		return $query->result();
	} 

	public function get_episodes_by_id($article_id) {
		$this->db->select('*');
		$this->db->from('episodes');
		$this->db->where('article_id', $article_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_episodes_by_chapter_id($chapter_id) {
		$this->db->select('*');
		$this->db->from('episodes');
		$this->db->where('chapter_id', $chapter_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function delete_episodes_by_article_id($article_id) {
		$this->db->where('article_id', $article_id);
		$this->db->delete('episodes');
	}
	
	public function delete_episodes_by_chapter_id($chapter_id) {
		$this->db->where('chapter_id', $chapter_id);
		$this->db->delete('episodes');
	}


	public function get_episode_by_id($episode_id) {
		$this->db->select('*');
		$this->db->from('episodes');
		$this->db->where('id', $episode_id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function add_episode($article_id, $chapter_id, $data) {
		$this->db->insert('episodes', $data);
		return $this->db->insert_id();
	}

	public function update_episode($id, $data) {
		$this->db->set($data);
		$this->db->where('id', $id);
		$this->db->update('episodes');
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return $rows;
		} else {
			return false;
		}
	}


	public function add_to_likes($data) {
		$this->db->insert('likes', $data);
		return $this->db->insert_id();
	}


	public function get_likes($article_id) {
		$this->db->select('*');
		$this->db->from('likes');
		$this->db->where('article_id', $article_id);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_likes_by_user_id($user_id) {
		$this->db->select('*');
		$this->db->from('likes');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function is_liked($article_id, $user_id) {
		$this->db->select("*");
		$this->db->from('likes');
		$this->db->where('article_id', $article_id);
		$this->db->where('user_id', $user_id);
		$this->db->get();
		$rows = $this->db->affected_rows();
		if($rows > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function add_view($article_id) {
		$this->db->set('view_count', '`view_count`+1', FALSE);
		$this->db->where('id', $article_id);
		$this->db->update('posts');
	}

}