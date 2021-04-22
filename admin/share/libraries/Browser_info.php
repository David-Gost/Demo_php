<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//require_once VENDOR_PATH . 'autoload.php';

class Browser_info {

    public $agent;

    function __construct() {
        $agent = new Jenssegers\Agent\Agent();

        $this->agent = $agent;
    }

    /**
     * 
     * @return \Browser_info_dto
     */
    public function get_clint_browser() {
        $browsers = $this->agent->browser();
        $borwser_version = $this->agent->version($browsers);

        //---ip取得
        $ip_array = array();
        $ip_array['HTTP_CLIENT_IP'] = $this->get_server_ip('HTTP_CLIENT_IP');
        $ip_array['HTTP_X_FORWARDED_FOR'] = $this->get_server_ip('HTTP_X_FORWARDED_FOR');
        $ip_array['HTTP_X_FORWARDED'] = $this->get_server_ip('HTTP_X_FORWARDED');
        $ip_array['HTTP_X_CLUSTER_CLIENT_IP'] = $this->get_server_ip('HTTP_X_CLUSTER_CLIENT_IP');
        $ip_array['HTTP_FORWARDED_FOR'] = $this->get_server_ip('HTTP_FORWARDED_FOR');
        $ip_array['HTTP_FORWARDED'] = $this->get_server_ip('HTTP_FORWARDED');
        $ip_array['REMOTE_ADDR'] = $this->get_server_ip('REMOTE_ADDR');
        $ip_array['HTTP_VIA'] = $this->get_server_ip('HTTP_VIA');


        $browser_info_dto = new Browser_info_dto();
        $browser_info_dto->setBorwser_name($browsers);
        $browser_info_dto->setBorwser_version($borwser_version);
        $browser_info_dto->setDevice_type($this->get_device_type());
        $browser_info_dto->setDevice_os($this->agent->platform());
        $browser_info_dto->setLanguage_array($this->agent->languages());
        $browser_info_dto->setIp_array($ip_array);
        $browser_info_dto->setIs_robot($this->agent->isRobot() ? '1' : '0');
        $browser_info_dto->setRobot_name($this->agent->robot());

        return $browser_info_dto;
    }

    public function check_is_pc() {
        if ($this->agent->isDesktop()) {
            return true;
        } else {
            
            return false;
        }
    }

    private function get_device_type() {
        if ($this->agent->isDesktop()) {
            return 'Desktop';
        } else {

            if ($this->agent->isMobile()) {
                return 'Mobile';
            } else {
                return 'Tablet';
            }
        }
    }

    private function get_server_ip($ip_type_name) {
        if (!empty($_SERVER[$ip_type_name])) {
            return $_SERVER[$ip_type_name];
        } else {
            return ' ';
        }
    }

}

/**
 * @param string $device_type
 * <p>裝置類型</p>
 * @param string $device_os
 * <p>裝置系統名稱</p>
 * @param string $borwser_name
 * <p>瀏覽器名稱</p>
 * @param string $borwser_version
 * <p>瀏覽器版本</p>
 * @param string $ip_array
 * <p>ip陣列</p>
 * @param string $language_array
 * <p>瀏覽器偏好語言陣列</p>
 * 
 */
class Browser_info_dto {

    public $device_type;
    public $device_os;
    public $borwser_name;
    public $borwser_version;
    public $ip_array;
    public $language_array;
    public $is_robot;
    public $robot_name;

    function getDevice_type() {
        return $this->device_type;
    }

    function getDevice_os() {
        return $this->device_os;
    }

    function getBorwser_name() {
        return $this->borwser_name;
    }

    function getBorwser_version() {
        return $this->borwser_version;
    }

    function getIp_array() {
        return $this->ip_array;
    }

    function getLanguage_array() {
        return $this->language_array;
    }

    function getIs_robot() {
        return $this->is_robot;
    }

    function getRobot_name() {
        return $this->robot_name;
    }

    function setIs_robot($is_robot) {
        $this->is_robot = $is_robot;
    }

    function setRobot_name($robot_name) {
        $this->robot_name = $robot_name;
    }

    function setDevice_type($device_type) {
        $this->device_type = $device_type;
    }

    function setDevice_os($device_os) {
        $this->device_os = $device_os;
    }

    function setBorwser_name($borwser_name) {
        $this->borwser_name = $borwser_name;
    }

    function setBorwser_version($borwser_version) {
        $this->borwser_version = $borwser_version;
    }

    function setIp_array($ip_array) {
        $this->ip_array = $ip_array;
    }

    function setLanguage_array($language_array) {
        $this->language_array = $language_array;
    }

}
