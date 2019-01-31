<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Exceptions extends CI_Exceptions
{

    public function __construct()
    {
        parent::__construct();

        log_message('info', 'Plain_Exceptions Class Initialized');
    }

    // Log using CI if using default method
    // Else, call exceptional library
    public function log_exception($severity, $message, $filepath, $line)
    {
        if (!($severity & error_reporting())) return;
        if (CUSTOM_ERROR_TRACKING === false) {
            parent::log_exception($severity, $message, $filepath, $line);
        }
        else {
            $CI =& get_instance();
            $CI->load->library('exceptional');
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