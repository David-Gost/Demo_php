<?php

defined('BASEPATH') OR exit('No direct script access allowed');

define("DOWNLOAD_CR", 'download/download_file/');

/**
 *
 * @param type $file_name 檔案名稱
 * @param type $actual_name 檔案命名名稱
 * @param type $view d:下載 v:預覽
 */
function download_url($file_name, $actual_name, $view = 'd') {

    echo site_url() . '/' . DOWNLOAD_CR . $file_name . '/' . $actual_name . '/' . $view;
}
