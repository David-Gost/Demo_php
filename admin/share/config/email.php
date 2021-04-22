<?php

defined('BASEPATH') or exit('No direct script access allowed.');

$config['useragent'] = 'PHPMailer';                 // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
$config['protocol'] = 'smtp';                       // 'mail', 'sendmail', or 'smtp'
$config['mailpath'] = '/usr/sbin/sendmail';
switch (ENVIRONMENT) {
    case 'development':
        $config['smtp_host'] = '';     //設定smtp host
        break;
    case 'testing':
        $config['smtp_host'] = '';     //設定smtp host
        break;
    case 'production':
        $config['smtp_host'] = '';     //設定smtp host
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}

$config['smtp_user'] = '';                          //登入smtp的帳號
$config['smtp_pass'] = '';                          //登入smtp的密碼
$config['smtp_port'] = 25;
$config['smtp_timeout'] = 30;                        // (in seconds)
$config['smtp_crypto'] = '';                        // '' or 'tls' or 'ssl'
$config['smtp_debug'] = 0;                          // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data, 3 = as 2 plus connection status, 4 = low level data output.
$config['wordwrap'] = true;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';                       // 'text' or 'html'
$config['charset'] = 'UTF-8';                       // 'UTF-8', 'ISO-8859-15', ...; NULL (preferable) means config_item('charset'), i.e. the character set of the site.
$config['validate'] = true;
$config['priority'] = 3;                            // 1, 2, 3, 4, 5; on PHPMailer useragent NULL is a possible option, it means that X-priority header is not set at all, see https://github.com/PHPMailer/PHPMailer/issues/449
$config['crlf'] = "\r\n";                             // "\r\n" or "\n" or "\r"
$config['newline'] = "\r\n";                          // "\r\n" or "\n" or "\r"
$config['bcc_batch_mode'] = false;
$config['bcc_batch_size'] = 200;
$config['encoding'] = '8bit';                       // The body encoding. For CodeIgniter: '8bit' or '7bit'. For PHPMailer: '8bit', '7bit', 'binary', 'base64', or 'quoted-printable'.

$CI = get_instance();
if ($CI->db->table_exists('sys_smtp')) {
    //先檢查有沒有這些欄位
    $CI->load->model("sys/Smtp_model", "smtp_model");
    
    $mail_config = $CI->smtp_model->get_smtp(['sid' => 1], [], 'obj');

    $have_all_fields = 5;
    if (!empty($mail_config)) {
        foreach ($mail_config as $key => $value) {
            if ($key == 'smtp_host' || $key == 'smtp_user' || $key == 'smtp_pass' || $key == 'smtp_port' || $key == 'smtp_crypto') {
                $have_all_fields = $have_all_fields - 1;
            }
            if ($have_all_fields <= 0) {
                break;
            }
        }
    }

    //若有從後台手動設定email config，則以後台的為主
    if (!empty($mail_config) && $have_all_fields <= 0) {
        if (!empty($mail_config)) {
            $_cfg = $mail_config;
            $config['smtp_host'] = ($_cfg->smtp_host != '' ? $_cfg->smtp_host : $config['smtp_host']);
            $config['smtp_user'] = ($_cfg->smtp_user != '' ? $_cfg->smtp_user : $config['smtp_user']);
            $config['smtp_pass'] = ($_cfg->smtp_pass != '' ? $_cfg->smtp_pass : $config['smtp_pass']);
            $config['smtp_port'] = ($_cfg->smtp_port != '' ? $_cfg->smtp_port : $config['smtp_port']);
            $config['smtp_crypto'] = ($_cfg->smtp_crypto != '' ? $_cfg->smtp_crypto : $config['smtp_crypto']);
        }
    }
}
