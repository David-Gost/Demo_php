<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of login
 *
 * @author jingyu1009
 *
 *
 * @property Account_model $account_model
 * @property Account_dto $account_dto
 * @property Module_model $module_model
 * @property Module_dto $module_dto
 * @property Sysinfo_model $sysinfo_model
 * @property Sysinfo_dto $sysinfo_dto
 */
class Login extends CI_Controller {

    //put your code here

    public function __construct() {

        parent::__construct();
        $this->load->model("sys/Account_model", "account_model");
        $this->load->model("sys/Module_model", "module_model");
        $this->load->model("sys/Rule_model", "rule_model");
        $this->load->model('sys/Sysinfo_model', 'sysinfo_model');

        $this->load->library('dto/Account_dto', 'account_dto');
        $this->load->library('dto/Module_dto', 'module_dto');
        $this->load->library('dto/Rule_dto', 'Rule_dto');
        $this->load->library('dto/Sysinfo_dto', 'sysinfo_dto');
    }

    public function index() {

        if ($this->is_usehttps()) {
            if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "") {
                $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                header("Location: $redirect");
            }
        }
        
        $this->build_captcha();
        $this->mViewData['sysinfo_dto'] = $this->sysinfo_model->find_sysinfo();
        $this->mViewFile = 'login';
        $redirect = $this->input->get('redirect');
        $this->mViewData['redirect'] = empty($redirect)?'':'?redirect='.urlencode($redirect);
    }

    /**
     * 處理帳號密碼登入
     */
    public function process() {

        $error_message = $this->validation_message();

        if (!empty($error_message)) {
//                echo '<pre>',print_r($error_message), '</pre>';

            set_warn_alert($error_message);
            $this->build_captcha();
            $this->mViewFile = 'login';
            return;
        }

        $usr = $this->input->post("username");
        $pwd = $this->input->post("password");

        if ($this->is_useverify()) {
            //判斷captch
            $userCaptcha = $this->input->post('userCaptcha');
            $word = $this->session->userdata('captchaWord');
            if (strcmp(strtoupper($userCaptcha), strtoupper($word)) == 0) {
                //驗證通過清掉userdata session
                $this->clean_session_captcha();
            } else {

                set_warn_alert('驗證碼輸入錯誤!!');
                $this->build_captcha();
                $this->mViewFile = 'login';
                return;
            }
        }

        $this->account_dto = $this->account_model->check($usr, $pwd);
        if (is_null($this->account_dto)) {

            set_warn_alert('帳號密碼輸入錯誤!!');
            $this->build_captcha();
            $this->mViewFile = 'login';
            return;
        }
        //判斷離職日小於今日更新account isuse=>0
        $departurdate = $this->account_dto->getDeparturedate();
        if ($departurdate != '' && ($departurdate <= Date('Y-m-d'))) {

            $this->account_model->modify_account($this->account_dto);
            set_warn_alert('帳號密碼輸入錯誤!!');
            $this->build_captcha();
            $this->mViewFile = 'login';
            return;
        }

        $this->process_login($this->account_dto->getGroups_sid());
    }


    private function process_login($groups_sid) {

        if ($this->account_dto) {

            $this->module_dto = $this->module_model->find_module_by_account($groups_sid);
            $account_array = object_to_array($this->account_dto);
            $module_array = object_to_array($this->module_dto);

            $append_array = array_merge($account_array, array("module" => $module_array));
            login_session_user($append_array);
            
            if(!empty($this->input->get('redirect'))){
                redirect(BASE_URL.'/'.urldecode($this->input->get('redirect')));
            }else{
                redirect("dashboard");
            }
        }
    }

    //登出
    public function logout() {

        logout_user();
        session_unset();
        $this->clean_session_captcha();
        redirect("login");
    }

    private function validation_message() {

        $error_message = [];
        $username = $this->input->post('username');
        $password = $this->input->post('password');
//        $this->fb->warn($username, 'username=>');
        if (is_empty_and_not_zero($username)) {

            $error_message[] = '帳號必須輸入!!';
        }

        if (is_empty_and_not_zero($password)) {
            $error_message[] = '密碼必須輸入!!';
        }

//        $this->fb->warn($error_message, 'error_message=>');
        return $error_message;
    }

    //建立驗證碼session
    private function build_captcha() {

        if ($this->is_useverify()) {

            $this->clean_session_captcha();
            //判斷是否要啟用圖形驗證
            $this->mViewData['is_useverify'] = TRUE;
            $setting = captcha_setting('captcha_id', 4, 20);
            $cap = create_captcha($setting);
            $this->mViewData['captcha'] = $cap;
//            $this->fb->warn($cap, 'captcha =>');
            $this->session->set_userdata('captchaWord', $cap['word']);
            return $cap;
        } else {
            $this->mViewData['is_useverify'] = FALSE;
        }
    }

    //清除驗證碼session
    private function clean_session_captcha() {
        $this->session->unset_userdata('captchaWord');
    }

    //判斷是否有登入驗證碼
    private function is_useverify() {

        $this->sysinfo_dto = $this->sysinfo_model->find_sysinfo();
        $this->mViewData['sysinfo_dto'] = $this->sysinfo_dto;
        if (!is_null($this->sysinfo_dto) && !is_null($this->sysinfo_dto->getUseverify()) && (($this->sysinfo_dto->getUseverify()) != 0)) {
            return true;
        }
        return false;
    }
    
    //判斷是否有啟用https
    private function is_usehttps() {
        
        $this->sysinfo_dto = $this->sysinfo_model->find_sysinfo();
        if (!is_null($this->sysinfo_dto) && !is_null($this->sysinfo_dto->getHttpson_end()) && (($this->sysinfo_dto->getHttpson_end()) != 0)) {
            return true;
        }
        return false;
    }

}
