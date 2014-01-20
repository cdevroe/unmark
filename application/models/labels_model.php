<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Labels_model extends Plain_Model
{

    public $sort = 'created_on DESC';


    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        // Set data types
        $this->data_types = array(
            'smart_label_id'  =>  'numeric',
            'user_id'         =>  'numeric',
            'name'            =>  'string',
            'domain'          =>  'domain',
            'path'            =>  'string',
            'smart_key'       => 'md5',
            'active'          => 'bool',
            'created_on'      => 'datetime'
        );

    }

    public function create($options=array())
    {
        $smart_label = (isset($options['smart_label']) && ! empty($options['smart_label'])) ? true : false;

        // If a smart label, set the required fields
        if ($smart_label === true) {
            $required = array('smart_label_id', 'user_id', 'name', 'domain');
        }
        else {
            $required = array('name');
        }

        $valid  = validate($options, $this->data_types, $required);

        // Make sure all the options are valid
        if ($valid === true) {

            // If smart label, create MD5 hash of domain and path
            if ($smart_label == true) {
                $md5                  = md5($options['domain'] . $options['path']);
                $where                = "labels.smart_key = '" . $md5 . "' AND labels.user_id = '" . $options['user_id'] . "'";
                $options['smart_key'] = $md5;
            }
            else {
                $where = "labels.name = '" . $options['name'] . "' labels.user_id IS NULL";
            }

            // See if this record already exists
            $total = $this->count($where);

            // If not, add it
            if ($total < 1) {
                $options['created_on'] = date('Y-m-d H:i:s');
                $q                     = $this->db->insert_string($this->table, $options);
                $res                   = $this->db->query($q);

                // Check for errors
                $this->sendException();

                // If good, return full label
                if ($res === true) {
                    $label_id = $this->db->insert_id();
                    return $this->read($label_id);
                }

                // Else return error
                return $this->formatErrors('Label could not be added. Please try again.');
            }

        }

        return $this->formatErrors($valid);
    }

}