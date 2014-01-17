<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends Plain_Model
{

    public $sort = 'user_id DESC';

	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->data_types = array(
            'user_id'     =>  'numeric',
            'email'       =>  'email',
            'password'    =>  'password',
            'active'      =>  'bool',
            'admin'       =>  'bool',
            'created_on'  =>  'datetime'
        );
    }

    public function create($options=array())
    {
        $required  = array('email','password');
        $valid     = validate($options, $this->data_types, $required);

        // Make sure all the options are valid
        if ($valid === true) {

            // Make sure email does not exist already
            $total = $this->count("email = '" . $options['email'] . "'");
            if ($total > 0) {
                $this->formatErrors('This email address already exists in the system.');
            }

            // If you made it this far, we need to add the record to the DB
            $options['password']    = generateHash($options['password']);
            $options['created_on']  = date("Y-m-d H:i:s");
            $q   = $this->db->insert_string('users', $options);
            $res = $this->db->query($q);

            // Check for errors
            $this->sendException();

            if ($res === true) {
                $user_id = $this->db->insert_id();
                return $this->read($user_id);
            }
            else {
                $this->formatErrors('Eek this is akward, sorry. Something went wrong. Please try again.');
            }
        }

        return $this->formatErrors($valid);
    }

}