<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = 'home/not_found';
$route['translate_uri_dashes'] = FALSE;


// Image Viewer
// $route['view_image/(:any)'] = 'view_image/index/$1';


// Alerts
$route['alert'] = 'alert/index';

// Search
$route['search'] = 'search/index';
$route['search/latest'] = 'search/latest';
$route['search/latest/(:num)'] = 'search/latest/$1';
$route['search/(:any)'] = 'search/index/$1';
$route['search/(:any)/(:num)'] = 'search/index/$1';

$route['tag'] = 'search/tag';
$route['tag/latest'] = 'search/latest';
$route['tag/latest/(:num)'] = 'search/latest/$1';
$route['tag/(:any)'] = 'search/tag/$1';
$route['tag/(:any)/(:num)'] = 'search/tag/$1';

// Home
$route['all/(:any)'] = 'all/index/$1';
$route['all/(:any)/(:num)'] = 'all/index/$1/$2';

// Profile
$route['user/(:any)'] = 'profile/user/$1';
$route['profile/upload_profile_picture'] = 'profile/upload_profile_picture';
$route['profile/upload_profile_cover'] = 'profile/upload_profile_cover';
$route['profile/(:any)'] = 'profile/index/$1';

// Post
$route['like'] = 'post/add_to_likes';
$route['create_comment'] = 'post/create_comment';
$route['download_chapter/(:num)'] = 'post/download_chapter/$1';
$route['download/(:num)'] = 'post/download/$1';
$route['(:any)/(:num)'] = 'post/index/$1/$2';
$route['(:any)/(:num)/chapter/(:num)'] = 'post/chapter/$1/$2/$3';