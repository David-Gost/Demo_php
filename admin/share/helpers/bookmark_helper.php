<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function cms_src_conver($content) {
    $http_host = $_SERVER['HTTP_HOST'];

    $host_flag = preg_match('/demo.acubedt.com/', $http_host) || preg_match('/localhost/', $http_host) || preg_match('/127.0.0.1/', $http_host);

    $preg = "/[A-Za-z\/_]*[\/admin]*\/resource\//";
    $flag = preg_match($preg, $content);

    if ($host_flag) {
        if ($flag) {
            $project_name = __FILE__;
            for ($i = 0; $i < 4; $i++) {
                $project_name = dirname($project_name);
            }
            $project_name = basename($project_name);

            $replacement = "/$project_name/admin/resource/";
            $content = preg_replace($preg, $replacement, $content);
        }
    } else {
        if (preg_match('/^manage/', $http_host)) {
            if ($flag) {
                $replacement = '/resource/';
                $content = preg_replace($preg, $replacement, $content);
            }
        } else {
            if ($flag) {
                $replacement = '/admin/resource/';
                $content = preg_replace($preg, $replacement, $content);
            }
        }
    }

    $content = change_cms_iframe(urldecode($content));
    $content = change_cms_table(urldecode($content));

    return urldecode($content);
}

//----表格修改
function change_cms_table($content) {

    $table_start = '/<table/';
    $table_end = '/<\/table>/';

    $table_start_flag = preg_match($table_start, $content);
    $table_end_flag = preg_match($table_end, $content);

    if ($table_start_flag && $table_end_flag) {

        $replacement = '<div class="cms_tablearea">
                <p class="table-rwd_mobile">(表格可左右滑動)</p>
                
                <div class="cms_table-rwd"><table';
        $content = preg_replace($table_start, $replacement, $content);
        $content = preg_replace($table_end, '</table></div></div>', $content);
    }
    return urlencode($content);
}

//----iframe修改
function change_cms_iframe($content) {

    $iframe_start = '/<iframe/';
    $iframe_end = '/<\/iframe>/';

    $iframe_start_flag = preg_match($iframe_start, $content);
    $iframe_end_flag = preg_match($iframe_end, $content);

    if ($iframe_start_flag !== false) {
//        $replacement = '<div style="position: relative;padding-bottom: 56.25%;"><iframe style="position: absolute;width: 100%;height: 100%;"';
        $replacement = '<div class="videoWrapper"><iframe';
        $content = preg_replace($iframe_start, $replacement, $content);
    }

    if ($iframe_end_flag !== false) {
        $replacement = '</iframe></div>';
        $content = preg_replace($iframe_end, $replacement, $content);
    }

    return $content;
}

//----圖片模式內容

