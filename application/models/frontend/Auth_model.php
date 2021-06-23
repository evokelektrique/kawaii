<?php
class Auth_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		// Loading Libraries
		$this->load->library('encryption');
		$this->load->library('session');

	}

	public function register($data) {
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	public function login($data) {
		$condition = array('email' => $data['email']);
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where($condition);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$decrypted_password = $this->encryption->decrypt($row->password);
				if($data['password'] == $decrypted_password) {
					if($row->role == 2) {
						$this->session->set_userdata('admin', true);
					}
					$this->session->set_userdata('user_id', $row->id);
					$this->session->set_userdata('logged_in', true);
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}

}