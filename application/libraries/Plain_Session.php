<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

require_once(BASEPATH.'/libraries/Session.php');

/**
 * Session Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Sessions
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/sessions.html
 */
class Plain_Session extends CI_Session {

	public $sess_encrypt_cookie	     = TRUE; // Overwritten default value to TRUE
	public $sess_cookie_name         = 'plain_session'; // Overwritten default value to TRUE
	public $plain_sess_storage       = 'files';
	public $plain_sess_memcache_addr = 'localhost';

	/**
	 * Session Constructor
	 *
	 * The constructor runs the session routines automatically
	 * whenever the class is instantiated.
	 */
	public function __construct($params = array())
	{
		log_message('debug', "Plain Session Class Initialized");

		// Set the super object to a local variable for use throughout the class
		$this->CI =& get_instance();

		// Set all the session preferences, which can either be set
		// manually via the $params array above or via the config file
		foreach (array('plain_sess_storage','plain_sess_memcache_addr','sess_encrypt_cookie', 'sess_expiration', 'sess_expire_on_close', 'sess_match_ip', 'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 'cookie_secure', 'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key)
		{
			$this->$key = (isset($params[$key])) ? $params[$key] : $this->CI->config->item($key);
		}

		if ($this->encryption_key == '')
		{
			show_error('In order to use the Plain Session class you are required to set an encryption key in your config file.');
		}

		// Load the string helper so we can use the strip_slashes() function
		$this->CI->load->helper('string');

		// Do we need encryption? If so, load the encryption class
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$this->CI->load->library('encrypt');
		}

		// Set the "now" time.  Can either be GMT or server time, based on the
		// config prefs.  We use this to set the "last activity" time
		$this->now = $this->_get_time();

		// Set the session length. If the session expiration is
		// set to zero we'll set the expiration two years from now.
		if ($this->sess_expiration == 0)
		{
			$this->sess_expiration = (60*60*24*365*2);
		}

		// Set the cookie name
		$this->sess_cookie_name = $this->cookie_prefix.$this->sess_cookie_name;

		// Initialize session
		$this->_start_session();

		// Run the Session routine. If a session doesn't exist we'll
		// create a new one.  If it does, we'll update it.
		if ( ! $this->sess_read())
		{
			$this->sess_create();
		}
		else
		{
			$this->sess_update();
		}

		// Delete 'old' flashdata (from last request)
		$this->_flashdata_sweep();

		// Mark all new flashdata as old (data will be deleted before next request)
		$this->_flashdata_mark();

		// Delete expired sessions if necessary
		$this->_sess_gc();

		log_message('debug', "Session routines successfully run");
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current session data if it exists
	 *
	 * @access	public
	 * @return	bool
	 */
	function sess_read()
	{
		// No session data?  Goodbye cruel world!...
		if (!isset($_SESSION['ci_data']) || ($session = $_SESSION['ci_data']) === FALSE)
		{
			log_message('debug', 'Session data not found.');
			return FALSE;
		}

		// Decrypt the cookie data
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$session = $this->CI->encrypt->decode($session);
		}
		else
		{
			// encryption was not used, so we need to check the md5 hash
			$hash	 = substr($session, strlen($session)-32); // get last 32 chars
			$session = substr($session, 0, strlen($session)-32);

			// Does the md5 hash match?  This is to prevent manipulation of session data in userspace
			if ($hash !==  md5($session.$this->encryption_key))
			{
				$this->CI->exceptional->createTrace(E_ERROR, 'The session cookie data did not match what was expected. This could be a possible hacking attempt.', __FILE__, __LINE__);
				$this->sess_destroy();
				return FALSE;
			}
		}

		// Unserialize the session array
		$session = $this->_unserialize($session);

		// Is the session data we unserialized an array with the correct format?
		if ( ! is_array($session) OR ! isset($session['session_id']) OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) OR ! isset($session['last_activity']))
		{
		    $this->_log('Invalid session data format', 'debug');
			$this->sess_destroy();
			return FALSE;
		}

		// Is the session current?
		if (($session['last_activity'] + $this->sess_expiration) < $this->now)
		{
		    $this->_log('Session data expired', 'info');
			$this->sess_destroy();
			return FALSE;
		}

		// Does the IP Match?
		if ($this->sess_match_ip == TRUE AND $session['ip_address'] != $this->CI->input->ip_address())
		{
		    $this->_log('Not matching user IP - possible session hijacking', 'info');
			session_regenerate_id(false);
			return FALSE;
		}

