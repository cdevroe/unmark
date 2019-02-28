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
        $data['no_header'] = true;
        $data['no_footer'] = true;
        $this->view('singletons/changelog', $data);
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

    // Reset password View
    public function reset($token=null)
    {
        $this->data['token'] = $token;
        $this->data['no_header'] = true;
        $this->data['no_footer'] = true;
        $this->view('singletons/reset');
    }

}