<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Account_dto extends Base_dto {

    public $groups_sid;
    public $company_sid;
    public $company_title_sid; //常用分類
    public $account;
    public $password;
    public $cname;
    public $cname_en;
    public $email;
    public $phone;
    public $tel;
    public $birthday;
    public $city_sid;
    public $area_sid;
    public $post_code;
    public $address;
    public $dutydate;
    public $departuredate;
    public $contact_name;
    public $contact_phone;
    public $contact_relationship;
    public $isuse = 1;
    public $remark;
    public $group_name;
    public $company_name;
    public $job_name;
    public $confirmpassword;
    public $picinfo;

    function getGroups_sid() {
        return $this->groups_sid;
    }

    function getCompany_sid() {
        return $this->company_sid;
    }

    function getCompany_title_sid() {
        return $this->company_title_sid;
    }

    function getAccount() {
        return $this->account;
    }

    function getPassword() {
        return $this->password;
    }

    function getCname() {
        return $this->cname;
    }

    function getCname_en() {
        return $this->cname_en;
    }

    function getEmail() {
        return $this->email;
    }

    function getPhone() {
        return $this->phone;
    }

    function getTel() {
        return $this->tel;
    }

    function getBirthday() {
        return $this->birthday;
    }

    function getCity_sid() {
        return $this->city_sid;
    }

    function getArea_sid() {
        return $this->area_sid;
    }

    function getPost_code() {
        return $this->post_code;
    }

    function getAddress() {
        return $this->address;
    }

    function getDutydate() {
        return $this->dutydate;
    }

    function getDeparturedate() {
        return $this->departuredate;
    }

    function getContact_name() {
        return $this->contact_name;
    }

    function getContact_phone() {
        return $this->contact_phone;
    }

    function getContact_relationship() {
        return $this->contact_relationship;
    }

    function getIsuse() {
        return $this->isuse;
    }

    function getRemark() {
        return $this->remark;
    }

    function getGroup_name() {
        return $this->group_name;
    }

    function getCompany_name() {
        return $this->company_name;
    }

    function getJob_name() {
        return $this->job_name;
    }

    function getConfirmpassword() {
        return $this->confirmpassword;
    }

    function getPicinfo() {
        return $this->picinfo;
    }

    function setGroups_sid($groups_sid) {
        $this->groups_sid = $groups_sid;
    }

    function setCompany_sid($company_sid) {
        $this->company_sid = $company_sid;
    }

    function setCompany_title_sid($company_title_sid) {
        $this->company_title_sid = $company_title_sid;
    }

    function setAccount($account) {
        $this->account = $account;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setCname($cname) {
        $this->cname = $cname;
    }

    function setCname_en($cname_en) {
        $this->cname_en = $cname_en;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPhone($phone) {
        $this->phone = $phone;
    }

    function setTel($tel) {
        $this->tel = $tel;
    }

    function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    function setCity_sid($city_sid) {
        $this->city_sid = $city_sid;
    }

    function setArea_sid($area_sid) {
        $this->area_sid = $area_sid;
    }

    function setPost_code($post_code) {
        $this->post_code = $post_code;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function setDutydate($dutydate) {
        $this->dutydate = $dutydate;
    }

    function setDeparturedate($departuredate) {
        $this->departuredate = $departuredate;
    }

    function setContact_name($contact_name) {
        $this->contact_name = $contact_name;
    }

    function setContact_phone($contact_phone) {
        $this->contact_phone = $contact_phone;
    }

    function setContact_relationship($contact_relationship) {
        $this->contact_relationship = $contact_relationship;
    }

    function setIsuse($isuse) {
        $this->isuse = $isuse;
    }

    function setRemark($remark) {
        $this->remark = $remark;
    }

    function setGroup_name($group_name) {
        $this->group_name = $group_name;
    }

    function setCompany_name($company_name) {
        $this->company_name = $company_name;
    }

    function setJob_name($job_name) {
        $this->job_name = $job_name;
    }

    function setConfirmpassword($confirmpassword) {
        $this->confirmpassword = $confirmpassword;
    }

    function setPicinfo($picinfo) {
        $this->picinfo = $picinfo;
    }
}
