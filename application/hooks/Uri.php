<?php

//處理uri，使大小寫皆可訪問
class Uri {

    function strtolower() {
        $_SERVER['REQUEST_URI'] = strtolower($_SERVER['REQUEST_URI']);
    }

}
