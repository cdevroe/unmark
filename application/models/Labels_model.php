<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Labels_model extends Plain_Model
{

    public $sort = 'created_on ASC';


    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        // Set data types
        $this->data_types = array(
            'smart_label_id'  => 'numeric',
            'user_id'         => 'numeric',
            'name'            => 'string',
            'domain'          => 'domain',
            'path'            => 'string',
            'smart_key'       => 'md5',
            'active'          => 'bool',
            'slug'            => 'string',
            'created_on'      => 'datetime'
        );

        // Set a different read method
        $this->read_method = 'readComplete';
    }

    public function create($options=array())
    {
        $smart_label = (isset($options['domain'])) ? true : false;

        // If a smart label, set the required fields
        if ($smart_label === true) {
            $required = array('smart_label_id', 'domain', 'smart_key');
        }
        else {
            $required = array('name', 'slug');
        }

        $valid = validate($options, $this->data_types, $required);

        // Make sure all the options are valid
        if ($valid === true) {

            // If not, add it
            $options['created_on'] = date('Y-m-d H:i:s');
            $q                     = $this->db->insert_string($this->table, $options);
            $res                   = $this->db->query($q);

            // Check for errors
            $this->sendException();

            // If good, return full label
            if ($res === true) {
                $cache_key = (isset($options['user_id'])) ? $this->cache_id . $options['user_id'] . '-*' : $this->cache_id . 'labels-*';
                $this->removeCacheKey($cache_key);
                $label_id = $this->db->insert_id();
                return self::readComplete($label_id);
            }

            // Else return error
            return false;
        }

        return formatErrors($valid);
    }

    private function formatResults($labels)
    {
        foreach ($labels as $k => $label) {
            $labels[$k]->type = (empty($label->smart_label_id)) ? 'label' : 'smart';

            // Create different information sets for label vs. smart_label
            if ($labels[$k]->type =='smart') {
                $labels[$k]->settings         = new stdClass;
                $labels[$k]->settings->domain = $labels[$k]->smart_label_domain;
                $labels[$k]->settings->path   = $labels[$k]->smart_label_path;

                $labels[$k]->settings->label        = new stdClass;
                $labels[$k]->settings->label->name  = $labels[$k]->smart_label_name;
                $labels[$k]->settings->label->slug  = $labels[$k]->smart_label_slug;
                $labels[$k]->settings->label->id    = $labels[$k]->smart_label_id;

                // Unset some shiz
                unset($labels[$k]->name);
                unset($labels[$k]->slug);
            }

            // Unset all smart_label keys
            unset($labels[$k]->smart_label_id);
            unset($labels[$k]->smart_label_domain);
            unset($labels[$k]->smart_label_path);
            unset($labels[$k]->smart_label_name);
            unset($labels[$k]->smart_label_slug);
        }

        return $labels;
    }

    public function getSystemLabels($type='label')
    {
        //$this->sort = 'order DESC';
        $and = ($type == 'label') ? ' AND labels.smart_key IS NULL' : ' AND labels.smart_key IS NOT NULL';
        $and = ($type == 'all') ? '' : $and;
        return self::readComplete('labels.user_id IS NULL' . $and, 'all');
    }

    public function readComplete($where, $limit=1, $page=1, $start=null)
    {
        $id         = (is_numeric($where)) ? $where : null;
        $where      = (is_numeric($where)) ? $this->table . '.' . $this->id_column . " = '$where'" : trim($where);
        $page       = (is_numeric($page) && $page > 0) ? $page : 1;
        $limit      = ((is_numeric($limit) && $limit > 0) || $limit == 'all') ? $limit : 1;

        if ( is_numeric($limit)) {
            $start      = (! is_null($start)) ? $start : $limit * ($page - 1);
        }

        $q_limit    = ($limit != 'all') ? ' LIMIT ' . $start . ',' . $limit : null;
        $sort       = (! empty($this->sort)) ? ' ORDER BY l.' . $this->sort : null;
        $sort       = (stristr($this->sort, '.')) ? ' ORDER BY ' . $this->sort : null;

        // Check for cache hit
        $labels = $this->checkForHit("
            SELECT
            labels.label_id, labels.smart_label_id, labels.name, labels.slug, labels.order, labels.domain AS smart_label_domain, labels.path AS smart_label_path, labels.active,
            l.name AS smart_label_name, l.slug AS smart_label_slug
            FROM labels
            LEFT JOIN labels AS l ON labels.smart_label_id = l.label_id
            WHERE " . $where . " GROUP BY " . $this->table . '.' . $this->id_column . $sort . $q_limit
        );

        // Now format the group names and ids
        if ($this->num_rows > 0) {
            $labels = $this->formatResults($labels);
            return ($limit == 1) ? $labels[0] : $labels;
        }

        return false;
    }

}