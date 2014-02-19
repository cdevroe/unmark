<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends Plain_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::redirectIfNotInternal();
	}

	public function index()
	{
		$this->redirectIfLoggedIn('/marks');

		// No user table
		// Needs to be installed
		// This should be updated to be cleaner
		if (! $this->db->table_exists('users')) {
			print_r('<p>Unmark has not been installed yet. Please <a href="/install/">install</a> before continuing.</p>');
			exit;
		}

		$data['no_header'] = true;
		$data['no_footer'] = true;

		$this->view('welcome', $data);

	}

}