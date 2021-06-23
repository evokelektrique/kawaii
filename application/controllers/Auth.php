<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Frontend Index Page
class Auth extends CI_Controller {
	protected $template;
	public function __construct() {
		parent::__construct();
		// Loading Libraries
		$this->load->library('parser');
		$this->load->library('session');
		$this->load->library('encryption');
		$this->load->library('form_validation');
		// Loading Custom Libraries
		$this->load->library('MangaReader');
		// Loading Helpers
		$this->load->helper('url');
		$this->load->helper('captcha');
		// Loading Custom Models
		$this->load->model('frontend/auth_model');
		$this->load->model('frontend/captcha_model');

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

		if(!$this->uri->segment(2) == 'logout') {
			if($this->session->userdata('admin')) {
				redirect(base_url().'dashboard');
			}
			if($this->session->userdata('user_id')) {
				redirect(base_url().'');
			}
		}

	}

	
	public function index() {
		$template = $this->template;
		$captcha_values = array(
			'word'          => rand(1,999999),
			'img_path'      => './public/img/captcha_images/',
			'img_url'       => base_url('public').'/img/captcha_images/',
			'font_path'     => base_url('public').'/font/Vazir-Bold-FD.ttf',
			'img_width'     => '100',
			'img_height'    => 45,
			'word_length'   => 8,
			'colors'        => array(
				'background'     => array(255, 255, 255),
				'border'         => array(255, 255, 255),
				'text'           => array(0, 0, 0),
				'grid'           => array(255, 75, 100)
			)
		);


		$captcha = create_captcha($captcha_values);
		$captcha_data = array(
		    'captcha_time'  => $captcha['time'],
		    'ip_address'    => $this->input->ip_address(),
		    'word'          => $captcha['word']
        );
		$this->captcha_model->save_captcha($captcha_data);
		$this->session->set_flashdata('captcha', $captcha_data);
		$data = array(
			'template_name' => $template,
			'captcha' => $captcha['image'],
			'csrf_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
			'title' 	=> 'ورود و ثبت نام',
		);
		$this->parser->parse("frontend/$template/auth", $data);
	}


	///////////////////////
	// User Registration //
	///////////////////////
	public function register() {
		$template = $this->template;
		// // Captcha Configuration
		// $captcha_values = array(
		// 	'word'          => rand(1,999999),
		// 	'img_path'      => './public/img/captcha_images/',
		// 	'img_url'       => base_url('public').'/img/captcha_images/',
		// 	'font_path'     => base_url('public').'/font/Vazir-Bold-FD.ttf',
		// 	'img_width'     => '100',
		// 	'img_height'    => 45,
		// 	'word_length'   => 8,
		// 	'colors'        => array(
		// 		'background'     => array(255, 255, 255),
		// 		'border'         => array(255, 255, 255),
		// 		'text'           => array(0, 0, 0),
		// 		'grid'           => array(255, 75, 100)
		// 	)
		// );

		// // Generating Captcha Image
		// $captcha = create_captcha($captcha_values);
		$data = array(
			'template_name' => $template, /*Template Name*/
		);

		// Validation Config
		$config = array(
			'required' => 'فیلد %s الزامی می باشد',
			'max_length' => 'حداکثر فیلد %s باید 12 کاراکتر باشد',
			'min_length' => 'حداقل فیلد %s باید 5 کاراکتر باشد',
			'is_unique' => '%s وارد شده قبلا ثبت گردیده است',
			'matches' => '%s وارد شده مطاقبت ندارد',
		);

		// Username validation
		$this->form_validation->set_rules('username', 'نام کاربری', 
			array(
				'required',
				'min_length[1]',
				'max_length[30]',
				'trim',
				'is_unique[users.username]'
			),$config);

		// Firstname validation
		$this->form_validation->set_rules('firstname', 'نام', 
			array(
				'min_length[1]',
				'max_length[40]',
				'trim'
			),$config);

		// Lastname validation
		$this->form_validation->set_rules('lastname', 'نام خانوادگی', 
			array(
				'min_length[1]',
				'max_length[40]',
				'trim',
			),$config);

		// Email validation
		$this->form_validation->set_rules('email', 'ایمیل', 
			array(
				'required',
				'min_length[5]',
				'max_length[40]',
				'trim',
				'is_unique[users.email]'
			),$config);

		// Password Validation
		$this->form_validation->set_rules('password', 'رمز عبور', 
			array(
				'required',
				'min_length[5]',
				'max_length[30]',
				'trim'
			), $config);		

		// Captcha Validation
		$this->form_validation->set_rules('captcha', 'کد امنیتی', 
			array(
				'required',
				'max_length[30]',
				'trim'
			), $config);

		// Password Confirmation Validation
		$this->form_validation->set_rules('password_confirm', 'تکرار رمز عبور', 
			array(
				'required',
				'trim',
				'matches[password]'
			), $config);

		// Validating Inputs
		if($this->form_validation->run()) {
			// $captcha = $this->session->flashdata('captcha');
			// if($this->captcha_model->captcha_exists($captcha)) {
				$data = array(
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'email' => $this->input->post('email'),
					'username' => $this->input->post('username'),
					'password' => $this->encryption->encrypt($this->input->post('password'))
				);
				// Registering And Return [ID]
				$register_id = $this->auth_model->register($data);
				if($register_id > 0) {
					// $this->session->set_userdata('success', 'با موفقیت وارد شدید');
					echo $this->mangareader->create_response(1, base_url().'dashboard',"با موفقیت ثبت نام گردید");
				} else {
					echo $this->mangareader->create_response(1, base_url().'dashboard',"ثبت نام به مشکل بر خورد");
				}
			// } else {
			// 	echo $this->mangareader->create_response(2, base_url().'auth', 'کد امنیتی نا معتبر');
			// }


			// $this->parser->parse('frontend/auth', $data);
		} else {
			// $this->parser->parse('frontend/auth', $data);
			// echo ;
			// Status [1 = ok | 2 = field validation error | 3 = error]
			echo $this->mangareader->create_response(2, base_url().'auth', validation_errors());
		}
	}



