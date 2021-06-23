<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {

    protected $template;
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Tehran');
        $this->load->helper('url');
        $this->load->library('parser');
        $this->load->library('session');
        $this->load->library('mangareader');
        $this->load->library('form_validation');
        // Loading Post_Model model
        $this->load->model('backend/post_model');
        $this->load->model('backend/link_model');
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
    
    public function index() {
        $template = $this->template;
        $this->load->model('backend/users_model');
        // Custom variables
        $options = array(
            'template_name' => $template,
            'body_class' => '',
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'title'     => 'درباره ما',
        );
        // Configuring view data
        // Loading views
        $header = $this->load->view("frontend/$template/layout/header.php", $options, true);
        $footer = $this->load->view("frontend/$template/layout/footer.php", null, true);
        $data = array();
        // Defining variables to views
        $data['header'] = $header;
        $data['footer'] = $footer;
        $data['settings'] = $this->settings_model->settings(); 
        $data['articles'] = $this->post_model->get_latest_posts(12,0);
        $data['popular_articles'] = $this->post_model->get_lates_posts_sort_by('view_count',12,0);
        $data['links'] = $this->link_model->get_latest_links();
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();

        if($this->session->has_userdata('logged_in')) {
            $data['user'] = $this->users_model->get_users_by_id($this->session->userdata('user_id'));
        }
        // Parsing view
        $this->parser->parse("frontend/$template/about", $data);
    }


}