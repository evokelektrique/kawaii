<?php


class Captcha_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function save_captcha($data) {
		$this->db->insert('captcha', $data);
		return $this->db->insert_id();
	}

	public function captcha_exists($data) {
		if(!empty($data)) {
			$expiration = time() - 7200; // Two hour limit
			$this->db->where('captcha_time < ', $expiration)
			        ->delete('captcha');

			// Then see if a captcha exists:
			$sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
			$binds = array($data['word'], $data['ip_address'], $data['captcha_time']);
			$query = $this->db->query($sql, $binds);
			$row = $query->row();

			if ($row->count > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

}