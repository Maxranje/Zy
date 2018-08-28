<?php
define('APP_NAME',  'platmis');
define('ENV',       'production');

# init error reporting by env
// switch (ENV) {
// 	case 'development':
// 		ini_set('display_errors', 1);
// 		error_reporting(-1);
// 		break;

// 	case 'production':
// 		ini_set('display_errors', 0);
// 		error_reporting (E_ERROR & E_USER_WARNING);
// 		break;

// 	default:
// 		exit(1);
// }

# set file path
$application_path   = 'application/' . APP_NAME;
$template_path      = 'webroot/' . APP_NAME;
$system_path 		= 'zy/';

define('APPPATH',   realpath($application_path).DIRECTORY_SEPARATOR);
define('VIEWPATH',  realpath($template_path).DIRECTORY_SEPARATOR);
define('SYSPATH',   realpath($system_path).DIRECTORY_SEPARATOR);
define('BASEPATH',  dirname(__FILE__).DIRECTORY_SEPARATOR);

require_once SYSPATH . 'autoload/autoload.php';
Zy_Bootstrap::getInstance()->run();