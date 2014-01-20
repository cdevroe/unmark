<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Labels extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirectIfLoggedOut();
    }

}