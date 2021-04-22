<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function captcha_setting($img_id, $word_length = '', $font_size = '') {

    $setting = array(
        'img_path' => get_upload_base_path() . 'captcha/',
        'img_url' => base_url() . get_upload_base_path() . 'captcha/',
        'img_id' => $img_id,
        'font_path' => ASSETS_BACKEND . 'fonts/382-CAI978.ttf',
        'img_width' => '250',
        'img_height' => 35,
        'expiration' => 1800,
        'word_length' => $word_length,
        'font_size' => $font_size,
        'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        // White background and border, black text and red grid
        'colors' => array(
            'background' => array(255, 255, 255),
            'border' => array(255, 255, 255),
            'text' => array(0, 0, 0),
            'grid' => array(255, 40, 40)
        )
    );

    return $setting;
}

//設定驗證碼session
function set_captcha($tag = "",$setting = NULL) {
    $CI = & get_instance();
    if (is_null($setting)) {
        $setting = captcha_setting('captcha_id', 4, 20);
    }
    $cap = create_captcha($setting);
    $CI->session->set_userdata('captchaWord'.$tag, $cap['word']);
    return $cap;
}

function get_captcha_word($tag = "")
{
    $CI = & get_instance();
    return $CI->session->userdata('captchaWord'.$tag);
}

function check_captcha($userCaptcha,$tag = "")
{
    $CI = & get_instance();
    if ($CI->session->has_userdata('captchaWord'.$tag)) {
        //判斷captch
        $word = $CI->session->userdata('captchaWord'.$tag);
        if (strcmp(strtoupper($userCaptcha), strtoupper($word)) == 0) {
            //驗證通過清掉userdata session
            clean_captcha($tag);
            return true;
        }
    }
    return false;
}

//清除驗證碼session
function clean_captcha($tag = "") {
    $CI = & get_instance();
    $CI->session->unset_userdata('captchaWord'.$tag);
}
