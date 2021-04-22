<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property Groups_dto $groups_dto
 *
 */
class Groups_model extends AC_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();


        $this->load->library("dto/Groups_dto", "groups_dto");

        $this->_table = 'sys_groups';
        $this->_dto = 'Groups_dto';
    }

    public function find_groups_all() {

        $sql = "SELECT a.*,(select count(*) from sys_account b where b.groups_sid = a.sid) AS groups_no
                FROM sys_groups a";
        $data = $this->query_by_sql($sql, true);
        return $data;
    }

    public function find_groups_by_sid($sid) {

        $where = array(
            'sid' => $sid
        );
        $data = $this->find($where)->get();

        return $data;
    }

    /**
     *
     * @param Groups_dto $groups_dto
     * @return int insert_id
     */
    public function add_groups(Groups_dto $groups_dto) {

        $data = object_to_array($groups_dto);
        return $this->create($data);
    }

    /**
     *
     * @param Groups_dto $groups_dto
     * @return type
     */
    public function modify_groups(Groups_dto $groups_dto) {

        $props = object_to_array($groups_dto);
        return $this->update($groups_dto->sid, $props);
    }

    public function delete_by_sid($sid) {

        return $this->delete($sid);
    }

}
