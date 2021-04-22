<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Hook: post_controller
 * @property Fb $fb
 */
class Controller extends CI_Hooks {

    function add_view_data() {

        $CI = & \get_instance();

        $CI->load->model('sys/Sysinfo_model', 'sysinfo_model');
        $CI->load->library('dto/Sysinfo_dto', 'sysinfo_dto');
        $CI->mViewData['sysinfo_dto'] = $CI->sysinfo_model->find_sysinfo();

        $ctrler = $CI->router->fetch_class();
        if (strpos($ctrler, 'ajax') === FALSE) {


            // only for pages after login
            if ($ctrler != 'login') {
                // fallback when mTitle is not set / empty
                if (empty($CI->mTitle)) {
                    // take controller or action name as title
                    if ($CI->mAction === 'index') {
                        $CI->mTitle = humanize($CI->mCtrler);
                    } else {
                        $CI->mTitle = humanize($CI->mAction);
                    }
                }

                // fallback when mViewFile is not set
                if (empty($CI->mViewFile)) {
                    if ($CI->mAction === 'index') {
                        $CI->mViewFile = $CI->mCtrler;
                    } else {
                        $CI->mViewFile = $CI->mCtrler . '/' . $CI->mAction;
                    }
                }

                if ($ctrler != 'home') {
                    // add an "active" entry at the end of breadcrumb (based on mTitle)
                    $CI->mBreadcrumb[] = array('name' => $CI->mTitle);
                }

                // push to mViewData before rendering
                $CI->mViewData['breadcrumb'] = $CI->mBreadcrumb;
            }

            // render output
            $view_data = empty($CI->mViewData) ? NULL : $CI->mViewData;

            if (!empty($CI->mViewFile)) {
                $CI->load->view($CI->mViewFile, $view_data);
            }
        }
    }

}
