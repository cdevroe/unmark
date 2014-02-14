<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * JSONImport Class
 *
 * Library that handles nilai data import into JSON file
 * Design goal is ability to process huge data sets regardless of their size.
 * So instead of loading data into memory and parsing using `json_decode`,
 * we're processing input file line by line wherever possible. For that
 * the file needs to be properly formatted (@see JSONExport)
 *
 * @category Libraries
 */


class JSONImport {
    
    public function importFile($fileData){
        
    }
}