<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function check_image_file($files) {
    if (!is_uploaded_file($files['tmp_name'])) {
        return false;
    } else {
        switch (strtolower(pathinfo($files['name'], PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
            case 'gif':
            case 'png':
            case 'bmp':
                break;
            default:
                return false;
                break;
        }
        return true;
    }
    return false;
}

function check_document_file($files) {
    if (!is_uploaded_file($files['tmp_name'])) {
        return false;
    } else {
        switch (strtolower(pathinfo($files['name'], PATHINFO_EXTENSION))) {
            case 'doc':
            case 'docx':
            case 'pdf':
                break;
            default:
                return false;
                break;
        }
        return true;
    }
    return false;
}

function check_pdf_file($files) {
    if (!is_uploaded_file($files['tmp_name'])) {
        return false;
    } else {
        switch (strtolower(pathinfo($files['name'], PATHINFO_EXTENSION))) {
            case 'pdf':
                break;
            default:
                return false;
                break;
        }
        return true;
    }
    return false;
}

function check_word_file($files) {
    if (!is_uploaded_file($files['tmp_name'])) {
        return false;
    } else {
        switch (strtolower(pathinfo($files['name'], PATHINFO_EXTENSION))) {
            case 'doc':
            case 'docx':
                break;
            default:
                return false;
                break;
        }
        return true;
    }
    return false;
}

function check_date($date) {
    if (is_empty_and_not_zero($date)) {
        return true;
    } else if (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $date)) {
        list($y, $m, $d) = explode('-', $date);
        if (checkdate($m, $d, $y)) {
            return true;
        }
    }
    return false;
}

function check_email($email) {
    if (is_empty_and_not_zero($email)) {
        return true;
    } else if (preg_match('/^[0-9a-zA-Z_\-\.]+\@[0-9a-zA-Z_\-\.]+$/', $email)) {
        return true;
    }
    return false;
}

function check_url($url) {
    if ($url != '') {
        if (preg_match('/^http[s]{0,1}\:\/\/.+/s', $url)) {
            return true;
        }
    }
    return false;
}

function check_mobile($mobile) {
    if (is_empty_and_not_zero($mobile)) {
        return true;
    } else if (preg_match('/^[0-9]{10}$/', $mobile)) {
        return true;
    }
    return false;
}

function check_mobile_dash($mobile) {
    if (is_empty_and_not_zero($mobile)) {
        return true;
    } else if (preg_match('/^[0-9]{4}-[0-9]{3}-[0-9]{3}$/', $mobile)) {
        return true;
    }
    return false;
}

/* 	
 * 	家用電話格式(含分機)：06-2227777#111
 */

function check_tel_ext($telext) {
    if (is_empty_and_not_zero($telext)) {
        return true;
    } else if (preg_match('/^[0-9]{2,3}\-[0-9]{3,9}\#?[0-9]*$/', $telext)) {
        return true;
    }
    return false;
}

/* 	
 * 	家用電話格式(不含分機)：06-2227777
 */

function check_tel($telext) {
    if (is_empty_and_not_zero($telext)) {
        return true;
    } else if (preg_match('/^[0-9]{2,3}\-[0-9]{3,9}$/', $telext)) {
        return true;
    }
    return false;
}

function check_only_num($onlynum) {
    if (is_empty_and_not_zero($onlynum)) {
        return true;
    } else if (preg_match('/^\d+(\.\d+)?$/', $onlynum)) {
        return true;
    }
    return false;
}

function is_empty_and_not_zero($value) {
    if ((is_null($value) || $value == '' || $value == NULL) && $value != '0') {
        return true;
    }
    return false;
}

//帳號最少要三碼 英數字
function check_account($account) {
    if (is_empty_and_not_zero($account)) {
        
    } else if (preg_match('/^[0-9a-zA-Z]{3,20}$/', $account)) {
        return true;
    }
    return false;
}

//密碼最少要七碼 英數字最少各一
function check_password($password) {
    if (is_empty_and_not_zero($password)) {
        
    } else if (preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{7,}$/', $password)) {
        return true;
    }
    return false;
}

//帳號最少要兩碼 
function check_user_account($account) {
    if (is_empty_and_not_zero($account)) {
        
    } else if (preg_match('/^[\\S]+@[a-zA-z.]+[a-zA-Z]{2,}+$/', $account)) {
        return true;
    }
    return false;
}

//密碼最少要八碼
function check_user_password($password) {
    if (is_empty_and_not_zero($password)) {
        
    } else if (preg_match('/^[\\S]{8,}$/', $password)) {
        return true;
    }
    return false;
}
