<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function object_to_array($obj) {

    if (is_object($obj)) {
        $obj = (array) $obj;
    }
    if (is_array($obj)) {
        $new = array();
        foreach ($obj as $key => $val) {
            if (strpos($key, '*') > 0) {
                $new[\str_replace('*', '', $key)] = object_to_array($val);
            } else {
                $new[$key] = object_to_array($val);
            }
        }
    } else {
        $new = $obj;
    }
    return $new;
}

function array_to_object($array) {
    $object = new stdClass();

    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $object->$key = $value;
        }
    }

    return $object;
}

function array_to_string($array, $symbol) {

    if (count($array) > 0) {
        $string = implode($symbol, $array);
    } else {
        $string = '';
    }

    return $string;
}

//轉一維陣列
function array_to_array($array, $key_name) {
    $new_array = [];
    foreach ($array as $key => $value) {
        $new_array[] = $value[$key_name];
    }
    return $new_array;
}
