<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }


    function create_user()
    {

    	// Form input data
		$email    = $this->input->post('emailaddress');
		$password = $this->input->post('password');

    	// Check to see if email address exists already
		// If email already in use, exit
		$user = $this->Users_model->get_user_by_email($email);
		if ( is_array($user) ) {
			return false;
		}

		// Add user to users table
        $this->load->helper('hash_helper');
        $hash = generateHash($password);

        if ($hash === false) {
            return false;
        }

		$this->db->insert('users', array(
            'email'    => $email,
            'password' => $hash['encrypted'],
            'salt'     => $hash['salt'],
            'status'   => 'active'
        ));

		// Get userid of this user
        return $this->db->insert_id();
    }


    // As yet unused, but could be used to update email address, password, status
    function update_user()
    {
        // Form input data
        $user_id   = $this->input->post('userid');
        $email     = $this->input->post('emailaddress');
        $hash      = generateHash($this->input->post('password'));
        $status    = $this->input->post('status');

        if ($hash !== false) {

            // Add user to users table
            $this->db->update('users',
                array(
                    'email'    => $emailaddress,
                    'password' => $hash['encrypted'],
                    'salt'     => $hash['salt'],
                    'status'   => $status
                ),
                array(
                    'user_id' => $user_id
                )
            );
        }
    }

    // DANGER!
    // Unused yet, however
    function remove_user($user_id='')
    {
        $userid = (empty($user_id) || ! is_numeric($user_id)) ? $this->session->userdata('userid') : $user_id;
        $this->db->delete('users', array('user_id'=>$user_id));
    }

    function get_user_by_id($user_id='')
    {
    	if (empty($user_id)) {
            return false;
        }

    	$user = $this->db->get_where('users', array( 'user_id' => $user_id ) );
        return ($user->num_rows() > 0) ? $user->row_array() : false;
    }

    function get_user_by_email($email='')
    {
    	if (empty($email)) {
            return false;
        }

    	$user = $this->db->get_where('users', array( 'email' => $email));
        return ($user->num_rows() > 0) ? $user->row_array() : false;
    }

    function get_all_users($status='active')
    {
        $users = $this->db->get_where('users', array('status' => $status));
        return ($user->num_rows() > 0) ? $user->row_array() : false;
    }

    function check_user_credentials()
    {

    	// Turn XSS filter on email address and password
        $this->load->helper('hash_helper');
		$email     = $this->input->post('emailaddress', true);
		$password  = $this->input->post('password', true);


        // Get user by email address
        $user = $this->db->query("SELECT * FROM `users` WHERE email = '" . $email . "' LIMIT 1");

        if ($user->num_rows() < 1) {
            return false;
        }

        // Check passwords
        $row                = $user->row();
        $encrypted_password = $row->password;
        $salt               = $row->salt;

        // If salt exists, check it
        // Else check old MD5 checksum
        if (! empty($salt)) {
            $hash  = generateHash($password, $salt);
            $match = (isset($hash['encrypted']) && $encrypted_password == $hash['encrypted']) ? true : false;
        }
        else {
            $match = (md5($password) == $encrypted_password) ? true : false;
        }

        // If a match, return array, else false
        return ($match === true) ? $user->row_array() : false;

    }


}