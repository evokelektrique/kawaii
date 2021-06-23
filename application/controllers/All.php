<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Frontend Index Page
class All extends CI_Controller {
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
		$this->load->model('backend/link_model');
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
	
	public function index($sort = 'all', $page=0) {
		$template = $this->template;
        $this->load->library('pagination');
        $config = array();
        $config['total_rows'] = $this->post_model->count_posts();
        $config['per_page'] = 10;
        $config['next_link'] = 'بعدی';
        $config['prev_link'] = 'قبلی';
        $config['last_link'] = 'آخرین';
        $config['first_link'] = 'اولین';
        $config['attributes'] = array('class' => 'pagination-link');
		$sort_name = null;
		switch ($sort) {
			case 'all':
		        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		        $config['base_url'] = base_url('all') . '';
				$articles = $this->post_model->get_lates_posts_sort_by('id',$config['per_page'], $page);
				$sort_name = 'همه';
				break;
				
			case 'popular':
		        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
				$config['base_url'] = base_url('all/popular') . '';
				$articles = $this->post_model->get_lates_posts_sort_by('view_count',$config['per_page'], $page);
				$sort_name = 'پرطرفدار ها';
				break;
		
			default:
		        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		        $config['base_url'] = base_url('all') . '';
				$articles = $this->post_model->get_lates_posts_sort_by('id',$config['per_page'], $page);
				$sort_name = 'همه';
				break;
		}

		// Configuring Pagination
        $this->pagination->initialize($config);

		// Custom variables
		$options = array(
			'template_name' => $template,
			'body_class' => '',
			'csrf_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
			'title' 	=> 'همه مطالب',
		);
		// Configuring view data
		// Loading views
		$header = $this->load->view("frontend/$template/layout/header.php", $options, true);
		$footer = $this->load->view("frontend/$template/layout/footer.php", null, true);
		$data = array();
		// Defining variables to views
		$data['header'] = $header;
		$data['settings'] = $this->settings_model->settings(); 
		$data['footer'] = $footer;
		$data['articles'] = $articles;
		$data['sort_name'] = $sort_name;
		$data['pagination'] = $this->pagination->create_links();
		$data['links'] = $this->link_model->get_latest_links();
		if($this->session->has_userdata('logged_in')) {
			$data['user'] = $this->users_model->get_users_by_id($this->session->userdata('user_id'));
		}
		// Parsing view
		$this->parser->parse("frontend/$template/all", $data);

	}


}
