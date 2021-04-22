<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Store_model $store_model
 * @property Store_opening_relation_model $store_opening_relation_model
 * @property Store_opening_date_model $store_opening_date_model
 * @property Store_opening_time_model $store_opening_time_model
 */
class Ajax_store_opening extends AJAX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model("custom/Store_model", "store_model");
        $this->load->model("custom/Store_opening_relation_model", "store_opening_relation_model");
        $this->load->model("custom/Store_opening_date_model", "store_opening_date_model");
        $this->load->model("custom/Store_opening_time_model", "store_opening_time_model");
    }

    /**
     * 新增/修改店家營業星期
     *
     * @return void
     */
    public function merge_open_date_info()
    {

        $sid = input_post("sid");
        $store_sid = input_post('store_sid');
        $week_num = input_post('week_num');

        $store_dto = $this->store_model->find_by_sid($store_sid);

        if ($store_dto) {

            if (empty($sid)) {

                $store_open_relation_dto = new Store_opening_relation_dto();
                $store_open_relation_dto->storeSid = $store_sid;
            } else {

                $store_open_relation_dto = $this->store_opening_relation_model->find_by_sid($sid);

                if ($store_open_relation_dto) {
                    $store_open_relation_dto->updatedate = time();
                }
            }

            if ($week_num == "") {
                $week_num_array = array();
            } else {

                if (is_numeric($week_num)) {
                    $week_num_array[] = $week_num;
                } else {
                    $week_num_array = explode(',', $week_num);
                }
            }

            $now_week_array = $this->store_opening_relation_model->find_store_open_time_array_by_sid($sid);

            if (count($week_num_array) > 0 || ((count($week_num_array) == 0 && $sid != ""))) {

                $insert_array = array_diff($week_num_array, array_column($now_week_array, "weekNum"));
                $update_array = array_intersect(array_column($now_week_array, "weekNum", "openingDateSid"), $week_num_array);
                $del_array = array_diff(array_column($now_week_array, "weekNum", "openingDateSid"), $update_array);

                $insert_sid_array = array();
                $this->db->trans_start();
                foreach ($insert_array as $week_num) {

                    $store_open_date_dto = new Store_opening_date_dto();
                    $store_open_date_dto->storeSid = $store_sid;
                    $store_open_date_dto->weekNum = $week_num;
                    $store_open_date_dto->weekName = WEEK_ARRAY[$week_num];

                    $insert_sid_array[] = $this->store_opening_date_model->add_dto($store_open_date_dto);
                }

                foreach ($del_array as $del_sid => $week_num) {
                    $this->store_opening_date_model->delete($del_sid);
                }

                $this->db->trans_complete();

                $date_key_array = array_merge(array_keys($update_array), $insert_sid_array);
                $date_sid_key = implode(',', $date_key_array);
                if (count($date_key_array) == 0) {

                    $this->store_opening_relation_model->del_opening_data($sid);
                    $this->setResult_msg("修改成功");
                    $this->setResult_status(API_RESULT_SUC);
                } else {

                    $store_open_relation_dto->openingDateSid = $date_sid_key;

                    if (empty($sid)) {

                        $this->store_opening_relation_model->add_dto($store_open_relation_dto);
                        $this->setResult_msg("新增成功");
                        $this->setResult_status(API_RESULT_SUC);
                    } else {
                        $this->store_opening_relation_model->modify_dto($store_open_relation_dto);
                        $this->setResult_msg("修改成功");
                        $this->setResult_status(API_RESULT_SUC);
                    }
                }
            } else {

                $this->setResult_msg("無選擇時段");
                $this->setResult_status(API_RESULT_ERROR);
            }
        } else {
            $this->setResult_msg("錯誤店家");
            $this->setResult_status(API_RESULT_ERROR);
        }

        echo json_encode($this->get_ajax_result());
        exit;
    }

    /**
     * 刪除營業星期
     *
     * @return void
     */
    public function del_open_date_info()
    {

        $sid = input_post("sid");
        $store_open_relation_dto = $this->store_opening_relation_model->find_by_sid($sid);

        if ($store_open_relation_dto) {

            $this->store_opening_relation_model->del_opening_data($sid);
            $this->setResult_msg("刪除成功");
            $this->setResult_status(API_RESULT_SUC);
        } else {

            $this->setResult_msg("查無刪除資料");
            $this->setResult_status(API_RESULT_ERROR);
        }

        echo json_encode($this->get_ajax_result());
        exit;
    }

    /**
     * 找營業時間
     *
     * @return void
     */
    public function find_open_time_array()
    {

        $relation_sid = input_post('relation_sid');
        $time_array = $this->store_opening_time_model->find_time_by_relation($relation_sid, false);

        $re = get_datatable_result(input_post('draw'), count($time_array), count($time_array), $time_array);
        echo json_encode($re);
        exit;
    }

    
    /**
     * 新增/修改營業時間
     *
     * @return void
     */
    public function merge_open_time()
    {

        $relation_sid = input_post("relation_sid");
        $start_time = input_post("start_time");
        $end_time = input_post("end_time");
                $sid = input_post("sid");

        if (empty($sid)) {

            $data_dto = new Store_opening_time_dto();
            $data_dto->relationSid = $relation_sid;
        } else {
            $data_dto = $this->store_opening_time_model->find_by_sid($sid);

            if ($data_dto) {
                $data_dto->updatedate = time();
            } else {
                $this->setResult_msg("錯誤資料");
                $this->setResult_status(API_RESULT_ERROR);
            }
        }

        $data_dto->startTime = $start_time;
        $data_dto->endTime = $end_time;

        if (empty($sid)) {

            $this->store_opening_time_model->add_dto($data_dto);
            $this->setResult_msg("新增成功");
            $this->setResult_status(API_RESULT_SUC);
        } else {
            $this->store_opening_time_model->modify_dto($data_dto);
            $this->setResult_msg("修改成功");
            $this->setResult_status(API_RESULT_SUC);
        }

        echo json_encode($this->get_ajax_result());
        exit;
    }

     /**
     * 刪除營業時間
     *
     * @return void
     */
    public function del_open_time()
    {

        $sid = input_post("time_sid");

        $data_dto = $this->store_opening_time_model->find_by_sid($sid);

        if (!$data_dto) {
            $this->setResult_msg("錯誤資料");
            $this->setResult_status(API_RESULT_ERROR);
        } else {

            $this->store_opening_time_model->delete($sid);

            $this->setResult_status(API_RESULT_SUC);
            $this->setResult_msg("刪除成功");
        }

        echo json_encode($this->get_ajax_result());
        exit;
    }
}
