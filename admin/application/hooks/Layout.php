<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of layout
 *
 * @author jingyu1009
 * 
 * 
 */
class Layout extends CI_Hooks {

    function show_layout() {
        global $OUT;

        $CI = & get_instance();
        $output = $CI->output->get_output();

        if (isset($CI->mLayout)) {
            // load layout (APPPATH = "applications/backend/")
            $layout_name = $CI->mLayout;

            $layout_path = APPPATH . 'views/_layouts/' . $layout_name . '.php';
            $layout = $CI->load->file($layout_path, true);

            // change string "{body}" from view output
            $layout = str_replace("{body}", $output, $layout);

            // add title, define in controllers
            $title = isset($CI->mTitle) ? $CI->mTitle : NULL;
            // change string "{{title}}" with paremeter on controller
            $layout = str_replace("{{title}}", $title, $layout);
        } else {
            // output without layout
            $layout = $output;
        }

        /* @var $OUT <type> */
        $OUT->_display($layout);
    }

}
