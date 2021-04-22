<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Attributes_model $attributes_model
 * @property Product_model $product_model
 * @property Product_attr_model $product_attr_model
 * @property Store_model $store_model
 * @property Store_opening_date_model $store_opening_date_model
 * @property Store_opening_time_model $store_opening_time_model
 * @property Store_transaction_log_model $store_transaction_log_model
 * @property Transaction_log_model $transaction_log_model
 * @property Store_opening_relation_model $store_opening_relation_model
 * @property Member_model $member_model
 * @property Member_transaction_log_model $member_transaction_log_model
 */
class Inport_data extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("custom/Attributes_model", "attributes_model");
        $this->load->model("custom/Product_model", "product_model");
        $this->load->model("custom/Product_attr_model", "product_attr_model");
        $this->load->model("custom/Store_model", "store_model");
        $this->load->model("custom/Store_opening_date_model", "store_opening_date_model");
        $this->load->model("custom/Store_opening_time_model", "store_opening_time_model");
        $this->load->model("custom/Store_transaction_log_model", "store_transaction_log_model");
        $this->load->model("custom/Transaction_log_model", "transaction_log_model");
        $this->load->model("custom/Store_opening_relation_model", "store_opening_relation_model");
        $this->load->model("custom/Member_model", "member_model");
        $this->load->model("custom/Member_transaction_log_model", "member_transaction_log_model");
    }

    public function load_data()
    {

        $store_data_array = $this->get_data_array("https://raw.githubusercontent.com/kdan-mobile-software-ltd/phantom_mask/master/data/pharmacies.json");
        $member_data_array = $this->get_data_array("https://raw.githubusercontent.com/kdan-mobile-software-ltd/phantom_mask/master/data/users.json");
        // echo '<pre>',print_r($member_data_array),'</pre>';
        // exit;
        foreach ($store_data_array as $data_dto) {

            $this->store_data($data_dto);
        }

        foreach ($member_data_array as $data_dto) {

            $this->member_data($data_dto);
        }

        exit;
    }

    /**
     * 會員資料
     *
     * @param [type] $data_dto
     * @return void
     */
    private function member_data($data_dto)
    {

        $this->db->trans_start();
        $member_name = trim($data_dto->name);
        $member_sid = $this->create_member($member_name);

        if ($member_sid != -1) {

            $this->set_transaction_history($member_sid, $member_name, $data_dto->cashBalance, $data_dto->purchaseHistories);
        }

        $this->db->trans_complete();
    }

    /**
     * 匯入會員資料
     *
     * @param string $member_name
     * @return void
     */
    private function create_member($member_name = "")
    {

        if ($member_name != '') {

            $member_dto = new Member_dto();
            $member_dto->name = trim($member_name);

            return $this->member_model->add_dto($member_dto);
        } else {

            return -1;
        }
    }

    /**
     * 匯入交易紀錄
     *
     * @param string $member_sid
     * @param string $member_name
     * @param integer $now_cash
     * @param array $transaction_history
     * @return void
     */
    private function set_transaction_history($member_sid = "", $member_name = "", $now_cash = 0.0, $transaction_history = array())
    {

        if (!is_double($now_cash)) {
            $now_cash = 0.0;
        }

        $transaction_cache = 0.0;

        foreach ($transaction_history as $history_dto) {

            $store_name = trim($history_dto->pharmacyName);

            $product_name_dto = $this->get_product_name($history_dto->maskName);

            $store_dto = $this->store_model->find_dto_by_key_where(array("name" => $store_name));
            $product_dto = $this->product_attr_model->find_mask_dto($store_name, $product_name_dto->name, $product_name_dto->color_tag, $product_name_dto->type_tag);

            $tranaction_history_dto = new Transaction_log_dto();
            $tranaction_history_dto->memberSid = $member_sid;
            $tranaction_history_dto->memberName = $member_name;
            $tranaction_history_dto->productSid = $product_dto != null ? $product_dto->productSid : "";
            $tranaction_history_dto->productAttrSid = $product_dto != null ? $product_dto->productAttrSid : "";
            $tranaction_history_dto->colorTag = $product_name_dto->color_tag;
            $tranaction_history_dto->typeTag = $product_name_dto->type_tag;
            $tranaction_history_dto->storeSid = $store_dto != null ? $store_dto->sid : "";
            $tranaction_history_dto->pharmacyName = $store_name;
            $tranaction_history_dto->maskName = $history_dto->maskName;
            $tranaction_history_dto->transactionAmount = $history_dto->transactionAmount;
            $tranaction_history_dto->transactionDate = strtotime($history_dto->transactionDate);

            $transaction_cache+=$history_dto->transactionAmount;

            $member_transaction_log_dto=new Member_transaction_log_dto();
            $member_transaction_log_dto->memberSid=$member_sid;
            $member_transaction_log_dto->transactionAmount=-1*$history_dto->transactionAmount;

            $this->transaction_log_model->add_dto($tranaction_history_dto);
            $this->member_transaction_log_model->add_dto($member_transaction_log_dto);

        }

        $tranaction_history_dto = new Transaction_log_dto();
        $tranaction_history_dto->memberSid = $member_sid;
        $tranaction_history_dto->memberName = $member_name;
        $tranaction_history_dto->transactionAmount = $transaction_cache+$now_cash;
        $tranaction_history_dto->remark="資料匯入";

        $member_transaction_log_dto=new Member_transaction_log_dto();
        $member_transaction_log_dto->memberSid=$member_sid;
        $member_transaction_log_dto->transactionAmount=$transaction_cache+$now_cash;

        $this->transaction_log_model->add_dto($tranaction_history_dto);
        $this->member_transaction_log_model->add_dto($member_transaction_log_dto);
    }

    /**
     * 店家資料
     *
     * @param [type] $data_dto
     * @return void
     */
    private function store_data($data_dto)
    {
        $this->db->trans_start();
        $store_sid = $this->create_store(trim($data_dto->name));

        if ($store_sid != -1) {

            $this->set_now_cash($store_sid, $data_dto->cashBalance);
            $this->set_store_open_time($store_sid, $data_dto->openingHours);
            $this->set_store_product($store_sid, $data_dto->masks);
        }

        $this->db->trans_complete();
    }

    /**
     * 匯入店家資料
     *
     * @param string $srote_name
     * @return void
     */
    private function create_store($srote_name = "")
    {

        if ($srote_name != '') {

            $store_dto = new Store_dto();
            $store_dto->name = $srote_name;

            return $this->store_model->add_dto($store_dto);
        } else {

            return -1;
        }
    }

    /**
     * 匯入商家持有金錢
     *
     * @param string $store_sid
     * @param float $cash
     * @return void
     */
    private function set_now_cash($store_sid = "", $cash = 0.0)
    {
        if (!is_double($cash)) {
            $cash = 0.0;
        }

        $transaction_log_dto = new Transaction_log_dto();
        $transaction_log_dto->storeSid = $store_sid;
        $transaction_log_dto->transactionAmount = $cash;
        $transaction_log_dto->remark = "資料匯入";

        $store_transaction_log = new Store_transaction_log_dto();
        $store_transaction_log->storeSid = $store_sid;
        $store_transaction_log->transactionAmount = $cash;

        $this->transaction_log_model->add_dto($transaction_log_dto);
        $this->store_transaction_log_model->add_dto($store_transaction_log);
    }

    /**
     * 匯入店家營業時間
     *
     * @param string $store_sid
     * @param string $open_content
     * @return void
     */
    private function set_store_open_time($store_sid = "", $open_content = "")
    {

        $open_content = str_replace(', ', '-', $open_content);
        $open_content = str_replace(' - ', '-', $open_content);
        $open_array = explode('/', $open_content);

        foreach ($open_array as $open_time_data_str) {

            $open_time_data_array = explode(' ', trim($open_time_data_str));
            $week_array = explode('-', $open_time_data_array[0]);
            $time_array = explode('-', $open_time_data_array[1]);

            $week_sid_array = array();
            //----星期
            foreach ($week_array as $week_val) {

                $open_week_dto = new Store_opening_date_dto();
                $open_week_dto->storeSid = $store_sid;
                $open_week_dto->weekName = $week_val;
                $open_week_dto->weekNum = date('w', strtotime($week_val));

                $week_sid_array[] = $this->store_opening_date_model->add_dto($open_week_dto);
            }

            //關聯表
            $open_relation_dto = new Store_opening_relation_dto();
            $open_relation_dto->storeSid = $store_sid;
            $open_relation_dto->openingDateSid = implode(',', $week_sid_array);

            $relation_sid = $this->store_opening_relation_model->add_dto($open_relation_dto);

            //時間

            $time_dto = new Store_opening_time_dto();
            $time_dto->relationSid = $relation_sid;
            $time_dto->startTime = $time_array[0];
            $time_dto->endTime = $time_array[1];

            $this->store_opening_time_model->add_dto($time_dto);

            // echo '<pre>', print_r($time_array), '<pre>';
        }
    }

    /**
     * 匯入店家商品
     *
     * @param string $store_sid
     * @param array $product_array
     * @return void
     */
    private function set_store_product($store_sid = "", $product_array = array())
    {

        foreach ($product_array as $product_data) {

            $product_name_dto = $this->get_product_name($product_data->name);

            $product_dto = new Product_dto();
            $product_dto->storeSid = $store_sid;
            $product_dto->name = $product_name_dto->name;

            $product_sid = $this->product_model->add_dto($product_dto);

            $color_tag = $product_name_dto->color_tag;
            $type_tag = $product_name_dto->type_tag;

            $this->add_tag_data("color", $color_tag);
            $this->add_tag_data("product_style", $type_tag);

            $product_attr_dto = new Product_attr_dto();
            $product_attr_dto->productSid = $product_sid;
            $product_attr_dto->colorTag = $color_tag;
            $product_attr_dto->typeTag = $type_tag;
            $product_attr_dto->price = $product_data->price;

            $this->product_attr_model->add_dto($product_attr_dto);

            // echo '<pre>', print_r($product_name_dto), '<pre>';
        }
        // exit;
    }

    /**
     * 新增標籤
     *
     * @param string $parent_tag
     * @param string $child_tag
     * @return void
     */
    private function add_tag_data($parent_tag = "", $child_tag = "")
    {

        $tag_exit = $this->attributes_model->check_child_tag_exit($parent_tag, $child_tag);

        if (!$tag_exit && $parent_tag != "" && $child_tag != "") {
            $where_array = array("tag" => $parent_tag);
            $parent_tag_dto = $this->attributes_model->find_dto_by_key_where($where_array);

            if ($parent_tag_dto) {

                $attr_dto = new Attributes_dto();
                $attr_dto->parent_sid = $parent_tag_dto->sid;
                $attr_dto->cname = $child_tag;
                $attr_dto->tag = $child_tag;

                $this->attributes_model->add_dto($attr_dto);
            }
        }
    }

    /**
     * 取得商品名稱
     *
     * @param string $product_show_name
     * @return stdClass
     */
    private function get_product_name($product_show_name = "")
    {

        $product_name = str_replace(' (', '-', $product_show_name);
        $product_name = str_replace(')', '', $product_name);
        $product_name_array = explode('-', $product_name);

        $product_name_dto = new stdClass();
        $product_name_dto->name = trim($product_name_array[0]);
        $product_name_dto->color_tag = trim($product_name_array[1]);
        $product_name_dto->type_tag = trim($product_name_array[2]);

        return $product_name_dto;
    }

    /**
     * 取得資料
     *
     * @param string $url
     * @return stdClass
     */
    private function get_data_array($url = "")
    {

        $content = file_get_contents($url);
        $data_array = json_decode($content);

        return $data_array;
    }
}
