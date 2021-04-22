<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * <p>搜尋頁面於model中的key</p>
 * @return type
 */
function get_module_url() {

    $CI = &get_instance();
    $module_url = $CI->router->directory . $CI->router->class;
    $module_url_action = $CI->router->directory . $CI->router->class . '/' . $CI->router->method;
    $url_string = $CI->uri->uri_string;

    $key = null;

    if (is_array(get_seesion_user()['module'])) {

        $url_array = array_column(get_seesion_user()['module'], 'url');

        if (in_array($module_url_action, $url_array)) {
            $key = array_search($module_url_action, $url_array);
        } else {
            if (in_array($url_string, $url_array)) {
                $key = array_search($url_string, $url_array);
            } else {

                if (in_array($module_url, $url_array)) {
                    $key = array_search($module_url, $url_array);
                }
            }
        }
    }
    return $key;
}

/**
 * <p>取得網址頁面標題</p>
 * @param type $url_count
 * @return string
 */
function get_url_title() {

    $key = get_module_url();

    if (is_numeric($key)) {
        $title = get_seesion_user()['module'][$key]['rule_name'];
    } else {
        $title = "";
    }


    return $title;
}
