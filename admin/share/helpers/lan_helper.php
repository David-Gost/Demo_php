<?php
/**

 * 多語系helper
 * 資料表：sys_language_data 
 * 用法：不需事先準備翻譯，只要於函式中輸入key_word會對應有於資料庫的翻譯欄位，若不存在於資料庫，即會自動新增待翻譯欄位，再將自行將翻譯後的文字寫入相對應的語系欄位即可
 * 
 * 使用方式：
 * example_1:(一般使用情境)
 *      程式碼：
 *          <div>
 *              <?=_lan("關於我們");?>
 *          </div>
 *      輸出：<div>about us</div>
 * 
 * example_2: 特殊情境
 *      說明：針對特殊使用情境，且DB中已設定，若有相同keyword，但有不同註解，函式會辨別不同的註解呈現不同的翻譯文字，也可針對變數翻譯(同樣的變數，不同的註解，會有不同結果)，其中欲翻譯文字和註解可用變數呈現。
 *
 *      資料庫語法(需先於資料庫中加入)：
 * 			INSERT INTO sys_language_data key_word,point_path,zh_tw VALUE ('20190412新聞標題','最新消息專用','程式碼出去，錢進來，大家發大財','The code goes out, the money comes in, everyone makes a fortune')
 * 
 * 			INSERT INTO sys_language_data key_word,point_path,zh_tw VALUE ('20190412新聞內容','最新消息專用','本公司賺大錢啦～將案子給本公司做<br/>品質好，速度快唷～','The company makes a lot of money~ Give the case to the company.<br/> The quality is good, the speed is fast~')
 * 
 *      程式碼：
 *          <?=_lan(欲翻譯文字,註解);?> <?=_lan($text,$comment);?>
 *          1. <div> <?=_lan("20190412新聞標題","最新消息專用");?> </div>
 *          2. $content = "20190412新聞內容";
 *          <div> <?=_lan($content,,"最新消息專用");?> </div>
 *      中文語系輸出：
 *          <div> 程式碼出去，錢進來，大家發大財 </div>
 *          <div> 本公司賺大錢啦～將案子給本公司做<br/>品質好，速度快唷～ </div>
 *      英文語系輸出：
 *          <div> The code goes out, the money comes in, everyone makes a fortune </div>
 *          <div> The company makes a lot of money~ Give the case to the company.<br/> The quality is good, the speed is fast~ </div>
 * 
 * example_3: 同時呼叫多個語系的翻譯
 *      程式碼：
 *          sendmail($sender,$receiver,_lan("報名成功",'','zh_tw'));
 *          sendmail($sender,$receiver,_lan("報名成功",'','cn'));
 *          sendmail($sender,$receiver,_lan("報名成功",'','en'));
 *      輸出：
 *          繁中語系收件者，收到信件標題： 報名成功
 *          簡中語系收件者，收到信件標題： 报名成功
 *          英文語系收件者，收到信件標題： Successful registration 
 */
defined('BASEPATH') or exit('No direct script access allowed');

class lan_helper{
    //AC_TODO：這些看可不可以常駐記憶體
    
    /**
    * lan_helper的公用參數，表示目前所擁有的語系，請透過get_lan_ary()取得
    * 語系相對應的簡寫請盡量參考i18n的列表，減少瀏覽器語系碼轉為私用語系碼的麻煩
    * 資料格式 ['語系碼'=>'翻譯']
    */
    public static $_lan_ary = array(
        // 'zh_tw' => '繁體中文',   //   設為空陣列，會讓get_lan_ary()從資料庫更新
        // 'cn' => '簡體中文',
        // 'en' => '英文'
    );

    /**
     * 暫存某語系，只在這次request有效
     */
    public static $_lan_temp_lan = '';

    /**
     * 翻譯來源
     */
    public static $_lan_source = 'db';

    /**
     * 實際存放翻譯的變數，資料格式請參考如下
     */
    public static $_lan_data = [''=>["關鍵字" => [
        "zh_tw" => "關鍵字",
        "cn" => "关键词",
        "en" => "key word"
    ]],
    '針對某翻譯(xxx功能專用)'=>["關鍵字" => [
        "zh_tw" => "關鍵字",
        "cn" => "关键词",
        "en" => "key word"
    ]]];
}

/**
 * 取得現有語系
 *
 * @global array $_lan_ary
 * @return array $_lan_ary
 */
function get_lan_ary()
{
    $lan_helper = new lan_helper();
    if (!is_array($lan_helper::$_lan_ary) || count($lan_helper::$_lan_ary) == 0) {
        initialize_lan_data();
    }
    return $lan_helper::$_lan_ary;
}


/**
 * 設定現在要使用哪個語系
 *
 * @param string $lan 設定使用者的語系 預設使用 DEFAULT_LANG 所設定的語系 ex:zh_tw
 * @param boolean $temp 是否為暫時設定 (並不存入session，只在此次request中有效)
 * @return string 回傳實際設定了什麼語系
 */
function set_lan($lan = DEFAULT_LANG,$temp = false)
{
    // global $_lan_temp_lan;
    $lan_helper = new lan_helper();
    if ($temp) {
        $lan_helper::$_lan_temp_lan = $lan;
    }else{
        $CI = &get_instance();
        $CI->session->set_userdata('lan', $lan);
        return get_lan();
    }
}

/**
 * 取得當下所設定的語系
 *
 * @return string 若當下沒有設定任何語系，將回傳null
 */
