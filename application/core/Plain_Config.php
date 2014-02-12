<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Config extends CI_Config
{

    var $_config_paths = array(CUSTOMPATH, APPPATH);

    public function __construct()
    {
        parent::__construct();
        log_message('debug', 'Plain_Config Class Initialized');
    }

}