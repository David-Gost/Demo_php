<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * 定義backend libraries DTO 路徑
 */
//define('APPLICATION_BACKEND_DTO_PATH', 'application/libraries/dto/');

/**
 * 使用者自己定義常數
 */
define('ASSETS_BACKEND', 'assets/');

/**
 * 設定FirePHP 是否要開啟
 */
define("FIREPHP_ENABLE", (ENVIRONMENT === 'development') ? TRUE : FALSE);

/**
 * 設置是否要啟用效能分析器
 */
define("PROFILER_ENABLE", (ENVIRONMENT === 'development') ? TRUE : FALSE);

/**
 * 預設的語系
 */
define('DEFAULT_LANG', 'zh_tw');

/**
 * user session key
 */
define('USER_KEY', 'sys_user');


/**
 *
 * 設定使用者名稱session值
 */
define('USER_SESS_CNAME', 'cname');

/**
 *
 * 設定使用者群組值
 */
define('USER_SESS_GROUPSID', 'groups_sid');

/**
 * 定義時區
 */
define('TIMEZONE', 'Asia/Taipei');

/**
 * 定義日期格式
 */
define('DATE_FORMAT', '%Y-%m-%d %H:%i:%s');

define('SUPERVISOR_ID', "1");

/**
 * 加定義常數放入陣列中
 */
$customize = array(
//    APPLICATION_BACKEND_DTO_PATH,
    ASSETS_BACKEND,
    FIREPHP_ENABLE,
    PROFILER_ENABLE,
    USER_KEY,
    DATE_FORMAT,
    USER_SESS_CNAME
);


/**
 * 設定給系統使用
 */
$config['user_constants'] = $customize;

/**
 * 頁面
 */
define('BOOKMARK_ACTION', 'custom/bookmark_manage/');
define("FRONT", 'front/');

/**
 * 日期畫面類型
 */
define("CALENDAR_VIEW_ADD", "add");
define("CALENDAR_VIEW_MODIFY", "modify");

/**
 * api畫面類型 SMS
 */
define("API_SMS", 'sms');

/**
 * api畫面類型 發票
 */
define("API_INVOICE", 'invoive');
/**
 * api畫面類型 Line
 */
define("API_LINE", 'line');

