<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | This file lets you define "hooks" to extend CI without hacking the core
  | files.  Please see the user guide for info:
  |
  |	https://codeigniter.com/user_guide/general/hooks.html
  |
 */

$hook["post_controller"][] = array(
    "class" => "",
    "function" => "meta_seo_override",
    "filename" => "Meta_seo.php",
    "filepath" => "hooks"
);

if (ENVIRONMENT == "testing") {
    $hook["post_controller"][] = array(
        "class" => "",
        "function" => "add_demo_mark",
        "filename" => "Demo_mark.php",
        "filepath" => "../admin/share/hooks"
    );
}

if (ENVIRONMENT == "production") {
    $hook["post_controller"][] = array(
        "class" => "",
        "function" => "setGoogleAnalytics",
        "filename" => "GoogleAnalytics.php",
        "filepath" => "hooks"
    );
}