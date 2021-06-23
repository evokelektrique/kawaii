<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {
    protected $template;
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Tehran');
        $this->load->helper('url');
        $this->load->library('parser');
        $this->load->library('session');
        // $this->load->library('form_validation');
        $this->load->library('mangareader');
        $this->load->library('form_validation');
        // Loading Post_Model model
        $this->load->model('backend/post_model');
        $this->load->model('backend/link_model');
        $this->load->model('backend/contacts_model');
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
            'title'     => 'تماس با ما',
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
        $this->parser->parse("frontend/$template/contact", $data);
    }


    public function send() {

        // Validation Config
        $config = array(
            'required' => 'فیلد %s الزامی می باشد',
            'max_length' => 'حداکثر فیلد %s باید 12 کاراکتر باشد',
            'min_length' => 'حداقل فیلد %s باید 5 کاراکتر باشد',
            'valid_email' => '%s وارد شده نا معتبر می باشد',
            'matches' => '%s وارد شده مطاقبت ندارد',
        );

        // Text validation
        $this->form_validation->set_rules('text', 'متن ', 
            array(
                'required',
                'min_length[1]',
                'max_length[2000]',
            ),$config);

        // Name validation
        $this->form_validation->set_rules('name', 'نام', 
            array(
                'required',
                'min_length[1]',
                'max_length[100]',
            ),$config);
        
        // Username validation
        $this->form_validation->set_rules('email', 'ایمیل', 
            array(
                'required',
                'valid_email',
                'min_length[1]',
                'max_length[100]',
            ),$config);

        if($this->form_validation->run()) {
            $data = array(
                'name'   => $this->input->post('name'),
                'email'  => $this->input->post('email'),
                'text'  => $this->input->post('text'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $contact_id = $this->contacts_model->save_contact($data);
            if($contact_id) {
                // echo $this->mangareader->create_response(1, '', "پیام با موفقیت ارسال شد");
                $this->session->set_flashdata('success', 'پیام با موفقیت ارسال شد');
                redirect(base_url('contact'));
            } else {
                // echo $this->mangareader->create_response(3, '', "ارسال پیام نا موفق بود");
                $this->session->set_flashdata('unsuccess_contact', 'ارسال پیام نا موفق بود');
                redirect(base_url('contact'));
            }

        } else {
                $this->session->set_flashdata('unsuccess_contact', validation_errors());
                redirect(base_url('contact'));
            // echo $this->mangareader->create_response(2, '', validation_errors());
        }
    }

}