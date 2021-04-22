<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
$config['full_tag_close'] = '</ul>';

$config['first_link'] = '&laquo;'; //自訂開始分頁連結名稱
$config['first_tag_open'] = '<li class="prev page">';
$config['first_tag_close'] = '</li>';

$config['last_link'] = '&raquo;'; //自訂結束分頁連結名稱
$config['last_tag_open'] = '<li class="next page">';
$config['last_tag_close'] = '</li>';

$config['next_link'] = '下一頁 >';
$config['next_tag_open'] = '<li class="next page">'; //自訂下一頁標籤
$config['next_tag_close'] = '</li>';

$config['prev_link'] = '< 上一頁';
$config['prev_tag_open'] = '<li class="prev page">';
$config['prev_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li class="active"><a>';
$config['cur_tag_close'] = '</a></li>';
$config['num_tag_open'] = '<li class="page">';
$config['num_tag_close'] = '</li>';


// Other Style
$config['pagination_0']['full_tag_open'] = '<div class="pages"><ul class="pagination">';
$config['pagination_0']['full_tag_close'] = '</ul></div>';

$config['pagination_0']['first_link'] = ''; //自訂開始分頁連結名稱
$config['pagination_0']['first_tag_open'] = '';
$config['pagination_0']['first_tag_close'] = '';

$config['pagination_0']['prev_link'] = '&laquo;';
$config['pagination_0']['prev_tag_open'] = '<li>';
$config['pagination_0']['prev_tag_close'] = '</li>';

$config['pagination_0']['cur_tag_open'] = '<li class="active"><a>';
$config['pagination_0']['cur_tag_close'] = '</a></li>';

$config['pagination_0']['num_tag_open'] = '<li>';
$config['pagination_0']['num_tag_close'] = '</li>';

$config['pagination_0']['next_link'] = '&raquo;';
$config['pagination_0']['next_tag_open'] = '<li>';
$config['pagination_0']['next_tag_close'] = '</li>';

$config['pagination_0']['last_link'] = ''; //自訂結束分頁連結名稱
$config['pagination_0']['last_tag_open'] = '';
$config['pagination_0']['last_tag_close'] = '';
