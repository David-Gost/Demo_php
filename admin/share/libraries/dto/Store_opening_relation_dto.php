<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Store_opening_relation_dto extends Base_dto
{

    public $storeSid = NULL;    //關聯店家sid
    public $openingDateSid = NULL;    //關聯custom_store_opening_date sid

    function getStoreSid()
    {
        return $this->storeSid;
    }

    function getOpeningDateSid()
    {
        return $this->openingDateSid;
    }

    function setStoreSid($storeSid)
    {
        $this->storeSid = $storeSid;
    }

    function setOpeningDateSid($openingDateSid)
    {
        $this->openingDateSid = $openingDateSid;
    }
}
