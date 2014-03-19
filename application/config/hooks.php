<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// Loads language translation files
$hook['pre_controller_method'][''] = array(
	'class'    => 'Unmark_Localization',
    'function' => 'loadLanguage',
    'filename' => 'Unmark_Localization.php',
    'filepath' => 'hooks', 
);


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */