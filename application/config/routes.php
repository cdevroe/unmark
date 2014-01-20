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
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "welcome";
$route['404_override'] = '';

// Installation & Upgrades
$route['install'] = "install";
$route['upgrade'] = "install/upgrade";

// Marks
$route['(api/)?marks/info/(:num)']     = "marks/info/$2";
$route['(api/)?marks/archive/(:num)'] = "marks/archive/$2";
$route['(api/)?marks/restore/(:num)'] = "marks/restore/$2";
$route['(api/)?marks/label(.*?)']     = "marks/label$2";
$route['(api/)?marks(.*?)']           = "marks/index";

// Registration
$route['sirius'] = "welcome/sirius";

// User shiz
$route['users/add'] = "users/add";
$route['users/paymentsuccess'] = "users/paymentsuccess";
$route['users/paymentcancelled'] = "users/paymentcancelled";

// groups
$route['groups/create'] = "groups/create";
$route['groups/add'] = "groups/add";
$route['groups/update'] = "groups/update";
$route['groups/delete'] = "groups/delete";
$route['groups/invite/(:any)/(:any)/reject'] = "groups/rejectinvite";
$route['groups/invite/(:any)/(:any)'] = "groups/acceptinvite";
$route['groups/(:any)/edit'] = "groups/edit";
$route['groups/(:any)/members'] = "groups/members";
$route['groups/(:any)/invite_member'] = "groups/invite";
$route['groups/(:any)/leave'] = "groups/leave";
$route['groups/(:any)/remove/(:any)'] = "groups/remove";
$route['groups/(:any)'] = "nilai/bygroup";

// help
$route['help/bookmarklet'] = "welcome/helpbookmarklet";
$route['help/faq'] = "welcome/faq";
$route['help/how'] = "welcome/how";

// one offs
$route['changelog'] = "welcome/changelog";
$route['terms'] = "welcome/terms";


/* End of file routes.php */
/* Location: ./application/config/routes.php */