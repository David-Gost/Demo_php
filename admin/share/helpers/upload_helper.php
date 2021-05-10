<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 
 * 驗證上傳的分類目錄
 * _source  >> 放前台設計的ai檔之類的
 * default  >> 放檔案路徑找不到時，預設要顯示的目錄，裡面可以放客戶的logo或相關的圖檔當預設圖片(預防顯示叉燒包的圖示)
 * tmpfile  >> 放暫存檔，比如有的上傳只是暫時的，在確定修改的動作之後，會移動到確定的目錄，並刪除
 * 
 * @param type $filesname
 * @return string 路徑
 */
function checkfile_classfoldername($filesname) {
    $tmpclassfolder = "";
    if ($filesname == "") {
        $tmpclassfolder = "";
    } else {
        switch (strtolower(pathinfo($filesname, PATHINFO_EXTENSION))) {
            case 'xls':
            case 'xlsx':
            case 'doc':
            case 'docx':
            case 'ppt':
            case 'pptx':
            case 'pdf':
            case 'txt':
            case 'csv':
            case 'zip':
            case '7z':
            case 'gzip':
            case 'iso':
            case 'rar':
            case 'tar':
                $tmpclassfolder = "files/";
                break;
            case 'bmp':
            case 'gif':
            case 'jpeg':
            case 'jpg':
            case 'png':
            case 'ico':
            case 'tif':
            case 'tiff':
                $tmpclassfolder = "images/";
                break;
            case 'mp3':
            case 'avi':
            case 'mp4':
            case 'wav':
            case 'flv':
            case 'mpg':
            case 'mpeg':
            case 'mov':
            case 'rmvb':
            case 'wmv':
            case 'swf':
                $tmpclassfolder = "video/";
                break;
            default:
                $tmpclassfolder = "other/";
                break;
        }
    }
    return $tmpclassfolder;
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
function get_file_folder($filename) {
    return checkfile_classfoldername($filename);
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 
 * @return string 'resource/'
 */
function get_upload_base_path() {

    $CI = & get_instance();

    $upload_base_path = $CI->config->item('upload_path');
    return $upload_base_path;
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
function get_upload_default_folder() {

    $CI = & get_instance();
    $upload_default_folder = $CI->config->item('upload_default_folder');
    return $upload_default_folder;
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
function get_default_ico_name() {

    $CI = & get_instance();
    $default_ico_name = $CI->config->item('default_ico_name');
    return $default_ico_name;
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 取的相對應的副檔名上傳資料夾
 * @param type $filesname
 * @return string
 */
function get_full_upload_folder($filesname) {
    return get_upload_base_path() . checkfile_classfoldername($filesname);
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 刪除檔案
 * @param type $filename
 */
function del_file($filename) {
//    var_dump($filename);
    $path = get_full_upload_folder($filename) . $filename;
//    var_dump($path);
    if (is_file($path)) {
        unlink($path);
    }
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 取得檔案上傳路徑+檔案名稱
 * @param type $filename
 * @return string
 */
function get_file_fullpath($filename) {

    $full_path = '';

    if ($filename) {
        $full_path = get_full_upload_folder($filename) . $filename;
    }

    return $full_path;
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 取得檔案上傳路徑+檔案名稱
 * @param type $filename
 * @return string
 */
function get_fullpath_with_file($filename, $default_name = '') {

    $full_path = '';

    if ($default_name) {
        
    } else {
        $default_name = get_default_ico_name();
    }


    if (empty($filename)) {
        $full_path = get_upload_base_path() . get_upload_default_folder() . $default_name;
    } else {
        $full_path = get_full_upload_folder($filename) . $filename;

        if (!is_file($full_path)) {
            $full_path = get_upload_base_path() . get_upload_default_folder() . $default_name;
        }
    }
    return $full_path;
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 將圖片暫存檔案以base64編碼
 * @param type $filename
 */
function base64_encode_image($filename) {
    if ($filename) {
        $imgbinary = fread(fopen($filename, "r"), filesize($filename));
        return base64_encode($imgbinary);
    }
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 將圖片暫存檔案縮放後，回傳base64編碼
 */
function makepic_to_base64($filename, $dstW, $dstH, $is_base64 = true) {

    $tmpfilename = time() . ".png";
    $tmpfileextension = strtolower(pathinfo($tmpfilename, PATHINFO_EXTENSION)); //檔案的副檔名(轉成小寫)
    $tmpfilename = uniqid('f', false) . '.' . $tmpfileextension;
    $tmpfilepath = get_file_fullpath($tmpfilename);

    $srcFile = fread(fopen($filename, "r"), filesize($filename));
    $source_image = imagecreatefromstring($srcFile);

    $srcW = ImageSX($source_image); //原始圖片的寬度,也可以使用$data[0]
    $srcH = ImageSY($source_image); //原始圖片的高度,也可以使用$data[1]

    if (($dstW == '' or $dstW == null) and $dstW != '0') {
        $dstW = $srcW;
    }
    if (($dstH == '' or $dstH == null) and $dstH != '0') {
        $dstH = $srcH;
    }

    $srcX = 0; //來源圖的坐標x,y
    $srcY = 0;

    if (($srcW / $dstW) > ($srcH / $dstH)) {//得出要生成圖片的長寬
        $dstW2 = $dstW; //輸出圖片的寬度、高度
        $dstH2 = $srcH * $dstW / $srcW;

        $dstX = 0; //輸出圖形的坐標x,y
        $dstY = ($dstH - $dstH2) / 2;
    } else {
        $dstH2 = $dstH; //輸出圖片的寬度、高度
        $dstW2 = $srcW * $dstH / $srcH;

        $dstX = ($dstW - $dstW2) / 2; //輸出圖形的坐標x,y
        $dstY = 0;
    }
    $tmp_image = imagecreatetruecolor($dstW, $dstH); //ImageCreate($dstW,$dstH);畫出空白花布的大小

    imagesavealpha($tmp_image, true);
    $colorBody = imagecolorallocatealpha($tmp_image, 0, 0, 0, 127); //去背功能
    imagefill($tmp_image, 0, 0, $colorBody);

    imagecopyresampled($tmp_image, $source_image, $dstX, $dstY, $srcX, $srcY, $dstW2, $dstH2, $srcW, $srcH);

    imagepng($tmp_image, $tmpfilepath);

    if ($is_base64) {
        $imgbinary = fread(fopen($tmpfilepath, "r"), filesize($tmpfilepath)); //讀取暫存檔
        $base64_image = base64_encode($imgbinary);
        unlink($tmpfilepath); //刪除暫存檔

        imagedestroy($tmp_image);

        return $base64_image;
    } else {
        return $tmpfilename;
    }
}

/**
 * 將檔案自訂檔名下載
 */
function custom_download($fileinfo = '', $orig_file = '') {

    $CI = & get_instance();
    $CI->load->model("srvl/File_process_service","file_process_service");
    $CI->file_process_service->go_download($fileinfo,$orig_file);
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 上傳圖片簡化版
 * 
 * @param $dto
 * @param $field 欄位名稱
 * @param $orig_file 原始檔案名稱
 * @param $width 寬
 * @param $height 高
 * @param $inputBase64 來源是否為base64
 * @param $is_match 符合寬高
 */
function upload_cut_pic_easy($dto, $field = '', $orig_file = '', $width=0, $height=0, $pictype = 'jpg', $isMatchTargetWH = true) {
    $CI = & get_instance();
    $dto_name = 'set' . ucfirst($field);

    $imagezoom = new ImageZoom();
    $imagezoom->targetW = $width;
    $imagezoom->targetH = $height;
    $imagezoom->isMatchTargetWH = $isMatchTargetWH;

    switch ($pictype) {
        case 'jpg':
        case 'jpeg':
            $imagezoom->outputJpeg();
        case 'gif':
            $imagezoom->outputGif();
        case 'png':
            $imagezoom->outputPng();
        default :
            $imagezoom->outputOriginal();
    }
    $imagezoom->isTransparent = false;

    $err_msg = "";

    //限制檔案上傳的檔案型態
    $CI->upload->set_allowed_types(['jpg', 'jpeg', 'bmp', 'gif', 'png']);

    if ($CI->upload->has_uploaded_file($field)) {
        if (check_image_file($_FILES[$field])) {

            $pic_data = $CI->input->post('hid_base64_pic');
            if ($pic_data) {
                $new_pic = str_replace('data:image/png;base64,', '', $pic_data);
                if ($new_pic) {
                    $imagezoom->inputBase64 = $inputBase64;
                    $imagezoom->sourceBase64 = $new_pic;
                    $result = $imagezoom->makepic();
                } else {
                    $imagezoom->sourceFile = $_FILES[$field]['tmp_name'];
                    $result = $imagezoom->makepic();
                }
            } else {
                $imagezoom->sourceFile = $_FILES[$field]['tmp_name'];
                $result = $imagezoom->makepic();
            }

            if (empty($result)) {
                $err_msg = '上傳失敗!!';
            } else {
                $dto->$dto_name($result);
                if (is_file(base_url(get_fullpath_with_file($CI->input->post($orig_file))))) {
                    del_file($CI->input->post($orig_file));
                }
            }
        } else {
            $err_msg = '格式錯誤!!';
        }
    } else {
        $dto->$dto_name($CI->input->post($orig_file));
    }

    return $err_msg;
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
/**
 * 上傳圖片簡化版
 * 
 * @param $dto
 * @param $field 欄位名稱
 * @param $orig_file 原始檔案名稱
 * @param $width 寬
 * @param $height 高
 * @param $inputBase64 來源是否為base64
 * @param $is_match 符合寬高
 */
function upload_pic_easy($dto, $field = '', $orig_file = '', $width=0, $height=0, $pictype = 'jpg', $isMatchTargetWH = true) {
    $CI = & get_instance();
    $dto_name = 'set' . ucfirst($field);

    $imagezoom = new ImageZoom();
    $imagezoom->targetW = $width;
    $imagezoom->targetH = $height;
    $imagezoom->isMatchTargetWH = $isMatchTargetWH;

    switch ($pictype) {
        case 'jpg':
        case 'jpeg':
            $imagezoom->outputJpeg();
        case 'gif':
            $imagezoom->outputGif();
        case 'png':
            $imagezoom->outputPng();
        default :
            $imagezoom->outputOriginal();
    }
    $imagezoom->isTransparent = false;

    $err_msg = "";
    //限制檔案上傳的檔案型態
    $CI->upload->set_allowed_types(['jpg', 'jpeg', 'bmp', 'gif', 'png']);
    if ($CI->upload->has_uploaded_file($field)) {
        if (check_image_file($_FILES[$field])) {

            $imagezoom->sourceFile = $_FILES[$field]['tmp_name'];
            $result = $imagezoom->makepic();
            if (empty($result)) {
                $err_msg = '上傳失敗!!';
            } else {
                $dto->$dto_name($result);
                if (is_file(base_url(get_fullpath_with_file($CI->input->post($orig_file))))) {
                    del_file($CI->input->post($orig_file));
                }
            }
        } else {
            $err_msg = '格式錯誤!!';
        }
    } else {
        $dto->$dto_name($CI->input->post($orig_file));
    }

    return $err_msg;
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
function upload_file_easy($dto, $field = '', $orig_field = '', $orig_file = '', $orig = '') {
    $CI = & get_instance();
    $field_name = 'set' . ucfirst($field);
    $orig_field_name = 'set' . ucfirst($orig_field);

    $err_msg = "";
    if ($CI->upload->has_uploaded_file($field)) {
        if (check_document_file($_FILES[$field])) {

            $result = $CI->upload->do_upload($field);
            if (!$result) {
                $error = $CI->upload->errors();
                $err_msg = $error;
            } else {
                $data = $CI->upload->data();
                $dto->$field_name($data['file_name']);
                $dto->$orig_field_name($data['orig_name']);
                if (!empty($CI->input->post($orig_file))) {
                    del_file($CI->input->post($orig_file));
                }
            }
        } else {
            $dto->$orig_field_name($CI->input->post($orig));
            $dto->$field_name($CI->input->post($orig_file));
            $err_msg = '檔案格式錯誤!!';
        }
    } else {
        $dto->$orig_field_name($CI->input->post($orig));
        $dto->$field_name($CI->input->post($orig_file));
    }
    return $err_msg;
}

//AC_TODO:檢視這個function的必要性，是否可被其它方式取代
function upload_file_easy_show_msg($sid, $model, $field = '', $orig_field = '', $orig_file = '') {

    $CI = & get_instance();
    if ($CI->upload->has_uploaded_file($field)) {
        if (check_document_file($_FILES[$field])) {
            $result = $CI->upload->do_upload($field);
            if (!$result) {
                $error = $CI->upload->errors();
                set_warn_alert($error);
            } else {
                $data = $CI->upload->data();
                //更新檔案
                $model->update($sid, array($orig_field => $data['orig_name'], $field => $data['file_name']));
                if (!empty($CI->input->post($orig_file))) {
                    del_file($CI->input->post($orig_file));
                }
                set_success_alert('上傳成功!!');
            }
        } else {
            set_warn_alert('檔案格式錯誤!!');
        }
    } else {
        set_warn_alert('請選擇檔案!!');
    }
}
