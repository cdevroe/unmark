<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Singletons extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    // Changelog
    public function changelog()
    {
        $this->view('changelog');
    }

    // Redirect folks awayz
    public function index()
    {
        header('Location: /');
        exit;
    }

    // TOS
    public function terms()
    {
        $this->load->view('terms');
    }

}