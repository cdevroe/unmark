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

$route['default_controller']      = 'welcome';
$route['404_override']            = '';

// Installation & Upgrades
$route['install']                = 'install';
$route['upgrade']                = 'install/upgrade';

// Labels (API ONLY)
$route['label/activate(.*?)']    = 'labels/activate$1';
$route['label/add(.*?)']         = 'labels/add';
$route['label/deactivate(.*?)']  = 'labels/deactivate$1';
$route['label/delete(.*?)']      = 'labels/delete$1';
$route['label/edit(.*?)']        = 'labels/edit$1';
$route['label/info(.*?)']        = 'labels/info$1';
$route['labels?(.*?)']           = 'labels/index$1';

// Single Mark Actions
$route['marks?/add(.*?)']        = 'marks/add$1';
$route['mark/archive(.*?)']      = 'marks/archive$1';
$route['mark/edit(.*?)']         = 'marks/edit$1';
$route['mark/info(.*?)']         = 'marks/info$1';
$route['mark/restore(.*?)']      = 'marks/restore$1';

// Plural Marks
$route['marks/archive(.*?)']     = 'marks/index/archive$1';
$route['marks/label(.*?)']       = 'marks/index/label$1';
$route['marks/get(.*?)']         = 'marks/get$1';
$route['marks/search(.*?)']      = 'marks/index/search$1';
$route['marks/tag(.*?)']         = 'marks/index/tag$1';
$route['marks/total(.*?)']       = 'marks/total$1';
$route['marks?/random(.*?)']     = 'marks/random$1';

// Marks catch all
$route['marks?(.*?)']            = 'marks/index$1';

// Tags
$route['tag/add(.*?)']           = 'tags/add';
$route['tags?(.*?)']             = 'tags/index$1';

// Registration
$route['register']               = 'register/index';
$route['register/user']          = 'register/user';

// User methods
$route['user/update/email']      = 'user/updateEmail';
$route['user/update/password']   = 'user/updatePassword';
$route['user(.*?)']              = 'user/index';

// help
$route['help(.*?)']              = 'help$1';

// one offs
$route['changelog']              = 'singletons/changelog';
$route['terms']                  = 'singletons/terms';


/* End of file routes.php */
/* Location: ./application/config/routes.php */