<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Flourish Autoload Class
 *
 * Autoload Flourish classes by spl_autoload_register
 *
 * @package        CodeIgniter
 * @subpackage    Libraries
 * @category    Libraries
 * @author        fineen.chen@gmail.com
 */
class Flourish {

    /**
     * Constructor - register autoload function
     */
    public function __construct() {
        spl_autoload_register('Flourish::load');
    }

    // --------------------------------------------------------------------

    /**
     * Autoload function
     *
     * @access    public
     * @param    string    $class
     * @return    boolean
     */
    static public function load($class) {
        if (strpos($class, 'f') !== 0) {
            return FALSE;
        }

        $flourish_root = APPPATH . 'libraries/flourish/';
        $file = $flourish_root . $class . '.php';
        $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
        if (is_file($file)) {
            require $file;
            return TRUE;
        }

        return FALSE;
    }

}

/* End of file Flourish.php */
/* Location: ./application/libraries/Flourish.php */