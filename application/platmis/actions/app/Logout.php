<?php
/**
 * PLATMIS 平台登出
 *
 * @author  wangxuewen<maxranje@aliyun.com>
 * @date    2018-08-27 02:22:20
 */

class Action_Logout extends Zy_BaseWebAction {

    public function invoke () {
        if (0 >= $this->_userInfo['isLogin']) {
            Zy_Common::http_redirect(Zy_Config::getConfig('base_url') . 'platmis/app/login');
        }
        $session = Zy_Session::getInstance();
        $session->unsetSessionInfo('uname');
        $session->unsetSessionInfo('avatar');
        $session->unsetSessionInfo('uid');
        $session->unsetSessionInfo('role');
        
        Zy_Common::http_redirect(Zy_Config::getConfig('base_url') . 'platmis/app/login');
    }
}