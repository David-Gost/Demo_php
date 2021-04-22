<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Company_job_title_dto extends Base_dto {

    public function __construct() {
        parent::__construct();
    }

    public $parent_sid;
    public $cname;
    public $remark;

    function getParent_sid() {
        return $this->parent_sid;
    }

    function getCname() {
        return $this->cname;
    }

    function getRemark() {
        return $this->remark;
    }

    function setParent_sid($parent_sid) {
        $this->parent_sid = $parent_sid;
        return $this;
    }

    function setCname($cname) {
        $this->cname = $cname;
        return $this;
    }

    function setRemark($remark) {
        $this->remark = $remark;
        return $this;
    }

}
