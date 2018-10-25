<?php
class Action_PersonalDetail extends Zy_BaseWebAction {
    public function invoke () {
        $arrInput = array(
            'uid'   => isset($this->_userInfo['uid']) ? intval($this->_userInfo['uid']) : 0,
            'act'   => "getpersoninfo",
        );

        $objPs = new Service_App_Personal ();
        $arrOutput = $objPs->execute($arrInput);

        $template = Zy_Template::getInstance();
        $template->assgin(array('result' => $arrOutput)) ;
        $template->display('persional.twig');
    }
}