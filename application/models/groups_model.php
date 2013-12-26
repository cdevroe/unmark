<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups_model extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    /* ## CRUD */
    function group_create()
    {

    }

    function get_all_groups()
    {

    }

    function get_groups_by_user()
    {	
		$user_belongs_to_groups = $this->db->query('SELECT * FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid='.$this->session->userdata('userid'));
		
		if ($user_belongs_to_groups->num_rows() > 0) {
			return $user_belongs_to_groups->result_array();
		} else {
			return false;
		}
    }

    function get_group_members()
    {

    }

    function get_group_members_count()
    {

    }

    function group_update()
    {

    }

    function group_delete()
    {

    }

    /* Invites */

    function group_create_invite()
    {

    }

    function group_accept_invite()
    {

    }

    function group_delete_invite()
    {

    }


}