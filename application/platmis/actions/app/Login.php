<?php
/**
 * PLATMIS 平台登录页
 *
 * @author  wangxuewen<maxranje@aliyun.com>
 * @date    2018-08-27 02:22:20
 */

class Action_Login extends Zy_BaseWebAction {

    public function invoke () {
        if (0 < $this->_userInfo['isLogin']) {
            Zy_Common::http_redirect(Zy_Config::getConfig('base_url') . 'platmis/app/index');
        }

        $arrInput = array(
            'token'  => isset($this->_requestParam['token']) ? $this->_requestParam['token'] : '',
            'phone'  => isset($this->_requestParam['phone']) ? $this->_requestParam['phone'] : '',
            'upass'  => isset($this->_requestParam['upass']) ? $this->_requestParam['upass'] : '',
            'login_time' => time(),
        );

        $objPs = new Service_App_Login ();
        $arrOutput = $objPs->execute($arrInput);

        $template = Zy_Template::getInstance();
        $template->assgin(array('result' => $arrOutput)) ;
        $template->display('login.twig');
    }
}