function get_lan()
{
    $lan_helper = new lan_helper();
    if (!empty($lan_helper::$_lan_temp_lan)) {
        return $lan_helper::$_lan_temp_lan;
    }else{
        $CI = &get_instance();
        $lan_temp = $CI->session->userdata('lan');
        if ($lan_temp == null) {
            set_lan();
            $lan_temp = $CI->session->userdata('lan');
        }
        return $lan_temp == null?'':$lan_temp;
    }
}

/**
 * 初始化語系資料
 *
 * @param array $point_path_array 針對某翻譯(xxx功能專用)，若要全站通用的翻譯請傳空陣列
 * @param array $lan              語系初始化(不撈全部語系，來減少效能耗損)
 * @param string $source          翻譯的來源(目前只有db)
 */
function initialize_lan_data($point_path_array = [], $lan = [],$source = 'db')
{
    $lan_helper = new lan_helper();

    $lan_helper::$_lan_source = $source;
    switch ($lan_helper::$_lan_source) {
        default:
            $CI = &get_instance();
            $lan_helper::$_lan_ary = [];
            $lan_from_db = $CI->db->select('COLUMN_NAME')
                ->from('INFORMATION_SCHEMA.COLUMNS')
                ->where('TABLE_NAME', 'sys_language_data')
                ->where('TABLE_SCHEMA', $CI->db->database)
                ->where("COLUMN_NAME NOT IN ('sid','point_path','key_word','create_url_path','create_post_param')")
                ->get()->result();
            foreach ($lan_from_db as $value) {
                $lan_helper::$_lan_ary[$value->COLUMN_NAME] = $value->COLUMN_NAME;
            }

            //get_lan有可能會回傳空的，所以還是要判斷一下
            $lan_temp = get_lan();
            if(count($lan) == 0 && $lan_temp != ''){
                $lan = [$lan_temp];
            }

            $target_lan = (count($lan) > 0?implode(',', $lan):implode(',', $lan_helper::$_lan_ary));
            $CI->db
            ->select('key_word,point_path,' . $target_lan)
            ->from('sys_language_data')
            ->where("point_path IS NULL OR point_path = ''");
            foreach ($point_path_array as $value) {
                $CI->db->or_where('point_path',($value==null?'':$value));
            }
            $lan_data_temp = $CI->db->get()->result();
            
            foreach ($lan_data_temp as $lan_obj) {
                $lan_helper::$_lan_data[$lan_obj->point_path][$lan_obj->key_word] = (array)$lan_obj;
            }
            break;
    }
    if(!is_array($lan_helper::$_lan_data)){
        $lan_helper::$_lan_data = [];    //當資料庫沒有任何翻譯的時候
    }
}

/**
 * 要翻譯多語系的關鍵字_
 *
 * @param string $search_word   欲翻譯的文字 (若翻譯的字數太長，可以只下關鍵字，並搭配$point_path指定某項功能特定的翻譯)
 * @param string $point_path    預設為空，可針對某功能翻譯(xxx功能專用)
 * @param string $lan           要翻譯的語系，若為空則呼叫get_lan()來使用當下的語系
 * @return string
 */
function _lan_only($search_word, $point_path = '', $lan = null)
{
}

/**
 * 要翻譯多語系的關鍵字
 *
 * @param string $search_word   欲翻譯的文字 (若翻譯的字數太長，可以只下關鍵字，並搭配$point_path指定某項功能特定的翻譯)
 * @param string $point_path    預設為空，可針對某功能翻譯(xxx功能專用)
 * @param string $lan           要翻譯的語系，若為空則呼叫get_lan()來使用當下的語系
 * @param string $default       該語系沒有翻譯，預設文字 (若為null，則預設使用$search_word)
 * @return string
 */
function _lan($search_word, $point_path = '', $lan = null, $default = null)
{
    $add_new_key = true;

    if ($lan == null) {
        $lan = get_lan();
    }
    $lan_helper = new lan_helper();

        // 檢查有沒有這些資料，並試著載入看看
        if (!is_array($lan_helper::$_lan_data)
         || !array_key_exists($point_path, $lan_helper::$_lan_data)
         || !array_key_exists($search_word, $lan_helper::$_lan_data[$point_path])
         || !array_key_exists($lan, $lan_helper::$_lan_data[$point_path][$search_word])) {
            initialize_lan_data([$point_path],[$lan]);
        }

        // 雖然有載入，但有可能連來源都沒有這項翻譯，所以還是要先檢查過才return
        if (array_key_exists($point_path, $lan_helper::$_lan_data)
            && array_key_exists($search_word, $lan_helper::$_lan_data[$point_path])
            && array_key_exists($lan, $lan_helper::$_lan_data[$point_path][$search_word])) {
            if ($lan_helper::$_lan_data[$point_path][$search_word][$lan] != '') {
                return $lan_helper::$_lan_data[$point_path][$search_word][$lan];
            } else {
                $add_new_key = false;   //有key，但未有此項翻譯
            }
        }

    if ($add_new_key) {
        $CI = &get_instance();
        $count = $CI->db->select('count(sid) as count')->from('sys_language_data')
        ->where(['key_word' => $search_word,'point_path'=>$point_path])
        ->get()->result();

        if ($count[0]->count == '0') {
            $CI->db->insert('sys_language_data', ['key_word' => $search_word, 'create_url_path' => $_SERVER['REQUEST_URI'],'create_post_param'=>json_encode($_POST),'point_path'=>$point_path]);
        }
    }

    if (!$add_new_key && !is_null($default)) {
        $search_word = $default;
    }

    return $search_word;
}