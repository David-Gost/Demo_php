<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin_madal
 *
 * @author David
 */
class Admin_madal {

    public function create_modal(Madal_option_dto $madal_option_dto, $item_array = array()) {

        $ci = &get_instance();

        $ci->load->helper("crop_image");

        $madal_view = $this->create_madal_view($madal_option_dto, $item_array);
        $madal_script = $this->create_script($madal_option_dto, $item_array);

        $view_type_array = array_column(object_to_array($item_array), 'type');

        if (in_array('image', $view_type_array)) {
            //----有圖片類型，加入圖片裁切

            $view_type_array = array_column(object_to_array($item_array), 'name', 'type');
            $image_id = $view_type_array['image'];
            $view_dto = $item_array[$image_id];


            $crop_modal = create_image_madal($view_dto->image_width, $view_dto->image_height);
        } else {
            $crop_modal = '';
        }

        return $madal_view . $crop_modal . $madal_script;
    }

    /**
     * <p>建立modal 元件清單</p>
     * @param type $item_array
     * @return string
     */
    public function create_madal_item_view($item_array) {

        $view = "";

        foreach ($item_array as $view_dto) {

            switch ($view_dto->type) {
                case 'hidden':
                    //----隱藏欄位
                    $view .= '<input type="hidden" id="' . $view_dto->name . '" name="' . $view_dto->name . '" value="' . $view_dto->value . '">';

                    break;


                default:

                    if (empty($view_dto->alert_title)) {
                        $lable = ' <label>' . $view_dto->title . '</label>';
                    } else {
                        $lable = ' <label>' . $view_dto->title . '<span style="color: #C63333;margin-left: 7px">' . $view_dto->alert_title . '</span></label>';
                    }

                    //----其他元件
                    $view .= '<div class="' . $view_dto->div_class . '">
                                <div class="form-group">' . $lable;

                    switch ($view_dto->type) {

                        case 'checkbox':

                            $view .= '<label style="color: #C63333;" class="form-control">
                                     <input name="' . $view_dto->name . '" id="' . $view_dto->name . '" type="checkbox" class="minimal iclick" value="' . $view_dto->value . '" ' . $view_dto->other . '>(打勾表示使用)
                                   </label>';

                            break;

                        case 'text':
                        case 'number':

                            $view .= '<input type="' . $view_dto->type . '" id="' . $view_dto->name . '" name="' . $view_dto->name . '" value="' . $view_dto->value . '" class="form-control" ' . $view_dto->other . '/>';
                            break;

                        case 'textarea':

                            $view .= '<textarea rows="5" id="' . $view_dto->name . '" name="' . $view_dto->name . '" class="form-control" ' . $view_dto->other . '>' . $view_dto->value . '</textarea>';

                            break;

                        case 'file':

                            if ($view_dto->is_multi) {
                                $is_multi = 'multiple="multiple"';
                            } else {
                                $is_multi = '';
                            }

                            $view .= '<div class="form-control"><input type="file" id="' . $view_dto->name . '" name="' . $view_dto->name . '" ' . $is_multi . ' accept="image/*"  class="form-control file_upload" ' . $view_dto->other . '></div>';



                            break;

                        case 'image':

                            if ($view_dto->is_multi) {
                                $is_multi = 'multiple="multiple"';
                            } else {
                                $is_multi = '';
                            }

                            $view .= '<div class="form-control"><input type="file" id="' . $view_dto->name . '" ' . $is_multi . ' accept="image/*"  class="form-control image_crop" ' . $view_dto->other . '></div>';



                            break;

                        case 'radio':
                            $view .= '<label class="form-control">';
                            foreach ($view_dto->radio_value_array as $count => $radio_value) {

                                $view .= '<input type="' . $view_dto->type . '" id="' . $view_dto->name . '_' . $count . '" name="' . $view_dto->name . '" value="' . $radio_value . '" ' . $view_dto->other . '/>
                                        <span>' . $view_dto->radio_title_array[$count] . '</span>&nbsp;&nbsp;';
                            }
                            $view .= '</label>';
                            break;

                        case 'date':

                            $view .= '<input type="text" id="' . $view_dto->name . '" name="' . $view_dto->name . '" value="' . $view_dto->value . '" class="form-control" ' . $view_dto->other . '/>';

                            break;

                        case 'single_select':
                        case 'multiple_select':

                            if ($view_dto->type == 'multiple_select') {
                                $multiple = ' multiple';
                                $id_str = "multiple_select_$view_dto->name";
                            } else {
                                $multiple = '';
                                $id_str = $view_dto->name;
                            }

                            $view .= form_dropdown($id_str, $view_dto->select_data_from, $view_dto->value, 'id="' . $id_str . '" class="form-control"' . $multiple);

                            if ($view_dto->type == 'multiple_select') {
                                $view .= '<input type="hidden" id="' . $view_dto->name . '" name="' . $view_dto->name . '" value="' . $view_dto->value . '">';
                            }

                            break;
                    }

                    $view .= '</div>
                        </div>';

                    break;
            }
        }

        return $view;
    }

