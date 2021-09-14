<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ezpay_invoice
 *
 * @author David
 */
class Ezpay_invoice {

    private $CompanyID;
    private $MerchantID;
    private $HashKey;
    private $HashIV;
    private $test_mode = true;

    function setTest_mode($test_mode = true) {
        $this->test_mode = $test_mode;
        return $this;
    }

    function setHashKey($HashKey) {
        $this->HashKey = $HashKey;
        return $this;
    }

    function setHashIV($HashIV) {
        $this->HashIV = $HashIV;
        return $this;
    }

    function setMerchantID($MerchantID) {
        $this->MerchantID = $MerchantID;
        return $this;
    }

    function setCompanyID($CompanyID) {
        $this->CompanyID = $CompanyID;
        return $this;
    }

    /**
     * <p>開發票</p>
     * @param Open_invoice_dto $open_invoice_dto
     * @return \Invoice_result_dto
     */
    public function open_invoice(Open_invoice_dto $open_invoice_dto) {

        $open_invoice_dto->setTimeStamp(time());
        $open_invoice_dto->setVersion("1.4");

        if ($this->test_mode) {

            $url = "https://cinv.ezpay.com.tw/Api/invoice_issue";
        } else {

            $url = "https://inv.ezpay.com.tw/Api/invoice_issue";
        }

        $total_price = 0;
        $total_tax = 0;
        $total_amt = 0;
        $item_name = "";
        $item_count = "";
        $item_unit = "";
        $item_price = "";
        $item_amt = 0;

        $real_tax = (float) (intval(trim($open_invoice_dto->TaxRate)) * 0.01);

        $item_array = $open_invoice_dto->getInvoice_item_array();
        $item_price_str = "";
        //-----計算商品細向
        foreach ($item_array as $count => $data_array) {

            $item_dto = (object) $data_array;
            $item_price = trim($item_dto->price);
            $cache_item_count = intval(trim($item_dto->count));
            $item_unit_price  = $item_price + ($item_price * $real_tax) ;
            $tax_cache = $cache_item_count * $item_price * $real_tax;


            $price_cache = $cache_item_count * $item_price;

            $amt_cache =  $item_unit_price  * $cache_item_count;

            if ($count == 0) {

                $item_name = $item_dto->cname;
                $item_count = $cache_item_count;
                $item_unit = $item_dto->unit;
                $item_price_str = number_format($item_unit_price, 0, ',', '');
                $item_amt = number_format($amt_cache, 0, ',', '');
            } else {

                $item_name .= '|' . $item_dto->cname;
                $item_count .= '|' . $cache_item_count;
                $item_unit .= '|' . $item_dto->unit;
                $item_price_str .= '|' . number_format($item_unit_price, 0, ',', '');
                $item_amt .= '|' . number_format($amt_cache, 0, ',', '');
            }
           

            $total_tax += $tax_cache;
            $total_price += $price_cache;
            $total_amt += $amt_cache;
        }
        
        //-----產生送出資料

        $send_data_array = object_to_array($open_invoice_dto);
        unset($send_data_array['Invoice_item_array']);


        $send_data_obj = (object) $send_data_array;

        $send_data_obj->Amt = number_format($total_price, 0, ',', '');
        $send_data_obj->TaxAmt = number_format($total_tax, 0, ',', '');
        $send_data_obj->TotalAmt = number_format($total_amt, 0, ',', '');
        $send_data_obj->ItemName = $item_name;
        $send_data_obj->ItemCount = $item_count;
        $send_data_obj->ItemUnit = $item_unit;
        $send_data_obj->ItemPrice = $item_price_str;
        $send_data_obj->ItemAmt = $item_amt;
        
//        echo '<pre>', print_r($send_data_obj), '</pre>';
//        exit();




        $post_data_dto = $this->create_invoice_post_data($send_data_obj);

        $result = json_decode($this->send_from($url, $post_data_dto));

        return $this->analysis_result($result);
    }

