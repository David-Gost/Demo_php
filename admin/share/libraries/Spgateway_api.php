<?php

/**
 * Class Spgateway_api
 * 
 * @param string $MerchantID <p>
 * 商店代號 *
 * </p>
 * @param string $HashKey <p>
 * Hash Key *
 * </p>
 * @param string $HashIV <p>
 * Hash IV *
 * </p>
 * @param string $MerchantOrderNo <p>
 * 商店訂單編號 *
 * </p>
 * @param Int $Amt <p>
 * 訂單金額 *
 * </p>
 * @param string $ItemDesc <p>
 * 商品名稱 *
 * </p>
 * @param string $OrderComment <p>
 * 商店備註
 * </p>
 * @param string $return_url <p>
 * 返回網址
 * 不能使用 Localhost
 * </p>
 */
class Spgateway_api {

    private $test_mode;
    private $serial_url;
    //
    private $MerchantID = 'MS320675882';
    private $HashKey = 'OES51UBSyCKU25yXEwWi9cV5dCkVMWuA';
    private $HashIV = 'AmGBhitPUdJXjiUY';
    private $RespondType = 'JSON';
    private $TimeStamp;
    public $MerchantOrderNo;
    public $Amt;
    public $ItemDesc;
    public $Email;
    public $OrderComment;
    public $return_url = 'http://demo.acubedt.com/rwd_astera/account/login';
    public $NotifyURL = 'http://demo.acubedt.com/rwd_astera/account/login';

    /**
     * @param bool $test_mode <p>
     * 測試模式
     * </p>
     */
    function __construct($test_mode = TRUE) {
        $this->setTest_mode($test_mode);
    }

