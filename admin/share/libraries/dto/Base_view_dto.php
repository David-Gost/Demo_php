<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

// AC_TODO記得寫註解
class Base_view_dto extends Base_dto {

    private $_default_var_array = [];
    public function __construct() {
        parent::__construct();
        $this->_default_var_array = (array)$this;
    }

    /**
     * AC_TODO:要寫註解
     */
    function __call($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }else{
            $str =ord(substr($method,3,1));
            if (strlen($method) > 4 && $str > 64 && $str < 91) {
                $s = substr($method,0,3);
                $var_name = substr($method,3,strlen($method));
                if ($s == "set" || $s == "get") {
                    return call_user_func_array(array($this, $s."_var"), array_merge([$var_name],$params));
                }
            }
        }
        throw new Exception("Call to undefined method Template_view_dto::$method()");
    }

    public function set_var($var_name,$parem)
    {
        if (array_key_exists("$var_name",$this->_default_var_array)) {
            $method_name = "set".ucfirst($var_name);
            if (method_exists($this, $method_name)) {
                return $this->$method_name($parem);
            }else{
                $this->$var_name = $parem;
                return $this->$var_name;
            }
        }else{
            return "error:沒有宣告變數 $var_name";
        }
    }

    public function get_var($var_name)
    {
        if (isset($this->$var_name)) {
            $method_name = "get".ucfirst($var_name);
            if (method_exists($this, $method_name)) {
                return $this->$method_name();
            }else{
                return $this->$var_name;
            }
        }else{
            return null;
        }
    }

    private $_view_property_array = [];

    public function default_property($var_name,$other_html = [],$dom_child = "")
    {
        $tmp = new stdClass();
        $tmp->html_label = "";
        $tmp->html_tag = "input";
        $tmp->html_type = "text";
        $tmp->html_name = get_class($this).".".$var_name;
        $tmp->html_value = [$this->$var_name];
        $tmp->html_selected_checked = [];
        $tmp->html_child = "";

        $tmp = (object)array_merge((array)$tmp,(array)$other_html);
        return $tmp;
    }

    public function set_view_property($var_name,$property_key,$property_value)
    {
        if (!array_key_exists($var_name,$this->_view_property_array)) {
            $this->_view_property_array[$var_name] = [];
        }
        $this->_view_property_array[$var_name]->$property_key = $property_value;
        return $this;
    }

    public function get_view_property($var_name)
    {
        if (array_key_exists($var_name,$this->_view_property_array)) {
            return $this->_view_property_array[$var_name];
        }else{
            $this->init_view_property($var_name);
            return $this->_view_property_array[$var_name];
        }
    }

    public function init_view_property($var_name,$view_property = [])
    {
        $this->_view_property_array[$var_name] = $this->default_property($var_name,$view_property);
        return $this;
    }

    public function set_selected_checked_value($var_name,$select_checked = [])
    {
        return $this->set_view_property($var_name,"html_selected_checked",$select_checked);
    }

    /**
     * sample
     * $a = new template_view_dto();
     * $a->used_sid = "a";
     * $pro = $a->default_property("used_sid",["class"=>"hihihi"]);
     * $a->init_view_property("used_sid",["class"=>"hihihihi"]);
     * $a->init_view_property("title",$pro);
     * $a->init_view_property("title",["html_value"=>["kkkk"=>"vvv","aaa"=>"bbb"],"html_child"=>"yoyoyo","html_tag"=>"div"]);
     * echo '<pre>',print_r($a->get_property_html()),'</pre>';
     * exit;
     *
     * @return void
     */
    public function get_property_html($find_array_key = "")
    {
        $re_html = [];
        foreach ($this->_view_property_array as $array_key => $view_property) {
            if (strlen($find_array_key) > 0 && $find_array_key != $array_key) {
                continue;
            }
            $tmp_array = (array) $view_property;
            $need_end_tag = false;
            $html_property = "";
            $tag_name = "";
            $child_html = "";

            foreach ($tmp_array as $p_key => $p_value) {
                if ($p_key == "html_tag") {
                    $tag_name = $p_value;
                    if ($p_value != "input") {
                        $need_end_tag = true;
                    }
                } elseif ($p_key == "html_child") {
                    $child_html .= $p_value;
                } elseif ($p_key == "html_label") {
                    //不處理html_label，讓有需要的另外在外面處理
                } else {
                    $key_tmp = $p_key;
                    if ($p_key == "html_type" || $p_key == "html_name" || $p_key == "html_value") {
                        if (strlen($p_key) > 5 && strtolower(substr($p_key, 0, 5) == "html_")) {
                            $key_tmp = substr($p_key, 5);
                        }
                    }

                    if (is_string($p_value) && $key_tmp != "value") {
                        if (($view_property->html_type == "radio" || $view_property->html_type == "checkbox") && $key_tmp == "name") {
                            $p_value = $p_value."[]";
                        }
                        $html_property .= " " . htmlentities($key_tmp) . (strlen($p_value) > 0 ? '="' . htmlentities($p_value) . '"' : "");
                    }
                }
            }

            $value_array = [];
            if (is_string($view_property->html_value)) {
                $value_array = explode(",", $view_property->html_value);
            } elseif (is_array($view_property->html_value)) {
                $value_array = $view_property->html_value;
            }

            $html_array = [];
            if ($tag_name == "select") {
                foreach ($value_array as $s_o_key => $s_o_value) {
                    $selected = false;
                    foreach ($view_property->html_selected_checked as $key => $value) {
                        if (htmlentities($s_o_key) == $value) {
                            $selected = true;
                            break;
                        }
                    }
                    $child_html .= '<option value="' . htmlentities($s_o_key) . '"'.($selected?'selected="selected"':'').'>' . htmlentities($s_o_value) . '</option>';
                }
                $html_array = [""=>'<'.$tag_name.' '.$html_property.' >' . ($need_end_tag ? "$child_html</$tag_name>" : "")];
            }else{
                foreach ($value_array as $s_o_key => $s_o_value) {
                    $checked = false;
                    if ($view_property->html_type == "radio" || $view_property->html_type == "checkbox") {
                        foreach ($view_property->html_selected_checked as $key => $value) {
                            if (htmlentities($s_o_key) == $value) {
                                $checked = true;
                                break;
                            }
                        }
                    }
                    $html_array[$s_o_value] = '<'.$tag_name.' '.$html_property.' value="'.$s_o_key.'" '.($checked?'checked="checked"':'').'>' . ($need_end_tag ? "$child_html</$tag_name>" : "");
                }
            }
            $re_html[$array_key] = $html_array;
        }
        return $re_html;
    }
}