<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Hook which loads language files for localized controllers
 * @author kip9
 *
 */
class Unmark_Localization
{
    private $CI   = null;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Load translations if controller is localized
     */
    public function loadLanguage()
    {
        if($this->CI->localized){
            $this->CI->lang->load($this->CI->router->fetch_class(), $this->CI->selected_language);
        }
    }
}
