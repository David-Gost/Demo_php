<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Product_dto extends Base_dto
{

    public $storeSid = NULL;    //關聯店家sid
    public $name = NULL;    //商品名稱

    function getStoreSid()
    {
        return $this->storeSid;
    }

    function getName()
    {
        return $this->name;
    }

    function setStoreSid($storeSid)
    {
        $this->storeSid = $storeSid;
    }

    function setName($name)
    {
        $this->name = $name;
    }
}
