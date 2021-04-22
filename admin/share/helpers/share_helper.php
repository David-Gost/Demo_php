<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function share_url_by_community($type, $now_url = '') {

    if ($now_url == '') {
        $now_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    switch ($type) {
        case 'fb':
            echo 'https://www.facebook.com/share.php?u=' . $now_url;
            break;
        case 'google':
            echo 'https://plus.google.com/share?url=' . $now_url;
            break;
        case 'twitter':
            echo 'https://twitter.com/intent/tweet?url=' . $now_url;
            break;
        case 'line':
            echo 'line://msg/text/' . $now_url;
            break;
        default :
            break;
    }
}
