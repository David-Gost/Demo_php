<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Account_model $account_model
 * @property Account_dto $account_dto
 * @property Groups_model $groups_model
 * @property Company_model $company_model
 * @property Company_job_title_model $company_job_title_model
 */
class Account_manage extends AC_Controller {

    const TITLE = "帳號資料";
    const VIEWFILE = "sys/account_list";
    const ADD_TYPE = "a";
    const MODIFY_TYPE = "m";

    public function __construct() {
        parent::__construct();
        $this->load->model("sys/Account_model", "account_model");
        $this->load->library("dto/Account_dto", "account_dto");

        $this->load->model("sys/Company_model", "company_model");
        $this->load->model("sys/Groups_model", "groups_model");
        $this->load->model('sys/Company_job_title_model', 'company_job_title_model');

        $this->push_breadcrumb(Account_manage::TITLE, 'sys/account_manage');
    }

    public function index() {
        unset($_SESSION['account_manage_searchword']);
        unset($_SESSION['account_manage_groups_sid']);
        $this->account_list();
    }

    public function account_list() {
        $this->mTitle = Account_manage::TITLE;
        $this->mViewFile = Account_manage::VIEWFILE;

        $search_report = new Search_report();
        $data_dto = $search_report->get_search_data($_POST);

        if ($data_dto == null) {
            $searchword = $this->session->userdata('account_manage_searchword');
            $groups_sid = $this->session->userdata('account_manage_groups_sid');
        } else {
            $searchword = $data_dto->searchword;
            $groups_sid = $data_dto->groups_sid;
        }

        //建立篩選器
        $this->create_search_model($searchword, $groups_sid, '/sys/account_manage/account_list');

        //紀錄session
        $this->session->set_userdata('student_manage_groups_sid', $groups_sid);
        $this->session->set_userdata('student_manage_searchword', $searchword);

        //產生分頁
        $total_row = count($this->account_model->find_by_search($searchword, $groups_sid, '', ''));
        $list = $this->account_model->find_by_search($searchword, $groups_sid, page_offset(END_URI_SEGMENT), END_LIMIT);
        get_pagination_data_for_admin(END_URI_SEGMENT, END_LIMIT, $total_row, $list, site_url('/sys/account_manage/account_list'));
    }

    public function add_account() {
        $this->mTitle = '帳號新增';
        $this->mViewData['active_type'] = Account_manage::ADD_TYPE;
        $this->mViewFile = 'sys/account_manage';
        $this->mViewData['tab_select'] = 'default';

        $account_dto = new Account_dto();
        $this->mViewData['account_dto'] = $account_dto;
        $this->dropdown(0, 0, 0);
    }

    public function modify_account() {
        $this->mTitle = '修改帳號';
        $this->mViewData['active_type'] = Account_manage::MODIFY_TYPE;
        $this->mViewFile = 'sys/account_manage';
        $this->mViewData['tab_select'] = 'default';

        $sid = $this->check_sid($this->input->post('sid'));

        $account_dto = $this->account_model->find_by_sid($sid);
        $this->mViewData['account_dto'] = $account_dto;

        $this->dropdown($account_dto->groups_sid, $account_dto->company_sid, $account_dto->company_title_sid);
    }

    public function del_account() {

        $sid = $this->input->post('sid');

        $affected_rows = $this->account_model->del_account($sid);
        if ($affected_rows > 0) {
            set_success_alert('刪除成功!!');
        } else {
            set_warn_alert('刪除失敗!!');
        }
        redirect('sys/account_manage');
    }

    public function merge_account() {

        $account_dto = $this->account_model->params_to_dto($this->input);
        $active_type = $this->input->post('active_type');
        $this->mViewData['active_type'] = $active_type;
        $this->mViewData['account_dto'] = $account_dto;
        $this->mViewData['tab_select'] = 'default';
        $this->mViewFile = 'sys/account_manage';
        $this->dropdown($account_dto->groups_sid, $account_dto->company_sid, $account_dto->company_title_sid);

        $error_message = $this->validation_message($account_dto);
        if (!empty($error_message)) {
            if (Account_manage::ADD_TYPE == $active_type) {
                $this->mTitle = '新增帳號';
            } else {
                $this->mTitle = '修改帳號';
            }
            set_warn_alert($error_message);
            return;
        }

        if (Account_manage::ADD_TYPE == $active_type) {
            $rows = $this->account_model->add_account($account_dto);
            if (empty($rows)) {
                set_warn_alert('新增失敗!!');
                $this->mTitle = '新增帳號';
            } else {
                set_success_alert('新增成功!!');
                save_point('account',false, $rows);
                redirect('sys/account_manage/modify_account');
            }
        } else if (Account_manage::MODIFY_TYPE == $active_type) {

            $rows = $this->account_model->modify_account($account_dto);
            if (empty($rows)) {
                set_warn_alert('修改失敗!!');
                $this->mTitle = '修改帳號';
            } else {
                set_success_alert('修改成功!!');
                save_point('account',false, $account_dto->sid);
                redirect('sys/account_manage/modify_account');
            }
        }
    }

