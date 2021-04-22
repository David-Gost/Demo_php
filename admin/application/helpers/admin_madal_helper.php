<?php

/**
 * 
 * @param type $title
 * <p>標題</p>
 * @param type $alert_title
 * <p>提示文字</p>
 * @param type $type
 * <p>元件類型 text(輸入框-文字)、number(輸入框-數字),hidden(隱藏欄位)、textarea(輸入框-文本輸入)，checkbox，image(圖片)，file(檔案),single_select(單項下拉) 預設為text</p>
 * @param type $value
 * <p>預設值</p>
 * @param type $div_class
 * <p>div class，預設為col-md-3</p>
 * @param type $other
 * <p>其他設定 ex style,onclick...</p>
 * @return \Item_dto
 */
function quick_modal_view_dto($title = '', $alert_title = '', $type = 'text', $value = '', $div_class = 'col-md-3', $other = '') {

    $ci = &get_instance();
    $ci->load->library("Admin_madal");

    if (empty($type)) {
        $type = "text";
    }

    if (empty($div_class)) {
        $div_class = 'col-md-3';
    }

    $item_view_dto = new Item_dto();
    $item_view_dto->setTitle($title);
    $item_view_dto->setAlert_title($alert_title);
    $item_view_dto->setType($type);
    $item_view_dto->setValue($value);
    $item_view_dto->setOther($other);
    $item_view_dto->setDiv_class($div_class);


    return $item_view_dto;
}

/**
 * 
 * @param type $title
 * <p>標題</p>
 * @param type $alert_title
 * <p>提示文字</p>
 * @param type $value_array
 * <p>radio 內容，陣列</p>
 * @param type $title_array
 * <p>radio 標題，陣列</p>
 * @param string $div_class
 * <p>div class，預設為col-md-3</p>
 * @param type $other
 * <p>其他設定 ex style,onclick...</p>
 * @return \Item_dto
 */
function quick_modal_radio_view_dto($title = '', $alert_title = '', $value_array = array(), $title_array = array(), $div_class = 'col-md-3', $other = '') {

    $ci = &get_instance();
    $ci->load->library("Admin_madal");


    if (empty($div_class)) {
        $div_class = 'col-md-3';
    }

    $item_view_dto = new Item_dto();
    $item_view_dto->setTitle($title);
    $item_view_dto->setAlert_title($alert_title);
    $item_view_dto->setType('radio');
    $item_view_dto->setRadio_value_array($value_array);
    $item_view_dto->setRadio_title_array($title_array);
    $item_view_dto->setOther($other);
    $item_view_dto->setDiv_class($div_class);


    return $item_view_dto;
}

/**
 * 
 * @param type $title
 * <p>標題</p>
 * @param type $alert_title
 * <p>提示文字</p>
 * @param type $value
 * <p>數值</p>
 * @param string $div_class
 * <p>div class，預設為col-md-3</p>
 * @param type $other
 * <p>其他設定 ex style,onclick...</p>
 * @return \Item_dto
 */
function quick_modal_date_view_dto($title = '', $alert_title = '', $value = '', $div_class = 'col-md-3', $other = '') {

    $ci = &get_instance();
    $ci->load->library("Admin_madal");


    $type = "date";


    if (empty($div_class)) {
        $div_class = 'col-md-3';
    }
    $base_other=' readonly="readonly"';
    $other=$other.$base_other;

    $item_view_dto = new Item_dto();
    $item_view_dto->setTitle($title);
    $item_view_dto->setAlert_title($alert_title);
    $item_view_dto->setType($type);
    $item_view_dto->setValue($value);
    $item_view_dto->setOther($other);
    $item_view_dto->setDiv_class($div_class);


    return $item_view_dto;
}

/**
 * 
 * @param type $title
 * <p>標題</p>
 * @param type $alert_title
 * <p>提示文字</p>
 * @param type $data_from
 * <p>下拉資料來源</p>
 * @param string $div_class
 * <p>div class，預設為col-md-3</p>
 * @param type $other
 * <p>其他設定 ex style,onclick...</p>
 * @return \Item_dto
 */
function quick_modal_single_select_view_dto($title = '', $alert_title = '', $data_from = array(), $value = '', $div_class = 'col-md-3', $other = '') {

    $ci = &get_instance();
    $ci->load->library("Admin_madal");


    if (empty($div_class)) {
        $div_class = 'col-md-3';
    }

    $item_view_dto = new Item_dto();
    $item_view_dto->setTitle($title);
    $item_view_dto->setAlert_title($alert_title);
    $item_view_dto->setType('single_select');
    $item_view_dto->setSelect_data_from($data_from);
    $item_view_dto->setValue($value);
    $item_view_dto->setOther($other);
    $item_view_dto->setDiv_class($div_class);


    return $item_view_dto;
}

