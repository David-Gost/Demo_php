<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property Browser_history_dto $browser_history_dto
 * @property Browser_info $browser_info
 *
 */
class Browser_history_model extends AC_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();


        $this->load->library("dto/Browser_history_dto", "browser_history_dto");
        $this->load->library("Browser_info", "browser_info");

        $this->_table = 'sys_browser_history';
        $this->_dto = 'Browser_history_dto';
    }

    /**
     * @return int insert_id
     */
    public function add_browser_history() {

        $browser_info = $this->browser_info->get_clint_browser();
        $ip_dto = array_to_object($browser_info->ip_array);



        $browser_history_dto = new Browser_history_dto();
        $browser_history_dto->setDevice_type($browser_info->device_type);
        $browser_history_dto->setDevice_os($browser_info->device_os);
        $browser_history_dto->setBorwser_info($browser_info->borwser_name . '_' . $browser_info->borwser_version);
        $browser_history_dto->setHttp_clinet_ip($ip_dto->HTTP_CLIENT_IP);
        $browser_history_dto->setHttp_x_forwarded_for($ip_dto->HTTP_X_FORWARDED_FOR);
        $browser_history_dto->setHttp_x_forwarded($ip_dto->HTTP_X_FORWARDED);
        $browser_history_dto->setHttp_x_cluster_client_ip($ip_dto->HTTP_X_CLUSTER_CLIENT_IP);
        $browser_history_dto->setHttp_forwarded_for($ip_dto->HTTP_FORWARDED_FOR);
        $browser_history_dto->setHttp_forwarded($ip_dto->HTTP_FORWARDED);
        $browser_history_dto->setRemote_addr($ip_dto->REMOTE_ADDR);
        $browser_history_dto->setHttp_via($ip_dto->HTTP_VIA);
        $browser_history_dto->setLanguage_data(json_encode($browser_info->language_array));
        $browser_history_dto->setIs_robot($browser_info->is_robot);
        $browser_history_dto->setRobot_name($browser_info->robot_name);
        $browser_history_dto->setTime_cache((int) (microtime(true) * 1000));
        $browser_history_dto->setPost_info(json_encode($_POST));
        $browser_history_dto->setGet_info(json_encode($_GET));
        $browser_history_dto->setServer_info(json_encode($_SERVER));
        $browser_history_dto->setCookie_info(json_encode($_COOKIE));


        $data = object_to_array($browser_history_dto);

        return $this->create($data);
    }

    /**
     *
     * @param Browser_history_dto $browser_history_dto
     * @return int insert_id
     */
//    public function add_browser_history(Browser_history_dto $browser_history_dto) {
//
//        $data = object_to_array($browser_history_dto);
//
//        return $this->create($data);
//    }
//
//    public function modify_browser_history(Browser_history_dto $browser_history_dto) {
//
//        $data = object_to_array($browser_history_dto);
//
//        $affected_rows = $this->update($browser_history_dto->sid, $data);
//
//        return $affected_rows;
//    }

    /**
     *
     * @param type $sid
     * @return int affected_rows
     */
    public function del_browser_history($sid) {

        return $this->delete($sid);
    }

    /**
     *
     * @param type $sid
     * @return Browser_history_dto
     */
    public function find_browser_history_by_sid($sid) {

        $where = array(
            'sid' => $sid
        );
        return $this->find($where)->get();
    }

    /**
     * Counts all records inside a table.
     */
}