    //-----建立搜尋model
    private function create_search_model($searchword, $groups_sid, $url) {

        $search_view_dto = new Search_view_dto();
        $element_array = [];

        //-----關鍵字
        $input_dto = new Element_dto();
        $input_dto->setLabel("關鍵字");
        $input_dto->setElement_type('input');
        $input_dto->setId('searchword');
        $input_dto->setName('searchword');
        $input_dto->setInput_type("text");
        $input_dto->setValue($searchword);
        $input_dto->setIcon("fa-search");

        $element_array[] = $input_dto;

        //-----等級
        $groups_sid_dto = new Element_dto();

        $groups_sid_dto->setLabel("權限群組");
        $groups_sid_dto->setElement_type("select");
        $groups_sid_dto->setId("groups_sid");
        $groups_sid_dto->setName("groups_sid");
        $groups_sid_dto->setIs_multiple(true);
        $groups_sid_dto->setData_array($this->main_groups_select());
        $groups_sid_dto->setSelect($groups_sid);
        $groups_sid_dto->setSingle_level(true);
        $groups_sid_dto->setDefault_enable(false);

        $element_array[] = $groups_sid_dto;

        $search_view_dto->setView_name("student_search");
        $search_view_dto->setModel_id('model_search_model');
        $search_view_dto->setElement_array($element_array);
        $search_view_dto->setUrl(site_url($url));

        $search_report = new Search_report();

        $search_model = $search_report->create_search_obj($search_view_dto);
        $this->mViewData['search_model'] = $search_model;
    }

    private function validation_message($account_dto) {

        $error_message = [];

        $active_type = $this->input->post('active_type');

        $count = $this->account_model->account_is_exist($account_dto->account);
        if (Account_manage::ADD_TYPE === $active_type && $count > 0) {
            $error_message[] = '帳號已有人使用，不可重複!!';
        }

        if (empty($account_dto->company_sid)) {
            $error_message[] = '所屬公司未設定!!';
        }

        if (empty($account_dto->groups_sid)) {
            $error_message[] = '權限群組未設定!!';
        }

        if (!check_account($account_dto->account)) {
            $error_message[] = '帳號欄位空白或格式錯誤!!';
        }

        if (Account_manage::ADD_TYPE === $active_type) {
            if (!check_password($account_dto->password)) {
                $error_message[] = ' 密碼欄位空白或格式錯誤!!';
            }
        }

        if ($account_dto->password != $account_dto->confirmpassword) {
            $error_message[] = ' 密碼與確認密碼不一樣!!';
        }

        if (empty($account_dto->cname)) {
            $error_message[] = ' 姓名欄位不得空白!!';
        }

        if (!check_tel($account_dto->tel)) {
            $error_message[] = ' 電話欄位格式錯誤!!';
        }

        if (!check_mobile($account_dto->phone)) {
            $error_message [] = ' 手機欄位格式錯誤!!';
        }

        if (!check_email($account_dto->email)) {
            $error_message[] = ' E-Mail格式錯誤!!';
        }

        if (!empty($account_dto->dutydate) && !empty($account_dto->departuredate)) {
            if ($account_dto->dutydate > $account_dto->departuredate) {
                $error_message[] = '就職日不可超過離職日!';
            }
        }

        //上傳圖片
        $upload_pic = upload_cut_pic_easy($account_dto, 'picinfo', 'origin_picinfo', 300, 300);

        if ($upload_pic) {
            $error_message[] = $upload_pic;
        }
        return $error_message;
    }

    private function check_sid($sid) {
        $sid = $sid ?: get_point('account')->other_data;
        if (!$sid) {
            set_warn_alert('查無資料!!');
            redirect('sys/account_manage');
        } else {
            save_point('account',false, $sid);
        }
        return $sid;
    }

    private function dropdown($groups_selected, $company_selected, $company_title_selected) {

        $this->mViewData['main_groups_array'] = $this->main_groups_select();
        $this->mViewData['groups_selected'] = $groups_selected;

        $this->mViewData['main_company_array'] = creat_data_array('公司', $this->company_model->find_all());
        $this->mViewData['company_selected'] = $company_selected;

        $this->mViewData['main_company_title_array'] = $this->company_job_title_model->get_company_job_option('', '-------- 請選擇 --------');
        $this->mViewData['company_title_selected'] = $company_title_selected;
    }

    private function main_groups_select() {

        $groups_main_class_list = $this->groups_model->find_groups_all();
        $main_groups_array = [];
        foreach ($groups_main_class_list as $main_groups_dto) {
            if ($main_groups_dto->sid != 1 || get_seesion_user()['groups_sid'] == 1) {
                $main_groups_array[$main_groups_dto->sid] = $main_groups_dto->cname;
            }
        }

        return $main_groups_array;
    }

}
