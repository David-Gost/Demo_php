<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of search_report
 *
 */
class Search_report {

    //put your code here

    private $_ci;
    public $element_array;

    public function __construct() {

        $this->_ci = &get_instance();
    }

    public function create_search_obj(Search_view_dto $search_view_dto) {

        $this->element_array = $search_view_dto->element_array;
        $view = $this->create_search_view($search_view_dto);

        $search_obj = new stdClass();

        $search_obj->view = $view;
        $search_obj->model_id = $search_view_dto->model_id;
        $search_obj->view_name = $search_view_dto->view_name;

        return $search_obj;
    }

    public function get_element_data($element_id = '') {

        if (count($this->element_array) == 0 || $element_id == '') {
            return '';
        } else {
            $element_dto = $this->element_array[$element_id];

            switch ($element_dto->element_type) {

                case 'input':

                    $data = $element_dto->value;

                    break;

                case 'select':
                case 'multiple':

                    $data = $element_dto->data_array;

                    break;

                case 'date':

                    $data = $element_dto->startdate . ' ~ ' . $element_dto->enddate;

                    break;

                case 'year':

                    $data = $element_dto->year_month;
                    break;
            }

            return $data;
        }
    }

    /**
     * 
     * @param type $data
     * <p>傳入$_POST</p>
     */
    public function get_search_data($data) {

        $data_dto = new stdClass();

        if (count($data) == 0) {

            return null;
        }

        foreach ($data as $key => $value) {

            if ($key == 'search_view_name') {

                $data_dto->$key = $value;
            } else {

                $key_array = explode('_', $key);


                switch ($key_array[0]) {

                    case 'input':

                        $real_key_array = explode('input_', $key);
                        $real_key = $real_key_array[1];
                        $data_dto->$real_key = $value;

                        break;

                    case 'select':

                        $real_key_array = explode('select_attributes_', $key);
                        $real_key = $real_key_array[1];
                        $data_dto->$real_key = $value;

                        break;

                    case 'multiple':

                        $real_key_array = explode('multiple_select_attributes_', $key);
                        $real_key = $real_key_array[1];
                        $data_dto->$real_key = $value;

                        break;

                    case 'date':

                        //----起迄日期
                        $startdate = '';
                        $enddate = '';

                        if ($value != '') {
                            $date = explode(" ~ ", $value);

                            $startdate = $date[0];
                            $enddate = $date[1];
                        }

                        $data_dto->startdate = $startdate;
                        $data_dto->enddate = $enddate;

                        break;

                    case 'year':

                        $real_key_array = explode('year_month_', $key);
                        $real_key = $real_key_array[1];
                        $data_dto->$real_key = $value;

                        break;
                }


//                echo '<pre>', print_r($key_array), '</pre>';
            }
        }

//        echo '<pre>', print_r($data_dto), '</pre>';
//        exit;

        return $data_dto;
    }

    private function create_search_view(Search_view_dto $search_view_dto) {

        $element_array = $search_view_dto->getElement_array();

        //------畫面

        $view = '
        <div class="modal fade" id="' . $search_view_dto->model_id . '" tabindex="-1" role="dialog" aria-labelledby="search_report_label" aria-hidden="true">
          <div class="modal-dialog">
          
            <div class="modal-content">
                <div class="modal-header">
                    <div class="pull-left">
                        <div class="btn-group"><a href="#" class="btn btn-info btn-sm fa fa-search" id="btn_start_search" onclick="start_search();">篩選</a></div>
                        <div class="btn-group"><a href="#" class="btn btn-secondary btn-sm fa fa-close" onclick="clear_search();">清除</a></div>
                    </div>
                    <div class="pull-right">
	                    <div class="btn-group"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></div>
                    </div>                
                </div>
                  <form id="' . $search_view_dto->model_id . '_from" action="' . $search_view_dto->url . '"  method="post">
                    <div class="modal-body box-body">
                       <input type="hidden" class="form-control pull-right" id="search_view_name" name="search_view_name"  value="' . $search_view_dto->view_name . '"/>';

        foreach ($element_array as $element_dto) {
            $view .= $this->create_element_view($element_dto);
        }

        $view .= '
                    </div>
                 </form>
             </div>
           </div>
        </div>';

        //------script
        $id = '';
        foreach ($element_array as $element_dto) {

            if ($element_dto->element_type == 'select') {

                $default = empty($element_dto->data_array[0]) ? '\'\'' : 0;

                $id .= '$(\'#' . $this->get_element_id($element_dto) . '\').val(' . $default . ');';
            } else {
                $id .= '$(\'#' . $this->get_element_id($element_dto) . '\').val(\'\');';
            }
        }
        $view .= '
            <script type="text/javascript">
                function clear_search() {
                ' . $id . '
                $("#' . $search_view_dto->model_id . '_from").submit();
                }
            </script>';
        $view .= '
            <script type="text/javascript">
                function start_search() {
                   $("#' . $search_view_dto->model_id . '_from").submit();
                }
            </script>';


        foreach ($element_array as $element_dto) {

            if ($element_dto->element_type != 'input') {

                $view .= $this->create_element_view_script($element_dto);
            }
        }

        return $view;
    }

