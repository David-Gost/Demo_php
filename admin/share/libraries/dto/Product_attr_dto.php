<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Product_attr_dto extends Base_dto
{

    public $productSid = NULL;    //關聯商品sid
    public $colorTag = NULL;    //商品顏色(關聯custom_attributes)
    public $typeTag = NULL;    //商品型號(關聯custom_attributes)
    public $price = NULL;    //商品價錢

    function getProductSid()
    {
        return $this->productSid;
    }

    function getColorTag()
    {
        return $this->colorTag;
    }

    function getTypeTag()
    {
        return $this->typeTag;
    }

    function getPrice()
    {
        return $this->price;
    }

    function setProductSid($productSid)
    {
        $this->productSid = $productSid;
    }

    function setColorTag($colorTag)
    {
        $this->colorTag = $colorTag;
    }

    function setTypeTag($typeTag)
    {
        $this->typeTag = $typeTag;
    }

    function setPrice($price)
    {
        $this->price = $price;
    }
}
