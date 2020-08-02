<?php
class Controller_Home extends Zy_Base_Controller{

    public $actions = array(
        'school'        => 'actions/home/school.php',      // 校区
        'index'         => 'actions/home/index.php',     
        'plain'         => 'actions/home/plain.php',     
    );
}
