<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Store_transaction_log_dto extends Base_dto
{

    public $storeSid = NULL;    //關聯店家sid
    public $transactionAmount = NULL;    //金額(可正負數)

    function getStoreSid()
    {
        return $this->storeSid;
    }

    function getTransactionAmount()
    {
        return $this->transactionAmount;
    }

    function setStoreSid($storeSid)
    {
        $this->storeSid = $storeSid;
    }

    function setTransactionAmount($transactionAmount)
    {
        $this->transactionAmount = $transactionAmount;
    }
}
