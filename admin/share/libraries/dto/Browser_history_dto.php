<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Browser_history_dto extends Base_dto {

    public function __construct() {
        parent::__construct();
    }

    public $device_type;
    public $device_os;
    public $borwser_info;
    public $http_clinet_ip;
    public $http_x_forwarded_for;
    public $http_x_forwarded;
    public $http_x_cluster_client_ip;
    public $http_forwarded_for;
    public $http_forwarded;
    public $remote_addr;
    public $http_via;
    public $language_data;
    public $is_robot;
    public $robot_name;
    public $time_cache;
    public $server_info;
    public $cookie_info;
    public $post_info;
    public $get_info;

    function getDevice_type() {
        return $this->device_type;
    }

    function getDevice_os() {
        return $this->device_os;
    }

    function getBorwser_info() {
        return $this->borwser_info;
    }

    function getHttp_clinet_ip() {
        return $this->http_clinet_ip;
    }

    function getHttp_x_forwarded_for() {
        return $this->http_x_forwarded_for;
    }

    function getHttp_x_forwarded() {
        return $this->http_x_forwarded;
    }

    function getHttp_x_cluster_client_ip() {
        return $this->http_x_cluster_client_ip;
    }

    function getHttp_forwarded_for() {
        return $this->http_forwarded_for;
    }

    function getHttp_forwarded() {
        return $this->http_forwarded;
    }

    function getRemote_addr() {
        return $this->remote_addr;
    }

    function getHttp_via() {
        return $this->http_via;
    }

    function getLanguage_data() {
        return $this->language_data;
    }

    function getIs_robot() {
        return $this->is_robot;
    }

    function getRobot_name() {
        return $this->robot_name;
    }

    function getTime_cache() {
        return $this->time_cache;
    }

    function getServer_info() {
        return $this->server_info;
    }

    function getCookie_info() {
        return $this->cookie_info;
    }

    function getPost_info() {
        return $this->post_info;
    }

    function getGet_info() {
        return $this->get_info;
    }

    function setDevice_type($device_type) {
        $this->device_type = $device_type;
    }

    function setDevice_os($device_os) {
        $this->device_os = $device_os;
    }

    function setBorwser_info($borwser_info) {
        $this->borwser_info = $borwser_info;
    }

    function setHttp_clinet_ip($http_clinet_ip) {
        $this->http_clinet_ip = $http_clinet_ip;
    }

    function setHttp_x_forwarded_for($http_x_forwarded_for) {
        $this->http_x_forwarded_for = $http_x_forwarded_for;
    }

    function setHttp_x_forwarded($http_x_forwarded) {
        $this->http_x_forwarded = $http_x_forwarded;
    }

    function setHttp_x_cluster_client_ip($http_x_cluster_client_ip) {
        $this->http_x_cluster_client_ip = $http_x_cluster_client_ip;
    }

    function setHttp_forwarded_for($http_forwarded_for) {
        $this->http_forwarded_for = $http_forwarded_for;
    }

    function setHttp_forwarded($http_forwarded) {
        $this->http_forwarded = $http_forwarded;
    }

    function setRemote_addr($remote_addr) {
        $this->remote_addr = $remote_addr;
    }

    function setHttp_via($http_via) {
        $this->http_via = $http_via;
    }

    function setLanguage_data($language_data) {
        $this->language_data = $language_data;
    }

    function setIs_robot($is_robot) {
        $this->is_robot = $is_robot;
    }

    function setRobot_name($robot_name) {
        $this->robot_name = $robot_name;
    }

    function setTime_cache($time_cache) {
        $this->time_cache = $time_cache;
    }

    function setServer_info($server_info) {
        $this->server_info = $server_info;
    }

    function setCookie_info($cookie_info) {
        $this->cookie_info = $cookie_info;
    }

    function setPost_info($post_info) {
        $this->post_info = $post_info;
    }

    function setGet_info($get_info) {
        $this->get_info = $get_info;
    }

}
