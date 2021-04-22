<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Store_opening_date_dto extends Base_dto
{

    public $weekName = NULL;    //星期名稱
    public $storeSid = NULL;    //店家關聯sid
    public $weekNum = NULL;    //星期代表數字

    function getWeekName()
    {
        return $this->weekName;
    }

    function getStoreSid()
    {
        return $this->storeSid;
    }

    function getWeekNum()
    {
        return $this->weekNum;
    }

    function setWeekName($weekName)
    {
        $this->weekName = $weekName;
    }

    function setStoreSid($storeSid)
    {
        $this->storeSid = $storeSid;
    }

    function setWeekNum($weekNum)
    {
        $this->weekNum = $weekNum;
    }
}
