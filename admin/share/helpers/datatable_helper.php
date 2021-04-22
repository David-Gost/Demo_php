<?php

defined('BASEPATH') or exit('No direct script access allowed');

function get_datatable_result($draw = 0, $recordsTotal = 0, $recordsFiltered = 0, $data = [], $error = '', $DT_RowId = '', $DT_RowClass = '', $DT_RowData = NULL, $DT_RowAttr = NULL)
{
    $re = new stdClass();
    $re->draw = intval($draw);  //預防XSS
    $re->recordsTotal = $recordsTotal;
    $re->recordsFiltered = $recordsFiltered;
    $re->data = $data;
    $re->error = $error;
    $re->DT_RowId = $DT_RowId;
    $re->DT_RowClass = $DT_RowClass;
    $re->DT_RowData = $DT_RowData;
    $re->DT_RowAttr = $DT_RowAttr;
    return $re;
}

function get_datatable_order_list($all_input)
{
    $re = [];
    if (array_key_exists('order', $all_input) && array_key_exists('columns', $all_input)) {
        foreach ($all_input['order'] as $order_idx => $order_obj) {
            if (count($all_input['columns']) >= $order_obj['column']) {
                $search_column = $all_input['columns'][$order_obj['column']];
                if ($search_column['orderable'] == 'true') {
                    if (strlen($search_column['name']) > 0) {
                        $re[$search_column['name']] = $order_obj['dir'];
                    } else {
                        $re[$search_column['data']] = $order_obj['dir'];
                    }
                }
            }
        }
    }
    return $re;
}

function get_datatable_search_list($all_input)
{
    $re = [];
    if (array_key_exists('columns', $all_input)) {
        foreach ($all_input['columns'] as $column_idx => $column_obj) {
            foreach (['name', 'data'] as $key => $check_tag) {
                if (array_key_exists($check_tag, $column_obj) && strlen($column_obj[$check_tag]) > 0) {
                    if ($column_obj['searchable'] == 'true') {
                        $re[$column_obj[$check_tag]] = $column_obj['search']['value'];
                        break;
                    }
                }
            }
        }
    }
    if (array_key_exists('search', $all_input)) {
        $re['ac_datatable_search'] = $all_input['search']['value'];
    }
    return $re;
}

function datatable_search_where($ac_search, $search_white_list, $dynamic_model)
{
    if (array_key_exists('ac_datatable_search', $ac_search) && !empty($ac_search['ac_datatable_search'])) {
        $tmp_like_list = [];
        foreach ($search_white_list as $search_key => $search_type) {
            $tmp_key = (is_numeric($search_key) ? $search_type : $search_key);
            $tmp_type = (is_numeric($search_key) ? '=' : $search_type);
            $value = ac_trim($ac_search['ac_datatable_search']);
            if (is_array($tmp_type)) {
                foreach ($tmp_type as $mapping_key => $mapping_value) {
                    if (mb_strpos($value, $mapping_key) !== false) {
                        $tmp_like_list[$tmp_key] = $mapping_value;
                        break;
                    }
                }
            } elseif ($tmp_type != 'equal' || (stripos($tmp_type, 'like') !== false && stripos($tmp_type, 'not') === false)) {
                if (strtotime($value) > 1) {
                    $tmp_like_list[$tmp_key . '__unixtime'] = strtotime($value);  //如果資料庫是以unxitime儲存，也可以搜尋得到
                }
                $tmp_like_list[$tmp_key] = $value;
            }
        }
        if (count($tmp_like_list) > 0) {
            $dynamic_model->group_start();
            foreach ($tmp_like_list as $column_title => $column_value) {
                if (stripos($column_title, '__unixtime') !== false) {
                    $column_title = str_replace('__unixtime', '', $column_title);
                    //如果資料庫是以unxitime儲存，也可以搜尋得到
                    if ($column_value % 86400 == 0 || $column_value % 86400 == 57600)   //57600是GMT+8;
                    {
                        $day_of_last_seconds = $column_value + 86400;
                        $dynamic_model->or_where("$column_title >= $column_value AND $column_title < $day_of_last_seconds");
                    } elseif ($column_value % 3600 == 0) {  //搜尋到整點
                        $hour_of_last_seconds = $column_value + 3600;
                        $dynamic_model->or_where("$column_title >= $column_value AND $column_title < $hour_of_last_seconds");
                    } elseif ($column_value % 60 == 0) {  //搜尋到分鐘
                        $minute_of_last_seconds = $column_value + 60;
                        $dynamic_model->or_where("$column_title >= $column_value AND $column_title < $minute_of_last_seconds");
                    } else {
                        $dynamic_model->or_where($column_title, $column_value);
                    }
                }
                $dynamic_model->or_like($column_title, $column_value);
            }
            $dynamic_model->group_end();
        }
    }
    return $dynamic_model;
}

function check_datatable($input, $action = 'post')
{
    $all_input = $input->$action();
    $check_datatable_post = ['draw', 'start', 'length', 'search', 'order', 'columns'];
    $good_datatable_post = true;
    foreach ($check_datatable_post as $check_key => $check_value) {
        if (!array_key_exists($check_value, $all_input)) {
            $good_datatable_post = false;
        }
    }
    if ($good_datatable_post) {
        return $all_input;
    } else {
        return NULL;
    }
}
