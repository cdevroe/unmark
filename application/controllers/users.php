<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Plain_Controller
{

	// No base /users url in use
	// redirect to homepage
	public function index()
	{
		header('Location: /');
		exit;
	}



	public function add() {

		// Load everything we need for this method

		$this->load->model('Users_model');
		$this->load->library( 'session' );
		$this->load->helper( array('url','email', 'validation_helper') );

		// Form input data
		$emailaddress = $this->input->post( 'emailaddress' );
		$password = $this->input->post( 'password' );

		// If emailaddress or password is empty
		// Or if the email address is not valid, skip
		if (valid_email($emailaddress) && isValid($password, 'password')) {

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
			$this->session->set_userdata(array('userid'=>$userid,'emailaddress'=>$emailaddress,'logged_in'=>true,'status'=>'active'));

			// Congratulate them
			$this->session->set_flashdata('message', 'Congratulations. Enjoy using Nilai.');

			// ### Add some "Starter Links"

			// Mark: Read the FAQ
			// Label: "do"
			$this->db->insert('marks',array('title'=>'Read Nilai\'s FAQ','url'=>$this->config->item('base_url').'help/faq'));
			$urlid = $this->db->insert_id();
			$this->db->insert('users_marks',array('urlid'=>$urlid,'userid'=>$_SESSION['user']['user_id'],'addedby'=>$_SESSION['user']['user_id'],'tags'=>'do'));

			// Mark: How to use Nilai
			// Label: "read"
			$this->db->insert('marks',array('title'=>'How to use Nilai','url'=>$this->config->item('base_url').'help/how'));
			$urlid = $this->db->insert_id();
			$this->db->insert('users_marks',array('urlid'=>$urlid,'userid'=>$_SESSION['user']['user_id'],'addedby'=>$_SESSION['user']['user_id'],'tags'=>'read'));

			// All set, take them to their stream
			redirect('home');

		} else {

			exit( 'Please enter a valid email address and a password.' );

		} // end if emailaddress/password

	} // end add()

}

/* End of file users.php */
/* Location: ./application/controllers/users.php */