<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 月下拉選單內容
 * @param type $lan
 * @param type $first_option_value
 * @param type $first_option_text
 * @return type
 */
function month_option($lan = 'zh_TW', $first_option_value = '', $first_option_text = '') {

    if (!empty($first_option_text) && !empty($first_option_text)) {
        $month_array = [
            $first_option_value => $first_option_text
        ];
    } else {
        $month_array = [];
    }
    for ($i = 1; $i <= 12; $i++) {
        $month_array[$i] = _month_array()[$lan][$i - 1];
    }
    return $month_array;
}

/**
 * 年下拉選單內容
 * @param type $first_option_value
 * @param type $first_option_text
 * @return string
 */
function year_option($first_option_value = '', $first_option_text = '', $additional_data = true) {

    if (!empty($first_option_text) && !empty($first_option_text)) {
        $year_array = [
            $first_option_value => $first_option_text
        ];
    } else {
        $year_array = [];
    }
    for ($i = (date('Y') - 80); $i <= (date('Y') + 0); $i++) {

        $year_array[$i] = ($additional_data === true) ? $i . " (" . ($i - 1911) . "年次)" : $i;
    }

    return $year_array;
}

/**
 * 日期下拉選單
 * @param type $first_option_value
 * @param type $first_option_text
 * @return type
 */
function day_option($first_option_value = '', $first_option_text = '') {

    if (!empty($first_option_text) && !empty($first_option_text)) {
        $day_array = [
            $first_option_value => $first_option_text
        ];
    } else {
        $day_array = [];
    }
    for ($i = 1; $i <= 31; $i++) {

        $day_array[str_pad($i, 2, '0', STR_PAD_LEFT)] = str_pad($i, 2, '0', STR_PAD_LEFT);
    }

    return $day_array;
}

function _month_array() {

    $variables_month = array(
        "zh_TW" => array(
            "一月 January",
            "二月 February",
            "三月 March",
            "四月 April",
            "五月 May",
            "六月 June",
            "七月 July",
            "八月 August",
            "九月 September",
            "十月 October",
            "十一月 November",
            "十二月 December")
            //"zh-en"=>array("English","Traditional Chinese","English")
    );

    return $variables_month;
}

/**
 * 小時下拉選單
 * @param type $first_option_value
 * @param type $first_option_text
 * @return type
 */
function hour_option($first_option_value = '', $first_option_text = '') {

    if (!empty($first_option_text) && !empty($first_option_text)) {
        $hour_array = [
            $first_option_value => $first_option_text
        ];
    } else {
        $hour_array = [];
    }
    for ($i = 1; $i <= 24; $i++) {

        $hour_array[str_pad($i, 2, '0', STR_PAD_LEFT)] = str_pad($i, 2, '0', STR_PAD_LEFT);
    }

    return $hour_array;
}

function min_option($first_option_value = '', $first_option_text = '') {

    if (!empty($first_option_text) && !empty($first_option_text)) {
        $min_array = [
            $first_option_value => $first_option_text
        ];
    } else {
        $min_array = [];
    }

    $min_array = ['00' => '00', '30' => '30'];

    return $min_array;
}

function all_reserve_hour($first_option_value = '', $first_option_text = '') {

    if (!empty($first_option_text) && !empty($first_option_text)) {
        $hour_array = [
            $first_option_value => $first_option_text
        ];
    } else {
        $hour_array = [];
    }

    for ($i = 8; $i <= 20; $i++) {

        $hour_array[str_pad($i, 2, '0', STR_PAD_LEFT)] = str_pad($i, 2, '0', STR_PAD_LEFT);
    }
    return $hour_array;
}

function all_reserve_min($first_option_value = '', $first_option_text = '') {

    if (!empty($first_option_text) && !empty($first_option_text)) {
        $min_array = [
            $first_option_value => $first_option_text
        ];
    } else {
        $min_array = [];
    }

    $min_array = [
        '' => '--分--',
        '00' => '00',
        '05' => '05',
        '10' => '10',
        '15' => '15',
        '20' => '20',
        '25' => '25',
        '30' => '30',
        '35' => '35',
        '40' => '40',
        '45' => '45',
        '50' => '50'
    ];

    return $min_array;
}

function is_view_modify($create) {

    return (date('Y-m-d', now()) > date('Y-m-d', $create));
}

function check_onilne($startdate, $enddate) {

    $online = TRUE;

    if ($startdate != '') {
        if (date('Y-m-d', now()) < $startdate) {
            $online = FALSE;
        }
    }

    if ($enddate != '') {
        if (date('Y-m-d', now()) > $enddate) {
            $online = FALSE;
        }
    }

    return $online;
}
