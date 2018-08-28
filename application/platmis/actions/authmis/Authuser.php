<?php
/**
 * 权限管理中用户权限管理
 */
class Action_Authuser extends Zy_BaseWebAction {

    public function invoke () {
        $template = Zy_Template::getInstance();
        $template->assgin(array('result' => array('uname'=>'maxranje'))) ;
        $template->display('authuser.twig');
    }
}