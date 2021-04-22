<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Sysinfo_model $sysinfo_model 
 * @property Sysinfo_dto $sysinfo_dto
 * 
 */
class Sysinfo_manage extends AC_Controller {

    const TITLE = '系統參數維護';

    public function __construct() {

        parent::__construct();
        $this->load->model("sys/Sysinfo_model", "sysinfo_model");
        $this->load->library("dto/Sysinfo_dto", "sysinfo_dto");
    }

    public function index() {
        $this->mTitle = Sysinfo_manage::TITLE;
        $this->mViewFile = 'sys/sysinfo_manage';

        $sysinfo = $this->sysinfo_model->find_sysinfo();
        $this->mViewData['sysinfo'] = $sysinfo;
    }

    public function modify_sysinfo() {

        $this->sysinfo_dto->setSid($this->input->post('sid'));
        $this->sysinfo_dto->setSitename($this->input->post('sitename'));
        $this->sysinfo_dto->setSitecompany($this->input->post('sitecompany'));
        $this->sysinfo_dto->setSubsitename($this->input->post('subsitename'));
        $this->sysinfo_dto->setSiteurl($this->input->post('siteurl'));
        $this->sysinfo_dto->setSitetitle($this->input->post('sitetitle'));
        $this->sysinfo_dto->setMetakeywords($this->input->post('metakeywords'));
        $this->sysinfo_dto->setMetadescription($this->input->post('metadescription'));
        $this->sysinfo_dto->setMetacompany($this->input->post('metacompany'));
        $this->sysinfo_dto->setMetacopyright($this->input->post('metacopyright'));
        $this->sysinfo_dto->setMetaauthor($this->input->post('metaauthor'));
        $this->sysinfo_dto->setMetaauthoremail($this->input->post('metaauthoremail'));
        $this->sysinfo_dto->setMetaauthorurl($this->input->post('metaauthorurl'));
        $this->sysinfo_dto->setMetacreationdate($this->input->post('metacreationdate'));
        $this->sysinfo_dto->setMetarating($this->input->post('metarating'));
        $this->sysinfo_dto->setMetarobots($this->input->post('metarobots'));
        $this->sysinfo_dto->setMetapragma($this->input->post('metapragma'));
        $this->sysinfo_dto->setMetacache_control($this->input->post('metacache_control'));
        $this->sysinfo_dto->setMetaexpires($this->input->post('metaexpires'));
        $this->sysinfo_dto->setUseverify($this->input->post('useverify') == 'on' ? '1' : '0');
        $this->sysinfo_dto->setIpsource($this->input->post('ipsource'));
        $this->sysinfo_dto->setHttpson_front($this->input->post('httpson_front') == 'on' ? '1' : '0');
        $this->sysinfo_dto->setHttpson_end($this->input->post('httpson_end') == 'on' ? '1' : '0');
        $this->sysinfo_dto->setIs_tag($this->input->post('is_tag') == 'on' ? '1' : '0');
        $this->sysinfo_dto->setLine_api($this->input->post('line_api') == 'on' ? '1' : '0');
        $this->sysinfo_dto->setSms_api($this->input->post('sms_api') == 'on' ? '1' : '0');
        $this->sysinfo_dto->setInvoice_api($this->input->post('invoice_api') == 'on' ? '1' : '0');
        $this->sysinfo_dto->setService_token($this->input->post('service_token'));
        //隱藏欄位
        $this->sysinfo_dto->setBgpic($this->input->post('bgpic'));


        //限制檔案上傳的檔案型態
        $this->upload->set_allowed_types('ico');
        if ($this->upload->has_uploaded_file('sysico')) {

            $result = $this->upload->do_upload('sysico');
            if (!$result) {
                $error = $this->upload->errors();
                set_danger_alert($error, '系統小圖');
                redirect('sys/sysinfo_manage');
            } else {
                $data = $this->upload->data();
                $filename = $data['file_name'];
                $this->sysinfo_dto->setSysico($filename);
            }
        } else {
            $this->sysinfo_dto->setSysico($this->input->post('origin_ico'));
        }
        $affected_rows = $this->sysinfo_model->modify_sysinfo($this->sysinfo_dto);

        if ($affected_rows > 0) {
            set_success_alert("更新完成", Sysinfo_manage::TITLE);
        } else {
            set_warn_alert('更新失敗', Sysinfo_manage::TITLE);
        }
        redirect('sys/sysinfo_manage');
    }

}
