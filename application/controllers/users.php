<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	public function index(){ } // Unused
	
	// Method: login()
	// Used to log a user in
	// Accepts: Nothing
	// Returns: User goes to home page or URL or fails
	public function login() {

		// Load what we need for this method
		$this->load->database();
		$this->load->model('Users_model');

		$this->load->library('session');
		$this->load->helper('url');

		// Turn XSS filter on email address and password
		$emailaddress = $this->input->post('emailaddress', TRUE);
		$password = $this->input->post('password', TRUE);

		// If either are emtpy, exit
		if ($emailaddress == '' || $password == '') {
			exit( 'Can not submit a blank username or password.' );
		}

		// Select user from database
		$user = $this->Users_model->check_user_credentials();

		// If user found (and password matches)
		if ( is_array($user) === true ) {
			
			// If they were adding a mark, save the URL before session is destroyed.
			$addurl = $this->session->flashdata('addurl');

			// Destroy session
			$this->session->sess_destroy();

			// Create new session and add new user information to it
			$this->session->sess_create();
			$this->session->set_userdata(array('userid'=>$user['id'],'emailaddress'=>$user['emailaddress'],'logged_in'=>true,'status'=>$user['status']));
		
		} else { // User not found, or password didn't match

			exit( 'Please supply a valid username or password. Or create an account.' );

		}

		// If adding a new mark redirect to that,
		// Else just redirect home
		if ($addurl) {
			redirect($addurl);
		} else {
			redirect('home');
		}

	}
	
	// Method: logout()
	// Used to log a user out of Nilai
	// Accepts: Noething
	// Returns: Destroys current session, redirects to home page.
	public function logout() {
		$this->load->library('session');
		$this->load->helper('url');
		$this->session->sess_destroy();
		redirect('');
	}
	

	// Method: add()
	// Used to add a user
	// Accepts: Nothing
	// Returns: Redirects to /home/
	public function add() {
		
		// Load everything we need for this method
		$this->load->database();
		$this->load->model('Users_model');
		$this->load->library( 'session' );
		$this->load->helper( array('url','email') );

		// Form input data
		$emailaddress = $this->input->post( 'emailaddress' );
		$password = $this->input->post( 'password' );

		// If emailaddress or password is empty
		// Or if the email address is not valid, skip
		if ( $emailaddress != '' && $password != '' && valid_email($emailaddress) ) {

			// Check to see if email address exists already
			// If email already in use, exit
			$userid = $this->Users_model->create_user();

			if ( !$userid ) {
				$this->session->set_flashdata('message', 'This email address is already in use.');
				redirect('');
				exit;
			}

			// Destroy old CodeIgniter Session
			$this->session->sess_destroy();
			
			// Create brand-new session
			$this->session->sess_create();

			// Log the user in
			$this->session->set_userdata(array('userid'=>$userid,'emailaddress'=>$emailaddress,'logged_in'=>true,'status'=>'paid'));

			// Congratulate them
			$this->session->set_flashdata('message', 'Congratulations. Enjoy using Nilai.');
			
			// ### Add some "Starter Links"

			// Mark: Read the FAQ
			// Label: "do"
			$this->db->insert('marks',array('title'=>'Read Nilai\'s FAQ','url'=>'http://nilai.co/help/faq'));
			$urlid = $this->db->insert_id();
			$this->db->insert('users_marks',array('urlid'=>$urlid,'userid'=>$this->session->userdata('userid'),'addedby'=>$this->session->userdata('userid'),'tags'=>'do'));

			// Mark: How to use Nilai
			// Label: "read"
			$this->db->insert('marks',array('title'=>'How to use Nilai','url'=>'http://nilai.co/help/how'));
			$urlid = $this->db->insert_id();
			$this->db->insert('users_marks',array('urlid'=>$urlid,'userid'=>$this->session->userdata('userid'),'addedby'=>$this->session->userdata('userid'),'tags'=>'read'));

			// All set, take them to their stream
			redirect('home');
		
		} else {

			exit( 'Please enter a valid email address and a password.' );

		} // end if emailaddress/password

	} // end add()
	
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */