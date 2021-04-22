<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class that acts as DAO (data access object).
 *
 */
class AC_Model extends CI_Model {

    protected $_table = NULL;
    protected $_primary_key = 'sid';
    protected $_dto = 'stdClass';

    function __construct() {
        parent::__construct();
        $this->load->database();
        // $this->db->save_queries = FALSE;

        $this->db->query('SET SQL_BIG_SELECTS=1 ;'); 

    }

    /**
     * Delegates all method calls to db if exists.
     */
    function __call($method, $params = array()) {
        if (method_exists($this->db, $method)) {
            call_user_func_array(array($this->db, $method), $params);
            return $this;
        }
        throw new Exception("Call to undefined method ".get_class($this)."::$method()");
    }

    /**
     * Acts as getter and setter of the database table.
     */
    function table($table = NULL) {
        if (is_null($table)) {
            return $this->_table;
        } else {
            $this->_table = $table;
            return $this;
        }
    }

    /**
     * Acts as getter and setter of the DTO (data transfer object).
     *
     * DTO is the object used in generating query results by default it is std
     * class object.
     */
    function dto($class = NULL) {
        if (is_null($class)) {
            return $this->_dto;
        } else {
            $this->_dto = $class;
            return $this;
        }
    }

    /**
     * Returns the primary key of a table
     */
    function pk() {
        return $this->_primary_key;
    }

    /**
     * Fetches a single record as an instance of the dto setting.
     */
    function get() {
        $query = $this->db->get($this->_table);

        $this->log_sql();
        if ($query->num_rows() == 1) {
            return $query->row(0, $this->dto());
        }
        return null;
    }

    function get_bylan($lan) {
        $query = $this->db->get_where($this->_table, array('lan' => $lan));
        $this->log_sql();
        if ($query->num_rows() > 0) {
            return $query->row(0, $this->dto());
        } else {
            return NULL;
        }
    }

    /**
     * Fetches all records loaded from the db.
     */
    function all() {
        $query = $this->db->get($this->_table);
        $this->log_sql();
        if ($query->num_rows() > 0) {
            return $query->result($this->dto());
        } else {
            return array();
        }
    }

    function all_bylan($lan) {
//        $this->db->order_by('sequence asc');
        $query = $this->db->get_where($this->_table, array('lan' => $lan));
        $this->log_sql();
        if ($query->num_rows() > 0) {
            return $query->result($this->dto());
        } else {
            return array();
        }
    }

