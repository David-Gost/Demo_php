<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Store_model $store_model 
 * @property Product_model $product_model 
 * @property Attributes_model $attributes_model 
 * @property Store_opening_date_model $store_opening_date_model 
 * @property Store_opening_relation_model $store_opening_relation_model 
 */
class Store_manage extends AC_Controller
{

    const VIEWFILE = "custom/store_list";
    const SESSION_STORE_SID = "store_info_sid";

    public function __construct()
    {
        parent::__construct();

        $this->load->model("custom/Store_model", "store_model");
        $this->load->model("custom/Product_model", "product_model");
        $this->load->model("custom/Attributes_model", "attributes_model");
        $this->load->model("custom/Store_opening_date_model", "store_opening_date_model");
        $this->load->model("custom/Store_opening_relation_model", "store_opening_relation_model");
    }

    public function index()
    {

        $this->mTitle = get_url_title();
        $this->mViewFile = Store_manage::VIEWFILE;

        del_sesstion_by_key(Store_manage::SESSION_STORE_SID);
    }

    /**
     * 編輯店家商品
     *
     * @return void
     */
    public function product()
    {

        $store_sid = input_post('store_sid');

        $this->set_view_title($store_sid, '-商品清單');

        $color_array = $this->attributes_model->find_attributes_by_paretn_tag("color", false);
        $type_array = $this->attributes_model->find_attributes_by_paretn_tag("product_style", false);

        $color_array = array_column($color_array, "cname", "tag");
        $type_array = array_column($type_array, "cname", "tag");

        $this->mViewData['store_sid'] = $store_sid;
        $this->mViewData['color_array'] = $color_array;
        $this->mViewData['type_array'] = $type_array;

        $this->mViewFile = "custom/store_product_list";
    }

    /**
     * 店家營業時間
     *
     * @return void
     */
    public function opening()
    {

        $store_sid = input_post('store_sid');
        $this->set_view_title($store_sid, '-營業時間');

        $this->mViewData['store_sid'] = $store_sid;
        $this->mViewData['select_array'] = $this->get_can_sel_week_array($store_sid);

        $this->mViewFile = "custom/store_open_list";
    }

    /**
     * <p>店家清單</p>
     */
    public function ajax_store_data()
    {

        $all_post = check_datatable($this->input, 'post');
        $ac_search = get_datatable_search_list($all_post);
        $ac_sort = get_datatable_order_list($all_post);

        $search_key = $ac_search['ac_datatable_search'];

        //---資料
        $data = $this->store_model->find_store_full_info_array($search_key, $all_post['start'], $all_post['length']);
        $all_data = $this->store_model->find_store_full_info_array($search_key, 0, 0);

        $view_data_array = array();
        $recordsTotal = count($view_data_array);

        // echo '<pre>',print_r($all_data),"</pre>";
        // exit;        

        $re = get_datatable_result($all_post['draw'], $recordsTotal, count($all_data), $data);
        echo json_encode($re);
        exit;
    }

    /**
     * <p>商品清單</p>
     */
    public function ajax_product_data()
    {

        $all_post = check_datatable($this->input, 'post');
        $ac_search = get_datatable_search_list($all_post);
        $ac_sort = get_datatable_order_list($all_post);

        $search_key = $ac_search['ac_datatable_search'];

        //---資料
        $data = $this->product_model->find_product_array(get_seesion_by_key(Store_manage::SESSION_STORE_SID), $search_key, $all_post['start'], $all_post['length']);
        $all_data = $this->product_model->find_product_array($search_key, 0, 0);

        $view_data_array = array();
        $recordsTotal = count($view_data_array);

        $re = get_datatable_result($all_post['draw'], $recordsTotal, count($all_data), $data);
        echo json_encode($re);
        exit;
    }

    /**
     * 店家營業時間
     *
     * @return void
     */
    public function ajax_opening_data()
    {

        $all_post = check_datatable($this->input, 'post');


        //---資料
        $data = $this->store_opening_relation_model->find_store_week_open_array(get_seesion_by_key(Store_manage::SESSION_STORE_SID));

        $re = get_datatable_result($all_post['draw'], count($data), count($data), $data);
        echo json_encode($re);
        exit;
    }

    /**
     * 取得店家營業星期
     *
     * @return void
     */
    public function ajax_get_week_num()
    {
        $store_week_array = $this->get_can_sel_week_array(get_seesion_by_key(Store_manage::SESSION_STORE_SID));
        $extra_week_data_array = input_post('extra_week_num') == '' ? array() : explode(',', input_post('extra_week_num'));
        $extra_week_key_array = array();

        foreach (WEEK_ARRAY as $week_num => $value) {

            if (in_array($week_num, $extra_week_data_array)) {
                $extra_week_key_array[$week_num] = $value;
            }
        }

        echo json_encode($store_week_array + $extra_week_key_array);
        exit;
    }

    private function set_view_title($store_sid = "", $title = "")
    {
        $store_dto = $this->store_model->find_by_sid($store_sid);

        if (!$store_dto) {
            echo '<script>alert("店家錯誤!"); javascript:history.go(-1)</script>';
            exit;
        }

        set_session_data(Store_manage::SESSION_STORE_SID, $store_sid);

        $this->mTitle = ($store_dto ? $store_dto->name : "") . $title;
    }

    /**
     * 取得可選星期
     *
     * @param string $store_sid
     * @return array
     */
    private function get_can_sel_week_array($store_sid = "")
    {

        $store_now_week_array = $this->store_opening_date_model->find_store_week_array($store_sid);

        return array_diff(WEEK_ARRAY, $store_now_week_array);
    }
}
