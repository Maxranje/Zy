<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$configure = [
    'system' => [
        'language'      => 'en',
        'charset'       => 'UTF-8',
        'log_path'      =>  '/var/log/php/php-fpm.log',
        'serverdns'     => 'http://127.0.0.1/',
        'dbdriver'      => 'mysqli',
    ],
];

$configure = array_merge($configure, require SYSPATH . 'config/database.php');
return $configure;