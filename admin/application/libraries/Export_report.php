<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Csv
 *
 * @author David
 */
class Export_report {

    private $is_csv;
    private $file_name;
    private $title_array;
    private $reports_array;
    private $separated_symbols;

    /**
     * 
     * @param type $title_array
     * <p>標題陣列</p>
     * @param type $reports_array
     * <p>內容陣列</p>
     * @param type $file_name
     * <p>檔案名稱，預設為目前時間Y-m-d H:i</p>
     */
    function __construct($title_array = array(), $reports_array = array(),$file_name = '') {
//        if ($is_csv) {
            $this->separated_symbols = ',';
//        } else {
//            $this->separated_symbols = '\t';
//        }

        if ($file_name == '') {
            $file_name = date('Y-m-d H:i', time());
        }

        $this->file_name=$file_name;
        $this->is_csv = true;
        $this->title_array = $title_array;
        $this->reports_array = $reports_array;
    }

    public function create_reports() {

        $title = $this->create_title();
        $body = $this->create_create_report_body();
        
        

        if ($this->is_csv) {

            header("Content-type: text/x-csv");
            header("Content-Disposition: attachment; filename=$this->file_name.csv");
        } else {
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:filename=$this->file_name.xls");
        }
        
        echo mb_convert_encoding($title."\n".$body , "Big5" , "UTF-8");
        
        exit();
    }

    private function create_title() {


        if (count($this->getTitle_array()) == 0) {
            $title = '';
        } else {
            $title = '';

            foreach ($this->getTitle_array() as $title_dto) {

                if ($title_dto->is_ln) {
                    $new_line = "\n";
                } else {
                    $new_line = '';
                }

                $title.=$title_dto->title . $this->separated_symbols . $title_dto->title_value . $new_line;
            }
        }

        return $title;
    }

    private function create_create_report_body() {

        $body='';
        if (count($this->reports_array) == 0) {
            $body = '';
        } else {

            foreach ($this->reports_array as $value_array) {

                foreach ($value_array as $value) {
                    $body.="=\"$value\"" . $this->separated_symbols;
                }
                $body.="\n";
            }
        }

        return $body;
    }

    /**
     * @return title_dto
     */
    function getTitle_array() {
        return $this->title_array;
    }

    function getReports_array() {
        return $this->reports_array;
    }

    function setTitle_array($title_array) {
        $this->title_array = $title_array;
    }

    function setReports_array($reports_array) {
        $this->reports_array = $reports_array;
    }

}

class Title_dto {

    public $title;
    public $title_value;
    public $is_ln = false;

    function getTitle() {
        return $this->title;
    }

    function getTitle_value() {
        return $this->title_value;
    }

    function getIs_ln() {
        return $this->is_ln;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setTitle_value($title_value) {
        $this->title_value = $title_value;
    }

    function setIs_ln($is_ln) {
        $this->is_ln = $is_ln;
    }

}
