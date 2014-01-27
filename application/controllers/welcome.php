<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends Plain_Controller
{


	public function index()
	{
		$this->redirectIfLoggedIn('/marks');

		// No user table
		// Needs to be installed
		// This should be updated to be cleaner
		if (! $this->db->table_exists('users')) {
			print_r('<p>Nilai has not been installed yet. Please <a href="/install/">install</a> before continuing.</p>');
			exit;
		}

		//$this->view('home');
		$this->figureView('home');

	}

}