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
            'active'      =>  'numeric',
            'created_on'  =>  'datetime',
            'archived_on' =>  'datetime',
            'title'       =>  'string',
            'url'         =>  'url'
        );

         // Set a different read method
        $this->read_method = 'readComplete';

    }

    public function create($options=array())
    {
        return $this->validateAndSave($options, true);
    }

    public function import($options=array())
    {
        return $this->validateAndSave($options, false);
    }

    private function validateAndSave($options, $overwriteCreatedOn){
        $valid  = validate($options, $this->data_types, array('user_id', 'mark_id'));

        // Make sure all the options are valid
        if ($valid === true) {

            if($overwriteCreatedOn || empty($options['created_on'])){
                $options['created_on'] = date('Y-m-d H:i:s');
            }
            $q   = $this->db->insert_string('users_to_marks', $options);
            $res = $this->db->query($q);

            // Check for errors
            $this->sendException();

            // If good, return full record
            if ($res === true) {
                // Remove cache for this user
                $this->removeCacheKey($this->cache_id . $options['user_id'] . '-*');

                // Get info and return it
                $user_mark_id = $this->db->insert_id();
                return $this->readComplete($user_mark_id);
            }

            // Else return error
            return false;
        }

        return formatErrors($valid);
    }

    protected function format($marks)
    {
        foreach ($marks as $k => $mark) {
            $marks[$k]->tags = array();
            if (isset($mark->tag_ids) && ! empty($mark->tag_ids)) {
                $ids   = explode($this->delimiter, $mark->tag_ids);
                $names = explode($this->delimiter, $mark->tag_names);
                $slugs = explode($this->delimiter, $mark->tag_slugs);
                foreach ($ids as $kk => $id) {
                    $marks[$k]->tags[$slugs[$kk]] = array('tag_id' => $ids[$kk], 'name' => $names[$kk], 'slug' => $slugs[$kk]);
                }
                ksort($marks[$k]->tags);
            }

            // Figure the nice time
            $marks[$k]->nice_time = '';
            if (isset($mark->created_on) && ! empty($mark->created_on)) {
                $marks[$k]->nice_time = generateTimeSpan($mark->created_on);
            }

            unset($marks[$k]->tag_ids);
            unset($marks[$k]->tag_names);
            unset($marks[$k]->tag_slugs);
        }
        return $marks;
    }

    public function getTotal($type, $user_id, $start=null, $finish=null)
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
        if (! empty($start)) {
            $dates = findStartFinish($start, $finish);
        }

        // Figure date range
        $when = " AND active = '1'";

        // If from is not empty, figure timestamp
        if (isset($dates['start']) && ! empty($dates['start'])) {
            //$when .= " AND UNIX_TIMESTAMP(" . $column . ") >= '" . $dates['start'] . "'";
            $when .= " AND " . $column . " >= '" . $dates['start'] . "'";
        }

        // if to is not empty, figure timestamp
        if (isset($dates['finish']) && ! empty($dates['finish'])) {
            //$when .= " AND UNIX_TIMESTAMP(" . $column . ") <= '" . $dates['finish'] . "'";
            $when .= " AND " . $column . " < '" . $dates['finish'] . "'";
        }

        // If when is empty, set to IS NOT NULL
        if ($type == 'marks') {
            $when .= " AND archived_on IS NULL";
        }
        elseif ($type == 'archived') {
            $when .= " AND archived_on IS NOT NULL";
        }

        return $this->count("user_id='". $user_id . "'" . $when);
    }

    public function getTotalsSearch($page, $limit, $data=array(), $keyword, $user_id, $archive)
    {

        $result = $this->checkForHit("
            SELECT COUNT(*) AS total FROM (
                (
                    SELECT users_to_marks.users_to_mark_id
                    FROM users_to_marks
                    WHERE
                    users_to_marks.user_id='" . $user_id . "'
                    AND users_to_marks.archived_on IS NULL
                    AND users_to_marks.notes LIKE '%" . $keyword . "%'
                    AND users_to_marks.active = '1'
                )
                UNION DISTINCT
                (
                    SELECT users_to_marks.users_to_mark_id
                    FROM marks
                    INNER JOIN users_to_marks ON marks.mark_id = users_to_marks.mark_id AND users_to_marks.user_id = '" . $user_id . "' AND users_to_marks.archived_on " . $archive . " AND users_to_marks.active = '1'
                    WHERE
                    marks.title LIKE '%" . $keyword . "%'
                    OR marks.url LIKE '%" . $keyword . "%'
                )
            ) as t1
        ");

        $total            = (isset($result[0]->total)) ? (integer) $result[0]->total : 0;
        $total_pages      = ($total > 0) ? ceil($total / $limit) : 0;
        $data['total']    = $total;
        $data['page']     = $page;
        $data['per_page'] = $limit;
        $data['pages']    = $total_pages;

        // Return it all
        return $data;
    }

    public function readComplete($where, $limit=1, $page=1, $start=null, $options=array())
    {
        $is_search  = (isset($options['search']) && ! empty($options['search']) && isset($options['user_id']) && ! empty($options['user_id'])) ? true : false;
        $id         = (is_numeric($where)) ? $where : null;
        $where      = (is_numeric($where)) ? 'users_to_marks.' . $this->id_column . " = '$where'" : trim($where);
        $page       = (is_numeric($page) && $page > 0) ? $page : 1;
        $limit      = ((is_numeric($limit) && $limit > 0) || $limit == 'all') ? $limit : 1;
        $start      = (! is_null($start)) ? $start : $limit * ($page - 1);
        $q_limit    = ($limit != 'all') ? ' LIMIT ' . $start . ',' . $limit : null;
        $sort       = (! empty($this->sort)) ? ' ORDER BY users_to_marks.' . $this->sort : null;
        $sort       = (! empty($sort) && $is_search === true) ? ' ORDER BY ' . $this->sort : $sort;
        $sort       = (stristr($sort, 'RAND()')) ? ' ORDER BY ' . $this->sort : $sort;
        $tag_id     = (isset($options['tag_id']) && ! empty($options['tag_id'])) ? " INNER JOIN user_marks_to_tags UMTT ON users_to_marks.users_to_mark_id = UMTT.users_to_mark_id AND UMTT.tag_id = '" . $options['tag_id'] . "'" : null;
        $url_key    = (isset($options['url_key']) && ! empty($options['url_key'])) ? " INNER JOIN marks M1 ON users_to_marks.mark_id = M1.mark_id AND M1.url_key = '" . $options['url_key'] . "'" : null;

        // Default fields
        $fields = "
            users_to_marks.users_to_mark_id AS mark_id, users_to_marks.mark_title as mark_title, users_to_marks.notes, users_to_marks.active, users_to_marks.created_on, users_to_marks.archived_on,
            marks.title, marks.url, marks.embed,
            GROUP_CONCAT(tags.tag_id SEPARATOR '" . $this->delimiter . "') AS tag_ids,
            GROUP_CONCAT(tags.name SEPARATOR '" . $this->delimiter . "') AS tag_names,
            GROUP_CONCAT(tags.slug SEPARATOR '" . $this->delimiter . "') AS tag_slugs,
            labels.label_id, labels.name AS label_name
        ";

        // Default joins
        $joins = "
            LEFT JOIN user_marks_to_tags ON users_to_marks.users_to_mark_id = user_marks_to_tags.users_to_mark_id
            LEFT JOIN labels ON users_to_marks.label_id = labels.label_id
            LEFT JOIN tags ON user_marks_to_tags.tag_id = tags.tag_id
        ";

        // Group By
        $group_by = " GROUP BY users_to_marks.users_to_mark_id";

        // Default Query
        $query = "SELECT " . $fields . " FROM users_to_marks" . $tag_id . $url_key . " INNER JOIN marks ON users_to_marks.mark_id = marks.mark_id " . $joins . " WHERE " . $where . $group_by;

        // Order By
        $order_by = " GROUP BY users_to_marks.users_to_mark_id";

        // Check for search
        if ($is_search === true) {
            $search_query = "
                SELECT " . $fields . "
                FROM marks
                INNER JOIN users_to_marks ON marks.mark_id = users_to_marks.mark_id AND users_to_marks.user_id = '" . $options['user_id'] . "' AND users_to_marks.active = '1' AND users_to_marks.archived_on " . $options['archive'] . ' ' . $joins . "
                WHERE marks.title LIKE '%" . $options['search'] . "%' OR marks.url LIKE '%" . $options['search'] . "%'" . $group_by;

            $query = '(' . $query . ') UNION DISTINCT (' . $search_query . ')';
        }

        // Add order by
        $query = $query . $sort . $q_limit;

        // Stop, query time
        $q     = $this->db->query('SET SESSION group_concat_max_len = 10000');
		$marks = $this->checkForHit($query);

        // Now format the group names and ids
        if ($this->num_rows > 0) {
            $marks = $this->format($marks);
            return ($limit == 1) ? $marks[0] : $marks;
        }

        return false;
    }

}