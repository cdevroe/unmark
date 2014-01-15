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
            'email'       => $email,
            'password'    => $hash,
            'status'      => 'active',
            'date_joined' => date('Y-m-d H:i:s')
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
                    'password' => $hash,
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

        if (strlen($encrypted_password) == 32) {
            $match = (md5($password) == $encrypted_password) ? true : false;

            // Try to update to new password security since they are on old MD5
            $hash  = generateHash($password);

            // If hash is valid and match is valid
            // Upgrade users to new encryption routine
            if ($hash !== false && $match === true) {
                $this->db->update('users', array('password' => $hash), array('email' => $email));
            }
        }
        else {
            $match = (verifyHash($password, $encrypted_password) == $encrypted_password) ? true : false;
        }

        // If a match, return array, else false
        return ($match === true) ? $user->row_array() : false;

    }


}