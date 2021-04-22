<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 定義專案名稱
 */
define('PROJECT_NAME', 'kdan_test');

/**
 * 定義share 路徑
 */
define('SHARE_PATH', BASEPATH . '../share/');

/**
 * 定義backend libraries DTO 路徑
 */
define('APPLICATION_BACKEND_DTO_PATH', SHARE_PATH . 'libraries/dto/');

define('VENDOR_PATH', '../vendor/');

//----前後台分頁參數
define('FRONT_URI_SEGMENT', 3);
define('FRONT_LIMIT', 10);
define('END_URI_SEGMENT', 4);
define('END_LIMIT', 20);

// 回傳狀態
define('API_RESULT_SUC', "suc");
define('API_RESULT_ERROR', "error");

define("WEEK_ARRAY",array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'));
