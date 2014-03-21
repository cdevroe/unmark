<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exceptional {

    public function __construct()
    {
        $this->setHandlers();
    }

    public function createTrace($type=null, $message=null, $file=null, $line=null, $custom_data=array())
    {
        if (empty($type)) {
            $e = error_get_last();
            if (! empty($e)) {
                $message = $e['message'];
                $file    = $e['file'];
                $type    = $e['type'];
                $line    = $e['line'];
            }
        }

        if (! empty($type)) {
            $levels = array(
                E_ERROR             =>  'Error',
                E_WARNING           =>  'Warning',
                E_PARSE             =>  'Parsing Error',
                E_NOTICE            =>  'Notice',
                E_CORE_ERROR        =>  'Core Error',
                E_CORE_WARNING      =>  'Core Warning',
                E_COMPILE_ERROR     =>  'Compile Error',
                E_COMPILE_WARNING   =>  'Compile Warning',
                E_USER_ERROR        =>  'User Error',
                E_USER_WARNING      =>  'User Warning',
                E_USER_NOTICE       =>  'User Notice',
                E_STRICT            =>  'Runtime Notice'
            );

            // Set error type
            $type = (isset($levels[$type])) ? $levels[$type] : 'Unknown';

            // Fetch router
            $router =& load_class('Router', 'core');

            // Set up the trace file (1 level is better than none)
            $trace                = array();
            $trace[0]             = array();
            $trace[0]['function'] = $router->fetch_method();
            $trace[0]['class']    = $router->fetch_class();
            $trace[0]['type']     = $type;
            $trace[0]['args']     = array();
            $trace[0]['file']     = $file;
            $trace[0]['line']     = $line;

            // Send to airbrake
            self::sendToTracker($type . ': ' . $message, $type, $trace, $custom_data);
        }
    }

    public function logException($e)
    {
        // Add file and line number to first entry, PHP doesn't do this, derp!
        $trace = $e->getTrace();
        if (isset($trace)) {

            // Remove any args from CodeIgniter
            foreach ($trace as $k => $arr) {
                if (isset($arr['file']) && stristr($arr['file'], '/system')) {
                    $trace[$k]['args'] = array();
                }
            }

            // Fix first entry with current file and line number of error
            $trace[0]['file'] = $e->getFile();
            $trace[0]['line'] = $e->getLine();
        }

        self::sendToTracker('Exception: ' . $e->getMessage(), 'Exception', $trace);
    }

    public function sendToTracker($message, $type, $backtrace, $custom_data=array(), $exception_object=false)
    {
        // Only fire off exception if the user-agent is not a bot
        $bots = array('bot', 'spider', 'crawl');
        $ok = true;
        $agent = (isset($_SERVER['HTTP_USER_AGENT']) && ! empty($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : 'bot';
        foreach ($bots as $word) {
            if (stristr($agent, $word)) {
                $ok = false;
                break;
            }
        }

        // If ok, fire it off
        if ($ok === true) {

            // Get current instance
            $CI =& get_instance();

            // Get current user if applicable
            $CI->load->library('session');
            $user = $CI->session->userdata('user');
            $user = (empty($user)) ? array() : $user;

            // Hack because on shutdown errors we don't have complete access to all CI vars
            // Only have access to essentials
            // So we include the old way
            $env = (defined('ENVIRONMENT')) ? ENVIRONMENT : '';
            $params = array(
                'message'     => $message,
                'type'        => $type,
                'backtrace'   => $backtrace,
                'custom_data' => $custom_data,
                'enviornment' => $env,
                'user'        => $user
            );

            // If raw exception object is passed, send it so user can do whatever they want
            if (is_object($exception_object)) {
                $params['exception_object'] = $exception_object;
            }

            // Send it off
            if (CUSTOM_ERROR_TRACKING === true) {
                $CI->load->library('Error_Tracking', $params);
            }
            else {
                $params['backtrace'] = (isset($params['backtrace'][0]['file'])) ? $params['backtrace'][0]['file'] : '';
                log_message('error', print_r($params, true));
            }
        }
    }

    public function setHandlers()
    {
        set_exception_handler(array($this, 'logException'));
        register_shutdown_function(array($this, 'createTrace'));
    }

}