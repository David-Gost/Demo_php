<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * 使用者自己定義常數
 */
define('ASSETS_BACKEND', 'assets/');


/**
 * 預設的語系
 */
define('DEFAULT_LANG', 'zh_tw');

/**
 * user session key
 */
define('USER_KEY', 'sys_user_front');


/**
 * 定義時區
 */
define('TIMEZONE', 'Asia/Taipei');

/**
 * 定義日期格式
 */
define('DATE_FORMAT', '%Y-%m-%d %H:%i:%s');


/**
 * 加定義常數放入陣列中
 */
$customize = array(
    ASSETS_BACKEND,
    DEFAULT_LANG,
    USER_KEY,
    TIMEZONE,
    DATE_FORMAT
);


/**
 * 設定給系統使用
 */
$config['user_constants'] = $customize;


/**
 * 前台url使用
 */
define("FRONT", 'front/');


/**
 * Google
 */
define("Google_app_name", '');
define("Google_app_id", '');
define("Google_app_Secret", '');


//智付通設定
switch (ENVIRONMENT) {
    case 'development'://公司測試
    case 'testing'://公司測試    
        define("Test_model", true);
        define("MerchantID", 'MS32193755');
        define("HashKey", '9d57qNAEEz1hboNiBzJrxt0zjYUbQOw0');
        define("HashIV", '1B7rpH63g7ksw8L8');
        break;
    case 'production'://公司正式
        define("Test_model", false);
        define("MerchantID", '');
        define("HashKey", '');
        define("HashIV", '');
        break;
}

//LinePay設定
switch (ENVIRONMENT) {
    case 'development'://公司測試
    case 'testing'://公司測試    
        define("Test_model_line", true);
        define("Channel_ID", '1593324242');
        define("Channel_Secret_Key", 'b97ad9118a7a5610a660f9aa6381496c');
        break;
    case 'production'://正式
        define("Test_model_line", true);
        define("Channel_ID", '');
        define("Channel_Secret_Key", '');
        break;
}


