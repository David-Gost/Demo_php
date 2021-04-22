<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPLICATION_BACKEND_DTO_PATH . 'Base_dto.php';

class Store_dto extends Base_dto
{
    public $name=NULL;

    function getName() {
     return $this->name; 
    }
    
    function setName($name) {
     $this->name = $name; 
    }
}
