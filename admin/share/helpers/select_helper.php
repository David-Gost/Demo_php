<?php

if (!function_exists('form_dropdown_multi')) {

    /**
     * Drop-down Menu
     *
     * @param	mixed	$data
     * @param	mixed	$options
     * @param	mixed	$selected
     * @param	mixed	$extra
     * @param       mixed   $parent_disabled
     * @return	string
     */
    function form_dropdown_multi($data = '', $options = array(), $selected = array(), $extra = '', $disabled = '') {
        $defaults = array();

        if (is_array($data)) {
            if (isset($data['selected'])) {
                $selected = $data['selected'];
                unset($data['selected']); // select tags don't have a selected attribute
            }

            if (isset($data['options'])) {
                $options = $data['options'];
                unset($data['options']); // select tags don't use an options attribute
            }
        } else {
            $defaults = array('name' => $data);
        }

        is_array($selected) OR $selected = array($selected);
        is_array($options) OR $options = array($options);

        // If no selected state was submitted we will attempt to set it automatically
        if (empty($selected)) {
            if (is_array($data)) {
                if (isset($data['name'], $_POST[$data['name']])) {
                    $selected = array($_POST[$data['name']]);
                }
            } elseif (isset($_POST[$data])) {
                $selected = array($_POST[$data]);
            }
        }

        $extra = _attributes_to_string($extra);

        $disabled = _attributes_to_string($disabled);

        $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select ' . rtrim(_parse_form_attributes($data, $defaults)) . $extra . $multiple . ">\n";

        $form .= '<option value="0" >----選擇分類----</option>';

        foreach ($options as $classification_dto) {
            if ($classification_dto->nlevel == 0) {
                $form .= '<option value="' . html_escape($classification_dto->sid) . '" data-level="' . html_escape($classification_dto->nlevel + 1) . '" ' . $disabled
                        . (in_array($classification_dto->sid, $selected) ? ' selected="selected"' : '') . '>'
                        . (string) $classification_dto->cname . "</option>\n";

                foreach ($options as $classification1_dto) {
                    if ($classification1_dto->nlevel == 1) {
                        if ($classification1_dto->parent_sid == $classification_dto->sid) {
                            $form .= '<option value="' . html_escape($classification1_dto->sid) . '" data-level="' . html_escape($classification1_dto->nlevel + 1) . '" '
                                    . (in_array($classification1_dto->sid, $selected) ? ' selected="selected"' : '') . '>'
                                    . (string) $classification1_dto->cname . "</option>\n";

//                                    foreach ($options as $classification2_dto)
//                                    {
//                                        if ($classification2_dto->nlevel == 2)
//                                        {
//                                            if ($classification2_dto->parent_sid == $classification1_dto->sid)
//                                            {
//                                                $form .= '<option value="'.html_escape($classification2_dto->sid).'" data-level="'.html_escape($classification2_dto->nlevel+1).'"'
//                                                        .(in_array($classification2_dto->sid, $selected) ? ' selected="selected"' : '').'>'
//                                                        .(string) $classification2_dto->cname."</option>\n";
//
//                                            }
//                                        }
//
//                                    }
                        }
                    }
                }
            }
        }
        return $form . "</select>\n";
    }

    /**
     * Drop-down Menu
     *
     * @param	mixed	$data
     * @param	mixed	$options
     * @param	mixed	$selected
     * @param	mixed	$extra
     * @param       mixed   $parent_disabled
     * @return	string
     */
    function form_dropdown_multi2($first_option = '---選單項目主功能新增---', $data = '', $options = array(), $selected = array(), $extra = '', $disabled = '') {
        $defaults = array();

        if (is_array($data)) {
            if (isset($data['selected'])) {
                $selected = $data['selected'];
                unset($data['selected']); // select tags don't have a selected attribute
            }

            if (isset($data['options'])) {
                $options = $data['options'];
                unset($data['options']); // select tags don't use an options attribute
            }
        } else {
            $defaults = array('name' => $data);
        }

        is_array($selected) OR $selected = array($selected);
        is_array($options) OR $options = array($options);

        // If no selected state was submitted we will attempt to set it automatically
        if (empty($selected)) {
            if (is_array($data)) {
                if (isset($data['name'], $_POST[$data['name']])) {
                    $selected = array($_POST[$data['name']]);
                }
            } elseif (isset($_POST[$data])) {
                $selected = array($_POST[$data]);
            }
        }

        $extra = _attributes_to_string($extra);

        $disabled = _attributes_to_string($disabled);

        $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select ' . rtrim(_parse_form_attributes($data, $defaults)) . $extra . $multiple . ">\n";

        $first_data_level = 0;

        $n_option = "";
        foreach ($options as $index => $classification_dto) {
            if ($first_data_level > $classification_dto->nlevel + 1 || $first_data_level == 0) {
                $first_data_level = $classification_dto->nlevel + 1;
            }

            $n_option .= '<option value="' . html_escape($classification_dto->sid) . '" data-level="' . html_escape($classification_dto->nlevel + 1) . '" ' . $disabled
                    . (in_array($classification_dto->sid, $selected) ? ' selected="selected"' : '') . '>'
                    . (string) $classification_dto->cname . "</option>\n";
        }

        $f_option = "";
        if ($first_option != null) {
            $f_option = '<option value="0" data-level="' . $first_data_level . '">' . $first_option . '</option>';
        }

        return $form . $f_option . $n_option . "</select>\n";
    }

    /**
     * Drop-down Menu
     *
     * @param	mixed	$data
     * @param	mixed	$options
     * @param	mixed	$selected
     * @param	mixed	$extra
     * @param       mixed   $parent_disabled
     * @return	string
     */
    function form_dropdown_product_multi($data = '', $options = array(), $selected = array(), $extra = '') {
        $defaults = array();

        if (is_array($data)) {
            if (isset($data['selected'])) {
                $selected = $data['selected'];
                unset($data['selected']); // select tags don't have a selected attribute
            }

            if (isset($data['options'])) {
                $options = $data['options'];
                unset($data['options']); // select tags don't use an options attribute
            }
        } else {
            $defaults = array('name' => $data);
        }

        is_array($selected) OR $selected = array($selected);
        is_array($options) OR $options = array($options);

        // If no selected state was submitted we will attempt to set it automatically
        if (empty($selected)) {
            if (is_array($data)) {
                if (isset($data['name'], $_POST[$data['name']])) {
                    $selected = array($_POST[$data['name']]);
                }
            } elseif (isset($_POST[$data])) {
                $selected = array($_POST[$data]);
            }
        }

        $extra = _attributes_to_string($extra);



        $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select ' . rtrim(_parse_form_attributes($data, $defaults)) . $extra . $multiple . ">\n";

        $form .= '<option value="0">----請選擇產品類別----</option>';

        foreach ($options as $classification_dto) {
            if ($classification_dto->nlevel == 0) {
                if ($classification_dto->product_count == 0) {
                    $disabled = '';
                } else {
                    $disabled = 'disabled';
                }

                $form .= '<option value="' . html_escape($classification_dto->sid) . '" data-level="' . html_escape($classification_dto->nlevel + 1) . '" ' . $disabled
                        . (in_array($classification_dto->sid, $selected) ? ' selected="selected"' : '') . '>'
                        . (string) $classification_dto->cname . "</option>\n";

                foreach ($options as $classification1_dto) {
                    if ($classification1_dto->nlevel == 1) {
                        if ($classification1_dto->parent_sid == $classification_dto->sid) {
                            $form .= '<option value="' . html_escape($classification1_dto->sid) . '" data-level="' . html_escape($classification1_dto->nlevel + 1) . '" '
                                    . (in_array($classification1_dto->sid, $selected) ? ' selected="selected"' : '') . '>'
                                    . (string) $classification1_dto->cname . "</option>\n";

//                                    foreach ($options as $classification2_dto)
//                                    {
//                                        if ($classification2_dto->nlevel == 2)
//                                        {
//                                            if ($classification2_dto->parent_sid == $classification1_dto->sid)
//                                            {
//                                                $form .= '<option value="'.html_escape($classification2_dto->sid).'" data-level="'.html_escape($classification2_dto->nlevel+1).'"'
//                                                        .(in_array($classification2_dto->sid, $selected) ? ' selected="selected"' : '').'>'
//                                                        .(string) $classification2_dto->cname."</option>\n";
//
//                                            }
//                                        }
//
//                                    }
                        }
                    }
                }
            }
        }
        return $form . "</select>\n";
    }

    function creat_data_array($defult_name, $data_array_from) {

        $is_object = is_object(current($data_array_from));

        $data_array = array();

        if ($is_object) {


            if (!empty($defult_name)) {
                $data_array[0] = '---請選擇' . $defult_name . '---';
            }

            foreach ($data_array_from as $data_from_dto) {

                $data_array[$data_from_dto->sid] = $data_from_dto->cname;
            }
        } else {
            $data_array = $data_array_from;
        }

        return $data_array;
    }

    function crate_tag_view($tag_data = '', $lable = '', $tag_type = '') {
        $CI = get_instance();

        $tag_view_dto = new Tag_view_dto();
        $tag_view_dto->setTag_lable($lable);
        $tag_view_dto->setTag_type($tag_type);
        $tag_view_dto->setTag_data($tag_data);

        return $CI->tag_create->create_admin_tag($tag_view_dto);
    }

    function get_tag_ary($tag_data = '') {
        $CI = get_instance();
        $CI->load->model("sys/Tag_model", "tag_model");
        if (!empty($tag_data)) {
            $list = explode(",", $tag_data);
            $tag_ary = array();
            foreach ($list as $sid) {
                $dto = $CI->tag_model->find_tag_by_sid($sid);
                if (!empty($dto)) {
                    $tag_ary[$dto->sid] = $dto->cname;
                }
            }

            return $tag_ary;
        } else {
            return '';
        }
    }

    function get_tag_str($tag_data = '') {
        if (!empty($tag_data)) {
            $tag_ary = get_tag_ary($tag_data);
            return implode(',', $tag_ary);
        } else {
            return '';
        }
    }

    //取得系統項目名稱
    function get_sys_item_cname($sid) {
        $CI = get_instance();
        $CI->load->model("custom/Sys_item_model", "sys_item_model");

        if ($sid) {
            return $CI->sys_item_model->find_sys_item_by_sid($sid)->cname;
        } else {
            return '';
        }
    }

    //排列,分開字串
    function str_sort_to_br($str) {
        $ary = explode(',', $str);
        $new_str = "";
        foreach ($ary as $row) {
            $new_str .= $row . "\n";
        }

        return $new_str;
    }
    
     //排列,分開字串
    function multiple_str_sort_to_br($str) {
        $ary = explode('@', $str);
        $new_str = "";
        foreach ($ary as $row) {
            $new_str .= $row . "\n";
        }

        return $new_str;
    }

    //取得教師標籤名稱
    function get_tag_cname($sid) {
        $CI = get_instance();
        $CI->load->model("custom/Teacher_tag_model", "teacher_tag_model");

        if ($sid) {
            return $CI->teacher_tag_model->find_teacher_tag_by_sid($sid)->cname;
        } else {
            return '';
        }
    }

}