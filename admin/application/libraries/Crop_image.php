<?php

class Crop_option {

    /**
     *
     * @var type 
     */
    public $output_width;

    /**
     *
     * @var type 
     */
    public $output_height;

    function setOutput_width($output_width) {
        $this->output_width = $output_width;
    }

    function setOutput_height($output_height) {
        $this->output_height = $output_height;
    }

}

class Crop_image {

    //put your code here

    public function create_crop_view(Crop_option $crop_option) {
        $header = $this->page_header();
        $modal = $this->page_modal($crop_option);
        $script = $this->page_script($crop_option);

        return $header . $modal . $script;
    }

    /**
     * <p>取最大公因數</p>
     * @param type $a
     * @param type $b
     * @return type
     */
    private function gcd($a, $b) {

        return ($a % $b) ? $this->gcd($b, $a % $b) : $b;
    }

    private function page_header() {

        $head_style = '
            
            <style>
            
            .picClipper__title h3{
                margin-top: 5px;
                display: inline;
            }
            
            .picClipper__title span{
                font-weight: 200;
            }
            
            .picClipper__thumbnail{
                overflow-y:scroll;
                overflow-x: hidden;
                height: 70vh;
                display: flex;
                flex-wrap: wrap;
            }
            .picClipper__thumbnail__item{
                position: relative;
                margin: 4% 2%;
                display: flex;
                flex-wrap: wrap;
                height: 225px;

            }
            .picClipper__thumbnail__item img{
                width: 100%;
            }
            .picClipper__thumbnail__item .deleteButton{
                border-radius: 50%;
                height: 30px;
                width:30px;
                background-color: white;
                position: absolute;
                box-shadow: 0 6px 14px 0 rgba(0, 0, 0, 0.2);
                top: -11px;
                right: -13px;
            }
            .picClipper__thumbnail__item .deleteButton i{
                position: absolute;
                top: 6px;
                right: 9px;
            }
            .picClipper__thumbnail__item p{
                font-size: 14px;
                margin-bottom: -3px;
                padding: 5%;
            }
            .picClipper__thumbnail__item span{
                margin-top:5px;
                color: #999;
                display: inherit;
            }
            .picClipper__crop{
                height: 60vh;
            }
            .picClipper__crop .cropper-bg{
                height: 100%;
            }
            .picClipper__button{
                margin-bottom: 20px;
                display: flex;
                flex-wrap: wrap;
                padding: 1% 3% 2% 3%;
                border-radius: 5px;
                box-shadow: 0 6px 14px 0 rgba(0, 0, 0, 0.2);
            }
            .picClipper__button .btn-group{
                margin-right:4%;
            }
            .picClipper__button .btn-primary{
                background-color:#999;
                border-color:#999;
            }
            .picClipper__footer .btn-info:nth-child(2){
                background-color: #c2c7d0;
                border-color: #c2c7d0;
            }
            .picClipper__footer .btn{
                margin-left: 5px;
            }
            
            /*右側縮圖的基本設定*/
            .pic_normal,
            .pic_sel,
            .pic_edited{width:80%;box-shadow: 0 6px 14px 0 rgba(0, 0, 0, 0.2);}

            /* 一般 */
            .pic_normal {
            }
            /* 選擇圖片 */
            .pic_sel {
                border: solid #ffc107 3px;
            }
            /* 已裁切過 */
            .pic_edited {
                border: none;
            }
            /*警告尚未裁切*/
            span.alert_none{
                font-weight: bold;
                color:#dc3545;
            }
            /*提醒裁切成功*/
            span.alert_edited{
                color:#28a745;
            }
            </style>';

        return $head_style;
    }

    /**
     * 
     * @param type $id
     * <p>img元件id</p>
     * @param type $img_src
     * <p>img元件src</p>
     * @param type $image_width
     * <p>圖片寬</p>
     * @param type $image_height
     * <p>圖片高</p>
     * @return string
     */
    public function create_image_view($id, $img_src, $image_width, $image_height) {

        $view_str = '
        <div class="picClipper__thumbnail__item">
            <div class="btn-group pic_normal">
                <div onclick="image_click(\'' . $id . '\')">
                    <img id="' . $id . '" src = "' . $img_src . '">
                    <p ><span class="alert_none">尚未裁切</span><span class="image_info">原圖尺寸&nbsp;&nbsp;寬：' . $image_width . 'px&nbsp;&nbsp;高：' . $image_height . 'px</span></p>
                </div>
                <div class="deleteButton" onclick="del_image(\'' . $id . '\')">
                    <i class="fa fa-times" ></i>
                </div>
            </div>
        </div>';

        return $view_str;
    }

