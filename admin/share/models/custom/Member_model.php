<?php

/**
 * @property Member_dto $member_dto
 */
class Member_model extends AC_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("dto/Member_dto");

        $this->_table = 'custom_member';
        $this->_dto = 'Member_dto';
    }

    /**
     * 找會員清單
     *
     * @param string $search_key
     * @return stdClass
     */
    public function find_member_list($search_key=""){

        $cash_sql=$this->find_memeber_now_cash_sql();

        $this->db->select("custom_member.sid as memberSid,
        custom_member.name,
        ifnull(member_cash_data.cashBalance,0.0)as cashBalance,
        ifnull(lastTransactionDate,'')as lastTransactionDate,
        ifnull(lastPharmacyName,'')as lastPharmacyName")
        ->from('custom_member')
        ->join("($cash_sql)as member_cash_data","member_cash_data.memberSid = custom_member.sid","left")
        ->join("(select storeSid,
        pharmacyName as lastPharmacyName,
        memberSid,
        from_unixtime(max(transactionDate),'%Y-%m-%d %H:%i')as lastTransactionDate
        from custom_transaction_log
        where memberSid is not null
        group by memberSid
        )as member_last_transaction","member_last_transaction.memberSid = custom_member.sid","left")
        ->like("custom_member.`name`",$search_key);

        $result_array = $this->db->get()->result();

        return $result_array;

    }
    
    /**
     * 取得餘額
     *
     * @return string
     */
    private function find_memeber_now_cash_sql(){

        $this->db->select("memberSid,sum(transactionAmount)as cashBalance")
        ->from("custom_member_transaction_log")
        ->group_by("memberSid");

        return $this->db->get_compiled_select();
    }
}
