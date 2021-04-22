<?php

class LinePay_api {

    private $testMode = Test_model_line;
    private $Channel_ID = Channel_ID;
    private $Channel_Secret_Key = Channel_Secret_Key;

    function __construct($config = array()) {
        $this->initialize($config);
    }

    function get_object_vars_all($obj) {
        $objArr = substr(str_replace(get_class($obj) . "::__set_state(", "", var_export($obj, true)), 0, -1);
        eval("\$values = $objArr;");
        return $values;
    }

    function object_to_array($obj) {
        if (is_object($obj)) {
            $obj = $this->get_object_vars_all($obj);
        }
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                if (strpos($key, '*') > 0) {
                    $new[\str_replace('*', '', $key)] = object_to_array($val);
                } else {
                    $new[$key] = object_to_array($val);
                }
            }
        } else {
            $new = $obj;
        }
        return $new;
    }

    private function initialize($config = array()) {
        foreach ($config as $key => $val) {
            $pass = false;
            if (isset($this->$key)) {
                switch ($key) {
                    case 'Roturl_status':
                        $pass = true;
                        break;
                    default :
                        $pass = false;
                        break;
                }

                if ($pass) {
                    continue;
                }

                $method = 'set' . ucfirst($key);

                if (method_exists($this, $method)) {
                    $this->$method($val);
                } else {
                    $this->$key = $val;
                }
            }
        }

        return $this;
    }

    private function getServiceUrl() {
        if ($this->testMode) {
            return "https://sandbox-api-pay.line.me/v2/payments";
        } else {
            return "https://api-pay.line.me/v2/payments";
        }
    }

    private function getRequestHeader() {

        $header = array(
            'Content-Type: application/json; charset=UTF-8',
            'X-LINE-ChannelId: ' . $this->Channel_ID,
            'X-LINE-ChannelSecret: ' . $this->Channel_Secret_Key,
        );

        return $header;
    }

    private function getCurl($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getRequestHeader());

        return $curl;
    }

    /**
     * 查詢已付款
     * @param String $transactionId
     * 交易編號
     */
    function checkOrder($transactionId) {
        if (is_array($orderId)) {
            $orderId = implode(',', $orderId);
        }

        $curl = $this->getCurl($this->getServiceUrl() . "?orderId=$orderId");

        $result = curl_exec($curl);
        curl_close($curl);

        $obj = json_decode($result);
        if (isset($obj->info)) {
            return $obj->info;
        } else {
            return [];
        }
    }

    /**
     * 保留付款（建立訂單）
     * @param ReserveOrderDto $dto
     * 指定是否請款<p>
     * 
     * <b>TRUE</b>：呼叫付款 confirm API 時，立即進行付款授權與請款 (預設)。<p>
     * <b>FALSE</b>：呼叫付款 confirm API 時，只有經過授權，然後透過呼叫 "請款 API" 分開請款，才能完成付款。
     */
    function reserveOrder(ReserveOrderDto $dto) {
        $param = $this->object_to_array($dto);

        $curl = $this->getCurl($this->getServiceUrl() . '/request');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));

        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result);
    }

    /**
     * 請款
     * @param string $transactionId
     * 交易編號
     * @param number $amount
     * 金額
     * @param string $currency
     * 幣別
     * 
     * <b>TWD</b> 台幣 (預設)<p>
     * <b>USD</b> 美金<p>
     * <b>JPY</b> 日幣<p>
     * <b>THB</b> 泰銖
     */
    function captureOrder($transactionId, $amount, $currency = 'TWD') {
        $param = array(
            'amount' => $amount,
            'currency' => $currency
        );

        $curl = $this->getCurl($this->getServiceUrl() . "/authorizations/$transactionId/capture");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));

        $result = curl_exec($curl);
        curl_close($curl);
        $obj = json_decode($result);
        if (isset($obj->info)) {
            return $obj->info;
        } else {
            return [];
        }
    }

    /**
     * 付款（自動授權並請款）
     * @param string $transactionId
     * 交易編號
     * @param number $amount
     * 金額
     * @param string $currency
     * 幣別
     * 
     * <b>TWD</b> 台幣 (預設)<p>
     * <b>USD</b> 美金<p>
     * <b>JPY</b> 日幣<p>
     * <b>THB</b> 泰銖
     */
    function confirmOrder($transactionId, $amount, $currency = 'TWD') {
        $param = array(
            'amount' => $amount,
            'currency' => $currency
        );

        $curl = $this->getCurl($this->getServiceUrl() . "/$transactionId/confirm");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));

        $result = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($result);
    }

    /**
     * 退款
     * @param string $transactionId
     * 交易編號
     * @param number $amount
     * 金額
     */
    function refundOrder($transactionId, $amount) {
        $param = array(
            'amount' => $amount
        );

        $curl = $this->getCurl($this->getServiceUrl() . "/$transactionId/refund");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));

        $result = curl_exec($curl);
        curl_close($curl);
        $obj = json_decode($result);
        if (isset($obj->info)) {
            return $obj->info;
        } else {
            return [];
        }
    }

    function setTestMode($testMode) {
        $this->testMode = $testMode;
        return $this;
    }

    function setChannel_ID($Channel_ID) {
        $this->Channel_ID = $Channel_ID;
        return $this;
    }

    function setChannel_Secret_Key($Channel_Secret_Key) {
        $this->Channel_Secret_Key = $Channel_Secret_Key;
        return $this;
    }

}

