<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (APPPATH . 'libraries/JSONImportStateMachine/JSONImportStateInterface.php');

/**
 * JSON Import state inside marks array.
 * Every line from now on should contain saved mark object
 * JSON representation with dependencies.
 * @author kip9
 *
 */
class JSONImportStateMarks implements JSONImportStateInterface
{

    /**
     * Flag deciding if we want to return import details
     * @var boolean
     */
    const RETURN_DETAILS = true;

    /**
     * Flag deciding if we want to return warning and error
     * entries for details.
     * If RETURN_DETAILS is set to false, this flag is not
     * taken into account
     * @var boolean
     */
    const RETURN_DETAILS_ERRORS_ONLY = true;

    /*
     * Result keys
     */
    /**
     * Number of marks imported
     * @var string
     */
    const RESULT_ADDED = 'added';

    /**
     * Number of marks already existing in the system
     * @var string
     */
    const RESULT_SKIPPED = 'skipped';

    /**
     * Number of marks not imported due to errors
     * @var string
     */
    const RESULT_FAILED = 'failed';

    /**
     * Import status and data
     *
     * @var array
     */
    private $importData;

    /**
     * Cache for 'unlabeled' label id used to mark entries with unknown label
     *
     * @var int
     */
    private $unlabeled_label_id = null;

    public function __construct($importData)
    {
        $this->importData = $importData;
        $this->importData['result'] = array(
            self::RESULT_ADDED => 0,
            self::RESULT_SKIPPED => 0,
            self::RESULT_FAILED => 0,
            'total' => 0
        );
        $this->CI = & get_instance();
        $this->CI->load->library('Mark_Import', $importData);
    }

    /**
     * (non-PHPdoc)
     *
     * @see JSONImportStateInterface::processLine()
     */
    public function processLine($line)
    {
        // Finished
        if (mb_ereg_match("^[ ]*\][ ]*$", $line)) {
            return $this->importData;
        } else {
            // Strip trailing comma and simulate JSON
            $jsonLine = mb_ereg_replace("\\,[ ]*$", '', $line);
            $decodedMark = json_decode($jsonLine);
            $importResult = $this->CI->mark_import->importMark($decodedMark);
            $this->importData['result']['total'] ++;
            $this->importData['result'][$importResult['result']] ++;
            if (self::RETURN_DETAILS && (! self::RETURN_DETAILS_ERRORS_ONLY || $importResult['result'] === self::RESULT_FAILED || ! empty($importResult['warnings']))) {
                $this->importData['details'][$decodedMark->mark_id] = $importResult;
            }
            return $this;
        }
    }

}
