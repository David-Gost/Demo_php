<?php

/**
 * @property Attributes_dto $attributes_dto
 */
class Attributes_model extends AC_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("dto/Attributes_dto");

        $this->_table = 'custom_attributes';
        $this->_dto = 'Attributes_dto';
    }

    /**
     * <p>取得所有參數分類</p>
     * @return type
     */
    public function find_attributes_by_paretn_sid($parent_sid = 0)
    {
        $this->db->select("custom_attributes.*,ifnull(data_count,0)as data_count")
            ->from($this->_table)
            ->join('(select custom_attributes.sid,count(child_attr.sid)as data_count from custom_attributes
            left join custom_attributes as child_attr on child_attr.parent_sid = custom_attributes.sid
            where custom_attributes.parent_sid =0
            group by custom_attributes.sid
            )as child_data',"child_data.sid = custom_attributes.sid","left")
            ->where("parent_sid", $parent_sid);
        $this->db->order_by("sequence ,sid desc");

        $result_array = $this->db->get()->result();

        return $result_array;
    }


    /**
     * <p>取得所有參數分類</p>
     * @return array
     */
    public function find_attributes_by_paretn_tag($parent_tag = "",$is_api=true)
    {
        if($is_api){
            $column="custom_attributes.tag";
        }else{
            $column="custom_attributes.*";
        }
        $this->db->select($column)
            ->from($this->_table)
            ->join("custom_attributes as parent_attrs", "parent_attrs.sid = custom_attributes.parent_sid")
            ->where("parent_attrs.tag", $parent_tag);
        $this->db->order_by("custom_attributes.sequence ,custom_attributes.sid desc");

        $result_array = $this->db->get()->result();

        return $result_array;
    }

    /**
     * 檢查項目是否存在
     *
     * @param string $parent_tag
     * @param string $child_tag
     * @return attributes_dto
     */
    public function find_child_tag_dto($parent_tag = "", $child_tag = "")
    {

        $this->db->select("custom_attributes.*")
            ->from($this->_table)
            ->join("custom_attributes as parent_attrs", "parent_attrs.sid = custom_attributes.parent_sid")
            ->where("parent_attrs.tag", $parent_tag)
            ->where("custom_attributes.tag", $child_tag);

        $result_data = $this->db->get()->row(0, $this->_dto);

        return $result_data;
    }

    /**
     * 檢查項目是否存在
     *
     * @param string $parent_tag
     * @param string $child_tag
     * @return boolean
     */
    public function check_child_tag_exit($parent_tag = "", $child_tag = "")
    {

        if ($parent_tag == "" || $child_tag == "") {
            return false;
        }

        $this->db->select("custom_attributes.*,parent_attrs.tag as parent_tag")
            ->from($this->_table)
            ->join("custom_attributes as parent_attrs", "parent_attrs.sid = custom_attributes.parent_sid")
            ->where("parent_attrs.tag", $parent_tag)
            ->where("custom_attributes.tag", $child_tag);

        $result_num = $this->db->get()->num_rows();

        if ($result_num == 0) {
            return false;
        } else {
            return true;
        }
    }
}
