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
            // Trying to set locale
            $lang = $this->CI->selected_language.'.UTF-8';
            $setLocaleOut = setlocale(LC_ALL, $lang);
            if($setLocaleOut !== false){
                // Locale setting success
                $lang_path = FCPATH.APPPATH.'language/locales';
                bindtextdomain('unmark', $lang_path);
                textdomain('unmark');
            } else {
                // Locale setting failed - report error
                $errMsg = 'Setting language to '.$lang.' failed - no such locale';
                log_message('DEBUG', $errMsg);
                $this->CI->exceptional->createTrace(E_WARNING, $errMsg, __FILE__, __LINE__, array(
                    'language'  => $lang
                ));
            }
        }
    }
}
