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
        if ( !empty($_FILES) && ( !empty($_FILES['upload']) || !empty($_FILES['uploadReadability']) || !empty($_FILES['uploadHTML']) ) ) {
            $params = array('user_id' => $this->user_id);

            if ( !empty($_FILES['uploadReadability']) ) : // Process Readability file
              $this->_process_readability($_FILES['uploadReadability']);
            elseif ( !empty($_FILES['uploadHTML']) ) :
              $this->_process_html($_FILES['uploadHTML']);
            else :

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

            endif;
        } else{
            $this->data['success'] = false;
            $this->data['errors'] = formatErrors(100);
            $this->exceptional->createTrace(E_ERROR, 'No JSON file uploaded for import.', __FILE__, __LINE__);
        }

        $this->view('import/index', array('no_header' => true, 'no_footer' => true));

    }

    public function _process_html($file) {
      $this->CI =           &get_instance();
      $this->CI->load->library('Mark_Import', array('meta'=>array('export_version'=>1),'user_id'=>$this->user_id));
      $totalImported =      0;

      log_message( 'DEBUG', 'Importing bookmarks from an HTML file.' );

      // Get HTML
      $htmlSource =         file_get_contents($file['tmp_name']);

      // Parse HTML into DOMDocument
      $html =               new DOMDocument;
      $html->loadHTML($htmlSource);

      // Get all A tags
      $bookmarks =          $html->getElementsByTagName('a');

      // Look through links and create and import each
      foreach($bookmarks as $bookmark):

        // Added for Pocket
        // Pocket uses time_added
        $dateAdded = ( $bookmark->getAttribute('time_added') ) ? $bookmark->getAttribute('time_added') : $bookmark->getAttribute('add_date');

        $markObject =                 new stdClass();
        $markObject->title =          $bookmark->textContent;
        $markObject->url =            $bookmark->getAttribute('href');
        $markObject->embed =          '';
        $markObject->tags =           $bookmark->getAttribute('tags');
        $markObject->created_on =     date('Y-m-d h:m:s', $dateAdded);
        $markObject->archived_on =    null;
        $markObject->active =         1;

        $importResult =               $this->CI->mark_import->importMark($markObject);

        $totalImported++; // Increment total imported for stats

        // Reset Mark object
        unset($markObject);

      endforeach;

      log_message( 'DEBUG', 'Finished importing bookmarks from an HTML file.' );

      // Fake ish stats.
      $this->data['result'] = array(
        'total'=>$totalImported,
        'added'=>$totalImported,
        'skipped'=>0,
        'failed'=>0);

      // No check whatsoever here.
      // Should change this
      $this->data['success'] = true;

    }

    public function _process_readability($file) {
      $this->CI = & get_instance();
      $this->CI->load->library('Mark_Import', array('meta'=>array('export_version'=>1),'user_id'=>$this->user_id));

      $jsonReadability = file_get_contents($file['tmp_name']);
      $json = json_decode( $jsonReadability, true );

      $totalImported = 0;

      // Process file from Readability
      foreach ( $json['bookmarks'] as $bookmark ) :
        // Construct Mark object for Unmark
        $markObject = new stdClass();
        $markObject->title = $bookmark['article__title'];
        $markObject->url =   $bookmark['article__url'];
        $markObject->embed = '';
        $markObject->created_on = str_replace('T', ' ', $bookmark['date_added']);

        if ( isset($bookmark['archive']) && $bookmark['archive'] == 1 ) : // If archived
          $markObject->archived_on = str_replace('T', ' ', $bookmark['date_archived']);
          $markObject->active = 0;
        else : // If not archived
          $markObject->archived_on = null;
          $markObject->active = 1;
        endif;

        // Import this mark into Unmark
        $importResult = $this->CI->mark_import->importMark($markObject);

        $totalImported++;

        // Reset Mark object
        unset($markObject);

      endforeach;

      // Fake ish stats.
      $this->data['result'] = array(
        'total'=>$totalImported,
        'added'=>$totalImported,
        'skipped'=>0,
        'failed'=>0);

      // No check whatsoever here.
      // Shoudl change this
      $this->data['success'] = true;

      // The other method will load the view.

    }

}
