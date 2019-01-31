<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Hook to load the proper language array
 * @author cdevroe
 *
 */
class Unmark_Languages
{
    private $CI   = null;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Loads language array
     * Returns all phrases
     */
    public function loadLanguage()
    {
      $language_to_load =           $this->CI->config->item('language');
      $language_to_load =           ( !isset($language_to_load) || empty($language_to_load) ) ? 'english' : $language_to_load;
      $language_file_to_load =      APPPATH . 'language/' . $language_to_load . '.php';

      if ( file_exists($language_file_to_load) ) :
        include( APPPATH . 'language/' . $language_to_load . '.php' );
        $this->CI->config->set_item( 'phrases', $unmark_language ); // Load phrases into global config
        log_message('DEBUG', 'The "' . $language_to_load . '" language file has been loaded.');
      else :
        log_message('ERROR', 'The "' . $language_to_load . '" language file could not be found.');
      endif;

    }
}

/**
 * Loads a phrase
 * Accepts: Phrase (string), Phrase Plural (for backwards compatibilty) and number (0,1,2)
 * Returns: Prints singular or plural phrase if found
 * Error: If phrase not found it will log a message and respond with "Phrase not found."
 */
function unmark_phrase( $phrase, $phrase_plural='', $number=1 )
{
  // Load instance
  $CI =& get_instance();

  // Load phrases from config
  $phrases = $CI->config->item('phrases');

  //print_r($phrases);
  //exit;

  if ( !is_array($phrases) ) :
    return $phrase;
  endif;

  // Determine whether or not to use the plural phrase
  if ( $number == 0 || $number > 1 ) :
    $plural = 1;
  else :
    $plural = 0;
  endif;

  // Be sure language file contains phrase
  if ( array_key_exists( strtolower($phrase), $phrases ) ) :
    // See if language file has the singular or plural phrase, if not, default to singular
    if ( isset($phrases[strtolower($phrase)][$plural]) ) :
      return $phrases[strtolower($phrase)][$plural];
    else :
      return $phrases[strtolower($phrase)][0];
    endif;
  else : // Language file does not contain phrase, put something in Debug
    log_message('DEBUG', 'The phrase "' . $phrase . '" could not be found in the language file.');
    return $phrase;
  endif;
}
