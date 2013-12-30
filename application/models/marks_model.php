<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marks_model extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function create()
    {

    }

    function update()
    {

    }

    function delete()
    {

    }

    function get_by_time($time='')
    {
    	// Unix timestamps for yesterday and today
		$yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
		$today = mktime(0, 0, 0, date('n'), date('j'));

    	switch($time) {
    		case '':
    			$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users ON users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");
    		break;

    		case 'today':
    			$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND UNIX_TIMESTAMP(marks.dateadded) > ".$today." AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");
    		break;

    		case 'yesterday':
    			$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND UNIX_TIMESTAMP(marks.dateadded) > ".$yesterday." AND UNIX_TIMESTAMP(marks.dateadded) < ".$today." AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");
    		break;
    	}

    	// Are there any results? If so, return.
    	if ($marks->num_rows() > 0) {
			return $marks->result_array();
		}

		return false;
    }

    function get_by_label($label='') 
    {	
    	if ($label == 'unlabeled') $label = '';
    	
    	$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id  WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.tags = '".$label."' AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");

    	// Are there any results? If so, return.
    	if ($marks->num_rows() > 0) {
			return $marks->result_array();
		}

		return false;
    }

    function get_archived()
    {
    	$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.status = 'archive' ORDER BY users_marks.id DESC LIMIT 100");


    	// Are there any results? If so, return.
    	if ($marks->num_rows() > 0) {
			return $marks->result_array();
		}

		return false;
    }

    function get_by_group($groupuid='')
    {
    	if ($groupuid == '') return false;

    	$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded, groups.id as groupid FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users ON users_marks.addedby=users.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND groups.uid='".$groupuid."' AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");

    	// Are there any results? If so, return.
    	if ($marks->num_rows() > 0) {
			return $marks->result_array();
		}

		return false;
    }

    function search_from_user($search='')
    {
    	if ($search == '') return false;

    	$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.id, users.emailaddress, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.id  WHERE users_marks.userid='".$this->session->userdata('userid')."' AND marks.title LIKE '%".$search."%' ORDER BY users_marks.id DESC LIMIT 100");

    	// Are there any results? If so, return.
    	if ($marks->num_rows() > 0) {
			return $marks->result_array();
		}

		return false;
    }


}