    private function aes_encrypt($parameter = "") {
        $key = $this->HashKey;
        $iv = $this->HashIV;

        $return_str = '';
        if (!empty($parameter)) {
            $return_str = http_build_query($parameter);
        }

        return trim(bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $this->addpadding($return_str), MCRYPT_MODE_CBC, $iv)));
    }

    private function addpadding($string, $blocksize = 32) {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }

    private function strippadding($string) {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }

    /**
     * @param string $parameter <p>
     * 解析TradeInfo
     * </p>
     * @return type Object
     */
    function aes_decrypt($parameter = "") {
//        $parameter = "3dbb405ea03a505226dd2958b9ba5ee0984027ab431c4ed7a937b0d0fdea2e5c7494b97a2ee8daa0f5ab785a79e48b05780ca93d64f2b418649b465f1a79ef14a2f9bfe4f1ab9dc0f64d9e443dcc743ae9eb263d6232fc988514cea7c3aa3ce6906c73e4b6c0e6d2e9426e27a5305264aebb8c85b8b2fd17b71f7493b54083e85e0c16ed7f86ee5718be90a9b38d396b1d9ff07bcfce406cb659ebe0f47be4f0aba1bc1be1ba07bfe807e4071b826cbabc613083e77f06294a07c8a965f57baee97dfdf97332fd53b56bbb2f34227f8cfeb6ac66e3abc093427241bd20df820c014600e455b26d8f27212960a855ad327d3a594080e8c2e68281d59eedbccc17935473e9138823345a916814a11830d9ec7e029f84109b23cc1635d7e39d07d968db3bc853324bdae78704b71b53eea66f4f57a25d7c121e8a34ff3401c7160124508971f193da2d2547eae223fc4dcc38aa2bcf4c664ed4fc53196f63b5a3c703fb2a6b060b5064f0402c8609df987abe291a1ef33712f3c63881821c989b112ee5aaa38a3490753745431a246cb740a32d7e49cbd9fb423e9befcb3a9d7bf8ef13cfa48413db765a5e6cdc73af14d6fd0911ce1f45c5e17b113bdd24ac4f3b";

        $key = $this->HashKey;
        $iv = $this->HashIV;

        $bin = hex2bin($parameter);
        $decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $bin, MCRYPT_MODE_CBC, $iv);
        $strippadding = $this->strippadding($decrypt);

        $obj = json_decode($strippadding);
        return $obj;
    }

    private function sha256_encrypt($parameter = "") {
        return strtoupper(hash("sha256", $parameter));
    }

    /**
     * 建立訂單
     * @param string $order_number <p>
     * 訂單編號 
     * </p>
     * @param int $amt <p>
     * 訂單金額
     * </p>
     * @param bool $auto_post <p>
     * 是否自動送出訂單 <p>
     * 預設: false
     * </p>
     * @param string $target <p>
     * 新視窗開啟方式 <p>
     * 預設: _self
     * </p>
     */
    function create_order($ordernumber, $amt, $auto_post = false, $target = '_self') {
        $this->MerchantOrderNo = $ordernumber;
        $this->Amt = $amt;
        $this->create_order_checkout($auto_post, $target);
    }

    private function create_order_checkout($auto_post = false, $target = '_blank') {
        $this->TimeStamp = time();
        $ase = $this->aes_encrypt($this->get_create_order_parameter());
        $sha256_str = "HashKey=$this->HashKey&$ase&HashIV=$this->HashIV";
        $sha256 = $this->sha256_encrypt($sha256_str);

        echo "<form id='spgateway_form' method='post' target='$target' action='$this->serial_url'>
                <input type='hidden' name='MerchantID' value='$this->MerchantID'><!-- 商店代號＊ -->
                <input type='hidden' name='TradeInfo' value='$ase'><!-- 交易資料 AES 加密＊ -->
                <input type='hidden' name='TradeSha' value='$sha256'><!-- 交易資料 SHA256 加密＊ -->
                <input type='hidden' name='Version' value='1.4'><!-- 串接程式版本＊ -->
                ";

        if ($auto_post) {
            echo '<script>
                document.getElementById("spgateway_form").submit();
            </script>';
        } else {
            echo "<input type='submit' value='Submit'>";
        }

        echo "</form>";
    }

    private function get_create_order_parameter() {
        $parameter = array(
            'MerchantID' => $this->MerchantID, //商店代號 *
            'RespondType' => $this->RespondType, //回傳格式＊
            'TimeStamp' => $this->TimeStamp, //時間戳記＊
            'Version' => '1.4', //串接程式版本＊
            'LangType' => 'zh-tw', //語系
            'MerchantOrderNo' => $this->MerchantOrderNo, //商店訂單編號＊
            'Amt' => $this->Amt, //訂單金額＊
            'ItemDesc' => $this->ItemDesc, //商品名稱＊
            'TradeLimit' => NULL, //交易限制秒數
            'ExpireDate' => NULL, //繳費有效期限
            'ReturnURL' => $this->return_url, //支付完成返回商店網址
            'NotifyURL' => $this->NotifyURL, //支付通知網址
            'CustomerURL' => $this->return_url, //商店取號網址
            'ClientBackURL' => $this->return_url, //支付取消返回商店網址
            'Email' => $this->Email, //付款人電子信箱
            'EmailModify' => 1, //付款人電子信箱是否開放修改
            'LoginType' => 0, //需不需要登入智付通會員 0:不需要
            'OrderComment' => $this->OrderComment, //商店備註
//          <!-- 以下是否啟用 -->
            'CREDIT' => 1, //信用卡 一次付清
            'InstFlag' => 0, //信用卡 分期付款
            'CreditRed' => 0, //信用卡 紅利
            'UNIONPAY' => 0, //信用卡 銀聯卡
            'WEBATM' => 0, //WEBATM啟用
            'VACC' => 1, //ATM 轉帳
            'CVS' => 0, //超商代碼繳費
            'BARCODE' => 0, //超商條碼繳費啟
            'P2G' => 0, //Pay2go 電子錢包
//          <!-- 以上是否啟用 -->
        );

        return $parameter;
    }

    /**
     * 取消授權訂單
     * @param string $order_number <p>
     * 訂單編號 
     * </p>
     * @param int $amt <p>
     * 訂單金額
     * </p>
     * @param bool $auto_post <p>
     * 是否自動送出訂單 <p>
     * 預設: TRUE
     * </p>
     * @param string $target <p>
     * 新視窗開啟方式 <p>
     * 預設: _self
     * </p>
     * @return object
     */
    function cancel_order($ordernumber, $amt) {
        $this->MerchantOrderNo = $ordernumber;
        $this->Amt = $amt;
        $this->TimeStamp = time();
        $ase = $this->aes_encrypt($this->get_cancel_order_parameter());

        if ($this->test_mode) {
            $url = 'https://ccore.spgateway.com/API/CreditCard/Cancel';
        } else {
            $url = 'https://core.spgateway.com/API/CreditCard/Cancel';
        }

        $params = array(
            "MerchantID_" => $this->MerchantID,
            "PostData_" => $ase,
        );

        return $this->server_post($url, $params);
    }

    private function get_cancel_order_parameter() {
        $parameter = array(
            'RespondType' => $this->RespondType, //回傳格式＊
            'Version' => '1.0', //串接程式版本＊
            'Amt' => $this->Amt, //訂單金額＊
            'MerchantOrderNo' => $this->MerchantOrderNo, //商店訂單編號＊
            'IndexType' => 1, //只限定填數字 1 或 2，1 表示使用商店訂單編號，2 表示使用智付通交易單號＊
            'TimeStamp' => $this->TimeStamp, //時間戳記＊
            'NotifyURL' => $this->NotifyURL, //支付通知網址
        );

        return $parameter;
    }

    /**
     * 訂單退款
     * @param string $order_number <p>
     * 訂單編號 
     * </p>
     * @param int $amt <p>
     * 訂單金額
     * </p>
     * @param bool $cancel_refund <p>
     * 是否取消退款的申請 <p>
     * 退款有效期限為請款日起算 90 個日曆日晚上九點前 <p>
     * 取消退款需為發動退款當日的晚上九點前，逾時則無法取消 <p>
     * 預設: false
     * </p>
     * @return object
     */
    function refund_order($ordernumber, $amt, $cancel_refund = false) {
        $this->MerchantOrderNo = $ordernumber;
        $this->Amt = $amt;
        $this->TimeStamp = time();
        $ase = $this->aes_encrypt($this->get_refund_order_parameter($cancel_refund));

        if ($this->test_mode) {
            $url = 'https://ccore.spgateway.com/API/CreditCard/Close';
        } else {
            $url = 'https://core.spgateway.com/API/CreditCard/Close';
        }

        $params = array(
            "MerchantID_" => $this->MerchantID,
            "PostData_" => $ase,
        );

        return $this->server_post($url, $params);
    }

    private function get_refund_order_parameter($cancel_refund) {
        $Cancel = $cancel_refund ? 1 : 0;
        $parameter = array(
            'RespondType' => $this->RespondType, //回傳格式＊
            'Version' => '1.0', //串接程式版本＊
            'Amt' => $this->Amt, //訂單金額＊
            'MerchantOrderNo' => $this->MerchantOrderNo, //商店訂單編號＊
            'IndexType' => 1, //只限定填數字 1 或 2，1 表示使用商店訂單編號，2 表示使用智付通交易單號＊
            'TimeStamp' => $this->TimeStamp, //時間戳記＊
            'CloseType' => 2, //時間戳記＊
            'Cancel' => $Cancel, //時間戳記＊
        );

        return $parameter;
    }

    /**
     * 訂單狀態查詢
     * @param string $order_number <p>
     * 訂單編號
     * </p>
     * @param string $amt <p>
     * 訂單金額
     * </p>
     * @return object
     */
    function transaction_query($ordernumber, $amt) {
        if ($this->test_mode) {
            $url = 'https://ccore.spgateway.com/API/QueryTradeInfo';
        } else {
            $url = 'https://core.spgateway.com/API/QueryTradeInfo';
        }

        $time = time();
        $check_code_str = "IV=$this->HashIV&Amt=$amt&MerchantID=$this->MerchantID&MerchantOrderNo=$ordernumber&Key=$this->HashKey";
        $checkValue = $this->sha256_encrypt($check_code_str);
        $params = array(
            "MerchantID" => $this->MerchantID,
            "Version" => "1.1",
            "RespondType" => "JSON",
            "CheckValue" => $checkValue,
            "TimeStamp" => "$time",
            "MerchantOrderNo" => $ordernumber,
            "Amt" => $amt
        );

        return $this->server_post($url, $params);
    }
    
    private function server_post($url, $params) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);

        return $result;
    }
    
