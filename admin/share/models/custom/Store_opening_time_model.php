<?php

/**
 * @property Store_opening_time_dto $store_opening_time_dto
 */
class Store_opening_time_model extends AC_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("dto/Store_opening_time_dto");

        $this->_table = 'custom_store_opening_time';
        $this->_dto = 'Store_opening_time_dto';
    }

    /**
     * 找營業時間
     *
     * @param string $relation_sid
     */
    public function find_time_by_relation($relation_sid = "", $is_api = true)
    {

        if ($is_api) {
            $column = "concat(startTime,' - ',endTime)as openTime";
        } else {
            $column = "*";
        }

        $this->db->select($column)
            ->from("custom_store_opening_time")
            ->where('relationSid', $relation_sid);

        $reult = $this->db->get()->result();

        return $reult;
    }
}
