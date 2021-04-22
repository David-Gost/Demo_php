<?php
use function GuzzleHttp\json_encode;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 */
class Front extends AC_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        header("Location: ".base_url("admin"));
        $this->load->view('index');
    }

}
