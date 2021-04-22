<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Viewcount_dto extends Base_dto {

    public function __construct() {
        parent::__construct();
    }

    public $sourceip;
    public $viewdate;
    
    function getSourceip() {
        return $this->sourceip;
    }

    function getViewdate() {
        return $this->viewdate;
    }

    function setSourceip($sourceip) {
        $this->sourceip = $sourceip;
    }

    function setViewdate($viewdate) {
        $this->viewdate = $viewdate;
    }
}
