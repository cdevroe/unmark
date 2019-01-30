<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags extends Plain_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfLoggedOut();
        parent::redirectIfWebView();

        // Load user marks model
        $this->load->model('tags_model', 'tags');
    }

    public function index($page=1)
    {

        // Figure the correct starting page
        $page = (! is_numeric($page) || $page < 0) ? 1 : $page;

        // Where
        $where = "tags.tag_id > 0";

        // Read the complete user marks records
        $tags = $this->tags->read($where, $this->limit, $page);

        // If no labels, return error
        // Else return labels
        if ($tags === false) {
            $this->data['errors'] = formatErrors(60);
        }
        else {
            $this->data['tags'] = $tags;
            $this->data         = $this->tags->getTotals($where, $page, $this->limit, $this->data);
        }

        // Figure if web or API view
        $this->figureView();
    }

    public function add()
    {

        if (! isset($this->db_clean->name) || empty($this->db_clean->name)) {
            $this->data['errors'] = formatErrors(61);
        }
        else {
            $tag = $this->tags->create(array('name' => $this->db_clean->name));

            if (isset($tag->tag_id)) {
                $this->data['tag'] = $tag;
            }
            elseif ($tag === false) {
                $this->data['errors'] = formatErrors(62);
            }
            else {
                $this->data['errors'] = $tag;
            }
        }

        // Figure view
        $this->figureView();
    }

}