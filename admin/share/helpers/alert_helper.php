<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper class to handle alerts
 */
// Alert box
function alert_box() {
    $alert = get_alert();

    if (!empty($alert)) {
        $type = $alert['type'];
        $msg = $alert['msg'];
        return "<div id='alert_box' class='alert alert-dismissable alert-$type'  style='margin-left:0'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h4><i class='icon fa fa-info'></i> Alert!</h4>$msg</div>
        <script>
        setTimeout(function(){
            $('#alert_box').hide();
        },5000);
        </script>
        ";
    }
}

// Callout box
// Remark: AdminLTE has no styling for "success" type
function callout_box() {
    $alert = get_alert();

    if (!empty($alert)) {
        $type = $alert['type'];
        $msg = $alert['msg'];
        $title = empty($alert['title']) ? '' : "<h4>" . $alert['title'] . "</h4>";
        return "<div class='callout callout-$type'>$title<p>$msg</p></div>";
    }
}

// Save to flashdata
// Remark: title is optional and only display in callout box
function set_alert($type, $msg, $title = '') {
    $CI = & get_instance();
    $CI->session->set_flashdata('alert_type', $type);
    $CI->session->set_flashdata('alert_title', $title);
    $CI->session->set_flashdata('alert_msg', $msg);
}

function set_warn_alert($msg, $title = '') {

    $msgstr = msg_array_to_str($msg);
    $CI = & get_instance();
    $CI->session->set_flashdata('alert_type', 'warning');
    $CI->session->set_flashdata('alert_title', $title);
    $CI->session->set_flashdata('alert_msg', $msgstr);
}

function set_info_alert($msg, $title = '') {

    $msgstr = msg_array_to_str($msg);
    $CI = & get_instance();
    $CI->session->set_flashdata('alert_type', 'info');
    $CI->session->set_flashdata('alert_title', $title);
    $CI->session->set_flashdata('alert_msg', $msgstr);
}

function set_danger_alert($msg, $title = '') {
    $CI = & get_instance();
    $CI->session->set_flashdata('alert_type', 'danger');
    $CI->session->set_flashdata('alert_title', $title);
    $CI->session->set_flashdata('alert_msg', $msg);
}

function set_success_alert($msg, $title = '') {

    $msgstr = msg_array_to_str($msg);
    $CI = & get_instance();
    $CI->session->set_flashdata('alert_type', 'success');
    $CI->session->set_flashdata('alert_title', $title);
    $CI->session->set_flashdata('alert_msg', $msgstr);
}

// Get from flashdata
function get_alert() {
    $CI = & get_instance();
    $type = $CI->session->flashdata('alert_type');
    $title = $CI->session->flashdata('alert_title');
    $msg = $CI->session->flashdata('alert_msg');

    if (!empty($type) && !empty($msg)) {
        return array('type' => $type, 'msg' => $msg);
    } else {
        return NULL;
    }
}

function msg_array_to_str($msg) {
    $msgstr = "";
    if (is_array($msg)) {
        for ($i = 0; $i < count($msg); $i++) {
            $msgstr .= "★ " . $msg[$i] . '<br/>';
        }
    } else {
        $msgstr = $msg;
    }
    return $msgstr;
}
