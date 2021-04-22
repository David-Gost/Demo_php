<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Module_dto extends Base_dto {

    public $module_name;
    public $module_icons;
    public $module_type;
 
    public function __construct() {
        parent::__construct();
    }

    
    function getModule_name() {
        return $this->module_name;
    }
 
    function setModule_name($module_name) {
        $this->module_name = $module_name;
        return $this;
    }
    
    function getModule_icons() {
        return $this->module_icons;
    }

    function setModule_icons($module_icons) {
        $this->module_icons = $module_icons;
        return $this;
    }
    
    function getModule_type() {
        return $this->module_type;
    }

    function setModule_type($module_type) {
        $this->module_type = $module_type;
        return $this;
    }

}
