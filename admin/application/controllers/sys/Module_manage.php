<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Module_model $module_model
 * @property Module_dto $module_dto
 */
class Module_manage extends AC_Controller {

    const TITLE = "系統功能分類維護";
    const VIEWFILE = "sys/module_manage";
    const ADD_TYPE = "a";
    const MODIFY_TYPE = "m";

    public function __construct() {
        parent::__construct();

        $this->mTitle = Module_manage::TITLE;
        $this->mViewFile = Module_manage::VIEWFILE;

        $this->load->model("sys/Module_model", "module_model");
        $this->load->library("dto/Module_dto", "module_dto");
    }

    public function index() {
        $this->mViewData['active_type'] = Module_manage::ADD_TYPE;
        $this->mViewData['module_dto'] = $this->module_dto;
        $this->mViewData['module_list'] = $this->module_model->find_module();
    }

    public function choice_module() {
        $sid = $this->input->post('sid');

        $this->mViewData['active_type'] = Module_manage::MODIFY_TYPE;
        $this->mViewData['module_dto'] = $this->module_model->find_module_by_sid($sid);
        $this->mViewData['module_list'] = $this->module_model->find_module();
    }

    public function merge_module() {
        $module_dto = $this->prepare_dto();
        $active_type = $this->input->post('active_type');
        $this->mViewData['active_type'] = $active_type;
        
        $this->mViewData['module_dto'] = $module_dto;
        $this->mViewData['module_list'] = $this->module_model->find_module();

        $error_message = $this->validation_message();
        if (!empty($error_message)) {
            set_warn_alert($error_message);
            return;
        }

        if (Module_manage::ADD_TYPE === $active_type) {
            $rows = $this->module_model->add_module($module_dto);
            if (empty($rows)) {
                set_warn_alert('新增失敗!!');
            } else {
                set_success_alert('新增成功!!');
                redirect('sys/module_manage');
            }
        } else if (Module_manage::MODIFY_TYPE == $active_type) {
            $rows = $this->module_model->modify_module($module_dto);
            if (empty($rows)) {
                set_warn_alert('修改失敗!!');
            } else {
                set_success_alert('修改成功!!');
                redirect('sys/module_manage');
            }
        }
    }

    public function del_module() {
        $sid = $this->input->post('sid');

        $affected_rows = $this->module_model->del_module($sid);
        if ($affected_rows > 0) {
            set_success_alert('刪除成功!!');
        } else {
            set_warn_alert('刪除失敗!!');
        }
        redirect('sys/module_manage');
    }

    private function validation_message() {
        $error_message = [];

        if (empty($this->input->post('module_name'))) {
            $error_message = '分類名稱不可為空白!!';
        }

        return $error_message;
    }

    private function prepare_dto() {

        $this->module_dto->setSid($this->input->post('sid'));
        $this->module_dto->setModule_name($this->input->post('module_name'));
        $this->module_dto->setModule_icons($this->input->post('module_icons'));
        $this->module_dto->setModule_type($this->input->post('module_type') == 'on' ? '1' : '0');
        $this->module_dto->setSequence($this->input->post('sequence'));

        return $this->module_dto;
    }

}
