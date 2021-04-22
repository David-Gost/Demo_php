<?php

/**
 * @property Sys_item_dto $sys_item_dto
 */
class Sys_item_model extends AC_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("dto/Sys_item_dto", "sys_item_dto");

        $this->_table = 'custom_sys_item';
        $this->_dto = 'Sys_item_dto';
    }

    public function find_all() {
        $this->db->order_by('sequence,sid desc');
        $data = $this->all();
        return $data;
    }

    public function find_list_by_classification_sid($key, $offset = '', $limit = '') {
        $t_temp = $this->db->select()->from('custom_sys_item_classification')->get()->result();
        $key_ary = array_column($t_temp, "sid", "cname");

        $classification_sid = $key;
        if (array_key_exists($key, $key_ary)) {
            $classification_sid = $key_ary[$key];
        }

        $sql = "SELECT
	custom_sys_item.*
FROM
	custom_sys_item
WHERE
	custom_sys_item.classification_sid = '$classification_sid' ORDER BY custom_sys_item.sequence,custom_sys_item.sid DESC";

        if (empty($offset) && empty($limit)) {
            $data = $this->query_by_sql($sql, '', true);
        } else {
            $data = $this->query_page_by_sql($sql, $offset, $limit, true);
        }
        return $data;
    }

}
