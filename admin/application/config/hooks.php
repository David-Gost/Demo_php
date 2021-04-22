<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | This file lets you define "hooks" to extend CI without hacking the core
  | files.  Please see the user guide for info:
  |
  |	http://codeigniter.com/user_guide/general/hooks.html
  |
 */



//$hook['pre_system'] = array(
//    'class' => 'Uri',
//    'function' => 'strtolower',
//    'filename' => 'Uri.php',
//    'filepath' => 'hooks',
//);
// for additional viewdata called after controller functions (e.g. add breadcrumb, render output)

$hook['post_controller'][] = array(
    'class' => 'Controller',
    'function' => 'add_view_data',
    'filename' => 'Controller.php',
    'filepath' => 'hooks'
);

// for rendering layout
$hook['display_override'][] = array(
    'class' => 'Layout',
    'function' => 'show_layout',
    'filename' => 'Layout.php',
    'filepath' => 'hooks'
);

if (ENVIRONMENT == "testing") {
    $hook["post_controller"][] = array(
        "class" => "",
        "function" => "add_demo_mark",
        "filename" => "Demo_mark.php",
        "filepath" => "../share/hooks"
    );
}