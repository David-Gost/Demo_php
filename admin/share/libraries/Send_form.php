<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of send_from
 *
 * @author David
 */
class Send_form {

    const TYPE_HTML = "html";
    const TYPE_CURL = "curl";

    /**
     * 
     * @param Send_form_dto $send_form_dto
     * @return type
     */
    public function start_send(Send_form_dto $send_form_dto) {

        switch ($send_form_dto->form_type) {
            case Send_form::TYPE_HTML:

                return $this->start_send_html($send_form_dto);

                break;

            case Send_form::TYPE_CURL:

                $result = $this->start_send_curl($send_form_dto);
                return $result;
        }
    }

    /**
     * 
     * @param Send_form_dto $send_form_dto
     * @return type
     */
    private function start_send_html(Send_form_dto $send_form_dto) {

        $type = $send_form_dto->is_post ? "post" : "get";


        $send_view = '<form id="send_data_form" action="' . $send_form_dto->url . '" target="' . $send_form_dto->target_type . '" method="' . $type . '" enctype="multipart/form-data">';

        $data_array = object_to_array($send_form_dto->data);

        foreach ($data_array as $key => $value) {
            $send_view.='<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }

//        $send_view.='
//                <input type="submit" value="Submit">
//            </form>';

        $send_view.='
                <script>
                    document.getElementById("send_data_form").submit();
                </script>
            </form>';

        echo $send_view;
    }

    /**
     * 
     * @param Send_form_dto $send_form_dto
     * @return type
     */
    private function start_send_curl(Send_form_dto $send_form_dto) {

        $ch = curl_init();
        $url = '';

        $data_array = object_to_array($send_form_dto->data);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



        if ($send_form_dto->is_post) {

            $url = $send_form_dto->url;

            curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_array));

            if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            }
        } else {

            if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }

            $url.='?' . http_build_query($data_array);
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        $reault = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Errno' . curl_error($ch); //捕抓异常
            exit;
        }


        curl_close($ch);



        return $reault;
    }

}

class Send_form_dto {

    public $url;
    public $is_post = true;
    public $form_type;
    public $data;
    public $target_type = '_blank';

    function getUrl() {
        return $this->url;
    }

    function getIs_post() {
        return $this->is_post;
    }

    function getForm_type() {
        return $this->form_type;
    }

    function getData() {
        return $this->data;
    }

    function getTarget_type() {
        return $this->target_type;
    }

    function setTarget_type($target_type) {
        $this->target_type = $target_type;
    }

    /**
     * 
     * @param type $url 
     */
    function setUrl($url) {
        $this->url = $url;
    }

    /**
     * 
     * @param type $is_post
     * <p>post 或是 get</p>
     */
    function setIs_post($is_post) {
        $this->is_post = $is_post;
    }

    /**
     * 
     * @param type $form_type
     * <p>html or curl</p>
     */
    function setForm_type($form_type) {
        $this->form_type = $form_type;
    }

    /**
     * 
     * @param type $data
     * <p>dto or 帶key的stdclass</p>
     */
    function setData($data) {
        $this->data = $data;
    }

}
