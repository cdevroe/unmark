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

    /*
        - Start to return all labels that belong to user
        - Admins will see other labels that say user_id = null
        - applicable domains:
            /api/labels?/smart(/PAGE)?  = only smart labels
            /api/labels?/normal(/PAGE)? = only normal system level labels (admins only, non-admins get nothing)
            /api/labels?(/PAGE)?        = all labels (non-admins won't see system level labels)

        - The user will only get their labels
        - Admins will get their labels and system level labels
    */
    public function index($v1=0, $v2=null)
    {

        // Figure the type
        $type = (! is_numeric($v1)) ? $v1 : 'all';

        // Figure the correct starting page
        $page = (! is_numeric($v1) || $v1 < 0) ? 1 : $v1;
        $page = (is_numeric($v2) || $v2 > 0) ? $v2 : $page;

        // Set the where
        $where = null;

        // If $type != all, set a where
        if ($type != 'all') {
            $where = ($type == 'normal') ? "labels.smart_label_id IS NULL " : "labels.smart_label_id IS NOT NULL ";
        }

        // Set user where
        // If admin account, get normal labels
        $user_where = "labels.user_id='". $this->user_id . "'";
        if (parent::isAdmin() === true) {
            $user_where .= " OR labels.user_id IS NULL";
        }

        // Set final where
        $where = (empty($where)) ? $user_where : $where . ' AND (' . $user_where . ')';

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
        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors('No `label_id` was found.');
        }
        else {
            $where = (parent::isAdmin() === true) ? '' : "labels.user_id = '" . $this->user_id . "' AND ";
            $this->data['label'] = $this->labels->update($where . "labels.label_id= '" . $label_id . "'", array('active' => 1));
        }

        // Figure view
        $this->figureView();
    }

    // Add a new label (smart or regular)
    public function deactivate($label_id=0)
    {
        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors('No `label_id` was found.');
        }
        else {
            $where = (parent::isAdmin() === true) ? '' : "labels.user_id = '" . $this->user_id . "' AND ";
            $this->data['label'] = $this->labels->update($where . "labels.label_id= '" . $label_id . "'", array('active' => 0));
        }

        // Figure view
        $this->figureView();
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