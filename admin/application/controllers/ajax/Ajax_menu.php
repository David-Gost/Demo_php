<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AJAX_menu extends AJAX_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

    }

    public function keep_module() {

        $result = $this->input->post('module_id');
        $key = $this->input->post('key');
        $this->session->set_userdata('module_id', $result);
        $this->session->set_userdata('temp_key', $key);
        echo json_encode(array('module_id' => $result));
        exit();
    }

}
