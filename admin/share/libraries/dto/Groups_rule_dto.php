<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Groups_rule_dto extends Base_dto {

    public function __construct() {
        parent::__construct();
    }

    public $groups_sid;
    public $rule_sid;
    public $c;
    public $u;
    public $r;
    public $d;

    function getGroups_sid() {
        return $this->groups_sid;
    }

    function getRule_sid() {
        return $this->rule_sid;
    }

    function getC() {
        return $this->c;
    }

    function getU() {
        return $this->u;
    }

    function getR() {
        return $this->r;
    }

    function getD() {
        return $this->d;
    }

    function setGroups_sid($groups_sid) {
        $this->groups_sid = $groups_sid;
        return $this;
    }

    function setRule_sid($rule_sid) {
        $this->rule_sid = $rule_sid;
        return $this;
    }

    function setC($c) {
        $this->c = $c;
        return $this;
    }

    function setU($u) {
        $this->u = $u;
        return $this;
    }

    function setR($r) {
        $this->r = $r;
        return $this;
    }

    function setD($d) {
        $this->d = $d;
        return $this;
    }

}
