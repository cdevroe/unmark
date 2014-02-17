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

require_once(APPPATH.'libraries/JSONImportStateMachine/JSONImportStateStart.php');

class JSONImport {
    
    const TYPE_JSON = 'application/json';
    
    private $tmpFile;
    
    private $jsonParser;
    
    private $params;
    
    public function __construct($params){
        if(empty($params['user_id'])){
            throw new RuntimeException('User_id was not passed for import. Cancelling');
        }
        $this->params = $params;
        $CI =& get_instance();
    }
    
    public function importFile($filePath){
        $this->tmpFile = new SplFileObject($filePath, "r");
        $curState = new JSONImportStateStart(array('meta'=>array(), 'result'=>array(), 'user_id'=>$this->params['user_id']));
        $result = false;
        while(!$this->tmpFile->eof()){
            $curLine = trim($this->tmpFile->fgets());
            $result = $curState->processLine($curLine);
            // Exit condition
            if(!is_array($result)){
                $curState = $result;
            } else{
                if($result['result']['success'] == $result['meta']['marks_count']){
                    print('Success <br /><br />');
                } else{
                    print('There were errors <br /><br />');
                }
                print($result['result']['success']. ' of ' . $result['meta']['marks_count']. ' marks imported <br /><br />');
                break;
            }
        }
        die;
    }
    
    public function validateUpload($uploadedFile){
        if(empty($uploadedFile) || $uploadedFile['size']<=0 || $uploadedFile['error'] != 0){
            return formatErrors(100);
        }
        if($uploadedFile['type'] !== self::TYPE_JSON){
            return formatErrors(101);
        }
        return true;
    }
    
    // Handler methods
    public function arrayStart($value, $property){
        print '<pre>';
        print_r('arrayStart v=' . $value . ' p='.$property );
        print '</pre>';
    }
    
    public function arrayEnd($value, $property){
        print '<pre>';
        print_r('arrayEnd v=' . $value . ' p='.$property );
        print '</pre>';
    }
    
    public function objStart($value, $property){
        print '<pre>';
        print_r('objStart v=' . $value . ' p='.$property );
        print '</pre>';
    }
    
    public function objEnd($value, $property){
        print '<pre>';
        print_r('objEnd v=' . $value . ' p='.$property );
        print '</pre>';
    }
    
    public function property($value, $property){
        print '<pre>';
        print_r('property v=' . $value . ' p='.$property );
        print '</pre>';
    }
    
    public function scalar($value, $property){
        print '<pre>';        
        print_r('scalar v=' . $value . ' p='.$property );
        print '</pre>';
    }
    
    
    
}