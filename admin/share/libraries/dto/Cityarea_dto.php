<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Cityarea_dto extends Base_dto {

    public function __construct() {
        parent::__construct();
    }

    public $parent_sid;
    public $post_code;
    public $area_name;
    public $remark;

    function getParent_sid() {
        return $this->parent_sid;
    }

    function getPost_code() {
        return $this->post_code;
    }

    function getArea_name() {
        return $this->area_name;
    }

    function getRemark() {
        return $this->remark;
    }

    function setParent_sid($parent_sid) {
        $this->parent_sid = $parent_sid;
        return $this;
    }

    function setPost_code($post_code) {
        $this->post_code = $post_code;
        return $this;
    }

    function setArea_name($area_name) {
        $this->area_name = $area_name;
        return $this;
    }

    function setRemark($remark) {
        $this->remark = $remark;
        return $this;
    }

}
