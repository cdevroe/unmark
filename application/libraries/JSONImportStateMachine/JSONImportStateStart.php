<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/JSONImportStateMachine/JSONImportStateInterface.php');
require_once(APPPATH.'libraries/JSONImportStateMachine/JSONImportStateMetaData.php');

class JSONImportStateStart implements JSONImportStateInterface{
    
    private $importData;
    
    public function __construct($importData){
        $this->importData = $importData;
    }
    
    public function processLine($line){
        // Change state
        if(mb_ereg_match("\"export\"\:[ ]*\{.*", $line)){
            return new JSONImportStateMetaData($this->importData);            
        } else{
            return $this;
        }
    }
}