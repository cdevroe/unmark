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
// COMMENTED OUT
/*$hook['pre_controller_method'][''] = array(
	'class'    => 'Unmark_Localization',
    'function' => 'loadLanguage',
    'filename' => 'Unmark_Localization.php',
    'filepath' => 'hooks',
);*/

// Loads language translation array
$hook['pre_controller_method'][''] = array(
		'class'    => 'Unmark_languages',
    'function' => 'loadLanguage',
    'filename' => 'Unmark_Languages.php',
    'filepath' => 'hooks',
);


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
