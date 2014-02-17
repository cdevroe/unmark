<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

interface JSONImportStateInterface{
    
    public function processLine($line);
    
}