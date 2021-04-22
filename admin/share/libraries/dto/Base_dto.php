<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Base_dto {

    public function __construct() {

        $this->getSid();
        $this->getCreatedate();
        $this->getCreateuser();
        $this->getCreateip();
        $this->getUpdatedate();
        $this->getUpdateuser();
        $this->getUpdateip();
        $this->getSequence();
        $this->getLan();
    }

    public $sid;
    public $createdate;
    public $createuser;
    public $createip;
    public $updatedate;
    public $updateuser;
    public $updateip;
    public $sequence;
    public $lan;

    public function getSid() {
        return $this->sid;
    }

    public function getCreatedate() {

        if (empty($this->createdate)) {
            $this->createdate = GLOBAL_TIME;
        }
        return $this->createdate;
    }

    public function getCreateuser() {

        if (get_seesion_user()) {
            $user_seesion = get_seesion_user();
            $this->createuser = $user_seesion['cname'];
        }
        return $this->createuser;
    }

    public function getCreateip() {

//        if (empty($this->createip)) {
//            return $this->createip = GLOBAL_TIME;
//        }
//        return $this->createip;
    }

    public function getUpdatedate() {

        if (empty($this->updatedate)) {
            return $this->updatedate = GLOBAL_TIME;
        }
        return $this->updatedate;
    }

    public function getUpdateuser() {

        if (get_seesion_user()) {
            $user_seesion = get_seesion_user();
            $this->updateuser = $user_seesion['cname'];
        }
        return $this->updateuser;
    }

    public function getUpdateip() {
//        if (get_seesion_user()) {
//            $user_seesion = get_seesion_user();
//            $this->updateuser = $user_seesion['cname'];
//        }
//        return $this->updateuser;
    }

    public function getSequence() {

        if (empty($this->sequence)) {
            return $this->sequence = 0;
        }
        return $this->sequence;
    }

    public function setSid($sid) {
        $this->sid = $sid;
    }

    public function setCreatedate($createdate) {

        if (!empty($createdate)) {
            $this->createdate = $createdate;
        } else {
            $this->createdate = GLOBAL_TIME;
        }
    }

    public function setCreateuser($createuser) {

        if (get_seesion_user()) {
            $user_seesion = get_seesion_user();
            $this->createuser = $user_seesion['cname'];
        } else {
            $this->createuser = $createuser;
        }
    }

    public function setCreateip($createip) {

//        if (get_seesion_user()) {
//            $user_seesion = get_seesion_user();
//            $this->createuser = $user_seesion['cname'];
//        } else {
//            $this->createuser = $createuser;
//        }
    }

    public function setUpdatedate($updatedate) {

        if (!empty($updatedate)) {
            $this->updatedate = $updatedate;
        } else {
            $this->updatedate = GLOBAL_TIME;
        }
    }

    public function setUpdateuser($updateuser) {
        $this->updateuser = $updateuser;
    }

    public function setUpdateip($updateip) {
//        $this->updateuser = $updateuser;
    }

    public function setSequence($sequence) {

        $this->sequence = $sequence;

        if (is_null($this->sequence)) {
            $this->sequence = 0;
        }
    }

    public function getLan() {

        if (empty($this->lan)) {
            $this->lan = DEFAULT_LANG;
        }
        return $this->lan;
    }

    public function setLan($lan) {
        $this->lan = $lan;
    }

    public function get_self_dto()
    {

        $original_array = object_to_array(new Base_dto());

        $now_array = object_to_array(get_object_vars($this));

        foreach ($original_array as $key => $value) {

            if ($key != "sid" && $key != "sequence") {


                unset($now_array[$key]);
            }
        }
        unset($now_array['_accept']);
        unset($now_array['_refuse']);

        return array_to_object($now_array);
    }

}