    private function page_modal(Crop_option $crop_option) {

        $gcd = $this->gcd($crop_option->output_width, $crop_option->output_height);

        $width_ratio = $crop_option->output_width / $gcd;
        $height_ratio = $crop_option->output_height / $gcd;

        //----樣式

        $modal_html = '
                
                    
                   <div class="modal fade"  id="image_crop_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="image_crop_modal_label" aria-hidden="true">
                    <div class="modal-dialog modal-xl"  >
                        <div class="modal-content">
                            <div class="card-header">
                      
                                 <div class="picClipper__title">
                                    <h3 id="image_crop_modal_label">圖片裁切工具</h3>
                                    <span>比例' . $width_ratio . ':' . $height_ratio . '</span>
                                </div>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-dismiss="modal"><i class="fa fa-remove"></i></button>
                                </div>
                            </div>
                            <div class="card-body">

                                <!--<div class="picClipper__main">-->
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="picClipper__crop">
                                            <img id="show_image" src="">
                                        </div>

                                        <div class="picClipper__button">
                                            <div class="btn-group" style=" margin-top: 10px">
                                                <button type="button" onclick="crop_button(\'move\');" id="move" class="btn btn-primary btn-md bg-blue-gradient fa fa-arrows">&nbsp;移動模式</button>
                                                <button type="button" onclick="crop_button(\'crop\');" id="crop" class="btn btn-primary btn-md bg-blue-gradient fa fa-crop">&nbsp;裁切模式</button>
                                            </div>
                                            <div class="btn-group" style=" margin-top: 10px">
                                                <button type="button" onclick="crop_button(\'zoom_add\');" id="zoom_add" class="btn btn-primary btn-md bg-blue-gradient fa fa-search-plus">&nbsp;放大</button>
                                                <button type="button" onclick="crop_button(\'zoom_less\');" id="zoom_less" class="btn btn-primary btn-md bg-blue-gradient fa fa-search-minus">&nbsp;縮小</button>
                                            </div>
                                            <div class="btn-group" style=" margin-top: 10px">
                                                <button type="button" onclick="crop_button(\'rotate_left\');" id="rotate_left" class="btn btn-primary btn-md bg-blue-gradient fa fa-rotate-left">&nbsp;向左翻轉</button>
                                                <button type="button" onclick="crop_button(\'rotate_right\');" id="rotate_right" class="btn btn-primary btn-md bg-blue-gradient fa fa-rotate-right">&nbsp;向右翻轉</button>
                                            </div>
                                            <div class="btn-group" style=" margin-top: 10px">
                                                <button type="button" onclick="crop_button(\'scaleX\');" id="scaleX" class="btn btn-primary btn-md bg-blue-gradient fa fa-arrows-h">&nbsp;水平翻轉</button>
                                                <button type="button" onclick="crop_button(\'scaleY\');" id="scaleY" class="btn btn-primary btn-md bg-blue-gradient fa fa-arrows-v">&nbsp;垂直翻轉</button>
                                            </div>
                                            <div class="btn-group" style=" margin-top: 10px">
                                                <button type="button" onclick="crop_button(\'reset\');" id="reset" class="btn btn-primary btn-md bg-blue-gradient fa fa-refresh">&nbsp;重置</button>
                                                <button type="button" onclick="crop_button(\'getCroppedCanvas\');" id="getImageData" class="btn btn-primary btn-md bg-blue-gradient fa fa-check" style="background-color:#17a2b8;border-color: #17a2b8;">&nbsp;確認裁切</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                
                                        <div id="image_crop_list" class="picClipper__thumbnail">
                                         <!-- loop_start -->
                                         <!-- loop_end -->
                                        </div>
                                    </div>
                                    
                                </div>
                              
                            </div> 
                            <div class="card-footer">
                                <div class="picClipper__footer">
                                    <div class="card-tools">

                                        <a class="btn btn-info btn-sm " style=" float:right;width:13%;height:66px;color:white;" onclick="check_image_crop();" >
                                            <i class="fa fa-check-circle" style="font-size: 27px;padding-top: 5px;"></i>
                                            <span style="display: block;">裁切完成</span>
                                        </a>
                                        <a class="btn btn-info btn-sm " style=" float:right;width:13%;height:66px;color:white;" data-dismiss="modal" >
                                            <i class="fa fa-ban" style="font-size: 27px;padding-top: 5px;"></i>
                                            <span style="display: block;">取消</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                
                <div class="modal fade"  id="load_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="load_modal_label" aria-hidden="true">
                    <div class="modal-dialog "  >
                        <div class="modal-content">
                            <div class="card-header">
                              
                                    <h3 id="load_modal_label">載入中</h3>
                     
                                
                                <div class="card-tools">
                                   
                                </div>
                            </div>
                            <div class="card-body">
                                請稍候...
                            </div> 
                            <div class="card-footer">
             
                            </div>
                        </div>
                    </div>
                </div> ';

        return $modal_html;
    }

