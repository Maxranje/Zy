<?php
class Controller_Account extends Zy_Base_Controller{

    public $actions = array(
        'send'          => 'actions/account/send.php',      // 发送短信验证码
        'login'         => 'actions/account/login.php',     // 注册和登陆混合体
        'index'         => 'actions/account/index.php',     // 个人中心主页
        'edit'          => 'actions/account/edit.php',      // 个人中心编辑
    );
}
