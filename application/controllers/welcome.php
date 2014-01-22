<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->helper(array('url','form'));
        $this->load->library('plain_session', '', 'session');

		if ($this->session->flashdata('addurl')) {
			$this->session->keep_flashdata('addurl');
		}
		if ($this->session->userdata('status') == 'active') {
			redirect('home');
		} else {
			// See if there is a users table
    		// Also, be sure there _are_ users.
    		$this->load->database();
    		if ( !$this->db->table_exists('users') ) { // No user table
    			print_r('<p>Nilai has not been installed yet. Please <a href="/install/">install</a> before continuing.</p>');
    			exit;
    		}

		  $this->load->view('home');
		}
	}

	public function sirius()
	{
		$this->load->helper(array('url','form'));
		$this->load->library('plain_session', '', 'session');

		if ($this->session->userdata('status') == 'active') {
			redirect('home');
		}

		$data = array();

		$this->load->view('signup',$data);
	}

	public function helpbookmarklet()
	{
		$this->load->helper(array('url','form'));
		$this->load->library('plain_session', '', 'session');
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
		$this->load->library('plain_session', '', 'session');

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
		$this->load->library('plain_session', '', 'session');

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
		$this->load->library('plain_session', '', 'session');

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
		$this->load->library('plain_session', '', 'session');
		$data = array();
		$this->load->view('terms', $data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */