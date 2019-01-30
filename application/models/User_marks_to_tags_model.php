<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Marks_To_Tags_model extends Plain_Model
{

    //public $sort = 'user_marks_to_tag_id DESC';
    public $sort = 'tag_id DESC';


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
            $tag = $this->read("tag_id = '" . $options['tag_id'] . "' AND user_id = '" . $options['user_id'] . "' AND users_to_mark_id = '" . $options['users_to_mark_id'] . "'", 1, 1, 'tag_id');

            // If not, add it
            if (! isset($tag->tag_id)) {
                $q    = $this->db->insert_string($this->table, $options);
                $res  = $this->db->query($q);

                // Check for errors
                $this->sendException();

                if ($res === true) {
                    $mark_to_tag_id = $this->db->insert_id();
                    return $this->read($mark_to_tag_id);
                }

                // Return true or false
                return false;

            }

            // If already exists, just return it
            return $tag;

        }

        return formatErrors($valid);
    }

    // We don't really delete much from database
    // The tags to marks need to be removed though
    public function delete($where)
    {
        // Send request
        $res  = $this->db->query("DELETE FROM `user_marks_to_tags` WHERE " . $where);

        // Check for errors
        $this->sendException();

        // Return true or false
        return $res;
    }

    public function getMostRecent($user_id, $limit=10)
    {
        return self::getTagList($user_id, $limit, 'recent');
    }

    public function getPopular($user_id, $limit=10)
    {
        return self::getTagList($user_id, $limit, 'popular');
    }

    private function getTagList($user_id, $limit=10, $type)
    {
        $order_by = array(
            'popular' => 'total',
            'recent'  => 'user_marks_to_tags.tag_id'
        );

        //'recent'  => 'user_marks_to_tags.user_marks_to_tag_id'

        $order = (array_key_exists($type, $order_by)) ? $order_by[$type] : 'total';

        $tags = $this->checkForHit("
            SELECT
            user_marks_to_tags.tag_id,
            COUNT(user_marks_to_tags.tag_id) as total,
            tags.name, tags.slug
            FROM `user_marks_to_tags`
            INNER JOIN `users_to_marks` ON users_to_marks.users_to_mark_id = user_marks_to_tags.users_to_mark_id AND users_to_marks.archived_on IS NULL AND users_to_marks.active = '1'
            LEFT JOIN `tags` ON user_marks_to_tags.tag_id = tags.tag_id
            WHERE user_marks_to_tags.user_id = '" . $user_id . "'
            GROUP BY user_marks_to_tags.tag_id ORDER BY " . $order . " DESC LIMIT " . $limit
        );

        return ($this->num_rows > 0) ? $tags : false;
    }

}
