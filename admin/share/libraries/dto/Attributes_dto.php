<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Attributes_dto extends Base_dto
{

    public $cname = NULL;
    public $parent_sid = 0;
    public $tag = NULL;
    public $remark = NULL;    //備註

    function getCname()
    {
        return $this->cname;
    }

    function getParent_sid()
    {
        return $this->parent_sid;
    }

    function getTag()
    {
        return $this->tag;
    }

    function getRemark()
    {
        return $this->remark;
    }

    function setCname($cname)
    {
        $this->cname = $cname;
    }

    function setParent_sid($parent_sid)
    {
        $this->parent_sid = $parent_sid;
    }

    function setTag($tag)
    {
        $this->tag = $tag;
    }

    function setRemark($remark)
    {
        $this->remark = $remark;
    }
}
