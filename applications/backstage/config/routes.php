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
$route['default_controller'] = "index";
$route['404_override'] = 'error/error_404';

/* 
 * ------------------------------------------------------------------------------
 *	Admin Stuffs 
 * ------------------------------------------------------------------------------
 */

// User's
$route['users/account'] = "user/account";

$route['users/artists'] = "artist/view";
	$route['users/artists/add'] = "artist/add";
	$route['users/artists/edit/(:num)'] = "artist/edit/$1";
	$route['users/artists/view/(:num)'] = "artist/details/$1";
	
$route['users/assets/search'] = "assets/search";
$route['users/assets/search/(:num)'] = "assets/search/$1";
$route['users/assets/create-track'] = "assets/createtrack";
$route['users/assets/create-track/(:num)'] = "assets/createtrack/$1";
$route['users/assets/create-album'] = "assets/createalbum";
$route['users/assets/edit-album/(:num)'] = "assets/editalbum/$1";

$route['users/songwriters'] = "songwriters/index";
$route['users/welcome/(:num)'] = "user/firsttime/$1";


// Managing users
$route['admin/users'] = "user/s";
$route['admin/users/edit/(:num)'] = "user/account/$1";
$route['admin/users/adddocuments/(:num)'] = "user/adddocuments/$1";

//Viewing Pages
$route['admin/page/view'] = "admin/pages_view/0";
$route['admin/page/view/(:num)'] = "admin/pages_view/$1";

// Editing Pages (no number not allowed)
$route['admin/page/edit/(:num)'] = "admin/pages_edit/$1";

// Creating New Pages
$route['admin/page/new/(:num)'] = "admin/pages_new/$1";

// dealing with Catalogs
$route['admin/catalog/view'] = "catalog/view/$1";
$route['admin/catalog/edit/(:num)'] = "catalog/edit/$1";
$route['admin/catalog/create'] = "catalog/create";
$route['admin/catalog/licenses/(:num)'] = "catalog/licenses/$1";
$route['admin/catalog/removelicenses/(:num)'] = "catalog/removelicenses/$1";
$route['admin/catalog/addlicense'] = "catalog/addlicense";

// Portals
$route['admin/portals/view'] = "portals/view";
$route['admin/portals/create'] = "portals/create";
$route['admin/portals/details/(:num)'] = "portals/details/$1";
$route['admin/portals/licenses/(:num)'] = "portals/licenses/$1";
$route['admin/portals/addlicense'] = "portals/addlicense";
$route['admin/portals/updatelicense'] = "portals/updatelicense";
$route['admin/portals/removelicense/(:num)'] = "portals/removelicense/$1";
$route['admin/portals/catalogs/(:num)'] = "portals/catalogs/$1";
$route['admin/portals/addcatalog'] = "portals/addcatalog";
$route['admin/portals/removecatalog/(:num)'] = "portals/removecatalog/$1";

// Licenses
$route['admin/licenses'] = "licenses";
$route['admin/licenses/view'] = "licenses/view";
$route['admin/licenses/create'] = "licenses/create";
$route['admin/licenses/details/(:num)'] = "licenses/details/$1";
$route['admin/licenses/edit/(:num)'] = "licenses/edit/$1";


$route['search/(:any)'] = "search/index/$1";


/* End of file routes.php */
/* Location: ./application/config/routes.php */
