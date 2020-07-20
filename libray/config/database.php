<?php
defined('BASEPATH') OR exit('No direct script access allowed');

return array(
    'mysqli' => [
        'dsn'   => '',
        'hostname' => '127.0.0.1',
        'db_port'  => 3306,
        'username' => 'root',
        'password' => 'wang3192161',
        'database' => 'zy',
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
);

return $zy;
