<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * State machine interface for Unmark.it JSON file importing
 * 
 * @author kip9
 *        
 */
interface JSONImportStateInterface
{

    /**
     * Processes single line of well formatted
     * JSON file with marks from other system
     * 
     * @param string $line
     *            Trimmed line of import file
     * @return mixed: array|JSONImportStateInterface Array with results or next step to use in import process
     */
    public function processLine($line);
}