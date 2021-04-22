<?php

/**
 * @property Store_opening_relation_dto $store_opening_relation_dto
 */
class Store_opening_relation_model extends AC_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("dto/Store_opening_relation_dto");

        $this->_table = 'custom_store_opening_relation';
        $this->_dto = 'Store_opening_relation_dto';
    }

    /**
     * 查指定日期時間營業店家
     *
     * @param string $date
     * @param string $time
     * @return stdClass
     */
    public function find_store_open_by_date($date = "", $time = "")
    {

        $this->db->select("
        custom_store_opening_relation.storeSid,
        custom_store.`name` as pharmacyName")
            ->from("custom_store_opening_relation")
            ->join("custom_store_opening_date", "custom_store_opening_date.storeSid = custom_store_opening_relation.storeSid 
        and find_in_set (custom_store_opening_date.sid,custom_store_opening_relation.openingDateSid)", "left")
            ->join("custom_store_opening_time", "custom_store_opening_time.relationSid = custom_store_opening_relation.sid", "left")
            ->join("custom_store", "custom_store.sid =custom_store_opening_relation.storeSid", "inner");

        $this->db->where('custom_store_opening_date.weekNum = date_format("' . $date . '","%w")');

        if ($time != "") {
            $this->db->where('"' . $time . '" between custom_store_opening_time.startTime and custom_store_opening_time.endTime');
        }

        // echo '<pre>',print_r($reult),'</pre>';
        // exit;

        $reult = $this->db->get()->result();

        return $reult;
    }

    /**
     * 查指定星期營業店家
     *
     * @param int $week_num
     */
    public function find_store_open_by_week($week_num = 0)
    {

        $this->db->select("
        custom_store_opening_relation.storeSid,
        custom_store.`name` as pharmacyName")
            ->from("custom_store_opening_relation")
            ->join("custom_store_opening_date", "custom_store_opening_date.storeSid = custom_store_opening_relation.storeSid 
        and find_in_set (custom_store_opening_date.sid,custom_store_opening_relation.openingDateSid)", "left")
            ->join("custom_store_opening_time", "custom_store_opening_time.relationSid = custom_store_opening_relation.sid", "left")
            ->join("custom_store", "custom_store.sid =custom_store_opening_relation.storeSid", "inner");

        $this->db->where('custom_store_opening_date.weekNum', $week_num);

        $reult = $this->db->get()->result();

        return $reult;
    }

    /**
     * 店家營業時間清單
     *
     * @param string $store_sid
     * @return array
     */
    public function find_store_week_open_array($store_sid = "")
    {

        $this->db->select("
        group_concat(custom_store_opening_date.weekName order by custom_store_opening_date.weekNum)as weekName,
        group_concat(custom_store_opening_date.weekNum order by custom_store_opening_date.weekNum)as weekNum,
        custom_store_opening_relation.openingDateSid,ifnull(data_count,0)as data_count,
        custom_store_opening_relation.sid,
        custom_store_opening_relation.storeSid ")
            ->from("custom_store_opening_relation")
            ->join("custom_store_opening_date", "custom_store_opening_date.sid is not null and find_in_set(custom_store_opening_date.sid,custom_store_opening_relation.openingDateSid)", "inner")
            ->join("(select relationSid,count(sid) as data_count from custom_store_opening_time
            group by custom_store_opening_time.relationSid)as data_count", "data_count.relationSid = custom_store_opening_relation.sid", "left")
            ->where("custom_store_opening_relation.storeSid", $store_sid)
            ->group_by('custom_store_opening_relation.storeSid,custom_store_opening_relation.sid')
            ->order_by("custom_store_opening_date.weekNum");

        $result_array = $this->db->get()->result();

        return $result_array;
    }

    /**
     * 找店家營業時間清單
     *
     * @param string $store_sid
     * @return array
     */
    public function find_store_open_time_by_store($store_sid = "")
    {

        $this->db->select("custom_store_opening_relation.sid,
        custom_store_opening_relation.storeSid,
        custom_store.`name` as pharmacyName,
        custom_store_opening_date.weekName,
        custom_store_opening_date.weekNum")
            ->from("custom_store_opening_relation")
            ->join("custom_store_opening_date", "custom_store_opening_date.storeSid = custom_store_opening_relation.storeSid 
        and find_in_set (custom_store_opening_date.sid,custom_store_opening_relation.openingDateSid)", "left")
            ->join("custom_store", "custom_store.sid =custom_store_opening_relation.storeSid", "inner");

        $this->db->where("custom_store_opening_relation.storeSid", $store_sid);

        $this->db->order_by("custom_store_opening_relation.storeSid,custom_store_opening_relation.sid,custom_store_opening_date.weekNum");

        $reult = $this->db->get()->result();

        return $reult;
    }

    /**
     * 找店家營業時間清單
     *
     * @param string $sid
     * @return array
     */
    public function find_store_open_time_array_by_sid($sid = "")
    {

        $this->db->select("
        custom_store_opening_date.weekName,
        custom_store_opening_date.weekNum,
        custom_store_opening_relation.sid,
        custom_store_opening_date.sid as openingDateSid,
        custom_store_opening_relation.storeSid ")
            ->from("custom_store_opening_relation")
            ->join("custom_store_opening_date", "custom_store_opening_date.sid is not null and find_in_set(custom_store_opening_date.sid,custom_store_opening_relation.openingDateSid)", "inner")
            ->where("custom_store_opening_relation.sid", $sid)
            ->order_by("custom_store_opening_relation.sid,custom_store_opening_date.weekNum");

        // echo '<pre>', $this->db->get_compiled_select(), '</pre>';
        // exit;

        $result_array = $this->db->get()->result();

        return $result_array;
    }

    /**
     * 刪除營業資料
     *
     * @param string $sid
     * @return boolean
     */
    public function del_opening_data($sid = "")
    {

        $this->db->trans_start();

        $this->db->query("delete custom_store_opening_date from custom_store_opening_date
        left join custom_store_opening_relation on custom_store_opening_date.sid is not null and find_in_set(custom_store_opening_date.sid,custom_store_opening_relation.openingDateSid)
        where custom_store_opening_relation.sid = $sid");

        $this->db->query("delete custom_store_opening_time,custom_store_opening_relation from custom_store_opening_relation
        left join custom_store_opening_time on custom_store_opening_relation.sid = custom_store_opening_time.relationSid
        where custom_store_opening_relation.sid = $sid");

        $this->db->trans_complete();

        return true;
    }
}
