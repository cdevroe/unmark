<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Router extends CI_Router
{
    function __construct()
    {
        parent::__construct();
        log_message('debug', 'Plain_Router Class Initialized');
    }

    /**
     * Set the route mapping
     *
     * This function determines what should be served based on the URI request,
     * as well as any "routes" that have been set in the routing config file.
     *
     * @access  private
     * @return  void
     */
    function _set_routing()
    {
        // Are query strings enabled in the config file?  Normally CI doesn't utilize query strings
        // since URI segments are more search-engine friendly, but they can optionally be used.
        // If this feature is enabled, we will gather the directory/class/method a little differently
        $segments = array();
        if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
        {
            if (isset($_GET[$this->config->item('directory_trigger')]))
            {
                $this->set_directory(trim($this->uri->_filter_uri($_GET[$this->config->item('directory_trigger')])));
                $segments[] = $this->fetch_directory();
            }

            if (isset($_GET[$this->config->item('controller_trigger')]))
            {
                $this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));
                $segments[] = $this->fetch_class();
            }

            if (isset($_GET[$this->config->item('function_trigger')]))
            {
                $this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
                $segments[] = $this->fetch_method();
            }
        }

        // Load the routes.php file.
        if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
        {
            include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
        }
        elseif (is_file(APPPATH.'config/routes.php'))
        {
            include(APPPATH.'config/routes.php');
        }

        // If routes.php file exists in custom, load it
        $custom_routes = array();
        if (defined('ENVIRONMENT')) {
            array_push($custom_routes, CUSTOMPATH . 'config/' . ENVIRONMENT . '/routes.php');
        }
        array_push($custom_routes, CUSTOMPATH . 'config/routes.php');

        foreach ($custom_routes as $file) {
            if (file_exists($file)) {
                include_once $file;
                break;
            }
        }

        $this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
        unset($route);

        // Set the default controller so we can display it in the event
        // the URI doesn't correlated to a valid controller.
        $this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);

        // Were there any query string segments?  If so, we'll validate them and bail out since we're done.
        if (count($segments) > 0)
        {
            return $this->_validate_request($segments);
        }

        // Fetch the complete URI string
        $this->uri->_fetch_uri_string();

        // Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
        if ($this->uri->uri_string == '')
        {
            return $this->_set_default_controller();
        }

        // Do we need to remove the URL suffix?
        $this->uri->_remove_url_suffix();

        // Compile the segments into an array
        $this->uri->_explode_segments();

        // Parse any custom routing that may exist
        $this->_parse_routes();

        // Re-index the segment array so that it starts with 1 rather than 0
        $this->uri->_reindex_segments();
    }
}