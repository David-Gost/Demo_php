<?php

require_once VENDOR_PATH . 'autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\IWriter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Description of Php_spreadsheet
 *
 * @author David
 */
class Php_spreadsheet {

    const FILE_TYPE_CSV = "csv";
    const FILE_TYPE_XLS = "xls";
    const FILE_TYPE_XLSX = "xlsx";

    public function data_export($column_title_array = array(), $column_data_array = array(), $file_name = "", $export_ext = "xls", $table_ex_title_array = array()) {


        $error_msg = '';
        $content_type = "";

        if (empty($file_name)) {
            $error_msg = "檔名空白\n";
        } else {

            switch ($export_ext) {

                case self::FILE_TYPE_CSV:

                    $content_type = "text/x-csv";

                    break;

                case self::FILE_TYPE_XLS:

                    $content_type = "application/vnd.ms-excel";

                    break;

                case self::FILE_TYPE_XLSX:

                    $content_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";

                    break;

                default :
                    $error_msg = "副檔名錯誤！";
                    break;
            }
        }

        if ($error_msg != '') {

            echo '<script>alert(\'' . $error_msg . '\');</script>';
            exit;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();






        //---表格項次
        $column = 1;

        //---表格額外標題
        foreach ($table_ex_title_array as $ex_title_value) {

            if (is_array($ex_title_value)) {
                //---維陣列時往旁邊排序
                foreach ($ex_title_value as $ex_child_title_key => $ex_child_title) {
                    $sheet->setCellValueByColumnAndRow($ex_child_title_key + 1, $column, $ex_child_title);
                }
            } else {
                $sheet->setCellValueByColumnAndRow(1, $column, $ex_title_value);
            }

            $column++;
        }

        //---表格標題

        if (count($column_title_array) > 0) {

            foreach ($column_title_array as $column_title_key => $column_title) {
                $sheet->setCellValueByColumnAndRow($column_title_key + 1, $column, $column_title);
            }

            $column += 1;
        }


        //---表格資料
        foreach ($column_data_array as $column_data) {

            $child_count = 0;

            if (is_array($column_data)) {
                //---維陣列時往旁邊排序

                foreach ($column_data as $column_child_count => $value) {

                    $child_count = $column_child_count + 1;
//                    $sheet->getCellByColumnAndRow($child_count, $column, $value);
                    $sheet->setCellValueExplicitByColumnAndRow($child_count, $column, $value, PHPExcel_Cell_DataType::TYPE_STRING);

                    $style = $sheet->getStyleByColumnAndRow($child_count, $column);

                    $style->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }
            } else {
                $sheet->setCellValueExplicitByColumnAndRow(1, $column, $column_data, PHPExcel_Cell_DataType::TYPE_STRING);

                $style = $sheet->getStyleByColumnAndRow(1, $column);

                $style->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            }



            $column += 1;
        }


//        echo '<pre>', print_r($spreadsheet), '</pre>';
//        exit();

        $file_name = $file_name . '.' . $export_ext;
        header('Content-Type: ' . $content_type);
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
//
        $writer = IOFactory::createWriter($spreadsheet, ucfirst($export_ext));
        $writer->save('php://output');
        exit();
    }

    /**
     * <p>讀取表單資料，成功時回傳陣列</p>
     * @param type $file_name
     * <p>檔名 or 路徑</p>
     * @param type $start_load
     * <p>讀取起始位置預設2</p>
     */
    public function data_load($file_name, $start_load = 2) {
        // 這步棋室就是創建reader，免去了你手動創建

        try {

            $reader = IOFactory::load($file_name);


            $sheet = $reader->getActiveSheet();

            foreach ($sheet->getRowIterator($start_load) as $row) {
                $tmp = array();
                foreach ($row->getCellIterator() as $cell) {
                    $tmp[] = $cell->getFormattedValue();
                }
                $res[$row->getRowIndex()] = $tmp;
            }

            return $res;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
