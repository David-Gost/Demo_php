<?php

/**
 * @property Member_transaction_log_dto $member_transaction_log_dto
 */
class Member_transaction_log_model extends AC_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("dto/Member_transaction_log_dto");

        $this->_table = 'custom_member_transaction_log';
        $this->_dto = 'Member_transaction_log_dto';
    }

    /**
     * 取得餘額
     *
     * @return float
     */
    public function find_memeber_now_cash($member_sid="")
    {

        $this->db->select("memberSid,ifnull(sum(transactionAmount),'0.0')as cashBalance")
            ->from("custom_member_transaction_log")
            ->where("memberSid",$member_sid)
            ->group_by("memberSid");

        $result_data = $this->db->get()->row(0);

        if(!$result_data){
            return 0.0;
        }else{
            return $result_data->cashBalance;
        }

    }
}