/**
 * 
 * @param type $title
 * <p>標題</p>
 * @param type $alert_title
 * <p>提示文字</p>
 * @param type $data_from
 * <p>下拉資料來源</p>
 * @param string $div_class
 * <p>div class，預設為col-md-3</p>
 * @param type $other
 * <p>其他設定 ex style,onclick...</p>
 * @return \Item_dto
 */
function quick_modal_multiple_select_view_dto($title = '', $alert_title = '', $data_from = array(), $value = '', $div_class = 'col-md-3', $other = '') {

    $ci = &get_instance();
    $ci->load->library("Admin_madal");


    if (empty($div_class)) {
        $div_class = 'col-md-3';
    }

    $item_view_dto = new Item_dto();
    $item_view_dto->setTitle($title);
    $item_view_dto->setAlert_title($alert_title);
    $item_view_dto->setType('multiple_select');
    $item_view_dto->setSelect_data_from($data_from);
    $item_view_dto->setValue($value);
    $item_view_dto->setOther($other);
    $item_view_dto->setDiv_class($div_class);


    return $item_view_dto;
}


/**
 * 
 * @param type $title
 * <p>標題</p>
 * @param type $image_width
 * <p>圖片寬</p>
 * @param type $image_height
 * <p>圖片高</p>
 * @param type $div_class
 * <p>div class，預設為col-md-3</p>
 * @param type $is_multi
 * <p>是否為多張</p>
 * @param type $other
 * <p>其他設定 ex style,onclick...</p>
 */
function quick_modal_image_view_dto($title = '', $image_width = 0, $image_height = 0, $div_class = 'col-md-3', $is_multi = false, $other = '') {


    $ci = &get_instance();
    $ci->load->library("Admin_madal");

    if (empty($div_class)) {
        $div_class = 'col-md-3';
    }

    $alert_title = "※ 建議大小：$image_width x $image_height px";

    $item_view_dto = new Item_dto();
    $item_view_dto->setTitle($title);
    $item_view_dto->setAlert_title($alert_title);
    $item_view_dto->setType('image');
    $item_view_dto->setOther($other);
    $item_view_dto->setDiv_class($div_class);
    $item_view_dto->setIs_multi($is_multi);
    $item_view_dto->setImage_width($image_width);
    $item_view_dto->setImage_height($image_height);


    return $item_view_dto;
}

/**
 * 
 * @param type $modal_title
 * <p>標題</p>
 * @param type $database_model
 * <p>資料表model 名稱</p>
 * @param type $submit_url
 * <p>資料送出網址</p>
 * @param type $form_id
 * <p>modal 表單id，預設為info_form</p>
 * @param type $ajax_url
 * <p>ajax資訊網址，預設為/ajax/ajax_common/show_info</p>
 * @return \Madal_option_dto
 */
function quick_modal_view_option($modal_title, $database_model, $submit_url, $form_id = "info_form", $ajax_url = "") {

    $ci = &get_instance();
    $ci->load->library("Admin_madal");

    if (empty($form_id)) {
        $form_id = 'info_form';
    }

    if (empty($ajax_url)) {
        $ajax_url = "/ajax/ajax_common/show_info";
    }

    $madal_option = new Madal_option_dto();
    $madal_option->setForm_id($form_id);
    $madal_option->setModal_title($modal_title);
    $madal_option->setDatabase_model($database_model);
    $madal_option->setSubmit_url($submit_url);
    $madal_option->setAjax_url($ajax_url);

    return $madal_option;
}

/**
 * 
 * @param type $option
 * <p>modal 設定</p>
 * @param type $view_array
 * <p>元件清單</p>
 * @return type
 */
function create_admin_modal($option, $view_array) {

    $ci = &get_instance();
    $ci->load->library("Admin_madal");

    if (is_array($option)) {
        $option = quick_modal_view_option($option['modal_title'], $option['database_model'], $option['submit_url'], $option['form_id']);
    }

    foreach ($view_array as $name => $view_dto) {
        $view_dto->name = $name;
        $view_array[$name] = $view_dto;
    }



    $admin_madal = new Admin_madal();

    return $admin_madal->create_modal($option, $view_array);
}

/**
 * <p>產生UI元件</p>
 * @param type $view_array
 * @return type
 */
function create_view_ui($view_array) {

    $ci = &get_instance();
    $ci->load->library("Admin_madal");

    foreach ($view_array as $name => $view_dto) {
        $view_dto->name = $name;
        $view_array[$name] = $view_dto;
    }

    $admin_madal = new Admin_madal();

    return $admin_madal->create_madal_item_view($view_array);
}
