<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Attributes_model $attributes_model
 */
class Ajax_common extends AJAX_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model("custom/Attributes_model", "attributes_model");

    }

    public function show_info() {

        $sid = input_post("sid");
        $modal_name = input_post('modal_name');

        if (empty($sid)) {
            $dto_name = $this->$modal_name->dto();
            $data_dto = new $dto_name();
        } else {
            $data_dto = $this->$modal_name->find_by_sid($sid);
        }



        echo json_encode($data_dto->get_self_dto());
        exit;
    }

}
