<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * JSONImport Class
 *
 * Library that handles Unmark data import from JSON file
 * Design goal is ability to process huge data sets regardless of their size.
 * So instead of loading data into memory and parsing using `json_decode`,
 * we're processing input file line by line wherever possible. For that
 * the file needs to be properly formatted (@see JSONExport)
 *
 * @category Libraries
 */

require_once (APPPATH . 'libraries/JSONImportStateMachine/JSONImportStateStart.php');

class JSONImport
{

    /**
     * File type
     * @var string
     */
    const TYPE_JSON = 'application/json';

    /**
     * Imported file handle
     * @var SplFileObject
     */
    private $tmpFile;

    /**
     * Parameters passed to json library
     * Has to contain user_id
     * @var array
     */
    private $params;

    /**
     * Creates JSON Importer library
     * Initializes CodeIgniter and saves passed params for later
     * @param array $params
     * @throws RuntimeException When no user_id is passed in params
     */
    public function __construct($params)
    {
        if (empty($params['user_id'])) {
            throw new RuntimeException('User_id was not passed for import. Cancelling');
        }
        $this->params = $params;
        $CI = & get_instance();
    }

    /**
     * Imports given JSON file
     * @param string $filePath Path to a JSON file with data to import
     * @return array Array with import output - metadata, status, warnings and errors
     */
    public function importFile($filePath)
    {
        $this->tmpFile = new SplFileObject($filePath, "r");
        $curState = new JSONImportStateStart(array(
            'meta' => array(),
            'result' => array(),
            'user_id' => $this->params['user_id']
        ));
        $result = array('success' => false);
        $stepResult = false;
        while (! $this->tmpFile->eof()) {
            $curLine = trim($this->tmpFile->fgets());
            $stepResult = $curState->processLine($curLine);
            // Returns next state for processing
            if (! is_array($stepResult) ) {
                $curState = $stepResult;
            // Returned array with results
            } else {
                $result = $stepResult;
                $result['success'] = true;
                break;
            }
        }
        return $result;
    }

    /**
     * Checks if passed file is valid 
     * @param array $uploadedFile Uploaded file POST information
     * @return multitype:array|boolean True on success, array with error information otherwise
     */
    public function validateUpload($uploadedFile)
    {
        if (empty($uploadedFile) || $uploadedFile['size'] <= 0 || $uploadedFile['error'] != 0) {
            return formatErrors(100);
        }
        if ($uploadedFile['type'] !== self::TYPE_JSON) {
            return formatErrors(101);
        }
        return true;
    }
}