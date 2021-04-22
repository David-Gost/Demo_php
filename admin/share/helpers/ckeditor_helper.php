<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function build_ckeditor($view_id = 'content') {

    $CI = get_instance();
    $CI->load->model("custom/Editer_model", "editer_model");

    $is_admin = get_seesion_user()['sid'] == 1 ? 1 : 2;

    $remove_plugins_array = $CI->editer_model->find_user_editer($is_admin, 0);
    $remove_buttons_array = $CI->editer_model->find_user_editer($is_admin, 1);

    if (count($remove_plugins_array) == 0) {
        $remove_plugins = '';
    } else {
        $remove_plugins = implode(',', array_column(object_to_array($remove_plugins_array), 'nick_name'));
    }

    if (count($remove_buttons_array) == 0) {
        $remove_buttons = '';
    } else {
        $remove_buttons = implode(',', array_column(object_to_array($remove_buttons_array), 'nick_name'));
    }

    $edit_script = '<script>
            
                        CKEDITOR.replace(\''.$view_id.'\', {
                            filebrowserImageUploadUrl: \''.BASE_URL.'/ajax/ajax_upload_file/upload_image\',
                            filebrowserUploadUrl: \''.BASE_URL.'/ajax/ajax_upload_file/upload_file\',
                            removePlugins:\''.$remove_plugins.'\',removeButtons:\''.$remove_buttons.'\'
                        });
                       
                        $(document).ready(function () {
                            $("#text_bookmark").on("submit", function () {
                                var hvalue = CKEDITOR.instances.content.getData();
                                $(this).append("<input type=\'hidden\' name=\'content\' value=\' " + hvalue + " \'/>");
                            });
                                                    
                             
                        });

                    </script>';

    return $edit_script;
}
