<?php
/**
 * mis平台用户管理界面
 *
 * @author maxranje <maxranje@qq.com>
 */
class Action_AuthDetail extends Zy_BaseWebAction {
    public function invoke () {
        $template = Zy_Template::getInstance();
        $template->assgin(array()) ;
        $template->display("authmis.twig");
    }
}