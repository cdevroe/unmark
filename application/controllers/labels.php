<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Labels extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirectIfLoggedOut();
        $this->redirectIfNotAPI();

        // Load user marks model
        $this->load->model('labels_model', 'labels');
    }

    // Start to return all labels that belong to user
    // Admins will see other labels that say user_id = null
    public function index($page=1)
    {
        // Figure the correct starting page
        $page = (! is_numeric($page) || $page < 0) ? 1 : $page;

        // Set where
        // If admin account, get system labels
        $where = "labels.user_id='". $this->user_id . "'";
        if (parent::isAdmin() === true) {
            $where .= " OR labels.user_id IS NULL";
        }

        // Get current page, total pages and total records
        $this->data = $this->labels->getTotals($where, $page, $this->limit, $this->data);

        // Read the complete user marks records
        $this->data['labels'] = $this->labels->readComplete($where, $this->limit, $page);

        // Figure if web or API view
        $this->figureView();
    }

    // Add a new label (smart or regular)
    public function add()
    {

    }

    // Add a new label (smart or regular)
    public function activate($label_id=0)
    {

    }

    // Add a new label (smart or regular)
    public function deactivate($label_id=0)
    {

    }

    // Edit an existing label
    public function edit($label_id=0)
    {

    }

    // Lookup info for a label
    public function info($label_id=0)
    {

    }

}