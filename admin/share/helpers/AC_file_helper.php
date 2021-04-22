<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 將檔案大小轉為人類看得懂的檔案大小
 * example:
 * input:1000000 bytes
 * output:0.95MB
 *
 * @param string $bytes 以bytes記的檔案大小
 * @param integer $decimals 顯示的檔案大小小數點顯示到幾位數
 * @return string
 */
function human_filesize($bytes, $decimals = 2) {
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor > 0){
         $sz = 'KMGTPE';
        }
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}

/**
 * 當你使用File_process_service上傳檔案後，需要取得檔案下載的路徑在哪裡
 *
 * @param string $which 此檔案由系統管理或開發的工程師自行管理 (預設為general系統管理，若開發的工程師有需要自行管理，請到File_process_service編寫相關管理的程式碼)
 * @param boolean $use_backend_base_url 強制透過後台的路徑下載檔案
 * @return string
 */
function file_src($which = "general",$use_backend_base_url = false)
{
    $CI = get_instance();
    $CI->load->model("srvl/File_process_service", "file_process_service");
    return $CI->file_process_service->file_src($which,$use_backend_base_url);
}

/**
 * 當你使用File_process_service上傳檔案時，File_process_service為了安全考量會要求上傳檔案時要帶相關token避免
 *
 * @return string
 */
function file_token()
{
    $CI = get_instance();
    $CI->load->model("srvl/File_process_service", "file_process_service");
    return $CI->file_process_service->token();
}

