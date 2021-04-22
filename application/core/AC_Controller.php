<?php

/**
 */
class AC_Controller extends CI_Controller
{

    public $data;

    public function __construct()
    {

        parent::__construct();

        $CI = &\get_instance();

        if ($this->is_usehttps()) {
            if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "") {
                $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                header("Location: $redirect");
            }
        }

        $this->browser_history();
    }

    //判斷是否有啟用https
    private function is_usehttps()
    {
        $CI = &\get_instance();
        $CI->load->model('sys/Sysinfo_model', 'sysinfo_model');
        $this->sysinfo_dto = $this->sysinfo_model->find_sysinfo();
        if (!is_null($this->sysinfo_dto) && !is_null($this->sysinfo_dto->getHttpson_front()) && (($this->sysinfo_dto->getHttpson_front()) != 0)) {
            return true;
        }
        return false;
    }

    protected function alert($msg, $url = null)
    {
        $msgstr = $this->msg_array_to_str($msg);

        echo "<script>alert('" . $msgstr . "');</script>";

        if (!empty($url)) {
            echo "<script>location.href = '" . $url . "';</script>";
        }
    }

    protected function msg_array_to_str($msg)
    {
        $msgstr = "";
        if (is_array($msg)) {
            for ($i = 0; $i < count($msg); $i++) {
                $msgstr .= "★ " . $msg[$i] . '\n';
            }
        } else {
            $msgstr = $msg;
        }
        return $msgstr;
    }

    function browser_history()
    {
        $REQUEST_SCHEME = '';
        if (isset($_SERVER['REQUEST_SCHEME'])) {
            $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME'];
        } else {
            $REQUEST_SCHEME = ((strtolower($_SERVER['HTTPS'])  == 'off') ? 'http' : 'https');
        }
        $now_url = $REQUEST_SCHEME . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $first_time_flag = empty($_SERVER['HTTP_REFERER']) && empty($_SERVER['HTTP_CACHE_CONTROL']);
        $reorganization_flag = !empty($_SERVER['HTTP_REFERER']) && empty($_SERVER['HTTP_CACHE_CONTROL']) && $now_url != $_SERVER['HTTP_REFERER'];

        if ($first_time_flag || $reorganization_flag) {
            $this->load->model("sys/Browser_history_model", "Browser_history_model");
            $this->Browser_history_model->add_browser_history();
        }
    }

    private function add_viewcount()
    {
        $user_ip = $this->session->userdata('user_ip');
        //        $this->session->unset_userdata('user_ip');
        if (!$user_ip) {
            $this->load->model("sys/Viewcount_model", "viewcount_model");
            $this->load->library("dto/Viewcount_dto", "viewcount_dto");

            $user_ip = $_SERVER['REMOTE_ADDR'];
            $this->session->set_userdata('user_ip', $user_ip);

            $viewcount_dto = $this->viewcount_dto;
            $viewcount_dto->setSourceip($user_ip);
            $viewcount_dto->setViewdate(date('Y-m-d'));
            $this->viewcount_model->add_view_count($viewcount_dto);
        }
    }
    public function ajax_status($succ_or_fail, $error_msg = '')
    {
        if ($succ_or_fail === true) {
            $this->output->set_status_header('200');
            $this->ajax_re->status = API_RESULT_SUC;
        } else {
            $this->output->set_status_header('500');
            $this->ajax_re->status = API_RESULT_ERROR;
            $this->ajax_re->message = $error_msg;   //ac_todo:這裡有i18n
        }
        return $this;
    }

    public function ajax_result($result)
    {
        $this->ajax_re->result = $result;
        return $this;
    }

    public function ajax_get_re()
    {
        return $this->ajax_re;
    }
}

class AJAX_Controller extends CI_Controller
{

    private $ajax_re;
    public function __construct()
    {
        parent::__construct();
        $this->ajax_re = new stdClass();
        $this->ajax_re->message = '';
        $this->ajax_re->result = null;
        $this->status(true);

        $this->browser_history();
        if ($this->is_usehttps()) {
            if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "") {
                $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                // header("Location: $redirect");
                //要試試看改用308跳轉
            }
        }
    }

    //判斷是否有啟用https
    private function is_usehttps()
    {
        $CI = &\get_instance();
        $CI->load->model('sys/Sysinfo_model', 'sysinfo_model');
        $this->sysinfo_dto = $this->sysinfo_model->find_sysinfo();
        if (!is_null($this->sysinfo_dto) && !is_null($this->sysinfo_dto->getHttpson_front()) && (($this->sysinfo_dto->getHttpson_front()) != 0)) {
            return true;
        }
        return false;
    }

    function browser_history()
    {
        $now_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $first_time_flag = empty($_SERVER['HTTP_REFERER']) && empty($_SERVER['HTTP_CACHE_CONTROL']);
        $reorganization_flag = !empty($_SERVER['HTTP_REFERER']) && empty($_SERVER['HTTP_CACHE_CONTROL']) && $now_url != $_SERVER['HTTP_REFERER'];

        if ($first_time_flag || $reorganization_flag) {
            $this->load->model("sys/Browser_history_model", "Browser_history_model");
            $this->Browser_history_model->add_browser_history();
        }
    }

    public function status($succ_or_fail)
    {
        if ($succ_or_fail === true) {
            $this->output->set_status_header('200');
            $this->ajax_re->status = API_RESULT_SUC;
        } else {
            $this->output->set_status_header('500');
            $this->ajax_re->status = API_RESULT_ERROR;
        }
        return $this;
    }

    public function check_result($true_or_false)
    {
        $this->ajax_re->check_result = $true_or_false;
        return $this;
    }

    public function message($message)
    {
        $message = _lan($message);
        $this->ajax_re->message .= $message . "\r\n";
        return $this;
    }

    public function clear_message($message)
    {
        $message = _lan($message);
        $this->ajax_re->message = $message . "\r\n";
        return $this;
    }

    public function result($result)
    {
        $this->ajax_re->result = $result;
        return $this;
    }

    public function get_re()
    {
        return $this->ajax_re;
    }
}
