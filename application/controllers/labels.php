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
            /api/labels/smart(/PAGE)?  = only smart labels
            /api/labels/normal(/PAGE)? = only normal system level labels (admins only, non-admins get nothing)
            /api/labels(/PAGE)?        = all labels (non-admins won't see system level labels)

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

    /*
        - Activate a single lable
        - URLS
            /api/label/add
    */
    public function add()
    {

    }

     /*
        - Activate a single lable
        - URLS
            /api/label/activate/LABEL_ID
    */
    public function activate($label_id=0)
    {
        self::toggle($label_id, 1);
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
         - name   : Optional
         - active : Optional

        // For smart labels
         - domain : Optional
         - path   : Optional
         - active : Optional

        // Easier to use activate/deactivate for this but you can still use the edit metho
    */
    public function edit($label_id=0)
    {

        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors('No `label_id` was found.');
        }
        else {
            $types = array(
                'label' => array('name', 'active'),
                'smart' => array('domain', 'path', 'active')
            );

            // Lookup label_id to get type
            $where = (parent::isAdmin() === true) ? "(labels.user_id IS NULL OR labels.user_id = '" . $this->user_id . "')" : "labels.user_id = '" . $this->user_id . "'";
            $where .= " AND labels.label_id= '" . $label_id . "'";
            $label = $this->labels->readComplete($where, 1);

            if (! isset($label->type)) {
                $this->data['errors'] = formatErrors('No label found using `' . $label_id . '` for your account.');
            }
            elseif (! array_key_exists($label->type, $types)) {
                $this->data['errors'] = formatErrors('This type of label `' . $label->type . '` could not be found.');
            }
            else {
                $options = array();
                foreach ($types[$label->type] as $k => $column) {
                    if (isset($this->db_clean->{$column})) {
                        $options[$column] = $this->db_clean->{$column};
                    }
                }

                if (empty($options)) {
                    $this->data['errors'] = formatErrors('No options found to update for this label.');
                }
                else {
                    $label = $this->labels->update($where, $options);
                    if ($label === false) {
                        $this->data['errors'] = formatErrors('Label could not be updated.');
                    }
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
        - Get info for a single label
        - URLS
            /api/label/info/LABEL_ID
    */
    public function info($label_id=0)
    {
        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors('No `label_id` was found.');
        }
        else {
            $where = (parent::isAdmin() === true) ? "(labels.user_id IS NULL OR labels.user_id = '" . $this->user_id . "')" : "labels.user_id = '" . $this->user_id . "'";
            $this->data['label'] = $this->labels->readComplete($where . " AND labels.label_id= '" . $label_id . "'");
        }

        // Figure view
        $this->figureView();
    }

    private function toggle($label_id=0, $active=0)
    {
        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors('No `label_id` was found.');
        }
        else {
            $where = (parent::isAdmin() === true) ? "(labels.user_id IS NULL OR labels.user_id = '" . $this->user_id . "')" : "labels.user_id = '" . $this->user_id . "'";
            $this->data['label'] = $this->labels->update($where . " AND labels.label_id= '" . $label_id . "'", array('active' => $active));
        }

        // Figure view
        $this->figureView();
    }

}