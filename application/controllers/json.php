<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    // Catch all
    public function index()
    {
        $this->data['errors'] = formatErrors(404);
        $this->renderJSON();
    }

    // Not logged in for api or ajax
    public function authError()
    {
        $this->data['errors'] = formatErrors(403);
        $this->renderJSON();
    }

}