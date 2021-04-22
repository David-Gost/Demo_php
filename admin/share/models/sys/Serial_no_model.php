<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Serial_no_model extends AC_Model {

    public function __construct() {

        parent::__construct();

        $this->load->library('dto/Serial_no_dto', 'serial_no_dto');
        $this->_table = 'sys_serial_no';
        $this->_dto = 'Serial_no_dto';
    }

    public function get_serial_no($no_type, $year_month_date) {

        $where = array(
            'no_type' => $no_type,
            'year_month_date' => $year_month_date
        );
        return $this->find($where)->get();
    }

    public function add_serial_no(Serial_no_dto $serial_no_dto) {
        
        $data = object_to_array($serial_no_dto);
        return $insert_id = $this->create($data);
    }

    public function update_serial_no(Serial_no_dto $serial_no_dto) {

        $data = object_to_array($serial_no_dto);
        $affected_rows = $this->update($serial_no_dto->sid, $data);
        return $affected_rows;
    }
    
    function create_sno($props, $unset_array = []) {
        
        for ($i = 0; $i < sizeof($unset_array); $i++) {

            unset($props[$unset_array[$i]]);
        }

        $this->db->insert($this->_table, $props);
        $this->log_sql();
        return $this->db->insert_id();
    }

}
