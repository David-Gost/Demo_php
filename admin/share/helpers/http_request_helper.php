<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function input_post($key) {
    $ci = get_instance();

    return $ci->input->post($key);
}

function input_get($key) {

    $ci = get_instance();

    return $ci->input->get($key);
}

function input_path($key) {

    

    return $ci->input->get($key);
}
