<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Product_model $product_model
 * @property Product_attr_model $product_attr_model
 */
class Ajax_product extends AJAX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model("custom/Product_model", "product_model");
        $this->load->model("custom/Product_attr_model", "product_attr_model");
    }

    /**
     * 新增/修改資料
     *
     * @return void
     */
    public function merge_info()
    {

        $store_sid = input_post("store_sid");
        $sid = input_post("sid");
        $name = input_post('name');

        if (empty($sid)) {

            $data_dto = new Product_dto();
            $data_dto->storeSid = $store_sid;
        } else {
            $data_dto = $this->product_model->find_by_sid($sid);

            if ($data_dto) {
                $data_dto->updatedate = time();
            } else {
                $this->setResult_msg("錯誤商品");
                $this->setResult_status(API_RESULT_ERROR);
            }
        }

        $data_dto->name = $name;

        if (empty($sid)) {

            $this->product_model->add_dto($data_dto);
            $this->setResult_msg("新增成功");
            $this->setResult_status(API_RESULT_SUC);
        } else {
            $this->product_model->modify_dto($data_dto);
            $this->setResult_msg("修改成功");
            $this->setResult_status(API_RESULT_SUC);
        }

        echo json_encode($this->get_ajax_result());
        exit;
    }

    /**
     * 刪除商品資料
     *
     * @return void
     */
    public function del_product()
    {

        $sid = input_post("sid");

        $data_dto = $this->product_model->find_by_sid($sid);

        if (!$data_dto) {
            $this->setResult_msg("錯誤商品");
            $this->setResult_status(API_RESULT_ERROR);
        } else {

            $this->product_model->del_product_data($sid);

            $this->setResult_status(API_RESULT_SUC);
            $this->setResult_msg("刪除成功");
        }

        echo json_encode($this->get_ajax_result());
        exit;
    }

    /**
     * 新增/修改商品規格
     *
     * @return void
     */
    public function merge_attr()
    {

        $product_sid = input_post("product_sid");
        $color_tag = input_post("color_tag");
        $type_tag = input_post("type_tag");
        $price = input_post('price');
        $sid = input_post("sid");

        if (empty($sid)) {

            $data_dto = new Product_attr_dto();
            $data_dto->productSid = $product_sid;
        } else {
            $data_dto = $this->product_attr_model->find_by_sid($sid);

            if ($data_dto) {
                $data_dto->updatedate = time();
            } else {
                $this->setResult_msg("錯誤商品");
                $this->setResult_status(API_RESULT_ERROR);
            }
        }

        $data_dto->colorTag = $color_tag;
        $data_dto->typeTag = $type_tag;
        $data_dto->price = $price;

        if (empty($sid)) {

            $this->product_attr_model->add_dto($data_dto);
            $this->setResult_msg("新增成功");
            $this->setResult_status(API_RESULT_SUC);
        } else {
            $this->product_attr_model->modify_dto($data_dto);
            $this->setResult_msg("修改成功");
            $this->setResult_status(API_RESULT_SUC);
        }

        echo json_encode($this->get_ajax_result());
        exit;
    }

     /**
     * 刪除商品規格資料
     *
     * @return void
     */
    public function del_attr()
    {

        $sid = input_post("attr_sid");

        $data_dto = $this->product_attr_model->find_by_sid($sid);

        if (!$data_dto) {
            $this->setResult_msg("錯誤商品規格");
            $this->setResult_status(API_RESULT_ERROR);
        } else {

            $this->product_attr_model->delete($sid);

            $this->setResult_status(API_RESULT_SUC);
            $this->setResult_msg("刪除成功");
        }

        echo json_encode($this->get_ajax_result());
        exit;
    }

    /**
     * 搜尋產品清單
     *
     * @return void
     */
    public function find_attr_array()
    {

        $product_sid = input_post("produt_sid");

        $attr_array = $this->product_attr_model->find_attr_array_by_product_sid($product_sid);

        $re = get_datatable_result(input_post('draw'), count($attr_array), count($attr_array), $attr_array);
        echo json_encode($re);
        exit;
    }
}