function bookmark_type_image_content($bookmark_type_dto) {

    $view = '<div class="cms_view_area">';
    $picinfo_array = $bookmark_type_dto->child_bookmark;
    $pic_view = "";
    switch ($bookmark_type_dto->child_type) {
        //----針對舊版圖片模式
        default :
            if (!empty($bookmark_type_dto->picinfo)) {
                $pic_view .= "<img class=\"img-responsive\" alt='" . $bookmark_type_dto->picinfo_description . "' title='" . $bookmark_type_dto->picinfo_description . "' src='" . base_url(get_fullpath_with_file($bookmark_type_dto->picinfo)) . "' style=''/>";
                if (empty($bookmark_type_dto->picinfo_description)) {
                    $pic_view .= '<p>' . $bookmark_type_dto->picinfo_description . '</p>';
                }
            }

            if (!empty($bookmark_type_dto->link_url)) {
                $target = $bookmark_type_dto->is_blank ? "target='_blank'" : '';
                $pic_view = '<a href="' . $bookmark_type_dto->link_url . '"' . $target . '>' . $pic_view . '</a>';
            }

            break;

        case EDITER_PICTURE_ONE:

            if (!empty($picinfo_array[0]->picinfo)) {
                $pic_view .= "<img class=\"img-responsive\" alt='" . $picinfo_array[0]->picinfo_description . "' title='" . $picinfo_array[0]->picinfo_description . "' src='" . base_url(get_fullpath_with_file($picinfo_array[0]->picinfo)) . "' style=''/>";
                if (empty($picinfo_array[0]->picinfo_description)) {
                    $pic_view .= '<p>' . $picinfo_array[0]->picinfo_description . '</p>';
                }
            }

            if (!empty($picinfo_array[0]->link_url)) {
                $target = $picinfo_array[0]->is_blank ? "target='_blank'" : '';
                $pic_view = '<a href="' . $picinfo_array[0]->link_url . '"' . $target . '>' . $pic_view . '</a>';
            }



            break;

        case EDITER_PICTURE_TWO:
        case EDITER_PICTURE_THERR:
        case EDITER_PICTURE_FOUR:
            $image_class = 'class="img-responsive"';
            switch ($bookmark_type_dto->child_type) {
                case EDITER_PICTURE_TWO:

                    $max_count = 2;
                    $image_div = 'col-cms_gridsm-6 col-cms_gridmd-6';

                    break;

                case EDITER_PICTURE_THERR:
                    $max_count = 3;
                    $image_div = 'col-cms_gridxs-12 col-cms_gridsm-6 col-cms_gridmd-4';

                    break;
                case EDITER_PICTURE_FOUR:

                    $max_count = 4;
                    $image_div = 'col-cms_gridxs-12 col-cms_gridsm-6 col-cms_gridmd-3';

                    break;
            }


            for ($i = 0; $i < $max_count; $i++) {
                $pic_view .= '<div class="' . $image_div . '">';

                if (!empty($picinfo_array[$i]->picinfo)) {

                    $pic_view .= "<img $image_class alt='" . $picinfo_array[$i]->picinfo_description . "' title='" . $picinfo_array[$i]->picinfo_description . "' src='" . base_url(get_fullpath_with_file($picinfo_array[$i]->picinfo)) . "' style=''/>";
                    if (empty($picinfo_array[$i]->picinfo_description)) {
                        $pic_view .= '<p>' . $picinfo_array[$i]->picinfo_description . '</p>';
                    }
                }
                $pic_view .= '</div>';

                if (!empty($picinfo_array[$i]->link_url)) {
                    $target = $picinfo_array[$i]->is_blank ? "target='_blank'" : '';
                    $pic_view = '<a href="' . $picinfo_array[$i]->link_url . '"' . $target . '>' . $pic_view . '</a>';
                }
            }

            $pic_view = '<div class="cms_gridrow">' . $pic_view . '</div>';


            break;
        case EDITER_PICTURE_RIGHT_HIDE:

            $pic_view .= '<div class="cms_gridrow">';
            $pic_view .= '<div class="col-cms_gridxs-12 col-cms_gridsm-6 col-cms_gridmd-6">';

            $pic_cache_view = '';

            if (!empty($picinfo_array[0]->picinfo)) {
                $pic_cache_view .= "<img class=\"img-responsive\" alt='" . $picinfo_array[0]->picinfo_description . "' title='" . $picinfo_array[0]->picinfo_description . "' src='" . base_url(get_fullpath_with_file($picinfo_array[0]->picinfo)) . "' style=''/>";

                if (!empty($picinfo_array[0]->link_url)) {
                    $target = $picinfo_array[0]->is_blank ? "target='_blank'" : '';
                    $pic_cache_view = '<a href="' . $picinfo_array[0]->link_url . '"' . $target . '>' . $pic_cache_view . '</a>';
                }

                if (empty($picinfo_array[0]->picinfo_description)) {
                    $pic_cache_view .= '<p>' . $picinfo_array[0]->picinfo_description . '</p>';
                }
            }
            $pic_view .= $pic_cache_view;


            $pic_view .= '    </div>
                   </div>';

            break;
        case EDITER_PICTURE_LEFT_HIDE:


            $pic_view .= '<div class="cms_gridrow">';
            $pic_view .= '<div class="col-cms_gridxs-12 col-cms_gridsm-6 col-cms_gridmd-6 col-cms_gridmd-offset-6">';

            $pic_cache_view = '';
            if (!empty($picinfo_array[0]->picinfo)) {
                $pic_cache_view .= "<img class=\"img-responsive\" alt='" . $picinfo_array[0]->picinfo_description . "' title='" . $picinfo_array[0]->picinfo_description . "' src='" . base_url(get_fullpath_with_file($picinfo_array[0]->picinfo)) . "' style=''/>";

                if (!empty($picinfo_array[0]->link_url)) {
                    $target = $picinfo_array[0]->is_blank ? "target='_blank'" : '';
                    $pic_cache_view = '<a href="' . $picinfo_array[0]->link_url . '"' . $target . '>' . $pic_cache_view . '</a>';
                }

                if (empty($picinfo_array[0]->picinfo_description)) {
                    $pic_cache_view .= '<p>' . $picinfo_array[0]->picinfo_description . '</p>';
                }
            }

            $pic_view .= $pic_cache_view;

            $pic_view .= '    </div>
                   </div>';

            break;
        case EDITER_PICTURE_LEFT_TEXT:

            $pic_view .= '<div class="cms_gridside_area">';



            $pic_cache_view = '';

            if (!empty($picinfo_array[0]->picinfo)) {
                $pic_cache_view .= "<img class=\"img-responsive cme_imgside_right\" alt='" . $picinfo_array[0]->picinfo_description . "' title='" . $picinfo_array[0]->picinfo_description . "' src='" . base_url(get_fullpath_with_file($picinfo_array[0]->picinfo)) . "' style=''/>";

                if (!empty($picinfo_array[0]->link_url)) {
                    $target = $picinfo_array[0]->is_blank ? "target='_blank'" : '';
                    $pic_cache_view = '<a href="' . $picinfo_array[0]->link_url . '"' . $target . '>' . $pic_cache_view . '</a>';
                }

                if (empty($picinfo_array[0]->picinfo_description)) {
                    $pic_cache_view .= '<p>' . $picinfo_array[0]->picinfo_description . '</p>';
                }
            }
            $pic_view .= $pic_cache_view;

            $pic_view .= '<div class="wrap_video_left">'.cms_src_conver($bookmark_type_dto->content).'</div>';
            $pic_view .= '</div>';
            break;
        case EDITER_PICTURE_RIGHT_TEXT:

            $pic_cache_view = '';
            $pic_view .= '<div class="cms_gridside_area">';



            if (!empty($picinfo_array[0]->picinfo)) {
                $pic_cache_view .= "<img class=\"img-responsive cme_imgside_left\" alt='" . $picinfo_array[0]->picinfo_description . "' title='" . $picinfo_array[0]->picinfo_description . "' src='" . base_url(get_fullpath_with_file($picinfo_array[0]->picinfo)) . "' style=''/>";

                if (!empty($picinfo_array[0]->link_url)) {
                    $target = $picinfo_array[0]->is_blank ? "target='_blank'" : '';
                    $pic_cache_view = '<a href="' . $picinfo_array[0]->link_url . '"' . $target . '>' . $pic_cache_view . '</a>';
                }

                if (empty($picinfo_array[0]->picinfo_description)) {
                    $pic_cache_view .= '<p>' . $picinfo_array[0]->picinfo_description . '</p>';
                }
            }

            $pic_view .= $pic_cache_view;
            $pic_view .= '<div class="wrap_video_right">'.cms_src_conver($bookmark_type_dto->content).'</div>';
            $pic_view .= '</div>';

            break;
    }
    $view .= $pic_view;

    $view .= '</div>';

    return $view;
}

