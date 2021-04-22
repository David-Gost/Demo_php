<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Groups_model $groups_model 
 * @property Groups_dto $groups_dto
 * @property Groups_rule_model $groups_rule_model 
 * @property Groups_rule_dto $groups_rule_dto
 * @property Company_model $company_model 
 * @property Company_dto $company_dto
 */
class Groups_manage extends AC_Controller {

    const TITLE = "權限群組";
    const VIEWFILE = "sys/groups_list";
    const ADD_TYPE = "a";
    const MODIFY_TYPE = "m";

    public function __construct() {
        parent::__construct();

        $this->load->model("sys/Groups_model", "groups_model");
        $this->load->library("dto/Groups_dto", "groups_dto");

        $this->load->model("sys/Groups_rule_model", "groups_rule_model");
        $this->load->library("dto/Groups_rule_dto", "groups_rule_dto");

        $this->load->model("sys/Company_model", "company_model");
        $this->load->library("dto/Company_dto", "company_dto");
    }

    public function index() {
        $this->mTitle = Groups_manage::TITLE;
        $this->mViewFile = Groups_manage::VIEWFILE;

        $this->mViewData['groups_list'] = $this->groups_model->find_groups_all();
    }

    public function add_groups() {

        $this->push_breadcrumb(Groups_manage::TITLE, 'sys/groups_manage');
        $this->mTitle = '新增權限群組';
        $this->mViewData['groups_dto'] = $this->groups_dto;
        $this->mViewData['groups_rule'] = '';
        $this->mViewData['active_type'] = Groups_manage::ADD_TYPE;
        $this->mViewFile = 'sys/groups_manage';
    }

    public function modify_groups() {

        $this->push_breadcrumb(Groups_manage::TITLE, 'sys/groups_manage');
        $this->mTitle = '修改權限群組';
        $this->mViewData['active_type'] = Groups_manage::MODIFY_TYPE;
        $this->mViewFile = 'sys/groups_manage';

        $sid = $this->input->post('sid');

        $this->mViewData['groups_dto'] = $this->groups_model->find_groups_by_sid($sid);
        $this->mViewData['groups_rule'] = $this->groups_rule_model->find_groups_rule_by_groups_sid($sid);
        $this->mViewData['company_info'] = $this->company_model->find_company_by_sid(get_seesion_user()['company_sid']);
    }

    public function merge_groups() {

        $groups_dto = $this->groups_prepare_dto();
        $groups_rule = $this->groups_rule_prepare_dto();

        $active_type = $this->input->post('active_type');
        $this->mViewData['active_type'] = $active_type;

        $title = $active_type == 'a' ? '新增權限群組' : '修改權限群組';
        $this->mTitle = $title;
        $this->mViewFile = 'sys/groups_manage';
        $this->mViewData['groups_dto'] = $groups_dto;

        $error_message = $this->groups_validation_message();
        if (!empty($error_message)) {
            set_warn_alert($error_message);
            return;
        }

        if (Groups_manage::ADD_TYPE == $active_type) {
            $rows = $this->groups_model->add_groups($groups_dto);
            $this->groups_rule_dto->setGroups_sid($rows);

            foreach ($groups_rule as $groups_rule_dto) {
                $this->groups_rule_dto->setRule_sid($groups_rule_dto->rule_sid);
                $this->groups_rule_dto->setR($groups_rule_dto->r);
                $this->groups_rule_dto->setC($groups_rule_dto->c);
                $this->groups_rule_dto->setU($groups_rule_dto->u);
                $this->groups_rule_dto->setD($groups_rule_dto->d);

                $this->groups_rule_model->add_groups_rule($this->groups_rule_dto);
            }

            if (empty($rows)) {
                set_warn_alert('新增失敗!!');
                return;
            } else {
                set_success_alert('新增成功!!');
                redirect('sys/groups_manage');
            }
        } else if (Groups_manage::MODIFY_TYPE == $active_type) {
            $rows = $this->groups_model->modify_groups($groups_dto);
            $this->groups_rule_dto->setGroups_sid($groups_dto->sid);
            $this->groups_rule_model->delete_by_groups_sid($this->groups_rule_dto);

            foreach ($groups_rule as $groups_rule_dto) {

                $this->groups_rule_dto->setRule_sid($groups_rule_dto->rule_sid);
                $this->groups_rule_dto->setR($groups_rule_dto->r);
                $this->groups_rule_dto->setC($groups_rule_dto->c);
                $this->groups_rule_dto->setU($groups_rule_dto->u);
                $this->groups_rule_dto->setD($groups_rule_dto->d);

                $this->groups_rule_model->add_groups_rule($this->groups_rule_dto);
            }

            if (empty($rows)) {
                set_warn_alert('修改失敗!!');
                return;
            } else {
                set_success_alert('修改成功!!');
                redirect('sys/groups_manage/index/' . page());
            }
        }
    }

