<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Notice_dto extends Base_dto {

    public $identity;
    public $cname;
    public $content;

    function getIdentity() {
        return $this->identity;
    }

    function getCname() {
        return $this->cname;
    }

    function getContent() {
        return $this->content;
    }

    function setIdentity($identity) {
        $this->identity = $identity;
    }

    function setCname($cname) {
        $this->cname = $cname;
    }

    function setContent($content) {
        $this->content = $content;
    }

}
