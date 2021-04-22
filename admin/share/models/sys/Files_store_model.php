<?php

class Files_store_model extends AC_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->library("dto/Sys_files_store_dto", "sys_files_store_dto");

        $this->_table = 'sys_files_store';
        $this->_dto = 'Sys_files_store_dto';
    }
}
