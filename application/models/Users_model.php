<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends Plain_Model
{

    public $sort = 'user_id DESC';
    
    private $CSV_FIELDS = array(
        'email'          => 'Email',
        'customer_id'    => 'Stripe Customer ID',
        'active'         => 'Active',
        'admin'          => 'Admin',
        'plan_id'        => 'Stripe plan id',
        'created_on'     => 'Registered On',
        'last_updated'   => 'Last Updated',
        'expires_on'     => 'Plan expiration',
        'marks_count'    => 'Active marks',
        'over_limit'     => 'Over limit'
    );
    
    /**
     * Field separator
     * @var unknown
    */
    const CSV_SEPARATOR_CHAR = ",";
    /**
     * Text enclosing character
     * @var unknown
     */
    const CSV_ENCLOSURE_CHAR = '"';
    /**
     * Escape character
     * @var unknown
     */
    const CSV_ESCAPE_CHAR = "\\";

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

        //print_r($options);
        //exit;

        // Add record
        $q   = $this->db->insert_string('users', $options);
        $res = $this->db->query($q);


        // Check for errors
        $this->sendException();

        if ($res === true) {
            $user_id = $this->db->insert_id();

            // Return the entire user obj
            return $this->read($user_id);
        }
        else {
            return formatErrors(500);
        }
    }
    
    /**
     * Returns CSV headers line (column captions)
     * @return string CSV header row
     */
    public function printCsvHeader()
    {
        foreach($this->CSV_FIELDS as $field=>$caption){
            $escapedFields[$field] = $this->escapeAndWrapForCsv($caption);
        }
        return implode(self::CSV_SEPARATOR_CHAR, $escapedFields) . PHP_EOL;
    }
    
    /**
     * Returns CSV entry for given object
     * @param account $accountData Object with account data
     * @return string CSV row
     */
    public function exportToCsvArray($accountData)
    {
        foreach($this->CSV_FIELDS as $field => $caption){
            $escapedValues[$field] = $this->escapeAndWrapForCsv($accountData->{$field});
        }
        return implode(self::CSV_SEPARATOR_CHAR, $escapedValues) . PHP_EOL;
    }
    
    /**
     * Escapes text and encloses text for CSV
     * @param string $field Text to escape
     * @return string Escaped and enclosed text
     */
    private function escapeAndWrapForCsv($field)
    {
        return is_numeric($field) ? $field : self::CSV_ENCLOSURE_CHAR . (empty($field) ? "" : str_replace(self::CSV_ENCLOSURE_CHAR, self::CSV_ESCAPE_CHAR.self::CSV_ENCLOSURE_CHAR, $field)) . self::CSV_ENCLOSURE_CHAR;
    }

}