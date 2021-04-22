<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Cityarea_model $cityarea_model 
 * @property Cityarea_dto $cityarea_dto
 */
class Cityarea_manage extends AC_Controller {

    const TITLE = "郵遞區號維護";
    const VIEWFILE = "sys/cityarea_manage";
    const ADD_TYPE = "a";
    const MODIFY_TYPE = "m";

    public function __construct() {
        parent::__construct();
        $this->mTitle = Cityarea_manage::TITLE;
        $this->mViewFile = Cityarea_manage::VIEWFILE;

        $this->load->model("sys/Cityarea_model", "cityarea_model");
        $this->load->library("dto/Cityarea_dto", "cityarea_dto");
    }

    public function index() {
        $this->mViewData['active_type'] = Cityarea_manage::ADD_TYPE;
        $parent_sid = $this->input->post('parent_sid') ?: 0;

        $this->mViewData['cityarea_dto'] = $this->cityarea_dto;
        $this->mViewData['cityarea_list'] = $this->cityarea_model->find_cityarea_by_parent_id($parent_sid);
        $this->dropdown($parent_sid);
    }

    public function choice_cityarea() {
        $this->mViewData['active_type'] = Cityarea_manage::MODIFY_TYPE;
        $parent_sid = $this->input->post('parent_sid');
        $sid = $this->input->post('sid');

        $this->mViewData['cityarea_dto'] = $this->cityarea_model->find_cityarea_by_sid($sid);
        $this->mViewData['cityarea_list'] = $this->cityarea_model->find_cityarea_by_parent_id($parent_sid);
        
        $this->dropdown($parent_sid);
    }

    public function merge_cityarea() {

        $cityarea_dto = $this->prepare_dto();

        $active_type = $this->input->post('active_type');
        $this->mViewData['active_type'] = $active_type;
        $this->mViewData['cityarea_dto'] = $cityarea_dto;
        $this->mViewData['cityarea_list'] = $this->cityarea_model->find_cityarea_by_parent_id($cityarea_dto->parent_sid);
        $this->dropdown($cityarea_dto->parent_sid);

        $error_message = ($cityarea_dto->parent_sid != 0 && $cityarea_dto->parent_sid != "") ? $this->validation_message('area') : $this->validation_message('');

        if (!empty($error_message)) {
            set_warn_alert($error_message);
            return;
        }

        if (Cityarea_manage::ADD_TYPE === $active_type) {
            $rows = $this->cityarea_model->add_cityarea($cityarea_dto);
            if (empty($rows)) {
                set_warn_alert('新增失敗!!');
            } else {
                set_success_alert('新增成功!!');
                redirect('sys/cityarea_manage/');
            }
        } else if (Cityarea_manage::MODIFY_TYPE == $active_type) {
            $rows = $this->cityarea_model->modify_cityarea($cityarea_dto);
            if (empty($rows)) {
                set_warn_alert('修改失敗!!');
            } else {
                set_success_alert('修改成功!!');
                redirect('sys/cityarea_manage/');
            }
        }
    }

    public function del_cityarea() {
        $sid = $this->input->post('sid');
        $affected_rows = $this->cityarea_model->delete_by_sid($sid);

        if ($affected_rows == 1) {
            set_success_alert('刪除成功!!');
        } else {
            set_warn_alert('刪除失敗!!');
        }
        redirect('sys/cityarea_manage');
    }

    private function validation_message($type) {

        $error_message = [];
        if (empty($this->input->post('area_name'))) {
            $error_message = '名稱不可為空白!!';
        }

        if ($type === 'area') {
            if (empty($this->input->post('post_code'))) {
                $error_message = '郵遞區號不可為空白!!';
            }
        }

        return $error_message;
    }

    private function prepare_dto() {

        $this->cityarea_dto->setSid($this->input->post('sid'));
        $this->cityarea_dto->setParent_sid($this->input->post('parent_sid'));
        $this->cityarea_dto->setArea_name($this->input->post('area_name'));
        $this->cityarea_dto->setPost_code($this->input->post('post_code'));
        $this->cityarea_dto->setRemark($this->input->post('remark'));
        $this->cityarea_dto->setSequence($this->input->post('sequence'));

        return $this->cityarea_dto;
    }

    private function dropdown($selected) {
        $this->mViewData['main_cityarea_array'] = $this->main_cityarea_select();
        $this->mViewData['selected'] = $selected;
    }

    private function main_cityarea_select() {
        $cityarea_main_class_list = $this->cityarea_model->find_cityarea_by_parent_id(0);
        $main_cityarea_array = [
            '0' => '---請選擇縣市---'
        ];

        foreach ($cityarea_main_class_list as $main_cityarea_dto) {
            $main_cityarea_array[$main_cityarea_dto->sid] = $main_cityarea_dto->area_name;
        }

        return $main_cityarea_array;
    }

}
