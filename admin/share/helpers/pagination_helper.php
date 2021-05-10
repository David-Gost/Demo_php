<?php

function create_pagination($uri_segment, $base_url, $total_rows, $per_page = 10) {

    $CI = & get_instance();
    $config['uri_segment'] = $uri_segment;
    $config['base_url'] = $base_url;
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $CI->pagination->initialize($config);

    return $CI->pagination->create_links();
}

function create_pagination_by_custom_config($uri_segment="", $base_url="", $total_rows=0, $per_page = 10, $custom_config=array()) {

    $CI = & get_instance();
    $config['uri_segment'] = $uri_segment;
    $config['base_url'] = $base_url;
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;

    $config = array_merge($config, $custom_config);

    $CI->pagination->initialize($config);

    return $CI->pagination->create_links();
}

function page_offset($uri_segment) {

    $CI = & get_instance();
    $offset = is_null($CI->uri->segment($uri_segment)) ? 0 : $CI->uri->segment($uri_segment);
    return $offset;
}

function page() {

    $url_array = explode("/", $_SERVER['PHP_SELF']);

    $urllength = count($url_array);

    if (is_numeric($url_array[$urllength - 1])) {
        return $url_array[$urllength - 1];
    }
}

function create_pagination_style($uri_segment, $base_url, $total_rows, $style_num, $per_page = 10) {

    $CI = & get_instance();
    $config['uri_segment'] = $uri_segment;
    $config['base_url'] = $base_url;
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;

    foreach ($CI->config->config['pagination_' . $style_num] as $key => $value) {
        $config[$key] = $value;
    }

    $CI->pagination->initialize($config);

    return $CI->pagination->create_links_style();
}


function get_pagination_data($uri_segment, $limit, $total_rows, $list, $url) {
    $pagination_config = array();
    $pagination_config['full_tag_open'] = '<ul class="pagination">';
    $pagination_config['prev_link'] = '<span aria-hidden="true">«</span>';
    $pagination_config['prev_tag_open'] = '<li>';
    $pagination_config['next_link'] = '<span aria-hidden="true">»</span>';
    $pagination_config['next_tag_open'] = '<li>';

    $pagination_config['first_link'] = FALSE;
    $pagination_config['first_tag_open'] = '';
    $pagination_config['first_tag_close'] = '';
    $pagination_config['last_link'] = FALSE;
    $pagination_config['last_tag_open'] = '';
    $pagination_config['last_tag_close'] = '';

    $pagination_config['cur_tag_open'] = '<li class="active"><a href="#">';
    $pagination_config['num_tag_open'] = '<li>';


    $CI = & get_instance();
    $CI->data['list'] = $list;
    $CI->data['page_list'] = create_pagination_by_custom_config($uri_segment, $url, $total_rows, $limit, $pagination_config);
}

function get_pagination_data_for_admin($uri_segment, $limit, $total_rows, $list, $url) {
    $pagination_config = array();
    $pagination_config['full_tag_open'] = '<ul class="pagination pagination">';
    $pagination_config['prev_link'] = '«';
    $pagination_config['prev_tag_open'] = '<li class="page-item">';
    $pagination_config['next_link'] = '»';
    $pagination_config['next_tag_open'] = '<li class="page-item">';

    $pagination_config['first_link'] = TRUE;
    $pagination_config['first_tag_open'] = '';
    $pagination_config['first_tag_close'] = '';
    $pagination_config['last_link'] = FALSE;
    $pagination_config['last_tag_open'] = '';
    $pagination_config['last_tag_close'] = '';

    $pagination_config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
    $pagination_config['num_tag_open'] = '<li class="page-item">';

    $CI = & get_instance();
    $CI->mViewData['list'] = $list;
    $CI->mViewData['page_list'] = create_pagination_by_custom_config($uri_segment, $url, $total_rows, $limit, $pagination_config);
}