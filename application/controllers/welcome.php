<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends Plain_Controller {


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

		$this->view('home');

		/*
		- Not sure what this is about so leaving for now
		if ($this->session->flashdata('addurl')) {
			$this->session->keep_flashdata('addurl');
		}
		*/
	}

	public function sirius()
	{
		$this->redirectIfLoggedIn('/home');
		$this->view('signup');
	}

	public function helpbookmarklet()
	{
		$this->load->helper(array('url','form'));
		$this->load->library('session');
		$data['when'] = '';
		$data['label'] = '';
		if ($this->session->userdata('status') == 'active') {
			$this->load->view('help_bookmarklet',$data);
		} else {
			redirect('');
		}
	}

	public function faq()
	{
		$this->load->helper(array('url','form'));
		$this->load->library('session');

		$data['when'] = '';
		$data['label'] = '';

		if ($this->session->userdata('status') == 'active') {
			$this->load->view('help_faq',$data);
		} else {
			redirect('');
		}
	}

	public function how()
	{
		$this->load->helper(array('url','form'));
		$this->load->library('session');

		$data['when'] = '';
		$data['label'] = '';

		if ($this->session->userdata('status') == 'active') {
			$this->load->view('help_how',$data);
		} else {
			redirect('');
		}
	}

	public function changelog()
	{
		$this->load->helper(array('url','form'));
		$this->load->library('session');

		if ($this->session->userdata('status') == 'active') {
			$data['label'] = '';
			$data['group']['groupuid'] = '';
			$data['when'] = '';

			$this->load->view('changelog',$data);
		} else {
			redirect('');
		}
	}

	public function terms()
	{
		$this->load->helper(array('url','form'));
		$this->load->library('session');
		$data = array();
		$this->load->view('terms', $data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */