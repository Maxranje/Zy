<?php
/**
 * PLATMIS 平台首页调用
 *
 * @author  wangxuewen<maxranje@aliyun.com>
 * @date    2018-08-27 02:22:20
 */

class Controller_App extends Zy_Controller{

    public $actions = array(
        'index'             => 'actions/app/Index.php',     	// mis后台首页
        'login'             => 'actions/app/Login.php',     	// mis后台登录页
        'logout'            => 'actions/app/Logout.php',     	// mis后台登录页
        'personalDetail'	=> 'actions/app/PersonalDetail.php',		# 个人详情页
    );
}
