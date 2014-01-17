<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_To_Marks_model extends Plain_Model
{

    public $sort = 'created_on DESC';


	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        // Set data types
        $this->data_types = array(
            'mark_id'     =>  'numeric',
            'user_id'     =>  'numeric',
            'label_id'    =>  'numeric',
            'notes'       =>  'string',
            'created_on'  =>  'datetime',
            'archived_on' =>  'datetime',
            'title'       =>  'string',
            'url'         =>  'url'
        );

    }

    public function create($options=array())
    {
        $valid  = validate($options, $this->data_types, array('user_id', 'mark_id'));

        // Make sure all the options are valid
        if ($valid === true) {

            $options['created_on'] = date('Y-m-d H:i:s');
            $q   = $this->db->insert_string('users_to_marks', $options);
            $res = $this->db->query($q);

            // Check for errors
            $this->sendException();

            if ($res === true) {
                $user_mark_id = $this->db->insert_id();
                return $this->read($user_mark_id);
            }
            else {
                return $this->formatErrors('The mark could not be created. Please try again.');
            }

        }

        return $this->formatErrors($valid);
    }


    function delete_mark_for_user($urlid)
    {
        if ($urlid=='') return false;

        // Lets see if this user has ever added this URL before
        $mark = $this->db->delete('users_marks',array('urlid'=>$urlid,'userid'=>$this->session->userdata('userid')));

        return true;
    }

    function get_users_mark_by_id($markid='')
    {
        if ($markid == '') return false;

        $mark = $this->db->query("SELECT * FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.id='".$markid."'");

        if ( $mark->num_rows() > 0 ) {
            return $mark->result_array();
        }

        return false;
    }

    protected function formatGroups($marks)
    {
        foreach ($marks as $k => $mark) {
            $marks[$k]->groups = array();
            if (isset($mark->group_ids) && ! empty($mark->group_ids)) {
                $ids  = explode($this->delimiter, $mark->group_ids);
                $names = explode($this->delimiter, $mark->group_names);
                foreach ($ids as $kk => $id) {
                    $marks[$k]->groups[$id] = $names[$kk];
                }
            }
            unset($marks[$k]->group_ids);
            unset($marks[$k]->group_names);
        }
        return $marks;
    }


    public function readComplete($where, $limit=1, $page=1, $start=null)
    {
        $id         = (is_numeric($where)) ? $where : null;
        $where      = (is_numeric($where)) ? 'users_to_marks.' . $this->id_column . " = '$where'" : trim($where);
        $page       = (is_numeric($page) && $page > 0) ? $page : 1;
        $limit      = ((is_numeric($limit) && $limit > 0) || $limit == 'all') ? $limit : 1;
        $start      = (! is_null($start)) ? $start : $limit * ($page - 1);
        $q_limit    = ($limit != 'all') ? ' LIMIT ' . $start . ',' . $limit : null;
        $sort       = (! empty($this->sort)) ? ' ORDER BY users_to_marks.' . $this->sort : null;

        // Stop, query time
        $q     = $this->db->query('SET SESSION group_concat_max_len = 10000');
		$marks = $this->db->query("
            SELECT
            users_to_marks.users_to_mark_id, users_to_marks.notes, users_to_marks.created_on,
            marks.mark_id, marks.title, marks.url,
            GROUP_CONCAT(groups.group_id SEPARATOR '" . $this->delimiter . "') AS group_ids,
            GROUP_CONCAT(groups.name SEPARATOR '" . $this->delimiter . "') AS group_names,
            labels.label_id, labels.name AS label_name
            FROM users_to_marks
            LEFT JOIN marks ON users_to_marks.mark_id = marks.mark_id
            LEFT JOIN user_marks_to_groups ON users_to_marks.mark_id = user_marks_to_groups.user_mark_id
            LEFT JOIN labels ON users_to_marks.label_id = labels.label_id
            LEFT JOIN groups ON user_marks_to_groups.group_id = groups.group_id
            WHERE " . $where . " GROUP BY users_to_marks.users_to_mark_id" . $sort . $q_limit
        );

        // Check for errors
        $this->sendException();

        // Now format the group names and ids
        if ($marks->num_rows() > 0) {
            return $this->formatGroups($marks->result());
        }

        return false;
    }

    function get_number_archived_today()
    {
        // Unix timestamps for yesterday and today
        $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
        $today = mktime(0, 0, 0, date('n'), date('j'));

        $marks = $this->db->query("SELECT * FROM users_marks WHERE userid='".$this->session->userdata('userid')."' AND UNIX_TIMESTAMP(datearchived) > ".$today." AND status = 'archive' ORDER BY id DESC LIMIT 100");

        // Are there any results? If so, return.
        if ($marks->num_rows() > 0) {
            return $marks->num_rows();
        }

        return false;
    }

    function get_number_saved_today()
    {
        // Unix timestamps for yesterday and today
        $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
        $today = mktime(0, 0, 0, date('n'), date('j'));

        $marks = $this->db->query("SELECT * FROM users_marks WHERE userid='".$this->session->userdata('userid')."' AND UNIX_TIMESTAMP(dateadded) > ".$today." ORDER BY id DESC LIMIT 100");

        // Are there any results? If so, return.
        if ($marks->num_rows() > 0) {
            return $marks->num_rows();
        }

        return false;
    }

    function get_by_label($label='')
    {
    	if ($label == 'unlabeled') $label = '';

    	$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.user_id, users.email, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.user_id  WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.tags = '".$label."' AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");

    	// Are there any results? If so, return.
    	if ($marks->num_rows() > 0) {
			return $marks->result_array();
		}

		return false;
    }

    function get_archived()
    {
    	$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.user_id, users.email, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.user_id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND users_marks.status = 'archive' ORDER BY users_marks.id DESC LIMIT 100");


    	// Are there any results? If so, return.
    	if ($marks->num_rows() > 0) {
			return $marks->result_array();
		}

		return false;
    }

    function get_by_group($groupuid='')
    {
    	if ($groupuid == '') return false;

    	$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.user_id, users.email, users_marks.id as usersmarkid, users_marks.dateadded as dateadded, groups.id as groupid FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users ON users_marks.addedby=users.user_id WHERE users_marks.userid='".$this->session->userdata('userid')."' AND groups.uid='".$groupuid."' AND users_marks.status != 'archive' ORDER BY users_marks.id DESC LIMIT 100");

    	// Are there any results? If so, return.
    	if ($marks->num_rows() > 0) {
			return $marks->result_array();
		}

		return false;
    }

    function add_mark_to_group($urlid='',$groupid='')
    {
        if ($urlid=='' || $groupid =='') return false;

        $this->load->model('Groups_model');

        $this->db->update('users_marks',array('groups'=>$groupid),array('urlid' => $urlid,'userid'=>$this->session->userdata('userid')));

        // Add this mark to for every other user in the group.
        //$groupmembers = $this->db->query("SELECT * FROM users_groups WHERE groupid = ".$group);
        $groupmembers = $this->Groups_model->get_group_members($groupid);

        if ( is_array($groupmembers) == true ) {
            foreach($groupmembers as $member) {
                if ($member['userid'] == $this->session->userdata('userid')) continue; // No reason to add for current user. We already did that.

                $this->db->insert('users_marks',array('userid'=>$member['userid'],'urlid'=>$urlid,'groups'=>$groupid,'addedby'=>$this->session->userdata('userid')));

            }
        }
    }

    function search_from_user($search='')
    {
    	if ($search == '') return false;

    	$marks = $this->db->query("SELECT users_marks.*, marks.*, groups.*, users.user_id, users.email, users_marks.id as usersmarkid, users_marks.dateadded as dateadded FROM users_marks LEFT JOIN marks ON users_marks.urlid=marks.id LEFT JOIN groups ON users_marks.groups=groups.id LEFT JOIN users on users_marks.addedby=users.user_id  WHERE users_marks.userid='".$this->session->userdata('userid')."' AND marks.title LIKE '%".$search."%' ORDER BY users_marks.id DESC LIMIT 100");

    	// Are there any results? If so, return.
    	if ($marks->num_rows() > 0) {
			return $marks->result_array();
		}

		return false;
    }

    public function update($where, $options)
    {

        // If groups exist, save and remove them for later
        $groups        = (isset($options['groups'] && ! empty($options['groups']) && is_array($options['groups']))) ? $options['groups'] : array();
        $update_groups = (isset($options['update_groups'] && ! empty($options['update_groups']))) ? true : false;

        // Unset some options
        if (isset($options['groups']) { unset($options['groups']); }
        if (isset($options['update_groups']) { unset($options['update_groups']); }

        $mark = parent::update($where, $options);

        if (isset($mark->users_to_mark_id)) {
            self::updateGroups($mark->users_to_mark_id, $groups, $update_groups);
            return $this->readComplete($mark->users_to_mark_id);
        }

        return $mark;
    }

    protected function updateGroups($user_mark_id, $groups=array(), $update_groups=false)
    {
        if ((! empty($groups) || ! empty($update_groups)) && is_numeric($user_mark_id)) {
            $this->load->model('user_marks_to_groups', 'to_groups');
            $res = $this->to_groups->delete("user_mark_id = '" . $user_mark_id . "'");

            if (! empty($groups)) {
                foreach ($groups as $group_id) {
                    if (is_numeric($group_id)) {
                        $res = $this->to_groups->create(array('user_mark_id' => $user_mark_id, 'group_id' => $group_id));
                    }
                }
            }
        }
    }


}