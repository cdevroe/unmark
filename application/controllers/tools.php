<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends Plain_Controller
{
    // NO API ROUTE

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfNotAJAX();
    }

    public function forgotPassword(){
        $email = $this->db_clean->email; 
        // Check passed email
        $this->load->helper('email');
        if(empty($email) || ! valid_email($email)){
            $this->data['errors'] = formatErrors('Invalid email.', 1);
        }
        // Check if email exists in our DB
        $this->load->model('users_model', 'user');
        $user = $this->user->read('email = \'' . $email . '\'');
        if(empty($user) || empty($user->user_id)){
            $this->data['errors'] = formatErrors('Account does not exist.', 2);
        } else{
            $this->data['token'] = 'TOKEN';
        }
        $this->figureView();
    }

}