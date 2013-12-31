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

$route['sirius'] = "welcome/sirius";

$route['install'] = "install";

$route['users/login'] = "users/login";
$route['users/logout'] = "users/logout";
$route['users/add'] = "users/add";
$route['users/paymentsuccess'] = "users/paymentsuccess";
$route['users/paymentcancelled'] = "users/paymentcancelled";

$route['home'] = "nilai/home";
$route['home/label/(:any)'] = "nilai/bylabel";
$route['home/(:any)'] = "nilai/home";

$route['groups/create'] = "groups/create";
$route['groups/add'] = "groups/add";
$route['groups/update'] = "groups/update";
$route['groups/delete'] = "groups/delete";
$route['groups/invite/(:any)'] = "groups/acceptinvite";
$route['groups/(:any)/edit'] = "groups/edit";
$route['groups/(:any)/members'] = "groups/members";
$route['groups/(:any)/invite_member'] = "groups/invite";
$route['groups/(:any)/leave'] = "groups/leave";
$route['groups/(:any)/remove/(:any)'] = "groups/remove";
$route['groups/(:any)'] = "nilai/bygroup";



$route['help/bookmarklet'] = "welcome/helpbookmarklet";
$route['help/faq'] = "welcome/faq";
$route['help/how'] = "welcome/how";

$route['changelog'] = "welcome/changelog";

$route['marks/add'] = "nilai/add";
$route['marks/addlabel'] = "nilai/addlabel";
$route['marks/addgroup'] = "nilai/addgroup";
$route['marks/savenote'] = "nilai/savenote";
$route['marks/edit/(:num)'] = "nilai/edit";
$route['marks/delete/(:num)'] = "nilai/delete";
$route['marks/search'] = "nilai/search";
$route['marks/archive/(:num)'] = "nilai/archive";
$route['marks/restore/(:num)'] = "nilai/restore";

$route['marks/addsmartlabel'] = "nilai/addsmartlabel";
$route['marks/removesmartlabel'] = "nilai/removesmartlabel";

$route['marks/backprocessOembed'] = "nilai/backprocessOembed";
$route['marks/backprocessRecipes'] = "nilai/backprocessRecipes";

/* End of file routes.php */
/* Location: ./application/config/routes.php */