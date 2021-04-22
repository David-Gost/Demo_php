<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function get_select_item($data_source,$filter_key_value,$return_property)
{
    foreach ($data_source as $key => $value) {
        $add_re = false;
        foreach ($filter_key_value as $f_key => $f_value) {
            if ((!is_null($f_value) && $value->$f_key == $f_value) || (is_null($f_value) && is_null($value->$f_key))) {
                $add_re = true;
                continue;
            }else{
                $add_re = false;
                break;
            }
        }
        if ($add_re) {
            return $value->$return_property;
        }
    }
    return "";
}

    /**
     * 篩選一維陣列資料
     * example:
     * input:
     * $data_source = [{"work_item":"指揮官","work":"內勤","duty_unit":"空噪科","account_sid":"15"}
     * ,{"work_item":"發布新聞稿","work":"內勤","duty_unit":"環檢中心","account_sid":"15"}
     * ,{"work_item":"場站稽查","work":"外勤","duty_unit":"大隊","account_sid":"16"}
     * ,{"work_item":"加強周邊道路洗掃","work":"外勤","duty_unit":"環清科","account_sid":"15"}
     * ,{"work_item":"天氣分析","work":"內勤","duty_unit":"天氣風險公司","account_sid":"11"}]
     * $this->filter_($data_source,["work"=>"內勤"],["work_item"],true);
     * 
     * output:
     * [{"work_item":"指揮官"}
     * ,{"work_item":"發布新聞稿"}
     * ,{"work_item":"天氣分析"}]
     * 
     * @param array $data_source    資料來源，請送陣列物件->[{}]
     * @param array $filter_key_value   篩選條件["物件的屬性名稱"=>"要篩選的值"]
     * @param array $return_property   回傳的欄位["欄位名稱"]
     * @param array $single   是否回傳單筆
     * @return array
     */
    function filter_($data_source, $filter_key_value, $return_property = [], $single = false)
    {
        $re = [];
        foreach ($data_source as $key => $value) {
            $add_re = false;
            foreach ($filter_key_value as $f_key => $f_value) {
                if ((!is_null($f_value) && $value->$f_key == $f_value) || (is_null($f_value) && is_null($value->$f_key))) {
                    $add_re = true;
                } else {
                    $add_re = false;
                    break;
                }
            }
            if ($add_re) {
                if (count($return_property) > 0) {
                    $tmp = new stdClass();
                    foreach ($return_property as $re_pro_key => $re_pro_value) {
                        if (isset($value->$re_pro_value)) {
                            $tmp->$re_pro_value = $value->$re_pro_value;
                        } else {
                            $tmp->$re_pro_value = NULL;
                        }
                    }
                    $re[] = $tmp;
                } else {
                    $re[] = $value;
                }
                if ($single) {
                    return $re;
                }
            }
        }
        return $re;
    }


    function group_data($property_list = [],$data_source = [],$group_name = NULL,$show_first_but_no_match = [])
    {
        $_group_tmp_name = $group_name;
        if (is_null($group_name)) {
            $_group_tmp_name = "_group";
        }

        $re = [];
        foreach ($data_source as $key => $value) {
            $add_to_array = true;
            foreach ($re as $re_key => $re_value) {
                $match = true;
                foreach ($property_list as $p_key => $prop_name) {
                    if ($re_value->$prop_name == $value->$prop_name) {
                        continue;
                    }else{
                        $match = false;
                        break;
                    }
                }
                if ($match) {
                    if (!is_null($group_name)) {
                        $re[$re_key]->{$_group_tmp_name}[] = $value;
                    }
                    $add_to_array = false;
                }
            }
            if ($add_to_array) {
                $tmp = new stdClass();
                foreach ($property_list as $p_key => $prop_name) {
                    $tmp->$prop_name = $value->$prop_name;
                }
                
                foreach ($show_first_but_no_match as $show_key => $show_value) {
                    $tmp->$show_value = $value->$show_value;
                }
                
                if (!is_null($group_name)) {
                    $tmp->$_group_tmp_name = [$value];
                }
                $re[] = $tmp;
            }
        }
        return $re;
    }

    function exclude_data($property_name, $white_list = [], $data_source = [],$refresh_array_key = true)
    {
        $re = $data_source;
        $data_count = count($data_source);
        if (empty($property_name)) {
            return $re;
        }
        foreach ($re as $key => $value) {
            $unset = true;
            foreach ($white_list as $property) {
                if ($value->$property_name == $property) {
                    $unset = false;
                }
            }
            if ($unset) {
                unset($re[$key]);
            }
        }
        if ($refresh_array_key) {
            if (count($re) != $data_count) {
                $re_new = [];
                foreach ($re as $key => $value) {
                    $re_new[] = $value;
                }
                $re = $re_new;
            }
        }
            return $re;
    }

    function ac_trim($str){
        $str = preg_replace('/^[(\xc2\xa0)|\s]+/', '', $str);
        return trim($str,"\0\t\n\x0B\r ");
    }

    function ac_ltrim($str){
        $str = preg_replace('/^[(\xc2\xa0)|\s]+/', '', $str);
        return ltrim($str,"\0\t\n\x0B\r ");
    }

    function ac_rtrim($str){
        $str = preg_replace('/^[(\xc2\xa0)|\s]+/', '', $str);
        return rtrim($str,"\0\t\n\x0B\r ");
    }

    function is_JSON($string){
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }