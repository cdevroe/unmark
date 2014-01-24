<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marks extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirectIfLoggedOut();

        $this->user_id = 5;
        $this->load->model('users_to_marks_model', 'user_mark');
    }

     /*
        - Add a mark
        - URLS
            /mark/add
            /api/mark/add

        // Query variables
         - title : Required
         - url   : Required
         -
    */
    public function add()
    {
        // Set default view & redirect
        $view     = null;
        $redirect = null;

        // Add mark to marks table
        $this->load->model('marks_model', 'mark');
        $mark = $this->mark->create(array(
            'title' => $this->db_clean->title,
            'url'   => $this->db_clean->url
        ));

        if ($mark === false) {
            $this->data['errors'] = formatErrors('Could not add mark.', 10);
            $view                 = 'marks/add';
        }
        elseif (! isset($mark->mark_id)) {
            $this->data['errors'] = $mark;
            $view                 = 'marks/add';
        }
        else {
            $user_mark = $this->user_mark->read("user_id = '" . $this->user_id . "' AND mark_id = '" . $mark->mark_id . "'");

            // Add
            if (! isset($user_mark->users_to_mark_id)) {

                // Set default options
                $options = array('user_id' => $this->user_id, 'mark_id' => $mark->mark_id);

                // Figure if any automatic labels should be applied
                $smart_info = getSmartLabelInfo($this->clean->url);
                if (isset($smart_info['key']) && ! empty($smart_info['key'])) {

                    // Load labels model
                    // Sort by user_id DESC (if user has same rule as system, use the user's rule)
                    // Try to extract label
                    $this->load->model('labels_model', 'labels');
                    $this->labels->sort = 'user_id DESC';
                    $label = $this->labels->readComplete("labels.user_id IS NULL OR labels.user_id = '" . $this->user_id . "' AND labels.smart_key = '" . $smart_info['key'] . "'", 1);

                    // If a label id is found
                    // Set it to options to save
                    if (isset($label->settings->label->id)) {
                        $options['label_id'] = $label->settings->label->id;
                    }
                }

                // Create the mark
                $user_mark = $this->user_mark->create($options);
            }

            if ($user_mark === false) {
                $this->data['errors'] = formatErrors('Could not add mark.', 10);
                $view                 = 'marks/add';
            }
            if (! isset($user_mark->users_to_mark_id)) {
                $this->data['errors'] = $user_mark;
                $view                 = 'marks/add';
            }
            else {
                $this->data['mark'] = $user_mark;
                $redirect           = '/marks/info/' . $user_mark->users_to_mark_id . '?bookmarklet=true';
            }
        }

        // Figure what to do here (api, redirect or generate view)
        $this->figureView($view, $redirect);

    }

    // Archive a mark
    // both api and web view
    public function archive($mark_id=0)
    {

        // Figure correct way to handle if no mark id
        if (empty($mark_id) || ! is_numeric($mark_id)) {
            header('Location: /');
            exit;
        }

        // Update
        $mark = $this->user_mark->update("users_to_marks.user_id = '" . $this->user_id . "' AND users_to_marks.users_to_mark_id = '" . $mark_id . "'", array('archived_on' => date('Y-m-d H:i:s')));

        if ($mark === false) {
            $this->data['errors'] = formatErrors('Mark could not be archived.', 11);
        }
        else {
            $this->data['mark'] = $mark;
        }

        // Figure view
        $this->figureView('marks/archive');
    }

    // The index of the marks page
    public function index($when=null, $page=1)
    {
        // Load user marks model
        $this->load->model('users_to_marks_model', 'user_marks');

        // Figure when to pull marks from
        $this->data['when'] = $this->uri->segment(2);
        $this->data['when'] = (! empty($data['when'])) ? $data['when'] : 'all';
        $where_time         = ($this->data['when'] == 'today') ? "UNIX_TIMESTAMP(marks.created_on) > '" . $today . "' AND " : '';
        $where_time         = ($this->data['when'] == 'yesterday') ? "UNIX_TIMESTAMP(marks.created_on) > '" . $yesterday . "' AND UNIX_TIMESTAMP(marks.created_on) < '" . $today . "' AND " : $where_time;
        $archived           = ($this->data['when'] == 'archive') ? 'IS NOT NULL' : 'IS NULL';

        // Figure the correct starting page
        $page = (! is_numeric($page) || $page < 0) ? 1 : $page;

        // Set where
        $where = "users_to_marks.user_id='". $this->user_id . "' AND" . $where_time . " users_to_marks.archived_on " . $archived;

        // Get all the marks
        $marks = $this->user_marks->readComplete($where, $this->limit, $page);

        // Check for marks
        if ($marks === false) {
            $this->data['errors'] = formatErrors('No marks found for your account.', 12);
        }
        else {
            $this->data['marks'] = $marks;
            $this->data          = $this->user_marks->getTotals($where, $page, $this->limit, $this->data);
        }

        // Only grab these stats for web view (on site)
        if (parent::isWebView() === true) {
            self::getStats();
            self::getLabels();
        }

        // Figure if web or API view
        $this->figureView('marks/index');
    }

    // Edit a mark
    // Both API and web view
    public function edit($mark_id=0)
    {
        // Figure correct way to handle if no mark id
        if (empty($mark_id) || ! is_numeric($mark_id)) {
            header('Location: /');
            exit;
        }

        // Figure what options to send for update
        $options = array();

        // If label ID is found, attach it
        if (isset($this->clean->label_id) && is_numeric($this->label_id)) {
            $options['label_id'] = $this->clean->label_id;
        }

        // If notes are present set them
        if (isset($this->db_clean->notes)) {
            $options['notes'] = $this->db_clean->notes;
        }

        // If tags are present, handle differentlu
        // Need to add to tags table first
        // Then create association
        // If notes are present set them
        if (isset($this->db_clean->tags) || isset($this->clean->delete_tags)) {
            // Update users_to_marks record
            $this->load->model('tags_model', 'tag');
            $this->load->model('user_marks_to_tags_model', 'mark_to_tag');

            // Add/Update tags
            if (isset($this->db_clean->tags)) {
                $tags = explode(',', $this->db_clean->tags);
                foreach ($tags as $k => $tag) {
                    $tag  = trim($tag);
                    $slug = generateSlug($tag);
                    if (! empty($slug)) {
                        $tag = $this->tag->read("slug == '" . $slug . "'", 1, 1, 'tag_id');
                        if (! isset($tag->tag_id)) {
                            $tag = $this->tag->create(array('tag' => trim($this->db_clean->tags->{$k}), 'slug' => $slug));
                        }

                        // Add tag to mark
                        if (isset($tag->tag_id)) {
                            $res = $this->mark_to_tags->create(array('users_to_mark_id' => $mark_id, 'tag_id' => $tag->id, 'user_id' => $this->user_id));
                        }
                    }
                }
            }

            // Delete tags
            if (isset($this->clean->delete_tags)) {
                $tag_ids = explode(',', $this->clean->delete_tags);
                foreach ($tag_ids as $tag_id) {
                    if (is_numeric($tag_id)) {
                        $tag = $this->tag->create(array('tag' => trim($this->db_clean->tags->{$k}), 'slug' => $slug));

                        // Add tag to mark
                        if (isset($tag->tag_id)) {
                            $res = $this->mark_to_tags->delete(array('users_to_mark_id' => $mark_id, 'tag_id' => $tag->id, 'user_id' => $this->user_id));
                        }
                    }
                }
            }
        }


        // Update users_to_marks record
        $mark = $this->user_mark->update("user_id = '" . $this->user_id . "' AND mark_id = '" . $mark->mark_id . "'", $options);

        // Check if it was updated
        if ($mark === false) {
            $this->data['errors'] = formatErrors('Mark could not be updated.', 14);
        }
        else {
            $this->data['mark'] = $mark;
        }

        // Figure what to do here (api, redirect or generate view)
        $this->figureView('marks/edit');

    }

    public function get($what='stats')
    {
        parent::redirectIfWebView();
        $method = 'get' . ucwords($what);
        if (method_exists($this, $method)) {
            $total = $this->$method();
            parent::renderJSON();
        }
        else {
            header('Location: /');
            exit;
        }

    }

    private function getLabels()
    {
        $this->load->model('labels_model', 'labels');
        $this->data['labels'] = $this->labels->getSystemLabels();

        if ($this->data['labels'] !== false) {
            $this->load->model('labels_model', 'labels');
            foreach($this->data['labels'] as $k => $label) {
                $this->data['labels'][$k]->total = $this->user_marks->count("label_id = '" . $label->label_id . "' AND user_id = '" . $this->user_id . "'");
            }
        }

    }


    private function getStats()
    {
        $this->data['stats'] = array();

        // Get total marks saved over the last 5 days
        $this->data['stats']['saved'] = array(
            'today'      => self::totalSaved('today'),
            'yesterday'  => self::totalSaved('yesterday'),
            '2 days ago' => self::totalSaved('-2 days'),
            '3 days ago' => self::totalSaved('-3 days'),
            '4 days ago' => self::totalSaved('-4 days')
        );

        // Get the total marks archived over the last 5 days
        $this->data['stats']['archived'] = array(
            'today'      => self::totalArchived('today'),
            'yesterday'  => self::totalArchived('yesterday'),
            '2 days ago' => self::totalArchived('-2 days'),
            '3 days ago' => self::totalArchived('-3 days'),
            '4 days ago' => self::totalArchived('-4 days')
        );

        // Get total marks for a series of ranges
        $this->data['stats']['marks'] = array(
            'today'         => self::totalMarks('today'),
            'yesterday'     => self::totalMarks('yesterday'),
            'last week'     => self::totalMarks('-7 days', 'today'),
            'last_month'    => self::totalMarks('-1 month', 'today'),
            'last 3 months' => self::totalMarks('-3 months', 'today'),
            'last 6 months' => self::totalMarks('-6 months', 'today'),
            'last year'     => self::totalMarks('-1 year', 'today'),
            'ages ago'      => self::totalMarks('-20 years', '-1 year')
        );

    }

    public function total($what='saved', $start='today', $finish=null)
    {
        parent::redirectIfWebView();

        $method = 'total' . ucwords($what);
        if (method_exists($this, $method)) {
            $start  = (empty($start)) ? 'today' : strtolower($start);
            $finish = (empty($finish)) ? 'today' : strtolower($finish);
            $this->data['total'] = $this->$method($start, $finish);
            parent::renderJSON();
        }
        else {
            header('Location: /');
            exit;
        }
    }

    private function totalArchived($start='today', $finish=null)
    {
        return $this->user_marks->getTotal('archived', $this->user_id, $start, $finish);
    }

    private function totalMarks($start='today', $finish=null)
    {
        return $this->user_marks->getTotal('marks', $this->user_id, $start, $finish);
    }

    private function totalSaved($start='today', $finish=null)
    {
        return $this->user_marks->getTotal('saved', $this->user_id, $start, $finish);
    }

    // Mark detail view
    // Both API and web view
    public function info($mark_id=0)
    {

        // Figure correct way to handle if no mark id
        if (empty($mark_id) || ! is_numeric($mark_id)) {
            header('Location: /');
            exit;
        }

        // Load correct model
        $mark = $this->user_mark->readComplete("users_to_marks.user_id = '" . $this->user_id . "' AND users_to_marks.users_to_mark_id = '" . $mark_id . "'", 1);

        // Check for mark
        if ($mark === false) {
            $this->data['errors'] = formatErrors('Mark with id of `' . $mark_id . '` could not be found for this account.', 15);
        }
        else {
            $this->data['mark'] = $mark;
        }

        // Figure view
        $this->figureView('marks/info');
    }

    // Lookup by label
    // Both api and web view
    public function label($label_id=0, $page=1)
    {
        // If label id is string, find label_id
        // Need to write this as ONE query
        if (! is_numeric($label_id)) {
            $this->load->model('labels_model', 'label');
            $label    = $this->label->read("slug = '" . mysqli_real_escape_string($this->db->conn_id, $label_id) . "'", 1, 1, 'label_id');
            $label_id = (isset($label->label_id)) ? $label->label_id : 0;
        }

        // If label id is numeric, proceed
        if (! empty($label_id) && is_numeric($label_id)) {

            // Load user marks model
            $this->load->model('users_to_marks_model', 'user_marks');

            // Figure the correct starting page
            $page = (! is_numeric($page) || $page < 0) ? 1 : $page;

            // Set where
            $where = "users_to_marks.user_id='". $this->user_id . "' AND users_to_marks.label_id = '" . $label_id . "'";

            // Get the marks by label id
            $marks = $this->user_marks->readComplete($where, $this->limit, $page);

            // Check for marks
            if ($marks === false) {
                $this->data['errors'] = formatErrors('No marks found for your account for this label.', 13);
            }
            else {
                $this->data['marks'] = $marks;
                $this->data          = $this->user_marks->getTotals($where, $page, $this->limit, $this->data);
            }
        }
        else {
            $this->data['errors'] = formatErrors('No label found for mark lookup.', 16);
        }

        // Figure if web or API view
        $this->figureView('marks/label');
    }

    // Restore a bookmark from archived
    // Both API and webview
    public function restore($mark_id=0)
    {

        // Figure correct way to handle if no mark id
        if (empty($mark_id) || ! is_numeric($mark_id)) {
            header('Location: /');
            exit;
        }

        // Load correct model
        $mark = $this->user_mark->update("users_to_marks.user_id = '" . $this->user_id . "' AND users_to_marks.users_to_mark_id = '" . $mark_id . "'", array('archived_on' => NULL));

        // Check if it was updated
        if ($mark === false) {
            $this->data['errors'] = formatErrors('Mark could not be restored.', 17);
        }
        else {
            $this->data['mark'] = $mark;
        }

        // Figure view
        $this->figureView('marks/restore');
    }

    // search, not sure hwo to handle yet
    public function search()
    {
        // Not sure how to tackle this one yet
    }

}