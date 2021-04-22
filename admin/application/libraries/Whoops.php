<?php

require_once VENDOR_PATH . 'autoload.php';

class Whoops {

    public $whoops;

    function __construct() {

        $CI = &get_instance();

        $whoops = new Whoops\Run();
        // Configure the PrettyPageHandler:
        $errorPage = new Whoops\Handler\PrettyPageHandler();

        $errorPage->setPageTitle("It's broken!"); // Set the page's title
        $errorPage->setEditor("sublime");         // Set the editor used for the "Open" link
        $errorPage->addDataTable("Extra Info", array(
            "stuff" => 123,
            "foo" => "bar",
            "route" => $CI->uri->uri_string()
        ));

        $whoops->pushHandler($errorPage);

        if ($CI->input->is_ajax_request() == true) {
            $whoops->pushHandler(new Whoops\Handler\JsonResponseHandler());
        }


        $whoops->register();
        $this->whoops = $whoops;
    }

}