		// Does the User Agent Match?
		if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr($this->CI->input->user_agent(), 0, 120)))
		{
		    $this->_log('Not matching user agent - possible session hijacking', 'info');
		    session_regenerate_id(false);
			return FALSE;
		}

		// Session is valid!
		$this->userdata = $session;

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Write the session data
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_write()
	{
    	$this->_save_into_session();
	}

	// --------------------------------------------------------------------

	/**
	 * Create a new session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_create()
	{
	    // Looking for existing session
	    $sessionId = session_id();
	    if(empty($sessionId)){
	        // No session exists - need to create new one
	        $this->_start_session();
	        $sessionId = session_id();
	    }
	    $this->userdata = array(
							'session_id'	=> $sessionId,
							'ip_address'	=> $this->CI->input->ip_address(),
							'user_agent'	=> substr($this->CI->input->user_agent(), 0, 120),
							'last_activity'	=> $this->now,
							'user_data'		=> ''
							);


		// Write the cookie
		$this->_save_into_session();
	}

	// --------------------------------------------------------------------

	/**
	 * Update an existing session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_update($bypass_timecheck=false)
	{
		// We only update the session every five minutes by default
		if ($bypass_timecheck === false && ($this->CI->input->is_ajax_request() OR ($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now))
		{
			return;
		}

		// Save the old session id so we know which record to
		// update in the database if we need it
		$old_sessid = session_id();

		// Turn it into a hash
		$new_sessid = session_regenerate_id(true);

		// Update the session data in the session data array
		$this->userdata['session_id'] = $new_sessid;
		$this->userdata['last_activity'] = $this->now;
		// _save_into_session() will handle this for us if we aren't using database sessions
		// by pushing all userdata to the cookie.
		$cookie_data = NULL;

		// Write the cookie
		$this->_save_into_session($cookie_data);
	}

	// --------------------------------------------------------------------

	/**
	 * Destroy the current session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_destroy()
	{
	    // Remove session but do not regenerate ID
	    session_destroy();

		// Kill session data
		$this->userdata = array();
	}

	// --------------------------------------------------------------------

	/**
	 * Write the session using underlying mechanism
	 *
	 * @access	public
	 * @return	void
	 */
	protected function _save_into_session($cookie_data = NULL)
	{
		if (is_null($cookie_data))
		{
			$cookie_data = $this->userdata;
		}


		// Serialize the userdata for the cookie
		$cookie_data = $this->_serialize($cookie_data);

		if ($this->sess_encrypt_cookie == TRUE)
		{
			$cookie_data = $this->CI->encrypt->encode($cookie_data);
		}
		else
		{
			// if encryption is not used, we provide an md5 hash to prevent userside tampering
			$cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
		}

		$_SESSION['ci_data'] = $cookie_data;

	}

	// --------------------------------------------------------------------

	/**
	 * Set session cookie parameters and invoke session_start to generate session data
	 */
	protected function _start_session(){
	    // Are we using a database?  If so, load it
	    if ($this->plain_sess_storage == 'database')
	    {
	        require_once(APPPATH.'/libraries/CIDatabaseSessionHandler.php');
	        $dbSessionHandler = new CIDatabaseSessionHandler();
	        session_set_save_handler(
                array($dbSessionHandler, 'open'),
                array($dbSessionHandler, 'close'),
                array($dbSessionHandler, 'read'),
                array($dbSessionHandler, 'write'),
                array($dbSessionHandler, 'destroy'),
                array($dbSessionHandler, 'gc')
            );
            register_shutdown_function('session_write_close');
	    } else if($this->plain_sess_storage == 'memcached'){
	        // Memcache session storage
	        ini_set('session.save_handler', 'memcached');
	        ini_set('session.save_path', $this->plain_sess_memcache_addr);
	    }
	    if( session_id() === '') {
	       session_name($this->sess_cookie_name);
	       session_set_cookie_params($this->sess_expiration, $this->cookie_path, $this->cookie_domain, $this->cookie_secure);
	       session_start();
	    }
	}

	// --------------------------------------------------------------------

	/**
	 * Garbage collection
	 *
	 * This deletes expired session rows from database
	 * if the probability percentage is met
	 *
	 * @access	public
	 * @return	void
	 */
	function _sess_gc()
	{
	    // FIXME kip9 New _gc logic
	    if ($this->sess_use_database != TRUE)
	    {
	        return;
	    }

	    srand(time());
	    if ((rand() % 100) < $this->gc_probability)
	    {
	        $expire = $this->now - $this->sess_expiration;

	        $this->CI->db->where("last_activity < {$expire}");
	        $this->CI->db->delete($this->sess_table_name);

	        log_message('debug', 'Session garbage collection performed.');
	    }
	}

	private function _log($message, $level = 'debug'){
	    @log_message($level, '[PlainSession] '.$message);
	}

}
// END Session Class

/* End of file Session.php */
/* Location: ./system/libraries/Session.php */
