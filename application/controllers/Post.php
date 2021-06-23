<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {
	protected $template;
	public function __construct() {
		parent::__construct();
        date_default_timezone_set('Asia/Tehran');
        $this->load->helper('url');
        $this->load->library('parser');
        $this->load->library('session');
        $this->load->library('mangareader');
		$this->load->model('backend/settings_model');
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
	

	public function index($slug, $id) {
		$template = $this->template;
		if(!empty($slug) || !empty($id)) {
			// Loading Post_Model model
			$this->load->model('backend/post_model');
			$this->load->model('backend/link_model');
			$this->load->model('backend/users_model');
			$this->load->model('backend/search_model');
				// Configuring view data
			// Adding view
			$this->post_model->add_view($id);
			$article = $this->post_model->get_article_by_id($id);
				// Custom variables
				$options = array(
					'template_name' => $template,
					'body_class' => 'single',
					'csrf_name' => $this->security->get_csrf_token_name(),
					'csrf_hash' => $this->security->get_csrf_hash(),
					'title' 	=> $article[0]->name,
				);
			// Configuring view data
			// Loading views
			$header = $this->load->view("frontend/$template/layout/header.php", $options, true);
			$footer = $this->load->view("frontend/$template/layout/footer.php", null, true);
			$data = array();
			// Defining variables to views
			$data['header'] = $header;
			$data['footer'] = $footer;
			$data['article'] = $article;
			$data['settings'] = $this->settings_model->settings(); 
			$data['links'] = $this->link_model->get_latest_links();

			if(!empty($article)) {
				$data['chapters'] = $this->post_model->get_chapters_by_id($id);
				$data['likes'] = $this->post_model->get_likes($id);
				$data['is_liked'] = $this->post_model->is_liked($id, $this->session->userdata('user_id'));
				$data['links'] = $this->link_model->get_latest_links();
				if(!empty($data['chapters'])) {
					$first_chapter_id = $data['chapters'][0]->id;
					$data['screenshots'] = $this->post_model->get_latest_episodes($id, $first_chapter_id, 3);
				}
				$tags = explode(',', $article[0]->tags);
				$data['similar_posts'] = array();
				foreach($tags as $tag) {
					array_push($data['similar_posts'], $this->search_model->search($tag, 2, 0, "DESC", 'tags')[0]);
				}
				if($this->session->has_userdata('logged_in')) {
					$data['user'] = $this->users_model->get_users_by_id($this->session->userdata('user_id'));
				}
				// Parsing view
				$this->parser->parse("frontend/$template/single", $data);

			} else {
				$this->parser->parse("frontend/$template/404", $data);
			}
		} else {
			$this->parser->parse("frontend/$template/404", $data);
		}
	}


	public function chapter($slug, $id, $chapter_id) {
		$template = $this->template;
		// Loading captcha-security helper
		$this->load->helper('captcha');
		$this->load->model('frontend/captcha_model');
		$this->load->model('backend/stats_model');

		if(!empty($slug) || !empty($id)) {
			// Adding to watch section
			if($this->session->has_userdata('logged_in')) {
				$data = array(
					'user_id' => $this->session->userdata('user_id'),
					'article_id' => $id,
					'chapter_id' => $chapter_id,
					'time' => date('Y-m-d H:i:s'),
				);
				$this->stats_model->add_chapter_watch($data);
			}

			// Loading Post_Model model
			$this->load->model('backend/post_model');
			$this->load->model('backend/settings_model');
			$this->load->model('backend/users_model');
			$this->load->model('frontend/comments_model');
			$article = $this->post_model->get_article_by_id($id);

			// Custom variables
			$options = array(
				'template_name' => $template,
				'body_class' => 'reader',
				'csrf_name' => $this->security->get_csrf_token_name(),
				'csrf_hash' => $this->security->get_csrf_hash(),
				'title' 	=> $article[0]->name,
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
			$data['article'] = $article;
			$data['csrf_name'] = $this->security->get_csrf_token_name();
			$data['csrf_hash'] = $this->security->get_csrf_hash();

			if(!empty($article)) {
				$captcha_values = array(
					'word'          => rand(1,999999),
					'img_path'      => './public/img/captcha_images/',
					'img_url'       => base_url('public').'/img/captcha_images/',
					'font_path'     => base_url('public').'/font/Vazir-Bold-FD.ttf',
					'img_width'     => '100',
					'img_height'    => 46,
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
				$data['chapters'] = $this->post_model->get_chapters_by_id($id);
				$data['episodes'] = $this->post_model->get_latest_episodes($id, $chapter_id);
				$data['captcha'] = $captcha['image'];
				$data['comments'] = $this->comments_model->get_comments_by_post($id);
				// next & prev links
				$id_array = array();
				$current_chapter = $this->post_model->get_chapter_by_id($chapter_id);
				if(!empty($current_chapter)) {
					foreach($data['chapters'] as $chapter) {
						$id_array[] = $chapter->id;
					}
					$current_index = array_search($current_chapter[0]->id, $id_array);
					$next = $current_index + 1;
					if(!empty($data['chapters'][$next])) {
						$next_chapter = (array)$data['chapters'][$next];
					} else {
						$next_chapter = NULL;
					}
					if($current_index == 0) {
						$prev = 0;
						$prev_chapter = NULL;
					} else {
						$prev = $current_index - 1;
						$prev_chapter = (array)$data['chapters'][$prev];
					}
					$data['next_chapter'] = $next_chapter;
					$data['prev_chapter'] = $prev_chapter;
					
				}
				$this->parser->parse("frontend/$template/reader", $data);
			} else {
				$this->parser->parse("frontend/$template/404", $data);
			}
		} else {
			$this->parser->parse("frontend/$template/404", $data);
		}
	}



	// Comments form Ajax controller
	public function create_comment() {
		// Loading form validation library
        $this->load->library('form_validation');
        // Loading Comments Model
        $this->load->model('frontend/comments_model');
        $this->load->model('frontend/captcha_model');
        // Empty data array
        // Validation Config
        $validation_config = array(
            'required' => 'فیلد %s الزامی می باشد',
        );
        // Text validation
        $this->form_validation->set_rules('comment_text', 'متن نظر', 
            array(
                'required',
            ),$validation_config); 
        // Checking validations
        if($this->form_validation->run()) {
        	$data = array(
        		'comment_text' => $this->input->post('comment_text'),
        		'comment_date' => date('Y-m-d H:i:s'),
        		'comment_user_id' => $this->session->userdata('user_id'),
        		'comment_post_id' => $this->input->post('comment_post_id'),
        		'comment_reply_id' => $this->input->post('comment_reply_id'),
        		'comment_ip' => $this->input->ip_address(),
        	);
        	// Captcha binds
        	$captcha = $this->session->flashdata('captcha');
        	// Validating captcha
        	$captcha_validation = $this->captcha_model->captcha_exists($captcha);
        	if($captcha_validation) {
	        	// Saving comment
	        	$comment_id = $this->comments_model->save_comment($data);
	        	if($comment_id > 0) {
	        		// Ok
		        	// echo($this->mangareader->create_response('1', '', 'ok'));
	        		redirect($_SERVER['HTTP_REFERER']);
	        	} else {
	        		// Not saved
	        		redirect($_SERVER['HTTP_REFERER']);
	        	}
	        } else {
        		redirect($_SERVER['HTTP_REFERER']);
	        }
        } else {
        	// Responding validtion errors instead
        	echo($this->mangareader->create_response('1', '', validation_errors()));
        }


	}



	public function add_to_likes() {
		$this->load->model('backend/post_model');
		if($this->session->has_userdata('logged_in')) {
			if($this->post_model->is_liked($this->input->post('article_id'), $this->session->userdata('user_id'))) {
	        	echo($this->mangareader->create_response('1', '', 'هم اکنون ذخیره شده است'));
			} else {
				$data = array(
					'article_id' => $this->input->post('article_id'),
					'user_id' => $this->session->userdata('user_id'),
				);
				$like_id = $this->post_model->add_to_likes($data);
				if($like_id > 0) {
		        	echo($this->mangareader->create_response('1', '', 'مطلب با موفقیت ذخیره شد'));
				} else {
		        	echo($this->mangareader->create_response('1', '', "ذخیره سازی مطلب نا موفق بود"));
				}
			}
		} else {
        	echo($this->mangareader->create_response('2', '', "ابتدا وارد حساب کاربری شوید"));
		}
	}





	public function download($id) {
		$this->load->model('backend/post_model');
		$article  = $this->post_model->get_article_by_id($id);
		if($article[0]->allow_download == 'yes') {
			$this->load->library('zip');
			$chapters = $this->post_model->get_chapters_by_id($id);
			$chapter_id = 0;
			$episode_id = 0;
			if(!empty($chapters)) {
				foreach($chapters as $chapter) {
					$episodes = $this->post_model->get_latest_episodes($id, $chapter->id);
					if(!empty($episodes)) {
						$chapter_id++;
						$episode_id = 0;
						foreach($episodes as $episode) {
							$this->zip->read_file('./public/img/episodes_images/'.$episode->image_name, $chapter_id.'-'.$episode_id.'-'.$article[0]->name.'-'.$episode->image_name);
							$episode_id++;
						}
					}
				}
			 	$filename = $article[0]->name.".zip";
		       	$this->zip->download($filename);
			} else {
	       		$this->session->set_flashdata('unsuccess', 'مطلب مورد نظر غیر قابل دانلود می باشد.');
	       		redirect(base_url($article[0]->url_slug.'/'.$article[0]->id));
			}
       	} else {
       		$this->session->set_flashdata('unsuccess', 'مطلب مورد نظر غیر قابل دانلود می باشد.');
       		redirect(base_url($article[0]->url_slug.'/'.$article[0]->id));
       	}
	}



	public function download_chapter($chapter_id) {
		$this->load->model('backend/post_model');
		$chapter = $this->post_model->get_chapter_by_id($chapter_id);
		$article  = $this->post_model->get_article_by_id($chapter[0]->article_id);
		if($article[0]->allow_download == 'yes') {
			$this->load->library('zip');

			$episodes = $this->post_model->get_latest_episodes($article[0]->id, $chapter[0]->id);
			// var_dump($episodes);
			$episode_id = 0;
			foreach($episodes as $episode) {
				$this->zip->read_file('./public/img/episodes_images/'.$episode->image_name, $episode_id.'-'.$article[0]->name.'-'.$episode->image_name);
				$episode_id++;
			}
		 	$filename = $article[0]->name.' ('.$chapter[0]->name.')'.".zip";
	       	$this->zip->download($filename);
       	} else {
       		$this->session->set_flashdata('unsuccess', 'مطلب مورد نظر غیر قابل دانلود می باشد.');
       		redirect(base_url($article[0]->url_slug.'/'.$article[0]->id));
       	}
	}




}