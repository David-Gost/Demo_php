<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 */
class Ajax_change extends AJAX_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function modify_field_val() {

        $table = $this->input->post('table');
        $field = $this->input->post('field');
        $sid = $this->input->post('sid');
        $val = $this->input->post('val');

        $msg = '';
        //確保資料表可修改欄位
        switch ($table . $field) {
            case 'sys_account' . 'isuse':
                $this->db->where('sid', $sid);
                $rows = $this->db->update($table, [$field => $val]);

                if ($rows) {
                    $msg = 'OK';
                } else {
                    $msg = '設定錯誤';
                }
                break;
            default :
                break;
        }

        echo json_encode($msg);
        exit();
    }

}
