<?php

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

/**
 * 
 * @property Member_model $member_model
 * 
 */
class Member extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("custom/Member_model", "member_model");
    }

    /**
     * 查詢會員
     *
     * @return void
     */
    public function find_member_list_get()
    {

        $search_key = trim($this->get("search_key"));

        $data_array = $this->member_model->find_member_list($search_key);

        $this->setResult_data($data_array);

        $this->response($this->getResult_data(), REST_Controller::HTTP_OK);
    }

    /**
     * 新增會員資料
     *
     * @return void
     */
    public function member_data_post()
    {

        $member_name = trim($this->post('member_name'));

        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        if ($member_name == '') {
            $this->setResult_message("member_name請勿空白");
        } else {
            $check_member_data = $this->member_model->find_dto_by_key_where(array('name' => $member_name));

            if ($check_member_data) {

                $this->setResult_message("會員名稱:" . $member_name . "已存在，請嘗試其他名稱");
            }
        }

        if (count($this->result_message) == 0) {

            $this->meramge_member_data($member_name);
            $http_code = REST_Controller::HTTP_OK;
            $this->setResult_message("新增成功");
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 修改會員資料
     *
     * @return void
     */
    public function member_data_patch()
    {
        $member_sid = $this->PATCH('member_sid');
        $member_name = trim($this->PATCH('member_name'));
        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        $member_dto = $this->member_model->find_by_sid($member_sid);

        if(!$member_dto){
            $this->setResult_message("查無會員資料");
        }else{
            if ($member_name == '') {
                $this->setResult_message("member_name請勿空白");
            } else {
                $check_member_data = $this->member_model->find_dto_by_key_where(array('name' => $member_name));
    
                if ($check_member_data&&$check_member_data->sid != $member_sid) {
    
                    $this->setResult_message("會員名稱:" . $member_name . "已存在，請嘗試其他名稱");
                }
            }
    
        }

        if (count($this->result_message) == 0) {

            $this->meramge_member_data($member_name,$member_sid);
            $http_code = REST_Controller::HTTP_OK;
            $this->setResult_message("修改成功");
        }

        $this->response($this->getResult_data(), $http_code);

    }

    /**
     * 新增/修改會員
     *
     * @param string $member_name
     * @param string $sid
     * @return void
     */
    private function meramge_member_data($member_name = "", $sid = "")
    {
        if ($sid == "") {
            $member_dto = new Member_dto();
        } else {

            $member_dto = $this->member_model->find_by_sid($sid);
            $member_dto->updatedate = time();
        }

        $member_dto->name = $member_name;

        if ($sid == "") {
            $this->member_model->add_dto($member_dto);
        } else {
            $this->member_model->modify_dto($member_dto);
        }
    }
}
