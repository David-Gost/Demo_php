<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Member_transaction_log_dto extends Base_dto
{

    public $memberSid=NULL;    //關聯會員sid
    public $transactionAmount=NULL;    //金額(可正負數)
    
    function getMemberSid() {
     return $this->memberSid; 
    }
    
    function getTransactionAmount() {
     return $this->transactionAmount; 
    }
    
    function setMemberSid($memberSid) {
     $this->memberSid = $memberSid; 
    }
    
    function setTransactionAmount($transactionAmount) {
     $this->transactionAmount = $transactionAmount; 
    }
}
