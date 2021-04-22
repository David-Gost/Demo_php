<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Sys_item_classification_model $sys_item_classification_model 
 * @property Sys_item_classification_dto $sys_item_classification_dto
 */
class Sys_item_classification_manage extends AC_Controller {

    const TITLE = "系統項目分類維護";
    const VIEWFILE = "custom/sys_item_classification";
    const ADD_TYPE = "a";
    const MODIFY_TYPE = "m";

    public function __construct() {
        parent::__construct();
        $this->mTitle = Sys_item_classification_manage::TITLE;
        $this->mViewFile = Sys_item_classification_manage::VIEWFILE;

        $this->load->model("custom/Sys_item_classification_model", "sys_item_classification_model");
        $this->load->library("dto/Sys_item_classification_dto", "sys_item_classification_dto");
    }

    public function index() {
        $this->mViewData['active_type'] = Sys_item_classification_manage::ADD_TYPE;
        $this->mViewData['sys_item_classification_dto'] = $this->sys_item_classification_dto;
        $this->mViewData['sys_item_classification_list'] = $this->sys_item_classification_model->find_all();
    }

    public function choice_sys_item_classification() {
        $this->mViewData['active_type'] = Sys_item_classification_manage::MODIFY_TYPE;
        $sid = $this->input->post('sid');
        $this->mViewData['sys_item_classification_dto'] = $this->sys_item_classification_model->find_by_sid($sid);
        $this->mViewData['sys_item_classification_list'] = $this->sys_item_classification_model->find_all();
    }

    public function merge_sys_item_classification() {

        $sys_item_classification_dto = $this->sys_item_classification_model->params_to_dto($this->input);
        $active_type = $this->input->post('active_type');
        $this->mViewData['active_type'] = $active_type;

        $this->mViewData['sys_item_classification_dto'] = $sys_item_classification_dto;
        $this->mViewData['sys_item_classification_list'] = $this->sys_item_classification_model->find_all();

        $error_message = $this->validation_message();
        if (!empty($error_message)) {
            set_warn_alert($error_message);
            return;
        }

        if (Sys_item_classification_manage::ADD_TYPE === $active_type) {
            $rows = $this->sys_item_classification_model->add_dto($sys_item_classification_dto);
            if (empty($rows)) {
                set_warn_alert('新增失敗!!');
            } else {
                set_success_alert('新增成功!!');
                redirect('custom/sys_item_classification_manage');
            }
        } else if (Sys_item_classification_manage::MODIFY_TYPE == $active_type) {
            $rows = $this->sys_item_classification_model->modify_dto($sys_item_classification_dto);
            if (empty($rows)) {
                set_warn_alert('修改失敗!!');
            } else {
                set_success_alert('修改成功!!');
                redirect('custom/sys_item_classification_manage');
            }
        }
    }

    public function del_sys_item_classification() {

        $sid = $this->input->post('sid');

        $affected_rows = $this->sys_item_classification_model->delete($sid);
        if ($affected_rows == 1) {
            set_success_alert('刪除成功!!');
        } else {
            set_warn_alert('刪除失敗!!');
        }

        redirect('custom/sys_item_classification_manage');
    }

    private function validation_message() {

        $error_message = [];
        if (empty($this->input->post('cname'))) {
            $error_message = '分類名稱不可為空白!!';
        }

        return $error_message;
    }

}
