<?php

/**
 * @property Store_transaction_log_dto $store_transaction_log_dto
 */
class Store_transaction_log_model extends AC_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("dto/Store_transaction_log_dto");

        $this->_table = 'custom_store_transaction_log';
        $this->_dto = 'Store_transaction_log_dto';
    }
    
}
