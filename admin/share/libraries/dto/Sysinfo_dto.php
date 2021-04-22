<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Sysinfo_dto extends Base_dto {

    public function __construct() {
        parent::__construct();
    }

    public $sitename;
    public $sitecompany;
    public $subsitename;
    public $siteurl;
    public $sitetitle;
    public $metakeywords;
    public $metadescription;
    public $metacompany;
    public $metacopyright;
    public $metaauthor;
    public $metaauthoremail;
    public $metaauthorurl;
    public $metacreationdate;
    public $metarating;
    public $metarobots;
    public $metapragma;
    public $metacache_control;
    public $metaexpires;
    public $sysico;
    public $useverify;
    public $ipsource;
    public $httpson_front;
    public $httpson_end;
    public $bgpic;
    public $is_tag;
    public $verification_type = 0;
    public $sms_api = 0;
    public $invoice_api = 0;
    public $line_api = 0;
    public $service_token;

    function getSitename() {
        return $this->sitename;
    }

    function getSitecompany() {
        return $this->sitecompany;
    }

    function getSubsitename() {
        return $this->subsitename;
    }

    function getSiteurl() {
        return $this->siteurl;
    }

    function getSitetitle() {
        return $this->sitetitle;
    }

    function getMetakeywords() {
        return $this->metakeywords;
    }

    function getMetadescription() {
        return $this->metadescription;
    }

    function getMetacompany() {
        return $this->metacompany;
    }

    function getMetacopyright() {
        return $this->metacopyright;
    }

    function getMetaauthor() {
        return $this->metaauthor;
    }

    function getMetaauthoremail() {
        return $this->metaauthoremail;
    }

    function getMetaauthorurl() {
        return $this->metaauthorurl;
    }

    function getMetacreationdate() {
        return $this->metacreationdate;
    }

    function getMetarating() {
        return $this->metarating;
    }

    function getMetarobots() {
        return $this->metarobots;
    }

    function getMetapragma() {
        return $this->metapragma;
    }

    function getMetacache_control() {
        return $this->metacache_control;
    }

    function getMetaexpires() {
        return $this->metaexpires;
    }

    function getSysico() {
        return $this->sysico;
    }

    function getUseverify() {
        return $this->useverify;
    }

    function getIpsource() {
        return $this->ipsource;
    }

    function getHttpson_front() {
        return $this->httpson_front;
    }

    function getHttpson_end() {
        return $this->httpson_end;
    }

    function getBgpic() {
        return $this->bgpic;
    }

    function getIs_tag() {
        return $this->is_tag;
    }

    function getVerification_type() {
        return $this->verification_type;
    }

    function getSms_api() {
        return $this->sms_api;
    }

    function getInvoice_api() {
        return $this->invoice_api;
    }

    function getLine_api() {
        return $this->line_api;
    }

    function getService_token() {
        return $this->service_token;
    }

    function setSitename($sitename) {
        $this->sitename = $sitename;
    }

    function setSitecompany($sitecompany) {
        $this->sitecompany = $sitecompany;
    }

    function setSubsitename($subsitename) {
        $this->subsitename = $subsitename;
    }

    function setSiteurl($siteurl) {
        $this->siteurl = $siteurl;
    }

    function setSitetitle($sitetitle) {
        $this->sitetitle = $sitetitle;
    }

    function setMetakeywords($metakeywords) {
        $this->metakeywords = $metakeywords;
    }

    function setMetadescription($metadescription) {
        $this->metadescription = $metadescription;
    }

    function setMetacompany($metacompany) {
        $this->metacompany = $metacompany;
    }

    function setMetacopyright($metacopyright) {
        $this->metacopyright = $metacopyright;
    }

    function setMetaauthor($metaauthor) {
        $this->metaauthor = $metaauthor;
    }

    function setMetaauthoremail($metaauthoremail) {
        $this->metaauthoremail = $metaauthoremail;
    }

    function setMetaauthorurl($metaauthorurl) {
        $this->metaauthorurl = $metaauthorurl;
    }

    function setMetacreationdate($metacreationdate) {
        $this->metacreationdate = $metacreationdate;
    }

    function setMetarating($metarating) {
        $this->metarating = $metarating;
    }

    function setMetarobots($metarobots) {
        $this->metarobots = $metarobots;
    }

    function setMetapragma($metapragma) {
        $this->metapragma = $metapragma;
    }

    function setMetacache_control($metacache_control) {
        $this->metacache_control = $metacache_control;
    }

    function setMetaexpires($metaexpires) {
        $this->metaexpires = $metaexpires;
    }

    function setSysico($sysico) {
        $this->sysico = $sysico;
    }

    function setUseverify($useverify) {
        $this->useverify = $useverify;
    }

    function setIpsource($ipsource) {
        $this->ipsource = $ipsource;
    }

    function setHttpson_front($httpson_front) {
        $this->httpson_front = $httpson_front;
    }

    function setHttpson_end($httpson_end) {
        $this->httpson_end = $httpson_end;
    }

    function setBgpic($bgpic) {
        $this->bgpic = $bgpic;
    }

    function setIs_tag($is_tag) {
        $this->is_tag = $is_tag;
    }

    function setVerification_type($verification_type) {
        $this->verification_type = $verification_type;
    }

    function setSms_api($sms_api) {
        $this->sms_api = $sms_api;
    }

    function setInvoice_api($invoice_api) {
        $this->invoice_api = $invoice_api;
    }

    function setLine_api($line_api) {
        $this->line_api = $line_api;
    }

    function setService_token($service_token) {
        $this->service_token = $service_token;
    }


}
