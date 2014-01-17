<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marks extends Plain_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->redirectIfLoggedOut();
        $this->load->helper('oembed');
    }

    // Unused for now.
    public function index($when='')
    {

        //$this->session->set_flashdata('lasturl', current_url());


        $this->load->model('users_to_marks_model', 'user_marks');
        $data['when']  = $this->uri->segment(2);
        $data['when']  = (! empty($data['when'])) ? $data['when'] : 'all';
        $where_time    = ($data['when'] == 'today') ? "UNIX_TIMESTAMP(marks.created_on) > '" . $today . "' AND " : '';
        $where_time    = ($data['when'] == 'yesterday') ? "UNIX_TIMESTAMP(marks.created_on) > '" . $yesterday . "' AND UNIX_TIMESTAMP(marks.created_on) < '" . $today . "' AND " : $where_time;
        $archived      = ($data['when'] == 'archive') ? 'IS NOT NULL' : 'IS NULL';
        $data['marks'] = $this->user_marks->readComplete("users_to_marks.user_id='". $_SESSION['user']['user_id'] . "' AND" . $where_time . " users_to_marks.archived_on " . $archived, 100, 1);



        //$this->load->model('Groups_model');
        //$data['groups']['belong'] = $this->Groups_model->get_groups_user_belongs_to();
        $data['groups']['belong'] = array();

       /* $invites = $this->db->query("
            SELECT
            group_invites.*,
            group_invites.group_invite_id as inviteid,
            groups.*,
            FROM group_invites
            LEFT JOIN groups ON group_invites.group_id = groups.group_id
            WHERE group_invites.email = '". $_SESSION['user']['email'] . "'
            AND
            group_invites.active = '0'
        ");

        if ($invites->num_rows() > 0) $data['invites'] = $invites->result_array();*/
        $data['invites'] = array();


        $data['label'] = '';
        $data['group']['groupuid'] = '';

        $data['marks_saved_today'] = $this->user_marks->get_number_saved_today();
        $data['marks_archived_today'] = $this->user_marks->get_number_archived_today();

        $this->view('marks', $data);
    }

    public function bylabel()
    {
        if (!$_SESSION['user']['user_id']) { redirect(''); }
        $this->session->set_flashdata('lasturl', current_url());


        $this->load->model('Groups_model');
        $this->load->model('Marks_model');

        $label = $this->uri->segment(3);

        // Retrieve marks.
        $data['marks'] = $this->Marks_model->get_by_label($label);

        // Load the groups the user belongs to
        $data['groups']['belong'] = $this->Groups_model->get_groups_user_belongs_to();

        $data['label'] = $label;
        $data['group']['groupuid'] = '';
        $data['when'] = 'all';

        $this->load->view('marks',$data);
    }

    public function bygroup()
    {
        if (!$_SESSION['user']['user_id']) { redirect(''); }
        $this->session->set_flashdata('lasturl', current_url());

        $groupuid = $this->uri->segment(2);


        $this->load->model('Groups_model');
        $this->load->model('Marks_model');

        // General group information
        // If Group does not exist, 404
        // Needs to be moved into the model. Somehow.
        $group = $this->db->query("SELECT * FROM groups WHERE uid = '".$groupuid."'");
        if ($group->num_rows() > 0) {
            $group = $group->result_array();
            $data['group']['name'] = $group[0]['name'];
            $data['group']['description'] = $group[0]['description'];
            $data['group']['groupuid'] = $groupuid;
            $data['group']['owner'] = $group[0]['createdby'];

            $data['group']['member_count'] = $this->Groups_model->get_group_members_count($group[0]['id']);

        } else {
            show_404();
        }

        $data['marks'] = $this->Marks_model->get_by_group($groupuid);

        // Load the groups the user belongs to
        $data['groups']['belong'] = $this->Groups_model->get_groups_user_belongs_to();

        $data['label'] = '';
        $data['when'] = 'all';

        $this->load->view('marks',$data);
    }

    public function search()
    {
        if (!$_SESSION['user']['user_id']) { redirect(''); }
        $this->session->set_flashdata('lasturl', current_url());

        $s = $this->input->post('s');

        if ($s == '') redirect('home');


        $this->load->model('Groups_model');
        $this->load->model('Marks_model');

        $data['marks'] = $this->Marks_model->search_from_user($s);

        // Load the groups the user belongs to
        $data['groups']['belong'] = $this->Groups_model->get_groups_user_belongs_to();

        $data['search'] = $s;
        $data['label'] = '';
        $data['group']['groupuid'] = '';
        $data['when'] = 'all';

        $this->load->view('marks',$data);
    }

    public function add()
    {
        $this->redirectIfLoggedOut('/');

        // Not sure what this is for
        // Commenting out for now
        // $this->session->set_flashdata('addurl', current_url());


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
            $this->load->model('user_to_marks_model', 'user_mark');
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
                foreach ($mark as $error) {
                    print '<p>Error: ' . $error . '</p>';
                }
            }
            else {
                header('Location: /marks/edit/' . $user_mark->users_to_mark_id . '?bookmarklet=true');
                exit;
            }
        }

    }

    public function addlabel($urlid='',$label='')
    {
        if (!$_SESSION['user']['user_id']) { redirect('home'); }


        if ($this->input->get('urlid') != '') $urlid = $this->input->get('urlid');
        if ($this->input->get('label') != '') $label = $this->input->get('label');

        $this->db->update('users_marks',array('tags'=>strtolower($label)),array('urlid' => $urlid,'userid'=>$_SESSION['user']['user_id']));

    // Success!
    return;
    }

    public function addsmartlabel($domain='',$label='')
    {
        if (!$_SESSION['user']['user_id']) { redirect('home'); }


        if ($this->input->get('domain') != '') $domain = $this->input->get('domain');
        if ($this->input->get('label') != '') $label = $this->input->get('label');

        $noduplicates = $this->db->query("SELECT * FROM users_smartlabels WHERE userid = ".$_SESSION['user']['user_id']." AND domain = '".$domain."'");

        if ($noduplicates->num_rows() > 0) { // Update record
            $this->db->update('users_smartlabels',array('label'=>$label),array('domain'=>$domain,'userid'=>$_SESSION['user']['user_id']));
        } else { // Add new record
            $this->db->insert('users_smartlabels',array('userid'=>$_SESSION['user']['user_id'],'domain'=>$domain,'label'=>$label));
        }

    return;
    }

    public function removesmartlabel($domain='',$label='')
    {
        if (!$_SESSION['user']['user_id']) { redirect('home'); }


        if ($this->input->get('domain') != '') $domain = $this->input->get('domain');
        if ($this->input->get('label') != '') $label = $this->input->get('label');

        $this->db->delete('users_smartlabels', array('userid' => $_SESSION['user']['user_id'],'domain'=>$domain));

    return;
    }

    public function checkdefaultsmartlabel($parsedUrl='')
    {
        if(!empty($parsedUrl['host'])){
         switch (str_replace('www.','',$parsedUrl['host'])) {
           /* Video web services */
           case 'youtube.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/watch');
             if ($pathPos !== FALSE) {
               return array(TRUE,'watch');
             }
           break;

           case 'viddler.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/v');
             if ($pathPos !== FALSE) {
                return array(TRUE,'watch');
             }
           break;

           case 'devour.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/video');
             if ($pathPos !== FALSE) {
                return array(TRUE,'watch');
             }
           break;

           case 'ted.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/talks');
             if ($pathPos !== FALSE) {
                return array(TRUE,'watch');
             }
           break;

           case 'vimeo.com':
             return array(TRUE,'watch');
           break;

           /* Documentation URLs */
           case 'php.net':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/manual');
             if ($pathPos !== FALSE) {
                return array(TRUE,'read');
             }
           break;

           case 'api.rubyonrails.org':
               return array(TRUE,'read');
           break;

           case 'ruby-doc.org':
               return array(TRUE,'read');
           break;

         case 'docs.jquery.com':
               return array(TRUE,'read');
           break;

           case 'codeigniter.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/user_guide');
             if ($pathPos !== FALSE) {
               return array(TRUE,'read');
             }
           break;

           case 'css-tricks.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/almanac');
             if ($pathPos !== FALSE) {
               return array(TRUE,'read');
             }
           break;

           case 'developer.apple.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/library');
             if ($pathPos !== FALSE) {
               return array(TRUE,'read');
             }
           break;

           /* Recipe URLs */

           case 'simplyrecipes.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/recipes');
             if ($pathPos !== FALSE) {
               return array(TRUE,'eatdrink');
             }
           break;

           case 'allrecipes.com':
             return array(TRUE,'eatdrink');
           break;

           case 'epicurious.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/recipes');
             if ($pathPos !== FALSE) {
               return array(TRUE,'eatdrink');
             }
           break;

           case 'foodnetwork.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/recipes');
             if ($pathPos !== FALSE) {
               return array(TRUE,'eatdrink');
             }
           break;

           case 'food.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/recipe');
             if ($pathPos !== FALSE) {
               return array(TRUE,'eatdrink');
             }
           break;

           /* Shopping URLs */

           case 'svpply.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/item');
             if ($pathPos !== FALSE) {
               return array(TRUE,'buy');
             }
           break;

           case 'amazon.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/gp/product');
             if ($pathPos !== FALSE) {
               return array(TRUE,'buy');
             }
           break;

        case 'fab.com':
             $pathPos = strpos(strtolower($parsedUrl['path']),'/sale');
             if ($pathPos !== FALSE) {
               return array(TRUE,'buy');
             }
           break;

           case 'zappos.com':
             return array(TRUE,'buy');
           break;

           default:
             //echo 'not adding any label';
           break;
         }
        }

    }

    public function addgroup($urlid='',$group='')
    {
        if (!$_SESSION['user']['user_id']) { redirect('home'); }

        $this->load->model('Marks_model');

        if ($this->input->get('urlid') != '') $urlid = $this->input->get('urlid');
        if ($this->input->get('group') != '') $group = $this->input->get('group');

        if ($urlid=='' || $group == '') return 'failure';

        $this->Marks_model->add_mark_to_group($urlid,$group);

        /*$this->db->update('users_marks',array('groups'=>$group),array('urlid' => $urlid,'userid'=>$_SESSION['user']['user_id']));

        // Duplicate this bookmark for every single person in the group.
        $groupmembers = $this->db->query("SELECT * FROM users_groups WHERE groupid = ".$group);

        if ($groupmembers->num_rows() > 0) {
            foreach($groupmembers->result_array() as $member) {
                if ($member['userid'] != $_SESSION['user']['user_id']) {

                    // No reason to duplicate the link. But, if the link is not yet in the group add it.
                    $link = $this->db->query("SELECT * FROM users_marks WHERE urlid = '".$urlid."' AND groups = '".$group."' AND userid = '".$member['userid']."'");
                    if ($link->num_rows() < 1) {
                        $this->db->insert('users_marks',array('userid'=>$member['userid'],'urlid'=>$urlid,'groups'=>$group,'addedby'=>$_SESSION['user']['user_id']));
                    }
                } // end if
            } // end foreach
        } // end if */

    // Success!
    return;
    }

    public function edit()
    {
        if (!$_SESSION['user']['user_id']) { redirect(''); }

        $this->session->set_flashdata('lasturl', current_url());


        $this->load->model('Groups_model');
        $this->load->model('Marks_model');

        $markid = $this->uri->segment(3);

        $mark = $this->Marks_model->get_users_mark_by_id($markid);

        if ( is_array($mark) == true ) {

            $parsedUrl = parse_url($mark[0]['url']);

            // Only look for smart labels if host is set
            if(!empty($parsedUrl['host'])){
                // First, check for user smart labels
                $smartlabel = $this->db->query("SELECT * FROM users_smartlabels WHERE domain = '".strtolower($parsedUrl['host'])."' AND userid = '".$_SESSION['user']['user_id']."'");

                 if ($smartlabel->num_rows() > 0) {  // smart label found
                    $label = $smartlabel->row();
                    $data['userlabeladded'] = TRUE;

                } else {
                    // Figure out if it matches any default labels
                    // Label accordingly.
                    $smartlabel = $this->checkdefaultsmartlabel($parsedUrl);
                    if ($smartlabel[0] == TRUE) {
                        $data['labeladded'] = TRUE;
                    }
                }
            }

            // I think this part could be reduced to a single line,
            // such as array_merge(). But I havent be able to do it.
            $data['title'] = $mark[0]['title'];
            $data['url'] = $mark[0]['url'];
            $data['urlid'] = $mark[0]['urlid'];
            $data['tags'] = $mark[0]['tags'];
            $data['note'] = $mark[0]['note'];
            $data['addedby'] = $mark[0]['addedby'];
            $data['groupid'] = $mark[0]['groups'];

            if(!empty($parsedUrl['host'])){
              $data['urldomain'] = strtolower($parsedUrl['host']);
            }

            $data['groups']['created'] = $this->Groups_model->get_groups_created_by_user();

            // Load the groups the user belongs to
            $data['groups']['belong'] = $this->Groups_model->get_groups_user_belongs_to();

            $data['label'] = '';
            $data['group']['groupuid'] = '';
            $data['when'] = 'all';

            if ( isset($_GET['bookmarklet']) && $_GET['bookmarklet'] == true) {
                $data['markadded'] = true;
            }

            $this->load->view('editpop',$data);
        } else {
            show_404();
        }
    }

    public function savenote($urlid='',$note='')
    {
        if (!$_SESSION['user']['user_id']) { redirect('home'); }


        if ($this->input->get('urlid') != '') $urlid = $this->input->get('urlid');
        if ($this->input->get('note') != '') $note = $this->input->get('note');

        $this->db->update('users_marks',array('note'=>$note),"urlid = ".$urlid);

    // Success!
    return;
    }

    public function archive()
    {
        if (!$_SESSION['user']['user_id']) { redirect(''); }


        $id = $this->uri->segment(3);
        $this->db->update('users_marks',array('status'=>'archive','archived_on'=>date('Y-m-d H:i:s')),array('id' => $id,'userid'=>$_SESSION['user']['user_id']));

        echo 'success';
        exit;
    }

    public function restore()
    {
        if (!$_SESSION['user']['user_id']) { redirect(''); }


        $id = $this->uri->segment(3);
        $this->db->update('users_marks',array('status'=>'','archived_on'=>''),array('id' => $id,'userid'=>$_SESSION['user']['user_id']));

        $this->session->set_flashdata('message', 'Your mark has been restored.');
        $this->session->set_flashdata('restoredurlid',$urlid);

        echo 'success';
        exit;
    }

    public function delete()
    {
        if (!$_SESSION['user']['user_id']) { redirect(''); }

        $this->load->model('Marks_model');

        $id = $this->uri->segment(3);
        $this->Marks_model->delete_mark_for_user($id);
        //$this->db->update('users_marks',array('status'=>''),array('id' => $id,'userid'=>$_SESSION['user']['user_id']));

        $this->session->set_flashdata('message', 'Your mark has been deleted.');

        redirect('home');
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

/* End of file nilai.php */
/* Location: ./application/controllers/nilai.php */