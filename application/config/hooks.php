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

if ( function_exists( 'bindtextdomain' ) ) :

	// Loads language translation files
	$hook['pre_controller_method'][''] = array(
		'class'    => 'Unmark_Localization',
	    'function' => 'loadLanguage',
	    'filename' => 'Unmark_Localization.php',
	    'filepath' => 'hooks',
	);

else :

	// Replace _() with simple function to return value.
	function _($v){
	    return $v;
	}

	// Unmark's own unmark_ngettext that simply returns the multiple value.
	function unmark_ngettext( $singular, $plural, $number ) {

		if ( function_exists( 'ngettext' ) ) : // Doubtful to ever be true, but just in case? I don't know.
			return ngettext( $singular, $plural, $number );
		else :
			if ( $number == 0 || $number > 1 ) :
				return $plural;
			else :
				return $singular;
			endif;
		endif;

	}

endif; // end if bindtextdomain()


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
