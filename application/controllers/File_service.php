<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property File_process_service $file_process_service
 * 檔案上傳與下載的service
 */
class File_service extends AC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("srvl/File_process_service","file_process_service");
    }
    
    /**
     * <p>下載圖片</p>
     * @param type $which
     * <p>檔案類型，預設使用general</p>
     * @param type $arg
     * <p>存放於系統的檔案名稱</p>
     * @param type $download_name
     * <p>下載檔案時所顯示的檔名</p>
     */
    public function download($which = "", $arg = "",$download_name = "") {
        $query = $this->file_process_service->switch_to_find_file($which, $arg);
        if ($query) {
            if (substr($query->file_path, 0, 6) == 'admin/'){
                $this->file_process_service->download($query->file_path,$download_name);
            }else{
                $this->file_process_service->download("admin/".$query->file_path,$download_name);
            }
        }

        //上面有找到東西就自動下載，沒找到就回傳false，有回傳才會跳到這邊
        show_404();
        exit;
    }
}