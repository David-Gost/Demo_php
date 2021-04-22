<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setGoogleAnalytics()
{
    $CI = &get_instance();
    $uri_string = $CI->uri->uri_string();
    $find_ajax_string = strpos($uri_string, "ajax");
    if ($find_ajax_string === false) {
        $config_paths = array(APPPATH);
        foreach ($config_paths as $path) {
            if (is_file($path . 'config/google_analytics.php')) {
                $ga = $CI->config->load('google_analytics');
                $ga = $CI->config->item('google_analytics');

                $CI->output->append_output($ga);
                return;
            }
        }
    }
}
