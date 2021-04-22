<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property Viewcount_dto $viewcount_dto
 *
 * 
 */
class Viewcount_model extends AC_Model {


    public function __construct() {
        parent::__construct();

        $this->load->library("dto/Viewcount_dto", "viewcount_dto");

        $this->_table = 'sys_viewcount';
        $this->_dto = 'Viewcount_dto';
             
    }

   public function add_view_count($add_ary) {
        $this->db->insert($this->_table, $add_ary);
        return $this->db->insert_id();
    }
    
    
    public function get_visitors($startdate = '', $enddate = '') {

        $sql = "select COALESCE(COUNT(sv.sid),0) as total
from sys_viewcount sv
where sv.sid != 0 ";

        if ($startdate != '') {
            $sql.= " and sv.viewdate >= '" . $startdate . "'";
        }

        if ($enddate != '') {
            $sql.= " and sv.viewdate <= '" . $enddate . "'";
        }

        $data = $this->query_by_sql($sql, '', FALSE);
        return $data->total;
    }
    
    
}
