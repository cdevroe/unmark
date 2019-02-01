<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Hooks extends CI_Hooks
{
    protected $in_progress = NULL;
    
    function __construct()
    {
        parent::__construct();
        
        log_message('info', 'Plain_Hooks Class Constructed');

        self::_initialize();
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the Hooks Preferences
     *
     * @access  private
     * @return  void
     */
    function _initialize()
    {

        $CFG =& load_class('Config', 'core');

        // If hooks are not enabled in the config file
        // there is nothing else to do

        if ($CFG->item('enable_hooks') == FALSE)
        {
            return;
        }

        // Grab the "hooks" definition file.
        // If there are no hooks, we're done.

        // Get all hooks
        $hooks = array();
        if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/hooks.php'))
        {
            include(APPPATH.'config/'.ENVIRONMENT.'/hooks.php');
            $hooks['core'] = (isset($hook)) ? $hook : array();
            unset($hook);
        }
        elseif (is_file(APPPATH.'config/hooks.php'))
        {
            include(APPPATH.'config/hooks.php');
            $hooks['core'] = (isset($hook)) ? $hook : array();
            unset($hook);
        }

        // Look for custom hooks
        if (defined('ENVIRONMENT') AND is_file(CUSTOMPATH.'config/'.ENVIRONMENT.'/hooks.php'))
        {
            include(CUSTOMPATH.'config/'.ENVIRONMENT.'/hooks.php');
            $hooks['custom'] = (isset($hook)) ? $hook : array();
            unset($hook);
        }
        elseif (is_file(CUSTOMPATH.'config/hooks.php'))
        {
            include(CUSTOMPATH.'config/hooks.php');
            $hooks['custom'] = (isset($hook)) ? $hook : array();
            unset($hook);
        }


        // Combine them all together
        $hook = array();
        foreach ($hooks as $type => $arr) {
            foreach ($arr as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $kk => $vv) {
                        if (! array_key_exists($k, $hook)) {
                            $hook[$k] = array();
                        }
                        array_push($hook[$k], $vv);
                    }
                }
                else {
                    if (! array_key_exists($k, $hook)) {
                        $hook[$k] = array();
                    }
                    array_push($hook[$k], $v);
                }
            }
        }


        if ( ! isset($hook) OR ! is_array($hook))
        {
            return;
        }

        $this->hooks =& $hook;
        $this->enabled = TRUE;
        log_message('info', 'Plain_Hooks Class Initialized');
    }

    /**
     * Run Hook
     *
     * Runs a particular hook
     *
     * @access  private
     * @param   array   the hook details
     * @return  bool
     */
    function _run_hook($data)
    {
        if ( ! is_array($data))
        {
            return FALSE;
        }

        // -----------------------------------
        // Safety - Prevents run-away loops
        // -----------------------------------

        // If the script being called happens to have the same
        // hook call within it a loop can happen

        if ($this->in_progress == TRUE)
        {
            return;
        }

        // -----------------------------------
        // Set file path
        // -----------------------------------

        if ( ! isset($data['filepath']) OR ! isset($data['filename']))
        {
            return FALSE;
        }

        $filepaths = array(APPPATH.$data['filepath'].'/'.$data['filename'], CUSTOMPATH.$data['filepath'].'/'.$data['filename']);

        foreach ($filepaths as $path) {
            if (file_exists($path)) {
                $filepath = $path;
                break;
            }
        }

        if (! isset($filepath) || empty($filepath))
        {
            return FALSE;
        }

        // -----------------------------------
        // Set class/function name
        // -----------------------------------

        $class      = FALSE;
        $function   = FALSE;
        $params     = '';

        if (isset($data['class']) AND $data['class'] != '')
        {
            $class = $data['class'];
        }

        if (isset($data['function']))
        {
            $function = $data['function'];
        }

        if (isset($data['params']))
        {
            $params = $data['params'];
        }

        if ($class === FALSE AND $function === FALSE)
        {
            return FALSE;
        }

        // -----------------------------------
        // Set the in_progress flag
        // -----------------------------------

        $this->in_progress = TRUE;

        // -----------------------------------
        // Call the requested class and/or function
        // -----------------------------------

        if ($class !== FALSE)
        {
            if ( ! class_exists($class))
            {
                require($filepath);
            }

            $HOOK = new $class;
            $HOOK->$function($params);
        }
        else
        {
            if ( ! function_exists($function))
            {
                require($filepath);
            }

            $function($params);
        }

        $this->in_progress = FALSE;
        return TRUE;
    }
}