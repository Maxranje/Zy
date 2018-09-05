<?php
/**
 * PLATMIS 平台登录页
 *
 * @author  wangxuewen<maxranje@aliyun.com>
 * @date    2018-08-27 02:22:20
 */

class Action_Index extends Zy_BaseWebAction {

    public function invoke () {
        if (0 >= $this->_userInfo['isLogin']) {
            Zy_Common::http_redirect(Zy_Config::getConfig('base_url') . 'platmis/app/login');
        }

        $arrOutput = array (
            'uname' => $this->_userInfo['uname'],
        );

        $template = Zy_Template::getInstance();
        $template->assgin(array('result' => $arrOutput)) ;
        $template->display('base.twig');
    }
}