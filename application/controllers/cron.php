<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Plain_Controller
{
    // NO API ROUTE

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfNotCommandLine();
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
        $marks   = $this->user_mark->read("embed_processed = '0'", 'all', 1, 'users_to_mark_id, url');

        if ($marks->num_rows() > 0) {
            $this->load->helper('oembed');
            $this->load->helper('hrecipe');

            foreach($marks->result() as $mark) {

                // OEmbed check
                $embed = oembed($mark->url);
                $embed = (empty($embed)) ? parse_hrecipe($mark->url) : $embed;

                // Set options
                // Set embed processed = 1
                $options                    = array();
                $options['embed_processed'] = 1;

                // If embed is found
                // Set it in options
                if (! empty($embed)) {
                    $options['embed'] = $embed;
                }

                $res = $this->user_mark->update($mark->users_to_mark_id, $options);

            }
        }
    }

}