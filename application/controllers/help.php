<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfLoggedOut();
    }

}
