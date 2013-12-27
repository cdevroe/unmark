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
		$emailaddress = $this->input->post( 'emailaddress' );
		$password = $this->input->post( 'password' );

    	// Check to see if email address exists already
		// If email already in use, exit
		$user = $this->Users_model->get_user_by_emailaddress($emailaddress);
		if ( is_array($user) ) {
			return false;
		}

		// Add user to users table
		$this->db->insert('users',array('emailaddress'=>$emailaddress,'password'=>md5($password),'status'=>'active'));
			
		// Get userid of this user
		// I wish this was a single line of code, halp?
		// I used to use $userid = $this->db->insert_id() but that seemed like it wouldn't scale to me
		$user = $this->db->get_where( 'users', array( 'emailaddress' => $emailaddress ) );
		$user = $user->result_array();
		
		return $user[0]['id'];
    }


    // As yet unused, but could be used to update email address, password, status
    function update_user()
    {
        // Form input data
        $userid = $this->input->post( 'userid' );
        $emailaddress = $this->input->post( 'emailaddress' );
        $password = $this->input->post( 'password' );
        $status = $this->input->post( 'status' );

        // Add user to users table
        $this->db->update('users',array('emailaddress'=>$emailaddress,'password'=>md5($password),'status'=>$status), array('id'=>$userid));
    }

    // DANGER!
    // Unused yet, however
    function remove_user($userid='')
    {
        if ( !$userid || $userid == '' ) $userid = $this->session->userdata('userid');

        $this->db->delete('users', array('id'=>$userid));   
    }

    function get_user_by_id($id='')
    {
    	if ( !$id || $id == '' ) return false;

    	$user = $this->db->get_where( 'users', array( 'id' => $id ) );

    	if ( $user->num_rows() > 0 ) {
    		return $user->row_array();
    	}

    	return false;
    }

    function get_user_by_emailaddress($emailaddress='')
    {
    	if ( !$emailaddress || $emailaddress == '' ) return false;

    	$user = $this->db->get_where( 'users', array( 'emailaddress' => $emailaddress ) );

    	if ( $user->num_rows() > 0 ) {
    		return $user->row_array();
    	}

    	return false;
    }

    function get_all_users($status='active')
    {
        $users = $this->db->get_where('users', array('status' => $status));
        if ( $users->num_rows() > 0 ) {
            return $users->result_array();
        }

        return false;
    }

    function check_user_credentials()
    {

    	// Turn XSS filter on email address and password
		$emailaddress = $this->input->post('emailaddress', TRUE);
		$password = $this->input->post('password', TRUE);

    	// Select user from database
		$user = $this->db->get_where('users', array('emailaddress' => $emailaddress, 'password' => md5($password)));

		if ($user->num_rows() > 0) {
			return $user->row_array();
		}

		return false;
    }

    
}