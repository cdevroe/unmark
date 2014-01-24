<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Marks_To_Tags_model extends Plain_Model
{

    public $sort = 'user_marks_to_tag_id DESC';


    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        // Set data types
        $this->data_types = array(
            'tag_id'           => 'numeric',
            'users_to_mark_id' => 'numeric',
            'user_id'          => 'numeric'
        );

    }

    public function create($options=array())
    {

        $valid  = validate($options, $this->data_types, array('tag_id', 'user_id', 'users_to_mark_id'));

        // Make sure all the options are valid
        if ($valid === true) {


            // See if this record already exists
            $total = $this->count("tag_id = '" . $options['tag_id'] . "' AND user_id = '" . $options['user_id'] . "' AND users_to_mark_id = '" . $options['users_to_mark_id'] . "'");

            // If not, add it
            if ($total < 1) {
                $q    = $this->db->insert_string($this->table, $options);
                $res  = $this->db->query($q);

                // Check for errors
                $this->sendException();

                // Return true or false
                return $res;

            }

            // If already exists, just return it
            return true;

        }

        return false;
    }

    // We don't really delete much from database
    // The tags to marks need to be removed though
    public function delete($options=array())
    {

        $valid  = validate($options, $this->data_types, array('tag_id', 'user_id', 'users_to_mark_id'));

        // Make sure all the options are valid
        if ($valid === true) {

            // Set where
            $where = "tag_id = '" . $options['tag_id'] . "' AND user_id = '" . $options['user_id'] . "'' AND users_to_mark_id = '" . $options['users_to_mark_id'] . "'";

            // See if this record already exists
            $total = $this->count($where);

            // If not, add it
            if ($total > 0) {
                $res  = $this->db->query("DELETE FROM `user_marks_to_tags` WHERE " . $where);

                // Check for errors
                $this->sendException();

                // Return true or false
                return $res;

            }

            // If record doesn't exists, just return true
            return true;

        }

        return false;
    }

    public function getMostRecent($user_id, $limit=10)
    {
        $q = $this->db->query("
            SELECT
            user_marks_to_tags.tag_id,
            tags.name
            FROM `user_marks_to_tags`
            LEFT JOIN `tags` ON user_marks_to_tags.tag_id = tags.tag_id
            GROUP BY user_marks_to_tags.tag_id ORDER BY user_marks_to_tags.user_marks_to_tag_id DESC LIMIT " . $limit
        );

        // If errors report
        $this->sendException();

        // Return that ish
        return ($q->num_rows() > 0) ? $q->result() : false;
    }

    public function getPopular($user_id, $limit=10)
    {
        $q = $this->db->query("
            SELECT
            user_marks_to_tags.tag_id,
            COUNT(user_marks_to_tags.tag_id) as total,
            tags.name
            FROM `user_marks_to_tags`
            LEFT JOIN `tags` ON user_marks_to_tags.tag_id = tags.tag_id
            GROUP BY user_marks_to_tags.tag_id ORDER BY total DESC LIMIT " . $limit
        );

        // If errors report
        $this->sendException();

        // Return that ish
        return ($q->num_rows() > 0) ? $q->result() : false;
    }

}