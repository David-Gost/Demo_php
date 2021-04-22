<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Module_model $module_model 
 * @property Module_dto $module_dto
 * @property Rule_model $rule_model
 * @property Rule_dto $rule_dto
 * @property Groups_rule_model $groups_rule_model 
 * @property Groups_rule_dto $groups_rule_dto
 * 
 */
class Rule_manage extends AC_Controller {

    const TITLE = "系統功能維護";
    const VIEWFILE = "sys/rule_manage";
    const ADD_TYPE = "a";
    const MODIFY_TYPE = "m";

    public function __construct() {
        parent::__construct();

        $this->mTitle = Rule_manage::TITLE;
        $this->mViewFile = Rule_manage::VIEWFILE;

        $this->load->model("sys/Module_model", "module_model");
        $this->load->library("dto/Module_dto", "module_dto");

        $this->load->model("sys/Rule_model", "rule_model");
        $this->load->library("dto/Rule_dto", "rule_dto");

        $this->load->model("sys/Groups_rule_model", "groups_rule_model");
        $this->load->library("dto/Groups_rule_dto", "groups_rule_dto");
    }

    public function index() {
        $this->mViewData['active_type'] = Rule_manage::ADD_TYPE;

        $module_sid = $this->input->post('module_sid') ?: 1;

        $this->dropdown($module_sid);
        $this->mViewData['rule_dto'] = $this->rule_dto;
        $this->mViewData['rule_list'] = $this->rule_model->find_by_module_sid($module_sid);
    }

    public function choice_rule() {

        $this->mViewData['active_type'] = Rule_manage::MODIFY_TYPE;
        $module_sid = $this->input->post('module_sid');
        $sid = $this->input->post('sid');

        $this->mViewData['rule_dto'] = $this->rule_model->find_rule_by_sid($sid);
        $this->mViewData['rule_list'] = $this->rule_model->find_by_module_sid($module_sid);
        $this->dropdown($module_sid);
    }

    public function merge_rule() {

        $rule_dto = $this->prepare_dto();
        $active_type = $this->input->post('active_type');
        $this->mViewData['active_type'] = $active_type;

        $this->mViewData['rule_dto'] = $rule_dto;
        $this->mViewData['rule_list'] = $this->rule_model->find_by_module_sid($rule_dto->module_sid);
        $this->dropdown($rule_dto->module_sid);

        $error_message = $this->validation_message();
        if (!empty($error_message)) {
            set_warn_alert($error_message);
            return;
        }

        if (Rule_manage::ADD_TYPE === $active_type) {

            $rows = $this->rule_model->add_rule($rule_dto);

            $this->groups_rule_dto->setRule_sid($rows);
            $this->groups_rule_dto->setGroups_sid(1);
            $this->groups_rule_dto->setC(1);
            $this->groups_rule_dto->setU(1);
            $this->groups_rule_dto->setR(1);
            $this->groups_rule_dto->setD(1);

            $this->groups_rule_model->add_groups_rule($this->groups_rule_dto);
            if (empty($rows)) {
                set_warn_alert('新增失敗!!');
            } else {
                set_success_alert('新增成功!!');
                redirect('sys/rule_manage');
            }
        } else if (Rule_manage::MODIFY_TYPE == $active_type) {
            $rows = $this->rule_model->modify_rule($rule_dto);

            if (empty($rows)) {
                set_warn_alert('修改失敗!!');
            } else {
                set_success_alert('修改成功!!');
                redirect('sys/rule_manage');
            }
        }
    }

    public function del_rule() {

        $sid = $this->input->post('sid');

        $affected_rows = $this->rule_model->del_rule($sid);
        if ($affected_rows == 1) {
            set_success_alert('刪除成功!!');
        } else {
            set_warn_alert('刪除失敗!!');
        }
        redirect('sys/rule_manage');
    }

    private function validation_message() {
        $error_message = [];

        if (empty($this->input->post('rule_name'))) {
            $error_message = '功能名稱不可為空白!!';
        }

        return $error_message;
    }

    private function prepare_dto() {

        $this->rule_dto->setSid($this->input->post('sid'));
        $this->rule_dto->setParent_sid(0);
        $this->rule_dto->setRule_name($this->input->post('rule_name'));
        $this->rule_dto->setUrl($this->input->post('url'));
        $this->rule_dto->setModule_sid($this->input->post('module_sid'));
        $this->rule_dto->setSequence($this->input->post('sequence'));

        return $this->rule_dto;
    }

    private function dropdown($selected) {
        $this->mViewData['module_array'] = $this->main_module_select();
        $this->mViewData['selected'] = $selected;
    }

    private function main_module_select() {

        $module_list = $this->module_model->find_module();

        foreach ($module_list as $module_dto) {
            $module_array[$module_dto->sid] = $module_dto->module_name;
        }

        return $module_array;
    }

}
