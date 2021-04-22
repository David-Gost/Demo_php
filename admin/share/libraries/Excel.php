<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once VENDOR_PATH . 'autoload.php';

class Excel extends PHPExcel {

    private $CI;

    const HORIZONTAL_LEFT = 'left';
    const HORIZONTAL_RIGHT = 'right';
    const HORIZONTAL_CENTER = 'center';

    public function __construct() {
        parent::__construct();

        $this->CI = & get_instance();
    }

    public function exports($header, $excel_data, $title = '', $filename = '') {

        $this->print_header($header, 1);

//寫入資料內容
        $this->print_content($excel_data, 2, $header);

        if (empty($filename)) {
            $filename = date('Y-m-d');
        }

        $this->output_excel($title, $filename);
    }

    public function merger_cells($pCoordinate, $pRange, $pValue, $alignment = Excel::HORIZONTAL_CENTER) {

        switch ($alignment) {
            case Excel::HORIZONTAL_LEFT:
                $align = PHPExcel_Style_Alignment::HORIZONTAL_LEFT;
                break;
            case Excel::HORIZONTAL_RIGHT:
                $align = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
                break;
            default:
                $align = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
        }
        $this->CI->excel->getActiveSheet()->getStyle($pCoordinate)->getAlignment()->setHorizontal($align);
        $this->CI->excel->getActiveSheet()->setCellValueExplicit($pCoordinate, $pValue)->mergeCells($pRange);
    }

    public function output_excel($title, $filename) {

        $this->CI->excel->setActiveSheetIndex(0);
        $this->CI->excel->getActiveSheet()->setTitle($title);
ob_end_clean();
// Sending headers to force the user to download the file
//header('Content-Type:  application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->CI->excel, 'Excel5');
//force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    public function output_pdf($title, $filename) {

        $rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
        $rendererLibraryPath = APPPATH . "/third_party/tcpdf/tcpdf.php";
        dump(PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath));
        if (!PHPExcel_Settings::setPdfRenderer(
                        $rendererName, $rendererLibraryPath
                )) {
            die(
                    'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
                    '<br />' .
                    'at the top of this script as appropriate for your directory structure'
            );
        }

        $this->CI->excel->setActiveSheetIndex(0);
        $this->CI->excel->getActiveSheet()->setTitle($title);
        
        // Sending headers to force the user to download the file
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
        header('Cache-Control: max-age=0');

        //$objWriter = new PHPExcel_Writer_PDF($this->CI->excel);
        $objWriter = PHPExcel_IOFactory::createWriter($this->CI->excel, 'PDF');
        //force user to download the Excel file without writing it to server's HD
        ob_end_clean();
        //$objWriter->save('php://output');
        //$objWriter->save(str_replace('..php', '.pdf', __FILE__));
        $objWriter->save('/Applications/XAMPP/xamppfiles/htdocs/sys_km/resource/files/' . $filename . 'pdf');
        exit;
    }

    public function print_header($header, $start_row) {

        $col = 0;
        foreach ($header as $field) {
            $this->CI->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $start_row, $field);
            $col++;
        }
    }

    public function print_content($excel_data, $start_row, $header) {

        //  $row = $start_row;
        foreach ($excel_data as $data) {

            for ($col = 0; $col < sizeof($header); $col++) {
                $this->CI->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $start_row, $data[$col]);
            }
            $start_row++;
        }
    }

}
