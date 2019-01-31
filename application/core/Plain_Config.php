<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Config extends CI_Config
{

    public function __construct()
    {
        parent::__construct();
        log_message('info', 'Plain_Config Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Load Config File
     *
     * @access  public
     * @param   string  the config file name
     * @param   boolean  if configuration values should be loaded into their own section
     * @param   boolean  true if errors should just return false, false if an error message should be displayed
     * @return  boolean if the file was loaded correctly
     */
    function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        // Call default config loader
        $load = parent::load($file, $use_sections, true);

        // Now check for any custom configs
        $file = (empty($file)) ? 'config' : $file;
        $ext  = pathinfo($file, PATHINFO_EXTENSION);
        $ext  = (empty($ext)) ? '.php' : '';
        $file = $file . $ext;

        // Set the custom locations
        $locations = array();
        if (defined('ENVIRONMENT')) {
            array_push($locations, CUSTOMPATH . 'config/' . ENVIRONMENT . '/' . $file);
        }
        array_push($locations, CUSTOMPATH . 'config/' . $file);


        // Loop thru the locations
        // If found, load the file
        // Loop thru configs in there
        // If value is array, loop again to apply to avoid overwriting any existing configs (IE: adding a  new error code)
        foreach ($locations as $file) {
            if (file_exists($file)) {
                $load = true;
                include_once $file;

                if (isset($config) && is_array($config)) {
                    foreach ($config as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $kk => $vv) {
                                $this->config[$k][$kk] = $vv;
                            }
                        }
                        else {
                            $this->config[$k] = $v;
                        }
                    }
                    unset($config);
                }
                break;
            }
        }

        // Return load
        return $load;
    }

}