    /**
     * <p>發票報廢</p>
     * @param Scrapped_invoice_dto $scrapped_invoice_dto
     * @return \Invoice_result_dto
     */
    public function scrapped_invoice(Scrapped_invoice_dto $scrapped_invoice_dto) {

        if ($this->test_mode) {

            $url = "https://cinv.ezpay.com.tw/Api/invoice_invalid";
        } else {

            $url = "https://inv.ezpay.com.tw/Api/invoice_invalid";
        }

        $scrapped_invoice_dto->setTimeStamp(time());
        $scrapped_invoice_dto->setVersion("1.0");

        $post_data_dto = $this->create_invoice_post_data($scrapped_invoice_dto);
        $result = json_decode($this->send_from($url, $post_data_dto));

        return $this->analysis_result($result);
    }

    /**
     * <p>查詢發票</p>
     * @param Search_invoice_dto $search_invoice_dto
     * @return  \Invoice_result_dto
     */
    public function search_invoice(Search_invoice_dto $search_invoice_dto) {

        if ($this->test_mode) {

            $url = "https://cinv.ezpay.com.tw/Api/invoice_search";
        } else {

            $url = "https://inv.ezpay.com.tw/Api/invoice_search";
        }

        $search_invoice_dto->setTimeStamp(time());
        $search_invoice_dto->setVersion("1.1");

        $post_data_dto = $this->create_invoice_post_data($search_invoice_dto);
        $result = json_decode($this->send_from($url, $post_data_dto));

        return $this->analysis_result($result);
    }

    /**
     * <p>確認發票字軌狀態</p>
     * @param Invoice_word_track_dto $invoice_word_track_dto
     * @return  \Invoice_result_dto
     */
    public function check_invoice_word_track(Invoice_word_track_dto $invoice_word_track_dto) {

        if ($this->test_mode) {

            $url = "https://cinv.ezpay.com.tw/Api_number_management/searchNumber";
        } else {

            $url = "https://inv.ezpay.com.tw/Api_number_management/searchNumber";
        }

        $invoice_word_track_dto->setTimeStamp(time());
        $invoice_word_track_dto->setVersion("1.0");

        $post_data_dto = $this->create_invoice_post_data($invoice_word_track_dto);
        $result = json_decode($this->send_from($url, $post_data_dto));


        return $this->analysis_result($result);
    }

    //----psot資料
    private function send_from($url, $data_dto) {

        $ch = curl_init();

        $data_array = object_to_array($data_dto);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_array));

        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        $reault = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Errno' . curl_error($ch); //捕抓异常
            exit;
        }

        curl_close($ch);

        return $reault;
    }

    /**
     * <p>產生EZ_Pay電子發票送出資料</p>
     * @param type $obj_data
     * @return \stdClass
     */
    private function create_invoice_post_data($obj_data) {


        $http_data = http_build_query(object_to_array($obj_data));

        $post_data_str = trim(bin2hex(openssl_encrypt($this->addpadding($http_data), 'AES-256-CBC', $this->HashKey, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->HashIV)));

        $post_data_dto = new stdClass();
        $post_data_dto->MerchantID_ = $this->MerchantID;
        $post_data_dto->CompanyID_ = $this->CompanyID;
        $post_data_dto->PostData_ = $post_data_str;

        return $post_data_dto;
    }

    /**
     * <p>補充資料長度至32</p>
     */
    private function addpadding($string, $blocksize = 32) {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }

    /**
     * 
     * @param type $json_obj
     * <p>json資料來源</p>
     * @return \Invoice_result_dto
     */
    private function analysis_result($json_obj) {

        $invoice_result_dto = new Invoice_result_dto();

        if ($json_obj->Status == "SUCCESS") {
            $invoice_result_dto->status = true;

            if (is_string($json_obj->Result)) {
                $result_obj = json_decode($json_obj->Result);
            } else {
                $result_obj = $json_obj->Result;
            }
        } else {
            $invoice_result_dto->status = false;
            $result_obj = null;
        }

        $invoice_result_dto->message = $json_obj->Message;
        $invoice_result_dto->result = $result_obj;

        return $invoice_result_dto;
    }

}

