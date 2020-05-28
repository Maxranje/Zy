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
$system_path    = 'libray/';

define('BASEPATH',  dirname(__FILE__).DIRECTORY_SEPARATOR);
define('SYSPATH',   BASEPATH . $system_path);

require_once SYSPATH . 'autoload/autoload.php';
Zy_Bootstrap::getInstance()->run();