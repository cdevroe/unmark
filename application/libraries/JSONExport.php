<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * JSONExport Class
 *
 * Library that handles Unmark data export into JSON file
 * Export should be able to handle data regardless of number
 * of marks to export. So data are processed in packs, saved
 * to temporary file and served as attachment in the end.
 * JSON file format has to be well defined in order to import
 * it later line-by-line, without need to load it as a whole
 * into memory.
 *
 * @category Libraries
 */


class JSONExport {
    
    /**
     * Decides if every write to the file should result in flushing it
     * @var bool
     */
    const FLUSH_ON_WRITE = false;
    /**
     * JSON Start character
     * @var string
     */
    const JSON_START = "{\n";
    
    /**
     * Json export object header
     * @var string
     */
    const JSON_MAIN_OBJECT_START = "\"export\": {\n";
    /**
     * JSON END character
     * @var string
     */
    const JSON_END = "}";
    
    /**
     * JSON Array Start character
     * @var string
     */
    const JSON_ARRAY_START = "[\n";
    /**
     * JSON Array END character
     * @var string
     */
    const JSON_ARRAY_END = "]";
    
    /**
     * Current tab level
     * @var int
     */
    private $currentLevel = 0;
    
    /**
     * Temporary file handle to store data
     * @var unknown
     */
    private $tmpFile;
    
    /**
     * Flag indicating if JSON file was already started 
     * @var bool
     */
    private $started = false;
    
    /**
     * Flag indicating if JSON file was already ended
     * @var bool
     */
    private $ended = false;
    
    /**
     * Flag indicating if first mark in array was already written
     */
    private $firstMarkWritten = false;
    
    /**
     * Flag indicating if marks array was already started
     * @var bool
     */
    private $marksStarted = false;
    
    /**
     * Flag indicating if marks array was already ended
     * @var bool
     */
    private $marksEnded = false;
    
    /**
     * Flag indicating if first element under export was already written
     * @var unknown
     */
    private $firstEntryWritten = false;
    
    /**
     * Create tmp file to write into
     */
    public function __construct(){
        $this->createTmpFile();
    }
    
    /**
     * Remove file and release handle on destruction
     */
    public function __destruct(){
        $this->removeFile();
    }
    
    /**
     * Add mark to export file
     * @param stdobject $markData
     * @param string $first Is it first mark in array
     */
    public function addMark($markData){
        if(!$this->started){
            $this->startFile();
        }
        if(!$this->marksStarted){
            $this->startMarks();
        }
        if(!empty($markData)){
            $encodedJSON = json_encode($markData, JSON_FORCE_OBJECT);
            $this->writeLine($encodedJSON, $this->firstMarkWritten, $this->firstMarkWritten);
            $this->firstMarkWritten = $this->firstMarkWritten || true;
        }
    }
    
    /**
     * Add metadata key/value entry to export file
     * @param string $metaName Name of entry
     * @param string $metaValue Value of entry
     */
    public function addMeta($metaName, $metaValue){
        if(!$this->started){
            $this->startFile();
        }
        if($this->marksStarted && !$this->marksEnded){
            $this->endMarks();
        }
        if(!empty($metaName) && isset($metaValue)){
            $obj = new stdClass();
            $obj->{$metaName} = $metaValue;
            $encodedJSON = json_encode($metaName).": ".json_encode($metaValue);
            $this->writeLine($encodedJSON, $this->firstEntryWritten, $this->firstEntryWritten);
            $this->firstEntryWritten = $this->firstEntryWritten || true;
        }
    }
    
    /**
     * Starts marks array
     * @throws RuntimeException
     */
    private function startMarks(){
        if($this->marksEnded){
            throw new RuntimeException("Marks already ended - cannot start again");
        } else{
            $this->writeLine("\"marks\": ".self::JSON_ARRAY_START, $this->firstEntryWritten, $this->firstEntryWritten);
            $this->currentLevel++;
            $this->marksStarted = true;
            $this->firstEntryWritten = $this->firstEntryWritten || true;
        }
    }
    
    /**
     * Ends marks array
     * @throws RuntimeException
     */
    private function endMarks(){
        if(!$this->marksStarted){
            throw new RuntimeException("Marks not started - cannot end");
        } else if($this->marksEnded){
            throw new RuntimeException("Marks already ended - cannot end second time");
        } else{
            $this->currentLevel--;
            $this->writeLine(self::JSON_ARRAY_END);
            $this->marksEnded = true;
        }
    }
        
    /**
     * Ends writing to file and returns file handle for further processing
     * @return SplFileObject File handle
     */
    public function getFileForOutput(){
        // END file if it was not ended already
        if(!$this->ended){
            $this->endFile();
        }
        $this->tmpFile->fflush();
        $this->tmpFile->rewind();
        return $this->tmpFile;
    }
    
    /**
     * Start valid JSON file
     */
    private function startFile(){
        $this->writeLine(self::JSON_START, false);
        $this->currentLevel++;
        $this->writeLine(self::JSON_MAIN_OBJECT_START, false);
        $this->currentLevel++;
        $this->started = true;
    }
    
    /**
     * End valid JSON file
     */
    private function endFile(){
        if($this->marksStarted && !$this->marksEnded){
            $this->endMarks();
        }
        $this->currentLevel--;
        $this->writeLine(self::JSON_END, true);
        $this->currentLevel--;
        $this->writeLine(self::JSON_END, true);
        $this->ended = true;
    }

    /**
     * Creates file in temp directory to write into
     */
    private function createTmpFile(){
        $this->tmpFile = new SplFileObject(tempnam(sys_get_temp_dir(), 'exp'), "r+");
        $this->started = false;
        $this->ended = false;
        $this->marksStarted = false;
        $this->marksEnded = false;
        $this->firstMarkWritten = false;
        $this->firstEntryWritten = false;
    }
    
    /**
     * Writes given string to the file
     * @param string $string String to write
     * @param bool $addEOL If we want to add new line in the beginning
     * @param bool $addComma If we want to add comma in the beginning
     * @throws RuntimeException
     */
    private function writeLine($string, $addEOL = true, $addComma = false){
        if($this->ended === true){
            throw new RuntimeException('File was already ended - cannot write more data');
        }
        $finalString = '';        
        if($addComma){
            $finalString .= ',';
        }
        if($addEOL){
            $finalString .= PHP_EOL;
        }
        // Add indent
        for($i=0; $i<$this->currentLevel;$i++){
            $finalString .= "\t";
        }
        $finalString .= $string;
        $this->tmpFile->fwrite($finalString);
        if(self::FLUSH_ON_WRITE){
            $this->tmpFile->fflush();
        }
    }
        
    /**
     * Remove file and release handle on destruction
     */
    private function removeFile(){
        if(isset($this->tmpFile) && $this->tmpFile !== null){
            $filePath = $this->tmpFile->getRealPath();
            $this->tmpFile = null;
        }
        @unlink($filePath);
    }
}