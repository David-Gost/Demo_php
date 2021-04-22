<?php

function get_all_address($city_sid, $area_sid, $address, $post_code) {
    $CI = get_instance();
    $CI->load->model("sys/Cityarea_model", "cityarea_model");

    $all_address = '';

    $city_dto = $CI->cityarea_model->find_cityarea_by_sid($city_sid);
    $area_dto = $CI->cityarea_model->find_cityarea_by_sid($area_sid);

    $city = $city_dto ? $city_dto->area_name : '';
    $area = $area_dto ? $area_dto->area_name : '';

    $all_address = $post_code . $city . $area . $address;

    return $all_address;
}

/**
 * 
 * @return \stdClass
 * <p>city_cname : 城市名 area_name：地區名 address：地址 post_code：郵遞區號</p>
 */
function get_address_dto($city_sid, $area_sid, $address, $post_code) {

    $CI = get_instance();
    $CI->load->model("sys/Cityarea_model", "cityarea_model");

    $city_dto = $CI->cityarea_model->find_cityarea_by_sid($city_sid);
    $area_dto = $CI->cityarea_model->find_cityarea_by_sid($area_sid);


    $address_dto = new stdClass();
    $address_dto->city_cname = $city_dto->area_name;
    $address_dto->area_name = $area_dto->area_name;
    $address_dto->address = $address;
    $address_dto->post_code = $post_code;

    return $address_dto;
}

/**
 * 
 * @param String $address
 * @return \address_dto
 */
function address_str_to_array($address) {

    $address_key = array("市", "縣", "區");
    $address_replace_key = array("市" . chr(13), "縣" . chr(13), "區" . chr(13));
    $address_array = array_filter(explode(chr(13), str_replace($address_key, $address_replace_key, $address)));

    if (empty($address_array)) {

        return new address_dto();
    }

    $CI = get_instance();
    $CI->load->model("sys/Cityarea_model", "cityarea_model");

    $city_dto = $CI->cityarea_model->find_cityarea_by_name($address_array[0]);
    $area_dto = $CI->cityarea_model->find_cityarea_by_name($address_array[1]);



    //----符合鄉鎮
    if (!$area_dto) {
        $address_key = array("鄉");
        $address_replace_key = array("鄉" . chr(13));
        $area_array = array_filter(explode(chr(13), str_replace($address_key, $address_replace_key, $address_array[1])));

        if (count($area_array) == 1) {

            $address_key = array("鎮");
            $address_replace_key = array("鎮" . chr(13));
            $area_array = array_filter(explode(chr(13), str_replace($address_key, $address_replace_key, $address_array[1])));


            if (count($area_array) == 1) {
                $area_dto = null;
            } else {
                $area_dto = $CI->cityarea_model->find_cityarea_by_name($area_array[0]);

                $address_array[] = $area_array[1];
            }
        } else {
            $area_dto = $CI->cityarea_model->find_cityarea_by_name($area_array[0]);
            $address_array[] = $area_array[1];
        }
    }

    $address_dto = new address_dto();
    $address_dto->setCity_sid($city_dto ? $city_dto->sid : '0');
    $address_dto->setArea_sid($area_dto ? $area_dto->sid : '0');
    $address_dto->setPost_code($area_dto ? $area_dto->post_code : '');
    $address_dto->setAddress($area_dto ? $address_array[2] : $address_array[1]);

    return $address_dto;
}

class address_dto {

    public $city_sid;
    public $area_sid;
    public $address;
    public $post_code;

    function getCity_sid() {
        return $this->city_sid;
    }

    function getArea_sid() {
        return $this->area_sid;
    }

    function getAddress() {
        return $this->address;
    }

    function getPost_code() {
        return $this->post_code;
    }

    function setCity_sid($city_sid) {
        $this->city_sid = $city_sid;
    }

    function setArea_sid($area_sid) {
        $this->area_sid = $area_sid;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function setPost_code($post_code) {
        $this->post_code = $post_code;
    }

}
