<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  @property Cityarea_model $cityarea_model
 */
class Ajax_cityarea extends AJAX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("sys/Cityarea_model", "cityarea_model");
    }

    public function index() {
        
    }

    public function load_city() {

        $city_list = $this->cityarea_model->find_cityarea_by_parent_id(0);
        $city_arry = [];
        foreach ($city_list as $city_dto) {
            if ($city_dto->sid != 394 && $city_dto->sid != 395) {
                $city_arry[$city_dto->sid] = $city_dto->area_name;
            }
        }
        echo json_encode($city_arry);
        exit();
    }

    public function load_location() {

        $city_list = $this->cityarea_model->find_cityarea_by_parent_id(0);
        $city_arry = [];
        foreach ($city_list as $city_dto) {

            $city_arry[$city_dto->sid] = $city_dto->area_name;
        }
        echo json_encode($city_arry);
        exit();
    }

    public function load_area() {

        $city_sid = $this->input->post('city_sid');
        $area_array = [];
        if ($city_sid != 0) {
            $area_list = $this->cityarea_model->find_cityarea_by_parent_id($city_sid);


            foreach ($area_list as $area_dto) {

                $area_array[$area_dto->sid] = $area_dto->area_name;
            }
        }
        echo json_encode($area_array);
        exit();
    }

    public function load_post_code() {

        $city_sid = $this->input->post('city_sid');
        $area_sid = $this->input->post('area_sid');
        $post_code_dto = $this->cityarea_model->get_post_code($city_sid, $area_sid);
        echo json_encode($post_code_dto->post_code);
        exit();
    }

}
