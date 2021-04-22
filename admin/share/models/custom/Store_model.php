<?php

/**
 * @property Store_dto $store_dto
 */
class Store_model extends AC_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("dto/Store_dto");

        $this->_table = 'custom_store';
        $this->_dto = 'Store_dto';
    }

    /**
     * 以金額篩選店家販賣商品
     *
     * @return stdClass
     */
    public function filter_by_price($min_price = 0.0, $max_price = 0.0)
    {

        $this->db->select("
        custom_product.storeSid,
        custom_store.`name` as pharmacyName")
            ->from("custom_product_attr")
            ->join('custom_product', 'custom_product.sid = custom_product_attr.productSid', 'inner')
            ->join("custom_store", "custom_store.sid =custom_product.storeSid", "inner")
            ->where('custom_product_attr.price between "' . $min_price . '" and "' . $max_price . '"')
            ->group_by("custom_product.storeSid")
            ->order_by("custom_product.storeSid");
            $reult=$this->db->get()->result();
        
            return $reult;
    }

    /**
     * 關鍵字搜尋
     *
     * @param string $search_key
     * @return stdClass
     */
    public function search_by_key($search_key = "")
    {
        $this->db->select("
        custom_store.sid as storeSid,
        custom_store.`name` as pharmacyName")
            ->from("custom_store")
            ->like("custom_store.`name`", $search_key)
            ->order_by("position('$search_key' in custom_store.`name`)");

            $reult=$this->db->get()->result();
        
            return $reult;
    }

    /**
     * 店家清單
     *
     * @return void
     */
    public function find_store_full_info_array($search_key,$offset = 0, $limit = 0){

        $cash_sql=$this->find_store_now_cash_sql();

        $this->db->select("
        custom_store.sid as storeSid,
        custom_store.`name` as pharmacyName,
        ifnull(cash_data.cashBalance,0.0)as cashBalance")
            ->from("custom_store")
            ->join("($cash_sql)as cash_data","cash_data.storeSid = custom_store.sid","left")
            ->like('custom_store.`name`',$search_key)
            ->order_by("sid desc");

            if (strlen($offset) > 0) {
                $this->db->offset($offset);
            }
    
            if ($limit > 0) {
                $this->db->limit($limit);
            }

            $reult=$this->db->get()->result();
        
            return $reult;

    }

    /**
     * 刪除店家資料
     *
     * @param string $store_sid
     * @return boolean
     */
    public function del_store_data($store_sid=""){

        $this->db->trans_start();

        $this->db->query("delete from custom_store where sid = $store_sid");
        $this->db->query("delete from custom_store_opening_date where storeSid = $store_sid");
        $this->db->query("delete custom_store_opening_time,custom_store_opening_relation from custom_store_opening_relation
        left join custom_store_opening_time on custom_store_opening_relation.sid = custom_store_opening_time.relationSid
        where custom_store_opening_relation.storeSid = $store_sid");
        $this->db->query("delete custom_product,custom_product_attr from custom_product_attr
        left join custom_product on custom_product.sid = custom_product_attr.productSid
        where custom_product.storeSid = $store_sid");
        $this->db->query("delete from custom_store_transaction_log where storeSid = $store_sid");

        $this->db->trans_complete();

        return true;
    }

    /**
     * 取得餘額
     *
     * @return string
     */
    private function find_store_now_cash_sql(){

        $this->db->select("storeSid,sum(transactionAmount)as cashBalance")
        ->from("custom_store_transaction_log")
        ->group_by("storeSid");

        return $this->db->get_compiled_select();
    }
}
