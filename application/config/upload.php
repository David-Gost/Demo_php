<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 設定上傳檔案預設設定
 * 
 */
//UPLOAD_BASE_PATH ='resource/'
$config['upload_path'] = 'admin/resource/';
$config['allowed_types'] = 'xls|xlsx|doc|docx|ppt|pptx|pdf|txt|csv|zip|7z|gzip|iso|rar|tar|bmp|gif|jpeg|jpg|png|ico|tif|tiff|mp3|avi|mp4|wav|flv|mpg|mpeg|mov|rmvb|wmv|swf';
$config['encrypt_name'] = TRUE;

$config['upload_default_folder'] = 'default/';
$config['default_ico_name'] = 'no.png';
$config['watermark_img'] = 'tmp.png';
