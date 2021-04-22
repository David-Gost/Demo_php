<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Sys_item_classification_dto extends Base_dto {

    public function __construct() {
        parent::__construct();
    }

    public $cname;
    public $is_add = 0;
    public $is_modify = 0;
    public $is_del = 0;
    public $is_ckedit = 0;
    public $remark;
    public $is_show = 0;

    function getCname() {
        return $this->cname;
    }

    function getIs_add() {
        return $this->is_add;
    }

    function getIs_modify() {
        return $this->is_modify;
    }

    function getIs_del() {
        return $this->is_del;
    }

    function getIs_ckedit() {
        return $this->is_ckedit;
    }

    function getRemark() {
        return $this->remark;
    }

    function getIs_show() {
        return $this->is_show;
    }

    function setCname($cname) {
        $this->cname = $cname;
    }

    function setIs_add($is_add) {
        $this->is_add = $is_add;
    }

    function setIs_modify($is_modify) {
        $this->is_modify = $is_modify;
    }

    function setIs_del($is_del) {
        $this->is_del = $is_del;
    }

    function setIs_ckedit($is_ckedit) {
        $this->is_ckedit = $is_ckedit;
    }

    function setRemark($remark) {
        $this->remark = $remark;
    }

    function setIs_show($is_show) {
        $this->is_show = $is_show;
    }

}
