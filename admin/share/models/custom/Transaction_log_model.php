<?php

/**
 * @property Transaction_log_dto $transaction_log_dto
 */
class Transaction_log_model extends AC_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("dto/Transaction_log_dto");

        $this->_table = 'custom_transaction_log';
        $this->_dto = 'Transaction_log_dto';
    }

    /**
     * 取得會員交易排名
     *
     * @param integer $start_time
     * @param integer $end_time
     * @param integer $limit
     * @return stdclass
     */
    public function find_member_transaction_top($start_time="",$end_time="",$limit=10){

        $this->db->select("memberSid,memberName,ifnull(sum(transactionAmount),0)as transactionAmount")
        ->from("custom_transaction_log")
        ->where("memberSid is not null and transactionDate between $start_time and $end_time")
        ->group_by("memberSid");

        $cache_sql=$this->db->get_compiled_select();

        $this->db->select('*')
        ->from("($cache_sql)as transaction_data")
        ->order_by('transactionAmount desc')
        ->limit($limit);

        $reult=$this->db->get()->result();
        
        return $reult;

    }

    /**
     * 找交易總額
     *
     * @param integer $start_time
     * @param integer $end_time
     * @param integer $limit
     * @return stdClass
     */
    public function find_transaction_total($start_time="",$end_time=""){

        $this->db->select("ifnull(sum(transactionAmount),0)as transactionAmount")
        ->from("custom_transaction_log")
        ->where("memberSid is not null and transactionDate between $start_time and $end_time");

        $result_data=$this->db->get()->row(0);

        if(!$result_data){
            $result_data=new stdClass();
            $result_data->transactionAmount=0;
        }

        return $result_data;

    }
    
}
