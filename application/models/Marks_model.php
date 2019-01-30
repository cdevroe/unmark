<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marks_model extends Plain_Model
{

    public $sort = 'created_on DESC';


    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        // Set data types
        $this->data_types = array(
            'title'       =>  'string',
            'url'         =>  'url',
            'url_key'     =>  'md5',
            'embed'       =>  'string',
            'created_on'  =>  'datetime'
        );

    }

    public function create($options=array())
    {
        return $this->validateAndSave($options, true);
    }
    
    private function validateAndSave($options, $overwriteCreatedOn){
        $valid  = validate($options, $this->data_types, array('title', 'url'));
        
        // Make sure all the options are valid
        if ($valid === true) {
        
            // Make sure url doesn't already exist
            $md5  = md5($options['url']);
            $mark = $this->read("url_key = '" . $md5 . "'", 1, 1);
        
            // If not found, add it
            if (! isset($mark->mark_id)) {
                if($overwriteCreatedOn || empty($options['created_on'])){
                    $options['created_on'] = date('Y-m-d H:i:s');
                }
                $options['url_key']    = $md5;
                $q   = $this->db->insert_string('marks', $options);
                $res = $this->db->query($q);
        
                // Check for errors
                $this->sendException();
        
                // Return mark_id
                if ($res === true) {
                    $mark_id = $this->db->insert_id();
                    return $this->read($mark_id);
                }
        
                return false;
            }
        
            // If already exists, just return it
            return $mark;
        }
        
        return formatErrors($valid);
    }
    
    /**
     * Import existing object and save to DB
     * @param array $options
     */
    public function import($options){
        return $this->validateAndSave($options, false);
    }

}