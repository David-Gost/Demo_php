<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Sysinfo_dto $sysinfo_dto
 */
class Sysinfo_model extends AC_Model {

    public function __construct() {
        parent::__construct();

        $this->load->library("dto/Sysinfo_dto", "sysinfo_dto");

        $this->_table = 'sys_sysinfo';
        $this->_dto = 'Sysinfo_dto';
    }

    /**
     * 
     * @return Sysinfo_dto 
     */
    public function find_sysinfo() {

        $data = $this->get();
        return $data;
    }
    
    public function modify_sysinfo(Sysinfo_dto $sysinfo_dto) {

        $props = object_to_array($sysinfo_dto);
//        $this->fb->warn($props, 'array');
        return $this->update($sysinfo_dto->sid, $props);
    }

    public function update_sysico($sid) {

        $props = array(
            'sysico' => ''
        );
        return $this->update($sid, $props);
    }
    
     public function update_data($sid, $up_ary) {

        $this->db->where('sid', $sid);
        $this->db->update($this->_table, $up_ary);
        return $this->db->affected_rows();
    }

}
