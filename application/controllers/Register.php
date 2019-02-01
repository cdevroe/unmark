<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends Plain_Controller
{
	public function __construct()
    {
        parent::__construct();
        parent::redirectIfNotInternal();
        parent::redirectIfLoggedIn();
        if ($this->config->item('plain_enable_registrations')===false) {
        	show_error('Public registrations have been disabled.');
        }
    }

	public function index()
	{
		$this->data['no_header'] = true;
        $this->data['no_footer'] = true;
		$this->view('register/index');
	}

	public function user()
	{

		$email    = (isset($this->db_clean->email)) ? $this->db_clean->email : null;
		$password = (isset($this->clean->password)) ? $this->clean->password : null;

		$this->data['success'] = false;
		$this->load->model('users_model', 'user');
		$user = $this->user->create(array('email' => $email, 'password' => $password, 'active' => '1'));
		// print_r($user);
		// exit;
		// If good
		// Add user data to session
		// Set user id
		// Add defualt marks
		// Set redirect to /marks
		// Set default marks (can't really do this)
		if (isset($user->user_id)) {
			$this->sessionAddUser($user);

			// Set user id
			$this->user_id = $user->user_id;

			// Now add default marks to user account
			$default_marks = $this->config->item('new_account_links');
			if (! empty($default_marks)) {
				foreach ($default_marks as $title => $arr) {
					$title    = $this->db->escape_str($title);
					$url      = $this->db->escape_str($arr['url']);
					$label_id = $this->db->escape_str($arr['label_id']);
					$res      = parent::addMark(array('url' => $url, 'title' => $title, 'label_id' => $label_id));
				}
			}

			// set redirect path
			$redirect              = '/marks';
			$this->data['success'] = true;
			$this->data['email']   = $email;

		}
		// If failure, get messages
		// Set to flash message
		// set redirect to root
		else {
			$redirect = '/register';
			$this->setFlashMessage($user);
			foreach ($user as $error_code => $message) {
				$this->data['message'] = $message;
			}
		}

		// Redirect for web view or print for ajax call
		$this->figureView(null, $redirect);
	}

}