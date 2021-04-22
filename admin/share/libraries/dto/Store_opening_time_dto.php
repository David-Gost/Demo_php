<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Store_opening_time_dto extends Base_dto
{

    public $relationSid;    //custom_store_opening_relation關聯sid
    public $startTime = NULL;    //開始時間
    public $endTime = NULL;    //結束時間

    function getRelationSid()
    {
        return $this->relationSid;
    }

    function getStartTime()
    {
        return $this->startTime;
    }

    function getEndTime()
    {
        return $this->endTime;
    }

    function setRelationSid($relationSid)
    {
        $this->relationSid = $relationSid;
    }

    function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }
}
