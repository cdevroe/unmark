<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Singletons extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfNotInternal();
    }

    // Changelog
    public function changelog()
    {
        $this->view('singletons/changelog');
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
        $this->view('singletons/terms');
    }

}