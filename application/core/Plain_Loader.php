<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Loader extends CI_Loader
{
    public function __construct()
    {
        parent::__construct();

        // Add custom path information for libraries, helpers, models and views
        log_message('info', 'Plain_Loader Class Initialized');
        array_unshift($this->_ci_library_paths, CUSTOMPATH);
        array_unshift($this->_ci_helper_paths, CUSTOMPATH);
        array_unshift($this->_ci_model_paths, CUSTOMPATH);
        $this->_ci_view_paths = array(
            CUSTOMPATH . 'views/' => true,
            APPPATH . 'views/'     => true
        );
    }

    // Autoload shiz
    private function autoload()
    {
        // Set default autoload array
        $autoload = array();

        // Load default autoload files
        if (defined('ENVIRONMENT') && file_exists(APPPATH . 'config/' . ENVIRONMENT . '/autoload.php')) {
            include APPPATH . 'config/' . ENVIRONMENT . '/autoload.php';
        }
        else {
            include APPPATH . 'config/autoload.php';
        }

        // Set any default autoload
        // Reset autoload array
        $default_autoload = $autoload;
        $autoload         = array();

        // Load any custom autoloaders
        if (defined('ENVIRONMENT') && file_exists(CUSTOMPATH . 'config/' . ENVIRONMENT . '/autoload.php')) {
            include CUSTOMPATH . 'config/' . ENVIRONMENT . '/autoload.php';
        }
        elseif (file_exists(CUSTOMPATH . 'config/autoload.php')) {
            include CUSTOMPATH . 'config/autoload.php';
        }

        // Set custom to autoload
        // and autoload back to default
        $custom_autoload = $autoload;
        $autoload        = $default_autoload;

        // Loop thru defaults and see if custom exists
        // If so merge them
        foreach ($autoload as $k => $arr) {
            if (isset($custom_autoload[$k])) {
                $autoload[$k] = array_merge($autoload[$k], $custom_autoload[$k]);
            }
        }

        // Unset unused vars
        unset($custom_autoload);
        unset($default_autoload);

        // Autoload packages
        if (isset($autoload['packages']))
        {
            foreach ($autoload['packages'] as $package_path)
            {
                $this->add_package_path($package_path);
            }
        }

        // Load any custom config file
        if (count($autoload['config']) > 0)
        {
            $CI =& get_instance();
            foreach ($autoload['config'] as $key => $val)
            {
                $CI->config->load($val);
            }
        }

        // Autoload helpers and languages
        foreach (array('helper', 'language') as $type)
        {
            if (isset($autoload[$type]) AND count($autoload[$type]) > 0)
            {
                $this->$type($autoload[$type]);
            }
        }

        // A little tweak to remain backward compatible
        // The $autoload['core'] item was deprecated
        if ( ! isset($autoload['libraries']) AND isset($autoload['core']))
        {
            $autoload['libraries'] = $autoload['core'];
        }

        // Load libraries
        if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0)
        {
            // Load the database driver.
            if (in_array('database', $autoload['libraries']))
            {
                $this->database();
                $autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
            }

            // Load all other libraries
            foreach ($autoload['libraries'] as $item)
            {
                $this->library($item);
            }
        }

        // Autoload models
        if (isset($autoload['model']))
        {
            $this->model($autoload['model']);
        }


    }

    public function initialize()
    {
        $this->_ci_classes      = array();
        $this->_ci_loaded_files = array();
        $this->_ci_models       = array();
        $this->_base_classes    =& is_loaded();

        self::autoload();
        //$this->_ci_autoloader();

        return $this;
    }
}