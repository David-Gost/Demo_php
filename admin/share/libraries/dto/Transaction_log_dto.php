<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Transaction_log_dto extends Base_dto
{
    public $memberSid = NULL;    //會員關聯sid
    public $storeSid = NULL;    //店家關聯sid
    public $productSid = NULL;    //商品關聯sid
    public $productAttrSid = NULL;    //商品型號(custom_product_attr)關聯sid
    public $colorTag = NULL;    //商品顏色(關聯custom_attributes)
    public $typeTag = NULL;    //商品型號(關聯custom_attributes)
    public $memberName = NULL;    //會員名稱
    public $pharmacyName = NULL;    //商家名稱
    public $maskName = NULL;    //售出商品顯示名稱
    public $transactionAmount = NULL;    //售價
    public $transactionDate = NULL;    //販售日期
    public $remark = NULL;

    function getMemberSid()
    {
        return $this->memberSid;
    }

    function getStoreSid()
    {
        return $this->storeSid;
    }

    function getProductSid()
    {
        return $this->productSid;
    }

    function getProductAttrSid()
    {
        return $this->productAttrSid;
    }

    function getColorTag()
    {
        return $this->colorTag;
    }

    function getTypeTag()
    {
        return $this->typeTag;
    }

    function getMemberName()
    {
        return $this->memberName;
    }

    function getPharmacyName()
    {
        return $this->pharmacyName;
    }

    function getMaskName()
    {
        return $this->maskName;
    }

    function getTransactionAmount()
    {
        return $this->transactionAmount;
    }

    function getTransactionDate()
    {
        return $this->transactionDate;
    }

    function getRemark()
    {
        return $this->remark;
    }

    function setMemberSid($memberSid)
    {
        $this->memberSid = $memberSid;
    }

    function setStoreSid($storeSid)
    {
        $this->storeSid = $storeSid;
    }

    function setProductSid($productSid)
    {
        $this->productSid = $productSid;
    }

    function setProductAttrSid($productAttrSid)
    {
        $this->productAttrSid = $productAttrSid;
    }

    function setColorTag($colorTag)
    {
        $this->colorTag = $colorTag;
    }

    function setTypeTag($typeTag)
    {
        $this->typeTag = $typeTag;
    }

    function setMemberName($memberName)
    {
        $this->memberName = $memberName;
    }

    function setPharmacyName($pharmacyName)
    {
        $this->pharmacyName = $pharmacyName;
    }

    function setMaskName($maskName)
    {
        $this->maskName = $maskName;
    }

    function setTransactionAmount($transactionAmount)
    {
        $this->transactionAmount = $transactionAmount;
    }

    function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
    }

    function setRemark($remark)
    {
        $this->remark = $remark;
    }
}
