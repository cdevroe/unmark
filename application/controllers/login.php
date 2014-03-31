<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Plain_Controller {

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfLoggedIn();
        parent::redirectIfNotInternalAJAX();
    }

    public function index()
    {
        $this->redirectIfInvalidCSRF();

        $this->data['success'] = false;

        // Find user
        $this->load->model('users_model', 'user');
        $user = $this->user->read("email = '" . $this->db_clean->email . "'", 1, 1);

        if (! isset($user->user_id)) {
            $this->data['message'] = sprintf(_('The email address `%s` was not found.'), $this->clean->email);
        }
        elseif (! isset($user->active) || empty($user->active)) {
            $this->data['message'] = _('Your account is no longer active. Please contact support.');
        }
        else {
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
                
                $this->data['message'] = _('Your password is incorrect. Please try again.');
            }
            else {
                // At this point we are clear for takeoff
                // Regenerate session
                // Set session variables and send user on their way
                $add_redirect = $this->session->userdata('add_redirect');
                $redirect     = (empty($add_redirect)) ? '/marks' : $add_redirect;

                $this->session->unset_userdata('add_redirect');
                $user->email = $this->clean->email;
                $this->session->sess_update(true);
                $this->sessionAddUser($user);
                $this->data['success'] = true;
                $this->data['redirect_url'] = $redirect;
            }
        }

        $this->renderJSON();
    }
}