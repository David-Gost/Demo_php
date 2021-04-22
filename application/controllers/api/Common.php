<?php

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

/**
 * 
 * @property Attributes_model $attributes_model
 * 
 */
class Common extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("custom/Attributes_model", "attributes_model");
    }

    /**
     * 新增顏色標籤
     *
     * @return void
     */
    public function color_tag_post()
    {

        $tag_name = trim($this->post("tag_name"));
        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        if ($tag_name == '') {
            $this->setResult_message("tag_name請勿空白");
        }

        if (count($this->result_message) == 0) {

            $check_exit = $this->attributes_model->check_child_tag_exit('color', $tag_name);

            if ($check_exit) {
                $this->setResult_message("標籤名稱:" . $tag_name . "已存在，請嘗試其他名稱");
            }

            if (count($this->result_message) == 0) {

                $http_code = REST_Controller::HTTP_OK;
                $this->setResult_message("新增成功");
                $this->add_tag_data('color', $tag_name);
            }
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 修改顏色標籤
     *
     * @return void
     */
    public function color_tag_patch()
    {

        $old_tag_name = trim($this->patch("tag_name"));
        $modify_tag_name = trim($this->patch("modify_tag_name"));
        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        if ($old_tag_name == '') {
            $this->setResult_message("tag_name請勿空白");
        }

        if ($modify_tag_name == '') {
            $this->setResult_message("modify_tag_name請勿空白");
        }

        if (count($this->result_message) == 0) {

            $check_exit = $this->attributes_model->check_child_tag_exit('color', $old_tag_name);

            if (!$check_exit) {
                $this->setResult_message("標籤名稱:" . $old_tag_name . "不存在，請嘗試其他名稱");
            } else {

                $old_attr_dto = $this->attributes_model->find_child_tag_dto('color', $old_tag_name);
                $modify_attr_dto = $this->attributes_model->find_child_tag_dto('color', $modify_tag_name);

                if ($modify_attr_dto) {

                    if ($modify_attr_dto->sid != $old_attr_dto->sid) {
                        $this->setResult_message("標籤名稱:" . $modify_tag_name . "已存在，請嘗試其他名稱");
                    }
                }
            }

            if (count($this->result_message) == 0) {

                $http_code = REST_Controller::HTTP_OK;
                $this->setResult_message("修改成功");
                $this->modify_tag_data('color',$old_tag_name, $modify_tag_name);
            }
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 取得顏色標籤
     *
     * @return void
     */
    public function color_tag_get()
    {

        $http_code = REST_Controller::HTTP_OK;

        $data_array = $this->attributes_model->find_attributes_by_paretn_tag('color');
        $this->setResult_data($data_array);

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 新增產品規格標籤
     *
     * @return void
     */
    public function product_style_tag_post()
    {

        $tag_name = trim($this->post("tag_name"));
        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        if ($tag_name == '') {
            $this->setResult_message("tag_name請勿空白");
        }

        $check_exit = $this->attributes_model->check_child_tag_exit('product_style', $tag_name);

        if ($check_exit) {
            $this->setResult_message("標籤名稱:" . $tag_name . "已存在，請嘗試其他名稱");
        }

        if (count($this->result_message) == 0) {

            $http_code = REST_Controller::HTTP_OK;
            $this->setResult_message("新增成功");
            $this->add_tag_data('product_style', $tag_name);
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 修改產品規格
     *
     * @return void
     */
    public function product_style_tag_patch()
    {

        $old_tag_name = trim($this->patch("tag_name"));
        $modify_tag_name = trim($this->patch("modify_tag_name"));
        $http_code = REST_Controller::HTTP_BAD_REQUEST;

        if ($old_tag_name == '') {
            $this->setResult_message("tag_name請勿空白");
        }

        if ($modify_tag_name == '') {
            $this->setResult_message("modify_tag_name請勿空白");
        }

        if (count($this->result_message) == 0) {

            $check_exit = $this->attributes_model->check_child_tag_exit('product_style', $old_tag_name);

            if (!$check_exit) {
                $this->setResult_message("標籤名稱:" . $old_tag_name . "不存在，請嘗試其他名稱");
            } else {

                $old_attr_dto = $this->attributes_model->find_child_tag_dto('product_style', $old_tag_name);
                $modify_attr_dto = $this->attributes_model->find_child_tag_dto('product_style', $modify_tag_name);

                if ($modify_attr_dto) {

                    if ($modify_attr_dto->sid != $old_attr_dto->sid) {
                        $this->setResult_message("標籤名稱:" . $modify_tag_name . "已存在，請嘗試其他名稱");
                    }
                }
            }

            if (count($this->result_message) == 0) {

                $http_code = REST_Controller::HTTP_OK;
                $this->setResult_message("修改成功");
                $this->modify_tag_data('product_style',$old_tag_name, $modify_tag_name);
            }
        }

        $this->response($this->getResult_data(), $http_code);
    }

    /**
     * 取得產品規格標籤
     *
     * @return void
     */
    public function product_style_tag_get()
    {

        $http_code = REST_Controller::HTTP_OK;

        $data_array = $this->attributes_model->find_attributes_by_paretn_tag('product_style');
        $this->setResult_data($data_array);

        $this->response($this->getResult_data(), $http_code);
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

    /**
     * 修改標籤
     *
     * @param string $parent_tag
     * @param string $old_tag_name
     * @param string $child_tag
     * @return void
     */
    private function modify_tag_data($parent_tag = "",$old_tag_name, $child_tag = "")
    {

        $attr_dto = $this->attributes_model->find_child_tag_dto($parent_tag, $old_tag_name);

        if ($attr_dto) {

            $attr_dto->updatedate = time();
            $attr_dto->tag = $child_tag;

            $this->attributes_model->modify_dto($attr_dto);
        }
    }
}
