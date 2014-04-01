<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfLoggedOut();
        parent::redirectIfNotInternalAJAX();
        parent::redirectIfInvalidCSRF();

        // If we can't find a user id, get them out of here
        if (! isset($this->user_id) || ! is_numeric($this->user_id)) {
            header('Location: /');
            exit;
        }

        // Set default success to false
        $this->data['success'] = false;

        // Load user model
        $this->load->model('users_model', 'user');
    }

    // Redirect any invalid traffic to homepage
    public function index()
    {
        $this->data['errors'] = formatErrors(404);
        $this->renderJSON();
    }

    // Update a user's email address
    public function updateEmail()
    {
        if (! isset($this->db_clean->email) || ! isValid($this->db_clean->email, 'email')) {
            $this->data['message'] = reset(array_values(formatErrors(604)));
        }
        else {
            // Check if email already exists
            $total = $this->user->count("email = '" . $this->db_clean->email . "'");

            if ($total > 0) {
                $this->data['message'] = reset(array_values(formatErrors(603)));
            }
            else {
                $user = $this->user->update($this->user_id, array('email' => $this->db_clean->email));
                if (isset($user->email) && $user->email == $this->clean->email) {
                    $this->data['success'] = true;
                    $this->sessionAddUser($user);
                }
                else {
                    $this->data['message'] = 'Your email address could not be updated at this time. Please try again.';
                }
            }
        }

        $this->renderJSON();
    }

    // Update a user's password
    public function updatePassword()
    {
        if (! isset($this->clean->password) || ! isValid($this->clean->password, 'password')) {
            $this->data['message'] = reset(array_values(formatErrors(602)));
        }
        else {

            // Check current password
            $current_password = (isset($this->clean->current_password)) ? $this->clean->current_password : null;
            $res              = $this->user->read($this->user_id, 1, 1, 'email,password');

            if (! isset($res->password)) {
                $this->data['message'] = 'We could not verify your current password.';
            }
            elseif (verifyHash($current_password, $res->password) != $res->password) {
                $this->data['message'] = 'Your current password does not match what we have on record.';
            }
            else {
                $password = generateHash($this->clean->password);
                $user = $this->user->update($this->user_id, array('password' => $password));
                if (isset($user->password) && $user->password == $password) {
                    $this->data['success'] = true;
                    // Send email
                    $this->load->library('email');
                    $this->email->initialize();
                    $sent = $this->email->updatePassword($user->email);
                }
                else {
                    $this->data['message'] = 'Your password could not be updated at this time. Please try again.';
                }
            }
        }

        $this->renderJSON();
    }
}