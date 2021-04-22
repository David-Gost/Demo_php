<?php

/**
 * @property Fb $fb Description
 */
class AC_Controller extends CI_Controller
{

    /** @var $mCtrler */
    public $mCtrler = "";

    /** @var $mAction */
    public $mAction = "";

    /** @var $mParam */
    public $mParam = "";

    /** @var $mLayout */
    public $mLayout = "default";

    /** @var $mBreadcrumb */
    public $mBreadcrumb = array();

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set(TIMEZONE);

        //判斷是否有存在session，導向到dashboard
        if (is_null(get_seesion_user())) {
            $redirect = $this->uri->uri_string();
            redirect("login" . (empty($redirect) ? '' : '?redirect=' . $redirect));
        }

        $this->enable_profiler();
        //判斷使用者session是否有效，若未登入導頁到login
        // basic URL params
        $this->init_basic_URL_params();

        //設定breadcrumb entries
        $this->push_breadcrumb('home', 'dashboard', 'home');
        //設定基本的view data
        $this->setting_basic_view_Data();

        //更新瀏覽器就可以改變側邊欄
        $this->load->model("sys/Module_model", "module_model");
        $this->menu();
    }

    protected function menu()
    {
        $module_dto = $this->module_model->find_module_by_account($this->session->sys_user['groups_sid']);
        $module_array = object_to_array($module_dto);
        $user_array = $this->session->sys_user;
        $user_array['module'] = $module_array;
        login_session_user($user_array);
    }

    /**
     * add breadcrumb entry
     */
    protected function push_breadcrumb($name, $url, $icon = '')
    {

        $icon = empty($icon) ? '' : 'fa fa-' . $icon;
        $this->mBreadcrumb[] = array(
            'name' => $name,
            'url' => site_url($url),
            'icon' => $icon,
        );
    }


    /**
     * AC_TODO：檢查是否還有用到？  
     * 設定是否要開啟FirePHP
     */
    private function enable_FirePHP()
    {
        $this->setEnabled(FIREPHP_ENABLE);
    }

    /**
     * 設置是否要啟用效能分析器
     */
    private function enable_profiler()
    {
        $this->output->enable_profiler(PROFILER_ENABLE);
    }

    /**
     * basic URL params
     */
    private function init_basic_URL_params()
    {

        $this->mCtrler = $this->router->fetch_class();
        $this->mAction = $this->router->fetch_method();
        $this->mParam = $this->uri->segment(3);
    }

    /**
     * 設定基本的view data
     */
    private function setting_basic_view_Data()
    {

        $this->mViewData = array(
            'ctrler' => $this->mCtrler,
            'action' => $this->mAction
        );
    }

    public function check_result_data($data = [], $whitelist = [])
    {
        $whitelist = array_merge(['sid', 'createdate', 'createuser', 'createip', 'updatedate', 'updateuser', 'updateip', 'sequence', 'lan'], $whitelist);
        $ignore = false;
        if (count($data) > 0) {
            foreach (array_keys((array) $data[0]) as $key => $value) {
                $in_whitelist = false;
                foreach ($whitelist as $w_key => $w_value) {
                    if ($value == $w_value) {
                        $in_whitelist = true;
                        break;
                    }
                }
                if ($in_whitelist === false) {
                    echo '<pre>', print_r('此欄位不在白名單內，請確認是否為機敏資訊：' . $value), '</pre>';
                    $ignore = true;
                    break;
                }
            }
        }
        if ($ignore) {
            return [];
        } else {
            return $data;
        }
    }
}

class AJAX_Controller extends CI_Controller
{

    public $result_status = ""; //----狀態
    public $result_msg = array(); //----訊息
    public $result_data = ""; //----資料

    public function __construct()
    {
        parent::__construct();

        //判斷是否有存在session，導向到dashboard
        if (is_null(get_seesion_user())) {
            redirect("login");
        }
    }

    /**
     * <p>取得ajax回傳值</p>
     * @return \stdClass
     */
    public function get_ajax_result()
    {

        $result_dto = new stdClass();
        $result_dto->status = $this->result_status;
        $result_dto->message = $this->result_msg;
        $result_dto->data = $this->result_data;

        return $result_dto;
    }

    function setResult_status($result_status)
    {
        $this->result_status = $result_status;
    }

    function setResult_msg($result_msg)
    {
        $this->result_msg = $result_msg;
    }

    function setResult_data($result_data)
    {
        $this->result_data = $result_data;
    }
}
