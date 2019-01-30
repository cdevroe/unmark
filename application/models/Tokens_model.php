<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tokens_model extends Plain_Model
{
    const DEFAULT_TOKEN_VALID_TIME_SECONDS = 86400; // 24 hours
    const TYPE_FORGOT_PASSWORD = 'FORGOT_PASSWORD';

	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->data_types = array(
            'token_id'    =>  'numeric',  // PrimaryKey auto increment ID
            'user_id'     =>  'numeric',  // User that token belongs to (may be null)
            'token_type'  =>  'string',   // Token type (ENUM)
            'token_value' =>  'string',   // Token 
            'created_on'  =>  'datetime', // Creation date
            'valid_until' =>  'datetime', // Expiration date
            'active'      =>  'bool',     // Active flag
            'used_on'     =>  'datetime', // Used date
        );
    }

    /**
     * Creates new token
     * @param array $options Token data
     * @return Ambigous <boolean, mixed, array>
     */
    public function create($options=array())
    {
        $required  = array('token_type');
        $valid     = validate($options, $this->data_types, $required);

        // Make sure all the options are valid
        if ($valid === true) {

            // If you made it this far, we need to add the record to the DB
            $options['created_on']  = date("Y-m-d H:i:s");
            $confExpireTime = $this->config->item('forgot_password_token_valid_seconds');
            $options['valid_until'] = date("Y-m-d H:i:s", time() + ( empty ($confExpireTime) ? self::DEFAULT_TOKEN_VALID_TIME_SECONDS : $confExpireTime));
            // Generate random token
            $this->load->library('uuid');
            do{
                $options['token_value'] =  $this->uuid->v4(true) . $this->uuid->v4(true);
                $total = $this->count("token_value = '" . $options['token_value'] . "'");
            } while ($total > 0); // This should never happen according to UUID generation

            // Add record
            $q   = $this->db->insert_string('tokens', $options);
            $res = $this->db->query($q);

            // Check for errors
            $this->sendException();

            if ($res === true) {
                $token_id = $this->db->insert_id();
                return $this->read($token_id);
            }
            else {
                return formatErrors('Eek this is akward, sorry. Something went wrong. Please try again.');
            }
        }

        return formatErrors($valid);
    }
    
    /**
     * Checks if given tokenValue exists and is still valid for use
     * @param string|object $tokenValue Token value (string) or retrieved token object 
     * @return boolean
     */
    public function isValid($tokenValue){
        if(!empty($tokenValue)){
            $nowTs = time();
            $now = date('Y-m-d H:i:s', $nowTs);
            // Token object passed - check fields
            if(is_object($tokenValue)){
                $validUntilTs = strtotime($tokenValue->valid_until);
                return $tokenValue->active == 1 && $validUntilTs >= $nowTs;
            // Token string passed - check in DB
            } else{
                return $this->count("token_value = '$tokenValue' and active='1' and valid_until >= '$now'") > 0;
            }
        } else{
            return false;
        }
    }
    
    /**
     * Mark selected token as used
     * @param string $tokenValue
     * @return boolean If token was updated
     */
    public function useToken($tokenValue){
       if(!empty($tokenValue)){
           $now = date('Y-m-d H:i:s');
           $updatedToken = $this->update("token_value = '$tokenValue' and active='1'", array('active' => '0', 'used_on' => $now));
           return ((!empty($updatedToken) && $updatedToken->active == 1) ? true : false);
       } else{
           return false;
       } 
    }
    

}