<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Frontend Index Page
class Search extends CI_Controller {
	protected $template;
	public function __construct() {
		parent::__construct();
        date_default_timezone_set('Asia/Tehran');
        $this->load->helper('url');
        $this->load->library('parser');
        $this->load->library('session');
        // $this->load->library('form_validation');
        $this->load->library('mangareader');
		// Loading Post_Model model
		$this->load->model('backend/post_model');
		$this->load->model('backend/search_model');
		$this->load->model('backend/settings_model');
		$this->load->model('backend/users_model');
		$template_id = $this->settings_model->settings()[0]->site_template;
		$this->template = null;
		switch ($template_id) {
			case '1':
				$this->template = 'default_template';
				break;
			case '2':
				$this->template = 'dark_template';
				break;
			default:
				$this->template = 'default_template';
				break;
		}
	}
	
	public function index($query=null,$latest=null) {
		$template = $this->template;
        $this->load->library('pagination');
        $this->load->model('backend/link_model');
        $config = array();
        $config['per_page'] = 12;
        $config['next_link'] = 'بعدی';
        $config['prev_link'] = 'قبلی';
        $config['last_link'] = 'آخرین';
        $config['first_link'] = 'اولین';
        $config['base_url'] = base_url("search/$query");
        $config['attributes'] = array('class' => 'pagination-link');
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		// Custom variables
		$options = array(
			'template_name' => $template,
			'body_class' => 'search_',
			'csrf_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
			'title' 	=> "جستجوی $query",
		);
		// Configuring view data
		// Loading views
		$header = $this->load->view("frontend/$template/layout/header.php", $options, true);
		$footer = $this->load->view("frontend/$template/layout/footer.php", null, true);
		$data = array();
		if($query !== null OR !empty($query)) {
			$query = str_replace("%20"," ",$query);
			$data['articles'] = $this->search_model->search(urldecode($query),$config['per_page'], $page);
	        $config['total_rows'] = $this->search_model->search_rows($query);

		} else {
			$data['articles'] = $this->post_model->get_latest_posts($config['per_page'], $page);
	        $config['base_url'] = base_url('search/latest');
	        $config['total_rows'] = $this->post_model->count_posts();
		}
		// Initializing pagination links
        $this->pagination->initialize($config);
		// Defining variables to views
		$data['header'] = $header;
		$data['settings'] = $this->settings_model->settings(); 
		$data['footer'] = $footer;
		$data['sort_name'] = 'جستجو' . ' (' .count($data['articles']) . ')';
		$data['links'] = $this->link_model->get_latest_links();
		$data['pagination'] = $this->pagination->create_links();
		if($this->session->has_userdata('logged_in')) {
			$data['user'] = $this->users_model->get_users_by_id($this->session->userdata('user_id'));
		}
		// Parsing view
		// echo "$query<br>";
		// echo "$page";
		// var_dump($config['total_rows']);
		// var_dump($data['pagination']);
		// var_dump($data['articles']);
		$this->parser->parse("frontend/$template/search", $data);
	}

	public function latest($page=0) {
		$template = $this->template;
        $this->load->library('pagination');
        $this->load->model('backend/link_model');
        $config = array();
        $config['per_page'] = 12;
        $config['next_link'] = 'بعدی';
        $config['prev_link'] = 'قبلی';
        $config['last_link'] = 'آخرین';
        $config['first_link'] = 'اولین';
        $config['base_url'] = base_url('search/latest');
        $config['attributes'] = array('class' => 'pagination-link');
        $config['total_rows'] = $this->post_model->count_posts();
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

		// Custom variables
		$options = array(
			'template_name' => $template,
			'body_class' => 'latest',
			'csrf_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
		);
		// Configuring view data
		// Loading views
		$header = $this->load->view("frontend/$template/layout/header.php", $options, true);
		$footer = $this->load->view("frontend/$template/layout/footer.php", null, true);
		$data = array();
		$data['articles'] = $this->post_model->get_latest_posts($config['per_page'], $page);
		// Initializing pagination links
		// Defining variables to views
		$data['header'] = $header;
		$data['settings'] = $this->settings_model->settings(); 
		$data['footer'] = $footer;
		$data['sort_name'] = 'آخرین ها' . ' (' .count($data['articles']) . ')';
		$data['pagination'] = $this->pagination->create_links();
		if($this->session->has_userdata('logged_in')) {
			$data['user'] = $this->users_model->get_users_by_id($this->session->userdata('user_id'));
		}
		$data['links'] = $this->link_model->get_latest_links();
		$this->parser->parse("frontend/$template/search", $data);
	}



	public function tag($query=null,$latest=null) {
		$template = $this->template;
        $this->load->library('pagination');
        $this->load->model('backend/link_model');
        $config = array();
        $config['per_page'] = 12;
        $config['next_link'] = 'بعدی';
        $config['prev_link'] = 'قبلی';
        $config['last_link'] = 'آخرین';
        $config['first_link'] = 'اولین';
        $config['base_url'] = base_url("search/$query");
        $config['attributes'] = array('class' => 'pagination-link');
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		// Custom variables
		$options = array(
			'template_name' => $template,
			'body_class' => 'tag',
			'csrf_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
		);
		// Configuring view data
		// Loading views
		$header = $this->load->view("frontend/$template/layout/header.php", $options, true);
		$footer = $this->load->view("frontend/$template/layout/footer.php", null, true);
		$data = array();
		if($query !== null OR !empty($query)) {
			$query = str_replace("%20"," ",$query);
			$query = urldecode($query);
			// $query, $limit, $start, $sort='DESC', $like='name'
			$data['articles'] = $this->search_model->search($query,$config['per_page'], $page, 'DESC', 'tags');
	        $config['total_rows'] = $this->search_model->search_rows($query);
		} else {
			$data['articles'] = $this->post_model->get_latest_posts($config['per_page'], $page);
	        $config['base_url'] = base_url('search/latest');
	        $config['total_rows'] = $this->post_model->count_posts();
		}
		// Initializing pagination links
        $this->pagination->initialize($config);
		// Defining variables to views
		$data['header'] = $header;
		$data['footer'] = $footer;
		$data['settings'] = $this->settings_model->settings(); 
		$data['sort_name'] = 'جستجو' . ' (' .count($data['articles']) . ')';
		$data['pagination'] = $this->pagination->create_links();
		if($this->session->has_userdata('logged_in')) {
			$data['user'] = $this->users_model->get_users_by_id($this->session->userdata('user_id'));
		}
		$data['links'] = $this->link_model->get_latest_links();
		// Parsing view
		// echo "$query<br>";
		// echo "$page";
		// var_dump($config['total_rows']);
		// var_dump($data['pagination']);
		// var_dump($data['articles']);
		$this->parser->parse("frontend/$template/search", $data);

	}


}
