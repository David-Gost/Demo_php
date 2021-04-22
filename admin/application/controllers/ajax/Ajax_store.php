<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Store_model $store_model
 */
class Ajax_store extends AJAX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model("custom/Store_model", "store_model");
    }

    /**
     * 新增/修改店家資料
     *
     * @return void
     */
    public function merge_store_info()
    {

        $sid = input_post("sid");
        $store_name = input_post('store_name');

        if (empty($sid)) {

            $store_dto =new Store_dto();
        } else {
            $store_dto = $this->store_model->find_by_sid($sid);

            if($store_dto){
                $store_dto->updatedate=time();
            }else{
                $this->setResult_msg("錯誤店家");
                $this->setResult_status(API_RESULT_ERROR);
            }
            
        }

        $store_dto->name=$store_name;

        if (empty($sid)) {

            $this->store_model->add_dto($store_dto);
            $this->setResult_msg("新增成功");
            $this->setResult_status(API_RESULT_SUC);

        } else {
            $this->store_model->modify_dto($store_dto);
            $this->setResult_msg("修改成功");
            $this->setResult_status(API_RESULT_SUC);
        }

        echo json_encode($this->get_ajax_result());
        exit;
    }

    /**
     * 刪除店家資料
     *
     * @return void
     */
    public function del_store(){

        $sid = input_post("sid");

        $store_dto = $this->store_model->find_by_sid($sid);

        if(!$store_dto){
            $this->setResult_msg("錯誤店家");
            $this->setResult_status(API_RESULT_ERROR);
        }else{

            $this->store_model->del_store_data($sid);

            $this->setResult_status(API_RESULT_SUC);
            $this->setResult_msg("刪除成功");

        }
        
        echo json_encode($this->get_ajax_result());
        exit;
    }

}