/**
 * ReserveOrderDto
 * @param string $payType <p>
 * 產品名稱 <p><p>
 * NORMAL:單筆付款 (預設) <p>
 * PREAPPROVED:自動付款<p>
 * @param string $langCd
 * 押碼值<p>
 * 
 * @param bool $capture <p>
 * 指定是否請款 <p><p>
 * true:呼叫付款 confirm API 時，立即進行付款授權與請款 (預設)。 <p>
 * false:呼叫付款 confirm API 時，只有經過授權，然後透過呼叫 "請款 API" 分開請款，才能完成付款。
 */
class ReserveOrderDto {

    private $productName;
    private $productImageUrl;
    private $amount;
    private $currency = 'TWD';
    private $mid;
    private $oneTimeKey;
    private $confirmUrl;
    private $confirmUrlType;
    private $checkConfirmUrlBrowser;
    private $cancelUrl;
    private $packageName;
    private $orderId;
    private $deliveryPlacePhone;
    private $payType = "NORMAL";
    private $langCd;
    private $capture;

//    private $extras.addFriends = '';
//    private $extras.branchName = '';

    function setProductName($productName) {
        $this->productName = $productName;
        return $this;
    }

    function setProductImageUrl($productImageUrl) {
        $this->productImageUrl = $productImageUrl;
        return $this;
    }

    function setAmount($amount) {
        $this->amount = $amount;
        return $this;
    }

    function setCurrency($currency) {
        $this->currency = $currency;
        return $this;
    }

    function setMid($mid) {
        $this->mid = $mid;
        return $this;
    }

    function setOneTimeKey($oneTimeKey) {
        $this->oneTimeKey = $oneTimeKey;
        return $this;
    }

    function setConfirmUrl($confirmUrl) {
        $this->confirmUrl = $confirmUrl;
        return $this;
    }

    function setConfirmUrlType($confirmUrlType) {
        $this->confirmUrlType = $confirmUrlType;
        return $this;
    }

    function setCheckConfirmUrlBrowser($checkConfirmUrlBrowser) {
        $this->checkConfirmUrlBrowser = $checkConfirmUrlBrowser;
        return $this;
    }

    function setCancelUrl($cancelUrl) {
        $this->cancelUrl = $cancelUrl;
        return $this;
    }

    function setPackageName($packageName) {
        $this->packageName = $packageName;
        return $this;
    }

    function setOrderId($orderId) {
        $this->orderId = $orderId;
        return $this;
    }

    function setDeliveryPlacePhone($deliveryPlacePhone) {
        $this->deliveryPlacePhone = $deliveryPlacePhone;
        return $this;
    }

    function setPayType($payType) {
        $this->payType = $payType;
        return $this;
    }

    function setLangCd($langCd) {
        $this->langCd = $langCd;
        return $this;
    }

    function setCapture($capture) {
        $this->capture = $capture;
        return $this;
    }

}