    private function page_script(Crop_option $crop_option) {

        $gcd = $this->gcd($crop_option->output_width, $crop_option->output_height);

        $width_ratio = $crop_option->output_width / $gcd;
        $height_ratio = $crop_option->output_height / $gcd;

        $script = '
              <script type="text/javascript">
                var click_image_id ="";
                var crop;
                var image_div_class = "pic_normal pic_sel pic_edited";
                var check_icon = "fa fa-check";
                var original_image_array = {};
                var upload_image_array = {};
                var is_specel=false;
                var form_id,click_input_id;
                var crop_width = ' . $crop_option->output_width . ',
                  crop_height = ' . $crop_option->output_height . ';';


        $script .= '
    
    
    $( document ).ready(function() {
        $(".image_crop").hide();
        
        $.each($(".image_crop"), function (count, element) {
            
            var id =$(element).attr(\'id\');
            $(element).before( \'<a class="btn btn-info btn-sm" id="crop_\'+id+\'" style=" color:white;" onclick="upload_but_click(\'+id+\');"  >上傳檔案</a>\' );

        });
        
    });
    
        function upload_but_click(id){
           $(id).click();

        }
            
    //----針對.image_crop 做選取檔案監聽，上傳後產生crop 
    $(".image_crop").on("change", function (e) {
    

        form_id=$(this).parents("form").attr("id");
        if(form_id==null){
            is_specel=true;
            form_id=$(this).parent().find(".image_crop_form").val();
        }
        click_input_id=$(this).attr("id");
        
        

        var file_array = $(this).get(0).files;
        var error_msg = checkSize(file_array);

        if (error_msg == "") {

            var formData = new FormData();

            $.each(file_array, function (count, file) {
         
                formData.append("crop_" + count, file);

            });
            

            $.ajax({
                url: "' . base_url("image/image_server/crop") . '",
                type: "POST",
                dataType: "json",
                cache: false,
                processData: false,
                contentType: false,
                data: formData,
                success: function (result) {
                       console.log(result);
                
                    $(".image_crop").val(\'\');
            
                    original_image_array = {};
                    upload_image_array = {};
                    
                    $("#image_crop_list").empty();
                    $("#image_crop_list").append(result.view);
                    
                    if(Object.keys(result.image_array).length>0){
                       click_image_id="crop_0";
             
                       $.each(result.image_array, function (key, value) {
                            original_image_array[key]=value;
                            upload_image_array[key]="";
                        });

                       $("#show_image").attr("src", "");
                       $("#show_image").attr("src", result.image_array[\'crop_0\']);
                       
                        //---裁切初始化
                        if(crop){
                          crop.cropper("destroy");
                        }
                        crop_intint();
                        
                        $("#image_crop_modal").modal().show();
                    }
                    
        
                }, error: function (error_contant) {
                }
            });



        } else {

            alert(error_msg);

        }


    });
    
    function getBase64(file) {
       var reader = new FileReader();
       reader.readAsDataURL(file);
       var result;
       reader.onload = function () {
         reader.result;
       };
       reader.onerror = function (error) {
         console.log(\'Error: \', error);
       };
//        console.log(reader.result);
        return reader;
    }
    

    //----檢查圖片大小
    function checkSize(file_array) {
        var max_kb = 3 * 1024;//--3m
        var error_msg = "";

        $.each(file_array, function (count, file) {

            var size = Math.pow(10, 1);
            var file_kb = Math.round((file.size / 1024) * size) / size;
            var file_name = file.name;

            if (file_kb > max_kb) {
                //---超出檔案上限

                if (error_msg == "") {
                    error_msg += file_name + "超出檔案限制3Mb";
                } else {
                    error_msg += "\n" + file_name + "超出檔案限制3Mb";
                }
            }


        });
        
        return error_msg;

    }
    
    //----刪除圖片
    function del_image(image_id){
        
        var image_div =  $("#" + image_id).parent().parent().parent();
        delete original_image_array[image_id]; 
        delete upload_image_array[image_id]; 
        image_div.remove();
        var data_array_count=Object.keys(original_image_array).length;
        
        if(data_array_count==0){
            $("#image_crop_modal").modal(\'hide\');
        }

    
    }

    //----上傳圖片
    function check_image_crop() {
       
        $.each(upload_image_array, function (img_id, value) {

            if (value == "") {
                var image_element = $("#" + img_id);
                var image_width = image_element.prop("naturalWidth")
                var image_height = image_element.prop("naturalHeight")


                if (crop_width == image_width && crop_height == image_height) {
                    //---比例相同，塞原圖
                    upload_image_array[img_id] = original_image_array[img_id];
                } else {

                    if (crop_width > image_width && crop_height > image_height) {
                        //----裁減匡比原圖大

                        var raio_width = crop_width % image_width;
                        var raio_height = crop_height % image_height;

                        if (raio_width == 0 && raio_height == 0) {
                            //----同比率，塞原圖
                            upload_image_array[img_id] = original_image_array[img_id];
                        }

                    } else {

                        if (crop_width < image_width && crop_height < image_height) {
                            //----裁減匡比原圖小
                            var raio_width = image_width % crop_width;
                            var raio_height = image_height % crop_height;

                            if (raio_width == 0 && raio_height == 0) {
                                //----同比率，塞原圖
                                upload_image_array[img_id] = original_image_array[img_id];
                            }

                        }

                    }

                }

            }
 
        });
        



        //---檢查是否有外裁切圖片
        var count = 0;
        var error_msg = "";
        
        $.each(upload_image_array, function (img_id, value) {
            count += 1;
            if (value == "") {
                error_msg += "圖片" + count + "未裁切！\n";
            }
        });

        if (error_msg == "") {

             $(\'#load_modal\').modal().show();

            //---送出post
            var formData = new FormData();
            $.each(upload_image_array, function (img_id, image_base64) {
                formData.append(img_id, image_base64.replace(/^data:image\/(png|jpg);base64,/, ""));
            });
            
            

            $.ajax(\'' . base_url('image/image_server/upload_image') . '\', {
              method: "POST",
              data: formData,
              dataType: "json",
              cache: false,
              processData: false,
              contentType: false,
              success: function (result) {
                 console.log(result);
                 var output_hidden_class="crop_image_output_"+click_input_id;
                 var result_count=Object.keys(result).length;
                 $("."+output_hidden_class).remove();
                 
                 $(\'#crop_\'+click_input_id).empty();
                 $(\'#crop_\'+click_input_id).append("已編輯"+result_count+"張圖片");
                 
                 var image_hidden_name=(result_count>1)?click_input_id+\'[]\':click_input_id;
              
                var form_obj;
                

                 $.each(result, function (conut, image_base64) {
                    $(\'<input>\').attr({
                        type: \'hidden\',
                        name: image_hidden_name,
                        class:output_hidden_class,
                        value: image_base64
                    }).appendTo($(\'#\'+form_id));
                    
                });
                    $("#load_modal").modal("hide");
                    $("#load_modal").on(\'shown.bs.modal\', function(e) {
                         $("#load_modal").modal("hide");
                        });
                     $("#image_crop_modal").modal(\'hide\');
              },
              error: function () {
                 $("#load_modal").modal(\'hide\');
                 $("#image_crop_modal").modal(\'hide\');
              }
            });





        } else {
            //----印出錯誤
            alert(error_msg);
        }


    }



    function image_click(id) {

        var click_image_src = $("#" + id).attr("src");

        //----選擇的圖加上外框，上張圖去除外框
        var old_parent_div = $("#" + click_image_id).parent().parent();
        var now_parent_div = $("#" + id).parent().parent();

        old_parent_div.removeClass(image_div_class);
        now_parent_div.removeClass(image_div_class);
        
        old_parent_div.addClass("pic_normal");
        now_parent_div.addClass("pic_sel");

        click_image_id = id;
        reoload_crop(click_image_id, click_image_src);

    }


    function reoload_crop(click_image_id, image_src) {

        var image_data;
        if (image_src == "") {
            image_data = original_image_array[click_image_id];
            upload_image_array[click_image_id] = "";

            //----重置已修改效果
//            var parent_div = $("#" + click_image_id).parent().parent();
//            parent_div.removeClass(image_div_class);
//            parent_div.addClass("pic_sel");
//            parent_div.find("span").removeClass(check_icon);
            
            var parent_div = $("#" + click_image_id).parent();
            var image_p=parent_div.find("p");
            var image_span=image_p.find("span.alert_edited");
                
            image_span.empty();
            image_span.removeClass("alert_edited");
            image_span.addClass("alert_none");
            image_span.append("尚未裁切");

        } else {
            image_data = image_src;
        }


        $("#show_image").attr("src", image_data);
        crop.cropper("destroy");
        crop_intint();
    }

    function crop_intint() {

        if (click_image_id == "") {
            var count = 0;
            $.each(original_image_array, function (img_id, value) {
                click_image_id = img_id;
                $("#show_image").attr("src", value);

                var parent_div = $("#" + img_id).parent().parent();
                parent_div.removeClass(image_div_class);
                parent_div.addClass("pic_sel");

                return false;
            });
        }


        crop = $("#show_image").cropper({
            aspectRatio: ' . $width_ratio . ' / ' . $height_ratio . ',
            viewMode: 1
        });
  



    }

    function crop_button(but_id) {

        switch (but_id) {
            case "zoom_add":

                crop.cropper("zoom", 0.1);
                break;

            case "zoom_less":

                crop.cropper("zoom", -0.1);

                break;

            case "rotate_left":

                crop.cropper("rotate", -45)

                break;


            case "rotate_right":

                crop.cropper("rotate", 45)

                break;

            case "scaleX":
            case "scaleY":
                var image_data = crop.cropper("getData");
                var value;
                if (but_id == "scaleX") {
                    value = image_data.scaleX * -1;
                } else {
                    value = image_data.scaleY * -1;
                }


                crop.cropper(but_id, value);
                break;

            case "move":
            case "crop":
                crop.cropper("setDragMode", but_id);

                break;

            case "reset":

                var original_image = original_image_array[click_image_id];
                $("#" + click_image_id).attr("src", original_image);

                reoload_crop(click_image_id, "");

                break;

            case "getCroppedCanvas":

                var image_canvas = crop.cropper("getCroppedCanvas", {
                    width: crop_width,
                    height: crop_height,
                    checkImageOrigin: false
                });
                var base64 = image_canvas.toDataURL("image/png");
                upload_image_array[click_image_id] =base64;
                $("#" + click_image_id).attr("src", base64);

                //---更改外框
                var parent_div = $("#" + click_image_id).parent();
                var image_p=parent_div.find("p");
                var image_span=image_p.find("span.alert_none");
                
                image_span.empty();
                image_span.removeClass("alert_none");
                image_span.addClass("alert_edited");
                image_span.append("裁切成功");
               
               

                break;

            default:
                // alert(but_id);
                crop.cropper(but_id);

                break;

        }

    }
              </script>';

        return $script;
    }

}