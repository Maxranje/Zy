<?php
define('APP_NAME',  'napi');
define('ENV',       'production');

# init error reporting by env
switch (ENV) {
	case 'development':
		ini_set('display_errors', 1);
		error_reporting(-1);
		break;

	case 'production':
		ini_set('display_errors', 0);
		error_reporting (E_ERROR & E_USER_WARNING);
		break;

	default:
		exit(1);
}

# set file path
define('BASEPATH',  dirname(__FILE__).DIRECTORY_SEPARATOR);
define('SYSPATH',   BASEPATH . 'libray/');

require_once SYSPATH . 'autoload/autoload.php';
Zy_Base_Bootstrap::getInstance()->run();