/**
 * EZ_Pay 基本欄位
 */
class Base_ez_dto {

    /**
     *
     * @var string 回傳格式
     */
    public $RespondType = "JSON";

    /**
     *
     * @var string 串接程式版本
     */
    public $Version;

    /**
     *
     * @var string 時間戳記 
     */
    public $TimeStamp;

    /**
     * 
     * @param string $RespondType 
     * <p>回傳格式，String or JSON</p>
     */
    function setRespondType($RespondType) {
        $this->RespondType = $RespondType;
    }

    /**
     * 
     * @param string $Version
     * <p>串接api程式版本</p>
     */
    function setVersion($Version) {
        $this->Version = $Version;
    }

    /**
     * 
     * @param string $TimeStamp
     * <p>時間戳記</p>
     */
    function setTimeStamp($TimeStamp) {
        $this->TimeStamp = $TimeStamp;
    }

}

/**
 * 開發票
 */
class Open_invoice_dto extends Base_ez_dto {

    /**
     *
     * @var string MerchantOrderNo 英數字組合的交易編號
     */
    public $MerchantOrderNo;

    /**
     *
     * @var string Status 開立發票方式，1=即時開立發票，3=預約自動開立發票
     */
    public $Status;

    /**
     *
     * @var string CreateStatusTime 當開立發票方式為預約自動開立發票時 (Status=3)，才需要帶此參數。 2.格式為 YYYY-MM-DD。
     */
    public $CreateStatusTime;

    /**
     *
     * @var string Category 發票種類，B2B=買受人為營業人。 B2C=買受人為個人。
     */
    public $Category = 'B2C';

    /**
     *
     *  @var string BuyerName 買受人名稱
     */
    public $BuyerName;

    /**
     *
     * @var string BuyerUBN 買受人統一編號
     */
    public $BuyerUBN;

    /**
     *
     * @var string BuyerEmail 買受人地址
     */
    public $BuyerEmail;

    /**
     *
     * @var string BuyerAddress 買受人地址
     */
    public $BuyerAddress;

    /**
     *
     * @var string CarrierType 載具類別，1.當 Category=B2C 時，才適用此參數。
     *                                  2.若買受人選擇將發票儲存至載具，則填入 載具類別。0=手機條碼載具 1=自然人憑證條碼載具 2=ezPay 電子發票載具
     *                                  3.若買受人無提供載具，則此參數為空值。 
     *                                  4.當此參數有提供數值時，LoveCode 參數 必為空值。
     */
    public $CarrierType;

    /**
     *
     * @var string CarrierNum 載具編號
     */
    public $CarrierNum;

    /**
     *
     * @var string LoveCode 捐贈碼
     */
    public $LoveCode;

    /**
     *
     * @var string PrintFlag 索取紙本發票 Y or N
     */
    public $PrintFlag = "Y";

    /**
     *
     * @var string TaxType 課稅別， 1=應稅
     *                            2=零稅率
     *                            3=免稅 
     */
    public $TaxType;

    /**
     *
     * @var string TaxRate 稅率，1.課稅別為應稅時，一般稅率請帶 5，特種 稅率請帶入規定的課稅稅率(不含%，例稅率 18%則帶入 18) 2.課稅別為零稅率、免稅時，稅率請帶入 0
     */
    public $TaxRate;

    /**
     *
     * @var string CustomsClearance 報關標記，課稅別為零稅率時，才須使用的欄位
     */
    public $CustomsClearance;

    /**
     *
     * @var Invoice_item_dto  Invoice_item_array 產品清單
     */
    public $Invoice_item_array;

