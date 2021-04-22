<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Company_job_title_dto $company_job_title_dto
 */
class Company_job_title_model extends AC_Model {

    public function __construct() {
        parent::__construct();

        $this->load->library("dto/Company_job_title_dto", "company_job_title_dto");

        $this->_table = 'sys_company_job_title';
        $this->_dto = 'Company_job_title_dto';
    }

    /**
     * 
     * @return Company_job_title_dto
     */
    public function find_all() {

        $data = $this->all();
        return $data;
    }

    /**
     * 
     * @param type $company_job_title_dto
     * @return insert_id
     */
    public function add_com_job_title(Company_job_title_dto $company_job_title_dto) {

        $data = object_to_array($company_job_title_dto);
        return $this->create($data);
    }

    public function modify_com_job_title($sid,Company_job_title_dto $company_job_title_dto) {

        $props = object_to_array($company_job_title_dto);
        return $this->update($sid, $props);
    }

    /**
     * 
     * @param type $sid
     * @return affected_rows
     */
    public function del_job_title($sid) {

        return $this->delete($sid);
    }

    /**
     * 
     * @param type $sid
     * @return Company_job_title_dto
     */
    public function find_by_id($sid) {

        $where = array(
            'sid' => $sid
        );
        return $this->find($where)->get();
    }
    
    /**
     * 
     * @param type $first_option_value 第一個選項值
     * @param type $first_option_text 第一個選項文字
     * @return string
     */
    public function get_company_job_option($first_option_value = '', $first_option_text = '') {

        $company_job_list = $this->find_all();
        if (!empty($first_option_text) && !empty($first_option_text)) {
            $company_job_arry = [
                $first_option_value => $first_option_text
            ];
        } else {
            $company_job_arry = [];
        }
        foreach ($company_job_list as $company_job_dto) {

            $company_job_arry[$company_job_dto->sid] = $company_job_dto->cname;
        }
        return $company_job_arry;
    }

}
