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
        $labels = $this->labels->readComplete($where, $this->limit, $page);

        // If no labels, return error
        // Else return labels
        if ($labels === false) {
            $this->data['errors'] = formatErrors('No labels found for your account.', 21);
        }
        else {
            $this->data['labels'] = $labels;
        }

        // Figure if web or API view
        $this->figureView();
    }

    /*
        - Activate a single lable
        - URLS
            /api/label/add

        // For normal labels (admins only)
         - name   : Optional
         - active : Optional : defaults to 1

        // For smart labels
         - domain   : Required
         - path     : Optional : defaults to null
         - active   : Optional : defaults to 1
         - label_id : Required : the static label_id to apply if rule is matched

        // Since some accounts will have admin flag set to 1
        // If adding a smart label to admin account, you must pass the admin=1 flag to create the smart label for the system, rather than the user
    */
    public function add()
    {
        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors('No `label_id` was found.', 20);
        }
        else {

            // Set the columns that CAN be updated
            $types = array(
                'label' => array('name', 'active'),
                'smart' => array('domain', 'path', 'active', 'label_id')
            );

            // Lookup label_id to get type
            $user_where = (parent::isAdmin() === true && isset($this->clean->admin) && ! empty($this->clean->admin)) ? "labels.user_id IS NULL" : "labels.user_id = '" . $this->user_id . "'";
            $where      = $user_where . " AND labels.label_id= '" . $label_id . "'";
            $label      = $this->labels->readComplete($where, 1);

            // If no type found, there was no label for this id/user combo
            if (! isset($label->type)) {
                $this->data['errors'] = formatErrors('No label found using label id of `' . $label_id . '` for your account.', 21);
            }
            // If no type was found in above array, something was drastically wrong
            elseif (! array_key_exists($label->type, $types)) {
                $this->data['errors'] = formatErrors('This type of label `' . $label->type . '` could not be found.', 22);
            }
            // Update
            else {
                // Find options to update
                $options = array();
                foreach ($types[$label->type] as $k => $column) {
                    if (isset($this->db_clean->{$column})) {
                        $options[$column] = $this->db_clean->{$column};

                        // If label_id, switch to match internal column
                        if ($column == 'label_id') {
                            $options['smart_label_id'] = $options[$column];
                            unset($options[$column]);
                        }

                        // If domain or path, standardize them
                        if ($column == 'domain') {
                            $options[$column] = formatDomain($options[$column]);
                        }
                        elseif ($column == 'path') {
                            $options[$column] = formatPath($options[$column]);
                        }
                    }
                }

                // If updating name
                // create a new slug
                if (isset($options['name'])) {
                    $options['slug'] = generateSlug($options['name']);
                    $total           = $this->labels->count("labels.user_id IS NULL AND labels.slug = '" . $options['slug'] . "'");
                }

                // smart keys for domain/path
                if (isset($options['domain']) || isset($options['path'])) {
                    $domain               = (isset($options['domain'])) ? $options['domain'] : $label->settings->domain;
                    $path                 = (isset($options['path'])) ? $options['path'] : $label->settings->path;
                    $options['smart_key'] = md5($domain . $path);
                    $total                = $this->labels->count($user_where . " AND labels.smart_key = '" . $options['smart_key'] . "'");
                }


                // If no options, return error
                if (empty($options)) {
                    $this->data['errors'] = formatErrors('No options found to update for this label.', 23);
                }
                // If label slug or smart key exists already
                // Error out
                elseif (isset($total) && $total > 0) {
                    $this->data['errors'] = formatErrors('Label already exists for this account.', 24);
                }
                // Send update
                else {
                    $label = $this->labels->update($where, $options);

                    // If update failed, tell the user
                    if ($label === false) {
                        $this->data['errors'] = formatErrors('Label could not be updated.', 25);
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
            $this->data['errors'] = formatErrors('No `label_id` was found.', 20);
        }
        else {

            // Set the columns that CAN be updated
            $types = array(
                'label' => array('name', 'active'),
                'smart' => array('domain', 'path', 'active', 'label_id')
            );

            // Lookup label_id to get type
            $user_where = (parent::isAdmin() === true && isset($this->clean->admin) && ! empty($this->clean->admin)) ? "labels.user_id IS NULL" : "labels.user_id = '" . $this->user_id . "'";
            $where      = $user_where . " AND labels.label_id= '" . $label_id . "'";
            $label      = $this->labels->readComplete($where, 1);

            // If no type found, there was no label for this id/user combo
            if (! isset($label->type)) {
                $this->data['errors'] = formatErrors('No label found using label id of `' . $label_id . '` for your account.', 21);
            }
            // If no type was found in above array, something was drastically wrong
            elseif (! array_key_exists($label->type, $types)) {
                $this->data['errors'] = formatErrors('This type of label `' . $label->type . '` could not be found.', 22);
            }
            // Update
            else {
                // Find options to update
                $options = array();
                foreach ($types[$label->type] as $k => $column) {
                    if (isset($this->db_clean->{$column})) {
                        $options[$column] = $this->db_clean->{$column};

                        // If label_id, switch to match internal column
                        if ($column == 'label_id') {
                            $options['smart_label_id'] = $options[$column];
                            unset($options[$column]);
                        }

                        // If domain or path, standardize them
                        if ($column == 'domain') {
                            $options[$column] = formatDomain($options[$column]);
                        }
                        elseif ($column == 'path') {
                            $options[$column] = formatPath($options[$column]);
                        }
                    }
                }

                // If updating name
                // create a new slug
                if (isset($options['name'])) {
                    $options['slug'] = generateSlug($options['name']);
                    $total           = $this->labels->count($user_where . " AND labels.slug = '" . $options['slug'] . "'");
                }

                // smart keys for domain/path
                if (isset($options['domain']) || isset($options['path'])) {
                    $domain               = (isset($options['domain'])) ? $options['domain'] : $label->settings->domain;
                    $path                 = (isset($options['path'])) ? $options['path'] : $label->settings->path;
                    $options['smart_key'] = md5($domain . $path);
                    $total                = $this->labels->count($user_where . " AND labels.smart_key = '" . $options['smart_key'] . "'");
                }


                // If no options, return error
                if (empty($options)) {
                    $this->data['errors'] = formatErrors('No options found to update for this label.', 23);
                }
                // If label slug or smart key exists already
                // Error out
                elseif (isset($total) && $total > 0) {
                    $this->data['errors'] = formatErrors('Label already exists for this account.', 24);
                }
                // Send update
                else {
                    $label = $this->labels->update($where, $options);

                    // If update failed, tell the user
                    if ($label === false) {
                        $this->data['errors'] = formatErrors('Label could not be updated.', 25);
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
        - Get info for a single label
        - URLS
            /api/label/info/LABEL_ID
    */
    public function info($label_id=0)
    {
        // Figure correct way to handle if no mark id
        if (empty($label_id) || ! is_numeric($label_id)) {
            $this->data['errors'] = formatErrors('No `label_id` was found.', 20);
        }
        else {
            $where = (parent::isAdmin() === true) ? "(labels.user_id IS NULL OR labels.user_id = '" . $this->user_id . "')" : "labels.user_id = '" . $this->user_id . "'";
            $label = $this->labels->readComplete($where . " AND labels.label_id= '" . $label_id . "'");
            if ($label === false) {
                $this->data['errors'] = formatErrors('No label found using `' . $label_id . '` for your account.', 21);
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
            $this->data['errors'] = formatErrors('No `label_id` was found.', 20);
        }
        else {
            $where = (parent::isAdmin() === true) ? "(labels.user_id IS NULL OR labels.user_id = '" . $this->user_id . "')" : "labels.user_id = '" . $this->user_id . "'";
            $this->data['label'] = $this->labels->update($where . " AND labels.label_id= '" . $label_id . "'", array('active' => $active));
        }

        // Figure view
        $this->figureView();
    }

}