//----cms內容(回傳字串)
function bookmark_type_string($bookmark_type_dto) {
    $text='';
    switch ($bookmark_type_dto->type):
        case 1:
            $text .= cms_src_conver($bookmark_type_dto->content);
            break;
        case 2:
            $text .= bookmark_type_image_content($bookmark_type_dto);
            break;
        case 3:
            $text .= '<div class="videoWrapper">';
            $text .= $bookmark_type_dto->iframe;
            $text .= '</div>';
            break;
        case 4:
            $text .= "<a href=" . base_url(get_fullpath_with_file($bookmark_type_dto->file_info)) . " alt=" . $bookmark_type_dto->file_name . " title=" . $bookmark_type_dto->file_name . "><button type='button' class='btn btn-primary btn-md bg-blue-gradient fa fa-file'>&nbsp;" . $bookmark_type_dto->file_name . "</button></a><br><br>";
            break;
    endswitch;

    return $text;
}

//----cms內容(直接印出)
function bookmark_type_show($bookmark_type_dto) {
    switch ($bookmark_type_dto->type):
        case 1:
            echo cms_src_conver($bookmark_type_dto->content);
            break;
        case 2:

            echo bookmark_type_image_content($bookmark_type_dto);

            break;
        case 3:
            echo '<div class="videoWrapper">';
            echo $bookmark_type_dto->iframe;
            echo '</div>';
            break;
        case 4:
            echo "<a href=" . base_url(get_fullpath_with_file($bookmark_type_dto->file_info)) . " alt=" . $bookmark_type_dto->file_name . " title=" . $bookmark_type_dto->file_name . "><button type='button' class='btn btn-primary btn-md bg-blue-gradient fa fa-file'>&nbsp;" . $bookmark_type_dto->file_name . "</button></a><br><br>";
            break;
    endswitch;
}

//----前台顯示（直接印出）
/**
 * 
 * @param type array OR objecy $obj
 */
function bookmark_list_show($obj,$add_csm_ared = true) {
    if ($add_csm_ared) {
        echo '<div class="cms_area">';
    }

    if (is_array($obj)) {
        foreach ($obj as $bookmark_dto) {
            foreach ($bookmark_dto->bookmark_type_list as $bookmark_type_dto) {
                bookmark_type_show($bookmark_type_dto);
            }
        }
    } else {
        foreach ($obj->bookmark_type_list as $bookmark_type_dto) {
            bookmark_type_show($bookmark_type_dto);
        }
    }

    if ($add_csm_ared) {
        echo '</div>';
    }
}

//----前台顯示（回傳string）
function bookmark_list_show_for_ajax($obj,$add_csm_ared = true) {

    $text = "";
    if ($add_csm_ared) {
        $text .= '<div class="cms_area">';
    }

    if (is_array($obj)) {
        foreach ($obj as $bookmark_dto) {
            foreach ($bookmark_dto->bookmark_type_list as $bookmark_type_dto) {
                $text .= bookmark_type_string($bookmark_type_dto);
            }
        }
    } else {
        foreach ($obj->bookmark_type_list as $bookmark_type_dto) {
            $text .= bookmark_type_string($bookmark_type_dto);
        }
    }

    if ($add_csm_ared) {
        $text .= '</div>';
    }
    
    return $text;
}

function simple_load_bookmark($table_cname,$table_sid)
{
    $CI = & \get_instance();
    $CI->load->model('custom/Bookmark_model', 'bookmark_model');
    return $CI->bookmark_model->find_bookmark_and_bookmark_type_by_table_cname_and_table_sid($table_cname, $table_sid);
}