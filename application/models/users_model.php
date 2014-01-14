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
        $password = generateHash($password);

        if ($password === false) {
            return false;
        }

		$this->db->insert('users', array(
            'email'    => $email,
            'password' => $password,
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
        $password  = generateHash($this->input->post('password'));
        $status    = $this->input->post('status');

        if ($password !== false) {

            // Add user to users table
            $this->db->update('users',
                array(
                    'email'    => $emailaddress,
                    'password' => $password,
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
        $hash      = generateHash($password);

        if ($hash === false) {
            return false;
        }

    	// Select user from database
        // Have to look for both hash types
        // so we can be backwards compatible with older versions
        $user = $this->db->query("
            SELECT * FROM `users`
            WHERE email = '" . $email . "' AND
            (password = '" . md5($password) . "' OR password = '" . $hash . "')
        ");

        return ($user->num_rows() > 0) ? $user->row_array() : false;
    }


}