<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends Plain_Controller
{
    // NO API ROUTE

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfNotAJAX();
    }

    /**
     * Generate password recovery token
     * AJAX action
     */
    public function forgotPassword(){
        $email = isset($this->db_clean->email) ? $this->db_clean->email : null; 
        // Check passed email
        $this->load->helper('email');
        if(empty($email) || ! valid_email($email)){
            $this->data['errors'] = formatErrors('Invalid email.', 1);
        }
        // Check if email exists in our DB
        $this->load->model('users_model', 'user');
        $user = $this->user->read('email = \'' . $email . '\' AND active = \'1\'');
        // Email does not exist or user is not active
        if(empty($user) || empty($user->user_id)){
            $this->data['errors'] = formatErrors('Account does not exist.', 2);
        // Valid email - generate token
        } else{
            $this->load->model('tokens_model','token');
            $createdToken = $this->token->create(array('token_type'=>Tokens_model::TYPE_FORGOT_PASSWORD, 'user_id'=>$user->user_id));
            $this->data['token'] = array('token_value' => $createdToken->token_value, 'valid_until' => $createdToken->valid_until);
            // Invalidate all other tokens for this type and user
            $this->token->update("token_value != '{$createdToken->token_value}' and token_type = '{$createdToken->token_type}' and active='1' and user_id='{$createdToken->user_id}'", array('active'=>0));
        }
        $this->figureView();
    }
    
    /**
     * Verify if given token is valid
     * AJAX action 
     */
    public function verifyToken(){
        $this->data['token_valid'] = false;
        $token = isset($this->db_clean->token) ? $this->db_clean->token : null;
        // Check if token exists and is valid
        if(empty($token)){
            $this->data['errors'] = formatErrors('No token passed', 3);
        } else{
            $this->load->model('tokens_model','token');
            if($this->token->isValid($token)){
                $this->data['token_valid'] = true;
            }
        }
        $this->figureView();
    }
    
    public function resetPassword(){
        $this->data['success'] = false;
        $token = isset($this->db_clean->token) ? $this->db_clean->token : null;
    }

}