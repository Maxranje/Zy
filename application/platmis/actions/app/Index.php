<?php
/**
 * PLATMIS 平台首页调用
 *
 * @author  wangxuewen<maxranje@aliyun.com>
 * @date    2018-08-27 02:22:20
 */

class Action_Index extends Zy_BaseWebAction {

    public function invoke () {
        // 没有登录直接跳转到登录页面
        if ( 0 >= $this->_userInfo['isLogin'] ) {
            Zy_Common::http_redirect(Zy_Config::getConfig('base_url') . "app/login", 301);
        }

        $template = Zy_Template::getInstance();
        $template->assgin(array('result' => array('uname'=>'maxranje'))) ;
        $template->display('base.twig');
    }
}