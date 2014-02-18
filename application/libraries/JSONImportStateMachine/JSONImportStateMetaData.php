<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/JSONImportStateMachine/JSONImportStateInterface.php');
require_once(APPPATH.'libraries/JSONImportStateMachine/JSONImportStateMarks.php');

/**
 * JSON Import state for meta data key => value entries describing
 * exported file.
 * @author kip9
 *
 */
class JSONImportStateMetaData implements JSONImportStateInterface{
    
    private $importData;
    
    public function __construct($importData){
        $this->importData = $importData;
    }
    
    /**
     * (non-PHPdoc)
     * @see JSONImportStateInterface::processLine()
     */
    public function processLine($line){
        // Change state
        if(mb_ereg_match("\"marks\"\:[ ]*\[.*", $line)){
            return new JSONImportStateMarks($this->importData);
        } else{
            // Strip trailing comma and simulate JSON
            $jsonLine = '{' . mb_ereg_replace("\\,[ ]*$", '', $line) . '}';
            $decodedObject = json_decode($jsonLine);
            foreach($decodedObject as $key => $value){
                if(!isset($this->importData['meta'])){
                    $this->importData['meta'] = array();
                }
                $this->importData['meta'][$key] = $value;
            }
            return $this;
        }
    }
}