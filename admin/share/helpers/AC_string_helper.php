<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function restr_by_tilde($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . " ~ " . $value2;
        }
    }
}

function restr_by_shar($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . " #" . $value2;
        }
    }
}

function restr_by_slash($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . " / " . $value2;
        }
    }
}

function restr_by_br($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . "<br/>" . $value2;
        }
    }
}

function restr_by_title($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $value2;
        }
    }
}

function restr_by_rn_title($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . "\r\n         " . $value2;
        }
    }
}

function restr_by_rn($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . "\r\n" . $value2;
        }
    }
}

function restr_by_ds($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . " - " . $value2;
        }
    }
}

function restr_by_br_tab($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . "<br>&nbsp;&nbsp;&nbsp;" . $value2;
        }
    }
}

function restr_by_brackets($value1, $value2) {
    if (is_null($value1) || $value1 == '' || $value1 == NULL) {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return "";
        } else {
            return $value2;
        }
    } else {
        if (is_null($value2) || $value2 == '' || $value2 == NULL) {
            return $value1;
        } else {
            return $value1 . "(" . $value2 . ")";
        }
    }
}

/**
* 隱藏部分字串
* # 此方法多用於手機號碼或身份證號、銀行卡號的中間部分數字的隱藏
*/
function privacy_str($str, $replacement = '*', $start = 1, $length = 3)
{
    $len = mb_strlen($str,'utf-8');
    if ($len > intval($start+$length)) {
        $str1 = mb_substr($str,0,$start,'utf-8');
        $str2 = mb_substr($str,intval($start+$length),NULL,'utf-8');
    } else {
        $str1 = mb_substr($str,0,1,'utf-8');
        $str2 = mb_substr($str,$len-1,1,'utf-8');
        $length = $len - 2;
    }
    $new_str = $str1;
    for ($i = 0; $i < $length; $i++) {
        $new_str .= $replacement;
    }
    $new_str .= $str2;
    return $new_str;
}

function privacy_email($str, $replacement = '*', $start = 1, $length = 99)
{
    $at_start = strpos($str, '@');
    $str_start = substr($str,0,$at_start);
    $str_end = substr($str,$at_start);
    return privacy_str($str_start, $replacement, $start, $length).$str_end;
}