<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marks extends Plain_Controller
{

    public $limit = 100;

    public function __construct()
    {
        parent::__construct();
        $this->redirectIfLoggedOut();
        $this->load->helper('oembed');
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
        $where = "users_to_marks.user_id='". $_SESSION['user']['user_id'] . "' AND" . $where_time . " users_to_marks.archived_on " . $archived;

        // Get current page, total pages and total records
        $this->data = $this->user_marks->getTotals($where, $page, $this->limit, $this->data);

        // Read the complete user marks records
        $this->data['marks'] = $this->user_marks->readComplete($where, $this->limit, $page);

        // Get the total saved and archived today
        $this->data['saved_today']    = $this->user_marks->getTotal('saved', $_SESSION['user']['user_id'], 'today');
        $this->data['archived_today'] = $this->user_marks->getTotal('archived', $_SESSION['user']['user_id'], 'today');

        // Figure if web or API view
        $this->figureView('marks/index');
    }

    public function label($label_id=0, $page=1)
    {
        // Set default marks
        $this->data['marks'] = false;
        $this->data['total'] = 0;

        // If label id is numeric, proceed
        if (! empty($label_id) && is_numeric($label_id)) {

            // Load user marks model
            $this->load->model('users_to_marks_model', 'user_marks');

            // Figure the correct starting page
            $page = (! is_numeric($page) || $page < 0) ? 1 : $page;

            // Set where
            $where = "users_to_marks.user_id='". $_SESSION['user']['user_id'] . "' AND users_to_marks.label_id = '" . $label_id . "'";

            // Get current page, total pages and total records
            $this->data = $this->user_marks->getTotals($where, $page, $this->limit, $this->data);

            // Get the marks by label id
            $this->data['marks'] = $this->user_marks->readComplete($where, $this->limit, $page);
        }

        // Figure if web or API view
        $this->figureView('marks/label');
    }

    public function search()
    {
        // Not sure how to tackle this one yet
    }

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

        // If successful
        // Check if user already has this mark
        // If not add it
        // If so, redirect to it
        if (isset($mark->mark_id)) {
            $this->load->model('users_to_marks_model', 'user_mark');
            $user_mark = $this->user_mark->read("user_id = '" . $_SESSION['user']['user_id'] . "' AND mark_id = '" . $mark->mark_id . "'");

            // Add
            if (! isset($user_mark->users_to_mark_id)) {
                $user_mark = $this->user_mark->create(array(
                    'user_id' => $_SESSION['user']['user_id'],
                    'mark_id' => $mark->mark_id
                ));
            }

            // If still no user mark id, error out
            // We need a better way to handle/show errors here
            if (! isset($user_mark->users_to_mark_id)) {
                $data = array();
                $this->data['mark']   = false;
                $this->data['errors'] = $user_mark;
                $view                 = 'marks/add';
            }
            else {
                $this->data['mark'] = $user_mark;
                $redirect           = '/marks/edit/' . $user_mark->users_to_mark_id . '?bookmarklet=true';
            }
        }
        else {
            $this->data['mark']   = false;
            $this->data['errors'] = $mark;
            $view                 = 'marks/add';
        }

        // Figure what to do here (api, redirect or generate view)
        $this->figureView($view, $redirect);

    }

    public function info($mark_id=0)
    {

        // Figure correct way to handle if no mark id
        if (empty($mark_id) || ! is_numeric($mark_id)) {
            header('Location: /');
            exit;
        }

        // Load correct model
        $this->load->model('users_to_marks_model', 'user_mark');
        $this->data['mark'] = $this->user_mark->readComplete("users_to_marks.user_id = '" . $_SESSION['user']['user_id'] . "' AND users_to_marks.users_to_mark_id = '" . $mark_id . "'", 1);

        // Figure view
        $this->figureView('marks/info');
    }

    public function archive($mark_id=0)
    {

        // Figure correct way to handle if no mark id
        if (empty($mark_id) || ! is_numeric($mark_id)) {
            header('Location: /');
            exit;
        }

        // Load correct model
        $this->load->model('users_to_marks_model', 'user_mark');
        $this->data['mark'] = $this->user_mark->update("users_to_marks.user_id = '" . $_SESSION['user']['user_id'] . "' AND users_to_marks.users_to_mark_id = '" . $mark_id . "'", array('archived_on' => date('Y-m-d H:i:s')));

        // Figure view
        $this->figureView('marks/archive');
    }

    public function restore($mark_id=0)
    {

        // Figure correct way to handle if no mark id
        if (empty($mark_id) || ! is_numeric($mark_id)) {
            header('Location: /');
            exit;
        }

        // Load correct model
        $this->load->model('users_to_marks_model', 'user_mark');
        $this->data['mark'] = $this->user_mark->update("users_to_marks.user_id = '" . $_SESSION['user']['user_id'] . "' AND users_to_marks.users_to_mark_id = '" . $mark_id . "'", array('archived_on' => NULL));

        // Figure view
        $this->figureView('marks/restore');
    }

    // Finds the day's bookmarks
    // Checks to see if they need oEmbed
    // Process them.
    // Every 1 minute
    // FIXME kip9 Remove oembed = '' conditions - backwards compatibility only
    public function backprocessOembed()
    {


        // Unix timestamps for yesterday and today
        $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
        $today = mktime(0, 0, 0, date('n'), date('j'));

        if (isset($_GET['all']) && $_GET['all'] == 'yes') {
            $marks = $this->db->query("SELECT * FROM marks WHERE ( oembed = '' or oembed is null ) ORDER BY id ASC LIMIT 100");
        } else {
            $marks = $this->db->query("SELECT * FROM marks WHERE UNIX_TIMESTAMP(dateadded) > ".$today." AND ( oembed = '' or oembed is null ) ORDER BY id ASC");
        }

        if ($marks->num_rows() > 0) {
            $numberofrecords = $marks->num_rows();
            $embedupdated = 0;

            foreach($marks->result_array() as $mark) {
                // OEmbed check
                $oembed = oembed($mark['url']);
                if (isset($oembed) && $oembed != '') {
                    $embedupdated++;
                    $this->db->update('marks',array('oembed'=>$oembed),array('id'=>$mark['id']));
                } else {
                    $this->db->update('marks',array('oembed'=>'None'),array('id'=>$mark['id']));
                }
                $oembed = '';

            } // end foreach

            echo 'Bookmarks processed: '.$numberofrecords.'<br />Embeds added: '.$embedupdated;
        } // end if
    return;
    }

    // Finds the day's bookmarks
    // Checks to see if they need Recipe Parsing
    // Process them.
    // Every minute.
    // FIXME kip9 Remove recipe = '' conditions - backwards compatibility only
    public function backprocessRecipes()
    {

        $this->load->helper('hrecipe');

        // Unix timestamps for yesterday and today
        $yesterday = mktime(0, 0, 0, date('n'), date('j') - 1);
        $today = mktime(0, 0, 0, date('n'), date('j'));

        if (isset($_GET['all']) && $_GET['all'] == 'yes') {
            $marks = $this->db->query("SELECT * FROM marks WHERE ( recipe = '' or recipe is null ) ORDER BY id ASC LIMIT 100");
        } else {
            $marks = $this->db->query("SELECT * FROM marks WHERE UNIX_TIMESTAMP(dateadded) > ".$today." AND ( recipe = '' or recipe is null ) ORDER BY id ASC");
        }

        if ($marks->num_rows() > 0) {
            $numberofrecords = $marks->num_rows();
            $embedupdated = 0;

            foreach($marks->result_array() as $mark) {
            // Recipe check
                if ($mark['url'] != 'http://localhost:8888/home') {
                    $recipe = parse_hrecipe($mark['url']);
                }

                if (isset($recipe) && $recipe != '') {
                    $embedupdated++;
                    $this->db->update('marks',array('recipe'=>$recipe),array('id'=>$mark['id']));
                } else {
                    $this->db->update('marks',array('recipe'=>'None'),array('id'=>$mark['id']));
                }

                $recipe = '';
            } // end foreach

            echo 'Bookmarks processed: '.$numberofrecords.'<br />Recipes added: '.$embedupdated;
        } else {
            echo 'No marks in the database need to be processed.';
        }

    return;
    }

}