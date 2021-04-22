<?php

/**
 * @property Sys_item_classification_dto $sys_item_classification_dto
 */
class Sys_item_classification_model extends AC_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->library("dto/Sys_item_classification_dto", "sys_item_classification_dto");

        $this->_table = 'custom_sys_item_classification';
        $this->_dto = 'Sys_item_classification_dto';
    }

    public function find_list()
    {

        if (get_seesion_user()['groups_sid'] != 1) {
            $this->db->where("custom_sys_item_classification.is_show = 1");
        }

        $this->db->select("custom_sys_item_classification.*")
            ->from($this->_table)
            ->order_by("custom_sys_item_classification.sequence,custom_sys_item_classification.sid DESC");

        $reult = $this->db->get()->result();

        return $reult;
    }
}