//    function transaction_query($ordernumber, $amt) {
//        if ($this->test_mode) {
//            $url = 'https://ccore.spgateway.com/API/QueryTradeInfo';
//        } else {
//            $url = 'https://core.spgateway.com/API/QueryTradeInfo';
//        }
//
//        $time = time();
//        $check_code_str = "IV=$this->HashIV&Amt=$amt&MerchantID=$this->MerchantID&MerchantOrderNo=$ordernumber&Key=$this->HashKey";
//        $checkValue = $this->sha256_encrypt($check_code_str);
//        $params = array(
//            "MerchantID" => $this->MerchantID,
//            "Version" => "1.1",
//            "RespondType" => "JSON",
//            "CheckValue" => $checkValue,
//            "TimeStamp" => "$time",
//            "MerchantOrderNo" => $ordernumber,
//            "Amt" => $amt
//        );
//        $params = http_build_query($params);
//
////        echo '<pre>', print_r($params), '</pre>';
////        echo '<pre>', print_r(json_encode($params)), '</pre>';
//
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_POST, true);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        $result = curl_exec($curl);
//        $result = json_decode($result);
////        echo '<pre>', print_r($result), '</pre>';
//
//        curl_close($curl);
//
//        if (isset($result->Result->TradeNo)) {
//            $TradeNo = $result->Result->TradeNo;
//            $CheckCode = $result->Result->CheckCode;
//            $check_checkcode_str = "HashIV=$this->HashIV&Amt=$amt&MerchantID=$this->MerchantID&MerchantOrderNo=$ordernumber&TradeNo=$TradeNo&HashKey=$this->HashKey";
//            $check_checkValue = $this->sha256_encrypt($check_checkcode_str);
//
//            if ($CheckCode != $check_checkValue) {
//                return;
//            }
//        }
//
//        return $result;
//    }

    function setTest_mode($test_mode) {
        $this->test_mode = $test_mode;

        if ($test_mode) {
            $this->serial_url = "https://ccore.spgateway.com/MPG/mpg_gateway";
        } else {
            $this->serial_url = "https://core.spgateway.com/MPG/mpg_gateway";
        }
    }

    function setMerchantID($MerchantID) {
        $this->MerchantID = $MerchantID;
    }

    function setHashKey($HashKey) {
        $this->HashKey = $HashKey;
    }

    function setHashIV($HashIV) {
        $this->HashIV = $HashIV;
    }

    function setItemDesc($ItemDesc) {
        $this->ItemDesc = $ItemDesc;
    }

    function setEmail($Email) {
        $this->Email = $Email;
    }

    function setReturn_url($return_url) {
        $this->return_url = $return_url;
    }

    function setNotifyURL($NotifyURL) {
        $this->NotifyURL = $NotifyURL;
    }

}

?>