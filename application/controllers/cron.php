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
        print 'hello command line user >_<' . PHP_EOL;
    }

    public function processEmbeds()
    {

        set_time_limit(0);

        // Get any marks that haven't been run for embeds
        $this->load->model('marks_model', 'mark');
        $marks = $this->mark->read("embed_processed = '0'", 'all', 1, 'mark_id, url');

        if (isset($marks[0]->mark_id)) {
            $this->load->helper('oembed');
            $this->load->helper('hrecipe');

            // Get all system smart label domains for food & drink
            $this->load->model('labels_model', 'label');
            $label = $this->label->read("labels.slug = 'eat-drink'", 1, 1, 'label_id');

            // Set all eat/drink domains to array
            $recipe_domains = array();
            if (isset($label->label_id) && is_numeric($label->label_id)) {
                $smart_labels = $this->label->read("labels.smart_label_id = '" . $label->label_id . "' AND labels.user_id IS NULL", 'all', 1, 'domain');
                if (isset($smart_labels[0]->domain)) {
                    foreach ($smart_labels as $k => $obj) {
                        array_push($recipe_domains, str_replace('www.', '', strtolower($obj->domain)));
                    }
                }
            }

            foreach($marks as $k => $mark) {

                // OEmbed check
                // If no embed, check recipes
                $embed = oembed($mark->url);

                // parse_url for host
                // Check if in recipe domain list
                // If so, try to find a recipe
                if (empty($embed)) {
                    $domain = str_replace('www.', '', strtolower(parse_url($mark->url,  PHP_URL_HOST)));
                    if (! empty($domain) && in_array($domain, $recipe_domains)) {
                        $embed = parse_hrecipe($mark->url);
                    }
                }

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