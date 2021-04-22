<?php

/**
 * @property Serial_no_model $serial_no_model
 * 
 */
class Serial_no {

    /**
     * ci
     *
     * @param instance object
     */
    private $_ci;

    public function __construct() {

        $this->_ci = &get_instance();
        $this->_ci->load->model('sys/Serial_no_model', 'serial_no_model');
    }

    public function next_serial_no($no_type, $no_len = 5, $format = '{0}-{1}{2}') {

        $year_month_date = mb_substr(date('Ymd'), 2, strlen(date('Ymd')), "utf-8");
        $serial_no_dto = $this->_ci->serial_no_model->get_serial_no($no_type, $year_month_date);

        $seq_no = "";
        if (is_null($serial_no_dto)) {

            //insert
            $serial_no_dto = new Serial_no_dto();
            $serial_no_dto->setNo_type($no_type);
            $serial_no_dto->setYear_month_date($year_month_date);
            $serial_no_dto->setSeq_no(1);

            $seq_no = $this->format_num(1, $no_len);

            $this->_ci->serial_no_model->add_serial_no($serial_no_dto);
        } else {

            //update
            $seq = (int) ($serial_no_dto->getSeq_no() + 1);
            $serial_no_dto->setNo_type($no_type);
            $serial_no_dto->setYear_month_date($year_month_date);
            $serial_no_dto->setSeq_no($seq);
            $this->_ci->serial_no_model->update_serial_no($serial_no_dto);

            $seq_no = $this->format_num($seq, $no_len);
        }

        $format_array = [$no_type, $year_month_date, $seq_no];

        return MessageFormatter::formatMessage(DEFAULT_LANG, $format, $format_array);
    }

    private function format_num($seq_no, $no_len) {

        return str_pad($seq_no, $no_len, '0', STR_PAD_LEFT);
    }

}
