<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property Attributes_model $attributes_model
 */
class Attributes_manage extends AC_Controller
{

    const PARENT_TITLE = "參數維護";
    const PARENT_VIEW = "custom/attributes_parent_list";
    const CHILD_TITLE = "類別維護";
    const CHILD_VIEW = "custom/attributes_child_list";

    public function __construct()
    {
        parent::__construct();

        $this->mTitle = Attributes_manage::PARENT_TITLE;
        $this->mViewFile = Attributes_manage::PARENT_VIEW;

        $this->load->model("custom/Attributes_model", "attributes_model");
    }

    function index()
    {

        $view_array['sid'] = quick_modal_view_dto('', '', 'hidden');
        $view_array['parent_sid'] = quick_modal_view_dto('', '', 'hidden', 0);
        $view_array['cname'] = quick_modal_view_dto('名稱', '', '', '', 'col-md-6');
        $view_array['tag'] = quick_modal_view_dto('標籤', '');


        $madal_option = quick_modal_view_option('屬性分類維護', 'attributes_model', base_url('custom/attributes_manage/merge_attributes'));

        $this->mViewData['admin_madal'] = create_admin_modal($madal_option, $view_array);
        $this->mViewData['parent_attributes_array'] = $this->attributes_model->find_attributes_by_paretn_sid();
    }

    /**
     * <p>子項屬性維護</p>
     * @param type $parent_sid
     */
    public function child_manage($parent_sid = 0)
    {

        if (empty($parent_sid)) {
            $parent_sid = input_post('sid');
        }

        if (empty($parent_sid)) {
            redirect('custom/attributes_manage' . page());
        }

        $parent_dto = $this->attributes_model->find_by_sid($parent_sid);

        $this->mTitle = $parent_dto->cname . '-' . Attributes_manage::CHILD_TITLE;
        $this->mViewFile = Attributes_manage::CHILD_VIEW;

        $view_array['sid'] = quick_modal_view_dto('', '', 'hidden');
        $view_array['parent_sid'] = quick_modal_view_dto('', '', 'hidden', $parent_sid);
        $view_array['cname'] = quick_modal_view_dto('名稱', '', '', '', 'col-md-6');
        $view_array['tag'] = quick_modal_view_dto('標籤', '');
        $view_array['sequence'] = quick_modal_view_dto('排序', '', '', 0);

        $madal_option = quick_modal_view_option(
            $parent_dto->cname . '屬性維護',
            'attributes_model',
            base_url('custom/attributes_manage/merge_attributes'),
            '',
            "/ajax/ajax_attributes/show_info"
        );

        $this->mViewData['admin_madal'] = create_admin_modal($madal_option, $view_array);
        $this->mViewData['child_attributes_array'] = $this->attributes_model->find_attributes_by_paretn_sid($parent_sid);
    }

    /**
     * <p>新增/修改</p>
     */
    public function merge_attributes()
    {
        $data_dto = $this->attributes_model->params_to_dto($this->input);


        $error_message = $this->validation_parent_message($data_dto);

        $url = $this->get_back_url($data_dto);

        //---顯示錯誤訊息
        if (count($error_message) != 0) {

            set_warn_alert($error_message);
            redirect($url . page());
            return;
        }

        if (empty($data_dto->sid)) {
            //--新增   
            $row = $this->attributes_model->add_dto($data_dto);

            if (empty($row)) {

                set_warn_alert('新增失敗!!');
            } else {
                set_success_alert('新增成功!!');
            }
        } else {
            //----修改

            $row = $this->attributes_model->modify_dto($data_dto);

            if (empty($row)) {

                set_warn_alert('修改失敗!!');
            } else {
                set_success_alert('修改成功!!');
            }
        }


        redirect($url . page());
    }

    /**
     * <p>刪除資料</p>
     */
    public function del_attributes()
    {

        $sid = input_post('sid');
        $data_dto = $this->attributes_model->find_by_sid($sid);

        $affected_rows = $this->attributes_model->delete($sid);
        if ($affected_rows == 1) {
            set_success_alert('刪除成功!!');
        } else {
            set_warn_alert('刪除失敗!!');
        }


        $url = $this->get_back_url($data_dto);

        redirect($url);
    }

    private function get_back_url($dto)
    {

        if (empty($dto->parent_sid)) {

            $url = "custom/attributes_manage";
        } else {
            $url = "custom/attributes_manage/child_manage/" . $dto->parent_sid;
        }

        return $url;
    }

    private function validation_parent_message($dto)
    {
        $error_message = [];

        if (empty($dto->cname)) {

            $error_message[] = "請輸入名稱！";
        }

        if (empty($dto->tag)) {

            $error_message[] = "請輸入標籤名稱！";
        } else {

            if ($dto->parent_sid == 0) {
                $check_dto = $this->attributes_model->find_dto_by_key_where(array("tag" => $dto->tag));
            } else {
                $parent_dto = $this->attributes_model->find_by_sid($dto->parent_sid);
                $check_dto = $this->attributes_model->find_child_tag_dto($parent_dto->tag, $dto->tag);

            }

            if ($check_dto && $check_dto->sid != $dto->sid) {
                $error_message[] = "標籤名稱重複！";
            }
        }

        return $error_message;
    }
}
