<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Company_dto extends Base_dto {

    public function __construct() {
        parent::__construct();
    }

    public $parent_sid;
    public $cname;
    public $cname_en;
    public $opendate;
    public $invoice;
    public $vatnumber;
    public $ceo;
    public $tel;
    public $tel_ext;
    public $tel_service;
    public $fax1;
    public $fax2;
    public $phone;
    public $email;
    public $city_sid;
    public $area_sid;
    public $post_code;
    public $address;
    public $address_en;
    public $service_time;

    function getParent_sid() {
        return $this->parent_sid;
    }

    function getCname() {
        return $this->cname;
    }

    function getCname_en() {
        return $this->cname_en;
    }

    function getOpendate() {
        return $this->opendate;
    }

    function getInvoice() {
        return $this->invoice;
    }

    function getVatnumber() {
        return $this->vatnumber;
    }

    function getCeo() {
        return $this->ceo;
    }

    function getTel() {
        return $this->tel;
    }

    function getTel_ext() {
        return $this->tel_ext;
    }

    function getTel_service() {
        return $this->tel_service;
    }

    function getFax1() {
        return $this->fax1;
    }

    function getFax2() {
        return $this->fax2;
    }

    function getPhone() {
        return $this->phone;
    }

    function getEmail() {
        return $this->email;
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

    function getAddress_en() {
        return $this->address_en;
    }

    function getService_time() {
        return $this->service_time;
    }

    function setParent_sid($parent_sid) {
        $this->parent_sid = $parent_sid;
    }

    function setCname($cname) {
        $this->cname = $cname;
    }

    function setCname_en($cname_en) {
        $this->cname_en = $cname_en;
    }

    function setOpendate($opendate) {
        $this->opendate = $opendate;
    }

    function setInvoice($invoice) {
        $this->invoice = $invoice;
    }

    function setVatnumber($vatnumber) {
        $this->vatnumber = $vatnumber;
    }

    function setCeo($ceo) {
        $this->ceo = $ceo;
    }

    function setTel($tel) {
        $this->tel = $tel;
    }

    function setTel_ext($tel_ext) {
        $this->tel_ext = $tel_ext;
    }

    function setTel_service($tel_service) {
        $this->tel_service = $tel_service;
    }

    function setFax1($fax1) {
        $this->fax1 = $fax1;
    }

    function setFax2($fax2) {
        $this->fax2 = $fax2;
    }

    function setPhone($phone) {
        $this->phone = $phone;
    }

    function setEmail($email) {
        $this->email = $email;
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

    function setAddress_en($address_en) {
        $this->address_en = $address_en;
    }

    function setService_time($service_time) {
        $this->service_time = $service_time;
    }

}
