<?php

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

/**
 * 
 * @property Transaction_log_model $transaction_log_model
 * @property Product_model $product_model
 * @property Product_attr_model $product_attr_model
 * @property Member_model $member_model
 * @property Member_transaction_log_model $member_transaction_log_model
 * @property Store_transaction_log_model $store_transaction_log_model
 */
class Transaction extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("custom/Transaction_log_model", "transaction_log_model");
        $this->load->model("custom/Product_attr_model", "product_attr_model");
        $this->load->model("custom/Product_model", "product_model");
        $this->load->model("custom/Member_model", "member_model");
        $this->load->model("custom/Member_transaction_log_model", "member_transaction_log_model");
        $this->load->model("custom/Store_transaction_log_model", "store_transaction_log_model");
    }

    /**
     * 搜尋會員交易排行
     *
     * @return void
     */
    public function find_transaction_top_get()
    {

        $start_time = $this->get('start_time');
        $end_time = $this->get('end_time');
        $limit_num = $this->get('limit_num');

        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        if (empty($limit_num) || !is_numeric($limit_num) || (is_numeric($limit_num) && $limit_num < 0)) {
            $this->setResult_message("limit_num請輸入大於0的整數");
        }

        if ($start_time == '' && $end_time == '') {
            $today = date("Y-m-d", now());
            $start_time = strtotime($today . '00:00');
            $end_time = strtotime($today . '23:59');
        }

        $this->check_time($start_time, $end_time);

        if (count($this->result_message) == 0) {

            $http_code = REST_Controller::HTTP_OK;
            $data_array = $this->transaction_log_model->find_member_transaction_top($start_time, $end_time, $limit_num);

            $this->setResult_data($data_array);
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 取得交易總額
     *
     * @return void
     */
    public function get_total_get()
    {

        $start_time = $this->get('start_time');
        $end_time = $this->get('end_time');

        if ($start_time == '' && $end_time == '') {
            $today = date("Y-m-d", now());
            $start_time = strtotime($today . '00:00');
            $end_time = strtotime($today . '23:59');
        }

        $this->check_time($start_time, $end_time);

        if (count($this->result_message) == 0) {
            $http_code = REST_Controller::HTTP_OK;
            $data_dto = $this->transaction_log_model->find_transaction_total($start_time, $end_time);

            $this->setResult_data($data_dto);
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 新增交易紀錄
     *
     * @return void
     */
    public function order_post()
    {

        $produat_attr_sid = $this->post('produat_attr_sid');
        $member_sid = $this->post('member_sid');

        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        $product_attr_dto = $this->product_attr_model->find_product_full_info_by_sid($produat_attr_sid);
        $member_dto = $this->member_model->find_by_sid($member_sid);

        if (!$product_attr_dto) {
            $this->setResult_message("查無商品資料");
        } else {
            
            if (!$member_dto) {
                $this->setResult_message("查無會員資料");
            } else {

                $now_cash = $this->member_transaction_log_model->find_memeber_now_cash($member_sid);

                if (($now_cash - $product_attr_dto->price) < 0) {
                    $this->setResult_message("會員餘額不足，無法交易");
                }
            }
        }

        if (count($this->result_message) == 0) {

            $this->add_order_data($member_sid, $produat_attr_sid);
            $http_code = REST_Controller::HTTP_OK;
            $this->setResult_message("交易成功");
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 寫入交易紀錄
     *
     * @param string $member_sid
     * @param string $produat_attr_sid
     * @return boolean
     */
    private function add_order_data($member_sid = "", $produat_attr_sid = "")
    {

        $product_attr_dto = $this->product_attr_model->find_product_full_info_by_sid($produat_attr_sid);
        $member_dto = $this->member_model->find_by_sid($member_sid);

        $tranaction_history_dto = new Transaction_log_dto();
        $tranaction_history_dto->memberSid = $member_sid;
        $tranaction_history_dto->memberName = $member_dto->name;
        $tranaction_history_dto->productSid = $product_attr_dto->productSid;
        $tranaction_history_dto->productAttrSid = $product_attr_dto->productAttrSid;
        $tranaction_history_dto->colorTag = $product_attr_dto->colorTag;
        $tranaction_history_dto->typeTag = $product_attr_dto->typeTag;
        $tranaction_history_dto->storeSid = $product_attr_dto->storeSid;
        $tranaction_history_dto->pharmacyName = $product_attr_dto->pharmacyName;
        $tranaction_history_dto->maskName = $product_attr_dto->maskName;
        $tranaction_history_dto->transactionAmount = $product_attr_dto->price;
        $tranaction_history_dto->transactionDate = time();

        $member_transaction_log_dto = new Member_transaction_log_dto();
        $member_transaction_log_dto->memberSid = $member_sid;
        $member_transaction_log_dto->transactionAmount = -1 * $product_attr_dto->price;

        $store_transaction_log_dto = new Store_transaction_log_dto();
        $store_transaction_log_dto->storeSid = $product_attr_dto->storeSid;
        $store_transaction_log_dto->transactionAmount = $product_attr_dto->price;

        $this->db->trans_start();

        $this->transaction_log_model->add_dto($tranaction_history_dto);
        $this->member_transaction_log_model->add_dto($member_transaction_log_dto);
        $this->store_transaction_log_model->add_dto($store_transaction_log_dto);

        $this->db->trans_complete();
    }

    /**
     * 確認輸入時間格式
     *
     * @param string $start_time
     * @param string $end_time
     * @return void
     */
    private function check_time($start_time = "", $end_time = "")
    {

        if ($start_time != '' && $end_time == '') {
            $this->setResult_message("請輸入結束時間");
        }

        if ($start_time == '' && $end_time != '') {
            $this->setResult_message("請輸入開始時間");
        }

        if (count($this->result_message) == 0) {

            if (!is_numeric($start_time)) {
                $this->setResult_message("開始時間格式錯誤");
            }

            if (!is_numeric($end_time)) {
                $this->setResult_message("結束時間格式錯誤");
            }

            if (count($this->result_message) == 0) {

                if ($start_time > $end_time) {

                    $this->setResult_message("開始時間大於結束時間");
                }
            }
        }
    }
}
