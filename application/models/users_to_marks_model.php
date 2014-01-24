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

            // If good, return full record
            if ($res === true) {
                $user_mark_id = $this->db->insert_id();
                return $this->readComplete($user_mark_id);
            }

            // Else return error
            return false;
        }

        return $this->formatErrors($valid);
    }

    protected function format($marks)
    {
        foreach ($marks as $k => $mark) {
            $marks[$k]->tags = array();
            if (isset($mark->tag_ids) && ! empty($mark->tags_ids)) {
                $ids   = explode($this->delimiter, $mark->tags_ids);
                $names = explode($this->delimiter, $mark->tag_names);
                $slugs = explode($this->delimiter, $mark->tag_slugs);
                foreach ($ids as $kk => $id) {
                    $marks[$k]->tags[$id] = array('name' => $names[$kk], 'slug' => $slugs[$kk]);
                }
            }
            unset($marks[$k]->tag_ids);
            unset($marks[$k]->tag_names);
            unset($marks[$k]->tag_slugs);
        }
        return $marks;
    }

    public function getTotal($type, $user_id, $start='today', $finish=null)
    {
        $type  = trim(strtolower($type));
        $types = array('archived' => 'archived_on', 'saved' => 'created_on', 'marks' => 'created_on');

        // If type not found, return 0
        if (! array_key_exists($type, $types)) {
            return 0;
        }

        // Set column from type
        $column = $types[$type];

        // Figure start & finish
        $dates = findStartFinish($start, $finish);

        // Figure date range
        $when = null;

        // If from is not empty, figure timestamp
        if (! empty($dates['start'])) {
            $when .= " AND UNIX_TIMESTAMP(" . $column . ") >= '" . $dates['start'] . "'";
        }

        // if to is not empty, figure timestamp
        if (! empty($dates['finish'])) {
            $when .= " AND UNIX_TIMESTAMP(" . $column . ") <= '" . $dates['finish'] . "'";
        }

        // If when is empty, set to IS NOT NULL
        if ($type == 'marks') {
            $when .= " AND archived_on IS NULL";
        }

        return $this->count("user_id='". $user_id . "'" . $when);
    }

    public function readComplete($where, $limit=1, $page=1, $start=null, $search=null)
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
            users_to_marks.users_to_mark_id AS mark_id, users_to_marks.notes, users_to_marks.created_on, users_to_marks.archived_on,
            marks.title, marks.url, marks.embed,
            GROUP_CONCAT(tags.tag_id SEPARATOR '" . $this->delimiter . "') AS tag_ids,
            GROUP_CONCAT(tags.name SEPARATOR '" . $this->delimiter . "') AS tag_names,
            GROUP_CONCAT(tags.slug SEPARATOR '" . $this->delimiter . "') AS tag_slugs,
            labels.label_id, labels.name AS label_name
            FROM users_to_marks
            LEFT JOIN marks ON users_to_marks.mark_id = marks.mark_id
            LEFT JOIN user_marks_to_tags ON users_to_marks.mark_id = user_marks_to_tags.users_to_mark_id
            LEFT JOIN labels ON users_to_marks.label_id = labels.label_id
            LEFT JOIN tags ON user_marks_to_tags.tag_id = tags.tag_id
            WHERE " . $where . " GROUP BY users_to_marks.users_to_mark_id" . $sort . $q_limit
        );

        // Check for errors
        $this->sendException();

        // Now format the group names and ids
        if ($marks->num_rows() > 0) {
            $marks = $this->format($marks->result());
            return ($limit == 1) ? $marks[0] : $marks;
        }

        return false;
    }

}