<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Sys_item_dto extends Base_dto {

    public function __construct() {
        parent::__construct();
    }

    public $classification_sid;
    public $cname;
    public $nickname;
    public $remark;

    function getClassification_sid() {
        return $this->classification_sid;
    }

    function getCname() {
        return $this->cname;
    }

    function getNickname() {
        return $this->nickname;
    }

    function getRemark() {
        return $this->remark;
    }

    function setClassification_sid($classification_sid) {
        $this->classification_sid = $classification_sid;
    }

    function setCname($cname) {
        $this->cname = $cname;
    }

    function setNickname($nickname) {
        $this->nickname = $nickname;
    }

    function setRemark($remark) {
        $this->remark = $remark;
    }

}
