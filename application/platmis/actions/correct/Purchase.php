<?php
/**
 *  批改平台 - 消费记录
 */
class Action_Purchase extends Zy_BaseWebAction {

    public function invoke () {
        $template = Zy_Template::getInstance();
        $template->assgin(array()) ;
        $template->display('purchase.twig');
    }
}