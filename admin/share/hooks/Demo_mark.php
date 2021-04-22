<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function add_demo_mark()
{
    $CI = &get_instance();
    $uri_string = $CI->uri->uri_string();
    $find_ajax_string = strpos($uri_string, "ajax");
    if ($find_ajax_string === false) {
        $data = "<div style='position: fixed;left: calc(48% - 4vw);bottom:0px;font-family: Roboto,Helvetica,Arial,sans-serifsans-serif;font-size: 4vw;font-weight: 900;line-height: normal;text-shadow: 0px 0px 10px white;color: red;opacity: 0.4;z-index:10000;pointer-events: none;'>DEMO</div>";
        $CI->output->append_output($data);
    }
}
