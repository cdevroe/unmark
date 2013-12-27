<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups_model extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    /* ## CRUD */
    function create_group()
    {
        $name = $this->input->post('name',TRUE);
        $description = $this->input->post('description',TRUE);
        $uid = $this->input->post('uid');

        // Add group to database
        $this->db->insert('groups',array('name'=>$name,'description'=>$description,'uid'=>$uid,'createdby'=>$this->session->userdata('userid')));
        
        $groupid = $this->Groups_model->get_group_id($uid);
        if (!$groupid) { 
            exit ('there was a problem creating the group');
        }

        return $groupid;
    }

    function get_group_id( $uid )
    {
        
        if (!$uid) { return false; }

        $group = $this->db->get_where('groups', array('uid' => $uid));
        
        if ($group->num_rows() > 0) {
            $group = $group->result_object();
            return $group[0]->id;
        }

        return false;
    }

    function get_group_info($groupid)
    {
        // Get Group information for email.
        $group = $this->db->query("SELECT * FROM groups WHERE id = '".$groupid."'");
        if ($group->num_rows() > 0) {
            return $group->result_array();
        }
        
        return false;
    }

    function get_all_groups()
    {

    }

    function get_groups_user_belongs_to()
    {	
		$user_belongs_to_groups = $this->db->query('SELECT * FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid='.$this->session->userdata('userid'));
		
		if ($user_belongs_to_groups->num_rows() > 0) {
			return $user_belongs_to_groups->result_array();
		} else {
			return false;
		}
    }

    function get_groups_created_by_user()
    {
        $createdgroups = $this->db->query('SELECT * FROM groups WHERE createdby = '.$this->session->userdata('userid').' ORDER BY id asc');
        if ($createdgroups->num_rows() > 0) {
            return $createdgroups->result_array();
        } else {
            return false;
        }
    }

    function get_group_members($id)
    {
        $groupmembers = $this->db->query("SELECT * FROM users_groups LEFT JOIN users ON users_groups.userid=users.id WHERE users_groups.groupid = '".$id."'");

        if ( $groupmembers->num_rows() > 0 ) {
            return $groupmembers->result_array();
        }

        return false;
    }

    function add_member_to_group($groupid)
    {
        if (!$groupid) return false;

        $this->db->insert('users_groups',array('groupid'=>$groupid,'userid'=>$this->session->userdata('userid')));
    }

    function get_group_members_count($id)
    {
        $groupmembers = $this->db->query("SELECT * FROM users_groups WHERE groupid = '".$id."'");
        if ($groupmembers->num_rows() > 0) {
            return $groupmembers->num_rows();
        }

        return false;
    }

    function update_group()
    {
        $name = $this->input->post('name',TRUE);
        $description = $this->input->post('description',TRUE);
        $uid = $this->input->post('uid');

        if ( $this->db->update('groups',array('name'=>$name,'description'=>$description),array('uid'=>$uid)) ) {
            return true;
        }

    return false;
    }

    function delete_group()
    {
        $uid = $this->input->post('uid');
        $groupid = $this->Groups_model->get_group_id($uid);

        // Remove all users from group
        $this->db->delete('users_groups', array('groupid' => $groupid));

        // Remove all invites
        $this->db->delete('groups_invites', array('groupid' => $groupid));

        // Remove all marks from group
        // But do not delete the marks themselves for the users
        $this->db->update('users_marks', array('groups' => ''), array('groups' => $groupid));

        // Delete group
        $this->db->delete('groups', array('id' => $groupid));

    return true;
    }

    /* Invites */

    function invite_member_to_group()
    {
        // Set up variables for group ID, UID, and the invitees email address
        $groupid = $this->input->post('groupid');
        $groupuid = $this->input->post('groupuid');
        $emailaddress = $this->input->post('emailaddress');

        // Do not allow a person to be invited more than once
        $invites = $this->db->query("SELECT * FROM groups_invites WHERE emailaddress = '".$emailaddress."' AND groupid = '".$groupid."'");
        
        if ($invites->num_rows() > 0) {
            return false;            
        }

        // Add invite to invites table
        $this->db->insert('groups_invites',array('groupid'=>$groupid,'emailaddress'=>$emailaddress,'invitedby'=>$this->session->userdata('userid')));

    return true;
    }

    function group_accept_invite()
    {

    }

    function group_delete_invite()
    {

    }


}