<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Plain_Controller
{
	// NO API ROUTE

	// No base /users url in use
	// redirect to homepage
	public function index()
	{
		header('Location: /');
		exit;
	}

	public function add()
	{

		$email    = (isset($this->db_clean->email)) ? $this->db_clean->email : null;
		$password = (isset($this->clean->password)) ? $this->clean->password : null;

		$this->load->model('users_model', 'user');
		$user = $this->user->create(array('email' => $email, 'password' => $password));

		// If good
		// Add user data to session
		// Set redirect to /marks
		// Set default marks (can't really do this)
		if (isset($user->user_id)) {
			$this->sessionAddUser($user);
			$redirect = '/marks';

			// Start links
			// Can't add these, will break the open source release
			// We won't know the links to add
			// We will have to -> either define in config or redirect success to like a getting started page

		}
		// If failure, get messages
		// Set to flash message
		// set redirect to root
		else {
			$redirect = '/';
			$this->setFlashMessage('<p>' . implode('</p><p>', $user) , '</p>');
		}

		// Redirect
		header('Location: ' . $redirect);
		exit;
	}

}