<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Rule_model extends AC_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();


        $this->load->library("dto/Rule_dto", "rule_dto");

        $this->_table = 'sys_rule';
        $this->_dto = 'Rule_dto';
    }

    /**
     *
     * @param type $ruleDTO
     * @return insert_id
     */
    public function add_rule(Rule_dto $rule_dto) {

        $data = object_to_array($rule_dto);
//        $this->fb->warn($ruleDTO, 'dto');
        return $this->create($data);
    }

    public function del_rule($sid) {

        return $this->delete($sid);
    }

    public function find_rule() {

        $sql = 'select * from sys_rule order by sequence ,sid';
        $query = $this->query_by_sql($sql);
        return $query;
    }

    public function find_rule_by_sid($sid) {

        $where = array(
            'sid' => $sid
        );
        
        return $this->find($where)->get();
    }

    public function modify_rule(Rule_dto $rule_dto) {

        $props = object_to_array($rule_dto);
//        $this->fb->warn($props, 'array');
        return $this->update($rule_dto->sid, $props);
    }

//    /**
//     *
//     * @return Rule_dto
//     */
//    public function find_all() {
//
//        $data = $this->all();
//        return $data;
//    }
//
//    public function find_by_ruld_sid($rule_sid) {
//
//        $sql = "select a.* from sys_rule a ";
//
//        if (SUPERVISOR_ID !== $rule_sid) {
//            $sql.=" where a.sid <> " . SUPERVISOR_ID;
//        }
//
//        $query = $this->query_by_sql($sql);
//        return $query;
//    }
//
//    /**
//     *
//     * @param type $rule_name
//     * @return Rule_dto
//     */
//    public function find_by_rule_name($rule_name) {
//
//        $where = array(
//            'rule_name' => $rule_name
//        );
//        return $this->find($where)->get();
//    }

    /**
     *
     * @param type $parent_sid
     * @return Rule_dto
     */
    public function find_by_parent_sid($parent_sid) {

        $where = array(
            'parent_sid' => $parent_sid
        );
        $this->db->order_by('sequence');
        return $this->find($where)->all();
    }

    /**
     *
     * @param type $parent_sid
     * @return Rule_dto
     */
    public function find_by_module_sid_and_parent_sid($parent_sid, $module_sid) {

        $where = array(
            'parent_sid' => $parent_sid,
            'module_sid' => $module_sid
        );
        $this->db->order_by('sequence');
        return $this->find($where)->all();
    }

    /**
     *
     * @param type $parent_sid
     * @return Rule_dto
     */
    public function find_by_module_sid($module_sid) {

        $where = array(
            'module_sid' => $module_sid
        );

        $this->db->order_by('sequence,sid');
        return $this->find($where)->all();
    }

//    /**
//     *
//     * @param type $sid
//     * @return Rule_dto
//     */
//    public function find_by_id($sid) {
//
//        $where = array(
//            'sid' => $sid
//        );
//        return $this->find($where)->get();
//    }
//
//    /**
//     *
//     * @param type $dto
//     * @return type array()
//     */
//    private function _object_to_array($dto) {
//
//        $this->rule_dto = $dto;
//
//        $data = array(
//            'createdate' => $this->rule_dto->getCreatedate(),
//            'createuser' => $this->rule_dto->getCreateuser(),
//            'createip' => $this->rule_dto->getCreateip(),
//            'updatedate' => $this->rule_dto->getUpdatedate(),
//            'updateuser' => $this->rule_dto->getUpdateuser(),
//            'updateip' => $this->rule_dto->getUpdateip(),
//            'sid' => $this->rule_dto->getSid(),
//            'sequence' => $this->rule_dto->getSequence(),
//            'lan' => $this->rule_dto->getLan(),
//            'rule_name' => $this->rule_dto->getRule_name(),
//            'url' => $this->rule_dto->getUrl(),
//            'module_sid' => $this->rule_dto->getModule_sid()
//        );
//
////        $this->fb->warn($data, 'array');
//        return $data;
//    }
//
//    /**
//     * @param type $first_option_value 第一個選項值
//     * @param type $first_option_text 第一個選項文字
//     * @return array
//     */
//    public function get_rule_option($first_option_value = '', $first_option_text = '') {
//
//        $rule_list = $this->find_all();
//        if (!empty($first_option_text) && !empty($first_option_text)) {
//            $rule_arry = [
//                $first_option_value => $first_option_text
//            ];
//        } else {
//            $rule_arry = [];
//        }
//        foreach ($rule_list as $rule_dto) {
//
//            $rule_arry[$rule_dto->sid] = $rule_dto->rule_name;
//        }
//        return $rule_arry;
//    }
}
