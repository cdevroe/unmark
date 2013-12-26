<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    
    $this->load->helper(array('url','form'));
    $this->load->library('session');
    
  }
  
  // Unused for now.
	public function index()
	{
	   
	}
	
	public function create() {
	 
	 if (!$this->session->userdata('userid')) { redirect(''); }
	 
	 $data['uid'] = $this->generate_uid(10);
	 $data['label'] = '';
	 $data['when'] = '';
	 $data['group']['groupuid'] = '';
	 
	 $this->load->database();
	 
	  $createdgroups = $this->db->query('SELECT * FROM groups WHERE createdby = '.$this->session->userdata('userid').' ORDER BY id asc');
   if ($createdgroups->num_rows() > 0) {
    $data['groups']['created'] = $createdgroups->result_array();
   }
    
   $belonggroups = $this->db->query('SELECT * FROM users_groups LEFT JOIN groups ON users_groups.groupid=groups.id WHERE users_groups.userid='.$this->session->userdata('userid'));
   if ($belonggroups->num_rows() > 0) {
    $data['groups']['belong'] = $belonggroups->result_array();
   } 
	 
	 $this->load->view('groups_create',$data);
	
	}
	
	public function add() {
  	 if (!$this->session->userdata('userid')) { redirect(''); }
  	 
    $name = $this->input->post('name',TRUE);
    $description = $this->input->post('description',TRUE);
    $uid = $this->input->post('uid');
    
    
    if ($name == 'Name your group') { 
      $this->session->set_flashdata('message', 'Please type in a name for your group.');
      redirect('groups/create');
    }
    
    if ($description == 'What is the purpose of your group? (optional)') { 
      $description = '';
    }
    
    $this->load->database();
       
    $existinggroup = $this->db->query('SELECT * FROM groups WHERE uid = "'.$uid.'"');
    if ($existinggroup->num_rows() > 0) {
      $uid = $this->generate_uid(10);      
    }
    
    $this->db->insert('groups',array('name'=>$name,'description'=>$description,'uid'=>$uid,'createdby'=>$this->session->userdata('userid')));
    $groupid = $this->db->insert_id();
    
    $this->db->insert('users_groups',array('groupid'=>$groupid,'userid'=>$this->session->userdata('userid')));
    
    $this->session->set_flashdata('message', 'Your group "'.$name.'" is ready. The next time you add a link to Nilai you can share it with that group!');
    
    
    // Email invites
    $this->load->helper('email');
    $this->load->library('email');
    
    $invites=array();
    $invites[] = $this->input->post('invite1');
    $invites[] = $this->input->post('invite2');
    $invites[] = $this->input->post('invite3');
    $invites[] = $this->input->post('invite4');
    $invites[] = $this->input->post('invite5');

    if (valid_email('email@somesite.com')) {
      
    for($i=0;$i<count($invites);$i++) {
      if ($invites[$i] != '') {
        
        $this->db->insert('groups_invites',array('groupid'=>$groupid,'emailaddress'=>$invites[$i],'invitedby'=>$this->session->userdata('userid')));
      
        $this->email->from($this->session->userdata('emailaddress'));
        $this->email->to($invites[$i]); 
        
        $this->email->subject('You\'ve been invited to the '.$name.' group on Nilai');
        $this->email->message("Hi $invites[$i],\n\nI assume that you know ".$this->session->userdata('emailaddress')." They have invited you to a group named \"".$name."\" on Nilai - The smartest way to save links for later.\n\nTo accept this invite click this link:\nhttp://nilai.co/\n\nIf you'd rather not accept this invite or if you'd like to verify that this email isn't unsolicited - simply reply to this email and your response will go directly to the person that invited you.\n\nI hope you enjoy Nilai.\nColin Devroe\nhttp://nilai.co");
        
        $this->email->send();
        $this->email->print_debugger();
      }
    }
      
      
      
      
    } else {
      echo 'At least one of the email addresses you provided was invalid. Please try again.';
      exit;
    }
    
    
    
    redirect('home');
    
    
	}
	
	public function invite() {
	
	 $groupid = $this->input->post('groupid');
	 $groupuid = $this->input->post('groupuid');
	 $emailaddress = $this->input->post('emailaddress');
	 
	 $this->load->database();
	 $this->load->helper('email');
   $this->load->library('email');
   
   $invites = $this->db->query("SELECT * FROM groups_invites WHERE emailaddress = '".$emailaddress."' AND groupid = '".$groupid."'");
   if ($invites->num_rows() > 0) {
    $this->session->set_flashdata('message', $emailaddress.' has already been invited to this group.');
	  redirect('groups/'.strtoupper($groupuid).'/members');
	  exit;
   }
	 
	 $group = $this->db->query("SELECT * FROM groups WHERE id = '".$groupid."'");
	 $group = $group->result_array();
	 
	 
	   $this->db->insert('groups_invites',array('groupid'=>$groupid,'emailaddress'=>$emailaddress,'invitedby'=>$this->session->userdata('userid')));
      
     $this->email->from($this->session->userdata('emailaddress'));
     $this->email->to($emailaddress); 
        
     $this->email->subject('You\'ve been invited to the '.$group[0]['name'].' group on Nilai');
     $this->email->message("Hi $emailaddress,\n\nI assume that you know ".$this->session->userdata('emailaddress')." They have invited you to a group named \"".$group[0]['name']."\" on Nilai - The smartest way to save links for later.\n\nTo accept this invite click this link:\nhttp://nilai.co/\n\nIf you'd rather not accept this invite or if you'd like to verify that this email isn't unsolicited - simply reply to this email and your response will go directly to the person that invited you.\n\nI hope you enjoy Nilai.\nColin Devroe\nhttp://nilai.co");
        
     $this->email->send();
     
     $this->session->set_flashdata('message', $emailaddress.' has been invited.');
	   redirect('groups/'.strtoupper($groupuid).'/members');
	   
	}
	
	
	public function acceptinvite() {
	   
	   $groupuid = $this->uri->segment(3);
	   $inviteid = $this->uri->segment(4);
	   
	   $this->load->database();
	   
	   if (!$this->session->userdata('userid')) { // Not logged in
	     redirect(''); // Redirect to a page that explains they can log in or sign up
	   } else {
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
	
	}
	
	public function edit() {
	
	 if (!$this->session->userdata('userid')) { // Not logged in
	     redirect('');
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
	
	public function update() {
	
	 if (!$this->session->userdata('userid')) { // Not logged in
	     redirect('');
	 }
	 
	 $this->load->database();
	
	 $name = $this->input->post('name',TRUE);
   $description = $this->input->post('description',TRUE);
   $uid = $this->input->post('uid');
   
   $this->db->update('groups',array('name'=>$name,'description'=>$description),array('uid'=>$uid));
   
   
   $this->session->set_flashdata('message', 'Group information updated.');
   redirect('groups/'.strtoupper($uid));



	}
	
	public function members() {
	
	 if (!$this->session->userdata('userid')) { // Not logged in
	     redirect('');
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