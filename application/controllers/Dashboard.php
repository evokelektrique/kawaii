<?php
defined('BASEPATH') or exit('no direct access allowed');

//////////////////////////////////////////////
// Kawaii Manga Reader dashboard controller //
//////////////////////////////////////////////

class Dashboard extends CI_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Tehran');
        $this->load->helper('url');
        $this->load->library('parser');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('backend/post_model');
        $this->load->library('mangareader');
        // $this->output->set_header('Content-Type: text/html; charset=utf-8');
        // Processing Permissions To log in to dashboard
        if($this->session->has_userdata('logged_in')) {
            if(!$this->session->userdata('admin')) {
                redirect(base_url().'auth');
            }
        } else {
            redirect(base_url().'auth');            
        }
    }

    // Dashboard index
    public function index() {
        $this->load->model('frontend/comments_model');
        $this->load->model('backend/alerts_model');
        $this->load->model('backend/users_model');
        $this->load->model('backend/stats_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'urls' => $breadcrumb,
            'sidebar' => $sidebar,
            'entries' => $this->post_model->get_latest_posts(4),
            'users' => $this->users_model->get_all_users(10),
            'chardata' => $this->stats_model->get_chartdata(),
        );
        $alerts = $this->alerts_model->get_all_alerts(10);
        $data['alerts'] = array();
        $id = 0;
        foreach($alerts as $alert) {
            $id++;
            if($alert->type == "article") { // Article
                $article = $this->post_model->get_article_by_id($alert->type_id);
                if(!empty($article)) {
                    array_push($data['alerts'], array(
                        'id' => $id,
                        'text' => substr($alert->text, 0,30).'...',
                        'type_id' => $alert->type_id,
                        'type' => 'پست',
                        'link' => base_url($article[0]->url_slug.'/'.$article[0]->id),
                        'edit_link' => base_url('dashboard/alert/'.$alert->id),
                        'created_at' => $alert->created_at,
                    ));
                }
            } else { // Comment
                $comment = $this->comments_model->get_comment_by_id($alert->type_id);
                $article = $this->post_model->get_article_by_id($comment[0]->comment_post_id);
                if(!empty($comment)) {
                    array_push($data['alerts'], array(
                        'id' => $id,
                        'text' => substr($alert->text, 0,30).'...',
                        'type_id' => $alert->type_id,
                        'type' => 'نظر',
                        'link' => base_url($article[0]->url_slug.'/'.$article[0]->id),
                        'edit_link' => base_url('dashboard/alert/'.$alert->id),
                        'created_at' => $alert->created_at,
                    ));
                }
            }
        }
        $data['comments'] = $this->comments_model->get_latest_comments(10);
        $this->parser->parse('admin/dashboard_main', $data);
    }

    // Posting article
    public function upload() {
        $this->load->model('backend/category_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'entries' => $this->post_model->get_latest_posts(),
            'categories' => $this->category_model->categories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );

        $this->parser->parse('admin/dashboard_upload', $data);
    }

    // Saving article
    public function do_upload() {
        $upload_config['upload_path']= 'public/img/post_images/';
        $upload_config['allowed_types']='jpg|png';
        $upload_config['encrypt_name'] = TRUE;
        $this->load->library('upload',$upload_config);


        // Validation Config
        $validation_config = array(
            'required' => 'فیلد %s الزامی می باشد'
        );

        // Name validation
        $this->form_validation->set_rules('name', 'نام', 
            array(
                'required'
            ),$validation_config); 

        // Author validation
        $this->form_validation->set_rules('author', 'نویسنده', 
            array(
                'required'
            ),$validation_config);

        // Tags validation
        $this->form_validation->set_rules('tags', 'دسته', 
            array(
                'required'
            ),$validation_config);

        // Date validation
        $this->form_validation->set_rules('release_date', 'تاریخ انتشار', 
            array(
                'required'
            ),$validation_config);

        // Description validation
        $this->form_validation->set_rules('description', 'توضیحات', 
            array(
                'required'
            ),$validation_config);

        if($this->form_validation->run()) {
            $name = strip_tags($this->input->post('name'));
            $url_slug = mb_strtolower(url_title($name), 'UTF-8');
            $data = array(
                'name' => $this->input->post('name'),
                'author' => $this->input->post('author'),
                'tags' => $this->input->post('tags'),
                'release_date' => $this->input->post('release_date'),
                'description' => $this->input->post('description'),
                'url_slug' => $url_slug,
                'created_at' => date('Y-m-d H:i:s'),
            );

            if($this->input->post('comments_status') != "") {$data['comments_status'] = 'on';}
            if($this->input->post('comments_status') == null) {$data['comments_status'] = "off";}

            if (!$this->upload->do_upload('post_image')) {
                echo $this->upload->display_errors();
            } else {
                $image_data = array('upload_data' => $this->upload->data());
                $image = $image_data['upload_data']['file_name'];
                // echo $image;
                $data['post_image'] = $image;
            }

            $upload_config['upload_path']= 'public/img/post_covers/';
            $this->upload->initialize($upload_config);
            if (!$this->upload->do_upload('post_cover')) {
                echo $this->upload->display_errors();
            } else {
                $image_data = array('upload_data' => $this->upload->data());
                $image = $image_data['upload_data']['file_name'];
                // echo $image;/
                $data['post_cover'] = $image;
            }

            $post_id = $this->post_model->savedata($data);
            // $post_id = true;
            if($post_id) {
                echo $this->mangareader->create_response(1, '', 'مطلب با موفقیت ذخیره شد');
            } else {
                echo $this->mangareader->create_response(3,'','مشکلی در ذخیره سازی مطلب بوجود آمد');
            }
        } else {
            echo $this->mangareader->create_response(2, base_url('dashboard').'/upload', validation_errors());
        }
    }

    // Article archive
    public function archive() {
        $this->load->library('pagination');
        $this->load->model('backend/stats_model');
        $this->load->model('backend/alerts_model');
        $config = array();
        $config['base_url'] = base_url('dashboard') . '/archive/';
        $config['total_rows'] = $this->post_model->count_posts();
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination center col m12 s12">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active blue">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="waves-effect">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="waves-effect">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<i class="material-icons">chevron_left</i>';
        $config['last_link'] = '';
        $config['prev_link'] = '<i class="material-icons">chevron_right</i>';
        $config['first_link'] = '';
        $config['first_url'] = base_url('dashboard') . '/archive/'.'0';
        $config['cur_tag_open'] = '<li class="active blue"><a href="#!">';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'entries' => array(),
            'links' =>$this->pagination->create_links(),
        );
        $entries = $this->post_model->get_latest_posts($config['per_page'], $page);
        foreach($entries as $entry) {
            array_push($data['entries'], array(
                    'id'            => $entry->id,
                    'user_id'       => $entry->user_id,
                    'post_image'    => $entry->post_image,
                    'name'          => $entry->name,
                    'author'        => $entry->author,
                    'read_count'    => count($this->stats_model->get_total_watched_by_article_id($entry->id)),
                    'alert_count'   => count($this->alerts_model->get_all_alerts_by_article_id($entry->id)),
                    'view_count'    => $entry->view_count,
                    'url_slug'      => $entry->url_slug,
                    'created_at'    => $entry->created_at,
                    'modified_at'   => $entry->modified_at,
                )
            );
        }
        $this->parser->parse('admin/dashboard_archive', $data);
    }

    public function edit_article($id,$slug=null) {
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'article' => $this->post_model->get_article_by_id($id),
            'chapters' => $this->post_model->get_chapters_by_id($id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        if(!empty($data['article'])) {
            $this->parser->parse('admin/dashboard_edit_article', $data);
        } else {
            $this->parser->parse('admin/404', $data);
        }
    }

    public function do_edit_article($id, $slug=null) {
        $upload_config['upload_path']= 'public/img/post_images/';
        $upload_config['allowed_types']='jpg|png';
        $upload_config['encrypt_name'] = TRUE;
        $config['overwrite'] = TRUE;
        $this->load->library('upload',$upload_config);

        // Validation Config
        $validation_config = array(
            'required' => 'فیلد %s الزامی می باشد',
        );

        // Name validation
        $this->form_validation->set_rules('name', 'نام', 
            array(
                'required'
            ),$validation_config); 

        // // Author validation
        // $this->form_validation->set_rules('author', 'نویسنده', 
        //     array(
        //         'required'
        //     ),$validation_config);

        // // Tags validation
        // $this->form_validation->set_rules('tags', 'دسته', 
        //     array(
        //         'required'
        //     ),$validation_config);

        // // Date validation
        // $this->form_validation->set_rules('release_date', 'تاریخ انتشار', 
        //     array(
        //         'required'
        //     ),$validation_config);

        if($this->form_validation->run()) {
            $name = strip_tags($this->input->post('name'));
            $url_slug = mb_strtolower(url_title($name), 'UTF-8');
            $data = array();
            if($this->input->post('name') != "") {$data['name'] = $this->input->post('name');}            
            if($this->input->post('author') != "") {$data['author'] = $this->input->post('author');}            
            if($this->input->post('tags') != "") {$data['tags'] = $this->input->post('tags');}            
            if($this->input->post('release_date') != "") {$data['release_date'] = $this->input->post('release_date');}            
            if($this->input->post('description') != "") {$data['description'] = $this->input->post('description');}            
            if($this->input->post('status') != "") {$data['status'] = $this->input->post('status');}            
            if($this->input->post('comments_status') != "") {$data['comments_status'] = 'on';}
            if($this->input->post('comments_status') == null) {$data['comments_status'] = "off";}
            $data['url_slug'] = $url_slug;
            $data['modified_at'] = date('Y-m-d H:i:s');            
            // $data = array(
            //     'name' => $this->input->post('name'),
            //     'author' => $this->input->post('author'),
            //     'tags' => $this->input->post('tags'),
            //     'release_date' => $this->input->post('release_date'),
            //     'url_slug' => $url_slug,
            //     'modified_at' => date('Y-m-d H:i:s'),
            // );
            $current_post = $this->post_model->get_article_by_id($id);
            $post_image_name = $current_post[0]->post_image;
            $post_cover_name = $current_post[0]->post_cover;
            if($post_image_name != $this->input->post('post_image_file_path')) {
                if (!$this->upload->do_upload('post_image')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data = array('upload_data' => $this->upload->data());
                    $image = $image_data['upload_data']['file_name'];
                    // echo $image;
                    $data['post_image'] = $image;
                }
            } elseif($post_cover_name != $this->input->post('post_cover_file_path')) {
                $upload_config['upload_path']= 'public/img/post_covers/';
                $this->upload->initialize($upload_config);
                if (!$this->upload->do_upload('post_cover')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data = array('upload_data' => $this->upload->data());
                    $image = $image_data['upload_data']['file_name'];
                    // echo $image;/
                    $data['post_cover'] = $image;
                }
            } else {
            }

            $update_id = $this->post_model->update_article_by_id($id,$data);
            if($update_id > 0) {
                echo $this->mangareader->create_response(1, '', 'مطلب با موفقیت ذخیره شد');
            } else {
                echo $this->mangareader->create_response(3,'','مشکلی در ذخیره سازی مطلب بوجود آمد');
            }
        } else {
            echo $this->mangareader->create_response(2, base_url('dashboard').'/upload', validation_errors());
        }
    }

    // Chapters
    public function chapters($article_id) {
        $this->load->library('pagination');
        $config['base_url'] = base_url('dashboard') . '/chapters/'.$article_id.'/';
        $config['total_rows'] = $this->post_model->count_chapters();
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination center col m12 s12">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active blue">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="waves-effect">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="waves-effect">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<i class="material-icons">chevron_left</i>';
        $config['last_link'] = '';
        $config['prev_link'] = '<i class="material-icons">chevron_right</i>';
        $config['first_link'] = '';
        $config['first_url'] = base_url('dashboard') . '/chapters/'.$article_id.'/0';
        $config['cur_tag_open'] = '<li class="active blue"><a href="#!">';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 0;
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'article' => $this->post_model->get_article_by_id($article_id),
            'chapters' => $this->post_model->get_chapters_by_id($article_id, $config['per_page'], 10),
            'links' =>$this->pagination->create_links()
        );
        $this->parser->parse('admin/dashboard_archive_chapters', $data);
    }

    // New chapter view
    public function add_chapter($id) {
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'article' => $this->post_model->get_article_by_id($id),
            'chapters' => $this->post_model->get_chapters_by_id($id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        $this->parser->parse('admin/dashboard_add_chapter', $data);
    }


    // New chapter process
    public function create_chapter($id) {
        // Validation Config
        $validation_config = array(
            'required' => 'فیلد %s الزامی می باشد',
        );

        // Name validation
        $this->form_validation->set_rules('name', 'نام فصل', 
            array(
                'required'
            ),$validation_config);         
        // Status validation
        $this->form_validation->set_rules('status', 'وضعیت فصل', 
            array(
                'required'
            ),$validation_config); 

        if($this->form_validation->run()) {
            $data = array(
                'article_id' => $id,
                'name' => $this->input->post('name'),
                'status' => $this->input->post('status'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $new_chapter = $this->post_model->add_chapter($data);
            if($new_chapter > 0) {
                echo $this->mangareader->create_response(1, '', 'فصل با موفقیت ذخیره شد');
            } else {
                echo $this->mangareader->create_response(3, '', 'ذخیره سازی فصل نا موفق بود');
            }

        } else {
            echo $this->mangareader->create_response(2, '', validation_errors());
        }

    }

    // Edit chapter view
    public function edit_chapter($article_id, $chapter_id) {
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'article' => $this->post_model->get_article_by_id($article_id),
            'chapter' => $this->post_model->get_chapter_by_id($chapter_id),
            'chapters' => $this->post_model->get_chapters_by_id($article_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        if(!empty($data['chapter'])) {
            $this->parser->parse('admin/dashboard_edit_chapter', $data);
        } else {
            $this->parser->parse('admin/404', $data);
        }
    }

    // Edit chapter proccess
    public function do_edit_chapter($id) {
        // Validation Config
        $validation_config = array(
            'required' => 'فیلد %s الزامی می باشد',
        );

        // Name validation
        $this->form_validation->set_rules('name', 'نام فصل', 
            array(
                'required'
            ),$validation_config);         
        // Status validation
        $this->form_validation->set_rules('status', 'وضعیت فصل', 
            array(
                'required'
            ),$validation_config); 

        if($this->form_validation->run()) {
            $data = array();
            if($this->input->post('name') != "") {$data['name'] = $this->input->post('name');}
            if($this->input->post('status') != "") {$data['status'] = $this->input->post('status');}
            $data['modified_at'] = date('Y-m-d H:i:s');
            $updated_chapter = $this->post_model->update_chapter($id, $data);
            if($updated_chapter > 0) {
                echo $this->mangareader->create_response(1, '', 'ویرایش با موفقیت انجام شد');
            } else {
                echo $this->mangareader->create_response(3, '', 'به مشکل بر خوردیم');
            }

        } else {
            echo $this->mangareader->create_response(2, '', validation_errors());
        }
    }

    // Chapters
    public function episodes($article_id) {
        $this->load->library('pagination');
        $episodes_count = count($this->post_model->get_episodes_by_id($article_id));
        $config['base_url'] = base_url('dashboard') . '/episodes/'.$article_id.'/';
        $config['total_rows'] = $episodes_count;
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination center col m12 s12">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active blue">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="waves-effect">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="waves-effect">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<i class="material-icons">chevron_left</i>';
        $config['last_link'] = '';
        $config['prev_link'] = '<i class="material-icons">chevron_right</i>';
        $config['first_link'] = '';
        $config['first_url'] = base_url('dashboard') . '/episodes/'.$article_id.'/0';
        $config['cur_tag_open'] = '<li class="active blue"><a href="#!">';
        $config['cur_tag_close'] = '</a></li>';


        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 0;
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'article' => $this->post_model->get_article_by_id($article_id),
            'episodes' => $this->post_model->get_episodes_by_id($article_id, $config['per_page'], 10),
            'links' =>$this->pagination->create_links()
        );
        $this->parser->parse('admin/dashboard_archive_episodes', $data);
    }

    public function ajax_latest_articles() {
        $data = array(
            'entries' => $this->post_model->get_latest_posts(6,0)
        );
        $this->parser->parse('admin/ajax_latest_articles', $data);
    }

    public function ajax_latest_chapters($article_id) {
        $data = array(
            'article' => $this->post_model->get_article_by_id($article_id),
            'chapters' => $this->post_model->get_chapters_by_id($article_id),
        );
        $this->parser->parse('admin/ajax_latest_chapters', $data);
    }

    public function ajax_latest_episodes($article_id, $chapter_id) {
        $data = array(
            'article' => $this->post_model->get_article_by_id($article_id),
            'episodes' => $this->post_model->get_latest_episodes($article_id, $chapter_id, 6),
        );
        $this->parser->parse('admin/ajax_latest_episodes', $data);
    }

    public function add_episode($article_id, $chapter_id) {
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'article' => $this->post_model->get_article_by_id($article_id),
            'chapter' => $this->post_model->get_chapter_by_id($chapter_id),
            'episodes' => $this->post_model->get_latest_episodes($article_id, $chapter_id, 6),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        $this->parser->parse('admin/dashboard_add_episode', $data);
    }

    public function create_episode($article_id, $chapter_id) {
        $upload_config['upload_path']= 'public/img/episodes_images/';
        $upload_config['allowed_types']='jpg|png|gif';
        $upload_config['encrypt_name'] = TRUE;
        $config['overwrite'] = TRUE;
        $this->load->library('upload',$upload_config);

        // $validation_config = array(
        //     'required' => 'فیلد %s الزامی می باشد',
        // );
        // // Image validation
        // $this->form_validation->set_rules('image_name', 'عکس', 
        //     array(
        //         'required'
        //     ),$validation_config);          
        // if($this->form_validation->run()) {         
        $data = array();
        if($_FILES['image_name']['size'] !== 0) {

            if (!$this->upload->do_upload('image_name')) {
                 echo $this->mangareader->create_response(2, '', $this->upload->display_errors());
            } else {
                $image_data = array('upload_data' => $this->upload->data());
                $image = $image_data['upload_data']['file_name'];
                $data['image_name'] = $image;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['article_id'] = $article_id;
                $data['chapter_id'] = $chapter_id;
                $new_episode = $this->post_model->add_episode($article_id, $chapter_id, $data);
                if($new_episode > 0) {
                    echo $this->mangareader->create_response(1, '', 'آپلود با موفقیت انجام شد');
                } else {
                    echo $this->mangareader->create_response(3, '', 'به مشکل بر خوردیم');
                }
            }

        } else {
            echo $this->mangareader->create_response(2, '', 'فیلد عکس خالی می باشد');
        }
    }

    public function comments($id=null) {
        $this->load->model('frontend/comments_model');
        $this->load->model('backend/users_model');
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] = base_url('dashboard') . '/comments/';
        $config['total_rows'] = $this->comments_model->count_comments();
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination center col m12 s12">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active blue">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="waves-effect">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="waves-effect">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<i class="material-icons">chevron_left</i>';
        $config['last_link'] = '';
        $config['prev_link'] = '<i class="material-icons">chevron_right</i>';
        $config['first_link'] = '';
        $config['first_url'] = base_url('dashboard') . '/comments/'.'0';
        $config['cur_tag_open'] = '<li class="active blue"><a href="#!">';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'links' =>$this->pagination->create_links(),
        );
        $data['comments'] = $this->comments_model->get_latest_comments($config['per_page'], $page);
        $this->parser->parse('admin/dashboard_archive_comments', $data);
    }

    public function edit_comment($id=null) {
        $this->load->model('frontend/comments_model');
        $this->load->model('backend/users_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        if($id !== null) {
            $data['comments'] = array();
            $comments = $this->comments_model->get_latest_comments(6);
            foreach ($comments as $comment) {
                $user = $this->users_model->get_users_by_id($comment->comment_user_id);
                array_push($data['comments'], array(
                    'id'        => $comment->id,
                    'text'      => substr($comment->comment_text, 0, 25).'...',
                    'username'  => $user[0]->username,
                    'user_id'   => $user[0]->id,
                    'approved'  => $comment->comment_approved,
                    'show'      => $comment->comment_show,
                    'ip'        => $comment->comment_ip,
                    'date'      => $comment->comment_date,
                ));   
            }
            $data['comment'] = $this->comments_model->get_comment_by_id($id);
            if(!empty($data['comment'])) {
                $this->parser->parse('admin/dashboard_edit_comment', $data);
            } else {
                $this->parser->parse('admin/404', $data);
            }
        } else {
            $this->parser->parse('admin/404', $data);
        }
    }

    public function ajax_latest_comments() {

        $this->load->model('frontend/comments_model');
        $this->load->model('backend/users_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        $data['comments'] = array();
        $comments = $this->comments_model->get_latest_comments(6);
        foreach ($comments as $comment) {
            $user = $this->users_model->get_users_by_id($comment->comment_user_id);
            array_push($data['comments'], array(
                'id'        => $comment->id,
                'text'      => substr($comment->comment_text, 0, 25).'...',
                'username'  => $user[0]->username,
                'user_id'   => $user[0]->id,
                'approved'  => $comment->comment_approved,
                'show'      => $comment->comment_show,
                'ip'        => $comment->comment_ip,
                'date'      => $comment->comment_date,
            ));   
        }
        $this->parser->parse('admin/ajax_latest_comments', $data);
    }

    public function update_comment($id) {
        $this->load->model('frontend/comments_model');
        // Validation Config
        $validation_config = array(
            'required' => 'فیلد %s الزامی می باشد',
        );

        // Name validation
        $this->form_validation->set_rules('comment_text', 'متن نظر', 
            array(
                'required'
            ),$validation_config);         

        if($this->form_validation->run()) {
            $data = array(
                'comment_text' => $this->input->post('comment_text'),
            );
            $comment_id = $this->comments_model->update_comment($data, $id);
            if($comment_id > 0 ) {
                echo $this->mangareader->create_response(1, '', 'نظر با موفقیت بروزرسانی شد');

            } else {
                echo $this->mangareader->create_response(3, '', 'بروزرسانی نظر نا موفق بود');
            }
        } else {
            echo $this->mangareader->create_response(2, '', validation_errors());
        }

    }

    public function alerts() {
        $this->load->model('backend/alerts_model');
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] = base_url('dashboard') . '/alerts/';
        $config['total_rows'] = $this->alerts_model->count_alerts();
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination center col m12 s12">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active blue">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="waves-effect">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="waves-effect">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<i class="material-icons">chevron_left</i>';
        $config['last_link'] = '';
        $config['prev_link'] = '<i class="material-icons">chevron_right</i>';
        $config['first_link'] = '';
        $config['first_url'] = base_url('dashboard') . '/alerts/'.'0';
        $config['cur_tag_open'] = '<li class="active blue"><a href="#!">';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'links' =>$this->pagination->create_links(),
            'alerts' => $this->alerts_model->get_all_alerts($config['per_page'], $page),
        );
        $this->parser->parse('admin/dashboard_archive_alerts', $data);
    }

    // Contact us archive
    public function contacts() {
        $this->load->model('backend/contacts_model');
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] = base_url('dashboard') . '/contacts/';
        $config['total_rows'] = $this->contacts_model->count_contacts();
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination center col m12 s12">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active blue">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="waves-effect">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="waves-effect">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<i class="material-icons">chevron_left</i>';
        $config['last_link'] = '';
        $config['prev_link'] = '<i class="material-icons">chevron_right</i>';
        $config['first_link'] = '';
        $config['first_url'] = base_url('dashboard') . '/contacts/'.'0';
        $config['cur_tag_open'] = '<li class="active blue"><a href="#!">';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'links' =>$this->pagination->create_links(),
            'contacts' => $this->contacts_model->get_all_contacts($config['per_page'], $page),
        );
        $this->parser->parse('admin/dashboard_archive_contacts', $data);
    }

    public function alert($id) {
        $this->load->model('frontend/comments_model');
        $this->load->model('backend/alerts_model');
        $this->load->model('backend/users_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'alerts' => array(),
            'alert' => $this->alerts_model->get_alert_by_id($id),
        );
        $id = 0;
        $alerts = $this->alerts_model->get_all_alerts(6);
        if(is_array($alerts)) {
            foreach($alerts as $alert) {
                $id++;
                if($alert->type == "article") { // Article
                    $article = $this->post_model->get_article_by_id($alert->type_id);
                    if(!empty($article)) {
                        array_push($data['alerts'], array(
                            'id'            => $id,
                            'text'          => substr($alert->text, 0,30).'...',
                            'type_id'       => $alert->type_id,
                            'type'          => 'پست',
                            'link'          => base_url($article[0]->url_slug.'/'.$article[0]->id),
                            'edit_link'     => base_url('dashboard/alert/'.$alert->id),
                            'created_at'    => $alert->created_at,
                        ));
                    }
                } else { // Comment
                    $comment = $this->comments_model->get_comment_by_id($alert->type_id);
                    $article = $this->post_model->get_article_by_id($comment[0]->comment_post_id);
                    if(!empty($comment)) {
                        array_push($data['alerts'], array(
                            'id'            => $id,
                            'text'          => substr($alert->text, 0,30).'...',
                            'type_id'       => $alert->type_id,
                            'type'          => 'نظر',
                            'link'          => base_url($article[0]->url_slug.'/'.$article[0]->id),
                            'edit_link'     => base_url('dashboard/alert/'.$alert->id),
                            'created_at'    => $alert->created_at,
                        ));
                    }
                }
            }
        }
        if(!empty($data['alert'])) {
            $this->alerts_model->seen($id);
            $this->parser->parse('admin/dashboard_edit_alert', $data);
        } else {
            $this->parser->parse('admin/404', $data);       
        }
    }

    public function close_content($id, $type) {
        $this->load->model('backend/alerts_model');
        if($type == 'article') {
            $closed_content_id = $this->alerts_model->close_content($id, 'article', 'posts');
        } else {
            $closed_content_id = $this->alerts_model->close_content($id, 'comment', 'comments');
        }
        if($closed_content_id > 0) {
            echo $this->mangareader->create_response(1, '', 'مطلب با موفقیت بسته شد');
        } else {
            echo $this->mangareader->create_response(2, '', 'بسته شدن مطلب نا موفق بود');
        }
    }

    public function free_content($id, $type) {
        $this->load->model('backend/alerts_model');
        if($type == 'article') {
            $closed_content_id = $this->alerts_model->free_content($id, 'article', 'posts');
        } else { 
            $closed_content_id = $this->alerts_model->free_content($id, 'comment', 'comments');
        }
        if($closed_content_id > 0) {
            echo $this->mangareader->create_response(1, '', 'مطلب با موفقیت آزاد شد');
        } else {
            echo $this->mangareader->create_response(2, '', 'آزاد شدن مطلب نا موفق بود');
        }
    }

    public function ajax_latest_alerts() {
        $this->load->model('frontend/comments_model');
        $this->load->model('backend/alerts_model');
        $this->load->model('backend/users_model');
        $data = array(
            'alerts' => array(),
        );
        $id = 0;
        $alerts = $this->alerts_model->get_all_alerts(6);
        if(is_array($alerts)) {
            foreach($alerts as $alert) {
                $id++;
                if($alert->type == "article") { // Article
                    $article = $this->post_model->get_article_by_id($alert->type_id);
                    if(!empty($article)) {
                        array_push($data['alerts'], array(
                            'id' => $id,
                            'text' => substr($alert->text, 0,30).'...',
                            'type_id' => $alert->type_id,
                            'type' => 'پست',
                            'link' => base_url($article[0]->url_slug.'/'.$article[0]->id),
                            'edit_link' => base_url('dashboard/alert/'.$alert->id),
                            'created_at' => $alert->created_at,
                        ));
                    }
                } else { // Comment
                    $comment = $this->comments_model->get_comment_by_id($alert->type_id);
                    $article = $this->post_model->get_article_by_id($comment[0]->comment_post_id);
                    if(!empty($comment)) {
                        array_push($data['alerts'], array(
                            'id' => $id,
                            'text' => substr($alert->text, 0,30).'...',
                            'type_id' => $alert->type_id,
                            'type' => 'نظر',
                            'link' => base_url($article[0]->url_slug.'/'.$article[0]->id),
                            'edit_link' => base_url('dashboard/alert/'.$alert->id),
                            'created_at' => $alert->created_at,
                        ));
                    }
                }
            }
        }
        $this->parser->parse('admin/ajax_latest_alerts', $data);
    }

    public function settings() {
        $this->load->model('backend/settings_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'settings' => $this->settings_model->settings(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        $this->parser->parse('admin/dashboard_settings', $data);
    }

    public function do_settings() {
        $this->load->model('backend/settings_model');
        $data = array();
        if(!empty($this->input->post('site_name'))) { $data['site_name'] = $this->input->post('site_name'); }
        if(!empty($this->input->post('site_tags'))) { $data['site_tags'] = $this->input->post('site_tags'); }
        if(!empty($this->input->post('site_description'))) { $data['site_description'] = $this->input->post('site_description'); }
        if(!empty($this->input->post('google_analytics_api'))) { $data['google_analytics_api'] = $this->input->post('google_analytics_api'); }
        if(!empty($this->input->post('site_template'))) { $data['site_template'] = $this->input->post('site_template'); }
        if(!empty($this->input->post('custom_css'))) { $data['custom_css'] = $this->input->post('custom_css'); }
        if(!empty($this->input->post('logo_url'))) { $data['logo_url'] = $this->input->post('logo_url'); }
        if(!empty($this->input->post('custom_js'))) { $data['custom_js'] = $this->input->post('custom_js'); }
        if(!empty($this->input->post('about_us_text'))) { $data['about_us_text'] = $this->input->post('about_us_text'); }
        if(!empty($this->input->post('ads1'))) { $data['ads1'] = $this->input->post('ads1'); }
        if(!empty($this->input->post('ads2'))) { $data['ads2'] = $this->input->post('ads2'); }
        if(!empty($this->input->post('ads3'))) { $data['ads3'] = $this->input->post('ads3'); }
        if(!empty($this->input->post('ads4'))) { $data['ads4'] = $this->input->post('ads4'); }


        $updated_settings = $this->settings_model->update($data);
        if($updated_settings > 0) {
            echo $this->mangareader->create_response(1, '', 'تنظیمات با موفقیت ذخیره شد');
        } else {
            echo $this->mangareader->create_response(2, '', 'مشکل در ذخیره تنظیمات');
        }
    }

    public function categories() {
        $this->load->model('backend/category_model');
        $this->load->model('backend/users_model');
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] = base_url('dashboard') . '/categories/';
        $config['total_rows'] = $this->category_model->count_categories();
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination center col m12 s12">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active blue">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="waves-effect">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="waves-effect">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<i class="material-icons">chevron_left</i>';
        $config['last_link'] = '';
        $config['prev_link'] = '<i class="material-icons">chevron_right</i>';
        $config['first_link'] = '';
        $config['first_url'] = base_url('dashboard') . '/categories/'.'0';
        $config['cur_tag_open'] = '<li class="active blue"><a href="#!">';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'categories' => $this->category_model->categories($config['per_page'], $page),
            'links' =>$this->pagination->create_links(),
        );
        $this->parser->parse('admin/dashboard_archive_categories', $data);
    }

    public function add_category() {
        $this->load->model('backend/category_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'categories' => $this->category_model->categories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        $this->parser->parse('admin/dashboard_add_category', $data);
    }

    public function create_category() {
        $this->load->model('backend/category_model');
        $data = array();
        // Validation Config
        $validation_config = array(
            'required' => 'فیلد %s الزامی می باشد',
        );
        // Name validation
        $this->form_validation->set_rules('cat_name', 'دسته', 
            array(
                'required'
            ),$validation_config);      


        if($this->form_validation->run()) {
            $data['cat_name'] = $this->input->post('cat_name');
            $data['created_at'] = date('Y-m-d H:i:s');
            $created_cat = $this->category_model->create_category($data);
            if($created_cat > 0) {
                echo $this->mangareader->create_response(1, '', 'تغییرات با موفقیت انجام شد');
            } else {
                echo $this->mangareader->create_response(3, '', 'تغییری ایجاد نشد');
            }
        } else {
            echo $this->mangareader->create_response(2, '', validation_errors());
        }
    }

    public function edit_category($cat_id) {
        $this->load->model('backend/category_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'categories' => $this->category_model->categories(),
            'category' => $this->category_model->get_category_by_id($cat_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        if(!empty($data['category'])) {
            $this->parser->parse('admin/dashboard_edit_category', $data);
        } else {
            $this->parser->parse('admin/404', $data);
        }
    }

    public function do_edit_category($cat_id) {
        $this->load->model('backend/category_model');
        $data = array();
        // Validation Config
        $validation_config = array(
            'required' => 'فیلد %s الزامی می باشد',
        );
        // Name validation
        $this->form_validation->set_rules('cat_name', 'دسته', 
            array(
                'required'
            ),$validation_config);      

        if($this->form_validation->run()) {
            $data['cat_name'] = $this->input->post('cat_name');
            $data['modified_at'] = date('Y-m-d H:i:s');
            $updated_cat = $this->category_model->update_category($cat_id, $data);
            if($updated_cat > 0) {
                echo $this->mangareader->create_response(1, '', 'تغییرات با موفقیت انجام شد');
            } else {
                echo $this->mangareader->create_response(3, '', 'تغییری ایجاد نشد');
            }
        } else {
            echo $this->mangareader->create_response(2, '', validation_errors());
        }
    }

    public function ajax_latest_categories() {
        $this->load->model('backend/category_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'categories' => $this->category_model->categories(6),
        );
        $this->parser->parse('admin/ajax_latest_categories', $data);
    }

    public function users() {
        $this->load->model('frontend/comments_model');
        $this->load->model('backend/users_model');
        $this->load->library('pagination');
        $config = array();
        $config['base_url'] = base_url('dashboard') . '/users/';
        $config['total_rows'] = $this->users_model->users_count();
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination center col m12 s12">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active blue">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="waves-effect">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="waves-effect">';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = '<i class="material-icons">chevron_left</i>';
        $config['last_link'] = '';
        $config['prev_link'] = '<i class="material-icons">chevron_right</i>';
        $config['first_link'] = '';
        $config['first_url'] = base_url('dashboard') . '/users/'.'0';
        $config['cur_tag_open'] = '<li class="active blue"><a href="#!">';
        $config['cur_tag_close'] = '</a></li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'links' =>$this->pagination->create_links(),
            'users' => $this->users_model->get_all_users($config['per_page'], $page),
        );
        $this->parser->parse('admin/dashboard_archive_users', $data);
    }

    public function edit_user($id) {
        $this->load->model('backend/users_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'user' => $this->users_model->get_users_by_id($id),
            'users' => $this->users_model->get_all_users(10),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        if(!empty($data['user'])) {
            $this->parser->parse('admin/dashboard_edit_user', $data);
        } else {
            $this->parser->parse('admin/404', $data);
        }
    }

    public function do_edit_user() {
        $this->load->model('backend/users_model');
        $this->load->library('encryption');
        $user_id = $this->input->post('user_id');
        $current_user = $this->users_model->get_users_by_id($user_id);
        $upload_config['upload_path']= 'public/img/profile_images/';
        $upload_config['allowed_types']='jpg|png';
        $upload_config['encrypt_name'] = TRUE;
        $config['overwrite'] = TRUE;
        $this->load->library('upload',$upload_config);

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

        // Password Validation
        $this->form_validation->set_rules('password', 'رمز عبور', 
            array(
                'required',
                'min_length[5]',
                'max_length[30]',
                'trim'
            ), $config);

        // Password Validation
        $this->form_validation->set_rules('role', 'نقش', 
            array(
                'required',
            ), $config);


        if($this->form_validation->run()) {
            $data = array();

            if($this->input->post('username')   != "") {$data['username']   = $this->input->post('username');}            
            if($this->input->post('firstname')  != "") {$data['firstname']  = $this->input->post('firstname');}            
            if($this->input->post('lastname')   != "") {$data['lastname']   = $this->input->post('lastname');}            
            if($this->input->post('email')      != "") {$data['email']      = $this->input->post('email');}            
            if($this->input->post('role')       != "") {$data['role']       = $this->input->post('role');}            
            if($this->input->post('password')   != "") {$data['password']   = $this->encryption->encrypt($this->input->post('password'));}        


            $data['modified_at'] = date('Y-m-d H:i:s');            

            
            $user_profile_picture_url = $current_user[0]->profile_picture_url;
            $user_profile_cover_url = $current_user[0]->profile_cover_url;

            if($user_profile_picture_url != $this->input->post('profile_picture_url')) {
                if (!$this->upload->do_upload('profile_picture')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data = array('upload_data' => $this->upload->data());
                    $image = $image_data['upload_data']['file_name'];
                    // echo $image;
                    $data['profile_picture_url'] = $image;
                }
            } elseif($user_profile_cover_url != $this->input->post('profile_cover_url')) {
                $upload_config['upload_path']= 'public/img/profile_covers/';
                $this->upload->initialize($upload_config);
                if (!$this->upload->do_upload('profile_cover')) {
                    echo $this->upload->display_errors();
                } else {
                    $image_data = array('upload_data' => $this->upload->data());
                    $image = $image_data['upload_data']['file_name'];
                    // echo $image;/
                    $data['profile_cover_url'] = $image;
                }
            } else {
            }

            $update_id = $this->users_model->update_user($data, $user_id);
            if($update_id > 0) {
                echo $this->mangareader->create_response(1, '', 'ویرایش کاربر با موفقیت انجام شد');
            } else {
                echo $this->mangareader->create_response(3,'','ویرایش کاربر نا موفق بود');
            }

        } else {
            echo $this->mangareader->create_response(2, base_url('dashboard').'/upload', validation_errors());
        }


    }

    public function ajax_latest_users() {
        $this->load->model('backend/users_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'users' => $this->users_model->get_all_users(6),
        );
        $this->parser->parse('admin/ajax_latest_users', $data);
    }

    public function create_user() {
        $this->load->model('backend/users_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $sidebar,
            'urls' => $breadcrumb,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'users' => $this->users_model->get_all_users(6),
        );
        $this->parser->parse('admin/dashboard_add_user', $data);
    }

    public function do_create_user() {
        $this->load->library('encryption');
        $this->load->model('frontend/auth_model');

        $upload_config['upload_path']= 'public/img/profile_images/';
        $upload_config['allowed_types']='jpg|png';
        $upload_config['encrypt_name'] = TRUE;
        $config['overwrite'] = TRUE;
        $this->load->library('upload',$upload_config);

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


        // Password Validation
        $this->form_validation->set_rules('role', 'نقش', 
            array(
                'required',
            ), $config);


        if($this->form_validation->run()) {

            $data = array(
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'role' => $this->input->post('role'),
                'password' => $this->encryption->encrypt($this->input->post('password'))
            );

            $data['created_at'] = date('Y-m-d H:i:s');            

            if (!$this->upload->do_upload('profile_picture')) {
                echo $this->upload->display_errors();
            } else {
                $image_data = array('upload_data' => $this->upload->data());
                $image = $image_data['upload_data']['file_name'];
                $data['profile_picture_url'] = $image;
            }

            $upload_config['upload_path']= 'public/img/profile_covers/';
            $this->upload->initialize($upload_config);
            if (!$this->upload->do_upload('profile_cover')) {
                echo $this->mangareader->create_response(2, '', $this->upload->display_errors());
            } else {
                $image_data = array('upload_data' => $this->upload->data());
                $image = $image_data['upload_data']['file_name'];
                $data['profile_cover_url'] = $image;
            }

            $update_id = $this->auth_model->register($data);
            if($update_id > 0) {
                echo $this->mangareader->create_response(1, '', 'کاربر با موفقیت ساخته شد');
            } else {
                echo $this->mangareader->create_response(3,'','ساختن کاربر نا موفق بود');

            }
        } else {
            echo $this->mangareader->create_response(2, base_url('dashboard').'/upload', validation_errors());
        }
    }

    public function remove_article($id) {
        $episodes = $this->post_model->get_episodes_by_id($id);
        $this->post_model->delete_chapter_by_article_id($id);
        $this->post_model->delete_episodes_by_article_id($id);
        $this->post_model->delete_article($id);
        foreach($episodes as $episode) {
            $link = 'public/img/episodes_images/'.$episode->image_name;
            if(file_exists($link)) {
                unlink($link);
            }
        }
        $this->session->set_flashdata('success', 'مطلب با موفقیت حذف شد');
        redirect(base_url('dashboard/archive'));
    }

    public function remove_chapter($article_id, $chapter_id) { 
        $episodes = $this->post_model->get_episodes_by_chapter_id($chapter_id);
        // var_dump($episodes);
        $this->post_model->delete_episodes_by_chapter_id($chapter_id);
        $this->post_model->delete_chapter_by_article_id($article_id);
        foreach($episodes as $episode) {
            $link = 'public/img/episodes_images/'.$episode->image_name;
            if(file_exists($link)) {
                unlink($link);
            }
        }
        $this->session->set_flashdata('success', 'مطلب با موفقیت حذف شد');
        redirect(base_url('dashboard/chapters/'.$article_id));
    }

    public function remove_episode($article_id, $episode_id) { 
        $episode = $this->post_model->get_episode_by_id($episode_id);
        $this->post_model->delete_episode_by_episode_id($episode_id);
        $link = 'public/img/episodes_images/'.$episode[0]->image_name;
        if(file_exists($link)) {
            unlink($link);
        }
        $this->session->set_flashdata('success', 'مطلب با موفقیت حذف شد');
        redirect(base_url('dashboard/episodes/'.$article_id));
    }

    public function remove_category($id) {
        $this->load->model('backend/category_model');
        $this->category_model->delete_category($id);
        $this->session->set_flashdata('success', 'دسته با موفقیت حذف شد');
        redirect(base_url('dashboard/categories'));
    }

    public function remove_comment($id,$redirect=null) {
        $this->load->model('frontend/comments_model');
        $this->comments_model->delete_comment($id);
        $this->session->set_flashdata('success', 'نظر با موفقیت حذف شد');
        if($redirect != null) {
            redirect(base_url('dashboard/'.$redirect));
        } else {
            redirect(base_url('dashboard/comments'));
        }
    }

    public function remove_alert($id) {
        $this->load->model('backend/alerts_model');
        $this->alerts_model->delete_alert($id);
        $this->session->set_flashdata('success', 'گزارش با موفقیت حذف شد');
        redirect(base_url('dashboard/alerts'));
    }

    public function remove_contact($id) {
        $this->load->model('backend/contacts_model');
        $this->contacts_model->delete_contact($id);
        $this->session->set_flashdata('success', 'گزارش با موفقیت حذف شد');
        redirect(base_url('dashboard/contacts'));
    }

    // links
    public function links() {
        $this->load->model('backend/link_model');
        $this->load->library('pagination');
        $config = array();
        $config['base_url']             = base_url('dashboard') . '/links/';
        $config['total_rows']           = $this->link_model->count_links();
        $config['per_page']             = 10;
        $config['full_tag_open']        = '<ul class="pagination center col m12 s12">';
        $config['full_tag_close']       = '</ul>';
        $config['num_tag_open']         = '<li class="waves-effect">';
        $config['num_tag_close']        = '</li>';
        $config['cur_tag_open']         = '<li class="active blue">';
        $config['cur_tag_close']        = '</li>';
        $config['prev_tag_open']        = '<li class="waves-effect">';
        $config['prev_tag_close']       = '</li>';
        $config['next_tag_open']        = '<li class="waves-effect">';
        $config['next_tag_close']       = '</li>';
        $config['next_link']            = '<i class="material-icons">chevron_left</i>';
        $config['last_link']            = '';
        $config['prev_link']            = '<i class="material-icons">chevron_right</i>';
        $config['first_link']           = '';
        $config['first_url']            = base_url('dashboard') . '/links/'.'0';
        $config['cur_tag_open']         = '<li class="active blue"><a href="#!">';
        $config['cur_tag_close']        = '</a></li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header'        => $header,
            'footer'        => $footer,
            'sidebar'       => $sidebar,
            'urls'          => $breadcrumb,
            'csrf_name'     => $this->security->get_csrf_token_name(),
            'csrf_hash'     => $this->security->get_csrf_hash(),
            'links'         => $this->link_model->get_latest_links($config['per_page'], $page),
            'pagination'    => $this->pagination->create_links(),
        );
        $this->parser->parse('admin/dashboard_archive_links', $data);
    }

    public function add_link() {
        $this->load->model('backend/link_model');

        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header'        => $header,
            'footer'        => $footer,
            'sidebar'       => $sidebar,
            'urls'          => $breadcrumb,
            'csrf_name'     => $this->security->get_csrf_token_name(),
            'links'         => $this->link_model->get_latest_links(6),
            'parent_links'  => $this->link_model->get_parent_links(),
            'csrf_hash'     => $this->security->get_csrf_hash(),
        );
        $this->parser->parse('admin/dashboard_add_link', $data);
    }
    
    public function create_link() {
        $this->load->model('backend/link_model');
        // Validation Config
        $validation_config = array(
            'required' => 'فیلد %s الزامی می باشد',
        );

        // Name validation
        $this->form_validation->set_rules('name', 'نام ', 
            array(
                'required'
            ),$validation_config);         

        // address validation
        $this->form_validation->set_rules('address', 'آدرس ', 
            array(
                'required'
            ),$validation_config);         

        if($this->form_validation->run()) {
            $data = array(
                'name'          => $this->input->post('name'),
                'address'       => $this->input->post('address'),
                'created_at'    => date('Y-m-d H:i:s'),
                'parent_id'     => $this->input->post('parent_id'),
                'position'      => $this->input->post('position'),
            );

            if($this->input->post('icon')) {
                $data['icon'] = $this->input->post('icon');
            }

            $new_link = $this->link_model->create_link($data);
            if($new_link > 0) {
                echo $this->mangareader->create_response(1, '', 'لینک با موفقیت ساخته شد');
            } else {
                echo $this->mangareader->create_response(3, '', 'ساختن لینک نا موفق بود');
            }

        } else {
            echo $this->mangareader->create_response(2, '', validation_errors());
        }
    }

    public function link($id) {
        $this->load->model('backend/link_model');

    }

    public function remove_link($id) {
        $this->load->model('backend/link_model');
        $this->link_model->delete_link($id);
        $this->session->set_flashdata('success', 'دسته با موفقیت حذف شد');
        redirect(base_url('dashboard/links'));
    }

    public function edit_link($id) {
        $this->load->model('backend/link_model');
        $header     = $this->load->view('admin/layout/header', null, true);
        $footer     = $this->load->view('admin/layout/footer', null, true);
        $sidebar    = $this->load->view('admin/layout/sidebar', null, true);
        $breadcrumb = $this->uri->segment_array();
        $data = array(
            'header'    => $header,
            'footer'    => $footer,
            'sidebar'   => $sidebar,
            'urls'      => $breadcrumb,
            'links'     => $this->link_model->get_latest_links(6),
            'link'      => $this->link_model->get_link_by_id($id),
            'parent_links'  => $this->link_model->get_parent_links(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );
        if(!empty($data['link'])) {
            $this->parser->parse('admin/dashboard_edit_link', $data);
        } else {
            $this->parser->parse('admin/404', $data);
        }
    }

    public function do_edit_link($id) {
        $this->load->model('backend/link_model');
        $data = array();

        // if($this->form_validation->run()) {
        if(!empty($this->input->post('name'))) { $data['name'] = $this->input->post('name'); }
        if(!empty($this->input->post('address'))) { $data['address'] = $this->input->post('address'); }
        if(!empty($this->input->post('icon'))) { $data['icon'] = $this->input->post('icon'); }
        if(!empty($this->input->post('position'))) { $data['position'] = $this->input->post('position'); }
        $data['parent_id'] = $this->input->post('parent_id');

        $data['modified_at'] = date('Y-m-d H:i:s');
        $updated_link = $this->link_model->update_link($id, $data);
        if($updated_link > 0) {
            echo $this->mangareader->create_response(1, '', 'تغییرات با موفقیت انجام شد');
        } else {
            echo $this->mangareader->create_response(3, '', 'تغییری ایجاد نشد');
        }

    }

    public function ajax_latest_links() {
        $this->load->model('backend/link_model');
        $data = array(
            'links'  => $this->link_model->get_latest_links(6),
        );
        $this->parser->parse('admin/ajax_latest_links', $data);
    }

}