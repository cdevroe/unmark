<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Exceptions extends CI_Exceptions
{

    private $use_default = true;

    public function __construct()
    {
        parent::__construct();
        $custom_exists     = file_exists(CUSTOMPATH . 'libraries/Error_Tracking.php');
        $this->use_default = ($custom_exists !== true || (defined('ENVIRONMENT') && ENVIRONMENT == 'development')) ? true : false;
    }

    // Log using CI if using default method
    // Else, call exceptional library
    public function log_exception($severity, $message, $filepath, $line)
    {
        if ($this->use_default === true) {
            parent::log_exception($severity, $message, $filepath, $line);
        }
        else {
            $CI =& get_instance();
            $CI->exceptional->createTrace($severity, $message, $filepath, $line);
        }
    }

    // Only show php errors on development
    public function show_php_error($severity, $message, $filepath, $line)
    {
        self::log_exception($severity, $message, $filepath, $line);
        parent::show_php_error($severity, $message, $filepath, $line);
    }

}