<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Plain_Controller
{

    /**
     * Maximum number of embeds to process at once
     * @var int
     */
    const MAX_EMBEDS_TO_PROCESS = 100;

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfNotCommandLine();
    }

    public function index()
    {
        print 'hello command line user >_<' . PHP_EOL;
    }

    public function processEmbeds()
    {
        $embedly_key = $this->config->item('embedly_api_key');

        if (empty($embedly_key)) {
            print 'ERROR: Please add an embedly API key in order to process embeds.' . PHP_EOL;

            // Send exception
            $this->exceptional->createTrace(E_ERROR, 'No embedly API key configured. Cannot run process to find embeds.', __FILE__, __LINE__);
            exit;
        }

        set_time_limit(0);

        // Get any marks that haven't been run for embeds
        $this->load->model('marks_model', 'mark');
        $marks = $this->mark->read("embed_processed = '0'", self::MAX_EMBEDS_TO_PROCESS, 1, 'mark_id, url');

        if (isset($marks[0]->mark_id)) {
            $this->load->helper('oembed');
            foreach($marks as $k => $mark) {

                // OEmbed check
                // If no embed, check recipes
                $embed = oembed($mark->url, $embedly_key);

                // Set options
                // Set embed processed = 1
                $options                    = array();
                $options['embed_processed'] = 1;

                // If embed is found
                // Set it in options
                if (! empty($embed)) {
                    $options['embed'] = $embed;
                }

                $res = $this->mark->update($mark->mark_id, $options);

            }
        }
    }

}
