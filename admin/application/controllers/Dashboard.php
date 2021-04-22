<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Dashboard extends AC_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->mTitle = "Dashboard";
        $this->mViewFile = 'admin/dashboard';
    }
}