<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alert extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('mangareader');
		$this->load->model('backend/alerts_model');
	}

	// Save alerts
	public function index() {

        // Validation Config
        $config = array(
            'required' => 'فیلد %s الزامی می باشد',
            'max_length' => 'حداکثر فیلد %s باید 12 کاراکتر باشد',
            'min_length' => 'حداقل فیلد %s باید 5 کاراکتر باشد',
            'is_unique' => '%s وارد شده قبلا ثبت گردیده است',
            'matches' => '%s وارد شده مطاقبت ندارد',
        );

        // Username validation
        $this->form_validation->set_rules('text', 'متن گزارش', 
            array(
                'required',
                'min_length[1]',
                'max_length[400]',
            ),$config);

        if($this->form_validation->run()) {
        	$data = array(
        		'text' 	=> $this->input->post('text'),
        		'type_id' 	=> $this->input->post('id'),
        		'type' 	=> $this->input->post('type'),
        		'created_at' => date('Y-m-d H:i:s'),
        	);
            $alert_id = $this->alerts_model->save_alert($data);
            if($alert_id) {
	            echo $this->mangareader->create_response(2, '', "ok");
            } else {
	            echo $this->mangareader->create_response(2, '', "bad");
            }

        } else {
            echo $this->mangareader->create_response(2, '', validation_errors());
        }

	}

}