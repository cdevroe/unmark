<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Controller extends CI_Controller
{
        
    public $clean          = null;
    public $csrf_valid     = false;
    public $current_user   = array();
    public $data           = array();
    public $db_clean       = null;
    public $flash_message  = array();
    public $footer         = 'layouts/footer';
    public $header         = 'layouts/header';
    public $html_clean     = null;
    public $is_api         = false;
    public $limit          = 30;
    public $logged_in      = false;
    public $original       = null;
    public $user_admin     = false;
    public $user_id        = 0;
    public $user_token     = 0;
    // Determines if controller tries to load language files
    public $localized      = true;
    // User selected language (overriden in config)
    public $selected_language = null;
    // Supported languages list (overriden in config)
    public $supported_languages = array('english' => 'en_US');

    public function __construct()
    {
        // Call home
        parent::__construct();

        // Start session
        $this->sessionStart();

        // Clean incoming variables in a variety of ways
        $this->clean();
        
        if($this->localized && $this->selected_language === null){
            // Set list of supported languages for the app and pick selected
            $this->getLanguageFromConfig();
        }
        
        // Get user token
        $this->getUserInfo();

        // Generate CSRF token per user
        $this->generateCSRF();

        // Get any flash messages
        $this->getFlashMessages();

    }

    protected function addMark($data=array())
    {
        // Set empty $result
        $result = array();

        // Set default view & redirect
        $view     = null;
        $redirect = null;

        // Figure the url and title
        $url   = (isset($data['url'])) ? $data['url'] : null;
        $title = (isset($data['title'])) ? $data['title'] : null;

        // If url or title are empty
        // error out
        if (empty($url)) {
            return formatErrors(8);
        }

        if (empty($title)) {
            return formatErrors(9);
        }

        // Add mark to marks table
        $this->load->model('marks_model', 'mark');
        $mark = $this->mark->create(array('title' => $title, 'url' => $url));

        // Check ish
        if ($mark === false) {
            $user_mark = formatErrors(6);
        }
        elseif (! isset($mark->mark_id)) {
            $user_mark = $mark;
        }
        else {
            $this->load->model('users_to_marks_model', 'user_marks');
            $user_mark = $this->user_marks->readComplete("users_to_marks.user_id = '" . $this->user_id . "' AND users_to_marks.mark_id = '" . $mark->mark_id . "' AND users_to_marks.active = '1'");

            // Add
            if (! isset($user_mark->mark_id)) {

                // Set default options
                $options = array('user_id' => $this->user_id, 'mark_id' => $mark->mark_id);

                // Label ID (not required), if set and numeric and greater than 0, use it
                if (isset($data['label_id']) && is_numeric($data['label_id']) && $data['label_id'] > 0) {
                    $options['label_id'] = $data['label_id'];
                }

                // Notes (not required)
                if (isset($data['notes']) && ! empty($data['notes'])) {
                    $options['notes'] = $data['notes'];
                    $tags             = getTagsFromHash($options['notes']);
                }

                // Figure if any automatic labels should be applied
                $smart_info = getSmartLabelInfo($url);
                if (isset($smart_info['key']) && ! empty($smart_info['key']) && ! isset($options['label_id'])) {

                    // Load labels model
                    // Sort by user_id DESC (if user has same rule as system, use the user's rule)
                    // Try to extract label
                    $this->load->model('labels_model', 'labels');
                    $this->labels->sort = 'user_id DESC';
                    $label = $this->labels->readComplete("(labels.user_id IS NULL OR labels.user_id = '" . $this->user_id . "') AND labels.smart_key = '" . $smart_info['key'] . "' AND labels.active = '1'", 1);

                    // If a label id is found
                    // Set it to options to save
                    if (isset($label->settings->label->id)) {
                        $options['label_id'] = $label->settings->label->id;
                    }
                }

                // Create the mark
                $user_mark = $this->user_marks->create($options);
            }

            if ($user_mark === false) {
                $user_mark = formatErrors(6);
            }
            elseif (isset($user_mark->mark_id)) {

                // If tags are present, add them
                // Get updated result
                if (isset($tags)) {
                    $mark_id   = $user_mark->mark_id;
                    self::addTags($tags, $mark_id);
                    $user_mark = $this->user_marks->readComplete($mark_id);
                }
            }
        }

        return $user_mark;

    }

    protected function addTags($tags, $mark_id)
    {
        if (! empty($tags) && is_array($tags)) {
            // Update users_to_marks record
            $this->load->model('tags_model', 'tag');
            $this->load->model('user_marks_to_tags_model', 'mark_to_tag');

            $tag_ids = array();
            foreach ($tags as $k => $tag) {
                $tag_name  = trim($tag);
                $slug      = generateSlug($tag);

                if (! empty($slug)) {
                    $tag = $this->tag->read("slug = '" . $slug . "'", 1, 1, 'tag_id');
                    if (! isset($tag->tag_id)) {
                        $tag = $this->tag->create(array('name' => $tag_name, 'slug' => $slug));
                    }

                    // Add tag to mark
                    if (isset($tag->tag_id)) {
                        $res = $this->mark_to_tag->create(array('users_to_mark_id' => $mark_id, 'tag_id' => $tag->tag_id, 'user_id' => $this->user_id));
                    }

                    // Save all tag ids
                    if (isset($res->tag_id)) {
                        array_push($tag_ids, $res->tag_id);
                    }
                }
            }

            // Delete old tags
            $delete_where = (! empty($tag_ids)) ? " AND tag_id <> '" . implode("' AND tag_id <> '", $tag_ids) . "'" : '';
            $delete       = $this->mark_to_tag->delete("users_to_mark_id = '" . $mark_id . "' AND user_id = '" . $this->user_id . "'" . $delete_where);
        }
    }

    // Check if a mark exists for a user
    // If not return false, if so return mark
    protected function checkMark($url)
    {
        $url_key = md5($url);
        $this->load->model('users_to_marks_model', 'user_marks');
        $mark = $this->user_marks->readComplete("users_to_marks.user_id = '" . $this->user_id . "' AND users_to_marks.active = '1'", 1, 1, null, array('url_key' => $url_key));

        if (! isset($mark->mark_id)) {
            return false;
        }

        return $mark;
    }

    // Clean any variables coming in from POST or GET 3 ways
    // We have the originals, clean and db_clean versions accessible
    protected function clean()
    {
        $method            = (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') ? $_POST : $_GET;
        $this->original   = new stdClass;
        $this->clean      = new stdClass;
        $this->db_clean   = new stdClass;
        $this->html_clean = new stdClass;

        if (! empty($method)) {
            foreach ($method as $k => $v) {
                $this->original->{$k}   = $v;
                $v                      = trim(decodeValue($v));
                $this->clean->{$k}      = strip_tags($v);
                $this->db_clean->{$k}   = $this->db->escape_str($this->clean->{$k});
                $this->html_clean->{$k} = $this->db->escape_str(purifyHTML($v));
            }
        }

    }

    public function figureView($view=null, $redirect=null)
    {
        // Sort main data keys from A-Z
        ksort($this->data);

        // If PJAX call, render view
        if (self::isPJAX() === true) {
            $this->data['no_header'] = true;
            $this->data['no_footer'] = true;
            $this->view($view);
        }

        // If api, return JSON
        elseif (self::isAPI() === true || self::isAJAX() === true) {
            $this->renderJSON();
        }

        // Redirect
        elseif (! empty($redirect)) {
            header('Location: ' . $redirect);
            exit;
        }

        // Else return array for view
        // This will change to show view when ready
        else {

            $this->view($view);

        }
    }

    // Check and generate CRSF tokens
    protected function generateCSRF()
    {

        // IF API call, CSRF is not used
        // Set to true
        // All calls will require a user_token to validate instead
        if (self::isAPI() === true || self::isCommandLine() === true || self::isChromeExtension() === true) {
            $this->csrf_valid = true;
        }
        else {

            $csrf_token = $this->session->userdata('csrf_token');
            // If set, validate it
            if (isset($this->clean->csrf_token)) {
                if (! empty($csrf_token)) {
                    $this->csrf_valid = ($csrf_token == $this->clean->csrf_token) ? true : false;
                }

                // If false, set a flash message and data error
                if ($this->csrf_valid === false) {
                    $this->setFlashMessage('We could not locate the correct security token. Please try again.');
                    $this->data['errors'] = formatErrors(600);
                }
            }

            // If not set, set it
            if (empty($csrf_token)) {
                $this->session->set_userdata('csrf_token', generateCSRF());
            }
        }
    }

    protected function getFlashMessages()
    {
        if (isset($this->session)) {
            $flash_message = $this->session->userdata('flash_message');
            if (isset($flash_message['message']) && ! empty($flash_message['message'])) {
                $this->flash_message['type']    = $flash_message['type'];
                $this->flash_message['message'] = $flash_message['message'];
                $this->session->unset_userdata('flash_message');
            }
        }
    }

    protected function getUserInfo()
    {
        // If the request sent the user token, or it's in the session
        // Set it
        $user_session = (isset($this->session)) ? $this->session->userdata('user') : array();

        // Check for user token
        if (isset($this->clean->user_token) || isset($user_session['user_token'])) {
            $this->user_token = (isset($this->clean->user_token)) ? $this->clean->user_token : $user_session['user_token'];
        }

        // If API call, get the user id
        if (self::isAPI() === true && ! empty($this->user_token) && empty($this->user_id)) {
            $this->load->model('users_model', 'user');
            $user = $this->user->read("users.user_token = '" . $this->user_token . "'", 1, 1);
            $this->user_id      = (isset($user->user_id)) ? $user->user_id : $this->user_id;
            $this->user_admin   = (isset($user->admin) && ! empty($user->admin)) ? true : $this->user_admin;
            $this->current_user = $user;
        }

        // User ID & admin
        $this->user_id       = (isset($user_session['user_id']) && ! empty($user_session['user_id'])) ? $user_session['user_id'] : $this->user_id;
        $this->user_admin    = (isset($user_session['admin']) && ! empty($user_session['admin'])) ? true : $this->user_admin;
        $this->logged_in     = (isset($this->session)) ? $this->session->userdata('logged_in') : false;
        $this->current_user  = (! empty($user_session)) ? $user_session : $this->current_user;
    }
    
    protected function isAdmin()
    {
        return $this->user_admin;
    }

    public function isAJAX()
    {
        return $this->input->is_ajax_request();
    }

    public function isAPI()
    {
        return (isset($this->clean->user_token)) ? true : false;
    }

    public function isCommandLine()
    {
        return $this->input->is_cli_request();
    }

    public function isChromeExtension()
    {
        return (isset($_SERVER['HTTP_X_CHROME_EXTENSION'])) ? true : false;
    }

    public function isInternalAJAX()
    {
        return (self::isAJAX() === true && self::isSameHost() === true) ? true : false;
    }

    public function isPJAX()
    {
        return (isset($_SERVER['HTTP_X_PJAX'])) ? true : false;
    }

    protected function isSameHost()
    {
        // Going to execute this better, need to think about it
        $host   = (isset($_SERVER['HTTP_HOST'])) ? strtolower($_SERVER['HTTP_HOST']) : null;
        $origin = (isset($_SERVER['HTTP_REFERER'])) ? strtolower(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) : null;
        $port   = (isset($_SERVER['SERVER_PORT']) && ! empty($_SERVER['SERVER_PORT'])) ? ':' . $_SERVER['SERVER_PORT'] : null;
        return (! empty($origin) && ! empty($host) && ($host == $origin || $host == $origin . $port)) ? true : false;
    }

    public function isWebView()
    {
        return (self::isAJAX() === false && self::isAPI() === false && self::isPJAX() === false && self::isCommandLine() === false) ? true : false;
    }

    // If logged if invalid CSRF token is not valid
    protected function redirectIfInvalidCSRF($url='/')
    {
        if (empty($this->csrf_valid) && self::isAPI() === false) {
            $url = (self::isWebView() === false) ? 'json/auth/error' : $url;
            header('Location: ' . $url);
            exit;
        }
    }

    // If logged in, redirect
    protected function redirectIfLoggedIn($url='/')
    {
        if (! empty($this->logged_in)) {
            header('Location: ' . $url);
            exit;
        }
    }

    // If logged out, redirect
    protected function redirectIfLoggedOut($url='/')
    {
        if (empty($this->logged_in) && empty($this->user_id)) {
            if (self::isWebView() === false) {
                $url = '/json/auth/error';
            }
            else {
                if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/marks/add?') == 0) {
                    $this->session->set_userdata('add_redirect', $_SERVER['REQUEST_URI']);
                }
            }
            header('Location: ' . $url);
            exit;
        }
    }

    // If not an admin, redirect
    protected function redirectIfNotAdmin($url='/')
    {
        if (empty($this->user_admin)) {
            $url = (self::isWebView() === false) ? '/json/auth/error' : $url;
            header('Location: ' . $url);
            exit;
        }
    }

    // If not AJAX call
    protected function redirectIfNotAJAX($url='/')
    {
        if (self::isAJAX() === false) {
            $url = (self::isWebView() === false) ? '/json' : $url;
            header('Location: ' . $url);
            exit;
        }
    }

    // If not an API url
    protected function redirectIfNotAPI($url='/')
    {
        if (self::isAPI() === false) {
            $url = (self::isWebView() === false) ? '/json' : $url;
            header('Location: ' . $url);
            exit;
        }
    }

    // Redirect if not terminal
    protected function redirectIfNotCommandLine()
    {
        if (self::isCommandLine() === false) {
            $url = (self::isWebView() === false) ? '/json' : $url;
            header('Location: /');
            exit;
        }
    }

    // If not a web view or an internal call
    protected function redirectIfNotInternal($url='/')
    {
        if (self::isAPI() === true || (self::isAJAX() === true && self::isInternalAJAX() === false)) {
            $url = (self::isWebView() === false) ? '/json' : $url;
            header('Location: ' . $url);
            exit;
        }
    }

    // If not a web view or an internal call
    protected function redirectIfNotInternalAJAX($url='/')
    {
        if (self::isAPI() === true || self::isWebView() === true || (self::isAJAX() === true && self::isInternalAJAX() === false)) {
            $url = (self::isWebView() === false) ? '/json' : $url;
            header('Location: ' . $url);
            exit;
        }
    }

    // If webview, redirect away
    protected function redirectIfWebView($url='/')
    {
        if (self::isWebView() === true) {
            header('Location: ' . $url);
            exit;
        }
    }

    public function renderJSON()
    {
        $json         = json_encode($this->data, JSON_FORCE_OBJECT);
        $callback     = (isset($this->clean->callback)) ? $this->clean->callback : null;
        $json         = (isset($this->clean->content_type) && strtolower($this->clean->content_type) == 'jsonp') ? $callback . '(' . $json . ');' : $json;
        $content_type = (isset($this->clean->content_type) && strtolower($this->clean->content_type) == 'jsonp') ? 'application/javascript' : 'application/json';
        //$this->data   = array();

        $this->view('json/index', array(
            'json'         => $json,
            'content_type' => $content_type,
            'no_debug'     => true,
            'no_header'    => true,
            'no_footer'    => true
        ));
    }

    // Add user info to session
    protected function sessionAddUser($user)
    {
        // Set logged in
        $this->session->set_userdata('logged_in', true);

        // Set user shiz
        $user_array = array();
        foreach ($user as $k => $v) {
            if ($k != 'password') {
                $user_array[$k] = $v;
            }
        }
        $this->session->set_userdata('user', $user_array);

        // Set user id and token
        $this->user_id    = (isset($user->user_id)) ? $user->user_id : $this->user_id;
        $this->user_token = (isset($user->user_token)) ? $user->user_token : $this->user_token;
    }

    // Clear all session variables and cookies
    protected function sessionClear()
    {
        // Remove phpsessid
        // Remove ci_session (legacy)
        // destroy session
        // set global back to empty array
        $cookie_name = $this->config->item('sess_cookie_name');
        $cookie_name = (empty($cookie_name)) ? 'PHPSESSID' : $cookie_name;
        setcookie($cookie_name, '', time()-31557600, '/', $_SERVER['HTTP_HOST']);
        setcookie('ci_session', '', time()-31557600, '/', $_SERVER['HTTP_HOST']);
        $this->session->sess_destroy();
    }

    // Start session
    protected function sessionStart()
    {
        // If request is not coming from command line & is not an API URL OR it is an internal ajax call, start the session
        if ((self::isCommandLine() === false && self::isAPI() === false) || self::isInternalAJAX() === true) {
            $this->load->library('session');
        }
    }

    protected function setFlashMessage($message, $type='error')
    {
        if (isset($this->session)) {
            $this->session->set_userdata('flash_message', array('type' => $type, 'message' => $message));
        }
    }

    // Process a view
    // This is used so that we can easily add partials to all views
    protected function view($view, $data=array())
    {
        $data                  = array_merge($data, $this->data);
        $data['csrf_token']    = $this->session->userdata('csrf_token');
        $data['flash_message'] = $this->flash_message;
        $data['user']          = $this->session->userdata('user');
        $data['logged_in']     = $this->session->userdata('logged_in');

        // Strip tags from page_title
        if (isset($data['page_title'])) {
            $data['page_title'] = strip_tags($data['page_title']);
        }

        //If there is a header file, load it
        $header = (isset($data['header'])) ? $data['header'] : $this->header;
        if (! isset($data['no_header']) && ! isset($data['json'])) {
            $this->load->view($header, $data);
        }

        //Load main view file
        $this->load->view($view, $data);


        //If there is a footer file, load it
        $footer = (isset($data['footer'])) ? $data['footer'] : $this->footer;
        if (! isset($data['no_footer']) && ! isset($data['json'])) {
            $this->load->view($footer, $data);
        }

        //If the template is asking to debug, load it
        if (isset($this->clean->debug) && $this->user_admin === true) {
            $data['page_data']      = $data;
            $data['session']        = $this->session->all_userdata();
            $this->load->view('partials/debug', $data);
        }
    }
    
    /**
     * Determines language selection based on configuration file
     */
    protected function getLanguageFromConfig(){
        // Get languages
        $this->load->config('all/language');
        $languages = $this->config->item('supported_languages');
        if(empty($languages)){
            $languages = array('english'=>'en_US');
        }
        $this->supported_languages = $languages;
        $languageList = array_values($this->supported_languages);
        // Check if there is any choice
        $langsCount = count($languageList);
        if($langsCount == 0){
            // No languages available
            $this->localized = false;
            $this->selected_language = null;
        } else{
            if($langsCount>1){
                $lang = $this->config->item('default_language');
                $this->selected_language = $languages[$lang];
            } else if($langsCount == 1){
                // No choice - return what's available
                $this->selected_language = $languages[$languageList[0]];
            }
        }
    }

}
