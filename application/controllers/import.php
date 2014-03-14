<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Import external nilai marks controller
 * @author kip9
 *
 */
class Import extends Plain_Controller
{

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

    public function index()
    {
        if(!empty($_FILES) && !empty($_FILES['upload'])){
            $params = array('user_id' => $this->user_id);
            $this->load->library('JSONImport', $params);
            $uploadedFile = $_FILES['upload'];
            $validationResult = $this->jsonimport->validateUpload($uploadedFile);
            if($validationResult !== true){
                $this->data['errors'] = $validationResult;
                $data = array();
                foreach ($validationResult as $k => $v) {
                    $data['validation_error_' . $k] = $v;
                }
                $this->exceptional->createTrace(E_ERROR, 'JSON Import Issue', __FILE__, __LINE__, $data);
            } else{
                $importResult = $this->jsonimport->importFile($uploadedFile['tmp_name']);
                $this->data = $importResult;
            }
        } else{
            $this->data['success'] = false;
            $this->data['errors'] = formatErrors(100);
            $this->exceptional->createTrace(E_ERROR, 'No JSON file uploaded for import.', __FILE__, __LINE__);
        }
        $this->view('import/index', array('no_header' => true, 'no_footer' => true));

    }

}