<?php

/**
 * @property Product_attr_dto $product_attr_dto
 */
class Product_attr_model extends AC_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("dto/Product_attr_dto");

        $this->_table = 'custom_product_attr';
        $this->_dto = 'Product_attr_dto';
    }


    /**
     * 搜尋單一商品
     *
     * @param string $store_name
     * @param string $product_name
     * @param string $color_tag
     * @param string $type_tag
     * @return stdClass
     */
    public function find_mask_dto($store_name = "", $product_name = "", $color_tag = "", $type_tag = "")
    {
        $search_sql = $this->get_mask_search_sql();
        $this->db->select("*")
            ->from("($search_sql)as mask_data");

        $this->db->group_start();
        $this->db->like('mask_data.pharmacyName', $store_name);
        $this->db->like('mask_data.maskName', $product_name);
        $this->db->like('mask_data.colorTag', $color_tag);
        $this->db->like('mask_data.typeTag', $type_tag);
        $this->db->group_end();

        return $this->db->get()->row(0);
    }

    /**
     * 找店家販賣商品
     *
     * @param string $store_sid
     * @return stdClass
     */
    public function find_product_by_store_sid($store_sid = "")
    {

        $this->db->select("
        custom_product_attr.sid as productAttrSid,
        custom_product.storeSid,
        custom_store.`name` as pharmacyName,
        concat(custom_product.`name`,' (',custom_product_attr.colorTag,') (',custom_product_attr.typeTag,')')as maskName ,
        custom_product_attr.price")
            ->from("custom_product_attr")
            ->join('custom_product', 'custom_product.sid = custom_product_attr.productSid', 'inner')
            ->join('custom_store','custom_store.sid = custom_product.storeSid','inner')
            ->where('custom_product.storeSid', $store_sid)
            ->order_by("custom_product.storeSid,custom_product_attr.price");

            $reult=$this->db->get()->result();
        
            return $reult;
    }

    /**
     * 以金額篩選店家販賣商品
     *
     * @return stdClass
     */
    public function filter_product_by_price($min_price = 0.0, $max_price = 0.0)
    {

        $this->db->select("
        custom_product_attr.sid as productAttrSid,
        custom_product.storeSid,
        custom_store.`name` as pharmacyName,
        concat(custom_product.`name`,' (',custom_product_attr.colorTag,') (',custom_product_attr.typeTag,')')as maskName ,
        custom_product_attr.price")
            ->from("custom_product_attr")
            ->join('custom_product', 'custom_product.sid = custom_product_attr.productSid', 'inner')
            ->join("custom_store", "custom_store.sid =custom_product.storeSid", "inner")
            ->where('custom_product_attr.price between "' . $min_price . '" and "' . $max_price . '"')
            ->order_by("custom_product_attr.price");
        // echo '<pre>',$this->db->get_compiled_select(),'</pre>';
        // exit;
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
        $product_sql=$this->get_mask_search_sql();
        $this->db->select("
        productAttrSid,
        maskName,
        storeSid,
        pharmacyName,
        price")
            ->from("($product_sql)as product_info")
            ->like("maskName", $search_key)
            ->order_by("position('$search_key' in maskName)");

            $reult=$this->db->get()->result();
        
            return $reult;
    }

    /**
     * 搜尋產品完整資訊
     *
     * @param string $sid
     * @return stdClass
     */
    public function find_product_full_info_by_sid($sid=""){
        $product_sql=$this->get_mask_search_sql();

        $this->db->select("*")
        ->from("($product_sql)as product_info")
        ->where("productAttrSid",$sid);

        return $this->db->get()->row(0);
    }

    /**
     * 找規格清單
     *
     * @param string $product_sid
     * @return array
     */
    public function find_attr_array_by_product_sid($product_sid=""){

        $this->db->select("sid,colorTag,typeTag,price")
        ->from("custom_product_attr")
        ->where("productSid",$product_sid)
        ->order_by("sid desc");

        $reult=$this->db->get()->result();
        
        return $reult;

    }

    /**
     * 產生搜尋語法
     *
     */
    private function get_mask_search_sql()
    {

        $this->db->select("concat(custom_product.`name`,' (',custom_product_attr.colorTag,') (',custom_product_attr.typeTag,')')as maskName,
        custom_product.storeSid,
        custom_store.`name` as pharmacyName,
        custom_product_attr.sid as productAttrSid,
        custom_product_attr.colorTag,
        custom_product_attr.typeTag,
        custom_product_attr.productSid,
        custom_product_attr.price")
            ->from("custom_product_attr")
            ->join("custom_product", "custom_product.sid = custom_product_attr.productSid", "inner")
            ->join("custom_store", "custom_product.storeSid = custom_store.sid", "inner");

        return $this->db->get_compiled_select();
    }
}
