<?php
require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

/**
 * 
 * @property Store_opening_relation_model $store_opening_relation_model
 * @property Store_opening_time_model $store_opening_time_model
 * @property Store_model $store_model
 * 
 */
class Store extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("custom/Store_opening_relation_model", "store_opening_relation_model");
        $this->load->model("custom/Store_opening_time_model", "store_opening_time_model");
        $this->load->model("custom/Store_model", "store_model");
        $this->load->helper('data_process');
    }

    /**
     * 找指定日期時間營業店家
     *
     * @return void
     */
    public function search_by_date_get()
    {
        $date = $this->get('date');
        $time = $this->get('time');
        $http_code = REST_Controller::HTTP_OK;

        if (empty($date)) {
            $date = date("Y-m-d");
        }

        if (empty($time)) {
            $time = "";
        }

        $store_data_array = $this->store_opening_relation_model->find_store_open_by_date($date, $time);
        $store_data_array = $this->get_store_open_time($store_data_array);

        $this->setResult_data($store_data_array);

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 找指定日期時間營業店家
     *
     * @return void
     */
    public function search_by_week_get()
    {
        $week_name = $this->get('week_name'); //Sun,Mon,Tue,Wed,Thu,Fri,Sat
        $week_array = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $http_code = REST_Controller::HTTP_OK;

        if (empty($week_name)) {
            $week_num = date("w");
        } else {

            if (in_array($week_name, $week_array)) {
                $week_num = date('w', strtotime($week_name));
            } else {
                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $week_num = "";
                $this->setResult_message("日期格式錯誤");
            }
        }

        if ($week_num != "") {

            $store_data_array = $this->store_opening_relation_model->find_store_open_by_week($week_num);
            $store_data_array = $this->get_store_open_time($store_data_array);

            $this->setResult_data($store_data_array);
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 以販賣商品金額篩選店家
     *
     * @return void
     */
    public function filter_by_price_get()
    {

        $min_prcie = $this->get('min_prcie');
        $max_prcie = $this->get('max_prcie');

        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        if (!is_numeric($min_prcie)) {
            $this->setResult_message("min_prcie非數字，請檢查資料格式");
        }

        if (!is_numeric($max_prcie)) {

            $this->setResult_message("max_prcie非數字，請檢查資料格式");
        }

        if (count($this->result_message) == 0) {

            if ($min_prcie >  $max_prcie) {

                $this->setResult_message("min_prcie大於max_prcie，請檢查送出資料");
            }

            if (count($this->result_message) == 0) {

                $http_code = REST_Controller::HTTP_OK;
                $store_array = $this->store_model->filter_by_price($min_prcie,$max_prcie);
                $store_array = $this->get_store_open_time($store_array);
                $this->setResult_data($store_array);

            }
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 關鍵字搜尋
     *
     * @return void
     */
    public function search_by_name_get()
    {

        $name = $this->get('name');
    
        $http_code = REST_Controller::HTTP_OK;
        $store_array=$this->store_model->search_by_key(trim($name));
        $store_array = $this->get_store_open_time($store_array);
        $this->setResult_data($store_array);

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 產生店家營業時間
     *
     * @param array $store_data_array
     * @return stdClass
     */
    private function get_store_open_time($store_data_array = array())
    {
        //         echo '<pre>',print_r($store_data_array),'</pre>';
        // exit;

        foreach ($store_data_array as &$store_data_dto) {

            $open_time_array = $this->store_opening_relation_model->find_store_open_time_by_store($store_data_dto->storeSid);
            $group_key_array = array('sid');
            $group_data_array = group_data($group_key_array, $open_time_array, "open_date");

            $show_week_name_array = array();
            foreach ($group_data_array as $week_data) {

                $relation_sid = $week_data->sid;
                $week_array = $week_data->open_date;
                $show_week_name = "";

                //---顯示星期
                foreach ($week_array as $i => $open_date_dto) {

                    if ($i == 0) {
                        $show_week_name = $open_date_dto->weekName;
                    } else {

                        if ($week_array[$i]->weekNum - $week_array[$i - 1]->weekNum == 1) {
                            $show_week_name .= " - " . $open_date_dto->weekName;
                        } else {
                            $show_week_name .= ", " . $open_date_dto->weekName;
                        }
                    }
                }
                $open_time_array = $this->store_opening_time_model->find_time_by_relation($relation_sid);
                $open_time_ste = implode(' ,', array_column($open_time_array, "openTime"));
                $show_week_name_array[] = $show_week_name . ' ' . $open_time_ste;
            }

            $store_data_dto->openingHours = implode(" / ", $show_week_name_array);
        }

        return $store_data_array;
    }
}