    /**
     *
     * @param type $sql
     * @param type $props
     * @param type $is_array 回傳資料是否為多筆陣列
     * @return type
     */
    function query_by_sql($sql, $props = array(), $is_array = TRUE) {

        if (empty($props)) {
            $query = $this->db->query($sql);
        } else {
            $query = $this->db->query($sql, $props);
        }


        $this->log_sql();
        if ($is_array) {

            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return array();
            }
        } else {

            if ($query->num_rows() > 0) {
                return $query->row(0);
            } else {
                return NULL;
            }
        }
    }

    /**
     *
     * @param string $sql
     * @param type $offset
     * @param type $limit
     * @param type $is_array 回傳資料是否為多筆陣列
     * @return type
     */
    function query_page_by_sql($sql, $offset, $limit, $is_array = TRUE) {

        $sql .= " limit " . $offset . ", " . $limit;
        $query = $this->db->query($sql);

        $this->log_sql();
        if ($is_array) {

            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return array();
            }
        } else {

            if ($query->num_rows() > 0) {
                return $query->row(0);
            } else {
                return NULL;
            }
        }
    }

    /**
     * Loads all records an returns an associative array with primary keys as key.
     */
    function as_list($property) {
        $data = array();
        $objects = $this->all();

        foreach ($objects as $object) {
            $data[$object->id] = $object->{$property};
        }

        return $data;
    }

    function find_by_sid($sid) {
        $tmp = $this->where(array($this->_primary_key => $sid))->get();
        $this->log_sql();
        return $tmp;
    }

    /**
     * Shorthand for finding an object by id.
     */
    function find_by_id($id)
    {
        return $this->find_by_sid($id);
    }

    function find($where, $orderby = '', $direction = '') {
        $this->db->order_by($orderby, $direction);
        $this->db->where($where);
        $this->log_sql();
        return $this;
    }

    public function find_all() {
        $this->db->order_by('sequence,sid desc');
        return $this->all();
    }

    /**
     * Fetches an object or raises 404.
     */
    function get_object_or_404($where) {

        $object = $this->where($where)->get();
        $this->log_sql();
        if ($object) {
            return $object;
        } else {
            show_404();
        }
    }

    /**
     * Creates new record in the databse.
     */
    function create($props, $unset_array = []) {

        $props = $this->afield_type_int($props);

        for ($i = 0; $i < sizeof($unset_array); $i++) {

            unset($props[$unset_array[$i]]);
        }

        $this->db->insert($this->_table, $props);
        $this->log_sql();
        return $this->db->insert_id();
    }

    /**
     * 
     * @param array $where
     * <p>ex:array('sid'=>value,'tag'=>value)</p>
     */
    function find_dto_by_key_where($where = array()) {
        $data = $this->find($where)->get();
        $this->log_sql();
        return $data;
    }

    /**
     *
     * @param type $id
     * @param type $props
     * @return int affected_rows
     */
    function update($id, $props, $unset_array = []) {


        $props = $this->afield_type_int($props);

        unset($props['createdate']);
        unset($props['createuser']);

        for ($i = 0; $i < sizeof($unset_array); $i++) {

            unset($props[$unset_array[$i]]);
        }

        $this->db->where($this->pk(), $id)->update($this->_table, $props);
        $this->log_sql();
        return $this->db->affected_rows();
    }

    /**
     * Deletes a record based on it's primary key.
     */
    function delete($id) {

        $this->db->where($this->pk(), $id)->delete($this->_table);
        $this->log_sql();
        return $this->db->affected_rows();
    }

    /**
     *
     * @param array $props 條件陣列
     * @return int affected_rows
     */
    function delete_where($props) {

        if (!empty($props)) {
            $this->db->where($props);
            $this->db->delete($this->_table);
            $this->log_sql();
            return $this->db->affected_rows();
        }
    }

    /**
     *
     * @param dto $dto
     * @return int $insert_id
     */
    function add_dto($dto) {
        $data = object_to_array($dto);
        $insert_id = $this->create($data);

        return $insert_id;
    }

    /**
     * 產生新的dto物件，clone後回傳供使用
     * clone是為了避免傳址造成，同時處理多個object時相互干擾
     * 如果不要clone的，請自行取用預設載入的dto
     */
    function new_dto($dto_name = '') {
        if (strlen($dto_name) == 0) {
            $dto_name = strtolower($this->_dto);    //依據CI的手冊，一但載入library之後, 『你可以取用你的類別採用 小寫 的方式』
        }
        $tmp_dto = new $dto_name();
        $re_dto = clone $tmp_dto;
        return $re_dto;
    }

    /**
     *
     * @param dto $dto
     * @return int $affected_rows
     */
    function modify_dto($dto) {
        $data = object_to_array($dto);
        $affected_rows = $this->update($dto->sid, $data);

        return $affected_rows;
    }

    /**
     * Performs pagination.
     */
    function paginate($offset, $limit) {
//        $this->db->order_by('sequence asc');
        $this->db->offset($offset)->limit($limit);
        $this->log_sql();
        return $this;
    }

    /**
     * 
     */
    function select_max($sql) {
        return $this->db->select_max($sql);
    }

    /**
     * Count all records loaded by the db.
     */
    function count() {
        return $this->db->count_all_results($this->_table);
    }

    /**
     * 複寫db的function
     *
     * @param string $table_name
     * @return string
     */
    function count_all_results($table_name = NULL){
        if (is_null($table_name)) {
            return $this->db->count_all_results($this->_table);
        }else{
            return $this->db->count_all_results($table_name);
        }
    }

    function count_bylan($lan) {

        $this->db->where('lan', $lan);
        $this->db->from($this->_table);
        return $this->db->count_all_results();
    }

    /**
     * 複寫db的function
     *
     * @param string $table_name
     * @return string
     */
    function count_all($table_name = NULL){
        if (is_null($table_name)) {
            return $this->db->count_all($this->_table);
        }else{
            return $this->db->count_all($table_name);
        }
    }

    public function log_sql() {
        if (ENVIRONMENT != 'production') {
//            $this->fb->warn($this->db->last_query(), 'sql=>');
        }
    }

    function begin_trans() {

        $this->db->trans_start();
    }

    function complete_trans() {
        $this->db->trans_complete();
    }

    private function afield_type_int($props = array()) {
        $this->db->query('SET @@GLOBAL.SQL_MODE = ""');

        $Obj = $this->query_by_sql('SHOW COLUMNS FROM ' . $this->_table);

        $fieldsObj = [];
        foreach ($Obj as $field) {
            $afield = new stdClass();

            $afield->name = $field->Field;
            sscanf($field->Type, '%[a-z](%d)', $afield->type, $afield->max_length);
            $afield->null = $field->Null == 'YES' ? 1 : 0;
            $afield->default = $field->Default;
            $afield->primary_key = (int) ($field->Key === 'PRI');

            $fieldsObj[$field->Field] = $afield;
        }

        foreach ($props as $key => $value) {
            if (array_key_exists($key, $fieldsObj)) {
                if ($value === '' || $value === NULL) {
                    $afield = $fieldsObj[$key];

                    $is_null = $afield->null;
                    $empty_flag = $props[$afield->name] === '';
                    $null_flag = $props[$afield->name] === NULL;

                    switch ($afield->type) {
                        case 'bigint':
                        case 'int':
                            if (!$is_null && ($empty_flag || $null_flag)) {
                                $props[$afield->name] = 0;
                            } else {
                                $props[$afield->name] = NULL;
                            }

                            break;
                        case 'float':
                        case 'double':
                            if (!$is_null && ($empty_flag || $null_flag)) {
                                $props[$afield->name] = 0.0;
                            } else {
                                $props[$afield->name] = NULL;
                            }

                            break;
                        case 'varchar':
                            if (!$is_null) {
                                $props[$afield->name] = '';
                            } else {
                                $props[$afield->name] = NULL;
                            }

                            break;
                        default :
                            break;
                    }
                }
            } else {
                unset($props[$key]);
            }
        }

        return $props;
    }

    /**
     * 將post,get,cookie...etc的資訊寫到dto 
     * (若需要做驗證，建議到個別dto的set處理，回傳的錯誤會在dto內加一個validation描述錯誤訊息)
     *      validation_message example:
     *          in dto:
     *          function setValue($value) {
     *              $this->value = $value;
     * 
     *                  if(empty($value)){
     *                      return 'it can't be set empty';
     *                  }
     *          }
     * 
     * @param input $input          資料來源 ex:從前端送進來的 CI input (post,get,cookie...etc)
     * @param array $whitelist      哪個欄位是合法的，前端可以送過來的 (避免駭客隨便猜到name直接塞值進來，若不使用此參數則統統合法)(若設定key，則可以把key映射到value去)
     * @param array $blacklist      禁止寫入某些欄位 (避免駭客隨便猜到name直接塞值進來，若不使用此參數則統統合法)
     * @param string $method       要使用資料來源的哪個method去取值 ex:CI's input method name
     * @param string $strict_level  判定是否合法輸入的等級，loose=黑名單才會刪除,standard=白名單以外都會刪除,strict=白名單以外，並且不在input裡面的都會刪除
     * @param string $dto_name      dto的class名稱，預設為空使用model預設的dto class (如果要使用自訂的class，請記得事先載入這個class才可以呼叫到)
     * @param string $whenArray  遇到陣列時的處理方法 (預設使用implode,以逗號做間隔)
     * @return *_dto                       回傳dto物件
     */
    public function params_to_dto($input, array $whitelist = [],$blacklist = [], $method = 'post',$strict_level = 'standard' ,$dto_name = '',$whenArray = 'implode') {
        $validation_message = [];
        $dto = $this->new_dto($dto_name);
        $base_dto_params = ['createdate', 'createuser', 'createip', 'updatedate', 'updateip'];
        foreach (get_class_vars(get_class($dto)) as $name => $value) {    //抓取dto的function
            $params_source_name = $name;
            $params_target_name = $name;
            $legal = true;
            $unset = false;
            if (count($whitelist) > 0) {
                $legal = false; //有送白名單進來，就先給他false，等等要判斷各個欄位是否合法
                $whitelist = array_merge($base_dto_params, $whitelist); //固定的資料庫欄位都要加到白名單內
                
                if($strict_level == 'standard' || $strict_level == 'strict'){
                    //standard與strict的等級中，只要不合法一定會刪除(白名單以外都會刪除)
                    $unset = true;
                }
            }

            foreach ($whitelist as $widx => $white) {    //有送$whitelist陣列進來，就會跑這個foreach                
                //一樣名字的or mapping過的
                if (is_int($widx)) {
                    $params_source_name = $white;
                }else{
                    $params_source_name = $widx;
                }

                if ($params_target_name == $params_source_name || (!is_int($widx) && $params_target_name == $white)) {
                    $legal = true;  //哦～原來這個name是合法的R～
                    $unset = false;
                    break;
                }
            }

            //若是在黑名單，強制設為不合法
            foreach ($blacklist as $black) {
                if ($params_target_name == $black) {
                    //在不合法名單內的就移除，讓他不影響其他人
                    $legal = false;
                    $unset = true;
                    break;
                }
            }

            if (!$legal) {
                //跳過不合法的來源
                if($unset){
                    unset($dto->$params_target_name);
                }
                continue;
            }
            
            //開始做正事
            $pararm = null;
            if (method_exists($input,$method)) {
                $pararm = $input->$method($params_source_name);   //抓取前端送進來的欄位
            }elseif (isset($input->{$params_source_name})) {
                $pararm = $input->{$params_source_name};
            }
            if (!is_null($pararm) && !in_array($params_source_name,$base_dto_params)) {
                //遇到陣列時的處理方法(上面有註解)
                if (gettype($pararm) == 'array') {
                    switch ($whenArray) {
                        case "implode":
                            $pararm = implode(',', $pararm);
                            break;
                    }
                }
                $error = null;
                //跑dto裡面的setter
                $funcname = 'set' . ucfirst($params_target_name);
                if (method_exists($dto, $funcname)) {
                    $error = $dto->$funcname($pararm);    //驗證不過的話會回傳error
                }else{
                    $dto->{$params_target_name} = $pararm;    //驗證不過的話會回傳error
                }
                
                if (!is_null($error)) {

                    //有一些人的寫法會在dto的setter 直接return this，所以還是要比對一下他是真的有error還是回傳自己
                    //其實我也不確定這樣寫邏輯對不對，後人看到想改就改看看吧～
                    if (!is_object($error) || get_class($error) != get_class($dto)) {
                        $validation_message[] = $error;
                    }
                }
            }elseif ($strict_level == 'strict' && !isset($dto->$params_target_name)) {
                unset($dto->$params_target_name);
            }
        }

        //若驗證有問題，要塞東西好回傳
        if (count($validation_message) > 0) {
            $dto->validation_message = $validation_message;
        }
        
        //輸出前，省略輸出Base_dto的private_key
        $dto = array_to_object(get_object_vars($dto));
        
        return $dto;
    }

    public function dto_validation($dto,$rules = [])
    {
        $re = [];
        if (count($rules) == 0) {
            if (method_exists($this, "valid_rule")) {
                $rules = $this->valid_rule();
            }
        }

        foreach ($dto as $dto_key => $dto_value) {
            if(array_key_exists($dto_key,$rules) && array_key_exists("err_msg",$rules[$dto_key])){
                $rule = $rules[$dto_key];
                $pass = false;
                if (array_key_exists("preg_match",$rule)) {
                    if (preg_match($rule["preg_match"],$dto_value)) {
                        $pass = true;
                    }
                }elseif (array_key_exists("empty",$rule)) {
                    if($rule["empty"] === true){
                        $pass = empty($dto_value);
                    }else{
                        $pass = !empty($dto_value);
                    }
                }elseif(count($rule) > 1){
                    foreach ($rule as $r_key => $r_value) {
                        if (method_exists($this, $r_key)) {
                            $params = [];
                            if(is_array($r_value)){
                                $params = $r_value;
                            }else{
                                $params = [$r_value];
                            }
                            $pass = (call_user_func_array(array($this, $r_key), array_merge([$dto_value],$params))?true:false);
                            if ($pass == false) {
                                break;
                            }
                        }
                    }
                }else{
                    $pass = !empty($dto_value); //除了err_msg以外，什麼都沒設就驗證不得為空
                }

                if (!$pass) {
                    $re[] = $rule["err_msg"];
                }
            }
        }
        return $re;
    }

    public $_mapping_array = [];

    public function dto_mapping($dto_name,$add_prefix = NULL,$suffix = NULL)
    {
        if (array_key_exists($dto_name, $this->_mapping_array)) {
            if (!is_null($add_prefix) || !is_null($suffix)) {
                $re = [];
                foreach ($this->_mapping_array[$dto_name] as $key => $value) {
                    if (is_numeric($key)) {
                        $re[$add_prefix.$value.$suffix] = $value;
                    }else{
                        $re[$add_prefix.$key.$suffix] = $value;
                    }
                    
                }
                return $re;
            }else{
                return $this->_mapping_array[$dto_name];
            }
        } else {
            return [];
        }
    }

    public function dto_mapping_reverse($dto_name)
    {
        $re = [];
        if (array_key_exists($dto_name, $this->_mapping_array)) {
            $tmp = $this->_mapping_array[$dto_name];
            foreach ($tmp as $key => $value) {
                    if (is_int($key)) {
                        $re[] = $value;
                    }else{
                        $re[$value] = $key;
                    }
            }
        }
        return $re;
    }
}