<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Groups_dto extends Base_dto {

    public $cname;
    public $company_sid;
    public $remark;

    public function __construct() {
        parent::__construct();
    }

    public function getCname() {
        return $this->cname;
    }

    public function setCname($cname) {
        $this->cname = $cname;
        return $this;
    }

    public function getCompany_sid() {
        return $this->company_sid;
    }

    public function setCompany_sid($company_sid) {
        $this->company_sid = $company_sid;
        return $this;
    }

    public function getRemark() {
        return $this->remark;
    }

    public function setRemark($remark) {
        $this->remark = $remark;
        return $this;
    }

}
