<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return array(
    'database' => [
        'mysqli' => [
            'dsn'   => '',
            'hostname' => '127.0.0.1',
            'hostport' => 3306,
            'username' => 'root',
            'password' => 'max123456',
            'database' => 'zy_platform',
            'dbdriver' => 'mysqli',
            'pconnect' => FALSE,
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE,
            'timeout'  => 10,
        ],
    ],
);

return $zy;
