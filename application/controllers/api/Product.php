<?php

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

/**
 * 
 * @property Product_attr_model $product_attr_model
 * @property Product_model $product_model
 * @property Store_model $store_model
 * @property Attributes_model $attributes_model
 * 
 */
class Product extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("custom/Store_model", "store_model");
        $this->load->model("custom/Product_attr_model", "product_attr_model");
        $this->load->model("custom/Product_model", "product_model");
        $this->load->model("custom/Attributes_model", "attributes_model");
    }

    /**
     * 找店家販賣商品
     *
     * @return void
     */
    public function find_product_get()
    {

        $store_sid = $this->get('store_sid');

        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        $store_dto = $this->store_model->find_by_sid($store_sid);

        if (!$store_dto) {
            $this->setResult_message("查無店家，請確認送出參數");
        } else {

            $http_code = REST_Controller::HTTP_OK;
            $product_array = $this->product_attr_model->find_product_by_store_sid($store_sid);
            $this->setResult_data($product_array);
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 以金額篩選商品
     *
     * @return void
     */
    public function filter_product_by_price_get()
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
                $product_array = $this->product_attr_model->filter_product_by_price($min_prcie, $max_prcie);
                $this->setResult_data($product_array);
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
        $product_array = $this->product_attr_model->search_by_key(trim($name));
        $this->setResult_data($product_array);

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 更改產品資料
     *
     * @return void
     */
    public function product_data_patch()
    {
        $produat_attr_sid = $this->patch('produat_attr_sid');
        $produat_name = $this->patch('produat_name');
        $color_tag = $this->patch('color_tag');
        $type_tag = $this->patch('type_tag');
        $price = $this->patch('price');
        $produat_name = trim($produat_name);

        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        $product_attr_dto = $this->product_attr_model->find_by_sid($produat_attr_sid);

        if (!$product_attr_dto) {
            $this->setResult_message("查無商品資料");
        }

        if ($produat_name == "") {
            $this->setResult_message("商品名稱請勿空白");
        }

        $color_tag_exit = $this->attributes_model->check_child_tag_exit('color', $color_tag);
        $product_style_tag_exit = $this->attributes_model->check_child_tag_exit('product_style', $type_tag);

        if (!$color_tag_exit) {
            $this->setResult_message("查無顏色資料");
        }

        if (!$product_style_tag_exit) {
            $this->setResult_message("查無規格資料");
        }

        if (!is_double($price) || $price < 0) {
            $this->setResult_message("price請輸入大於0的浮點數");
        }

        if (count($this->result_message) == 0) {

            $product_dto = $this->product_model->find_by_sid($product_attr_dto->productSid);

            $product_attr_dto->updatedate = time();
            $product_attr_dto->colorTag = $color_tag;
            $product_attr_dto->typeTag = $type_tag;
            $product_attr_dto->price = $price;

            $product_dto->updatedate = time();
            $product_dto->name = $produat_name;

            $this->db->trans_start();

            $this->product_model->modify_dto($product_dto);
            $this->product_attr_model->modify_dto($product_attr_dto);

            $this->db->trans_complete();

            $http_code = REST_Controller::HTTP_OK;
            $this->setResult_message("修改成功");
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 刪除產品資料
     *
     * @return void
     */
    public function product_data_delete()
    {

        $produat_attr_sid = $this->delete('produat_attr_sid');
        
        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        $product_attr_dto = $this->product_attr_model->find_by_sid($produat_attr_sid);

        if (!$product_attr_dto) {
            $this->setResult_message("查無商品資料");
        } else {
            $this->product_attr_model->delete($produat_attr_sid);

            $http_code = REST_Controller::HTTP_OK;
            $this->setResult_message("刪除成功");
        }

        $this->response($this->getResult_data(), $http_code);
    }
}
