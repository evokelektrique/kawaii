<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Profile extends CI_Controller {
	protected $template;


	public function __construct() {
		parent::__construct();
        $this->load->helper('url');
        $this->load->library('parser');
        $this->load->library('session');
        $this->load->library('mangareader');
		$this->load->model('backend/post_model');
		$this->load->model('backend/stats_model');
		$this->load->model('backend/users_model');
        $this->load->model('frontend/comments_model');
        $this->load->model('backend/settings_model');
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

	public function index($username) {
		$template = $this->template;
		$this->load->model('backend/link_model');
		// Custom variables
		$options = array(
			'template_name' => $template,
			'body_class' => 'single',
			'csrf_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
			'title' 	=> "پروفایل  $username",
		);
		// Configuring view data
		// Loading views
		$header = $this->load->view("frontend/$template/layout/header.php", $options, true);
		$footer = $this->load->view("frontend/$template/layout/footer.php", null, true);

		$data = array();
		// Defining variables to views
		$data['header'] = $header;
		$data['footer'] = $footer;
		$data['links'] = $this->link_model->get_latest_links();
		if(!empty($username)) {
		$data['settings'] = $this->settings_model->settings(); 
		$data['csrf_name'] = $this->security->get_csrf_token_name();
		$data['csrf_hash'] = $this->security->get_csrf_hash();
			if($this->session->has_userdata('logged_in')) {
				// Loading users_model model
				$user = $this->users_model->get_user_by_username($username);
				if($this->session->userdata('user_id') == $user[0]->id) {
					if(!empty($user)) {
						$data['user'] = $user;
						$data['comments'] = array();
						if($this->session->has_userdata('logged_in')) {
							$data['user'] = $this->users_model->get_users_by_id($this->session->userdata('user_id'));
						}
						$comments = $this->comments_model->get_comments_by_user_id($user[0]->id);
						foreach ($comments as $comment) {
							$article = $this->post_model->get_article_by_id($comment->comment_post_id);
							array_push($data["comments"], array(
										'name' => $user[0]->username,
										'text' => $comment->comment_text,
										'time' => $comment->comment_date,
										'url_slug' => $article[0]->url_slug,
										'article_id' => $article[0]->id,
										)
									);
						}
						$watched_chapters = $this->stats_model->watched_chapters($user[0]->id);
						$data['watched_chapters'] = array();
						foreach ($watched_chapters as $wc) {
							$chapter = $this->post_model->get_chapter_by_id($wc->chapter_id);
							$article = $this->post_model->get_article_by_id($chapter[0]->article_id);
							// var_dump($article);
							array_push($data["watched_chapters"], array(
										'chapter_id' => $wc->chapter_id,
										'name' => $chapter[0]->name,
										'url_slug' => $article[0]->url_slug,
										'article_name' => $article[0]->name,
										'article_id' => $article[0]->id,
										'time' => $wc->time,
										)
									);
						}

						$data['watched_chapters'] = $this->mangareader->unique_array($data['watched_chapters'], 'chapter_id');


						$data['likes'] = array();
						$likes = $this->post_model->get_likes_by_user_id($user[0]->id);
						foreach($likes as $like) {
							$article = $this->post_model->get_article_by_id($like->article_id);
							if(!empty($article[0])) {
								array_push($data['likes'], array(
									'name' => $article[0]->name,
									'article_id' => $article[0]->id,
									'url_slug' => $article[0]->url_slug
									)
								);
							}
						}




						// Parsing view
						$this->parser->parse("frontend/$template/profile", $data);
					} else {
						$this->parser->parse("frontend/$template/404", $data);
					}
				}
			} else {
				redirect(base_url('auth'));
			}
		} else {
			$this->parser->parse("frontend/$template/404", $data);
		}
	}


	public function user($username) {
		$template = $this->template;
		// Custom variables
		$options = array(
			'template_name' => $template,
			'body_class' => 'single',
			'csrf_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
			'title' 	=> "پروفایل  $username",
		);
		// Loading link_model model
		$this->load->model('backend/link_model');
		// Configuring view data
		// Loading views
		$header = $this->load->view("frontend/$template/layout/header.php", $options, true);
		$footer = $this->load->view("frontend/$template/layout/footer.php", null, true);
		// Defining variables to views
		$data = array();
		$data['header'] = $header;
		$data['footer'] = $footer;
		$data['links'] = $this->link_model->get_latest_links();
		$data['settings'] = $this->settings_model->settings(); 
		$data['csrf_name'] = $this->security->get_csrf_token_name();
		$data['csrf_hash'] = $this->security->get_csrf_hash();
		
		if(!empty($username)) {
			$user = $this->users_model->get_user_by_username($username);
			if(!empty($user)) {
				if($this->session->has_userdata('logged_in')) {
					$data['logged_in_user'] = $this->users_model->get_users_by_id($this->session->userdata('user_id'));
				}
				$data['user'] = $user;
				if($this->session->has_userdata('logged_in')) {
					$data['origin_user'] = $this->users_model->get_users_by_id($this->session->userdata('user_id'));
				} else {
					$data['origin_user'] = array();
				}
				$data['comments'] = array();
				$comments = $this->comments_model->get_comments_by_user_id($user[0]->id);
				foreach ($comments as $comment) {
					$article = $this->post_model->get_article_by_id($comment->comment_post_id);
					array_push($data["comments"], array(
								'name' => $user[0]->username,
								'text' => $comment->comment_text,
								'time' => $comment->comment_date,
								'url_slug' => $article[0]->url_slug,
								'article_id' => $article[0]->id,
								)
							);
				}
				$watched_chapters = $this->stats_model->watched_chapters($user[0]->id);
				$data['watched_chapters'] = array();
				foreach ($watched_chapters as $wc) {
					$chapter = $this->post_model->get_chapter_by_id($wc->chapter_id);
					$article = $this->post_model->get_article_by_id($chapter[0]->article_id);
					// var_dump($article);
					array_push($data["watched_chapters"], array(
								'chapter_id' => $wc->chapter_id,
								'name' => $chapter[0]->name,
								'url_slug' => $article[0]->url_slug,
								'article_name' => $article[0]->name,
								'article_id' => $article[0]->id,
								'time' => $wc->time,
								)
							);
				}

				$data['watched_chapters'] = $this->mangareader->unique_array($data['watched_chapters'], 'chapter_id');
				// Parsing view
				$this->parser->parse("frontend/$template/user", $data);
			} else {
				$this->parser->parse("frontend/$template/404", $data);
			}
		} else {
			$this->parser->parse("frontend/$template/404", $data);
		}
	}




	public function upload_profile_cover() {
        $upload_config['upload_path']= 'public/img/profile_covers/';
        $upload_config['allowed_types']='jpg|png';
        $upload_config['encrypt_name'] = TRUE;
        $this->load->library('upload',$upload_config);
        if($this->session->has_userdata('logged_in')) {
	        // Initializing "data" array
	        $data = array();
	        if (!$this->upload->do_upload('profile_cover')) {
				echo $this->mangareader->create_response(1, base_url(), $this->upload->display_errors());
	        } else {
	            $image_data = array('upload_data' => $this->upload->data());
	            $image = $image_data['upload_data']['file_name'];
	            $data['profile_cover_url'] = $image;
	        }

	        $user_id = $this->session->userdata('user_id');
	        $row_id = $this->users_model->update_profile_cover_url($data, $user_id);
	        if($row_id > 0) {
				echo $this->mangareader->create_response(1, '', 'Ok');
	        } else {
				echo $this->mangareader->create_response(1, '', 'Bad');
	        }
	    } else {
			echo $this->mangareader->create_response(1, base_url(), 'Not Logged in');
	    }
	}

	public function upload_profile_picture() {
        $upload_config['upload_path']= 'public/img/profile_images/';
        $upload_config['allowed_types']='jpg|png';
        $upload_config['encrypt_name'] = TRUE;
        $this->load->library('upload',$upload_config);
        if($this->session->has_userdata('logged_in')) {
	        // Initializing "data" array
	        $data = array();
	        if (!$this->upload->do_upload('profile_picture')) {
				echo $this->mangareader->create_response(1, base_url(), $this->upload->display_errors());
	        } else {
	            $image_data = array('upload_data' => $this->upload->data());
	            $image = $image_data['upload_data']['file_name'];
		        // var_dump($image);
	            $data['profile_picture_url'] = $image;
	        }

	        $user_id = $this->session->userdata('user_id');
	        $row_id = $this->users_model->update_profile_picture_url($data, $user_id);
	        if($row_id > 0) {
				echo $this->mangareader->create_response(1, '', 'Ok');
	        } else {
				echo $this->mangareader->create_response(1, '', 'Bad');
	        }
	    } else {
			echo $this->mangareader->create_response(1, base_url(), 'Not Logged in');
	    }
	}


}