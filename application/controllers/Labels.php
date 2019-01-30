<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Labels extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirectIfLoggedOut();
        $this->redirectIfWebView();

        // Load user marks model
        $this->load->model('labels_model', 'labels');
    }

    /*
        - Activate a single lable
        - URLS
            /label/activate/LABEL_ID
    */
    public function activate($label_id=0)
    {
        self::toggle($label_id, 1);
    }

    /*
        - Activate a single lable
        - URLS
            label/add

        // For normal labels (admins only)
         - name   : Optional
         - active : Optional : defaults to 1

        // For smart labels
         - url
         - active   : Optional : defaults to 1
         - label_id : Required : the static label_id to apply if rule is matched

        // Since some accounts will have admin flag set to 1
        // If adding a smart label to admin account, you must pass the admin=1 flag to create the smart label for the system, rather than the user
    */
    public function add()
    {

        // Declare options
        $options = array();

        // Figure type
        $type = (isset($this->clean->url)) ? 'smart' : 'label';

        // Figure user id
        if ($type == 'smart' && (parent::isAdmin() === false || ! isset($this->clean->admin) || empty($this->clean->admin))) {
            $options['user_id'] = $this->user_id;
        }

        // Figure the user where statement
        $user_where = (parent::isAdmin() === true && isset($this->clean->admin) && ! empty($this->clean->admin)) ? "labels.user_id IS NULL" : "labels.user_id = '" . $this->user_id . "'";

        // Figure what to check, slug or smart_key
        if ($type == 'smart') {
            $smart_info             = getSmartLabelInfo($this->db_clean->url);
            $options['domain']      = $smart_info['domain'];
            $options['path']        = $smart_info['path'];
            $options['smart_key']   = $smart_info['key'];

            if (isset($this->db_clean->label_id) && ! is_numeric($this->db_clean->label_id)) {
                $options['smart_label_id'] = $this->db_clean->label_id;
            }

            $total  = $this->labels->count($user_where . " AND labels.smart_key = '" . $options['smart_key'] . "'");

        }
        else {
            $options['name'] = (isset($this->clean->name) && ! empty($options['name'])) ? $options['name'] : null;
            $options['slug'] = (! empty($options['name'])) ? generateSlug($options['name']) : null;
            $total           = (! empty($slug)) ? $this->labels->count("labels.user_id IS NULL AND labels.slug = '" . $options['slug'] . "'") : 0;
        }

        // Figure if there was an active state passed
        if (isset($this->db_clean->active) && ($this->db_clean->active == 0 || $this->db_clean->active == 1)) {
            $options['active'] = $this->db_clean->active;
        }

        // If a record is found
        // Stop
        if ($total > 0) {
            $this->data['errors'] = formatErrors(34);
        }
        elseif ($type == 'label' && parent::isAdmin() === false) {
            $this->data['errors'] = formatErrors(37);
        }
        // Else, try and add it
        else {

            // Attempt to create the label
            $label = $this->labels->create($options);

            // If update failed, tell the user
            if ($label === false) {
                $this->data['errors'] = formatErrors(36);
            }
            // Return updated label
            else {
                $this->data['label'] = $label;
            }
        }

        // Figure view
        $this->figureView();
    }

    /*
        - Deactivate a single lable
        - URLS
            /api/label/deactivate/LABEL_ID
    */
    public function deactivate($label_id=0)
    {
        self::toggle($label_id, 0);
    }

   /*
        - Edit a single label
        - URLS
            /api/label/edit/LABEL_ID

        // For normal labels (admins only)
         - name   : Optional : If not sent, not updated
         - active : Optional : If not sent, not updated

        // For smart labels
         - domain   : Optional : If not sent, not updated
         - path     : Optional : If not sent, not updated
         - active   : Optional : If not sent, not updated
         - label_id : Required : the static label_id to apply if rule is matched

        // Since some accounts will have admin flag set to 1
        // If editing a smart label for admin account, you must pass the admin=1 flag to lookup the smart label for the system, rather than the user
        // Normal labels are always system only

        // Easier to use activate/deactivate for this but you can still use the edit method
    */
    public function edit($label_id=0)
    {

        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors(30);
        }
        else {

            // Set the columns that CAN be updated
            $types = array('label', 'smart');

            // Lookup label_id to get type
            $user_where = (parent::isAdmin() === true && isset($this->clean->admin) && ! empty($this->clean->admin)) ? "labels.user_id IS NULL" : "labels.user_id = '" . $this->user_id . "'";
            $where      = "(labels.user_id IS NULL AND labels.label_id= '" . $label_id . "' AND labels.smart_label_id IS NULL) OR (" . $user_where . " AND labels.label_id= '" . $label_id . "' AND labels.smart_label_id IS NOT NULL)";
            $label      = $this->labels->readComplete($where, 1);

            // If no type found, there was no label for this id/user combo
            if (! isset($label->type)) {
                $this->data['errors'] = formatErrors(31);
            }
            // If type == label and not admin, leave
            elseif ($label->type == 'label' && parent::isAdmin() === false) {
                $this->data['errors'] = formatErrors(37);
            }
            // If no type was found in above array, something was drastically wrong
            elseif (! array_key_exists($label->type, $types)) {
                $this->data['errors'] = formatErrors(33);
            }
            // Update label (if it doesn't exist)
            else {

                $options = array();
                $total   = 0;

                // Figure options per label type
                if ($label->type == 'smart') {
                    if (isset($this->db_clean->url) && ! empty($this->db_clean->url)) {
                        $smart_info             = getSmartLabelInfo($this->db_clean->url);
                        $options['domain']      = $smart_info['domain'];
                        $options['path']        = $smart_info['path'];
                        $options['smart_key']   = $smart_info['key'];
                        $total                  = $this->labels->count($user_where . " AND labels.smart_key = '" . $options['smart_key'] . "'");
                    }

                    // If label is sent, add to options
                    if (isset($this->db_clean->label_id) && ! is_numeric($this->db_clean->label_id)) {
                        $options['smart_label_id'] = $this->db_clean->label_id;
                    }
                }
                else {
                    if (isset($this->db_clean->name) && ! empty($this->db_clean->name)) {
                        $options['name'] = $this->db_clean->name;
                        $options['slug'] = generateSlug($options['name']);
                        $total           = $this->labels->count("labels.user_id IS NULL AND labels.slug = '" . $options['slug'] . "'");
                    }
                }

                // Figure if there was an active state passed
                if (isset($this->db_clean->active) && ($this->db_clean->active == 0 || $this->db_clean->active == 1)) {
                    $options['active'] = $this->db_clean->active;
                }


                // If no options, return error
                if (empty($options)) {
                    $this->data['errors'] = formatErrors(35);
                }
                // If label slug or smart key exists already
                // Error out
                elseif ($total > 0) {
                    $this->data['errors'] = formatErrors(34);
                }
                // Send update
                else {
                    $label = $this->labels->update($where, $options);

                    // If update failed, tell the user
                    if ($label === false) {
                        $this->data['errors'] = formatErrors(39);
                    }
                    // Return updated label
                    else {
                        $this->data['label'] = $label;
                    }
                }
            }

        }

        // Figure view
        $this->figureView();
    }

    /*
        - Start to return all labels that belong to user
        - Admins will see other labels that say user_id = null
        - applicable domains:
            /labels/smart(/PAGE)?  = only smart labels
            /labels/normal(/PAGE)? = only normal system level labels (admins only, non-admins get nothing)
            /labels(/PAGE)?        = all labels (non-admins won't see system level labels)

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
        $where = (! isset($this->db_clean->active) || $this->db_clean->active != 0) ? "labels.active = '1'" : "labels.active = '0'";

        // If $type != all, set a where
        if ($type != 'all') {
            $where .= ($type == 'normal') ? " AND labels.smart_label_id IS NULL " : " AND labels.smart_label_id IS NOT NULL ";
        }

        // Set user where
        // If admin account, get normal labels
        $user_where = "labels.user_id='". $this->user_id . "'";
        if (parent::isAdmin() === true) {
            $user_where .= " OR labels.user_id IS NULL";
        }

        // If requesting normal, give them
        if ($type == 'normal') {
            $user_where = "labels.user_id IS NULL";
            $this->labels->sort = 'labels.order ASC';
        }

        // Set final where
        $where = (empty($where)) ? $user_where : $where . ' AND (' . $user_where . ')';

        // Read the complete user marks records
        $labels = $this->labels->readComplete($where, $this->limit, $page);

        // If no labels, return error
        // Else return labels
        if ($labels === false) {
            $this->data['errors'] = formatErrors(32);
        }
        else {
            $this->data['labels'] = $labels;
            $this->data           = $this->labels->getTotals($where, $page, $this->limit, $this->data);
        }

        // Figure if web or API view
        $this->figureView();
    }

    /*
        - Get info for a single label
        - URLS
            /label/info/LABEL_ID
    */
    public function info($label_id=0)
    {
        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors(30);
        }
        else {
            $where = (parent::isAdmin() === true) ? "(labels.user_id IS NULL OR labels.user_id = '" . $this->user_id . "')" : "labels.user_id = '" . $this->user_id . "'";
            $label = $this->labels->readComplete($where . " AND labels.label_id= '" . $label_id . "'");
            if ($label === false) {
                $this->data['errors'] = formatErrors(31);
            }
            else {
                $this->data['label'] = $label;
            }
        }

        // Figure view
        $this->figureView();
    }

    private function toggle($label_id=0, $active=0)
    {
        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors(30);
        }
        else {
            $where = (parent::isAdmin() === true) ? "(labels.user_id IS NULL OR labels.user_id = '" . $this->user_id . "')" : "labels.user_id = '" . $this->user_id . "'";
            $label = $this->labels->update($where . " AND labels.label_id= '" . $label_id . "'", array('active' => $active));

            if ($label === false) {
                $this->data['errors'] = formatErrors(39);
            }
            else {
                $this->data['label'] = $label;
            }
        }

        // Figure view
        $this->figureView();
    }

}