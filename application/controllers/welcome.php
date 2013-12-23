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
	  $this->load->library('session');
	  
	  if ($this->session->flashdata('addurl')) { 
	   $this->session->keep_flashdata('addurl');
	  }
	  
	  if ($this->session->userdata('status') == 'paid') {
		  redirect('home');
		} else {
		  $this->load->view('home');
		}
	}
	
	public function sirius() {
	  $this->load->helper(array('url','form'));
	  $this->load->library('session');
	  
	  if ($this->session->userdata('status') == 'paid') {
		  redirect('home');
		} else {
		  $this->load->database();
		  // Determine how many people signed up today.
		  // Unix timestamps for yesterday and today
		  $today = mktime(0, 0, 0, date('n'), date('j'));
 		  $tomorrow = mktime(0, 0, 0, date('n'), date('j') + 1);
		  
		  // Only allow 5 people to sign up per day.
		  $daily = 100;
		  
		  $signupsToday = $this->db->query("SELECT * FROM users WHERE UNIX_TIMESTAMP(datejoined) > ".$today." AND UNIX_TIMESTAMP(datejoined) < ".$tomorrow);
		  
		  $signupsLeft = ($daily-$signupsToday->num_rows());
		  
		  if ($signupsLeft < 0) { $signupsLeft = 0; }
		
	    $data['daily'] = $daily;
	    $data['signupsLeft'] = $signupsLeft;
		  $this->load->view('signup',$data);
		}
	}
	
  public function helpbookmarklet() {
    $this->load->helper(array('url','form'));
	  $this->load->library('session');
	  $data['when'] = '';
	  $data['label'] = '';
    if ($this->session->userdata('status') == 'paid') {
		  $this->load->view('help_bookmarklet',$data);
		} else {
      redirect('');
    }
  }
  
  public function faq() {
    $this->load->helper(array('url','form'));
	  $this->load->library('session');
	  
	  $data['when'] = '';
	  $data['label'] = '';
	  
    if ($this->session->userdata('status') == 'paid') {
		  $this->load->view('help_faq',$data);
		} else {
      redirect('');
    }
  }
  
  public function how() {
    $this->load->helper(array('url','form'));
	  $this->load->library('session');
	  
	  $data['when'] = '';
	  $data['label'] = '';
	  
    if ($this->session->userdata('status') == 'paid') {
		  $this->load->view('help_how',$data);
		} else {
      redirect('');
    }
  }
  
  public function changelog() {
    $this->load->helper(array('url','form'));
	  $this->load->library('session');
    if ($this->session->userdata('status') == 'paid') {
      $data['label'] = '';
      $data['group']['groupuid'] = '';
      $data['when'] = '';
		  $this->load->view('changelog',$data);
		} else {
      redirect('');
    }
  }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */