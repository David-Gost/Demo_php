<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Rule_dto extends Base_dto {

    public $parent_sid;
    public $rule_name;
    public $url;
    public $module_sid;

    public function __construct() {
        parent::__construct();
    }

    public function getParent_sid() {
        return $this->parent_sid;
    }

    public function setParent_sid($parent_sid) {
        $this->parent_sid = $parent_sid;
        
    }

    public function getRule_name() {
        return $this->rule_name;
    }

    public function setRule_name($rule_name) {
        $this->rule_name = $rule_name;
    }

    public function getUrl() {
        return $this->url;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    public function getModule_sid() {
        return $this->module_sid;
    }

    public function setModule_sid($module_sid) {
        $this->module_sid = $module_sid;
    }

}
