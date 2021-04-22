<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Sysinfo_model $sysinfo_model
 * @property Sysinfo_dto $sysinfo_dto
 * @property Module_model $module_model
 * @property Module_dto $module_dto
 * @property Account_model $account_model
 */
class Ajax_uploadfile_del extends AJAX_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model("sys/Sysinfo_model", "sysinfo_model");
        $this->load->library("dto/Sysinfo_dto", "sysinfo_dto");
        $this->load->model("sys/Module_model", "module_model");
        $this->load->library('dto/Module_dto', 'module_dto');
        $this->load->model("sys/Account_model", "account_model");
        $this->load->library("dto/Account_dto", "account_dto");
    }

    public function del_sysinfo_bgpic() {
        $sid = $this->input->post('sid');

        $this->sysinfo_dto = $this->sysinfo_model->find_sysinfo();
        $filename = $this->sysinfo_dto->getBgpic();
        if ($filename != 'd_pgpic1.jpg' && $filename != 'd_pgpic2.jpg' && $filename != 'd_pgpic3.jpg' && $filename != 'd_pgpic4.jpg') {
            del_file($filename);
        }
        $affected_rows = $this->sysinfo_model->update_data($sid, array('bgpic' => ''));

        if ($affected_rows > 0) {
            echo json_encode(array('result' => $affected_rows));
        } else {
            echo json_encode(array('result' => 0));
        }

        exit();
    }

    public function del_products_file() {

        $sid = $this->input->post('sid');

        $this->products_dto = $this->products_model->find_products_by_sid($sid);
        $filename = $this->products_dto->getFileinfo();
        del_file($filename);

        $affected_rows = $this->products_model->update($sid, ['fileinfo' => '']);

        if ($affected_rows > 0) {
            echo json_encode(array('result' => $affected_rows));
        } else {
            echo json_encode(array('result' => 0));
        }

        exit();
    }

    public function del_sysinfo_ico() {

        $sid = $this->input->post('sid');

        $this->sysinfo_dto = $this->sysinfo_model->find_sysinfo();
        $filename = $this->sysinfo_dto->getSysico();
        del_file($filename);

        $affected_rows = $this->sysinfo_model->update_sysico($sid);

        if ($affected_rows > 0) {
            echo json_encode(array('result' => $affected_rows));
        } else {
            echo json_encode(array('result' => 0));
        }

        exit();
    }

    public function del_seo_ico() {

        $sid = $this->input->post('sid');

        exit;
    }

    public function del_pic() {

        $sid = $this->input->post('sid');
        $field = $this->input->post('field');
        $table = $this->input->post('table');
        $view = $this->input->post('view');
        $origin = $this->input->post('origin');

        switch ($table . $field) {
            case 'sys_account' . 'picinfo':
            case 'custom_teacher' . 'picinfo':
            case 'custom_student' . 'picinfo':
            case 'custom_aboutus' . 'picinfo':
            case 'custom_course_classification' . 'picinfo':
            case 'custom_activity' . 'picinfo':
            case 'custom_news' . 'picinfo':
            case 'custom_nav_banner' . 'picinfo':
            case 'custom_aboutus' . 'picinfo':
            case 'custom_journal' . 'picinfo':
                $this->db->where('sid', $sid);
                $old_dto = $this->db->get($table)->result();
                $dto = object_to_array(array_shift($old_dto));
                del_file($dto[$field]);
                $rows = $this->db->update($table, [$field => '']);
                break;
            default :
                break;
        }

        if ($table == 'sys_account') {
            $this->account_dto = $this->account_model->find_account_by_sid(get_seesion_user()['sid']);
            if (sizeof($this->account_dto)) {
                $this->module_dto = $this->module_model->find_module_by_account($this->account_dto->groups_sid);
                $account_array = object_to_array($this->account_dto);
                $module_array = object_to_array($this->module_dto);
                $append_array = array_merge($account_array, array("module" => $module_array));

                login_session_user($append_array);
            }
        }

        if ($rows > 0) {
            echo json_encode(array('result' => $rows, 'view' => $view, 'origin' => $origin));
        } else {
            echo json_encode(array('result' => 0, 'view' => $view, 'origin' => $origin));
        }
        exit();
    }

}
