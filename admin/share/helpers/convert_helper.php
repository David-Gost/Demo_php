<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 轉換金流中心的日期格式為xxxx-xx-xx
 * @param type $tmp_transdate
 * @return string
 */
function convert_transdate($tmp_transdate) {
    if ($tmp_transdate != '') {
        $transdate = mb_substr($tmp_transdate, 0, 4, "utf-8") . "-" . mb_substr($tmp_transdate, 4, 2, "utf-8") . "-" . mb_substr($tmp_transdate, 6, 2, "utf-8");
    }
    return $transdate;
}


/**
 * 轉換手機格式為xxxx-xxx-xxx
 * @param string $mobile
 * @return string
 */
function convert_mobile($mobile) {
    if (preg_match('/^[0-9]{10}$/', $mobile)) {
        $mobile = mb_substr($mobile, 0, 4, "utf-8") . "-" . mb_substr($mobile, 4, 3, "utf-8") . "-" . mb_substr($mobile, 7, 3, "utf-8");
        return $mobile;
    }
    return $mobile;
}
