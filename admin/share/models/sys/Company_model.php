<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property Company_dto $company_dto
 *
 */
class Company_model extends AC_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();


        $this->load->library("dto/Company_dto", "company_dto");

        $this->_table = 'sys_company';
        $this->_dto = 'Company_dto';
    }

    /**
     * 
     * @return company_dto 
     */
    public function find_all() {
        
        $sql = 'select sys_cityarea1.area_name as city_name, sys_cityarea.area_name,sys_company.*
                from sys_company
                left join sys_cityarea on sys_company.area_sid = sys_cityarea.sid
                left join sys_cityarea as sys_cityarea1 on sys_company.city_sid = sys_cityarea1.sid
                ';
        
        $sql .= 'order by sequence, sid';
        
        $data = $this->query_by_sql($sql);
        
        return $data;
    }

    /**
     *
     * @param Company_dto $company_dto
     * @return int insert_id
     */
    public function add_company(Company_dto $company_dto) {

        $data = object_to_array($company_dto);

        return $this->create($data);
    }

    public function modify_company(Company_dto $company_dto) {

        $data = object_to_array($company_dto);

        $affected_rows = $this->update($company_dto->sid, $data);

        return $affected_rows;
    }

    /**
     *
     * @param type $sid
     * @return int affected_rows
     */
    public function del_company($sid) {

        return $this->delete($sid);
    }

    /**
     *
     * @param type $sid
     * @return Company_dto
     */
    public function find_company_by_sid($sid) {
        
        $sql = 'select sys_cityarea1.area_name as city_name, sys_cityarea.area_name,sys_company.*
                from sys_company
                left join sys_cityarea on sys_company.area_sid = sys_cityarea.sid
                left join sys_cityarea as sys_cityarea1 on sys_company.city_sid = sys_cityarea1.sid
                where sys_company.sid = ? ';
        
        $where = [$sid];
        
        $data = $this->query_by_sql($sql, $where, FALSE);
        
        return $data;
    }

}
