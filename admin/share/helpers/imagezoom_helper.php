<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//圖片上傳
/**
 * 
 * @param type $file
 * <p>檔案名稱</p>
 * @param type $targetW
 * <p>檔案寬</p>
 * @param type $targetH
 * <p>檔案高</p>
 * @param type $array_key
 * <p>上傳為多檔時需傳入array_key</p>
 * @param type $isMatchTargetWH
 * <p>是否為滿版圖片</p>
 * 
 */
function imagezoom_upload($file, $targetW, $targetH,$array_key='0', $isMatchTargetWH = false) {

    $CI = get_instance();
    $CI->load->library("Imagezoom");
    $imagezoom = new ImageZoom();
    $imagezoom->isMatchTargetWH = $isMatchTargetWH;

    $imagezoom->makeOriginalType = 'jpeg';
    $imagezoom->sourceFile = is_array($_FILES[$file]['tmp_name'])?$_FILES[$file]['tmp_name'][$array_key]:$_FILES[$file]['tmp_name'];

    if (!empty($targetW) && $targetW != 0) {
        $imagezoom->targetW = $targetW;
    }
    if (!empty($targetH) && $targetH != 0) {
        $imagezoom->targetH = $targetH;
    }
    $imagezoom->file_name = '';

    $result = $imagezoom->makepic();

    if (empty($result)) {
        return 'upload_error';
    } else {
        return $result;
    }
}

/**
 * @param type $file_name
 * <p>檔案名稱</p>
 * @param type $targetW
 * <p>檔案轉換寬</p>
 * @param type $targetH
 * <p>檔案轉換高</p>
 * @param type $defult_image
 * <p>檔案預設圖路徑</p>
 */
function imagezoom_to_base64($file_name, $targetW, $targetH, $isMatchTargetWH = false, $defult_image = '') {
    $CI = get_instance();
    $CI->load->library("Imagezoom");
    $imagezoom = new ImageZoom();
    $imagezoom->isMatchTargetWH = $isMatchTargetWH;

    $imagezoom->makeOriginalType = 'jpeg';
    $imagezoom->sourceFile = get_fullpath_with_file($file_name);
    $imagezoom->outputBase64 = true;

    if (!empty($targetW) && $targetW != 0) {
        $imagezoom->targetW = $targetW;
    }
    if (!empty($targetH) && $targetH != 0) {
        $imagezoom->targetH = $targetH;
    }
    $imagezoom->file_name = '';

    $result = $imagezoom->makepic();

    if (empty($result)) {

        if (!empty($defult_image)) {
            return $defult_image;
        } else {
            return 'file_error';
        }
    } else {
        return 'data:image/png;base64,'.$result;
    }
}
