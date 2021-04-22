<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//記錄當下URL和POST的內容，可以作為存檔點使用
function save_point($point_name = 'save_point', $post = false, $other_data = null, $XSS = true)
{
    $CI = get_instance();
    $post_data = [];
    if ($post) {
        $post_data = $CI->input->post(NULL, $XSS);
    }
    $point_data = new stdClass();
    $point_data->uri_string = uri_string();
    $point_data->post = $post_data;
    $point_data->other_data = $other_data;
    $CI->session->set_userdata($point_name, $point_data);
    return $CI->session->userdata($point_name);
}

//取回save_point的存檔點
function get_point($point_name = 'save_point')
{
    $CI = get_instance();
    return $CI->session->userdata($point_name);
}