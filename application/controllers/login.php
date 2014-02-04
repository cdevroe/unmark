<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Plain_Controller {

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfLoggedIn();
        parent::redirectIfNotInternal();
    }

    public function index()
    {
        $this->redirectIfInvalidCSRF();

        // Get redirect page for error
        $redirect = (isset($this->clean->redirect) && ! empty($this->clean->redirect)) ? $this->clean->redirect : '/';

        // Find user
        $this->load->model('users_model', 'user');
        $user = $this->user->read("email = '" . $this->db_clean->email . "'", 1, 1, 'user_id, password, user_token, active, admin');

        if (! isset($user->user_id)) {
            $this->setFlashMessage('The email address `' . $this->clean->email . '` was not found.');
            header('Location: ' . $redirect);
            exit;
        }

        // Check if active
        if (! isset($user->active) || empty($user->active)) {
            $this->setFlashMessage('Your account is no longer active. Please contact support.');
            header('Location: ' . $redirect);
            exit;
        }

        // Check proper password
        if (strlen($user->password) == 32) {
            $match = (md5($this->clean->password) == $user->password) ? true : false;

            // Try to update to new password security since they are on old MD5
            $hash  = generateHash($this->clean->password);

            // If hash is valid and match is valid
            // Upgrade users to new encryption routine
            if ($hash !== false && $match === true) {
                $res = $this->user->update("user_id = '" . $user->user_id . "'", array('password' => $hash));
            }
        }
        else {
            $match = (verifyHash($this->clean->password, $user->password) == $user->password) ? true : false;
        }

        // Check if passwords match
        if ($match === false) {
            $this->setFlashMessage('Your password is incorrect. Please try again.');
            header('Location: ' . $redirect);
            exit;
        }

        // At this point we are clear for takeoff
        // Regenerate session
        // Set session variables and send user on their way
        $user->email = $this->clean->email;
        $this->session->sess_update(true);
        $this->sessionAddUser($user);
        header('Location: /marks');
        exit;
    }
}