<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Module_model extends AC_Model {

    public function __construct() {
        parent::__construct();

        $this->load->library("dto/Module_dto", "module_dto");
        $this->_table = 'sys_module';
        $this->_dto = 'Module_dto';
    }

    /**
     *
     * @param type $account_id
     * @return Module_dto
     */
    public function find_module_by_account($account_id) {

        $sql = 'SELECT
	sys_module.*, sys_rule.sid AS rule_sid ,
	sys_rule.rule_name ,
	sys_rule.url ,
	sys_rule.module_sid ,';

        if ($account_id != 1) {
            $sql .= 'sys_groups_rule.r ,
	sys_groups_rule.c ,
	sys_groups_rule.u ,
	sys_groups_rule.d';
        } else {
            $sql .= '1 r ,
	1 c ,
	1 u ,
	1 d';
        }

        $sql .= ' FROM
	sys_module
LEFT JOIN sys_rule ON sys_rule.module_sid = sys_module.sid
LEFT JOIN sys_groups_rule ON sys_rule.sid = sys_groups_rule.rule_sid';

        if ($account_id != 1) {
            $sql .= ' WHERE
	sys_groups_rule.groups_sid = ?
AND sys_groups_rule.r = 1';
        }

        $sql .= '
GROUP BY
	sys_rule.sid
                order by sys_module.sequence, sys_module.sid asc , sys_rule.sequence ,sys_rule.sid asc';

        $query = $this->query_by_sql($sql, array($account_id));

        return $query;
    }

    public function find_module() {

        $sql = 'select * from sys_module order by sequence,sid';
        $query = $this->query_by_sql($sql);
        return $query;
    }

    public function find_module_by_sid($sid) {

        $where = array(
            'sid' => $sid
        );
        return $this->find($where)->get();
    }

    public function modify_module(Module_dto $module_dto) {

        $props = object_to_array($module_dto);
//        $this->fb->warn($props, 'array');
        return $this->update($module_dto->sid, $props);
    }

    public function add_module(Module_dto $module_dto) {

        $data = object_to_array($module_dto);
//        $this->fb->warn($props, 'array');
        return $this->create($data);
    }

    public function del_module($sid) {

        return $this->delete($sid);
    }

}
