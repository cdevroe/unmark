<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Plain_Controller
{
    // NO API ROUTE

    public function __construct()
    {
        parent::__construct();
        redirectIfNotTerminal();
    }

    public function index()
    {
        print 'hello command line user >_<';
    }

    public function processEmbeds()
    {

        set_time_limit(0);

        // Get any new marks in the last ten minutes that are embeds of NULL
        $this->load->model('users_to_marks_model', 'user_mark');
        $marks   = $this->user_mark->read("UNIX_TIMESTAMP(created_on) >= '" . strtotime('-10 minutes') . "'' AND embed is NULL ORDER BY id ASC", 'all', 1, 'users_to_mark_id, url');
        $total   = $marks->num_rows();
        $updated = 0;

        if ($total > 0) {
            $this->load->helper('oembed');
            $this->load->helper('hrecipe');

            foreach($marks->result() as $mark) {

                // OEmbed check
                $embed = oembed($mark->url);
                $embed = (empty($embed)) ? parse_hrecipe($mark->url) : $embed;

                if (! empty($embed)) {
                    $updated += 1;
                    $res = $this->user_mark->update($mark->users_to_mark_id, array('embed' => $embed));
                    $this->db->update('marks',array('oembed'=>$oembed),array('id'=>$mark['id']));
                }
            }
        }

        print 'Records processed: ' . $numberofrecords . "\n" . 'Embeds added: ' . $updated;
    }

}