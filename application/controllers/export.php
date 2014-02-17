<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends Plain_Controller
{
    
    /**
     * Number of DB records to retrieve with single query
     * @var int
     */
    const PAGE_SIZE = 100;
    
    /**
     * Export file version
     * @var int
     */
    const EXPORT_FILE_VERSION = 1;

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfLoggedOut();

        // If we can't find a user id, get them out of here
        if (! isset($this->user_id) || ! is_numeric($this->user_id)) {
            header('Location: /');
            exit;
        }

        // Set default success to false
        $this->data['success'] = false;

    }

    /**
     * Generate JSON export file for current user
     */
    public function index()
    {
        // Enable export library
        $this->load->library('JSONExport');
        // Add import version info
        $this->jsonexport->addMeta('export_version', self::EXPORT_FILE_VERSION);
        $this->jsonexport->addMeta('export_date', date('Y-m-d H:i:s'));
        // Retrieve user marks
        $this->load->model('users_to_marks_model', 'user_marks');
        $where = 'users_to_marks.user_id='. $this->user_id;
        $marksCount = $this->user_marks->count($where);
        // Number of marks
        $this->jsonexport->addMeta('marks_count', $marksCount);
        $pages = ceil((double) $marksCount / (double) self::PAGE_SIZE);
        // Get page of data
        for($curPage=1;$curPage<=$pages;$curPage++){
            $pageResults = $this->user_marks->readComplete($where, self::PAGE_SIZE, $curPage);
            // Add all retrieved marks
            if(is_array($pageResults)){
                foreach($pageResults as $key=>$singleMark){
                    $this->jsonexport->addMark($singleMark);
                }
            // Add single mark
            } else if(!empty($pageResults)){
                $this->jsonexport->addMark($pageResults);
            }
        }
        // Write the file as attachment
        $file = $this->jsonexport->getFileForOutput();
        $this->output->set_content_type('application/json');
        $this->output->set_header('Content-Disposition: attachment; filename=' . 'export.json');
        while (!$file->eof()) {
            $this->output->append_output($file->fgets());
        }
    }

}