    private function get_element_id(Element_dto $element_dto) {

        $id = '';

        switch ($element_dto->element_type) {

            case 'input':

                $id = 'input_' . $element_dto->id;

                break;

            case 'select':

                $front_name = $element_dto->is_multiple ? 'multiple_select_' : 'select_';

                $id = $front_name . 'attributes_' . $element_dto->id;

                break;

            case 'date':

                $front_name = $element_dto->is_date_range ? 'date_range_' : 'year_month_';

                $id = $front_name . $element_dto->id;

                break;
        }

        return $id;
    }

    private function create_element_view(Element_dto $element_dto) {

        $view = '';

        switch ($element_dto->element_type) {

            case 'input':
                $icon = empty($element_dto->icon) ? 'fa-cubes' : $element_dto->icon;
                $view = '<div class="' . $element_dto->class . '" >
                        <div class="form-group" >
                        <label>' . $element_dto->label . '</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa ' . $icon . '"></i></span>
                            </div>
                            <input type="' . $element_dto->input_type . '" class="form-control pull-right" id="input_' . $element_dto->id . '" name="input_' . $element_dto->name . '" value="' . $element_dto->value . '" />
                        </div>
                    </div>
                   </div> ';

                break;

            case 'select':

                $icon = empty($element_dto->icon) ? 'fa-cubes' : $element_dto->icon;

                $multiple = $element_dto->is_multiple ? 'multiple' : '';
                $front_name = $element_dto->is_multiple ? 'multiple_select_' : 'select_';


                if (!$element_dto->single_level) {
                    $select_view = form_dropdown_multi('', $element_dto->data_array, '', 'id="' . $front_name . $element_dto->id . '" class="form-control" ' . $multiple);
                } else {

                    if ($element_dto->default_enable) {
                        $defult_name = $element_dto->label;
                    } else {
                        $defult_name = '';
                    }

                    $data_array = creat_data_array($defult_name, $element_dto->data_array);
                    $select_view = form_dropdown('', $data_array, '', 'id="' . $front_name . $element_dto->id . '" class="form-control" ' . $multiple);
                }

                $view = '<div class="' . $element_dto->class . '" >
                    <div class="form-group" >
                       <label>' . $element_dto->label . '</label>
                       <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa ' . $icon . '"></i></span>
                            </div>
                            ' . $select_view . '
                            <input type=\'hidden\'id=\'' . $front_name . 'attributes_' . $element_dto->id . '\' name=\'' . $front_name . 'attributes_' . $element_dto->name . '\' />
                        </div>
                    </div>
                    </div>';
                break;

            case 'date':

                if ($element_dto->is_date_range) {


                    if (!empty($element_dto->startdate) && !empty($element_dto->enddate)) {

                        $date_txt = $element_dto->startdate . " ~ " . $element_dto->enddate;
                    } else {
                        $date_txt = '';
                    }
                } else {

                    $date_txt = $element_dto->year_month;
                }

                $icon = empty($element_dto->icon) ? 'fa-calendar' : $element_dto->icon;
                $front_name = $element_dto->is_date_range ? 'date_range_' : 'year_month_';

                $view = '<div class="' . $element_dto->class . '" >
                    <div class="form-group">
                        <label>' . $element_dto->label . '</label>
                        <div class="input-group" style=" min-width: 220px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa ' . $icon . '"></i></span>
                            </div>
                            <input type="text" class="form-control pull-right" id="' . $front_name . $element_dto->id . '" name="' . $front_name . $element_dto->name . '" value="' . $date_txt . '" />
                        </div>
                    </div>
                </div>';

                break;
        }

        return $view;
    }

    private function create_element_view_script(Element_dto $element_dto) {

        $view = '<script type="text/javascript">';

        switch ($element_dto->element_type) {

            case 'select':

                $front_name = $element_dto->is_multiple ? 'multiple_select_' : 'select_';
                $height = (count($element_dto->data_array) > 15) ? "400px;" : '';

                if ($element_dto->default_enable) {

                    $select_script = 'select_' . $element_dto->id . '_obj.selectOptions([' . $element_dto->select . '], false);';
                } else {

                    $select_script = 'select_' . $element_dto->id . '_obj.selectOptions([' . $element_dto->select . '], false).disableOptions([\'0\']);';
                }

                $view .= '
                    
                    var add_' . $element_dto->id . '= function () {

                        var sel_' . $element_dto->id . '_select = select_' . $element_dto->id . '_obj.getSelectedPairs();

                        $("#' . $front_name . 'attributes_' . $element_dto->id . '").val(sel_' . $element_dto->id . '_select.value);

                        return true;
                    };

                    var select_' . $element_dto->id . '_obj = $("select#' . $front_name . $element_dto->id . '").smartselect({
                        multiple: true,
                        template: {
                            select: \'<button class="form-control" type="button" style="background-color: #ffffff;"><i class="ss-icon"></i><span class="ss-label"></span><i class="ss-caret"></i></button>\',
                            dropdown: \'<ul style="height: ' . $height . 'overflow: scroll;"></ul>\'
                        },
                        toolbar: false,
                        text: {
                            selectLabel: \'---選擇' . $element_dto->label . '---\'
                        },
                        callback: {
                            onOptionChanged: [add_' . $element_dto->id . ']
                        }
                    }).getsmartselect();' . $select_script;

                break;

            case 'date':
                $front_name = $element_dto->is_date_range ? 'date_range_' : 'year_month_';

                if ($element_dto->is_date_range) {

                    $view .= ' $(\'#' . $front_name . $element_dto->id . '\').daterangepicker({
                    locale: {
                        format: "YYYY-MM-DD",
                        separator: " ~ ",
                        applyLabel: \'確定\',
                        cancelLabel: \'取消\',
                        fromLabel: \'開始\',
                        toLabel: \'結束\'
                    }
                });';
                } else {

                    $view .= '    
                
                $(\'#' . $front_name . $element_dto->id . '\').datepicker({
                    autoclose: true,
                    format: "yyyy-mm",
                    minViewMode: 1,
                    todayBtn: true,
                    language: \'zh-TW\'
                });';
                }


                break;
        }

        $view .= '</script>';

        return $view;
    }

}

