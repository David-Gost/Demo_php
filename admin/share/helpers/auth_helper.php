<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Store user data to session
function login_session_user($user) {
    if (is_object($user)) {
        $user = object_to_array($user);
    }elseif (!is_array($user)) {
        $user = [$user,'cname'=>''];
    }
    if (!array_key_exists('cname',$user)) {
        $user['cname'] = '';
    }
    $CI = & get_instance();
    $CI->session->set_userdata(USER_KEY, $user);
}

// Get user data from session
function get_seesion_user() {
    $CI = & get_instance();
    return $CI->session->userdata(USER_KEY);
}

// Partially update user data in current session
function refresh_user($data) {
    $CI = & get_instance();
    $user = get_user();
    foreach ($data as $key => $value) {
        $user[$key] = $value;
    }

    // store updated users to session
    $CI->session->set_userdata(USER_KEY, $user);
}

// Destroy user data from session
function logout_user() {
    $CI = & get_instance();
    $CI->session->unset_userdata(USER_KEY);
    //  $CI->session->sess_destroy();
}
function set_session_data($key, $value)
{

    $CI = &get_instance();
    if (is_null($value)) {
        $result = $CI->session->userdata($key);
    } else {
        $CI->session->set_userdata($key, $value);
        $result = $value;
    }

    return $result;
}


/**
 * <p>取得session 資料</p>
 * @param type $key
 * <p>session key</p>
 * @return type
 */
function get_seesion_by_key($key)
{
    $CI = &get_instance();
    return $CI->session->userdata($key);
}

/**
 * <p>清除session 資料</p>
 * @param type $key
 * <p>session key</p>
 */
function del_sesstion_by_key($key)
{
    $CI = &get_instance();
    $CI->session->unset_userdata($key);
}