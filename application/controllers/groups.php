<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends CI_Controller {

	public function __construct()
	{
    	parent::__construct();

    	$this->load->helper(array('url','form'));
    	$this->load->library('session');
    }
  	
  	// Unused for now.
	public function index()
	{

	}
	
	// Just a view for the user
	// to type in a name, description
	// and invite some people.
	// This doens't actually add()
	public function create()
	{
		// If user is not logged in, redirect.
		if (!$this->session->userdata('userid')) { redirect(''); }
		
		// Create a 10char unique ID for group
		$data['uid'] = $this->generate_uid(10);

		// Clear vars
		$data['label'] = '';
		$data['when'] = '';
		$data['group']['groupuid'] = '';

		// Load database
		$this->load->database();
		$this->load->model('Groups_model');

		// ### I do not remember why I figure out the groups that this person has created, or belongs to. Clean up later.

		// Determine the number of groups this user has created before
		$data['groups']['created'] = $this->Groups_model->get_groups_created_by_user();

		// Determine the number of groups this user belongs to already
		$data['groups']['belong'] = $this->Groups_model->get_groups_user_belongs_to();

		$this->load->view('groups_create',$data);

	}
	
	// Method used to actually add a new group
	public function add()
	{
		// If user is not logged in, redirect
		if (!$this->session->userdata('userid')) { redirect(''); }

		// Load database
		$this->load->database();
		$this->load->model('Groups_model');

		// Add Group to Database
		$groupid = $this->Groups_model->create_group();

		// Add this user to the group
        $this->Groups_model->add_member_to_group($groupid);
    	
	    
	    // # Setup Email library to email invites for the group
	    $this->load->helper('email');
	    $this->load->library('email');
	    
	    // Create an array of the email addresses
	    $invites=array();
	    $invites[] = $this->input->post('invite1');
	    $invites[] = $this->input->post('invite2');
	    $invites[] = $this->input->post('invite3');
	    $invites[] = $this->input->post('invite4');
	    $invites[] = $this->input->post('invite5');

	  	// Loop through emails provided    
	    for($i=0;$i<count($invites);$i++) {
	      if ( $invites[$i] != '' && valid_email($invites[$i]) ) {
	        
	        // Add record to invites table
	        $this->db->insert('groups_invites',array('groupid'=>$groupid,'emailaddress'=>$invites[$i],'invitedby'=>$this->session->userdata('userid')));
	      	
	      	// Construct and send email.
	        $this->email->from($this->session->userdata('emailaddress'));
	        $this->email->to($invites[$i]); 
	        
	        $this->email->subject('You\'ve been invited to the '.$name.' group on Nilai');
	        $this->email->message("Hi $invites[$i],\n\nI assume that you know ".$this->session->userdata('emailaddress')." They have invited you to a group named \"".$name."\" on Nilai - The smartest way to save links for later.\n\nTo accept this invite click this link:\nhttp://nilai.co/\n\nIf you'd rather not accept this invite or if you'd like to verify that this email isn't unsolicited - simply reply to this email and your response will go directly to the person that invited you.\n\nI hope you enjoy Nilai.\nColin Devroe\nhttp://nilai.co");
	        
	        $this->email->send();
	        // Used only to debug $this->email->print_debugger();
	      }
	  	}

	  	redirect('home');
	}
	
	// Sends a single invite to a group
	public function invite()
	{
		// Set up variables for group ID, UID, and the invitees email address
        $groupid = $this->input->post('groupid');
        $groupuid = $this->input->post('groupuid');
        $emailaddress = $this->input->post('emailaddress');

		// Load Database and Email Helper/Library
		$this->load->database();
	 	$this->load->helper('email');
	 	$this->load->library('email');
	 	$this->load->model('Groups_model');

	 	if ( $this->Groups_model->invite_member_to_group() ) {
	 		$this->session->set_flashdata('message', $emailaddress.' has been invited.');
	 	} else {
	 		$this->session->set_flashdata('message', $emailaddress.' has already been invited to this group.');
	 	}
	 	
	 	// Get Group information for email.
	 	$group = $this->Groups_model->get_group_info($groupid);

	 	// Construct and send email
	 	$this->email->from($this->session->userdata('emailaddress'));
	 	$this->email->to($emailaddress);
	 	$this->email->subject('You\'ve been invited to the '.$group[0]['name'].' group on Nilai');
	 	$this->email->message("Hi $emailaddress,\n\nI assume that you know ".$this->session->userdata('emailaddress')." They have invited you to a group named \"".$group[0]['name']."\" on Nilai - The smartest way to save links for later.\n\nTo accept this invite click this link:\nhttp://nilai.co/\n\nIf you'd rather not accept this invite or if you'd like to verify that this email isn't unsolicited - simply reply to this email and your response will go directly to the person that invited you.\n\nI hope you enjoy Nilai.\nColin Devroe\nhttp://nilai.co");
	 	$this->email->send();

	 	redirect('groups/'.strtoupper($groupuid).'/members');
	}
	
	
	public function acceptinvite()
	{
		// Group UID and Invite ID from URL
		$groupuid = $this->uri->segment(3);
		$inviteid = $this->uri->segment(4);

		$this->load->database();

		if (!$this->session->userdata('userid')) { // Not logged in
			redirect(''); // Redirect to a page that explains they can log in or sign up
			exit;
		}

		// See if user already belongs to this group
		// If not, add them to the group
		// Copy all bookmarks
		// If so, redirect to group page.
		$usergroups = $this->db->query("SELECT *, groups.id as groupid FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid = '".$this->session->userdata('userid')."' AND groups.uid = '".$groupuid."'");
		
		if ($usergroups->num_rows() > 0) {
			$usergroups=$usergroups->result_array();
			// User already belongs to this group
			$this->session->set_flashdata('message', '<strong>Invitation accepted!</strong> However, you already belonged to this group.');
			// Accept the invite
			$this->db->update('groups_invites',array('status'=>'accepted'),array('emailaddress'=>$this->session->userdata('emailaddress'),'groupid'=>$usergroups[0]['groupid']));
			redirect('groups/'.strtoupper($groupuid));
		} else {
			// Find more information about the group
			$groupinfo = $this->db->query("SELECT * FROM groups WHERE uid = '".$groupuid."'");
			$group=$groupinfo->result_array();
			// Add user to this group
			$this->db->insert('users_groups',array('userid'=>$this->session->userdata('userid'),'groupid'=>$group[0]['id']));
			// Copy all bookmarks that were in this group to the newly joined user.
			$marks = $this->db->query("SELECT * FROM users_marks GROUP BY urlid WHERE groups = '".$group[0]['id']."' ORDER BY id asc");
			if ($marks->num_rows() > 0) {
				$marks = $marks->result_array();
				foreach ($marks as $mark) {
					$this->db->insert('users_marks',array('userid'=>$this->session->userdata('userid'),'urlid'=>$mark['urlid'],'groups'=>$group[0]['id'],'tags'=>$mark['tags'],'addedby'=>$mark['addedby']));
				}
			}
			// Accept the invite
			$this->db->update('groups_invites',array('status'=>'accepted'),array('emailaddress'=>$this->session->userdata('emailaddress'),'groupid'=>$group[0]['id']));
			$this->session->set_flashdata('message', '<strong>Invitation accepted!</strong> You can now see the links that belong to this group and you can add your own!');

			// Redirect to group!
			redirect('groups/'.strtoupper($groupuid));
		}
	}
	
	public function edit()
	{
		if (!$this->session->userdata('userid')) { // Not logged in
			redirect('');
			exit;
		}

		$groupuid = $this->uri->segment(2);

		$this->load->database();

		// General group information
		$group = $this->db->query("SELECT * FROM groups WHERE uid = '".$groupuid."'");
		if ($group->num_rows() > 0) {
			$group = $group->result_array();

			$data['group']['name'] = $group[0]['name'];
			$data['group']['description'] = $group[0]['description'];
			$data['group']['groupuid'] = $groupuid;
			$data['group']['groupid'] = $group[0]['id'];
			$data['group']['owner'] = $group[0]['createdby'];

			$groupmembers = $this->db->query("SELECT * FROM users_groups WHERE groupid = '".$group[0]['id']."'");
			$data['group']['member_count'] = $groupmembers->num_rows();
		} else {
			show_404();
		}

		$data['when'] = '';
		$data['label'] = '';

		if ($data['group']['owner'] != $this->session->userdata('userid')) {
			exit('You do not have permission to manage this group.');
		}

		$this->load->view('groups_edit',$data);
	}
	
	public function update()
	{

		if (!$this->session->userdata('userid')) { // Not logged in
			redirect('');
			exit;
		}
		$uid = $this->input->post('uid');

		$this->load->database();
		$this->load->model('Groups_model');

		$this->Groups_model->update_group();

		$this->session->set_flashdata('message', 'Group information updated.');

		redirect('groups/'.strtoupper($uid));
	}

	public function delete()
	{
		
		if (!$this->session->userdata('userid')) { // Not logged in
			redirect('');
			exit;
		}
		$uid = $this->input->post('uid');

		$this->load->database();
		$this->load->model('Groups_model');

		// Check to see if this user owns the current group
        $group = $this->Groups_model->get_group_info($this->Groups_model->get_group_id($uid));

        if ( is_array($group) ){
           
            if ( $this->session->userdata('userid') == $group[0]['createdby'] ) {
                $this->Groups_model->delete_group();
                $this->session->set_flashdata('message', 'All users have been removed from the group, all bookmarks have been removed from the group, all outstanding invitations to the group have been deleted and the group has been deleted. This cannot be undone.');
            }
        }

		redirect('home');
	}
	
	public function members() {
		if (!$this->session->userdata('userid')) { // Not logged in
			redirect('');
			exit;
		}

		$groupuid = $this->uri->segment(2);
		$this->load->database();

		// General group information
		$group = $this->db->query("SELECT * FROM groups WHERE uid = '".$groupuid."'");
		if ($group->num_rows() > 0) {
			$group = $group->result_array();
			
			$data['group']['name'] = $group[0]['name'];
			$data['group']['description'] = $group[0]['description'];
			$data['group']['groupuid'] = $groupuid;
			$data['group']['groupid'] = $group[0]['id'];
			$data['group']['owner'] = $group[0]['createdby'];

			$groupmembers = $this->db->query("SELECT * FROM users_groups LEFT JOIN users ON users_groups.userid=users.id WHERE users_groups.groupid = '".$group[0]['id']."'");

			if ($groupmembers->num_rows() > 0) {
				$data['group']['member_count'] = $groupmembers->num_rows();
				$data['group']['members'] = $groupmembers->result_array();
			}

			$invites = $this->db->query("SELECT * FROM groups_invites WHERE groupid = '".$group[0]['id']."' AND status = ''");
			if ($invites->num_rows() > 0) {
				$data['group']['invites'] = $invites->result_array();
			}
		} else {
			show_404();
		}

		$data['when'] = '';
		$data['label'] = '';

		if ($data['group']['owner'] != $this->session->userdata('userid')) {
			exit('You do not have permission to manage this group.');
		}

		$this->load->view('groups_members',$data);
	}
	
	
	public function generate_uid($length)
	{
	  if($length>0) 
	  { 
	  $rand_id="";
	   for($i=1; $i<=$length; $i++)
	   {
	   mt_srand((double)microtime() * 1000000);
	   $num = mt_rand(1,36);
	   $rand_id .= $this->assign_rand_value($num);
	   }
	  }
	return $rand_id;
	}
	
	public function assign_rand_value($num)
	{
	// accepts 1 - 36
	  switch($num)
	  {
	    case "1":
	     $rand_value = "a";
	    break;
	    case "2":
	     $rand_value = "b";
	    break;
	    case "3":
	     $rand_value = "c";
	    break;
	    case "4":
	     $rand_value = "d";
	    break;
	    case "5":
	     $rand_value = "e";
	    break;
	    case "6":
	     $rand_value = "f";
	    break;
	    case "7":
	     $rand_value = "g";
	    break;
	    case "8":
	     $rand_value = "h";
	    break;
	    case "9":
	     $rand_value = "i";
	    break;
	    case "10":
	     $rand_value = "j";
	    break;
	    case "11":
	     $rand_value = "k";
	    break;
	    case "12":
	     $rand_value = "l";
	    break;
	    case "13":
	     $rand_value = "m";
	    break;
	    case "14":
	     $rand_value = "n";
	    break;
	    case "15":
	     $rand_value = "o";
	    break;
	    case "16":
	     $rand_value = "p";
	    break;
	    case "17":
	     $rand_value = "q";
	    break;
	    case "18":
	     $rand_value = "r";
	    break;
	    case "19":
	     $rand_value = "s";
	    break;
	    case "20":
	     $rand_value = "t";
	    break;
	    case "21":
	     $rand_value = "u";
	    break;
	    case "22":
	     $rand_value = "v";
	    break;
	    case "23":
	     $rand_value = "w";
	    break;
	    case "24":
	     $rand_value = "x";
	    break;
	    case "25":
	     $rand_value = "y";
	    break;
	    case "26":
	     $rand_value = "z";
	    break;
	    case "27":
	     $rand_value = "0";
	    break;
	    case "28":
	     $rand_value = "1";
	    break;
	    case "29":
	     $rand_value = "2";
	    break;
	    case "30":
	     $rand_value = "3";
	    break;
	    case "31":
	     $rand_value = "4";
	    break;
	    case "32":
	     $rand_value = "5";
	    break;
	    case "33":
	     $rand_value = "6";
	    break;
	    case "34":
	     $rand_value = "7";
	    break;
	    case "35":
	     $rand_value = "8";
	    break;
	    case "36":
	     $rand_value = "9";
	    break;
	  }
	return $rand_value;
	}
	
	
}

/* End of file groups.php */
/* Location: ./application/controllers/groups.php */