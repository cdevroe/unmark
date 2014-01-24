<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the 'welcome' class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'welcome';
$route['404_override'] = '';

// Installation & Upgrades
$route['install'] = 'install';
$route['upgrade'] = 'install/upgrade';

// Labels (API ONLY)
$route['api/labels?/activate(.*?)']    = 'labels/activate$1';
$route['api/labels?/add(.*?)']         = 'labels/add';
$route['api/labels?/deactivate(.*?)']  = 'labels/deactivate$1';
$route['api/labels?/edit(.*?)']        = 'labels/edit$1';
$route['api/labels?/info(.*?)']        = 'labels/info$1';
$route['api/labels?(.*?)']             = 'labels/index$1';

// Marks
$route['(api/)?mark/edit(.*?)']       = 'marks/edit$2';
$route['(api/)?mark/info(.*?)']       = 'marks/info$2';
$route['(api/)?marks/archive(.*?)']   = 'marks/index/archive$2';
$route['(api/)?mark/archive(.*?)']    = 'marks/archive/$2';
$route['(api/)?mark/restore(.*?)']    = 'marks/restore/$2';
$route['(api/)?marks/label(.*?)']     = 'marks/label$2';
$route['(api/)?marks/get(.*?)']       = 'marks/get$2';
$route['(api/)?marks/total(.*?)']     = 'marks/total$2';
$route['(api/)?marks?(.*?)']          = 'marks/index$2';

// Tags
$route['api/tags?/add(.*?)']         = 'tags/add';
$route['api/tags?(.*?)']             = 'tags/index$1';


// Registration
$route['register']      = 'register/index';
$route['register/user'] = 'register/user';


// help
$route['help(.*?)'] = 'help$1';

// one offs
$route['changelog'] = 'singletons/changelog';
$route['terms']     = 'singletons/terms';


/* End of file routes.php */
/* Location: ./application/config/routes.php */