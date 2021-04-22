<?php

class Calendar_service extends AC_Model
{
    public $_table_name = '';
    public $_column = [];
    public $_load_event = [];
    public $processed_array = [];
    public $holiday_date_sql = '';
    const a_day = 86400; //一天的秒數
    const a_week = 604800;   //一週的秒數
    const day_31 = 2678400; //31天的秒數
    const date_format = 'Y-m-d H:i:s';


    public $_mapping_array = [];

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->library("view_dto/Calendar_view_dto", "calendar_view_dto");
        $this->_dto = 'Calendar_view_dto';
        $this->_mapping_array = [
            "Calendar_event_dto" =>
            ["event_sid" => "sid", "parent_sid", "start_date", "end_date", "repeat_stop", "repeat_times", "repeat_gap", "repeat_type", "repeat_value", "title", "allDay","use_table_sid"]
            , ["Calendar_use_table_dto" => []]
        ];

        $this->load->model("custom/Calendar_use_table_model", "calendar_use_table_model");
        $this->load->model("custom/Calendar_event_model", "calendar_event_model");
    }


    public function dto_mapping($dto_name)
    {
        if (array_key_exists($dto_name, $this->_mapping_array)) {
            return $this->_mapping_array[$dto_name];
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
                        $re[] = $key;
                    }else{
                        $re[$value] = $key;
                    }
            }
        }
        return $re;
    }

    /**
     * 註冊要使用行事曆的人
     * 
     * @param string $table_name    該行事曆現在要跟哪張表綁定
     * @param array $column         綁定後的表要撈哪些欄位出來
     */
    public function set_table_info($table_name, $column = ['cname', 'sid'])
    {
        $dto_name = strtolower($this->calendar_use_table_model->dto());
        $this->calendar_use_table_model->$dto_name->table_name = $table_name;

        $this->_table_name = $table_name;
        $this->_column = $column;
    }


    /**
     * 取得當下註冊使用行事曆的table資訊
     *
     * @return void
     */
    public function get_table_info()
    {
        $table_info = new stdClass();
        $table_info->table_name = $this->_table_name;
        $table_info->column = $this->_column;
        return $table_info;
    }

    /**
     * 取得註冊該行事曆的的sid
     *
     * @param integer $sid get_use_table_sid
     * @return void
     */
    public function get_used_sid($used_sid)
    {
        //用used_sid查詢sid
        $temp = $this->db->select('sid')->from('custom_calendar_use_table')->where(["table_name" => $this->_table_name, "used_sid" => $used_sid])->get()->result();

        $sid = (count($temp) == '0'?'0':$temp[0]->sid);
        if($sid == '0'){
            $this->db->insert('custom_calendar_use_table', ['table_name' => $this->_table_name,"used_sid"=>$used_sid]);
            $sid = $this->db->insert_id();
        }
        return $sid;
    }

    public function add_modify($source)
    {
        $calendar_event_dto = $this->calendar_event_model->params_to_dto($source, $this->dto_mapping($this->calendar_event_model->_dto), [], "get_var");
        $calendar_use_table_dto = $this->calendar_use_table_model->params_to_dto($source, ["used_sid", "table_name"], [], "get_var");

        $calendar_event_dto = ((object) array_merge( (array)$calendar_event_dto, ["use_table_sid"=>$this->get_used_sid($calendar_use_table_dto->used_sid)]));

        if (is_numeric($calendar_event_dto->sid) && $calendar_event_dto->sid > 0) {
            return $this->calendar_event_model->modify_dto($calendar_event_dto);
        } else {
            unset($calendar_event_dto->sid);
            return $this->calendar_event_model->add_dto($calendar_event_dto);
        }
    }


    public function delete_event($event_sid)
    {
        return $this->calendar_event_model->delete($event_sid);
    }

    public function delete_event_where($where_param = [])
    {
        return $this->calendar_event_model->delete_where($where_param);
    }

    // public function holiday($show_start,$show_end,$calendar,$only_unset = false)
    // {        
    //     $show_start = $show_start - $this::a_day;
    //     $holiday = $this->db->select("holiday_date,tmp.cname as cname,color")->from("($this->holiday_date_sql) as tmp")->join("custom_calendar_tag","custom_calendar_tag.sid = ".CALENDAR_EVENT_HOLIDAY,"left")->where("holiday_date >= $show_start")->where("holiday_date <= $show_end")->get()->result();

    //     //國定假日替換進來
    //     foreach ($holiday as $h_key => $h_value) {
    //         foreach ($calendar as $key => $value) {
    //             if ($h_value->holiday_date <= strtotime($value->start) && strtotime($value->end) <= ($h_value->holiday_date+86400)) {
    //                 unset($calendar[$key]);
    //             }
    //         }
    //         if (!$only_unset) {
    //             $h = new stdClass();
    //             $h->start = date("Y-m-d H:i:s",$h_value->holiday_date);
    //             $h->end = date("Y-m-d 23:59:59",$h_value->holiday_date);
    //             $h->tag_sid = CALENDAR_EVENT_HOLIDAY;
    //             $h->tag_cname = "國定假日";
    //             $h->title = $h_value->cname;
    //             $h->color = $h_value->color;
    //             $calendar[] = $h;
    //         }
    //     }

    //     $re_new = [];
    //     foreach ($calendar as $key => $value) {
    //         $re_new[] = $value;
    //     }

    //     return $re_new;
    // }

    /**
     * 取得行事曆
     *
     * @param int $show_start   篩選區間的起始日期(unix time)
     * @param int $show_end     篩選區間的結束日期(unix time)
     * @param array $used_sid   使用這個行事曆的人
     * @param array $column_search   其它搜尋條件
     * @param string $use_table_or_tag  使用的是自定義還是資料表 (預設table為資料表)
     * @param array $load_event   行事曆事件來源 (預設為空呼叫load_event來使用)
     * @return array
     */
    public function get_calendar($show_start, $show_end, $used_sid = [], $column_search = [], $use_table_or_tag = 'table', $load_event = null)
    {
        if (is_null($load_event)) {
            $this->_load_event = $this->load_event($show_start, $show_end, $used_sid, $column_search, $use_table_or_tag);
        } else {
            $this->_load_event = $load_event;
        }

        //洗資料，轉unixtime
        //這樣的寫法在php運算上比較快
        $key = array_keys($this->_load_event);
        $size = sizeOf($key);
        for ($i=0; $i<$size; $i++){
            $this->_load_event[$key[$i]]->start_date = strtotime($this->_load_event[$key[$i]]->start_date);
            $this->_load_event[$key[$i]]->end_date = strtotime($this->_load_event[$key[$i]]->end_date);
            $this->_load_event[$key[$i]]->repeat_stop = strtotime($this->_load_event[$key[$i]]->repeat_stop);
        }

        return $this->event_to_calendar($show_start, $show_end);
    }

    public function exclude_data($property_name, $white_list = [], $calendar = [],$refresh_array_key = true)
    {
        $re = $calendar;
        $calendar_count = count($calendar);
        if (empty($property_name)) {
            return $re;
        }
        foreach ($re as $key => $value) {
            $unset = true;
            foreach ($white_list as $property) {
                if ($value->$property_name == $property) {
                    $unset = false;
                }
            }
            if ($unset) {
                unset($re[$key]);
            }
        }
        if ($refresh_array_key) {
            if (count($re) != $calendar_count) {
                $re_new = [];
                foreach ($re as $key => $value) {
                    $re_new[] = $value;
                }
                $re = $re_new;
            }
        }
            return $re;
    }

    public function group_data($property_list = [],$calendar = [],$primary_prop_list = [],$group_name = "_group")
    {
        $re = [];
        foreach ($calendar as $key => $value) {
            $add_to_array = true;
            foreach ($re as $re_key => $re_value) {
                $match = true;
                foreach ($property_list as $p_key => $prop_name) {
                    if ($re_value->$prop_name == $value->$prop_name) {
                        continue;
                    }else{
                        $match = false;
                        break;
                    }
                }
                if (count($primary_prop_list) && $match) {
                    $match = false;
                    foreach ($re[$re_key]->{$group_name} as $g_key => $g_value) {
                        foreach ($primary_prop_list as $p_key => $primary_name) {
                            if ($g_value->$primary_name == $value->$primary_name) {
                                continue;
                            }else{
                                $match = true;
                                break;
                            }
                        }
    
                    }
                }
                if ($match) {
                    $re[$re_key]->{$group_name}[] = $value;
                    $add_to_array = false;
                }
            }
            if ($add_to_array) {
                $tmp = new stdClass();
                foreach ($property_list as $p_key => $prop_name) {
                    $tmp->$prop_name = $value->$prop_name;
                }
                $tmp->$group_name = [$value];
                $re[] = $tmp;
            }
        }
        return $re;
    }

    public function find_by_sid($sid)
    {
        return $this->db
        ->select("
            custom_calendar_event.sid as event_sid
            ,custom_calendar_use_table.sid as calendar_use_table_sid
            ,parent_sid
            ,start_date
            ,end_date
            ,repeat_stop
            ,repeat_times
            ,repeat_gap
            ,repeat_type
            ,repeat_value
            ,title
            ,color
            ,allDay
            ,use_table_sid
            ,custom_calendar_use_table.used_sid as used_sid
            ,custom_calendar_use_table.table_name as table_name"
        )
        ->from("custom_calendar_event")
        ->join("custom_calendar_use_table", "custom_calendar_use_table.sid = custom_calendar_event.use_table_sid")
        ->where("custom_calendar_event.sid",$sid)->get()->row(0,$this->_dto);
    }

    public function load_event($show_start, $show_end, $used_sid = [], $column_search = [], $use_table_or_tag = 'table')
    {
        //抓取行事曆 (custom_calendar_event是可以共用的，所以他的表看起來比較複雜
        $t_name = $this->_table_name;
        $other_select_sql = '';
        if ($this->_column != [] && $use_table_or_tag == 'table') {
            $column = $this->_column;
            foreach ($column as $value) {
                $other_select_sql .= " , $t_name.$value as $t_name" . '_' . "$value";
            }
        }

        $used_sid_sql = '';
        if (count($used_sid) > 0) {
            $used_sid_sql = "AND custom_calendar_use_table.used_sid in (" . implode(',', $used_sid) . ')';
        }

        foreach ($column_search as $key => $value) {
            if (count($value) > 0) {
                $this->db->where_in($key, $value);
            }
        }

        $this->db
            ->select("custom_calendar_use_table.table_name as table_name,custom_calendar_use_table.used_sid as used_sid
        ,custom_calendar_event.sid as event_sid, custom_calendar_event.*, $other_select_sql")
            ->from('custom_calendar_event')
            ->join("custom_calendar_use_table", "custom_calendar_use_table.sid = custom_calendar_event.use_table_sid and custom_calendar_use_table.table_name = '$t_name' $used_sid_sql", 'inner');
        if ($use_table_or_tag == 'table') {
            $this->db->join($t_name, "$t_name.sid = custom_calendar_use_table.used_sid", 'inner');
        }

        return $this->db
            ->group_start()
            ->or_where("($show_start >= UNIX_TIMESTAMP(start_date) AND $show_start <= UNIX_TIMESTAMP(end_date))")
            ->or_where("($show_start >= UNIX_TIMESTAMP(start_date) AND $show_start <= UNIX_TIMESTAMP(repeat_stop))")
            ->or_where("($show_end >= UNIX_TIMESTAMP(start_date) AND $show_end <= UNIX_TIMESTAMP(repeat_stop))")
            ->or_where("($show_start <= UNIX_TIMESTAMP(start_date) AND $show_end >= UNIX_TIMESTAMP(repeat_stop))")
            ->or_where("($show_end >= UNIX_TIMESTAMP(start_date) AND repeat_stop IS NULL)")
            ->or_where("($show_end = UNIX_TIMESTAMP(start_date))")
            ->group_end()
            ->get()->result($this->_dto);
    }

    private function switch_repeat_type($key, $show_start, $show_end)
    {
        $event = $this->_load_event[$key];
        $repeat = [];
        $result = [];
        switch ($event->repeat_type) {
            case 'type_day':
                $repeat = $this->repeat_day($event->repeat_gap, $event->start_date, $event->end_date, $show_start, $show_end, 0);
                break;
            case 'type_week':
                $repeat = $this->repeat_week($event->repeat_gap, explode(',', $event->repeat_value), $event->start_date, $event->end_date, $show_start, $show_end, 0);
                break;
            case 'type_month':
                $repeat = $this->repeat_month($event->repeat_gap, explode(',', $event->repeat_value), $event->start_date, $event->end_date, $show_start, $show_end, 0);
                break;
            default:
                break;
        }
        foreach ($repeat as $key => $value) {
            $temp = clone $event;
            $temp->event_sid = $event->sid;
            $temp->start = date('Y-m-d H:i:s', $value->start);
            $temp->end = date('Y-m-d H:i:s', $value->end);
            $temp->repeat_stop = date('Y-m-d H:i:s', $temp->repeat_stop);
            unset($temp->sid);
            $result[] = $temp;
        }
        return $result;
    }

    /**
     * 以遞迴的方式，從行事曆事件算出實際發生的時間日期 (包含行事曆事件的疊加都會自動處理)
     *
     * @param integer $show_start       篩選區間的起始日期(unix time)
     * @param integer $show_end         篩選區間的結束日期(unix time)
     * @param integer $parent_sid   覆蓋此行事曆的child事件 (預設-1為最高層機)
     * @return array 輸出格式為array(object)參考如下
     * 
     * object {
     *  event_sid:行事曆事件sid
     *  start:行事曆實際排定開始日期時間
     *  end:行事曆實際排定結束時間日期
     *  repeat_stop:結束重複的日期時間
     * }
     */
    public function event_to_calendar($show_start, $show_end, $parent_sid = -1)
    {
        $data_array = [];
        // 先計算自己的時間，計算到有人覆蓋我，就以覆蓋我的人為主，覆蓋我的人結束後若我還沒結束，則我的時間繼續計算
        foreach ($this->_load_event as $parent_key => $parent) {
            //這個sid要是否要計算 (接收上一層遞迴指定的sid or 遞迴的第一層全部都要計算)
            if ($parent->sid == $parent_sid || ($parent_sid == -1 && !in_array($parent->sid, $this->processed_array))) {
                if ($parent_sid == -1) {
                    $skip = false;
                    foreach ($this->_load_event as $tmp_ey => $tmp_value) {
                        //為處理_load_event裡面的子比父先出現的狀況，這邊先找看看有沒有自己的父輩
                        //有的話這一輪先跳過，等到自己的父輩時，從他的子輩就可以找到自己
                        if ($tmp_value->sid == $parent->parent_sid) {
                            $skip = true;
                            break;
                        }
                    }
                    if ($skip) {
                        continue;
                    }
                }
                $this->processed_array[] = $parent->sid;

                $child_data_array = [];
                $parent_end_date = (empty($parent->repeat_stop) || $parent->repeat_stop > $show_end ? $show_end : $parent->repeat_stop);   //未被覆蓋事件以前
                $parent_calendar = $this->switch_repeat_type($parent_key, $show_start, $parent_end_date);

                //先看看有沒有人覆蓋我
                foreach ($this->_load_event as $child) {
                    if ($child->parent_sid == $parent->sid) {
                        if (in_array($child->sid, $this->processed_array)) {
                            echo '<pre>', print_r('出現循環覆蓋事件，請聯絡技術廠商：$child->sid = ' . $child->sid . ' & $parent->sid = ' . $parent->sid . ' & $this->processed_array = ' . implode(',', $this->processed_array), true), '</pre>';
                            exit;
                        }

                        //使用遞迴讓child去計算時間
                        $child_data_array = array_merge($child_data_array, $this->event_to_calendar($show_start, $show_end, $child->sid));
                        $this->processed_array[] = $child->sid;
                    }
                }

                //child的值先塞進來
                $data_array = array_merge(
                    $data_array,
                    $child_data_array
                );

                //剩下的地方就是parent可以補進去的
                foreach ($parent_calendar as $p_value) {
                    $empty = true;
                    foreach ($child_data_array as $value) {
                        if ($p_value->start == $value->start && $p_value->end == $value->end && $p_value->end == $value->end) {
                            $empty = false;
                            break;
                        }
                    }
                    if ($empty) {
                        $data_array[] = $p_value;
                    }
                }
            }
        }
        return $data_array;
    }

    /**
     * 重複排定日期-month
     * 
     * @param int $gap     重複的頻率
     * @param array $month  指定月份的日期
     * @param strtotime $start_date    起始日期
     * @param strtotime $start_date    結束日期
     * @param strtotime $show_start      什麼時間點要開始show出來
     * @param strtotime $repeat_stop      結束重複
     * @param int       $repeat_times 重複次數 (與結束重複互斥，有結束重複就不會考慮重複次數)
     * 
     * (若要指定$repeat_times，則$repeat_stop要設為null)
     * 
     * @return array object
     */
    private function repeat_month(int $gap, array $month, int $start_date, int $end_date, int $show_start, int $repeat_stop, int $repeat_times)
    {
        $jump_range = $gap * 2419200;   //以28天當作一個月的跳躍基準，看要跳幾個月
        $date_gap = $end_date - $start_date;    //先計算開始和結束差距的時間，等等直接加上去就好，就不用計算兩次
        $temp_start_date = $start_date;
        if ($start_date >= $show_start) {
            $re = [$this->setStart_End($start_date, $end_date)];
        } else {
            //直接跳躍到要show的時間點內
            if (floor(($show_start - $start_date) / $jump_range) < $repeat_times) {
                $end_date += $jump_range * floor(($show_start - $start_date) / $jump_range);
                $start_date += $jump_range * floor(($show_start - $start_date) / $jump_range);
            }
            $re = [];
        }

        $Y = date('Y', $temp_start_date);
        $m = date('m', $temp_start_date);
        $H_i_s = date('H:i:s', $temp_start_date);

        //利用int最大值來解決$repeat_times和$repeat_stop互斥的問題
        if ($repeat_stop == 0) {
            $repeat_stop = PHP_INT_MAX;
        } else {
            $repeat_times = PHP_INT_MAX;
        }

        $i = 0;
        while (++$i < $repeat_times && $temp_start_date <= $repeat_stop) {
            $t = date('t', $temp_start_date); //本月最後一天
            foreach ($month as $day) {
                if ($day > $t) {
                    //超過本月最後一天的就跳過
                    continue;
                }

                //已經過去的日子不需要算進去
                //必須要用大於等於去判斷，因為當月1號是在等於的範圍內而不是大於
                $temp = strtotime($Y . '-' . $m . '-' . $day . ' ' . $H_i_s);
                if ($temp >= $start_date && $temp <= $repeat_stop) {
                    //要show出來的區間
                    if ($temp >= $show_start || ($temp <= $show_start && ($temp + $date_gap) >= $show_start)) {
                        $re[] = $this->setStart_End($temp, $temp + $date_gap);
                    }
                }
            }

            //因為$Y會被加總後的$m影響，所以以下兩行是有順序性的
            $Y += $gap % 12 == 0 ? $gap / 12 : floor(($m + $gap) / 12);   //加月份加到過年的要補給$Y (這樣的可以直接處理一次+13個月之類的ooxx使用方法)
            $m = $gap % 12 == 0 ? $m : ($m + $gap) % 12; //12進位，多的就捕到之後年度的月份。gap%12 == 時就是12的倍數，那就是原本的月份 (這樣的可以直接處理一次+12,+24個月之類的ooxx使用方法)

            $temp_start_date = strtotime($Y . '-' . $m . '-' . '01' . ' ' . $H_i_s); //下一輪要計算下一個月份的
        }
        if (count($re) >= 2 && $re[0] == $re[1]) { //設定的當天和算出來的第一天如果重複，就把設定的當天拿掉 (使用者很乖沒有設定A日期卻又重複B日期)
            unset($re[0]);
        }
        return $re;
    }

    /**
     * 重複排定日期-week
     * 
     * @param int $gap     重複的頻率
     * @param array $weeks  指定星期
     * @param strtotime $start_date    起始日期
     * @param strtotime $start_date    結束日期
     * @param strtotime $show_start      什麼時間點要開始show出來
     * @param strtotime $repeat_stop      結束重複
     * @param int       $repeat_times 重複次數 (與結束重複互斥，有結束重複就不會考慮重複次數)
     * 
     * (若要指定$repeat_times，則$repeat_stop要設為null)
     * 
     * @return array object
     */
    private function repeat_week($gap, array $weeks, $start_date, $end_date, $show_start, $repeat_stop, $repeat_times)
    {
        $jump_range = $gap * $this::a_week;
        $date_gap = $end_date - $start_date;    //先計算開始和結束差距的時間，等等直接加上去就好，就不用計算兩次
        //利用int最大值來解決$repeat_times和$repeat_stop互斥的問題
        if ($repeat_stop == 0) {
            $repeat_stop = PHP_INT_MAX;
        } else {
            $repeat_times = PHP_INT_MAX;
        }
        if ($start_date >= $show_start) {
            $re = [$this->setStart_End($start_date, $end_date)];
        } else {
            //直接跳躍到要show的時間點內
            if (floor(($show_start - $start_date) / $jump_range) < $repeat_times) {
                $end_date += $jump_range * floor(($show_start - $start_date) / $jump_range);
                $start_date += $jump_range * floor(($show_start - $start_date) / $jump_range);
            }
            $re = [];
        }

        $temp_start_date = $start_date;
        $i = 0;
        $while_repeat_stop = $repeat_stop + $this::a_week;    //while時會遇到最後一個禮拜有幾天被吃掉的問題，因為多加一個禮拜給他，然後實際用while裡面的if去判斷
        while (++$i < $repeat_times && $temp_start_date <= $while_repeat_stop) {
            foreach ($weeks as $key => $week) {
                if ($week > 7 || $week < 1) {
                    continue;   //這不是星期的範圍！
                }
                $date_week = date("w", $temp_start_date);   //先知道這是禮拜幾
                $days_gap = $date_week - $week; //計算目標日跟基準差幾天

                //今天減掉差距的幾天(若差距為負，就會--得正)，就會是這週禮拜幾的日期
                $temp = $temp_start_date - ($this::a_day * $days_gap);

                //沒有過過的日期才要回傳 || 第一次選擇的日期橫跨我們要找的日期
                if ($temp >= $start_date || ($start_date <= $show_start && $show_start <= $end_date)) {
                    //要show出來的區間
                    if ($temp >= $show_start && $temp <= $repeat_stop) {
                        $re[] = $this->setStart_End($temp, $temp + $date_gap);
                    }
                }
            }
            //基準日往後推一個禮拜，繼續下一輪
            $temp_start_date += $jump_range;
        }
        if (count($re) >= 2 && $re[0] == $re[1]) { //設定的當天和算出來的第一天如果重複，就把設定的當天拿掉 (使用者很乖沒有設定A日期卻指定重複B日期)
            unset($re[0]);
        }
        return $re;
    }

    /**
     * 重複排定日期-day
     * 
     * @param int $gap     重複的頻率
     * @param strtotime $start_date    起始日期
     * @param strtotime $start_date    結束日期
     * @param strtotime $show_start      什麼時間點要show出來
     * @param strtotime $repeat_stop      結束重複
     * @param int       $repeat_times 重複次數 (與結束重複互斥，有結束重複就不會考慮重複次數)
     * 
     * (若要指定$repeat_times，則$repeat_stop要設為null)
     * 
     * @return array object
     */
    private function repeat_day(int $gap, int $start_date, int $end_date, int $show_start, int $repeat_stop, int $repeat_times)
    {
        $date_gap = $end_date - $start_date;    //先計算開始和結束差距的時間，等等直接加上去就好，就不用計算兩次
        $re = [];
        $jump_range = $gap * $this::a_day;

        //利用int最大值來解決$repeat_times和$repeat_stop互斥的問題
        if ($repeat_stop == 0) {
            $repeat_stop = PHP_INT_MAX;
        } else {
            $repeat_times = PHP_INT_MAX;
        }

        if ($start_date < $show_start) {
            //直接跳躍到要show的時間點內
            if (floor(($show_start - $start_date) / $jump_range) < $repeat_times) {
                $end_date += $jump_range * floor(($show_start - $start_date) / $jump_range);
                $start_date += $jump_range * floor(($show_start - $start_date) / $jump_range);
            }
        }

        $i = 0;
        while (++$i < $repeat_times && $start_date < $repeat_stop) {
            if ($start_date >= $show_start && $start_date <= $repeat_stop || ($start_date <= $show_start && $show_start <= $end_date)) {
                $re[] = $this->setStart_End($start_date, $start_date + $date_gap);
            }
            $start_date += $gap * $this::a_day;
        }
        return $re;
    }

    /**
     * 減少行數用的，裡面就短短的程式碼，可是很多function都重複用到
     *
     * @param [strtotime] $start_date
     * @param [strtotime] $end_date
     * @return object
     */
    private function setStart_End($start_date, $end_date)
    {
        $temp_date_obj = new stdClass();
        $temp_date_obj->start = $start_date;
        $temp_date_obj->end = $end_date;
        return $temp_date_obj;
    }
}