    /**
     * 
     * @param type $MerchantOrderNo
     * <p>英數字組合的交易編號</p>
     */
    function setMerchantOrderNo($MerchantOrderNo) {
        $this->MerchantOrderNo = $MerchantOrderNo;
    }

    /**
     * 
     * @param type $Status
     * <p>開立發票方式，1=即時開立發票，3=預約自動開立發票</p>
     */
    function setStatus($Status) {
        $this->Status = $Status;
    }

    /**
     * 
     * @param type $CreateStatusTime
     * <p>當開立發票方式為預約自動開立發票時 (Status=3)，才需要帶此參數。 2.格式為 YYYY-MM-DD</p>
     */
    function setCreateStatusTime($CreateStatusTime) {
        $this->CreateStatusTime = $CreateStatusTime;
    }

    /**
     * 
     * @param type $Category
     * <p>Category 發票種類，B2B=買受人為營業人。 B2C=買受人為個人。</p>
     */
    function setCategory($Category = "B2C") {
        $this->Category = $Category;
    }

    /**
     * 
     * @param type $BuyerName
     * <p>購買人姓名</p>
     */
    function setBuyerName($BuyerName) {
        $this->BuyerName = $BuyerName;
    }

    /**
     * 
     * @param type $BuyerEmail
     * <p>購買人信箱</p>
     */
    function setBuyerEmail($BuyerEmail) {
        $this->BuyerEmail = $BuyerEmail;
    }

    /**
     * 
     * @param type $CarrierType
     * <p>載具類別，
     * 1.當Category=B2C 時，才適用此參數。
     * 2.若買受人選擇將發票儲存至載具，則填入 載具類別。0=手機條碼載具 1=自然人憑證條碼載具 2=ezPay 電子發票載具
     * 3.有輸入捐贈碼時，此欄位為空</p>
     */
    function setCarrierType($CarrierType) {
        $this->CarrierType = $CarrierType;
    }

    /**
     * 
     * @param type $CarrierNum
     * <p>載具編號</p>
     */
    function setCarrierNum($CarrierNum) {
        $this->CarrierNum = $CarrierNum;
    }

    /**
     * 
     * @param type $LoveCode
     * <p>捐贈碼</p>
     */
    function setLoveCode($LoveCode) {
        $this->LoveCode = $LoveCode;
    }

    /**
     * 
     * @param type $PrintFlag
     * <p>索取紙本發票，除有輸入捐贈碼，其餘情況必填</p>
     */
    function setPrintFlag($PrintFlag = "Y") {
        $this->PrintFlag = $PrintFlag;
    }

    /**
     * 
     * @param type $TaxType
     * <p>課稅別， 1=應稅，2=零稅率，3=免稅 </p>
     */
    function setTaxType($TaxType) {
        $this->TaxType = $TaxType;
    }

    /**
     * 
     * @param type $TaxRate
     * <p>稅率，1.課稅別為應稅時，一般稅率請帶 5，特種 稅率請帶入規定的課稅稅率(不含%，例稅率 18%則帶入 18) 2.課稅別為零稅率、免稅時，稅率請帶入 0</p>
     */
    function setTaxRate($TaxRate) {
        $this->TaxRate = $TaxRate;
    }

    /**
     * 
     * @param type $CustomsClearance
     * <p>報關標記，課稅別為零稅率時，才須使用的欄位</p>
     */
    function setCustomsClearance($CustomsClearance) {
        $this->CustomsClearance = $CustomsClearance;
    }

    /**
     * 
     * @param Invoice_item_dto[] $Invoice_item_array
     * <p>商品清單</p>
     */
    function setInvoice_item_array($Invoice_item_array) {
        $this->Invoice_item_array = $Invoice_item_array;
    }

    /**
     * 
     * @return Invoice_item_dto
     */
    function getInvoice_item_array() {
        return $this->Invoice_item_array;
    }

}

/**
 * 
 * 作廢發票
 * 
 */
class Scrapped_invoice_dto extends Base_ez_dto {

