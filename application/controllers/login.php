<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Plain_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->redirectIfLoggedIn();
    }

    public function index()
    {

    }

    public function process()
    {

        //$this->redirectIfInvalidCSRF();
        $this->load->model('accounts_model', 'account');
        if (isset)
        $email = $this->db_clean->email;

        // Turn XSS filter on email address and password
        $this->load->helper('hash_helper');
        $email     = $this->input->post('emailaddress', true);
        $password  = $this->input->post('password', true);


        // Get user by email address
        $user = $this->db->query("SELECT * FROM `users` WHERE email = '" . $this-> . "' LIMIT 1");

        if ($user->num_rows() < 1) {
            return false;
        }

        // Check passwords
        $row                = $user->row();
        $encrypted_password = $row->password;

        if (strlen($encrypted_password) == 32) {
            $match = (md5($password) == $encrypted_password) ? true : false;

            // Try to update to new password security since they are on old MD5
            $hash  = generateHash($password);

            // If hash is valid and match is valid
            // Upgrade users to new encryption routine
            if ($hash !== false && $match === true) {
                $this->db->update('users', array('password' => $hash), array('email' => $email));
            }
        }
        else {
            $match = (verifyHash($password, $encrypted_password) == $encrypted_password) ? true : false;
        }

        // If a match, return array, else false
        return ($match === true) ? $user->row_array() : false;
    }
}