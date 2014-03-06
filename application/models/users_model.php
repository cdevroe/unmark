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

        if (! isValid($options['email'], 'email')) {
            return formatErrors(604);
        }

        if (! isValid($options['password'], 'password')) {
            return formatErrors(602);
        }


        // Make sure email does not exist already
        $total = $this->count("email = '" . $options['email'] . "'");
        if ($total > 0) {
            return formatErrors(603);
        }

        // If you made it this far, we need to add the record to the DB
        $options['password']    = generateHash($options['password']);
        $options['created_on']  = date("Y-m-d H:i:s");

        // Create user token
        do {
            $options['user_token'] = generateToken(30) . md5(time());
            $total = $this->count("user_token = '" . $options['user_token'] . "'");

            // If by some freak chance there is a collision
            // Report it
            if ($total > 0) {
                log_message('debug', 'User token collision detected on key of `' . $options['user_token'] . '`');
            }

        } while ($total > 0);

        // Add record
        $q   = $this->db->insert_string('users', $options);
        $res = $this->db->query($q);

        // Check for errors
        $this->sendException();

        if ($res === true) {
            $user_id = $this->db->insert_id();
            return $this->read($user_id);
        }
        else {
            return formatErrors(500);
        }
    }

}