<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Front_set_model $front_set_model
 * @property Front_set_dto $front_set_dto
 */
class Ajax_set_area extends AJAX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("custom/Front_set_model", "front_set_model");
        $this->load->library("dto/Front_set_dto", "front_set_dto");
    }

    public function load_area_data() {

        $area_cname = $this->input->post('area_cname');
        $dto = $this->front_set_model->find_by_cname($area_cname);

//        echo '<pre>',print_r($dto),'</pre>'; 
        $fileinfo_ary = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16, 17, 18);
        $title_ary = array(2, 3, 4, 5, 6, 8, 12, 13, 14, 15, 16, 17, 18);
        $msg_ary = array(1, 2, 3, 4, 5, 6, 8, 11, 12, 13, 14, 15, 16, 17, 18);

        $data_ary = array(
            'sid' => $dto->sid,
            'title_show' => ((in_array($dto->sid, $title_ary)) ? 1 : 0),
            'title' => $dto->title,
            'fileinfo_show' => ((in_array($dto->sid, $fileinfo_ary)) ? 1 : 0),
            'fileinfo' => base_url(get_fullpath_with_file($dto->fileinfo)),
            'hid_fileinfo' => $dto->fileinfo,
            'img_size' => $dto->img_size,
            'msg_show' => ((in_array($dto->sid, $msg_ary)) ? 1 : 0),
            'msg' => $dto->msg
        );

        echo json_encode($data_ary);
        exit();
    }

}
