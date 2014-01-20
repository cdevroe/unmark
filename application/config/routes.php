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
$route['api/label/add']         = 'label/add';
$route['api/label/edit/(:num)'] = 'label/edit/$1';
$route['api/label/info(.*?)']   = 'label/info$1';
$route['api/labels(.*?)']       = 'labels/index$1';
$route['api/label(.*?)']        = 'labels/index$1';

// Marks
$route['(api/)?marks/edit/(:num)']     = 'marks/edit/$2';
$route['(api/)?marks/info/(:num)']     = 'marks/info/$2';
$route['(api/)?marks/archive/(:num)']  = 'marks/archive/$2';
$route['(api/)?marks/restore/(:num)']  = 'marks/restore/$2';
$route['(api/)?marks/label(.*?)']      = 'marks/label$2';
$route['(api/)?marks(.*?)']            = 'marks/index';


// Registration
$route['register']      = 'register/index';
$route['register/user'] = 'register/user';


// help
$route['help/bookmarklet'] = 'help/bookmarklet';
$route['help/faq']         = 'help/faq';
$route['help/how']         = 'help/how';

// one offs
$route['changelog'] = 'singletons/changelog';
$route['terms']     = 'singletons/terms';


/* End of file routes.php */
/* Location: ./application/config/routes.php */