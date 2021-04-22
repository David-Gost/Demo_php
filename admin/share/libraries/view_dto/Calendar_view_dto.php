<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once APPLICATION_BACKEND_DTO_PATH . 'Base_view_dto.php';
class Calendar_view_dto extends Base_view_dto
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public $used_sid;
    public $parent_sid;
    public $start_date;
    public $end_date;
    public $repeat_stop;
    public $repeat_times;
    public $repeat_gap;
    public $repeat_type;
    public $repeat_value;
    public $title;
    public $color;
    public $allDay;
    public $event_sid;
}