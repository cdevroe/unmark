<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Loader extends CI_Loader
{
    public function __construct()
    {
        parent::__construct();

        // Add custom path information for libraries, helpers, models and views
        log_message('debug', 'Plain_Loader Class Initialized');
        array_unshift($this->_ci_library_paths, CUSTOMPATH);
        array_unshift($this->_ci_helper_paths, CUSTOMPATH);
        array_unshift($this->_ci_model_paths, CUSTOMPATH);
        $this->_ci_view_paths = array(
            CUSTOMPATH . 'views/' => true,
            APPPATH . 'views/'     => true
        );

        print '<pre>';
        print_r($this->_ci_library_paths);
        print_r($this->_ci_helper_paths);
        print_r($this->_ci_model_paths);
        print_r($this->_ci_view_paths);
        print '</pre>';
    }
}