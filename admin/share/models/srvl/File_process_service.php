<?php

use function GuzzleHttp\default_ca_bundle;

define('FILE_PROCESS_TOKEN_SESSION_KEY', "upload_token_array");
define('FILE_PROCESS_MAX_TOKEN_TIME', 604800);
class File_process_service extends AC_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();

        $this->load->library('upload');

        $this->load->model('sys/Files_store_model', 'files_store_model');
        $this->load->model('sys/Files_model', 'files_model');
    }

    /**
     * <p>顯示目前網址為前台 or 後台</p>
     * @return string
     */
    public function where_am_i()
    {
        if (defined('FRONT_BASE_URL')) {
            return 'BACKEND';
        } else {
            return 'FRONT';
        }
    }

    /**
     * <p>取得檔案網址</p>
     * @param type $which
     * @param type $use_backend_base_url
     * @return type
     */
    public function file_src($which = 'general', $use_backend_base_url = false)
    {
        if ($use_backend_base_url) {
            return BASE_URL . "/file_service/download/$which/";
        } else {
            if ($this->where_am_i() == "BACKEND") {
                return FRONT_BASE_URL . "/file_service/download/$which/";
            } else {
                return BASE_URL . "/file_service/download/$which/";
            }
        }
    }

    // // AC_TODO記得寫註解
    // public function find_files($sid = [],$need_base_url = true)
    // {
    //     if(empty($sid)){
    //         return [];
    //     }
    //     $re = $this->files_store_model->where_in('sid',$sid)->all();
    //     if($need_base_url){
    //         foreach ($re as $key => $value) {
    //             $re[$key]->url_path = $this->FILE_SRC.$value->url_path;
    //         }
    //     }
    //     return $re;
    // }

    /**
     *<p>檔案上傳</p>
     * @param type $token
     * <p>此次上傳的token，使用function token 取得</p>
     * @param type $formname
     * <p>input upload key名稱</p>
     * @param type $allowed_types
     * <p>允許上傳的檔案類型</p>
     * @return \dto_name
     */
    public function files_upload($token, $formname, $allowed_types = "")
    {
        if ($this->check_token_time($token) <= 0) {
            return null;
        }
        $this->upload->allowed_types = $allowed_types == "" ? $this->upload->allowed_types : $allowed_types;
        $result_and_message = [];
        if ($this->upload->has_uploaded_file($formname)) {
            $multiple = is_array($_FILES[$formname]['tmp_name']) ? true : false;
            if ($multiple) {
                foreach ($_FILES[$formname]['name'] as $key => $value) {
                    $tmp_file = array(
                        'name' => $_FILES[$formname]['name'][$key],
                        'type' => $_FILES[$formname]['type'][$key],
                        'tmp_name' => $_FILES[$formname]['tmp_name'][$key],
                        'error' => $_FILES[$formname]['error'][$key],
                        'size' => $_FILES[$formname]['size'][$key],
                    );
                    $result_and_message[] = $this->upload->do_upload($tmp_file) ? $this->upload->data() : $this->upload->errors();
                }
            } else {
                $tmp_file = array(
                    'name' => $_FILES[$formname]['name'],
                    'type' => $_FILES[$formname]['type'],
                    'tmp_name' => $_FILES[$formname]['tmp_name'],
                    'error' => $_FILES[$formname]['error'],
                    'size' => $_FILES[$formname]['size'],
                );
                $result_and_message[] = $this->upload->do_upload($tmp_file) ? $this->upload->data() : $this->upload->errors();
            }
        } else {
            //支援base64用陣列的方式上傳
            $tmp_post = $this->input->post($formname);
            $upload_array = [];
            if (is_array($tmp_post)) {
                $upload_array = $tmp_post;
            } else {
                $upload_array = [$tmp_post];
            }
            foreach ($upload_array as $upload_string_key => $upload_string) {
                if (!is_null($base64_string = $upload_string)) {
                    $data = explode(',', $base64_string);
                    if (count($data) > 1 && (strpos(strtolower($data[0]), 'jpeg') || strpos(strtolower($data[0]), 'png'))) {
                        $file_ext = '';
                        if (strpos(strtolower($data[0]), 'jpeg')) {
                            $file_ext = '.jpeg';
                        } else {
                            $file_ext = '.png';
                        }

                        $file_name = '';
                        $full_path = '';
                        do {
                            $file_name = md5(uniqid(mt_rand())) . $file_ext;
                            $full_path = $this->upload->upload_path . checkfile_classfoldername($file_name) . $file_name;
                        } while (is_file($full_path));

                        $file = fopen($full_path, 'wb');
                        fwrite($file, base64_decode($data[1]));
                        fclose($file);

                        $result_and_message[] = ['file_name' => $file_name, 'file_ext' => $file_ext, 'full_path' => $full_path, 'orig_name' => 'base64_decode' . $file_ext];
                    } else {
                        $result_and_message[] = 'bad base64 string';
                    }
                }
            }
        }

        foreach ($result_and_message as $key => $value) {
            if (is_array($value)) {
                $dto_name = $this->files_store_model->dto();
                $file_dto = new $dto_name();
                $file_dto->name = $value['file_name'];
                $file_dto->file_ext = $value['file_ext'];
                $file_dto->size = filesize($value['full_path']);
                $file_dto->file_hash = md5_file($value['full_path']);
                $file_dto->file_path = get_full_upload_folder($value['file_name']) . $value['file_name']; //原本要用 絕對路徑 $value['full_path']，但後來想想這樣路徑會被綁住就不要了;
                $file_dto->upload_token = $token;
                $file_dto->sigh = 'temporary';
                $file_dto->action_sigh = 'save';
                $file_dto->upload_from = $this->where_am_i();

                $this->files_store_model->add_dto($file_dto);
                $file_dto->sid = $this->db->insert_id();
                $file_dto->orig_name = $value['orig_name'];

                $result_and_message[$key] = $file_dto;
            }
        }
        return $result_and_message;
    }

    /**
     * <p>取得操作檔案的token，有此token才可以在此process_service內做任何操作(token是存放在session內)</p>
     * @param integer $token_time   
     * <p>設定token存活時間，預設3600秒，最多604800秒</p>
     * @return void
     */
    public function token($token_time = 3600)
    {
        $token = md5(now() . uniqid($this->session->userdata('session_id'), true));    //這樣寫出來的token碰撞機率應該很低了啦，雖然沒有驗證到底有多低就是了 科科
        $upload_token_array = $this->session->userdata(FILE_PROCESS_TOKEN_SESSION_KEY);
        $upload_token_array[$token] = now() + ($token_time > FILE_PROCESS_MAX_TOKEN_TIME ? FILE_PROCESS_MAX_TOKEN_TIME : $token_time);
        $this->session->set_userdata(FILE_PROCESS_TOKEN_SESSION_KEY, $upload_token_array);
        return $token;
    }

    /**
     * <p>檢查此token的剩餘時間</p>
     *
     * @param $token
     * <p>檔案上傳的token</p>
     * @param boolean $unset    
     * <p>是否刪除，預設為不要</p>
     * @return void
     */
    public function check_token_time($token, $unset = false)
    {
        $upload_token_array = $this->session->userdata(FILE_PROCESS_TOKEN_SESSION_KEY);
        if (is_array($upload_token_array) && array_key_exists($token, $upload_token_array)) {
            $token_unix_time = $upload_token_array[$token];
            if ($token_unix_time - now() <= 0 || $unset) {
                unset($upload_token_array[$token]);
                $this->session->set_userdata(FILE_PROCESS_TOKEN_SESSION_KEY, $upload_token_array);
            }
            return $token_unix_time;
        } else {
            return -1;
        }
    }

    /**
     * 
     *<p>標記此檔案預計要做的動作</p>
     * @param string $token
     * <p>此次動作的token</p>
     * @param array $sid_or_size__hash            
     * <p>要標記的對象，陣列（sid 為sys_files_store的sid，size__hash為使用files_upload上傳檔案時所產生的檔名）</p>
     * @param string $action_sigh
     * <p>要進行的動作 (’save’=儲存,’delete’=刪除)</p>
     * @return void
     */
    public function set_action_sigh($token, $sid_or_size__hash = [], $action_sigh)
    {
        if ($this->check_token_time($token) <= 0) {
            return -1;
        }
        $table = $this->files_store_model->table();
        $sid_array = [];
        foreach ($sid_or_size__hash as $key => $value) {
            if (is_numeric($value)) {
                $sid_array[] = $value;
            } else {
                $tmp = explode("__", $value);
                if (count($tmp) == 2) {
                    $this->db->where_not_in('sigh', ['same'])->where('size', $tmp[0])->where('file_hash', $tmp[1])->update($table, ['upload_token' => $token, 'action_sigh' => $action_sigh]);
                } else {
                    $this->db->where_not_in('sigh', ['same'])->where('name', $value)->update($table, ['upload_token' => $token, 'action_sigh' => $action_sigh]);
                }
            }
        }
        if (count($sid_array) > 0) {
            $this->db->where_not_in('sigh', ['same'])->where_in('sid', $sid_array)->update($table, ['upload_token' => $token, 'action_sigh' => $action_sigh]);
        }
        return $this->db->affected_rows();
    }

    /**
     * <p>實際進行此token所標記要做的動作，操作完此token即失效，因為這個service遇到一模一樣的檔案會進行合併
     * 對應sys_files_store的sid可能會改變，
     * 此function會回傳此次實際上傳到server內的檔案所對應的sid</p>
     * 
     * 
     *
     * @param type $token
     * <p>此次動作的token</p>
     * @param type $remark
     * <p>若是儲存檔案，標記此檔案的備註，哪個model上傳的，對應的sid是多少，以後有問題會比較好找</p>
     * @return *_dto
     */
    public function confirm_action_sign($token, $remark)
    {
        if ($this->check_token_time($token, true) <= 0) {
            return false;
        }
        $table = $this->files_store_model->table();
        $MAX_TOKEN_TIME = now() - FILE_PROCESS_MAX_TOKEN_TIME;

        //尋找所有原本被標記要刪除，但後來沒有按下確認的(token過期)，action_sigh改回NULL以免有機會被誤刪
        $this->db->where("createdate < $MAX_TOKEN_TIME AND action_sigh = 'delete'")->update($table, ["action_sigh" => null, "upload_token" => null]);

        //將剛剛上傳的檔案標記為已儲存
        $this->db->where(["upload_token" => $token, "action_sigh" => "save", "sigh" => "temporary"])->update($table, ["sigh" => "save", "action_sigh" => null, "remark" => $remark]);

        //刪除被標記為刪除or暫存過期的
        $del_files = $this->db
            ->group_start()->where("sigh <> 'same' AND upload_token = '$token' AND action_sigh = 'delete'")->group_end()
            ->or_group_start()->or_where("createdate < $MAX_TOKEN_TIME AND sigh = 'temporary'")->group_end()
            ->get($table)->result();
        foreach ($del_files as $d_key => $d_value) {
            del_file($d_value->name);
            $this->db->where(["sid" => $d_value->sid])->delete($table);
        }


        //尋找有沒有人上傳一模一樣的檔案，有的話合併起來，然後把自己的刪掉
        $files = $this->files_store_model->where(["upload_token" => $token])->all();
        foreach ($files as $key => $value) {
            $same_file = $this->files_store_model->where(["upload_token" => NULL, "file_hash" => $value->file_hash, "size" => $value->size, "file_ext" => $value->file_ext])->where_not_in("sigh", ["temporary"])->all();
            if (count($same_file) >= 1) {
                $remark_tmp_array = explode(";;;", $same_file[0]->remark);
                $remark_tmp_array[] = $remark;
                $new_remark = implode(";;;", $remark_tmp_array);
                $this->db->where("sid", $same_file[0]->sid)->update($table, ["sigh" => "same", "remark" => $new_remark]);

                del_file($value->name);
                $this->db->where("sid", $value->sid)->delete($table);
                $files[$key] = $same_file[0];   //取代它！
            }
        }

        //移除token，不再被隨意新增刪除
        $this->db->where(["upload_token" => $token])->update($table, ["upload_token" => NULL]);
        return $files;
    }

    /**
     * 相信使用者上傳的東西很安全，不做任何驗證就上傳檔案並儲存
     * (相當於呼叫 files_upload + confirm_action_sign 這兩個function)
     *
     * @param string $token     可以傳有效的token或直接傳字串 safety 進來
     * @param string $formname  檔案上傳的name名稱
     * @param string $remark    此檔案的備註，哪個model上傳的，對應的sid是多少，以後有問題會比較好找
     * @param array $delete_file 此項上傳後的檔案是用來替代某一個檔案的，被替代的檔案會被刪除
     * @param int $upload_limit 限制上傳多少個檔案
     * @param string $allowed_types 可接收的副檔名，預設為全部接受
     * @return void
     */
    public function upload_and_confirm($token, $formname, $remark, $delete_file, $upload_limit, $allowed_types = "")
    {
        if ($token == "safety") {
            $token = $this->token();
        }

        if (!is_array($delete_file)) {
            $delete_file = [$delete_file];
        }
        $this->set_action_sigh($token, $delete_file, 'delete');

        $upload = $this->files_upload($token, $formname, $allowed_types);

        if (count($upload) <= $upload_limit) {
            foreach ($upload as $key => $value) {
                if (($key + 1) > $upload_limit) {
                    $this->set_action_sigh($token, [$value->size . '__' . $value->file_hash], 'delete');
                }
            }
            $confirm = $this->confirm_action_sign($token, $remark);
            foreach ($confirm as $c_key => $c_value) {
                foreach ($upload as $u_key => $u_value) {
                    if ($c_value->file_hash == $u_value->file_hash && $c_value->size == $u_value->size && $c_value->file_ext == $u_value->file_ext) {
                        $confirm[$c_key]->orig_name = $u_value->orig_name;
                        break;
                    }
                }
            }
            return $confirm;
        }

        //實際上傳檔案超過限制
        return [];
    }

    /**
     * 實際進行此token所標記要做的動作，不做任何確認就進行
     * (相當於呼叫 set_action_sigh + confirm_action_sign 這兩個function)
     *
     * @param [type] $token
     * @param array $sid            要標記的對象
     * @param string $action_sigh   要進行的動作 (’save’=儲存,’delete’=刪除)
     * @param string $remark        標記此檔案的備註，哪個model上傳的，對應的sid是多少，以後有問題會比較好找
     * @return *_dto
     */
    public function set_action_sigh_and_confirm($token, $sid_or_size__hash = [], $action_sigh, $remark)
    {
        if ($token == "safety") {
            $token = $this->token();
        }
        $this->set_action_sigh($token, $sid_or_size__hash, $action_sigh);
        return $this->confirm_action_sign($token, $remark);
    }

    /**
     * 依照url_path或client_name尋找檔案
     * 預設是由Files_model這個model進行檔案管理
     * 但RD也可自行開發自己的model，只要在
     * 可是相對應的表內一定要有url_path，client_name，can_download，sys_files_store_sid這四個欄位
     * sys_files_store_sid對應sys_files_store這個table的sid
     *
     * @param string $which     預設使用general
     * @param string $arg       要尋找的檔案
     * @return *_dto
     */
    public function switch_to_find_file($which, $arg)
    {
        $arg = urldecode($arg);
        $which = urldecode($which);
        $join_table = "";
        switch ($which) {
            case 'internal':
                $join_table = $this->files_model->table();
                break;
            case 'general':
                $join_table = "";
                break;
        }

        // url_path和client_name可能會重複，以url_path為優先
        $query = null;
        if ($join_table == "") {
            $query = $this->db->from($this->files_store_model->table())->where('name', $arg)->limit(1)->get()->row();

            if (is_null($query)) {
                $tmp = explode("__", $arg);
                if (count($tmp) == 2) {
                    $query = $this->db->from($this->files_store_model->table())->where('size', $tmp[0])->where('file_hash', $tmp[1])->limit(1)->get()->row();
                }
            }
        } else {
            $query = $this->db->from($this->files_store_model->table())->join($join_table, "$join_table.sys_files_store_sid = sys_files_store.sid")->where(['url_path' => $arg, "can_download" => "1"])->limit(1)->get()->row();
            if (empty($query)) {
                $this->db->from($this->files_store_model->table())->join($join_table, "$join_table.sys_files_store_sid = sys_files_store.sid")->where(['client_name' => $arg, "can_download" => "1"])->limit(1)->get()->row();
            }
        }

        if (!is_null($query)) {
            return $query;
        } else {
            return null;
        }
    }

    /**
     * <p>檔案下載<p>
     * @param type $full_name_filepath
     * <p>實際檔案的存放位置</p>
     * @param type $filename_for_browser
     * @param string $download_mode
     * @param type $force_download
     * @return boolean
     */
    public function download($full_name_filepath, $filename_for_browser = '', $download_mode = 'attachment', $force_download = false)
    {
        if (is_file($full_name_filepath)) {
            $full_path = $full_name_filepath;
        } else {
            $full_path = get_file_fullpath($full_name_filepath);
        }

        if (is_file($full_path)) {
            header_remove('Pragma'); //移除快取控制，讓瀏覽器自己決定要不要快取
            header_remove('Expires'); //移除快取控制，讓瀏覽器自己決定要不要快取
            header_remove('Cache-Control'); //移除快取控制，讓瀏覽器自己決定要不要快取
            header('Cache-Control:public,max-age=604800');

            $file_name = $filename_for_browser == '' ? basename($full_path) : $filename_for_browser;

            //使用利用 switch判別額外的的檔案類型
            $info = finfo_open(FILEINFO_MIME_TYPE);
            $ctype = finfo_file($info, $full_path);
            $extension = pathinfo($full_path, PATHINFO_EXTENSION);

            //用get_mimes判斷有沒有額外處理的mime類型
            // Load the mime types
            $mimes = &get_mimes();

            // Only change the default MIME if we can find one
            if (isset($mimes[$extension])) {
                $ctype = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
            }

            if ($force_download) {
                $ctype = "application/octet-stream";    //強制瀏覽器用下載的方式，不使用流覽器的預覽方式 (不告訴瀏覽器，現在要下載的是什麼類型)
                $download_mode = "attachment";  //小提示，就算送inline進來，遇到application/octet-stream的狀況，還是會開新視窗來下載 (所以這邊乾脆改成attachment)
            }
            header("Content-Type: $ctype");

            //執行下載動作(支援中文檔名下載)
            $file_name = str_replace('+', '%20', str_replace(' ', '%20', urlencode($file_name)));  //php的urlencode会把空格替换成+，而这里需要替换成%20
            $header = "Content-Disposition: $download_mode; filename=\"$file_name\";filename*=\"utf-8''$file_name\"";
            header($header);
            header("Content-Transfer-Encoding: binary");

            $size = filesize($full_path);

            header("Accept-Ranges: bytes");
            $range_start = 0;
            $range_end = $size - 1;
            //支援斷點續傳
            /*Range頭域 Range頭域可以請求實體的一個或者多個子範圍。 
                例如， 
                表示頭500個位元組：bytes=0-499 
                表示第二個500位元組：bytes=500-999 
                表示最後500個位元組：bytes=-500 
                表示500位元組以後的範圍：bytes=500- 
                第一個和最後一個位元組：bytes=0-0,-1 
                同時指定幾個範圍：bytes=500-600,601-999 
                但是伺服器可以忽略此請求頭，如果包含Range請求頭，響應會以狀態碼206（PartialContent）返回而不是以200 （OK）。 
            */
            if (isset($_SERVER['HTTP_RANGE']) && !empty($_SERVER['HTTP_RANGE'])) {
                $range = '';
                $range = explode("=", $_SERVER['HTTP_RANGE'])[1];
                $range = explode(',', $range)[0];  //同時要求多個範圍時，只處理第一個區段 ex:同時指定幾個範圍：bytes=500-600,601-999
                $range_start = explode('-', $range)[0];   //ex:表示頭500個位元組：bytes=0-499 
                $range_end = explode('-', $range)[1];   //ex:表示頭500個位元組：bytes=0-499 

                $range_end = (empty($range_end) && $range_end != '0') ? ($size - 1) : $range_end;  //ex:表示500位元組以後的範圍：bytes=500- 
                $range_start = (empty($range_start) && $range_start != 0) ? ($size - $range_end - 1) : $range_start; //表示最後500個位元組：bytes=-500 

                if (($range_start > $range_end) || $range_end >= $size) {
                    $this->output->set_status_header('416');    //HTTP /1.1 416 Range Not Satisfiable
                    echo "HTTP /1.1 416 Range Not Satisfiable";
                    exit();
                }

                $this->output->set_status_header('206');    //HTTP /1.1 206 Partial Content
                header('Content-Length:' . ($range_end - $range_start + 1));
                header('Content-Range: bytes ' . $range_start . '-' . $range_end . '/' . $size);
            } else {
                $ETag = $size . md5_file($full_path);
                header("ETag: " . $ETag);
                if ((isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : '') == $ETag) {
                    $this->output->set_status_header('304');    //HTTP /1.1 304 Not Modified
                    echo "HTTP /1.1 304 Not Modified";
                    exit();
                }
                header("Content-Length: " . $size);
                header('Content-Range: bytes ' . $range_start . '-' . $range_end . '/' . $size);
            }

            // AC_TODO:可以加上支援GZIP的傳輸方式
            session_write_close();  //關閉sessino，提升多點續傳的能力
            set_time_limit(0);

            // Clean output buffer
            if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
                @ob_clean();
            }

            $fp = fopen($full_path, "rb");  //只读方式打开，将文件指针指向文件头。 (為避免在windows下有 \n 转换为 \r\n的問題，在mode參數加上 b 解決相容性問題)
            fseek($fp, $range_start);
            $one_set_bytes = 1 * (1024 * 1024);
            $push_length = $range_end - $range_start + 1;
            while (!feof($fp) && (connection_status() === CONNECTION_NORMAL) && $push_length > 0) {
                echo fread($fp, $one_set_bytes);

                if (ob_get_level() > 0) {
                    ob_flush();
                    flush();
                }
                $push_length = $push_length - $one_set_bytes;
            }

            fclose($fp);
            if (ob_get_level() > 0) {
                ob_flush();
                flush();
            }

            exit();

            return true;
        } else {
            return false;
        }
    }

    //清空不在資料夾內的檔案 (專案轉正式機時移除不必要的檔案時使用)
    public function sync_file_and_table()
    {
        $files_store_table = $this->files_store_model->table();

        $sys_file = $this->files_model->all();
        foreach ($sys_file as $key => $value) {
            //寫法很亂七八糟，反正是跑一次的就不管了
            $tmp_file = $this->files_store_model->find_by_sid($value->sys_files_sid);

            del_file($tmp_file->name);
            $this->db->where(["sid" => $tmp_file->sid])->delete($files_store_table);
        }

        $files_store = $this->files_store_model->all();
        foreach ($files_store as $key => $value) {
            del_file($value->name);
            $this->db->where(["sid" => $value->sid])->delete($files_store_table);
        }
    }
}
