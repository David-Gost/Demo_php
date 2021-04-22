<?php

class Files_model extends AC_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->library("dto/Files_dto", "files_dto");

        $this->_table = 'sys_files';
        $this->_dto = 'files_dto';
    }
}
