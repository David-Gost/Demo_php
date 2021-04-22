<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Serial_no_dto {

    public function __construct() {
        
    }

    public $sid;
    public $no_type;
    public $year_month_date;
    public $seq_no;

    function getSid() {
        return $this->sid;
    }

    function getNo_type() {
        return $this->no_type;
    }

    function getYear_month_date() {
        return $this->year_month_date;
    }

    function getSeq_no() {
        return $this->seq_no;
    }

    function setSid($sid) {
        $this->sid = $sid;
    }

    function setNo_type($no_type) {
        $this->no_type = $no_type;
    }

    function setYear_month_date($year_month_date) {
        $this->year_month_date = $year_month_date;
    }

    function setSeq_no($seq_no) {
        $this->seq_no = $seq_no;
    }

}