//-----建立查詢元件
//-----建立查詢元件

class Search_view_dto {

    public $view_name;
    public $model_id;
    public $url;
    public $element_array = array();

    function getView_name() {
        return $this->view_name;
    }

    /**
     * 
     * @return element_dto
     */
    function getElement_array() {
        return $this->element_array;
    }

    function getModel_id() {
        return $this->model_id;
    }

    function getUrl() {
        return $this->url;
    }

    function setModel_id($model_id) {
        $this->model_id = $model_id;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setView_name($view_name) {
        $this->view_name = $view_name;
    }

    /**
     * 
     * @param Element_dto $element_array
     */
    function setElement_array($element_array) {
        $this->element_array = $element_array;
    }

}

//-----元件

class Element_dto {

    public $element_type;
    public $id;
    public $icon;
    public $name;
    public $class = 'col-md-12';
    public $other;
    public $label;
    //-----輸入框
    public $value;
    public $input_type = 'text';
    public $is_multiple = true;
    //-----下拉
    public $data_array;
    public $select;
    public $default_enable = true;
    public $single_level = true;
    //-----日期選擇
    public $date_str = '';
    public $startdate;
    public $enddate;
    public $year_month;
    public $is_date_range = true;

    function getIs_multiple() {
        return $this->is_multiple;
    }

    function getLabel() {
        return $this->label;
    }

    function getInput_type() {
        return $this->input_type;
    }

    function getId() {
        return $this->id;
    }

    function getElement_type() {
        return $this->element_type;
    }

    function getIcon() {
        return $this->icon;
    }

    function getName() {
        return $this->name;
    }

    function getClass() {
        return $this->class;
    }

    function getOther() {
        return $this->other;
    }

    function getValue() {
        return $this->value;
    }

    function getData_array() {
        return $this->data_array;
    }

    function getSelect() {
        return $this->select;
    }

    function getDate_value() {
        return $this->date_value;
    }

    function getIs_date_range() {
        return $this->is_date_range;
    }

    function getStartdate() {
        return $this->startdate;
    }

    function getEnddate() {
        return $this->enddate;
    }

    function getYear_month() {
        return $this->year_month;
    }

    function getDate_str() {
        return $this->date_str;
    }

    function getDefault_enable() {
        return $this->default_enable;
    }

    function getSingle_level() {
        return $this->single_level;
    }

    function setSingle_level($single_level) {
        $this->single_level = $single_level;
    }

    function setDefault_enable($default_enable) {
        $this->default_enable = $default_enable;
    }

    function setDate_str($date_str) {
        $this->date_str = $date_str;
    }

    function setStartdate($startdate) {
        $this->startdate = $startdate;
    }

    function setEnddate($enddate) {
        $this->enddate = $enddate;
    }

    function setYear_month($year_month) {
        $this->year_month = $year_month;
    }

    /**
     * 
     * @param type $element_type
     * <p>輸入框：input 下拉：select 日期選擇：date</p>
     */
    function setElement_type($element_type) {
        $this->element_type = $element_type;
    }

    function setIcon($icon) {
        $this->icon = $icon;
    }

    function setName($name) {
        $this->name = $name;
    }

    /**
     * 
     * @param type $class
     * <p>預設為 col-md-6 如有額外需求請自行修改</p>
     */
    function setClass($class) {
        $this->class = $class;
    }

    function setOther($other) {
        $this->other = $other;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setData_array($data_array) {
        $this->data_array = $data_array;
    }

    function setSelect($select) {
        $this->select = $select;
    }

    function setDate_value($date_value) {
        $this->date_value = $date_value;
    }

    /**
     * 
     * @param type $is_date_range
     * <p>true時為日期選擇 false 時為年月選擇</p>
     */
    function setIs_date_range($is_date_range) {
        $this->is_date_range = $is_date_range;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setInput_type($input_type) {
        $this->input_type = $input_type;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function setIs_multiple($is_multiple) {
        $this->is_multiple = $is_multiple;
    }

}
