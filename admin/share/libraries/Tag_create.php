<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property Tag_classification_model $tag_classification_model
 * @property Tag_model $tag_model 
 */
class Tag_create {


    function __construct() {

        $CI = &get_instance();
        $CI->load->model("sys/Tag_classification_model", "tag_classification_model");
        $CI->load->model("sys/Tag_model", "tag_model");
    }

    /**
     * 
     * @param Tag_view_dto $tag_view_dto
     */
    public function create_admin_tag(Tag_view_dto $tag_view_dto) {

        $tag_json = $this->create_tag_json($tag_view_dto->tag_data);

        $view = $this->create_tag_view($tag_view_dto);
        $script = $this->create_tag_script($tag_view_dto->tag_type, $tag_json);

        return $script.$view ;
    }

    public function create_tag_json($tag_data) {

        return json_encode(explode(",", $tag_data));
    }

    //----建立tagview
    private function create_tag_view(Tag_view_dto $tag_view_dto) {

        $view = '<div class="' . $tag_view_dto->view_class . '">
                            <label>' . $tag_view_dto->tag_lable . '</label>
                            <select id="tag" name="tag[]" class="form-control select" multiple="multiple" data-placeholder="---關鍵字標籤---" style=" width: 100%;"></select>
               </div>';
        return $view;
    }

    //----建立script
    private function create_tag_script($tag_type, $tag_json) {

        $view = '   
            <script type="text/javascript">
            
                $(document).ready(function() {
                    $(".select").select2({closeOnSelect: false});
                });

                
                $.ajax({
                url:"' . BASE_URL . '/ajax/ajax_tag/load_tag",
                data: {\'type\': \'' . $tag_type . '\'},
                type: "POST",
                dataType: \'json\',
                success: function (result) {

                    $("#tag").empty();

                    $.each(result, function (key, value) {

                        $("#tag").append($("<option value=\'0\'>---關鍵字標籤---</option>").attr("value", key).text(value));
                    });
                    $("#tag").select2(\'val\',' . $tag_json . ');
                 }
                });
            </script>';

        return $view;
    }

}

class Tag_view_dto {

    public $view_class = 'form-group';
    public $tag_type;
    public $tag_lable;
    public $tag_data;

    function getView_class() {
        return $this->view_class;
    }

    function getTag_type() {
        return $this->tag_type;
    }

    function getTag_data() {
        return $this->tag_data;
    }

    function getTag_lable() {
        return $this->tag_lable;
    }

    function setTag_lable($tag_lable) {
        $this->tag_lable = $tag_lable;
    }

    /**
     * 
     * @param type $view_class
     * <p>預設為form-group</p>
     */
    function setView_class($view_class) {
        $this->view_class = $view_class;
    }

    function setTag_type($tag_type) {
        $this->tag_type = $tag_type;
    }

    function setTag_data($tag_data) {
        $this->tag_data = $tag_data;
    }

}
