<?php

/**
 * @property Store_opening_date_dto $store_opening_date_dto
 */
class Store_opening_date_model extends AC_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("dto/Store_opening_date_dto");

        $this->_table = 'custom_store_opening_date';
        $this->_dto = 'Store_opening_date_dto';
    }

    /**
     * 找店家已設定營業時間
     *
     * @param string $store_sid
     * @return array
     */
    public function find_store_week_array($store_sid=""){

        $this->db->select("group_concat(weekName order by weekNum)as weekName")
        ->from("custom_store_opening_date")
        ->where("storeSid",$store_sid)
        ->group_by('storeSid');

        $data_dto=$this->db->get()->row(0);

        if($data_dto){
            $data_array=explode(',',$data_dto->weekName);
        }else{
            $data_array=array();
        }

        return $data_array;

    }
    
}
