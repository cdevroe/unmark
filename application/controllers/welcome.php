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
		$view = (! $this->db->table_exists('users')) ? 'setup' : 'welcome';
		$this->view($view, array('no_header' => true, 'no_footer' => true));
	}

}