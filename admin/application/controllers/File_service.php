<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 檔案上傳與下載的service
 */
class File_service extends AC_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("srvl/File_process_service", "file_process_service");
    }
    public function download($which = "", $arg = "", $download_name = "")
    {
        $query = $this->file_process_service->switch_to_find_file($which, $arg);
        if ($query) {
            $this->file_process_service->download($query->file_path, $download_name);
        }

        //上面有找到東西就自動下載，沒找到就回傳false，有回傳才會跳到這邊
        show_404();
        exit;
    }
}
