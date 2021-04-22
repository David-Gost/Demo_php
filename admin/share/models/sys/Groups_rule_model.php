<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property Groups_rule_dto $groups_rule_dto
 *
 */
class Groups_rule_model extends AC_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();


        $this->load->library("dto/Groups_rule_dto", "groups_rule_dto");

        $this->_table = 'sys_groups_rule';
        $this->_dto = 'Groups_rule_dto';
    }

    public function find_groups_rule_by_groups_sid($groups_sid) {

        $where = array(
            'groups_sid' => $groups_sid
        );
        $data = $this->find($where)->all();

        return object_to_array($data);
    }

    /**
     *
     * @param Groups_rule_dto $groups_rule_dto
     * @return int insert_id
     */
    public function add_groups_rule(Groups_rule_dto $groups_rule_dto) {

        $data = object_to_array($groups_rule_dto);
        return $this->create($data);
    }

    /**
     *
     * @param Groups_rule_dto $groups_rule_dto
     * @return type
     */
    public function modify_groups_rule(Groups_rule_dto $groups_rule_dto) {

        $props = object_to_array($groups_rule_dto);
        return $this->update($groups_rule_dto->sid, $props);
    }

    public function delete_by_groups_sid(Groups_rule_dto $groups_rule_dto) {

        return $this->delete_where(array('groups_sid' => $groups_rule_dto->groups_sid));
    }

}