    public function del_groups() {

        $sid = $this->input->post('sid');

        $affected_rows = $this->groups_model->delete_by_sid($sid);

        if ($affected_rows == 1) {
            set_success_alert('刪除成功!!');
        } else {
            set_warn_alert('刪除失敗!!');
        }
        redirect('sys/groups_manage');
    }

    private function groups_validation_message() {

        $error_message = [];
        if (empty($this->input->post('cname'))) {
            $error_message = '群組名稱不可為空白!!';
        }

        return $error_message;
    }

    private function groups_prepare_dto() {

        $this->groups_dto->setSid($this->input->post('sid'));
        $this->groups_dto->setCname($this->input->post('cname'));
        $this->groups_dto->setCompany_sid(get_seesion_user()['company_sid']);
        $this->groups_dto->setRemark($this->input->post('remark'));
        $this->groups_dto->setSequence($this->input->post('sequence'));

        return $this->groups_dto;
    }

    private function groups_rule_prepare_dto() {

        $r_datas = $this->input->post('r_select');
        $c_datas = $this->input->post('c_select');
        $u_datas = $this->input->post('u_select');
        $d_datas = $this->input->post('d_select');

        $modules = get_seesion_user()['module'];
        foreach ($modules as $rule) {
            $groups_rule_dto = new groups_rule_dto();
            $groups_rule_dto->setRule_sid($rule['rule_sid']);

            $check_r = FALSE;
            if (!empty($r_datas)) {
                foreach ($r_datas as $r) {
                    if ($r == $groups_rule_dto->getRule_sid()) {
                        $check_r = TRUE;
                    }
                }
            }

            $check_c = FALSE;
            if (!empty($c_datas)) {
                foreach ($c_datas as $c) {
                    if ($groups_rule_dto->getRule_sid() == $c) {
                        $check_c = TRUE;
                    }
                }
            }

            $check_u = FALSE;
            if (!empty($u_datas)) {
                foreach ($u_datas as $u) {
                    if ($u == $groups_rule_dto->getRule_sid()) {
                        $check_u = TRUE;
                    }
                }
            }

            $check_d = FALSE;
            if (!empty($d_datas)) {
                foreach ($d_datas as $d) {
                    if ($d == $groups_rule_dto->getRule_sid()) {
                        $check_d = TRUE;
                    }
                }
            }

            if ($check_r) {
                $groups_rule_dto->setR(1);
            } else {
                $groups_rule_dto->setR(0);
            }

            if ($check_c) {
                $groups_rule_dto->setC(1);
            } else {
                $groups_rule_dto->setC(0);
            }

            if ($check_u) {
                $groups_rule_dto->setU(1);
            } else {
                $groups_rule_dto->setU(0);
            }

            if ($check_d) {
                $groups_rule_dto->setD(1);
            } else {
                $groups_rule_dto->setD(0);
            }
            $groups_rule[] = $groups_rule_dto;
        }
        return $groups_rule;
    }

}