	////////////////
	// User Login //
	////////////////
	public function login() {
		$template = $this->template;
		$data = array(
			'template_name' => $template, /*Template Name*/
		);


		// Validation Config
		$config = array(
			'required' => 'فیلد %s الزامی می باشد',
			'max_length' => 'حداکثر فیلد %s باید 12 کاراکتر باشد',
			'min_length' => 'حداقل فیلد %s باید 5 کاراکتر باشد',
			'is_unique' => '%s وارد شده قبلا ثبت گردیده است',
		);

		// Email validation
		$this->form_validation->set_rules('email', 'ایمیل', 
			array(
				'required',
				'min_length[1]',
				'max_length[30]',
				'trim'
			),$config);


		// Password validation
		$this->form_validation->set_rules('password', 'رمز عبور', 
			array(
				'required',
				'min_length[1]',
				'max_length[30]',
				'trim',
			),$config);

		// Captcha Validation
		$this->form_validation->set_rules('captcha', 'کد امنیتی', 
			array(
				'required',
				'max_length[30]',
				'trim'
			), $config);

		if($this->form_validation->run()) {
			// $captcha = $this->session->flashdata('captcha');
			// if($this->captcha_model->captcha_exists($captcha)) {
				$data = array(
					'email' => $this->input->post('email'),
					'password' => $this->input->post('password')
				);
				$login_status = $this->auth_model->login($data);
				if($login_status) {
					if($this->session->userdata('admin')) {
						echo $this->mangareader->create_response(1, base_url().'dashboard', 'ورود با موفقیت انجام گردید');

					} else {
						echo $this->mangareader->create_response(1, base_url().'', 'ورود با موفقیت انجام گردید');
					}
				} else {
					echo $this->mangareader->create_response(2, base_url().'auth', 'اطلاعات وارد شده اشتباه می باشند');
				}
			// } else {
			// 	echo $this->mangareader->create_response(2, base_url().'auth', 'کد امنیتی نا معتبر');
			// }
		} else {
			echo $this->mangareader->create_response(2, base_url().'auth', validation_errors());
		}
	}


	public function logout() {
		$data = $this->session->all_userdata();
		foreach ($data as $row => $value) {
			$this->session->unset_userdata($row);
		}
		redirect(base_url()."");
	}

}