    private function create_madal_view(Madal_option_dto $madal_option_dto, $item_array) {

        $view = '<div class="modal fade" id="info_madal" tabindex="-1" role="dialog" aria-labelledby="info_madal_title" aria-hidden="true">
            <div class="modal-dialog ' . $madal_option_dto->modal_size . '">
        <div class="modal-content">
            <div class="card-header">
                <h4 id="info_madal_title">' . $madal_option_dto->modal_title . '</h4>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-dismiss="modal"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">
             <form id="' . $madal_option_dto->form_id . '" method="post" enctype="multipart/form-data">
                    <div class="row">';

        $view .= $this->create_madal_item_view($item_array);

        $view .= '</div>
                </form>

                    </div> 
                    <div class="card-footer">
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-info" onclick="form_submit();">確定</button> 
                        </div>
                    </div>
                </div>
            </div>
        </div> ';

        return $view;
    }

    /**
     * <p>modal script</p>
     * @param type $item_array
     * @return string
     */
    private function create_script(Madal_option_dto $madal_option_dto, $item_array) {

        $result_value = '';

        foreach ($item_array as $dto) {

            $view_type = $dto->type;

            switch ($view_type) {

                case 'hidden':
                case 'text':
                case 'textarea';
                case 'number':
                case 'date':
                    $result_value .= ' $(\'#' . $dto->name . '\').val(result.' . $dto->name . ');';
                    break;

                case 'checkbox':
                    $result_value .= 'if(result.' . $dto->name . '==1){
                            $("input[name=' . $dto->name . ']").iCheck(\'check\');
                        }else{
                            $("input[name=' . $dto->name . ']").iCheck(\'uncheck\');
                        }';
                    $result_value .= ' $(\'#' . $dto->name . '\').val(result.' . $dto->name . ');';

                    break;

                case 'radio':
                    $result_value .= '$("input[name=' . $dto->name . '][value="+result.' . $dto->name . '+"]").attr("checked", true);';

                    break;

                case 'single_select':
                    $result_value .= 'if(result.' . $dto->name . '!=null){
                            $(\'#' . $dto->name . '\').val(result.' . $dto->name . ');
                        }';

                    break;

                case 'multiple_select':
                    $result_value .= 'if(result.' . $dto->name . '!=null && result.' . $dto->name . '!= \'\'){
                            $(\'#' . $dto->name . '\').val(result.' . $dto->name . ');
                            select_' . $dto->name . '_obj.selectOptions(result.' . $dto->name . '.split(","), false);
                        }else{
                        console.log(result.' . $dto->name . ');
                        $(\'#' . $dto->name . '\').val(\'\');
                        select_' . $dto->name . '_obj.selectOptions(\'\');
                    }';

                    break;
            }
        }


        $view = '<script type="text/javascript">
            
    $( document ).ready(function() {
        $(".file_upload").hide();
        
        $.each($(".file_upload"), function (count, element) {
            
            var id =$(element).attr(\'id\');
            $(element).before( \'<a class="btn btn-info btn-sm" id="file_upload_\'+id+\'" style=" color:white;" onclick="file_upload_but_click(\'+id+\');"  >上傳檔案</a>\' );

        });
        
    });
    
        function file_upload_but_click(id){
        $(id).click();

        }
          $(".file_upload").on("change", function (e) {
            var now_obj= $(this);
            var  click_input_id=now_obj.attr(\'id\');
            console.log(now_obj);
           var file_array = $(this).get(0).files;
           
            var but_title="";
           if(file_array.length==0){
            but_title="上傳檔案";
           }else{
            but_title="已選擇"+file_array.length+"個檔案";
           }

           $(\'#file_upload_\'+click_input_id).html(but_title);
         });
        
    

               var flatpickr_option = {dateFormat:"Y/m/d",locale: "zh"};
               ' . $this->extra_script($madal_option_dto, $item_array) . '

               function show_info(sid) {
                      $(\'#sid\').val(sid);
                     var form_data=$(\'#' . $madal_option_dto->form_id . '\').serialize();
                    form_data+= \'&modal_name=' . $madal_option_dto->database_model . '\';
                     $(\'#info_madal\').modal().show();
                        $.ajax({
                            url: "' . base_url($madal_option_dto->ajax_url) . '",
                            type: "POST",
                            dataType: "json",
                            data: form_data,
                            success: function (result) {
                           
                            ' . $result_value . '
                            
                   

                        }, error: function (error_contant) {
                        }
                    });
                
                }
                   function form_submit() {

                    $(\'#' . $madal_option_dto->form_id . '\').attr(\'action\', \'' . $madal_option_dto->submit_url . '\').submit();

                } 
               </script>';

        return $view;
    }

    /**
     * <p>元件使用套件</p>
     * @param Madal_option_dto $madal_option_dto
     * @param type $item_array
     * @return string
     */
    private function extra_script(Madal_option_dto $madal_option_dto, $item_array) {

        $script_str = "";

        foreach ($item_array as $dto) {

            $view_type = $dto->type;
            switch ($view_type) {
                case 'date':
                    $script_str .= '$(\'input[name="' . $dto->name . '"]\').flatpickr(flatpickr_option);';
                    break;


                case 'multiple_select':

                    $height = (count($dto->select_data_from) > 15) ? "400px;" : '';
                    $script_str .= '
                    
                    var add_' . $dto->name . '= function () {

                        var sel_' . $dto->name . '_select = select_' . $dto->name . '_obj.getSelectedPairs();

                        $("#' . $dto->name . '").val(sel_' . $dto->name . '_select.value);

                        return true;
                    };

                    var select_' . $dto->name . '_obj = $("select#multiple_select_' . $dto->name . '").smartselect({
                        multiple: true,
                        template: {
                            select: \'<button class="form-control" type="button" style="background-color: #ffffff;"><i class="ss-icon"></i><span class="ss-label"></span><i class="ss-caret"></i></button>\',
                            dropdown: \'<ul style="height: ' . $height . 'overflow: scroll;"></ul>\'
                        },
                        toolbar: {
                            buttonSearch:   true,
                            buttonAlias:    false,
                            buttonView:     false,
                            buttonUnfold:   false,
                            buttonCancel:   false,
                            buttonCheckAll: false,
                            buttonUnCheck:  false
                        },
                        text: {
                            selectLabel: \'---選擇' . $dto->title . '---\'
                        },
                        callback: {
                            onOptionChanged: [add_' . $dto->name . ']
                        }
                    }).getsmartselect();';

                    break;
            }
        }

        return $script_str;
    }

}

class Madal_option_dto {

    /**
     * <p>資料表modle名稱</p>
     * @var type 
     */
    public $database_model;

    /**
     * <p>modle 大小，預設為modal-lg</p>
     * @var type 
     */
    public $modal_size = 'modal-lg';

    /**
     * <p>modle 標題</p>
     * @var type 
     */
    public $modal_title;

    /**
     * <p>form id，預設為info_form</p>
     * @var type 
     */
    public $form_id = 'info_form';

    /**
     * <p>submit網址</p>
     * @var type 
     */
    public $submit_url;

    /**
     * <p>顯示資訊ajax路徑，ex:/ajax/ajax_common/show_info</p>
     * @var type 
     */
    public $ajax_url = '';

    /**
     * <p>資料表modle名稱</p>
     * @var type 
     */
    function setDatabase_model($database_model) {
        $this->database_model = $database_model;
    }

    /**
     * <p>modal 大小，預設為modal-lg</p>
     * @var type 
     */
    function setModal_size($modal_size = 'modal-lg') {
        $this->modal_size = $modal_size;
    }

    /**
     * <p>modle 標題</p>
     * @var type 
     */
    function setModal_title($modal_title) {
        $this->modal_title = $modal_title;
    }

    /**
     * <p>form id，預設為info_form</p>
     * @var type 
     */
    function setForm_id($form_id = 'info_form') {
        $this->form_id = $form_id;
    }

    /**
     * <p>submit網址</p>
     * @var type 
     */
    function setSubmit_url($submit_url) {
        $this->submit_url = $submit_url;
    }

    /**
     * <p>顯示資訊ajax路徑，ex:/ajax/ajax_common/show_info</p>
     * @var type 
     */
    function setAjax_url($ajax_url = '/ajax/ajax_common/show_info') {
        $this->ajax_url = $ajax_url;
    }

}

class Item_dto {

    /**
     * <p>標題</p>
     * @var type 
     */
    public $title;

    /**
     * <p>提示文字</p>
     * 
     */
    public $alert_title;

    /**
     * <p>id and name</p>
     * @var type 
     */
    public $name;

    /**
     * <p>元件類型 text(輸入框-文字)、number(輸入框-數字),hidden(隱藏欄位)、textarea(輸入框-文本輸入)，checkbox，image(圖片)，file(檔案) 預設為text</p>
     * @var type 
     */
    public $type = 'text';

    /**
     *  <p>div class，預設為col-md-3</p>
     * @var type 
     */
    public $div_class = 'col-md-3';

    /**
     * <p>預設值</p>
     * @var type 
     */
    public $value = '';

    /**
     * <p>其他，隨意塞</p>
     * @var type 
     */
    public $other = '';

    /**
     * <p>多選 只在圖片，檔案時可設定</p>
     */
    public $is_multi = false;

    /**
     * <p>圖片寬</p> 
     */
    public $image_width = 0;

    /**
     * <p>圖片高</p> 
     */
    public $image_height = 0;

    /**
     * <p>radio數值陣列</p>
     * 
     */
    public $radio_value_array = array();

    /**
     * <p>radio 文字陣列</p>
     * 
     */
    public $radio_title_array = array();

    /**
     * <p>下拉資料來源</p>
     * @var type 
     */
    public $select_data_from = array();

    /**
     * <p>標題</p>
     * @var type 
     */
    function setTitle($title) {
        $this->title = $title;
    }

    /**
     * <p>id and name</p>
     * @var type 
     */
    function setName($name) {
        $this->name = $name;
    }

    /**
     * <p>提示文字</p>
     * 
     */
    function setAlert_title($alert_title) {
        $this->alert_title = $alert_title;
    }

    /**
     * <p>元件類型 text(輸入框-文字)、number(輸入框-數字),hidden(隱藏欄位)、textarea(輸入框-文本輸入)，checkbox，image(圖片)，file(檔案)，radio(單選)， 預設為text</p>
     * @var type 
     */
    function setType($type = 'text') {
        $this->type = $type;
    }

    /**
     *  <p>div class，預設為col-md-3</p>
     * @var type 
     */
    function setDiv_class($div_class = 'col-md-3') {
        $this->div_class = $div_class;
    }

    /**
     * <p>預設值</p>
     * @var type 
     */
    function setValue($value) {
        $this->value = $value;
    }

    /**
     * <p>其他，隨意塞</p>
     * @var type 
     */
    function setOther($other) {
        $this->other = $other;
    }

    /**
     * <p>多選 只在圖片，檔案時可設定</p>
     */
    function setIs_multi($is_multi) {
        $this->is_multi = $is_multi;
    }

    /**
     * <p>圖片寬</p> 
     */
    function setImage_width($image_width) {
        $this->image_width = $image_width;
    }

    /**
     * <p>圖片高</p> 
     */
    function setImage_height($image_height) {
        $this->image_height = $image_height;
    }

    /**
     * <p>radio數值陣列</p>
     * 
     */
    function setRadio_value_array($radio_value_array) {
        $this->radio_value_array = $radio_value_array;
    }

    /**
     * <p>radio 文字陣列</p>
     * 
     */
    function setRadio_title_array($radio_title_array) {
        $this->radio_title_array = $radio_title_array;
    }

    /**
     * <p>下拉資料來源</p>
     * @param type $select_data_from
     */
    function setSelect_data_from($select_data_from) {
        $this->select_data_from = $select_data_from;
    }

}
