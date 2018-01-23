<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "view/index";

$route['(:num)'] = "view/index/$1";

$route['tag/(:any)'] = "view/tag/$1";

$route['post/(:any)'] = "view/post/$1";

$route['widget/(:any)'] = "view/widget/$1";

$route['go/(:num)/(:num)'] = "go/out/$1/$2";
$route['scaffolding_trigger'] = "";
