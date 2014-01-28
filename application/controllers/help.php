<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfLoggedOut();
    }

    public function index()
    {
        header('Location: /help/how');
        exit;
    }

    public function bookmarklet()
    {
        $this->view('help/bookmarklet');
    }

    public function faq()
    {
        $this->view('help/faq');
    }

    public function how()
    {
        $this->view('help/how');
    }

}