<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* * *
 * 回傳checkbox 是否被勾選
 */

function show_checked($value) {

    return ($value > 0) ? "checked='checked'" : '';
}

function image_width($value) {

    return(empty($value) || is_null($value)) ? 'width:32px;' : 'width:96%;';
}

function show_status_ok() {

    return base_url(get_upload_base_path() . '_source/ok.png');
}

function show_status_remove() {

    return base_url(get_upload_base_path() . '_source/no.png');
}


/**
 * 設定box打開隱藏
 */
function setting_top_box($value = 'close') {

    $CI = & get_instance();

    switch ($value) {
        case 'open':

            $CI->mViewData['box'] = '';
            $CI->mViewData['tool_btn'] = 'fa-minus';
            break;
        case 'close':

            $CI->mViewData['box'] = 'collapsed-box';
            $CI->mViewData['tool_btn'] = 'fa-plus';
            break;
        default :
            break;
    }
}