    /**
     *
     * @var string 欲作廢發票號碼
     */
    public $InvoiceNumber;

    /**
     *
     * @var string  作廢原因，字數限中文 6 字或英文 20 字。
     */
    public $InvalidReason;

    /**
     * 
     * @param type $InvoiceNumber
     * <p>欲作廢發票號碼</p>
     */
    function setInvoiceNumber($InvoiceNumber) {
        $this->InvoiceNumber = $InvoiceNumber;
    }

    /**
     * 
     * @param type $InvalidReason
     * <p>作廢原因，字數限中文 6 字或英文 20 字。</p>
     */
    function setInvalidReason($InvalidReason) {
        $this->InvalidReason = $InvalidReason;
    }

}

/**
 * 搜尋發票
 */
class Search_invoice_dto extends Base_ez_dto {

    /**
     *
     * @var string 訂單編號
     */
    public $MerchantOrderNo;

    /**
     *
     * @var string 發票金額，開立發票的總金額。
     */
    public $TotalAmt;

    /**
     *
     * @var string 發票號碼
     */
    public $InvoiceNumber;

    /**
     *
     * @var string 發票防偽隨機碼，開立發票時，回傳的 4 碼發票防偽隨機碼。
     */
    public $RandomNum;

    /**
     * 
     * @param type $MerchantOrderNo
     * <p>訂單編號</p>
     */
    function setMerchantOrderNo($MerchantOrderNo) {
        $this->MerchantOrderNo = $MerchantOrderNo;
    }

    /**
     * 
     * @param type $TotalAmt
     * <p>發票金額</p>
     */
    function setTotalAmt($TotalAmt) {
        $this->TotalAmt = $TotalAmt;
    }

    /**
     * 
     * @param type $InvoiceNumber
     * <p>發票號碼</p>
     */
    function setInvoiceNumber($InvoiceNumber) {
        $this->InvoiceNumber = $InvoiceNumber;
    }

    /**
     * 
     * @param type $RandomNum
     * <p>發票防偽隨機碼</p>
     */
    function setRandomNum($RandomNum) {
        $this->RandomNum = $RandomNum;
    }

}

/**
 * 
 * 單項商品
 * 
 */
class Invoice_item_dto {

    /**
     *
     * @var string cname 商品名稱
     * 
     */
    public $cname;

    /**
     *
     * @var string count 商品數量
     * 
     */
    public $count;

    /**
     *
     * @var string unit 商品單位
     * 
     */
    public $unit;

    /**
     *
     * @var int price 商品單價
     * 
     */
    public $price;

    function setCname($cname) {
        $this->cname = $cname;
    }

    function setCount($count) {
        $this->count = $count;
    }

    function setUnit($unit) {
        $this->unit = $unit;
    }

    function setPrice($price) {
        $this->price = $price;
    }

}

/**
 * 發票回傳
 */
class Invoice_result_dto {

    /**
     *
     * @var boolean 是否成功 
     */
    public $status;

    /**
     *
     * @var string 回傳訊息 
     */
    public $message;

    /**
     *
     * @var object api回傳詳細資訊 
     */
    public $result;

}

/**
 * 查詢發票字軌資料
 */
class Invoice_word_track_dto extends Base_ez_dto {

    /**
     *
     * @var Varchar 發票年度，民國年。如 106。只可輸入前兩年與明年
     */
    public $Year;

    /**
     *
     * @var Varchar 發票期別，1=一,二月
      2=三,四月
      3=五,六月
      4=七,八月
      5=九,十月
      6=十一,十二月
     */
    public $Term;

    /**
     *
     * @var Varchar 字軌狀態,0:暫停，1:啟用
     */
    public $Flag = 1;

    function setYear($Year) {
        $this->Year = $Year;
    }

    function setTerm($Term) {
        $this->Term = $Term;
    }

    function setFlag($Flag) {
        $this->Flag = $Flag;
    }

}
