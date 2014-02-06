<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    // Not logged in for api or ajax
    public function authError()
    {
        $this->data['errors'] = formatErrors(403);
        $this->renderJSON();
    }

    // Check if user is logged in and using chrome extension
    public function chromePing()
    {
        if ($this->logged_in !== true || parent::isChromeExtension() === false) {
            $this->data['errors'] = formatErrors(403);
        }
        else {
            $this->data['success'] = true;
        }
        $this->renderJSON();
    }

    // Catch all
    public function index()
    {
        $this->data['errors'] = formatErrors(404);
        $this->renderJSON();
    }

}