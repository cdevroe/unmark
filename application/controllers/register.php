<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends Plain_Controller
{
	// NO API ROUTE

	public function index()
	{
		$this->view('register');
	}

	public function user()
	{

		$email    = (isset($this->db_clean->email)) ? $this->db_clean->email : null;
		$password = (isset($this->clean->password)) ? $this->clean->password : null;

		$this->load->model('users_model', 'user');
		$user = $this->user->create(array('email' => $email, 'password' => $password, 'active' => '1'));

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
			$redirect = '/marks';
		}
		// If failure, get messages
		// Set to flash message
		// set redirect to root
		else {
			$redirect = '/register';
			$this->setFlashMessage('<p>' . implode('</p><p>', $user) , '</p>');
		}

		// Redirect
		header('Location: ' . $redirect);
		exit;
	}

}