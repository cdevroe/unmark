<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marks extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirectIfLoggedOut();
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
        $view    = null;
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

            $this->load->model('users_to_marks_model', 'user_mark');
            $user_mark = $this->user_mark->read("user_id = '" . $this->user_id . "' AND mark_id = '" . $mark->mark_id . "'");

            // Add
            if (! isset($user_mark->users_to_mark_id)) {
                $user_mark = $this->user_mark->create(array(
                    'user_id' => $this->user_id,
                    'mark_id' => $mark->mark_id
                ));
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

        // Load correct model
        $this->load->model('users_to_marks_model', 'user_mark');
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

        // Get current page, total pages and total records
        $marks = $this->user_marks->getTotals($where, $page, $this->limit, $this->data);

        // Check for marks
        if ($marks === false) {
            $this->data['errors'] = formatErrors('No marks found for your account.', 12);
        }
        else {
            $this->data['marks'] = $marks;
            $this->data          = $this->user_marks->getTotals($where, $page, $this->limit, $this->data);
        }

        // Get the total saved and archived today
        $this->data['saved_today']    = $this->user_marks->getTotal('saved', $this->user_id, 'today');
        $this->data['archived_today'] = $this->user_marks->getTotal('archived', $this->user_id, 'today');

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
        $this->load->model('users_to_marks_model', 'user_mark');
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
        $this->load->model('users_to_marks_model', 'user_mark');
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
        $this->load->model('users_to_marks_model', 'user_mark');
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