<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Sys_item_model $sys_item_model 
 * @property Sys_item_dto $sys_item_dto
 * @property Sys_item_classification_model $sys_item_classification_model 
 */
class Sys_item_manage extends AC_Controller {

    const TITLE = "系統項目";
    const VIEWFILE = "custom/sys_item";
    const ADD_TYPE = "a";
    const MODIFY_TYPE = "m";

    public function __construct() {
        parent::__construct();
        $this->mViewFile = Sys_item_manage::VIEWFILE;
        $this->mTitle = Sys_item_manage::TITLE;

        $this->load->model("custom/Sys_item_model", "sys_item_model");
        $this->load->library("dto/Sys_item_dto", "sys_item_dto");
        $this->load->model("custom/Sys_item_classification_model", "sys_item_classification_model");

        $this->mViewData['classification_list'] = creat_data_array('', $this->sys_item_classification_model->find_list());
    }

    public function index() {
        unset($_SESSION['sys_item_classification_sid']);
        $this->sys_item_list();
    }

    public function sys_item_list() {
        $this->mViewData['active_type'] = Sys_item_manage::ADD_TYPE;

        if (!empty($this->input->post('classification_sid'))) {
            $classification_sid = $this->input->post('classification_sid');
        } else {
            if (!empty($_GET['jump'])) {
                $classification_sid = $_GET['jump'];
            } else {
                if (!empty($this->session->userdata('sys_item_classification_sid'))) {
                    $classification_sid = $this->session->userdata('sys_item_classification_sid');
                } else {
                    $classification_sid = 1;
                }
            }
        }

        //紀錄session
        $this->session->set_userdata('sys_item_classification_sid', $classification_sid);
        $this->mViewData['sys_item_dto'] = $this->sys_item_dto;

        $this->mViewData['classification_dto'] = $this->sys_item_classification_model->find_by_sid($classification_sid);
        $this->mViewData['sys_item_list'] = $this->sys_item_model->find_list_by_classification_sid($classification_sid, '', '');
    }

    public function choice_sys_item() {
        $this->mViewData['active_type'] = Sys_item_manage::MODIFY_TYPE;

        $sid = $this->input->post('sid');

        $sys_item_dto = $this->sys_item_model->find_by_sid($sid);
        $this->mViewData['sys_item_dto'] = $sys_item_dto;
        $this->mViewData['classification_dto'] = $this->sys_item_classification_model->find_by_sid($sys_item_dto->classification_sid);
        $this->mViewData['sys_item_list'] = $this->sys_item_model->find_list_by_classification_sid($sys_item_dto->classification_sid, '', '');
    }

    public function merge_sys_item() {
        $sys_item_dto = $this->sys_item_model->params_to_dto($this->input);

        $active_type = $this->input->post('active_type');
        $this->mViewData['active_type'] = $active_type;
        $this->mViewData['sys_item_dto'] = $sys_item_dto;
        $this->mViewData['classification_dto'] = $this->sys_item_classification_model->find_by_sid($sys_item_dto->classification_sid);
        $this->mViewData['sys_item_list'] = $this->sys_item_model->find_list_by_classification_sid($sys_item_dto->classification_sid, '', '');

        $error_message = $this->validation_message($sys_item_dto);
        if (!empty($error_message)) {
            set_warn_alert($error_message);
            return;
        }

        if (Sys_item_manage::ADD_TYPE === $active_type) {
            $rows = $this->sys_item_model->add_dto($sys_item_dto);
            if (empty($rows)) {
                set_warn_alert('新增失敗!!');
            } else {
                set_success_alert('新增成功!!');
                redirect('custom/sys_item_manage/sys_item_list/');
            }
        } else if (Sys_item_manage::MODIFY_TYPE == $active_type) {
            $rows = $this->sys_item_model->modify_dto($sys_item_dto);
            if (empty($rows)) {
                set_warn_alert('修改失敗!!');
            } else {
                set_success_alert('修改成功!!');
                redirect('custom/sys_item_manage/sys_item_list/');
            }
        }
    }

    public function del_sys_item() {
        $sid = $this->input->post('sid');

        $affected_rows = $this->sys_item_model->delete($sid);
        if ($affected_rows == 1) {
            set_success_alert('刪除成功!!');
        } else {
            set_warn_alert('刪除失敗!!');
        }

        redirect('custom/sys_item_manage/sys_item_list');
    }

    private function validation_message($sys_item_dto) {

        $error_message = [];

        if (empty($sys_item_dto->classification_sid) || $sys_item_dto->classification_sid == 0) {
            $error_message = '分類不可為空白!!';
        }

        if (empty($sys_item_dto->cname)) {
            $error_message = '名稱不可為空白!!';
        }

        return $error_message;
    }

}
