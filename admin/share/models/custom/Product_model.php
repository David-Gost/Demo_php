<?php

/**
 * @property Product_dto $product_dto
 */
class Product_model extends AC_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("dto/Product_dto");

        $this->_table = 'custom_product';
        $this->_dto = 'Product_dto';
    }

    /**
     * 找商品清單
     *
     * @param string $store_sid
     * @param string $search_key
     * @param integer $limit
     * @param integer $offset
     * @return stdClass
     */
    public function find_product_array($store_sid = "", $search_key = "", $offset = 0, $limit = 0)
    {

        $this->db->select("custom_product.sid,custom_product.name,ifnull(data_count,0)as data_count")
            ->from("custom_product")
            ->join("(select custom_product.sid as productSid,count(custom_product_attr.sid)as data_count from custom_product
        left join custom_product_attr on custom_product_attr.productSid = custom_product.sid
        group by custom_product.sid)as detail_data", 'detail_data.productSid = custom_product.sid', 'left')
            ->where("custom_product.storeSid", $store_sid)
            ->like("custom_product.name", $search_key)
            ->order_by("custom_product.sid desc");

        if (strlen($offset) > 0) {
            $this->db->offset($offset);
        }

        if ($limit > 0) {
            $this->db->limit($limit);
        }

        // echo '<pre>', print_r($this->db->get_compiled_select()), '</pre>';
        // exit;

        $reult = $this->db->get()->result();

        return $reult;
    }

    /**
     * 刪除商品資料
     *
     * @param string $store_sid
     * @return boolean
     */
    public function del_product_data($product_sid = "")
    {

        $this->db->trans_start();

        $this->db->query("delete custom_product,custom_product_attr
        from custom_product
        left join custom_product_attr on custom_product_attr.productSid = custom_product.sid
        where custom_product.sid =  $product_sid");

        $this->db->trans_complete();

        return true;
    }
}
